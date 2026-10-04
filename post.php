<?php if (!defined('__TYPECHO_ROOT_DIR__')) exit; ?>
<?php $this->need('header.php'); ?>

<div class="page-wrap">
    <div class="container">
        <div class="page-grid">

            <!-- ============ 主栏 ============ -->
            <main class="main">

                <article>
                    <header class="post-head">
                        <div class="post-meta">
                            <time><?php $this->date('Y-m-d'); ?></time>
                            <span>/</span>
                            <?php $this->category(' · '); ?>
                            <span>/</span>
                            <span><?php $this->commentsNum(_t('0 评论'), _t('1 评论'), _t('%d 评论')); ?></span>
                            <?php if ('0' !== (string)$this->options->show_views): ?>
                            <span>/</span>
                            <span class="post-views">
                                <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>
                                <?php echo intpDisplayCount($this, 'intp_views'); ?> <?php echo _t('阅读'); ?>
                            </span>
                            <?php endif; ?>
                        </div>
                        <h1><?php $this->title(); ?></h1>
                        <div class="post-tags"><?php $this->tags(' ', true, '无标签'); ?></div>
                    </header>

                    <?php $postThumb = ('0' !== $this->options->post_thumb) ? intpThumb($this) : ''; ?>
                    <?php if ($postThumb): ?>
                    <div class="post-banner">
                        <img src="<?php echo htmlspecialchars($postThumb, ENT_QUOTES, 'UTF-8'); ?>" alt="<?php echo htmlspecialchars($this->title, ENT_QUOTES, 'UTF-8'); ?>">
                    </div>
                    <?php endif; ?>

                    <div class="post-content">
                        <?php $this->content(); ?>
                    </div>

                    <?php if ('0' !== (string)$this->options->show_likes):
                        $isLiked = in_array((int)$this->cid, intpCookieCids('intp_liked'), true);
                        $sep = (false === strpos((string)$this->permalink, '?')) ? '?' : '&';
                        $likeUrl = (string)$this->permalink . $sep . 'intp_action=like';
                    ?>
                    <div class="post-like">
                        <button type="button"
                                class="intp-like-btn<?php echo $isLiked ? ' is-liked' : ''; ?>"
                                data-url="<?php echo htmlspecialchars($likeUrl, ENT_QUOTES, 'UTF-8'); ?>"
                                data-liked="<?php echo $isLiked ? '1' : '0'; ?>">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"/></svg>
                            <span class="like-text"><?php echo $isLiked ? _t('已赞') : _t('点赞'); ?></span>
                            <span class="like-count"><?php echo intpDisplayCount($this, 'intp_likes'); ?></span>
                        </button>
                    </div>
                    <?php endif; ?>

                    <nav class="post-nav">
                        <?php $this->thePrev('<span>« %s</span>', _t('已经是第一篇文章了')); ?>
                        <?php $this->theNext('<span>%s »</span>', _t('已经是最后一篇文章了')); ?>
                    </nav>

                    <?php $this->need('comments.php'); ?>
                </article>

            </main>

            <!-- ============ 侧栏 ============ -->
            <?php $this->need('sidebar.php'); ?>

        </div>
    </div>
</div>

<?php $this->need('footer.php'); ?>
