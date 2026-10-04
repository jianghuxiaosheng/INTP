<?php if (!defined('__TYPECHO_ROOT_DIR__')) exit; ?>

<footer class="site-footer">
  © <?php echo date('Y'); ?> <a href="<?php $this->options->siteUrl(); ?>"><?php $this->options->title(); ?></a>
  · Powered by <a href="https://typecho.org" rel="external nofollow">Typecho</a>
  <?php if ($this->options->rss_show != '0'): ?>· <a href="<?php $this->options->feedUrl(); ?>">RSS</a><?php endif; ?>
</footer>

<!-- FAB 返回顶部 -->
<button id="fab" class="fab" aria-label="返回顶部">
  <!-- IconPark: to-top -->
  <svg width="20" height="20" viewBox="0 0 48 48" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true"><path d="M24 40V10M10 24l14-14 14 14" stroke="currentColor" stroke-width="4" stroke-linecap="round" stroke-linejoin="round"/></svg>
</button>

<!-- 代码高亮 highlight.js（仅文章/独立页面加载，本地托管；11.x common 版已内置常用语言） -->
<?php if ($this->is('post') || $this->is('page')): ?>
<script src="<?php $this->options->themeUrl('assets/vendor/hljs/highlight.min.js'); ?>?v=<?php echo filemtime(__DIR__ . '/assets/vendor/hljs/highlight.min.js'); ?>"></script>
<?php endif; ?>
<script src="<?php $this->options->themeUrl('assets/js/main.js'); ?>?v=<?php echo filemtime(__DIR__ . '/assets/js/main.js'); ?>"></script>
<?php if ('0' !== (string)$this->options->show_sw): ?>
<script>
if ('serviceWorker' in navigator) {
  navigator.serviceWorker.register('<?php $this->options->themeUrl('sw.php'); ?>', { scope: '/' }).catch(function () {});
}
</script>
<?php endif; ?>

<?php $this->footer(); ?>
</body>
</html>
