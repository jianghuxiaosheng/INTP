<?php
/**
 * INTP 主题
 *
 * @package INTP
 * @author imoyy
 * @link https://github.com/imoyy
 * @version 1.0.0
 * @description 极简双栏 Typecho 主题 · 侧边栏可左可右 · Tailwind CSS + shadcn-ui 风格 · IconPark 图标 · 代码高亮 · 时间轴归档
 */

if (!defined('__TYPECHO_ROOT_DIR__')) exit;

/* ==================== 后台主题设置 ==================== */
function themeConfig($form) {

    $sidebarPos = new \Typecho\Widget\Helper\Form\Element\Select('sidebar_position',
        array('right' => '右侧', 'left' => '左侧'),
        'right', _t('侧边栏位置'), _t('选择侧边栏显示在右侧还是左侧。'));
    $form->addInput($sidebarPos);

    $widgetRegistry = intpSidebarRegistry();
    $widgetEnabled = new \Typecho\Widget\Helper\Form\Element\Checkbox('side_widgets',
        $widgetRegistry, array_keys($widgetRegistry),
        _t('侧边栏组件'), _t('勾选需要显示在侧边栏的组件；章节目录仅在文章页出现，热门文章需同时开启下方「阅读量统计」，友情链接需先填写链接列表，自定义组件需填写 HTML 内容。'));
    $form->addInput($widgetEnabled);

    $widgetOrder = new \Typecho\Widget\Helper\Form\Element\Text('side_order', NULL,
        implode(',', array_keys($widgetRegistry)),
        _t('组件排序'), _t('按从上到下的顺序填写组件标识，用英文逗号分隔：blog,color,recent,popular,comments,category,tags,archive,tools,links,toc,custom。未列出的组件自动排到末尾。'));
    $form->addInput($widgetOrder);

    $sideCustom = new \Typecho\Widget\Helper\Form\Element\Textarea('side_custom', NULL, '',
        _t('自定义侧边栏'), _t('「自定义」组件的内容，支持原始 HTML（该区域仅管理员可编辑，请勿粘贴不可信代码），留空则不显示。'));
    $form->addInput($sideCustom);

    $authorName = new \Typecho\Widget\Helper\Form\Element\Text('author_name', NULL, '你好呀',
        _t('侧栏昵称'), _t('侧栏「关于我」中显示的名字。'));
    $form->addInput($authorName);

    $avatarUrl = new \Typecho\Widget\Helper\Form\Element\Text('avatar_url', NULL, '',
        _t('头像地址'), _t('留空则显示默认占位头像。'));
    $form->addInput($avatarUrl);

    $githubUrl = new \Typecho\Widget\Helper\Form\Element\Text('github_url', NULL, '',
        _t('GitHub 链接'), _t('显示在「关于我」下方，留空则不显示。'));
    $form->addInput($githubUrl);

    $siteIntro = new \Typecho\Widget\Helper\Form\Element\Text('site_intro', NULL, '记录生活，也记录折腾。',
        _t('站点标语'), _t('显示在页首。'));
    $form->addInput($siteIntro);

    $siteDesc = new \Typecho\Widget\Helper\Form\Element\Text('site_desc', NULL,
        '写代码、玩硬件、读书、骑行，把值得记住的日子都留在这个小角落。',
        _t('站点描述'), _t('显示在标语下方。'));
    $form->addInput($siteDesc);

    $siteSince = new \Typecho\Widget\Helper\Form\Element\Text('site_since', NULL, '2024',
        _t('建站年份'), _t('显示在页首元信息行（since）。'));
    $form->addInput($siteSince);

    $rssShow = new \Typecho\Widget\Helper\Form\Element\Radio('rss_show',
        array('1' => '显示', '0' => '隐藏'), '1', _t('RSS 订阅'), _t('是否在页脚显示 RSS 链接。'));
    $form->addInput($rssShow);

    $listLayout = new \Typecho\Widget\Helper\Form\Element\Select('list_layout',
        array('small' => '小图模式（缩略图在左）', 'big' => '大图模式（头图置顶）', 'full' => '全文模式（直接显示全文）'),
        'small', _t('文章列表排版'), _t('默认排版方式。每篇文章可通过自定义字段 list_layout（big/small/full）单独覆盖。'));
    $form->addInput($listLayout);

    $thumbSource = new \Typecho\Widget\Helper\Form\Element\Select('thumb_source',
        array('first' => '自动抓取正文第一张图片', 'random' => '随机图片池', 'manual' => '仅使用手动指定的图片'),
        'first', _t('头图来源'), _t('文章可通过自定义字段 thumb 指定图片 URL，优先级最高；正文首图策略在无图时会回退到随机图片池。'));
    $form->addInput($thumbSource);

    $thumbPool = new \Typecho\Widget\Helper\Form\Element\Textarea('thumb_pool', NULL, '',
        _t('随机头图池'), _t('每行填写一个图片 URL，作为没有头图的文章的随机配图。'));
    $form->addInput($thumbPool);

    $postThumb = new \Typecho\Widget\Helper\Form\Element\Radio('post_thumb',
        array('1' => '显示', '0' => '隐藏'), '1', _t('文章页头图'), _t('是否在文章页标题下方显示头图。'));
    $form->addInput($postThumb);

    $showViews = new \Typecho\Widget\Helper\Form\Element\Radio('show_views',
        array('1' => '显示', '0' => '隐藏'), '1', _t('阅读量统计'), _t('文章页显示阅读量，侧边栏显示热门文章。同一访客 30 天内重复访问不重复计数。'));
    $form->addInput($showViews);

    $showLikes = new \Typecho\Widget\Helper\Form\Element\Radio('show_likes',
        array('1' => '显示', '0' => '隐藏'), '1', _t('点赞功能'), _t('文章页显示点赞按钮，同一访客仅可点赞一次。'));
    $form->addInput($showLikes);

    $showSticky = new \Typecho\Widget\Helper\Form\Element\Radio('show_sticky',
        array('1' => '显示', '0' => '隐藏'), '1', _t('文章置顶'), _t('给文章添加自定义字段 sticky（任意非空值）即可在首页第一页顶部置顶显示。'));
    $form->addInput($showSticky);

    $showPjax = new \Typecho\Widget\Helper\Form\Element\Radio('show_pjax',
        array('1' => '开启', '0' => '关闭'), '1', _t('PJAX 无刷新跳转'), _t('站内链接无刷新切换页面，更新标题与地址并重绑定交互脚本，阅读量统计正常累加。'));
    $form->addInput($showPjax);

    $showSw = new \Typecho\Widget\Helper\Form\Element\Radio('show_sw',
        array('1' => '开启', '0' => '关闭'), '1', _t('Service Worker 缓存'), _t('缓存静态资源与最近访问页面，支持离线阅读；页面访问始终优先获取最新内容，不影响阅读量统计。'));
    $form->addInput($showSw);

    $gravatarSource = new \Typecho\Widget\Helper\Form\Element\Select('gravatar_source',
        array(
            'weavatar' => 'WeAvatar（国内推荐）',
            'gravatar' => 'Gravatar 官方',
            'cravatar' => 'Cravatar 国内镜像',
            'loli'     => 'gravatar.loli.net 镜像',
            'custom'   => '自定义源',
        ), 'weavatar', _t('评论头像源'), _t('评论者 QQ 数字邮箱（如 10000@qq.com）自动显示 QQ 头像，其余邮箱使用所选 Gravatar 兼容源。'));
    $form->addInput($gravatarSource);

    $gravatarCustom = new \Typecho\Widget\Helper\Form\Element\Text('gravatar_custom', NULL, '',
        _t('自定义头像源地址'), _t('头像源选择「自定义源」时生效，填写形如 https://example.com/avatar/ 的地址。'));
    $form->addInput($gravatarCustom);

    $linksList = new \Typecho\Widget\Helper\Form\Element\Textarea('links_list', NULL, '',
        _t('友情链接'), _t('每行一个，格式：站点名称 | 网址 | 简介（简介可省略）。用于「友情链接」独立页面模板。'));
    $form->addInput($linksList);

    $githubRepos = new \Typecho\Widget\Helper\Form\Element\Textarea('github_repos', NULL, '',
        _t('GitHub 展示仓库'), _t('每行填写 owner/repo 或仓库完整地址；留空则自动抓取 GitHub 主页中最近更新的公开仓库。用于「GitHub 项目」独立页面模板。'));
    $form->addInput($githubRepos);
}

/* ==================== 公共函数 ==================== */

/** 侧栏位置 → body class（layout-right / layout-left） */
function themeSidebarClass() {
    $pos = \Typecho\Widget::widget('Widget_Options')->sidebar_position;
    return $pos === 'left' ? 'layout-left' : 'layout-right';
}

/* ==================== 侧边栏组件系统（开关 + 排序） ==================== */

/** 可显示在侧边栏的全部组件：标识 => 名称（顺序即默认排序） */
function intpSidebarRegistry() {
    return array(
        'blog'     => _t('博客信息'),
        'color'    => _t('主题配色'),
        'recent'   => _t('最新文章'),
        'popular'  => _t('热门文章'),
        'comments' => _t('最新回复'),
        'category' => _t('文章分类'),
        'tags'     => _t('标签云'),
        'archive'  => _t('文章归档'),
        'tools'    => _t('其它功能'),
        'links'    => _t('友情链接'),
        'toc'      => _t('章节目录'),
        'custom'   => _t('自定义'),
    );
}

/** 已开启的组件标识列表（后台未配置时默认全开） */
function intpSidebarEnabled($key = NULL) {
    static $enabled = NULL;
    if (NULL === $enabled) {
        $defaults = array_keys(intpSidebarRegistry());
        $stored = \Typecho\Widget::widget('Widget_Options')->side_widgets;
        if (is_array($stored)) {
            $enabled = array_values(array_intersect(array_map('strval', $stored), $defaults));
        } else {
            $enabled = $defaults;
        }
    }
    return NULL === $key ? $enabled : in_array($key, $enabled, true);
}

