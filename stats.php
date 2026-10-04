<?php
/**
 * 站点统计
 *
 * @package custom
 */
/* 说明：展示文章 / 评论 / 分类 / 标签数量、总字数、运行天数及最近更新。 */
if (!defined('__TYPECHO_ROOT_DIR__')) exit;
$this->need('header.php');

$options = \Typecho\Widget::widget('Widget_Options');
$stat = \Widget\Stat::alloc();

/* 运行天数 */
$since = trim((string)$options->site_since);
if (preg_match('/^\d{4}$/', $since)) {
    $since .= '-01-01';
}
$runDays = max(1, (int)floor((time() - strtotime($since)) / 86400) + 1);

/* 全部文章总字数（按字符计） */
$db = \Typecho\Db::get();
$totalWords = (int)$db->fetchObject($db->select(array('SUM(CHAR_LENGTH(text))' => 'n'))
    ->from('table.contents')->where('type = ?', 'post')->where('status = ?', 'publish'))->n;

$cards = array(
    array('文章', $stat->publishedPostsNum, '篇'),
    array('独立页面', $stat->publishedPagesNum, '个'),
    array('评论', $stat->publishedCommentsNum, '条'),
    array('分类', $stat->categoriesNum, '个'),
    array('标签', $stat->tagsNum, '个'),
    array('总字数', intpFormatNumber($totalWords), ''),
    array('运行天数', intpFormatNumber($runDays), '天'),
);

$recentPosts = \Widget\Contents\Post\Recent::alloc(array('pageSize' => 8));
$categories = \Widget\Metas\Category\Rows::alloc();
$tags = \Widget\Metas\Tag\Cloud::alloc('ignoreZeroCount=1&limit=40');
?>

<div class="page-wrap">
    <div class="container">
        <div class="page-grid">

            <!-- ============ 主栏：站点统计 ============ -->
            <main class="main">

                <div class="page-head">
                    <h1><?php $this->title(); ?></h1>
                </div>

                <div class="stats-grid">
                    <?php foreach ($cards as $card): ?>
                    <div class="stat-card">
                        <span class="stat-num"><?php echo $card[1]; ?></span>
                        <span class="stat-label"><?php echo $card[0]; ?><?php if ('' !== $card[2]): ?><em><?php echo $card[2]; ?></em><?php endif; ?></span>
                    </div>
                    <?php endforeach; ?>
                </div>

                <section class="stats-block">
                    <h2 class="tpl-h2">最近更新</h2>
                    <ul class="tpl-post-list">
                        <?php while ($recentPosts->next()): ?>
                        <li>
                            <a href="<?php $recentPosts->permalink(); ?>"><?php $recentPosts->title(); ?></a>
                            <time datetime="<?php echo date('c', $recentPosts->created); ?>"><?php echo date('Y-m-d', $recentPosts->created); ?></time>
                        </li>
                        <?php endwhile; ?>
                    </ul>
                </section>

                <section class="stats-block">
                    <h2 class="tpl-h2">文章分类</h2>
                    <ul class="tpl-meta-list">
                        <?php while ($categories->next()): ?>
                        <li><a href="<?php $categories->permalink(); ?>"><?php $categories->name(); ?></a><span class="num"><?php echo (int)$categories->count; ?></span></li>
                        <?php endwhile; ?>
                    </ul>
                </section>

                <section class="stats-block">
                    <h2 class="tpl-h2">标签云</h2>
                    <?php $hasTag = false; ?>
                    <div class="tag-cloud">
                        <?php while ($tags->next()): $hasTag = true; ?>
                        <a class="tag-chip" href="<?php $tags->permalink(); ?>"><?php $tags->name(); ?><span class="num"><?php echo (int)$tags->count; ?></span></a>
                        <?php endwhile; ?>
                        <?php if (!$hasTag): ?><span class="tpl-empty-inline">暂无标签</span><?php endif; ?>
                    </div>
                </section>

            </main>

            <!-- ============ 侧栏 ============ -->
            <?php $this->need('sidebar.php'); ?>

        </div>
    </div>
</div>

<?php $this->need('footer.php'); ?>
