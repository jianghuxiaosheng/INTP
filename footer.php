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

<!-- 代码高亮 highlight.js（highlight.min.js 11.x 已内置全部 common 常用语言，无需额外加载 languages/common.min.js） -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/highlight.js/11.9.0/highlight.min.js"></script>
<script src="<?php $this->options->themeUrl('assets/js/main.js'); ?>"></script>
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