/** 组件从上到下的展示顺序（后台排序串非法或缺少时按注册表顺序兜底） */
function intpSidebarOrder() {
    static $order = NULL;
    if (NULL !== $order) {
        return $order;
    }
    $registry = array_keys(intpSidebarRegistry());
    $order = array();
    foreach (explode(',', (string)\Typecho\Widget::widget('Widget_Options')->side_order) as $k) {
        $k = trim($k);
        if ('' !== $k && in_array($k, $registry, true) && !in_array($k, $order, true)) {
            $order[] = $k;
        }
    }
    foreach ($registry as $k) {
        if (!in_array($k, $order, true)) {
            $order[] = $k;
        }
    }
    return $order;
}

/** 侧边栏入口：按后台开关与顺序依次渲染组件 */
function intpSidebarRender($archive) {
    foreach (intpSidebarOrder() as $key) {
        if (!intpSidebarEnabled($key)) {
            continue;
        }
        switch ($key) {
            case 'blog':
                intpSideBlogCard();
                break;
            case 'color':
                intpSideColorCard();
                break;
            case 'recent':
                intpSideRecentCard();
                break;
            case 'popular':
                intpSidePopularCard();
                break;
            case 'comments':
                intpSideCommentsCard();
                break;
            case 'category':
                intpSideCategoryCard();
                break;
            case 'tags':
                intpSideTagsCard();
                break;
            case 'archive':
                intpSideArchiveCard();
                break;
            case 'tools':
                intpSideToolsCard();
                break;
            case 'links':
                intpSideLinksCard();
                break;
            case 'toc':
                if ($archive->is('post')) {
                    intpTocCard($archive);
                }
                break;
            case 'custom':
                intpSideCustomCard();
                break;
        }
    }
}

/** 博客信息 */
function intpSideBlogCard() {
    $opts = \Typecho\Widget::widget('Widget_Options');
    echo '<div class="side-card"><div class="about-box">';
    echo '<img class="about-avatar" src="';
    themeAvatar();
    echo '" alt="avatar"><div>';
    echo '<p class="about-name">' . intpScH($opts->author_name ? $opts->author_name : $opts->title) . '</p>';
    echo '<p class="about-desc">' . intpScH($opts->site_desc) . '</p>';
    echo '</div></div>';
    $githubUrl = intpScUrl($opts->github_url);
    if ('' !== $githubUrl) {
        echo '<div style="margin-top:0.75rem"><a class="post-more" href="' . intpScH($githubUrl)
            . '" rel="external nofollow" target="_blank">'
            . '<svg width="15" height="15" viewBox="0 0 48 48" fill="none" xmlns="http://www.w3.org/2000/svg" style="vertical-align:-2px;margin-right:4px" aria-hidden="true"><path d="M24 8a16 16 0 0 0-5.06 31.18c.8.15 1.1-.35 1.1-.77v-2.7c-4.47.97-5.42-2.16-5.42-2.16-.73-1.86-1.78-2.36-1.78-2.36-1.46-1 .1-.97.1-.97 1.6.1 2.45 1.66 2.45 1.66 1.43 2.45 3.75 1.74 4.66 1.33.14-1.04.56-1.74 1.02-2.14-3.56-.4-7.3-1.78-7.3-7.92 0-1.75.63-3.18 1.66-4.3-.17-.4-.72-2.03.16-4.24 0 0 1.35-.43 4.42 1.64a15.4 15.4 0 0 1 8.05 0c3.07-2.07 4.42-1.64 4.42-1.64.88 2.21.33 3.84.16 4.24 1.03 1.12 1.66 2.55 1.66 4.3 0 6.16-3.75 7.51-7.32 7.9.57.5 1.08 1.47 1.08 2.97v4.4c0 .43.3.93 1.1.77A16 16 0 0 0 24 8Z" fill="currentColor"/></svg>GitHub</a></div>';
    }
    echo '</div>';
}

/** 主题配色 */
function intpSideColorCard() {
    $colors = array(
        array('blue',   '#1b6ef3', '蓝色'),
        array('green',  '#16a34a', '绿色'),
        array('purple', '#7c3aed', '紫色'),
        array('red',    '#dc2626', '红色'),
        array('orange', '#ea580c', '橙色'),
        array('cyan',   '#0891b2', '青色'),
        array('pink',   '#db2777', '粉色'),
    );
    echo '<div class="side-card"><h3>' . _t('主题配色') . '</h3><div class="color-swatches">';
    foreach ($colors as $c) {
        echo '<button type="button" class="color-dot" data-accent="' . $c[0]
            . '" style="--dot:' . $c[1] . '" aria-label="' . $c[2] . '" title="' . $c[2] . '"></button>';
    }
    echo '</div></div>';
}

/** 最新文章 */
function intpSideRecentCard() {
    \Widget\Contents\Post\Recent::alloc()->to($recentPosts);
    echo '<div class="side-card"><h3>' . _t('最新文章') . '</h3><ul class="side-list">';
    while ($recentPosts->next()) {
        echo '<li><a href="' . htmlspecialchars($recentPosts->permalink, ENT_QUOTES, 'UTF-8') . '">'
            . htmlspecialchars($recentPosts->title, ENT_QUOTES, 'UTF-8')
            . '</a><span class="num">';
        $recentPosts->date('m.d');
        echo '</span></li>';
    }
    echo '</ul></div>';
}

/** 热门文章（依赖阅读量统计） */
function intpSidePopularCard() {
    if ('0' === (string)\Typecho\Widget::widget('Widget_Options')->show_views) {
        return;
    }
    $popularPosts = intpPopularPosts(5);
    if (!$popularPosts) {
        return;
    }
    echo '<div class="side-card"><h3>' . _t('热门文章') . '</h3><ul class="side-list side-hot">';
    foreach ($popularPosts as $hotIndex => $hotPost) {
        echo '<li><span class="hot-rank">' . ($hotIndex + 1) . '</span><a href="'
            . htmlspecialchars($hotPost['permalink'], ENT_QUOTES, 'UTF-8') . '">'
            . htmlspecialchars($hotPost['title'], ENT_QUOTES, 'UTF-8')
            . '</a><span class="num">' . (int)$hotPost['views'] . '</span></li>';
    }
    echo '</ul></div>';
}

/** 最新回复 */
function intpSideCommentsCard() {
    \Widget\Comments\Recent::alloc(array('pageSize' => 4))->to($recentComments);
    echo '<div class="side-card"><h3>' . _t('最新回复') . '</h3><ul class="side-comments">';
    while ($recentComments->next()) {
        echo '<li><div class="sc-head">';
        echo themeCommentAvatar($recentComments->mail, 24, 'sc-avatar');
        echo '<span class="sc-name">';
        $recentComments->author();
        echo '</span><span class="sc-date">';
        $recentComments->date('m.d');
        echo '</span></div>';
        echo '<p class="sc-text">';
        $recentComments->excerpt(60);
        echo '</p>';
        echo '<a class="sc-link" href="' . htmlspecialchars($recentComments->permalink, ENT_QUOTES, 'UTF-8')
            . '">→ 在《' . htmlspecialchars($recentComments->title, ENT_QUOTES, 'UTF-8') . '》下评论</a></li>';
    }
    echo '</ul></div>';
}

/** 文章分类 */
function intpSideCategoryCard() {
    \Widget\Metas\Category\Rows::alloc()->to($sideCats);
    echo '<div class="side-card"><h3>' . _t('文章分类') . '</h3><ul class="side-list">';
    while ($sideCats->next()) {
        echo '<li><a href="' . htmlspecialchars($sideCats->permalink, ENT_QUOTES, 'UTF-8') . '">'
            . htmlspecialchars($sideCats->name, ENT_QUOTES, 'UTF-8')
            . '</a><span class="num">' . (int)$sideCats->count . '</span></li>';
    }
    echo '</ul></div>';
}

/** 标签云 */
function intpSideTagsCard() {
    \Widget\Metas\Tag\Cloud::alloc(array(
        'sort' => 'count', 'ignoreZeroCount' => true, 'desc' => true, 'limit' => 24,
    ))->to($sideTags);
    echo '<div class="side-card"><h3>' . _t('标签云') . '</h3><div class="tag-flow">';
    $tagIndex = 0;
    while ($sideTags->next()) {
        if ($tagIndex++ > 0) {
            echo ' · ';
        }
        echo '<a href="' . htmlspecialchars($sideTags->permalink, ENT_QUOTES, 'UTF-8') . '">'
            . htmlspecialchars($sideTags->name, ENT_QUOTES, 'UTF-8') . '</a>';
    }
    echo '</div></div>';
}

/** 文章归档（按月） */
function intpSideArchiveCard() {
    \Widget\Contents\Post\Date::alloc('type=month&format=Y 年 n 月&limit=12')->to($monthList);
    $html = '';
    while ($monthList->next()) {
        $html .= '<li><a href="' . htmlspecialchars($monthList->permalink, ENT_QUOTES, 'UTF-8') . '">'
            . htmlspecialchars($monthList->date, ENT_QUOTES, 'UTF-8')
            . '</a><span class="num">' . (int)$monthList->count . '</span></li>';
    }
    if ('' === $html) {
        return;
    }
    echo '<div class="side-card"><h3>' . _t('文章归档') . '</h3><ul class="side-list">' . $html . '</ul></div>';
}

/** 其它功能（登录/管理、RSS） */
function intpSideToolsCard() {
    $opts = \Typecho\Widget::widget('Widget_Options');
    $user = \Widget\User::alloc();
    echo '<div class="side-card"><h3>' . _t('其它功能') . '</h3><ul class="side-list">';
    echo '<li><a href="' . htmlspecialchars($opts->adminUrl(NULL, true), ENT_QUOTES, 'UTF-8') . '">'
        . ($user->hasLogin() ? _t('管理后台') : _t('登录'))
        . '</a><span class="num">→</span></li>';
    if ('0' !== (string)$opts->rss_show) {
        echo '<li><a href="' . htmlspecialchars($opts->feedUrl, ENT_QUOTES, 'UTF-8') . '">'
            . _t('RSS 订阅') . '</a><span class="num">→</span></li>';
    }
    echo '</ul></div>';
}

