<?php if (!defined('__TYPECHO_ROOT_DIR__')) exit; ?>
<?php $this->need('header.php'); ?>

<div class="page-wrap">
    <div class="container">
        <div class="page-grid">

            <!-- ============ 主栏：搜索结果 ============ -->
            <main class="main">

                <div class="page-head archive-head">
                    <h1>搜索：<?php echo htmlspecialchars($this->request->s); ?></h1>
                    <p class="desc">共 <?php echo $this->getTotal(); ?> 个结果</p>
                </div>

                <div class="post-list">
                <?php if ($this->have()): ?>
                    <?php while ($this->next()): ?>
                    <article class="post-item">
                        <div class="post-meta">
                            <time><?php $this->date('Y-m-d'); ?></time>
                            <span>/</span>
                            <?php $this->category(' · '); ?>
                            <span>/</span>
                            <span><?php $this->commentsNum(_t('0 评论'), _t('1 评论'), _t('%d 评论')); ?></span>
                        </div>
                        <h2 class="post-title"><a href="<?php $this->permalink(); ?>"><?php $this->title(); ?></a></h2>
                        <p class="post-summary"><?php $this->excerpt(120); ?></p>
                        <a class="post-more" href="<?php $this->permalink(); ?>">阅读全文 →</a>
                    </article>
                    <?php endwhile; ?>
                    <?php $this->pageNav('«', '»', 3, '...', array('wrapClass' => 'pagination', 'currentClass' => 'current')); ?>
                <?php else: ?>
                    <p class="search-empty">没有找到与「<?php echo htmlspecialchars($this->request->s); ?>」相关的文章。</p>
                <?php endif; ?>
                </div>

            </main>

            <!-- ============ 侧栏 ============ -->
            <?php $this->need('sidebar.php'); ?>

        </div>
    </div>
</div>

<?php $this->need('footer.php'); ?>
