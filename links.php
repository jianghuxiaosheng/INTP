<?php
/**
 * 友情链接
 *
 * @package custom
 */
/* 说明：展示主题设置中配置的友情链接，格式：站点名称 | 网址 | 简介。 */
if (!defined('__TYPECHO_ROOT_DIR__')) exit;
$this->need('header.php');

$links = intpLinks();
?>

<div class="page-wrap">
    <div class="container">
        <div class="page-grid">

            <!-- ============ 主栏：友情链接 ============ -->
            <main class="main">

                <div class="page-head">
                    <h1><?php $this->title(); ?></h1>
                </div>

                <?php if (trim((string)$this->text) !== ''): ?>
                <div class="post-content tpl-intro"><?php $this->content(); ?></div>
                <?php endif; ?>

                <?php if ($links): ?>
                <div class="links-grid">
                    <?php foreach ($links as $link): ?>
                    <a class="link-card" href="<?php echo htmlspecialchars($link['url'], ENT_QUOTES, 'UTF-8'); ?>" target="_blank" rel="noopener noreferrer">
                        <span class="link-avatar" aria-hidden="true"><?php echo htmlspecialchars(mb_substr($link['name'], 0, 1, 'UTF-8'), ENT_QUOTES, 'UTF-8'); ?></span>
                        <span class="link-meta">
                            <span class="link-name"><?php echo htmlspecialchars($link['name'], ENT_QUOTES, 'UTF-8'); ?></span>
                            <span class="link-desc"><?php echo htmlspecialchars($link['desc'] !== '' ? $link['desc'] : $link['url'], ENT_QUOTES, 'UTF-8'); ?></span>
                        </span>
                    </a>
                    <?php endforeach; ?>
                </div>
                <?php else: ?>
                <p class="tpl-empty">还没有友链，请到「后台 → 更改外观 → 设置外观 → 友情链接」中按「名称 | 网址 | 简介」格式添加。</p>
                <?php endif; ?>

            </main>

            <!-- ============ 侧栏 ============ -->
            <?php $this->need('sidebar.php'); ?>

        </div>
    </div>
</div>

<?php $this->need('footer.php'); ?>