/** 友情链接 */
function intpSideLinksCard() {
    $links = intpLinks();
    if (!$links) {
        return;
    }
    echo '<div class="side-card"><h3>' . _t('友情链接') . '</h3><ul class="side-list side-links">';
    foreach ($links as $link) {
        echo '<li><a href="' . htmlspecialchars($link['url'], ENT_QUOTES, 'UTF-8')
            . '" target="_blank" rel="external nofollow"'
            . ('' !== $link['desc'] ? ' title="' . htmlspecialchars($link['desc'], ENT_QUOTES, 'UTF-8') . '"' : '')
            . '>' . htmlspecialchars($link['name'], ENT_QUOTES, 'UTF-8') . '</a></li>';
    }
    echo '</ul></div>';
}

/** 自定义 HTML（仅管理员可配置，原样输出） */
function intpSideCustomCard() {
    $html = trim((string)\Typecho\Widget::widget('Widget_Options')->side_custom);
    if ('' === $html) {
        return;
    }
    echo '<div class="side-card side-custom">' . $html . '</div>';
}

/** 站点统计（文章 / 分类 / 标签数量） */
function themeStat() {
    static $stat = NULL;
    if (NULL === $stat) {
        $stat = \Widget\Stat::alloc();
    }
    return $stat;
}

/** 安全的图片地址：仅允许 http(s)、协议相对、站内绝对路径或 data:image/* */
function intpSafeImgUrl($url) {
    $url = trim((string)$url);
    if ('' === $url) {
        return '';
    }
    if (preg_match('#^(?:https?:)?//#i', $url) || 0 === strpos($url, '/')
        || preg_match('#^data:image/[a-zA-Z0-9.+-]+[;,]#', $url)) {
        return $url;
    }
    return '';
}

/** 默认头像占位图（data URI） */
function intpFallbackAvatar() {
    return 'data:image/svg+xml;charset=utf-8,' . rawurlencode('<svg xmlns="http://www.w3.org/2000/svg" width="96" height="96" viewBox="0 0 96 96"><rect width="96" height="96" fill="#e4edff"/><text x="48" y="62" font-size="42" text-anchor="middle" fill="#1b6ef3" font-family="sans-serif">我</text></svg>');
}

/** 输出头像地址（优先后台配置，否则默认占位图） */
function themeAvatar() {
    $options = \Typecho\Widget::widget('Widget_Options');
    echo intpScH(intpSafeImgUrl($options->avatar_url) ?: intpFallbackAvatar());
}

/**
 * 评论者头像地址
 * QQ 数字邮箱走 Q 头像，其余邮箱按主题设置的 Gravatar 兼容源生成；无邮箱返回空串。
 */
function intpAvatarUrl($mail, $size = 40) {
    $mail = strtolower(trim((string)$mail));
    if ('' === $mail) {
        return '';
    }
    if (preg_match('/^(\d{5,12})@qq\.com$/', $mail, $m)) {
        return 'https://q1.qlogo.cn/g?b=qq&nk=' . $m[1] . '&s=' . (int)$size;
    }

    $options = \Typecho\Widget::widget('Widget_Options');
    $source = $options->gravatar_source ? (string)$options->gravatar_source : 'weavatar';
    switch ($source) {
        case 'gravatar':
            $base = 'https://www.gravatar.com/avatar/';
            break;
        case 'cravatar':
            $base = 'https://cravatar.cn/avatar/';
            break;
        case 'loli':
            $base = 'https://gravatar.loli.net/avatar/';
            break;
        case 'custom':
            $base = trim((string)$options->gravatar_custom);
            // 自定义源必须是 http(s) 地址或协议相对地址，否则回退默认源
            if (!preg_match('#^(?:https?:)?//#i', $base)) {
                $base = 'https://weavatar.com/avatar/';
            }
            break;
        default:
            $base = 'https://weavatar.com/avatar/';
    }

    return rtrim($base, '/') . '/' . md5($mail) . '?s=' . (int)$size . '&r=G&d=mp';
}

/** 评论者头像 img 标签（无邮箱返回空） */
function themeCommentAvatar($mail, $size = 40, $class = 'c-avatar') {
    $url = intpSafeImgUrl(intpAvatarUrl($mail, $size));
    if ('' === $url) {
        return '';
    }
    return '<img class="' . intpScH($class) . '" src="' . intpScH($url)
        . '" alt="avatar" width="' . (int)$size . '" height="' . (int)$size . '" loading="lazy" />';
}

/* ==================== SEO 增量标签 ==================== */

/** 输出 JSON-LD 脚本块 */
function intpJsonLd(array $data) {
    echo '<script type="application/ld+json">'
        . json_encode(
            $data,
            JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES
            | JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT
        )
        . "</script>\n";
}

/**
 * SEO 增量输出（核心 $this->header() 已输出 canonical / description / 基础 OG / Twitter，
 * 这里只补充 og:image、article:* 与 JSON-LD，避免重复标签）。
 */
function intpSeoExtra($widget) {
    $options = \Typecho\Widget::widget('Widget_Options');
    $siteUrl = rtrim((string)$options->siteUrl, '/');

    if ($widget->is('single')) {
        $thumb = intpThumb($widget);
        if ('' !== $thumb) {
            if (0 === strpos($thumb, '//')) {
                $thumb = 'https:' . $thumb;
            }
            echo '<meta property="og:image" content="' . htmlspecialchars($thumb, ENT_QUOTES, 'UTF-8') . "\">\n";
            echo '<meta name="twitter:image" content="' . htmlspecialchars($thumb, ENT_QUOTES, 'UTF-8') . "\">\n";
        }

        $desc = mb_substr(trim(strip_tags((string)$widget->text)), 0, 160, 'UTF-8');

        if ($widget->is('post')) {
            echo '<meta property="article:published_time" content="' . date('c', (int)$widget->created) . "\">\n";
            if ((int)$widget->modified !== (int)$widget->created) {
                echo '<meta property="article:modified_time" content="' . date('c', (int)$widget->modified) . "\">\n";
            }
            echo '<meta property="article:author" content="' . htmlspecialchars($widget->author->screenName, ENT_QUOTES, 'UTF-8') . "\">\n";

            $categories = (array)$widget->categories;
            $crumbs = array(
                array('首页', $siteUrl . '/'),
            );
            if (!empty($categories)) {
                $firstCat = $categories[0];
                echo '<meta property="article:section" content="' . htmlspecialchars($firstCat['name'], ENT_QUOTES, 'UTF-8') . "\">\n";
                $crumbs[] = array($firstCat['name'], $firstCat['permalink']);
            }
            $tags = $widget->tags;
            if (!empty($tags) && is_array($tags)) {
                foreach ($tags as $tag) {
                    echo '<meta property="article:tag" content="' . htmlspecialchars($tag['name'], ENT_QUOTES, 'UTF-8') . "\">\n";
                }
            }
            $crumbs[] = array($widget->title, $widget->permalink);

            $article = array(
                '@context' => 'https://schema.org',
                '@type'    => 'Article',
                'headline' => $widget->title,
                'mainEntityOfPage' => $widget->permalink,
                'url'      => $widget->permalink,
                'datePublished' => date('c', (int)$widget->created),
                'dateModified'  => date('c', (int)$widget->modified),
                'author'   => array('@type' => 'Person', 'name' => $widget->author->screenName),
                'publisher' => array('@type' => 'Organization', 'name' => $options->title),
            );
            if ('' !== $desc) {
                $article['description'] = $desc;
            }
            if ('' !== $thumb) {
                $article['image'] = $thumb;
            }
            intpJsonLd($article);

            $itemList = array();
            $pos = 1;
            foreach ($crumbs as $crumb) {
                $itemList[] = array(
                    '@type' => 'ListItem',
                    'position' => $pos++,
                    'name' => $crumb[0],
                    'item' => $crumb[1],
                );
            }
            intpJsonLd(array(
                '@context' => 'https://schema.org',
                '@type'    => 'BreadcrumbList',
                'itemListElement' => $itemList,
            ));
        } else {
            $page = array(
                '@context' => 'https://schema.org',
                '@type'    => 'WebPage',
                'name'     => $widget->title,
                'url'      => $widget->permalink,
                'datePublished' => date('c', (int)$widget->created),
                'dateModified'  => date('c', (int)$widget->modified),
            );
            if ('' !== $desc) {
                $page['description'] = $desc;
            }
            if ('' !== $thumb) {
                $page['image'] = $thumb;
            }
            intpJsonLd($page);
        }
    } elseif ($widget->is('index')) {
        intpJsonLd(array(
            '@context' => 'https://schema.org',
            '@type'    => 'WebSite',
            'name'     => $options->title,
            'url'      => $siteUrl . '/',
            'potentialAction' => array(
                '@type'    => 'SearchAction',
                'target'   => array(
                    '@type'       => 'EntryPoint',
                    'urlTemplate' => $siteUrl . '/?s={search_term_string}',
                ),
                'query-input' => 'required name=search_term_string',
            ),
        ));
    }
}

/* ==================== 独立页面模板：友链 / GitHub ==================== */

/** 大数字格式化（12000 → 1.2 万） */
function intpFormatNumber($n) {
    $n = (int)$n;
    if ($n >= 100000000) {
        return rtrim(rtrim(number_format($n / 100000000, 1, '.', ''), '0'), '.') . ' 亿';
    }
    if ($n >= 10000) {
        return rtrim(rtrim(number_format($n / 10000, 1, '.', ''), '0'), '.') . ' 万';
    }
    return number_format($n);
}

/** 解析友链配置（每行：名称 | 网址 | 简介） */
function intpLinks() {
    static $links = NULL;
    if (NULL !== $links) {
        return $links;
    }
    $links = array();
    $raw = (string)\Typecho\Widget::widget('Widget_Options')->links_list;
    foreach (preg_split('/\r\n|\r|\n/', $raw) as $line) {
        $line = trim($line);
        if ('' === $line || false === strpos($line, '|')) {
            continue;
        }
        $parts = array_map('trim', explode('|', $line));
        if (count($parts) < 2 || '' === $parts[0] || '' === $parts[1]
            || !preg_match('#^https?://#i', $parts[1])) {
            continue;
        }
        $links[] = array(
            'name' => $parts[0],
            'url'  => $parts[1],
            'desc' => isset($parts[2]) ? $parts[2] : '',
        );
    }
    return $links;
}

/** 从 GitHub 主页地址解析用户名 */
function intpGithubUser() {
    static $user;
    if (NULL !== $user) {
        return $user;
    }
    $user = '';
    $url = trim((string)\Typecho\Widget::widget('Widget_Options')->github_url);
    if (preg_match('~github\.com/([^/?#]+)~i', $url, $m)) {
        $user = $m[1];
    }
    return $user;
}

