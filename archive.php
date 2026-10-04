<?php
/**
 * 归档
 *
 * @package custom
 */
/* 说明：独立页面用时以时间轴形式按年份展示全部文章；
   同时作为分类 / 标签 / 作者 / 日期归档的回退模板（仅显示当前归档范围内的文章）。 */
if (!defined('__TYPECHO_ROOT_DIR__')) exit;
$this->need('header.php');

/* 分类 / 标签 / 作者 / 日期归档：只遍历当前归档范围；作为独立页面时拉取全部文章 */
$isMetaArchive = $this->is('category') || $this->is('tag') || $this->is('author') || $this->is('date');
$years = array();
$viewCids = array();

if ($isMetaArchive) {
    while ($this->next()) {
        $year = date('Y', $this->created);
        $years[$year][] = array(
            'title'     => $this->title,
            'permalink' => $this->permalink,
            'created'   => $this->created,
            'cid'       => (int)$this->cid,
        );
        $viewCids[] = (int)$this->cid;
    }
    krsort($years);
    $archiveName = $this->getArchiveTitle();
    switch ($this->getArchiveType()) {
        case 'category':
            $headTitle = _t('分类：%s', $archiveName);
            break;
        case 'tag':
            $headTitle = _t('标签：%s', $archiveName);
            break;
        case 'author':
            $headTitle = _t('%s 的文章', $archiveName);
            break;
        default:
            $headTitle = _t('按时间归档');
    }
    $headDesc = _t('共 %d 篇文章', $this->getTotal());
} else {
    $allPosts = \Widget\Contents\Post\Recent::alloc(array('pageSize' => 10000));
    while ($allPosts->next()) {
        $year = date('Y', $allPosts->created);
        if (!isset($years[$year])) {
            $years[$year] = array();
        }
        $years[$year][] = array(
            'title'     => $allPosts->title,
            'permalink' => $allPosts->permalink,
            'created'   => $allPosts->created,
            'cid'       => (int)$allPosts->cid,
        );
        $viewCids[] = (int)$allPosts->cid;
    }
    krsort($years); /* 年份倒序 */
    $headTitle = _t('归档');
    $headDesc = _t('共 %d 篇文章，按时间倒序排列。', \Widget\Stat::alloc()->publishedPostsNum);
}
$viewMap = intpCounterValueMap($viewCids, 'intp_views');
$showViews = '0' !== (string)$this->options->show_views;
?>

<div class="page-wrap">
    <div class="container">
        <div class="page-grid">

            <!-- ============ 主栏：时间轴归档 ============ -->
            <main class="main">

                <div class="page-head archive-head">
                    <h1><?php echo htmlspecialchars($headTitle, ENT_QUOTES, 'UTF-8'); ?></h1>
                    <p class="desc"><?php echo htmlspecialchars($headDesc, ENT_QUOTES, 'UTF-8'); ?></p>
                </div>

                <?php if ($years): ?>
                <div class="timeline">
                <?php foreach ($years as $year => $items): ?>
                    <div class="tl-year">
                        <div class="tl-year-label"><?php echo $year; ?></div>
                        <?php foreach ($items as $item): ?>
                        <div class="tl-item">
                            <span class="tl-date"><?php echo date('m.d', $item['created']); ?></span>
                            <a class="tl-title" href="<?php echo $item['permalink']; ?>"><?php echo htmlspecialchars($item['title'], ENT_QUOTES, 'UTF-8'); ?></a>
                            <?php if ($showViews): ?>
                            <span class="tl-views"><?php intpViewsBadge($viewMap[$item['cid']]); ?></span>
                            <?php endif; ?>
                        </div>
                        <?php endforeach; ?>
                    </div>
                <?php endforeach; ?>
                </div>
                <?php if ($isMetaArchive): ?>
                <?php intpPageNav($this); ?>
                <?php endif; ?>
                <?php else: ?>
                <div class="tpl-empty">这个范围内暂时没有文章。</div>
                <?php endif; ?>

            </main>

            <!-- ============ 侧栏 ============ -->
            <?php $this->need('sidebar.php'); ?>

        </div>
    </div>
</div>

<?php $this->need('footer.php'); ?>
