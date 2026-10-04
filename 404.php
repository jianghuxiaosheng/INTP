<?php if (!defined('__TYPECHO_ROOT_DIR__')) exit; ?>
<?php $this->need('header.php'); ?>

<div class="page-wrap">
    <div class="container">
        <div class="search-empty" style="padding:80px 0;">
            <h1 style="font-size:52px;font-weight:700;margin:0 0 10px;">404</h1>
            <p>页面不存在，或已被移动到别处。</p>
            <a class="post-more" href="<?php $this->options->siteUrl(); ?>">← 回到首页</a>
        </div>
    </div>
</div>

<?php $this->need('footer.php'); ?>