/** 解析手动指定的仓库列表（owner/repo 或完整地址） */
function intpGithubRepoNames() {
    $repos = array();
    $raw = (string)\Typecho\Widget::widget('Widget_Options')->github_repos;
    foreach (preg_split('/\r\n|\r|\n/', $raw) as $line) {
        $line = trim($line);
        if ('' === $line) {
            continue;
        }
        if (preg_match('~github\.com/([^/]+/[^/?#]+?)(?:\.git)?/?$~i', $line, $m)) {
            $repos[] = $m[1];
        } elseif (preg_match('~^[\w.-]+/[\w.-]+$~', $line)) {
            $repos[] = $line;
        }
    }
    return array_values(array_unique($repos));
}

/** 发起一次 HTTP GET（优先 cURL），失败返回 NULL */
function intpHttpGet($url, $timeout = 6) {
    $ua = 'INTP-Theme/1.0 (Typecho; +https://github.com/imoyy)';
    if (function_exists('curl_init')) {
        $ch = curl_init($url);
        curl_setopt_array($ch, array(
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_TIMEOUT => $timeout,
            CURLOPT_CONNECTTIMEOUT => $timeout,
            CURLOPT_USERAGENT => $ua,
            CURLOPT_HTTPHEADER => array('Accept: application/vnd.github+json'),
        ));
        $body = curl_exec($ch);
        $code = (int)curl_getinfo($ch, CURLINFO_RESPONSE_CODE);
        curl_close($ch);
        if (false !== $body && $code >= 200 && $code < 300) {
            return $body;
        }
        return NULL;
    }
    $ctx = stream_context_create(array(
        'http' => array(
            'timeout' => $timeout,
            'header' => "User-Agent: {$ua}\r\nAccept: application/vnd.github+json\r\n",
            'follow_location' => 1,
            'ignore_errors' => true,
        ),
    ));
    $body = @file_get_contents($url, false, $ctx);
    return false === $body ? NULL : $body;
}

/**
 * GitHub 项目数据（结果缓存到系统临时目录 6 小时；接口失败时回退旧缓存）
 * 返回数组，每项为 GitHub API 的 repository 对象。
 */
function intpGithubProjects() {
    static $cache;
    if (NULL !== $cache) {
        return $cache;
    }
    $cache = array();
    $options = \Typecho\Widget::widget('Widget_Options');
    // 缓存名并入站点 secret，避免共享主机上缓存文件名被他人预测后投毒/抢占
    $cacheFile = sys_get_temp_dir() . '/intp_gh_' . md5(__FILE__ . '|' . (string)$options->secret
        . '|' . intpGithubUser() . '|' . (string)$options->github_repos) . '.json';

    if (is_file($cacheFile) && time() - filemtime($cacheFile) < 21600) {
        $cached = json_decode((string)file_get_contents($cacheFile), true);
        if (is_array($cached)) {
            return $cache = $cached;
        }
    }

    $pinned = intpGithubRepoNames();
    $list = array();
    if ($pinned) {
        foreach ($pinned as $full) {
            $body = intpHttpGet('https://api.github.com/repos/' . $full);
            if (NULL !== $body) {
                $repo = json_decode($body, true);
                if (is_array($repo) && !empty($repo['full_name'])) {
                    $list[] = $repo;
                }
            }
        }
    } else {
        $user = intpGithubUser();
        if ('' !== $user) {
            $body = intpHttpGet('https://api.github.com/users/' . rawurlencode($user)
                . '/repos?sort=updated&per_page=20&type=owner');
            if (NULL !== $body) {
                $rows = json_decode($body, true);
                if (is_array($rows)) {
                    foreach ($rows as $repo) {
                        if (empty($repo['fork'])) {
                            $list[] = $repo;
                        }
                    }
                    $list = array_slice($list, 0, 12);
                }
            }
        }
    }

    if ($list) {
        @file_put_contents($cacheFile, json_encode($list));
        $cache = $list;
    } elseif (is_file($cacheFile)) {
        $cached = json_decode((string)file_get_contents($cacheFile), true);
        if (is_array($cached)) {
            $cache = $cached;
        }
    }
    return $cache;
}

/* ==================== 文章头图与列表排版 ==================== */

/**
 * 文章生效的列表排版模式
 * 自定义字段 list_layout（big/small/full）覆盖主题全局设置。
 */
function intpListLayout($widget) {
    $layout = strtolower(trim((string)$widget->fields->list_layout));
    if (!in_array($layout, array('big', 'small', 'full'), true)) {
        $layout = (string)\Typecho\Widget::widget('Widget_Options')->list_layout;
    }
    return in_array($layout, array('big', 'small', 'full'), true) ? $layout : 'small';
}

/** 随机头图池（过滤空行） */
function intpThumbPool() {
    static $pool = NULL;
    if (NULL === $pool) {
        $pool = array();
        foreach (preg_split('/\r\n|\r|\n/', (string)\Typecho\Widget::widget('Widget_Options')->thumb_pool) as $line) {
            $line = trim($line);
            if ($line !== '') {
                $pool[] = $line;
            }
        }
    }
    return $pool;
}

/** 从文章原文中提取第一张图片（兼容 HTML <img> 与 Markdown ![]() 语法） */
function intpFirstImage($widget) {
    $text = (string)$widget->text;
    $url = '';
    if (preg_match('/<img[^>]+\\ssrc=["\']([^"\']+)/i', $text, $matches)) {
        $url = $matches[1];
    } elseif (preg_match('/!\[[^\]]*\]\(([^\s)"\']+)/i', $text, $matches)) {
        $url = $matches[1];
    }
    return $url === '' ? '' : intpNormalizeUrl($url);
}

/** 将根相对 / 相对路径补全为绝对地址（协议相对、data URI、绝对地址原样返回） */
function intpNormalizeUrl($url) {
    if (preg_match('#^(https?:)?//#i', $url) || 0 === strpos($url, 'data:')) {
        return $url;
    }
    return rtrim(\Typecho\Widget::widget('Widget_Options')->rootUrl, '/') . '/' . ltrim($url, '/');
}

/**
 * 获取文章头图 URL（无图返回空字符串）
 * 优先级：自定义字段 thumb → 按全局策略取正文首图 → 随机图片池
 * 随机池按 cid 稳定选取，同一文章在列表与文章页保持一致。
 */
function intpThumb($widget) {
    static $cache = array();
    $cid = $widget->cid;
    if (isset($cache[$cid])) {
        return $cache[$cid];
    }

    $thumb = trim((string)$widget->fields->thumb);
    if ($thumb !== '') {
        $thumb = intpNormalizeUrl($thumb);
    }

    if ($thumb === '') {
        $options = \Typecho\Widget::widget('Widget_Options');
        $source = $options->thumb_source ? $options->thumb_source : 'first';
        $pool = 'manual' !== $source ? intpThumbPool() : array();

        if ('random' === $source && $pool) {
            $thumb = intpNormalizeUrl($pool[abs(crc32((string)$cid)) % count($pool)]);
        } elseif ('manual' !== $source) {
            $thumb = intpFirstImage($widget);
            if ($thumb === '' && $pool) {
                $thumb = intpNormalizeUrl($pool[abs(crc32((string)$cid)) % count($pool)]);
            }
        }
    }

    $cache[$cid] = $thumb;
    return $thumb;
}

/* ==================== 阅读量与点赞 ==================== */

/** 按当前数据库驱动给表名加标识符引号（MySQL 反引号 / SQLite、Pgsql 双引号） */
function intpQuoteTable($name) {
    $db = \Typecho\Db::get();
    return $db->getAdapter()->quoteColumn($db->getPrefix() . $name);
}

/**
 * 计数字段自增（upsert 到 typecho_fields，字段名白名单）
 *
 * 三种官方支持的数据库均以 (cid, name) 唯一约束（MySQL/Pgsql 主键，
 * SQLite 唯一索引）做单条原子 UPSERT，避免「读-改-写」在并发下丢失更新：
 * - MySQL/MariaDB：INSERT ... ON DUPLICATE KEY UPDATE
 * - SQLite >= 3.24 / PostgreSQL：标准 SQL:2016 的 INSERT ... ON CONFLICT DO UPDATE
 */
function intpCounterBump($cid, $name) {
    static $allowed = array('intp_views' => 1, 'intp_likes' => 1);
    if (!isset($allowed[$name])) {
        return;
    }
    $db = \Typecho\Db::get();
    $adapter = $db->getAdapter();
    $table = intpQuoteTable('fields');
    $values = (int)$cid . ', ' . $adapter->quoteValue($name)
        . ', ' . $adapter->quoteValue('int') . ', 1';

    if ('mysql' === $adapter->getDriver()) {
        $sql = 'INSERT INTO ' . $table . ' (cid, name, type, int_value) VALUES (' . $values
            . ') ON DUPLICATE KEY UPDATE int_value = int_value + 1';
    } else {
        // SQLite 与 PostgreSQL 共用标准 ON CONFLICT 语法
        $sql = 'INSERT INTO ' . $table . ' (cid, name, type, int_value) VALUES (' . $values
            . ') ON CONFLICT (cid, name) DO UPDATE SET int_value = int_value + 1';
    }
    $db->query($sql, \Typecho\Db::WRITE);
}

/** 读取计数字段当前值 */
function intpCounterValue($cid, $name) {
    $db = \Typecho\Db::get();
    $row = $db->fetchRow(
        $db->select('int_value')->from('table.fields')
            ->where('cid = ?', (int)$cid)->where('name = ?', $name)
    );
    return $row ? (int)$row['int_value'] : 0;
}

/**
 * 文章计数的前台展示值
 * 计数自增发生在 fields 惰性加载之前/之后皆有可能，这里以自增后回读的值为准。
 */
function intpDisplayCount($widget, $name, $fresh = NULL) {
    static $map = array();
    $cid = (int)$widget->cid;
    if (NULL !== $fresh) {
        $map[$name][$cid] = (int)$fresh;
    }
    if (isset($map[$name][$cid])) {
        return $map[$name][$cid];
    }
    $val = $widget->fields->{$name};
    return NULL === $val ? 0 : (int)$val;
}

