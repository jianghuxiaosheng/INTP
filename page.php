<?php if (!defined('__TYPECHO_ROOT_DIR__')) exit; ?>
<?php $this->need('header.php'); ?>

<div class="page-wrap">
    <div class="container">
        <div class="page-grid">

            <!-- ============ 主栏 ============ -->
            <main class="main">

                <article>
                    <header class="post-head">
                        <h1><?php $this->title(); ?></h1>
                    </header>

                    <div class="post-content">
                        <?php $this->content(); ?>
                    </div>

                    <?php $this->need('comments.php'); ?>
                </article>

            </main>

            <!-- ============ 侧栏 ============ -->
            <?php $this->need('sidebar.php'); ?>

        </div>
    </div>
</div>

<?php $this->need('footer.php'); ?>
