<?php
/**
 * 极简双栏 Typecho 主题 · 侧边栏可左可右 · Tailwind CSS + shadcn-ui 风格 · IconPark 图标 · 代码高亮 · 时间轴归档
 *
 * @package INTP
 * @author imoyy
 * @version 1.0.0
 * @link https://github.com/imoyy
 */
if (!defined('__TYPECHO_ROOT_DIR__')) exit;
$this->need('header.php'); ?>

<div class="page-wrap">
    <div class="container">
        <div class="page-grid">

            <!-- ============ 主栏 ============ -->
            <main class="main">

                <!-- 页首信息 -->
                <?php if ($this->is('index')): ?>
                <div class="page-head">
                    <div class="intro">你好呀，欢迎来坐坐</div>
                    <h1><?php echo intpScH($this->options->site_intro ? $this->options->site_intro : '记录生活，也记录折腾。'); ?></h1>
                    <p class="desc"><?php echo intpScH($this->options->site_desc); ?></p>
                    <div class="meta">
                        <?php $stat = themeStat(); ?>
                        <span><b><?php echo $stat->publishedPostsNum; ?></b> 篇文章</span>
                        <span>/</span>
                        <span><b><?php echo $stat->categoriesNum; ?></b> 分类</span>
                        <span>/</span>
                        <span><b><?php echo $stat->tagsNum; ?></b> 标签</span>
                        <span>/</span>
                        <span>since <?php echo intpScH($this->options->site_since ? $this->options->site_since : '2024'); ?></span>
                    </div>
                </div>
                <?php else: ?>
                <div class="page-head archive-head">
                    <h1><?php $this->archiveTitle(array('category' => _t('分类：%s'), 'tag' => _t('标签：%s'), 'author' => _t('%s 的文章')), '', ''); ?></h1>
                    <p class="desc">共 <?php echo $this->getTotal(); ?> 篇文章</p>
                </div>
                <?php endif; ?>

                <!-- 置顶文章（仅首页第一页） -->
                <?php
                    $stickyPosts = array();
                    $stickyCids = array();
                    if ($this->is('index') && 1 === (int)$this->getCurrentPage()) {
                        $stickyPosts = intpStickyPosts();
                        $stickyCids = array_map('intval', array_column($stickyPosts, 'cid'));
                        $stickyViews = intpCounterValueMap($stickyCids, 'intp_views');
                    }
                ?>
                <?php if ($stickyPosts): ?>
                <div class="post-sticky-list">
                    <?php foreach ($stickyPosts as $stickyPost): ?>
                    <article class="post-item post-sticky">
                        <?php if (!empty($stickyPost['thumb_url'])): ?>
                        <a class="post-thumb post-thumb-small" href="<?php echo htmlspecialchars($stickyPost['permalink'], ENT_QUOTES, 'UTF-8'); ?>" aria-label="<?php echo htmlspecialchars($stickyPost['title'], ENT_QUOTES, 'UTF-8'); ?>">
                            <img src="<?php echo htmlspecialchars($stickyPost['thumb_url'], ENT_QUOTES, 'UTF-8'); ?>" alt="<?php echo htmlspecialchars($stickyPost['title'], ENT_QUOTES, 'UTF-8'); ?>" loading="lazy">
                        </a>
                        <?php endif; ?>
                        <div class="post-row-body">
                            <div class="post-meta">
                                <span class="sticky-badge">置顶</span>
                                <time><?php echo date('Y-m-d', (int)$stickyPost['created']); ?></time>
                                <?php if ('0' !== (string)$this->options->show_views): ?>
                                <span>/</span>
                                <?php intpViewsBadge($stickyViews[(int)$stickyPost['cid']]); ?>
                                <?php endif; ?>
                            </div>
                            <h2 class="post-title"><a href="<?php echo htmlspecialchars($stickyPost['permalink'], ENT_QUOTES, 'UTF-8'); ?>"><?php echo htmlspecialchars($stickyPost['title'], ENT_QUOTES, 'UTF-8'); ?></a></h2>
                            <p class="post-summary"><?php echo htmlspecialchars($stickyPost['excerpt'], ENT_QUOTES, 'UTF-8'); ?></p>
                            <a class="post-more" href="<?php echo htmlspecialchars($stickyPost['permalink'], ENT_QUOTES, 'UTF-8'); ?>">阅读全文 →</a>
                        </div>
                    </article>
                    <?php endforeach; ?>
                </div>
                <?php endif; ?>

                <!-- 文章列表 -->
                <div class="post-list">
                <?php while ($this->next()): ?>
                    <?php if (!empty($stickyCids) && in_array((int)$this->cid, $stickyCids, true)) continue; ?>
                    <?php
                        $layout = intpListLayout($this);
                        $thumb  = 'full' !== $layout ? intpThumb($this) : '';
                    ?>
                    <?php if ('full' === $layout): ?>
                    <article class="post-item post-full">
                        <div class="post-meta">
                            <time><?php $this->date('Y-m-d'); ?></time>
                            <span>/</span>
                            <?php $this->category(' · '); ?>
                            <span>/</span>
                            <span><?php $this->commentsNum(_t('0 评论'), _t('1 评论'), _t('%d 评论')); ?></span>
                            <?php if ('0' !== (string)$this->options->show_views): ?>
                            <span>/</span>
                            <?php intpViewsBadge(intpDisplayCount($this, 'intp_views')); ?>
                            <?php endif; ?>
                        </div>
                        <h2 class="post-title"><a href="<?php $this->permalink(); ?>"><?php $this->title(); ?></a></h2>
                        <div class="post-content"><?php $this->content(); ?></div>
                    </article>

                    <?php elseif ('big' === $layout && $thumb): ?>
                    <article class="post-item post-card">
                        <a class="post-thumb post-thumb-big" href="<?php $this->permalink(); ?>" aria-label="<?php echo htmlspecialchars($this->title, ENT_QUOTES, 'UTF-8'); ?>">
                            <img src="<?php echo htmlspecialchars($thumb, ENT_QUOTES, 'UTF-8'); ?>" alt="<?php echo htmlspecialchars($this->title, ENT_QUOTES, 'UTF-8'); ?>" loading="lazy">
                        </a>
                        <div class="post-card-body">
                            <div class="post-meta">
                                <time><?php $this->date('Y-m-d'); ?></time>
                                <span>/</span>
                                <?php $this->category(' · '); ?>
                                <span>/</span>
                                <span><?php $this->commentsNum(_t('0 评论'), _t('1 评论'), _t('%d 评论')); ?></span>
                                <?php if ('0' !== (string)$this->options->show_views): ?>
                                <span>/</span>
                                <?php intpViewsBadge(intpDisplayCount($this, 'intp_views')); ?>
                                <?php endif; ?>
                            </div>
                            <h2 class="post-title"><a href="<?php $this->permalink(); ?>"><?php $this->title(); ?></a></h2>
                            <p class="post-summary"><?php $this->excerpt(120); ?></p>
                            <a class="post-more" href="<?php $this->permalink(); ?>">阅读全文 →</a>
                        </div>
                    </article>

                    <?php else: ?>
                    <article class="post-item post-row<?php echo $thumb ? '' : ' no-thumb'; ?>">
                        <?php if ($thumb): ?>
                        <a class="post-thumb post-thumb-small" href="<?php $this->permalink(); ?>" aria-label="<?php echo htmlspecialchars($this->title, ENT_QUOTES, 'UTF-8'); ?>">
                            <img src="<?php echo htmlspecialchars($thumb, ENT_QUOTES, 'UTF-8'); ?>" alt="<?php echo htmlspecialchars($this->title, ENT_QUOTES, 'UTF-8'); ?>" loading="lazy">
                        </a>
                        <?php endif; ?>
                        <div class="post-row-body">
                            <div class="post-meta">
                                <time><?php $this->date('Y-m-d'); ?></time>
                                <span>/</span>
                                <?php $this->category(' · '); ?>
                                <span>/</span>
                                <span><?php $this->commentsNum(_t('0 评论'), _t('1 评论'), _t('%d 评论')); ?></span>
                                <?php if ('0' !== (string)$this->options->show_views): ?>
                                <span>/</span>
                                <?php intpViewsBadge(intpDisplayCount($this, 'intp_views')); ?>
                                <?php endif; ?>
                            </div>
                            <h2 class="post-title"><a href="<?php $this->permalink(); ?>"><?php $this->title(); ?></a></h2>
                            <p class="post-summary"><?php $this->excerpt(120); ?></p>
                            <a class="post-more" href="<?php $this->permalink(); ?>">阅读全文 →</a>
                        </div>
                    </article>
                    <?php endif; ?>
                <?php endwhile; ?>

                <?php intpPageNav($this); ?>
                </div>
            </main>

            <!-- ============ 侧栏 ============ -->
            <?php $this->need('sidebar.php'); ?>

        </div>
    </div>
</div>

<?php $this->need('footer.php'); ?>