/** 读取 cookie 中的 cid 列表 */
function intpCookieCids($key) {
    $raw = \Typecho\Cookie::get($key);
    if (!is_string($raw) || '' === $raw) {
        return array();
    }
    $list = json_decode($raw, true);
    if (!is_array($list)) {
        return array();
    }
    return array_values(array_unique(array_map('intval', $list)));
}

/** 向 cookie 中的 cid 列表追加一个 cid（可设上限） */
function intpCookiePushCid($key, $cid, $expire, $cap = 0) {
    $list = intpCookieCids($key);
    $cid = (int)$cid;
    if (!in_array($cid, $list, true)) {
        $list[] = $cid;
        if ($cap > 0 && count($list) > $cap) {
            $list = array_slice($list, -$cap);
        }
        \Typecho\Cookie::set($key, json_encode($list), (int)$expire);
    }
}

/** 单篇文章阅读量自增（跳过作者本人，cookie 30 天防刷） */
function intpCountView($archive) {
    $cid = (int)$archive->cid;
    $user = \Typecho\Widget::widget('Widget_User');
    if ($user->hasLogin() && (int)$user->uid === (int)$archive->authorId) {
        return;
    }
    if (in_array($cid, intpCookieCids('intp_viewed'), true)) {
        return;
    }
    intpCounterBump($cid, 'intp_views');
    intpCookiePushCid('intp_viewed', $cid, time() + 2592000, 500);
    intpDisplayCount($archive, 'intp_views', intpCounterValue($cid, 'intp_views'));
}

/** 点赞接口：POST 文章 permalink?intp_action=like，输出 JSON 后 exit */
function intpHandleLike($archive) {
    $json = function ($data, $code = 200) {
        // 状态码需写入 Response 单例：Init 的输出缓冲回调会在 flush 时
        // 调用 sendHeaders() 覆盖直接使用 http_response_code() 设置的值
        \Typecho\Response::getInstance()->setStatus($code);
        header('Content-Type: application/json; charset=utf-8');
        header('Cache-Control: no-store');
        echo json_encode($data);
        exit;
    };

    if ('POST' !== ($_SERVER['REQUEST_METHOD'] ?? '')) {
        $json(array('ok' => false, 'message' => 'method_not_allowed'), 405);
    }
    if (!$archive->is('post') || 'publish' !== (string)$archive->status) {
        $json(array('ok' => false, 'message' => 'not_found'), 404);
    }
    $options = \Typecho\Widget::widget('Widget_Options');
    if ('0' === (string)$options->show_likes) {
        $json(array('ok' => false, 'message' => 'disabled'), 403);
    }
    // 仅接受同源 XHR，拦截跨站表单提交
    if ('xmlhttprequest' !== strtolower((string)($_SERVER['HTTP_X_REQUESTED_WITH'] ?? ''))) {
        $json(array('ok' => false, 'message' => 'bad_request'), 400);
    }

    $cid = (int)$archive->cid;
    if (!in_array($cid, intpCookieCids('intp_liked'), true)) {
        intpCounterBump($cid, 'intp_likes');
        intpCookiePushCid('intp_liked', $cid, time() + 31536000, 0);
    }
    $json(array('ok' => true, 'liked' => true, 'likes' => intpCounterValue($cid, 'intp_likes')));
}

/**
 * 注册接口：POST /?intp_action=register，输出 JSON 后 exit
 *
 * 复刻核心 Widget\Register 的校验 / 入库 / 插件钩子流程，改为 JSON 返回以配合
 * 主题悬浮注册窗；密码留空时与核心一致生成 7 位随机密码（仅一次性返回），
 * 填写密码时校验长度与确认密码。仅接受同源 XHR（自定义头触发 CORS 预检，防 CSRF）。
 */
function intpHandleRegister() {
    $json = function ($data, $code = 200) {
        \Typecho\Response::getInstance()->setStatus($code);
        header('Content-Type: application/json; charset=utf-8');
        header('Cache-Control: no-store');
        echo json_encode($data, JSON_UNESCAPED_UNICODE);
        exit;
    };

    if ('POST' !== ($_SERVER['REQUEST_METHOD'] ?? '')) {
        $json(array('ok' => false, 'message' => 'method_not_allowed'), 405);
    }
    // 仅接受同源 XHR，拦截跨站表单提交
    if ('xmlhttprequest' !== strtolower((string)($_SERVER['HTTP_X_REQUESTED_WITH'] ?? ''))) {
        $json(array('ok' => false, 'message' => 'bad_request'), 400);
    }

    $options = \Typecho\Widget::widget('Widget_Options');
    $user = \Widget\User::alloc();
    if ($user->hasLogin() || !$options->allowRegister) {
        $json(array('ok' => false, 'message' => '注册已关闭，或你已经登录。'), 403);
    }

    $request = \Typecho\Request::getInstance();
    $input = $request->from('name', 'password', 'mail', 'confirm');

    $reg = \Widget\Register::alloc();
    $validator = new \Typecho\Validate();
    $validator->addRule('name', 'required', _t('必须填写用户名称'));
    $validator->addRule('name', 'minLength', _t('用户名至少包含2个字符'), 2);
    $validator->addRule('name', 'maxLength', _t('用户名最多包含32个字符'), 32);
    $validator->addRule('name', 'xssCheck', _t('请不要在用户名中使用特殊字符'));
    $validator->addRule('name', array($reg, 'nameExists'), _t('用户名已经存在'));
    $validator->addRule('mail', 'required', _t('必须填写电子邮箱'));
    $validator->addRule('mail', array($reg, 'mailExists'), _t('电子邮箱地址已经存在'));
    $validator->addRule('mail', 'email', _t('电子邮箱格式错误'));
    $validator->addRule('mail', 'maxLength', _t('电子邮箱最多包含64个字符'), 64);

    // 密码留空：系统生成随机密码；填写密码：校验长度与二次确认
    $hasPassword = '' !== trim((string)$input['password']);
    if ($hasPassword) {
        $validator->addRule('password', 'minLength', _t('为了保证账户安全, 请输入至少六位的密码'), 6);
        $validator->addRule('password', 'maxLength', _t('为了便于记忆, 密码长度请不要超过十八位'), 18);
        $validator->addRule('confirm', 'required', _t('请再次输入密码'));
        $validator->addRule('confirm', 'confirm', _t('两次输入的密码不一致'), 'password');
    }

    if ($errors = $validator->run($input)) {
        $json(array('ok' => false, 'errors' => $errors), 422);
    }

    $name = trim((string)$input['name']);
    $mail = trim((string)$input['mail']);

    if ($hasPassword) {
        $password = (string)$input['password'];
        $generated = NULL;
    } else {
        $password = \Typecho\Common::randString(7);
        $generated = $password;
    }

    $hasher = new \Utils\PasswordHash(8, true);
    $dataStruct = array(
        'name' => $name,
        'mail' => $mail,
        'screenName' => $name,
        'password' => $hasher->hashPassword($password),
        'created' => $options->time,
        'group' => 'subscriber',
    );
    $dataStruct = \Widget\Register::pluginHandle()->filter('register', $dataStruct);

    try {
        $insertId = $reg->insert($dataStruct);
        $db = \Typecho\Db::get();
        $db->fetchRow($reg->select()->where('uid = ?', $insertId)->limit(1), array($reg, 'push'));
        \Widget\Register::pluginHandle()->call('finishRegister', $reg);
    } catch (\Throwable $e) {
        $json(array('ok' => false, 'message' => '注册失败，请稍后再试。'), 500);
    }

    \Typecho\Cookie::delete('__typecho_first_run');
    \Typecho\Cookie::delete('__typecho_remember_name');
    \Typecho\Cookie::delete('__typecho_remember_mail');

    if (!$user->login($name, $password)) {
        // 入库成功但自动登录异常：返回凭据，提示用户手动登录
        $json(array(
            'ok' => true,
            'logged' => false,
            'name' => $name,
            'generatedPassword' => $generated,
            'message' => NULL !== $generated
                ? '注册成功，请保存以下密码后手动登录。'
                : '注册成功，请使用你设置的密码登录。',
        ));
    }

    $json(array(
        'ok' => true,
        'logged' => true,
        'name' => $name,
        'generatedPassword' => $generated,
        'message' => NULL !== $generated ? '注册成功，已为你自动生成密码，请妥善保存。' : '注册成功，正在进入站点…',
    ));
}

/** 热门文章（按阅读量倒序，无数据时回退最新文章） */
function intpPopularPosts($limit = 5) {
    $db = \Typecho\Db::get();
    $options = \Typecho\Widget::widget('Widget_Options');
    $contents = intpQuoteTable('contents');
    $fields = intpQuoteTable('fields');
    $sql = 'SELECT c.cid, c.title, c.slug, c.type, c.created, COALESCE(f.int_value, 0) AS views'
        . ' FROM ' . $contents . ' c'
        . ' LEFT JOIN ' . $fields . " f ON f.cid = c.cid AND f.name = 'intp_views'"
        . " WHERE c.type = 'post' AND c.status = 'publish' AND c.created <= " . (int)$options->time
        . ' ORDER BY views DESC, c.cid DESC'
        . ' LIMIT ' . (int)$limit;
    $rows = $db->fetchAll($sql);
    foreach ($rows as &$row) {
        $row['permalink'] = \Typecho\Common::url(\Typecho\Router::url('post', $row), $options->index);
    }
    unset($row);
    return $rows;
}

/* ==================== TOC 目录 / 图片懒加载 / 文章分页 / 文章置顶 ==================== */

/**
 * 内容图片懒加载：为缺少 loading 属性的 <img> 补 loading="lazy" decoding="async"
 */
function intpLazyImages($content) {
    if (false === stripos($content, '<img')) {
        return $content;
    }
    return preg_replace_callback('/<img\b[^>]*>/i', function ($m) {
        $tag = $m[0];
        if (preg_match('/\bloading\s*=/i', $tag)) {
            return $tag;
        }
        return preg_replace('/^<img\b/i', '<img loading="lazy" decoding="async"', $tag, 1);
    }, $content);
}

/* ---------- TOC ---------- */

/** TOC 条目暂存（内容过滤时收集，侧栏渲染时读取，按 cid 区分） */
function intpTocStore($cid, $entries = NULL) {
    static $store = array();
    $cid = (int)$cid;
    if (NULL !== $entries) {
        $store[$cid] = $entries;
    }
    return isset($store[$cid]) ? $store[$cid] : array();
}

/** 由标题文本生成锚点 id，空文本回退 section，同页自动去重 */
function intpTocSlug($text, array &$used) {
    $base = preg_replace('/[^\p{L}\p{N}\-_]+/u', '-', $text);
    $base = trim($base, '-');
    if ('' === $base) {
        $base = 'section';
    }
    $id = $base;
    $i = 2;
    while (isset($used[$id])) {
        $id = $base . '-' . $i++;
    }
    $used[$id] = 1;
    return 'toc-' . $id;
}

/**
 * 为正文 H1-H4 注入锚点 id 并收集目录条目
 * 跳过短代码生成的标题（class 含 sc-）与无文本标题。
 */
function intpTocInject($content, $widget) {
    $cid = (int)$widget->cid;
    $entries = array();
    $used = array();

    $content = preg_replace_callback(
        '#<h([1-4])([^>]*)>(.*?)</h\1\s*>#is',
        function ($m) use (&$entries, &$used) {
            $level = (int)$m[1];
            $attrs = $m[2];
            $inner = $m[3];

            if (preg_match('/\bclass\s*=\s*["\'][^"\']*\bsc-/i', $attrs)) {
                return $m[0];
            }

            $text = trim(html_entity_decode(strip_tags($inner), ENT_QUOTES, 'UTF-8'));
            if ('' === $text) {
                return $m[0];
            }

            if (preg_match('/\bid\s*=\s*["\']([^"\']+)["\']/i', $attrs, $idm)) {
                $id = $idm[1];
                if (isset($used[$id])) {
                    $id = intpTocSlug($text, $used);
                    $attrs = preg_replace('/\sid\s*=\s*["\'][^"\']*["\']/i', '', $attrs, 1);
                    return '<h' . $level . $attrs . ' id="' . $id . '">' . $inner . '</h' . $level . '>';
                }
                $used[$id] = 1;
            } else {
                $id = intpTocSlug($text, $used);
                $attrs .= ' id="' . $id . '"';
            }

            $entries[] = array('level' => $level, 'text' => $text, 'id' => $id);
            return '<h' . $level . $attrs . '>' . $inner . '</h' . $level . '>';
        },
        $content
    );

    intpTocStore($cid, $entries);
    return $content;
}

/** 侧栏章节目录卡片（不足两个标题时不输出） */
function intpTocCard($widget) {
    $entries = intpTocStore((int)$widget->cid);
    if (count($entries) < 2) {
        return;
    }
    $minLevel = min(array_map(function ($e) { return $e['level']; }, $entries));
    echo '<div class="side-card"><h3>' . _t('章节目录') . '</h3>'
        . '<nav class="intp-toc">';
    foreach ($entries as $entry) {
        $indent = max(0, $entry['level'] - $minLevel);
        echo '<a class="toc-link toc-l' . $indent . '" href="#' . htmlspecialchars($entry['id'], ENT_QUOTES, 'UTF-8')
            . '" data-target="' . htmlspecialchars($entry['id'], ENT_QUOTES, 'UTF-8') . '">'
            . htmlspecialchars($entry['text'], ENT_QUOTES, 'UTF-8') . '</a>';
    }
    echo '</nav></div>';
}

/* ---------- 文章分页（正文插入 <!--nextpage-->） ---------- */

/**
 * 按 <!--nextpage--> 切分正文，仅显示当前页并在文末输出页码导航
 * 页码通过 permalink?page=N 传递，核心单篇路由不使用该参数。
 */
function intpNextPage($content, $widget) {
    if (false === stripos($content, '<!--nextpage-->')) {
        return $content;
    }

    $pages = preg_split('/<!--\s*nextpage\s*-->/i', $content);
    $total = count($pages);
    if ($total < 2) {
        return str_replace('<!--nextpage-->', '', $content);
    }

    $current = isset($_GET['page']) ? (int)$_GET['page'] : 1;
    $current = max(1, min($total, $current));

    $base = (string)$widget->permalink;
    $sep = (false === strpos($base, '?')) ? '?' : '&';
    $pageUrl = function ($n) use ($base, $sep) {
        return htmlspecialchars($base . $sep . 'page=' . $n, ENT_QUOTES, 'UTF-8');
    };

    $nav = '<nav class="pagination post-pagination">';
    if ($current > 1) {
        $nav .= '<a class="page-prev" href="' . $pageUrl($current - 1) . '" rel="prev">« ' . _t('上一页') . '</a>';
    }
    for ($i = 1; $i <= $total; $i++) {
        if ($i === $current) {
            $nav .= '<span class="current">' . $i . '</span>';
        } else {
            $nav .= '<a href="' . $pageUrl($i) . '">' . $i . '</a>';
        }
    }
    if ($current < $total) {
        $nav .= '<a class="page-next" href="' . $pageUrl($current + 1) . '" rel="next">' . _t('下一页') . ' »</a>';
    }
    $nav .= '</nav>';

    return $pages[$current - 1] . $nav;
}

/* ---------- 首页置顶（自定义字段 sticky） ---------- */

/**
 * 置顶文章（含自定义字段 sticky 的已发布文章），按 cid 倒序
 * 返回数组每项含 permalink / thumb_url / excerpt，供首页模板渲染。
 */
function intpStickyPosts() {
    static $cache = NULL;
    if (NULL !== $cache) {
        return $cache;
    }
    $cache = array();

    $options = \Typecho\Widget::widget('Widget_Options');
    if ('0' === (string)$options->show_sticky) {
        return $cache;
    }

    $db = \Typecho\Db::get();
    $contents = intpQuoteTable('contents');
    $fields = intpQuoteTable('fields');
    $sql = 'SELECT c.cid, c.title, c.slug, c.type, c.created, c.text,'
        . ' (SELECT f.str_value FROM ' . $fields . ' f WHERE f.cid = c.cid AND f.name = ' . $db->getAdapter()->quoteValue('thumb') . ' LIMIT 1) AS thumb'
        . ' FROM ' . $contents . ' c'
        . ' INNER JOIN ' . $fields . ' fs ON fs.cid = c.cid AND fs.name = ' . $db->getAdapter()->quoteValue('sticky')
        . " WHERE c.type = 'post' AND c.status = 'publish' AND c.created <= " . (int)$options->time
        . ' ORDER BY c.cid DESC';

    foreach ($db->fetchAll($sql) as $row) {
        $row['permalink'] = \Typecho\Common::url(\Typecho\Router::url('post', $row), $options->index);

        $item = new \Typecho\Config($row);
        $item->fields = new \Typecho\Config(array('thumb' => $row['thumb']));
        $row['thumb_url'] = intpThumb($item);

        $plain = trim(preg_replace('/\s+/u', ' ', strip_tags(\Utils\Markdown::convert((string)$row['text']))));
        $row['excerpt'] = \Typecho\Common::subStr($plain, 0, 120, '...');
        $cache[] = $row;
    }
    return $cache;
}

/* ==================== 短代码与隐藏内容（移植自 MfThemePlugin） ==================== */

\Typecho\Plugin::factory('Widget_Abstract_Contents')->contentEx = 'intpContentFilter';
\Typecho\Plugin::factory('Widget_Abstract_Comments')->contentEx = 'intpCommentFilter';
\Typecho\Plugin::factory('Widget\Feedback')->finishComment = 'intpIssueCommentToken';

/**
 * 主题初始化
 *
 * Typecho 1.3 中 functions.php 在 Archive 的 handle 执行之后才加载：单篇文章的
 * handle 内会提前读取 plainExcerpt，连锁求值并缓存 content / excerpt（此时上面的
 * contentEx 钩子尚未注册），导致短代码与隐藏内容过滤器错过首次过滤。这里清除内容
 * 缓存令其按已注册钩子重新求值，并重写由未过滤摘要生成的页面描述。
 */
function themeInit($archive) {
    if (!is_object($archive)) {
        return;
    }

    /* 点赞 JSON 接口（在文章 permalink 上以 ?intp_action=like POST 访问） */
    if (isset($_GET['intp_action']) && 'like' === $_GET['intp_action']) {
        intpHandleLike($archive);
    }

    /* 注册 JSON 接口（任意页面 ?intp_action=register POST） */
    if (isset($_GET['intp_action']) && 'register' === $_GET['intp_action']) {
        intpHandleRegister();
    }

    /* 阅读量自增（仅单篇已发布文章页） */
    $options = \Typecho\Widget::widget('Widget_Options');
    if ('0' !== (string)$options->show_views
        && $archive->is('post') && 'publish' === (string)$archive->status) {
        intpCountView($archive);
    }

    $rowProp = new ReflectionProperty('Typecho\\Widget', 'row');
    $rowProp->setAccessible(true);
    $row = $rowProp->getValue($archive);

    if (!is_array($row) || !array_key_exists('#plainExcerpt', $row)) {
        return;
    }

    unset($row['#content'], $row['#excerpt'], $row['#plainExcerpt']);
    $rowProp->setValue($archive, $row);

    if (method_exists($archive, 'setArchiveDescription')) {
        $archive->setArchiveDescription((string)$archive->plainExcerpt);
    }
}

/** 支持的短代码标签 */
function intpScTags() {
    return array('button', 'alert', 'collapse', 'badge', 'hide', 'progress', 'tabs', 'tab', 'row', 'col');
}

/** HTML 转义 */
function intpScH($str) {
    return htmlspecialchars((string)$str, ENT_QUOTES, 'UTF-8');
}

/** 安全 URL：仅允许常见协议 / 相对地址，拦截 javascript: 等 */
function intpScUrl($url) {
    $url = trim((string)$url);
    if ('' === $url) {
        return '';
    }
    if (0 === strpos($url, '//') || 0 === strpos($url, '#') || 0 === strpos($url, '?')) {
        return $url;
    }
    if (preg_match('#^(?:https?|ftp|mailto):#i', $url) || 0 === strpos($url, '/')) {
        return $url;
    }
    return '';
}

/** 解析短代码属性：name="value" 及裸标记（如 open） */
function intpScParseAttrs($attrString) {
    $attrs = array();
    if (preg_match_all('/(\w+)\s*=\s*(["\'])(.*?)\2/i', $attrString, $m, PREG_SET_ORDER)) {
        foreach ($m as $r) {
            $attrs[strtolower($r[1])] = $r[3];
        }
    }
    $rest = preg_replace('/\w+\s*=\s*(["\']).*?\1/i', ' ', $attrString);
    if (preg_match_all('/\b([a-zA-Z_]\w*)\b/', $rest, $b)) {
        foreach ($b[1] as $flag) {
            $attrs[strtolower($flag)] = true;
        }
    }
    return $attrs;
}

/** 去掉 AutoP 在短代码周围产生的多余 <br> 与空白 */
function intpScClean($content) {
    return preg_replace('#^\s*(?:<br\s*/?>\s*)*|(?:\s*<br\s*/?>\s*)*$#i', '', $content);
}

/** 判断当前请求是否为 RSS / Feed */
function intpIsFeed() {
    static $isFeed = null;
    if (null === $isFeed) {
        $path = \Typecho\Request::getInstance()->getPathInfo();
        $isFeed = (is_string($path) && preg_match('#^/feed#', $path));
    }
    return $isFeed;
}

/* ---------- 文章内容钩子：Feed 转纯文本，前台渲染短代码 ---------- */
function intpContentFilter($content, $widget, $lastResult) {
    $content = empty($lastResult) ? $content : $lastResult;
    if (intpIsFeed()) {
        return intpScConvertForFeed($content);
    }
    return intpScRender($content, $widget);
}

/* ---------- 评论内容钩子：[hide] 私密（仅评论者本人和管理员可见） ---------- */
function intpCommentFilter($content, $widget, $lastResult) {
    $content = empty($lastResult) ? $content : $lastResult;

    if (false === stripos($content, '[hide')) {
        return $content;
    }

    // Feed 中整条评论替换为提示
    if (intpIsFeed()) {
        return '私密评论，仅评论者和管理员可见。';
    }

    // 管理员或评论者本人：去掉标记，显示内容
    if (intpCanViewHiddenComment($widget)) {
        return preg_replace('/\[\s*\/?hide\s*\]/i', '', $content);
    }

    // 其他访客：移除隐藏块
    $stripped = preg_replace('/\[hide\].*?\[\/hide\]/is', '', $content);
    $plain = trim(str_replace('&nbsp;', ' ', strip_tags(str_ireplace('<br', ' <br', $stripped))));

    // 评论最终输出仅允许 <p><br>（Common::stripTags），提示使用纯文本
    if ('' === $plain) {
        return '<p>私密评论，仅评论者本人和管理员可见。</p>';
    }
    return $stripped . '<p>该评论包含仅评论者本人和管理员可见的隐藏内容。</p>';
}

/* ==================== 访客评论身份令牌（替代可伪造的 remember_mail Cookie） ==================== */

/** 评论令牌 Cookie 名 / 有效期（30 天） / 单访客保留上限 */
function intpCommentTokenTtl() {
    return 2592000;
}

/** 令牌 HMAC 密钥（基于站点 secret） */
function intpCommentTokenSecret() {
    return (string)\Typecho\Widget::widget('Widget_Options')->secret . '|intp_comment_token';
}

function intpB64UrlEncode($bin) {
    return rtrim(strtr(base64_encode($bin), '+/', '-_'), '=');
}

function intpB64UrlDecode($str) {
    $pad = strlen($str) % 4;
    if ($pad) {
        $str .= str_repeat('=', 4 - $pad);
    }
    return base64_decode(strtr($str, '-_', '+/'), true);
}

/** 生成单条令牌：base64url(coid|mail|exp).base64url(hmac_sha256) */
function intpMakeCommentToken($coid, $mail, $exp) {
    $payload = (int)$coid . '|' . $mail . '|' . (int)$exp;
    $p = intpB64UrlEncode($payload);
    $sig = hash_hmac('sha256', $p, intpCommentTokenSecret(), true);
    return $p . '.' . intpB64UrlEncode($sig);
}

/** 校验并解析单条令牌，返回 [coid, mail, exp] 或 NULL */
function intpParseCommentToken($token) {
    $parts = explode('.', (string)$token, 2);
    if (2 !== count($parts) || '' === $parts[0] || '' === $parts[1]) {
        return NULL;
    }
    list($p, $sig) = $parts;
    $expect = intpB64UrlEncode(hash_hmac('sha256', $p, intpCommentTokenSecret(), true));
    if (!hash_equals($expect, $sig)) {
        return NULL;
    }
    $raw = intpB64UrlDecode($p);
    if (!is_string($raw)) {
        return NULL;
    }
    $bits = explode('|', $raw, 3);
    if (3 !== count($bits) || (int)$bits[0] <= 0 || (int)$bits[2] < time()) {
        return NULL;
    }
    return array((int)$bits[0], $bits[1], (int)$bits[2]);
}

/** 读取当前访客全部有效评论令牌，返回 [['coid' => int, 'mail' => string], ...] */
function intpReadCommentTokens() {
    static $cache = NULL;
    if (NULL !== $cache) {
        return $cache;
    }
    $cache = array();
    $raw = \Typecho\Cookie::get('intp_comment_tokens');
    if (is_string($raw) && '' !== $raw) {
        foreach (explode('~', $raw) as $token) {
            $parsed = intpParseCommentToken($token);
            if (NULL !== $parsed) {
                $cache[] = array('coid' => $parsed[0], 'mail' => $parsed[1]);
            }
        }
    }
    return $cache;
}

/** finishComment 钩子：评论真正入库（拿到 coid）后为评论者签发身份令牌 */
function intpIssueCommentToken($feedback) {
    if (!is_object($feedback) || empty($feedback->coid) || empty($feedback->mail)
        || 'comment' !== (string)$feedback->type) {
        return;
    }
    $mail = trim((string)$feedback->mail);
    if ('' === $mail) {
        return;
    }
    $exp = time() + intpCommentTokenTtl();
    $coid = (int)$feedback->coid;
    $tokens = array(intpMakeCommentToken($coid, $mail, $exp));
    foreach (intpReadCommentTokens() as $old) {
        if ((int)$old['coid'] === $coid) {
            continue;
        }
        $tokens[] = intpMakeCommentToken((int)$old['coid'], $old['mail'], $exp);
        if (count($tokens) >= 30) {
            break;
        }
    }
    \Typecho\Cookie::set('intp_comment_tokens', implode('~', $tokens), $exp);
}

/** 当前访客是否可见某条评论的隐藏内容（管理员 / 评论者本人） */
function intpCanViewHiddenComment($widget) {
    $user = \Widget\User::alloc();
    if ($user->hasLogin()) {
        if ($user->pass('administrator', true)) {
            return true;
        }
        if ((int)$widget->authorId > 0 && (int)$widget->authorId === (int)$user->uid) {
            return true;
        }
        return !empty($widget->mail) && !empty($user->mail)
            && 0 === strcasecmp($widget->mail, $user->mail);
    }
    // 游客仅承认经 finishComment 钩子签名签发、绑定具体评论 coid 的令牌
    if (empty($widget->coid) || empty($widget->mail)) {
        return false;
    }
    $coid = (int)$widget->coid;
    foreach (intpReadCommentTokens() as $token) {
        if ((int)$token['coid'] === $coid
            && 0 === strcasecmp($token['mail'], (string)$widget->mail)) {
            return true;
        }
    }
    return false;
}

/** 当前访客是否可见文章的 [hide]：管理员 / 作者 / 已在本文发表审核评论者 */
function intpCanViewHiddenPost($widget) {
    static $cache = array();

    $cid = is_object($widget) && isset($widget->cid) ? (int)$widget->cid : 0;
    if ($cid && isset($cache[$cid])) {
        return $cache[$cid];
    }

    $ok = false;
    $user = \Widget\User::alloc();

    if ($user->hasLogin()) {
        if ($user->pass('administrator', true)) {
            $ok = true;
        }
        if (!$ok && is_object($widget) && (int)$widget->authorId === (int)$user->uid) {
            $ok = true;
        }
        // 登录用户：按账号邮箱匹配本文已审核评论
        if (!$ok && $cid) {
            $mail = strtolower(trim((string)$user->mail));
            if ('' !== $mail) {
                try {
                    $db = \Typecho\Db::get();
                    $row = $db->fetchRow($db->select('coid')->from('table.comments')
                        ->where('cid = ?', $cid)
                        ->where('LOWER(mail) = ?', $mail)
                        ->where('status = ?', 'approved')
                        ->where('type = ?', 'comment')
                        ->limit(1));
                    $ok = !empty($row);
                } catch (\Throwable $e) {
                    $ok = false;
                }
            }
        }
    } elseif ($cid) {
        // 游客：令牌中的 coid 必须对应本文下一条审核通过、邮箱匹配的评论
        $pairs = array();
        foreach (intpReadCommentTokens() as $token) {
            $mail = strtolower(trim((string)$token['mail']));
            if ((int)$token['coid'] > 0 && '' !== $mail) {
                $pairs[(int)$token['coid']] = $mail;
            }
        }
        if ($pairs) {
            try {
                $db = \Typecho\Db::get();
                $or = array();
                foreach ($pairs as $coid => $mail) {
                    $or[] = '(coid = ' . (int)$coid . ' AND LOWER(mail) = ' . $db->quoteValue($mail) . ')';
                }
                $row = $db->fetchRow($db->select('coid')->from('table.comments')
                    ->where('cid = ?', $cid)
                    ->where('status = ?', 'approved')
                    ->where('type = ?', 'comment')
                    ->where(implode(' OR ', $or))
                    ->limit(1));
                $ok = !empty($row);
            } catch (\Throwable $e) {
                $ok = false;
            }
        }
    }

    if ($cid) {
        $cache[$cid] = $ok;
    }
    return $ok;
}

/* ---------- 前台短代码渲染 ---------- */
function intpScRender($content, $widget) {
    $tags = implode('|', intpScTags());
    $pattern = '/(<pre\b[^>]*>.*?<\/pre>|<code\b[^>]*>.*?<\/code>)'
        . '|\[(' . $tags . ')\b([^\]]*?)\](.*?)\[\/\2\]/is';

    $canViewHide = intpCanViewHiddenPost($widget);

    for ($i = 0; $i < 10; $i++) {
        $rendered = preg_replace_callback($pattern, function ($m) use ($canViewHide) {
            if (!empty($m[1])) {
                return $m[1]; // 代码块原样保留
            }
            return intpScRenderTag(strtolower($m[2]), intpScParseAttrs($m[3]), $m[4], $canViewHide);
        }, $content);

        if (null === $rendered || $rendered === $content) {
            break;
        }
        $content = $rendered;
    }

    // 清理块级元素被 AutoP 包上的 <p></p>
    $content = preg_replace(
        '#<p>\s*(<(?:div|details|section)\b[^>]*\bclass="sc-[^"]*"[^>]*>)#is',
        '$1',
        $content
    );
    $content = preg_replace(
        '#(</(?:div|details|section)>)\s*</p>#is',
        '$1',
        $content
    );

    // 图片懒加载（列表全文模式同样生效）
    $content = intpLazyImages($content);

    // 文章分页与章节目录仅作用于单篇文章 / 独立页面
    if (is_object($widget) && $widget->is('single')) {
        $content = intpNextPage($content, $widget);
        $content = intpTocInject($content, $widget);
    }

    return $content;
}

/** 单个短代码 → HTML */
function intpScRenderTag($tag, $attrs, $inner, $canViewHide) {
    switch ($tag) {
        case 'alert':
            $types = array('info', 'success', 'warning', 'danger');
            $type = isset($attrs['type']) && in_array($attrs['type'], $types, true)
                ? $attrs['type'] : 'info';
            $title = !empty($attrs['title'])
                ? '<div class="sc-alert-title">' . intpScH($attrs['title']) . '</div>' : '';
            return '<div class="sc-alert sc-alert-' . $type . '">' . $title
                . '<div class="sc-alert-body">' . intpScClean($inner) . '</div></div>';

        case 'collapse':
            $title = !empty($attrs['title']) ? $attrs['title'] : '点击展开 / 收起';
            $open = !empty($attrs['open']) ? ' open' : '';
            return '<details class="sc-collapse"' . $open . '><summary>' . intpScH($title) . '</summary>'
                . '<div class="sc-collapse-body">' . intpScClean($inner) . '</div></details>';

        case 'progress':
            if (isset($attrs['value'])) {
                $value = (float)$attrs['value'];
            } elseif (isset($attrs['percent'])) {
                $value = (float)$attrs['percent'];
            } else {
                $num = preg_replace('/[^0-9.]/', '', $inner);
                $value = '' === $num ? 0 : (float)$num;
            }
            $value = max(0, min(100, $value));
            $width = rtrim(rtrim(number_format($value, 2, '.', ''), '0'), '.');
            $text = isset($attrs['label']) ? (string)$attrs['label']
                : trim(html_entity_decode(strip_tags($inner)));
            if ('' === $text || is_numeric($text)) {
                $text = $width . '%';
            }
            return '<div class="sc-progress"><div class="sc-progress-bar">'
                . '<span class="sc-progress-fill" style="width:' . $width . '%"></span></div>'
                . '<span class="sc-progress-label">' . intpScH($text) . '</span></div>';

        case 'tabs':
            return intpScRenderTabs($inner);

        case 'tab':
            $title = !empty($attrs['title']) ? $attrs['title'] : 'Tab';
            return '<section class="sc-tab-panel sc-tab-alone"><h4 class="sc-tab-title">'
                . intpScH($title) . '</h4>' . intpScClean($inner) . '</section>';

        case 'button':
        case 'badge':
            return intpScRenderLink($tag, $attrs, $inner);

        case 'row':
            return '<div class="sc-row">' . intpScClean($inner) . '</div>';

        case 'col':
            $style = '';
            if (!empty($attrs['width'])) {
                $style = ' style="flex-basis:' . intpScH($attrs['width']) . '"';
            }
            return '<div class="sc-col"' . $style . '>' . intpScClean($inner) . '</div>';

        case 'hide':
            if ($canViewHide) {
                return '<div class="sc-hide is-unlocked">'
                    . '<div class="sc-hide-head">' . intpScLockSvg() . '<span>隐藏内容</span></div>'
                    . '<div class="sc-hide-body">' . intpScClean($inner) . '</div></div>';
            }
            return '<div class="sc-hide is-locked">' . intpScLockSvg()
                . '<p>以下为隐藏内容，<a href="#comments">发表评论</a>并通过审核后刷新即可查看。</p></div>';
    }

    return $inner;
}

/** button / badge：有合法 url 渲染为链接，否则渲染为同名容器 */
function intpScRenderLink($tag, $attrs, $inner) {
    $url = isset($attrs['url']) ? intpScUrl($attrs['url']) : '';
    $variantName = isset($attrs['type']) ? preg_replace('/[^a-z]/i', '', strtolower($attrs['type'])) : '';
    $variant = '' !== $variantName ? ' sc-' . $tag . '-' . $variantName : '';

    if ('button' === $tag) {
        $class = 'sc-btn' . $variant;
        if ($url) {
            $target = (isset($attrs['target']) && '_self' === $attrs['target']) ? ''
                : ' target="_blank" rel="noopener noreferrer"';
            return '<a class="' . $class . '" href="' . intpScH($url) . '"' . $target . '>' . $inner . '</a>';
        }
        return '<span class="' . $class . '">' . $inner . '</span>';
    }

    $class = 'sc-badge' . $variant;
    if ($url) {
        return '<a class="' . $class . '" href="' . intpScH($url) . '" target="_blank" rel="noopener noreferrer">'
            . $inner . '</a>';
    }
    return '<span class="' . $class . '">' . $inner . '</span>';
}

/** tabs：解析内部原始 [tab]，输出 nav + panels（panel 内短代码由后续迭代继续渲染） */
function intpScRenderTabs($inner) {
    if (!preg_match_all('/\[tab\b([^\]]*?)\](.*?)\[\/tab\]/is', $inner, $tm, PREG_SET_ORDER)) {
        return $inner;
    }

    $nav = '';
    $panels = '';
    foreach ($tm as $i => $tab) {
        $attrs = intpScParseAttrs($tab[1]);
        $title = !empty($attrs['title']) ? $attrs['title'] : 'Tab ' . ($i + 1);
        $active = 0 === $i ? ' active' : '';
        $nav .= '<button type="button" class="sc-tab-link' . $active . '" data-index="' . $i
            . '" role="tab">' . intpScH($title) . '</button>';
        $panels .= '<section class="sc-tab-panel' . $active . '" role="tabpanel">'
            . intpScClean($tab[2]) . '</section>';
    }

    return '<div class="sc-tabs"><div class="sc-tabs-nav" role="tablist">' . $nav . '</div>'
        . '<div class="sc-tabs-panels">' . $panels . '</div></div>';
}

/** 小锁图标 */
function intpScLockSvg() {
    return '<svg viewBox="0 0 24 24" width="16" height="16" fill="none" stroke="currentColor" '
        . 'stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">'
        . '<rect x="4" y="10" width="16" height="11" rx="2"/><path d="M8 10V7a4 4 0 0 1 8 0v3"/></svg>';
}

/* ---------- Feed 短代码转纯文本（移植自 MfThemePlugin::convertShortcodes） ---------- */
function intpScConvertForFeed($content) {
    $tags = implode('|', intpScTags());
    $pattern = '/(<pre\b[^>]*>.*?<\/pre>|<code\b[^>]*>.*?<\/code>)'
        . '|\[(' . $tags . ')\b([^\]]*?)\](.*?)\[\/\2\]/is';

    for ($i = 0; $i < 10; $i++) {
        $converted = preg_replace_callback($pattern, function ($m) {
            if (!empty($m[1])) {
                return $m[1];
            }

            $tag = strtolower($m[2]);
            $inner = $m[4];

            if ('hide' === $tag) {
                return '此处是隐藏内容，请到文章页查看。';
            }
            if ('progress' === $tag) {
                $value = (float)preg_replace('/[^0-9.]/', '', $inner);
                return '进度: ' . $value . '%';
            }
            if ('tabs' === $tag) {
                return intpScFeedTabs($inner);
            }
            if ('tab' === $tag) {
                return intpScFeedTab($m[3], $inner);
            }
            if ('button' === $tag || 'badge' === $tag) {
                $url = '';
                if (preg_match('/\burl\s*=\s*(["\'])(.*?)\1/i', $m[3], $urlMatches)) {
                    $url = trim($urlMatches[2]);
                }
                if ('' !== $url && intpScUrl($url)) {
                    return '<a href="' . intpScH($url) . '">' . $inner . '</a>';
                }
            }

            return $inner;
        }, $content);

        if (null === $converted || $converted === $content) {
            break;
        }
        $content = $converted;
    }

    // Feed 输出全文，移除文章分页标记
    $content = preg_replace('/<!--\s*nextpage\s*-->/i', '', $content);

    return $content;
}

function intpScFeedTabs($inner) {
    if (!preg_match_all('/\[tab\b([^\]]*?)\](.*?)\[\/tab\]/is', $inner, $tabMatches)) {
        return $inner;
    }

    $parts = array();
    foreach ($tabMatches[1] as $index => $attrString) {
        $title = intpScParseTabTitle($attrString, $index + 1);
        $text = intpScCleanTabContent($tabMatches[2][$index]);
        $parts[] = '<strong>' . intpScH($title) . '</strong><br><br>' . $text;
    }
    return implode('<br><br>', $parts);
}

function intpScFeedTab($attrString, $inner) {
    $title = intpScParseTabTitle($attrString, 1);
    return '<strong>' . intpScH($title) . '</strong><br><br>' . intpScCleanTabContent($inner);
}

function intpScParseTabTitle($attrString, $defaultIndex) {
    $title = 'Tab ' . $defaultIndex;
    if (preg_match_all('/(\w+)\s*=\s*(["\'])(.*?)\2/i', $attrString, $attrMatches)) {
        foreach ($attrMatches[1] as $index => $key) {
            if ('title' === strtolower($key)) {
                $title = $attrMatches[3][$index];
                break;
            }
        }
    }
    return $title;
}

function intpScCleanTabContent($content) {
    return preg_replace('/^<br\s*\/?>|<br\s*\/?>$/i', '', trim($content));
}
