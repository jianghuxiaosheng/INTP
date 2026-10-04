<?php
/**
 * GitHub 项目
 *
 * @package custom
 */
/* 说明：通过 GitHub API 展示仓库卡片（名称、简介、语言、Star、最近更新），结果缓存 6 小时。 */
if (!defined('__TYPECHO_ROOT_DIR__')) exit;
$this->need('header.php');

$options = \Typecho\Widget::widget('Widget_Options');
$user = intpGithubUser();
$projects = intpGithubProjects();
$pinned = intpGithubRepoNames();
?>

<div class="page-wrap">
    <div class="container">
        <div class="page-grid">

            <!-- ============ 主栏：GitHub 项目 ============ -->
            <main class="main">

                <div class="page-head gh-head">
                    <h1><?php $this->title(); ?></h1>
                    <?php if ('' !== $user): ?>
                    <a class="gh-profile-link" href="https://github.com/<?php echo htmlspecialchars($user, ENT_QUOTES, 'UTF-8'); ?>" target="_blank" rel="noopener noreferrer">@<?php echo htmlspecialchars($user, ENT_QUOTES, 'UTF-8'); ?></a>
                    <?php endif; ?>
                </div>

                <?php if (trim((string)$this->text) !== ''): ?>
                <div class="post-content tpl-intro"><?php $this->content(); ?></div>
                <?php endif; ?>

                <?php if ($projects): ?>
                <div class="gh-grid">
                    <?php foreach ($projects as $repo):
                        $repoUrl = intpScUrl($repo['html_url'] ?? '');
                        $homeUrl = intpScUrl($repo['homepage'] ?? '');
                    ?>
                    <article class="gh-card">
                        <h2 class="gh-card-title">
                            <?php if ('' !== $repoUrl): ?>
                            <a href="<?php echo intpScH($repoUrl); ?>" target="_blank" rel="noopener noreferrer"><?php echo htmlspecialchars($repo['full_name'], ENT_QUOTES, 'UTF-8'); ?></a>
                            <?php else: ?>
                            <span><?php echo htmlspecialchars($repo['full_name'], ENT_QUOTES, 'UTF-8'); ?></span>
                            <?php endif; ?>
                            <?php if (!empty($repo['fork'])): ?><span class="gh-badge">Fork</span><?php endif; ?>
                            <?php if (!empty($repo['archived'])): ?><span class="gh-badge gh-badge-muted">Archived</span><?php endif; ?>
                        </h2>
                        <p class="gh-card-desc"><?php echo htmlspecialchars((string)($repo['description'] !== null ? $repo['description'] : '暂无简介'), ENT_QUOTES, 'UTF-8'); ?></p>
                        <div class="gh-card-foot">
                            <span class="gh-meta">
                                <?php if (!empty($repo['language'])): ?>
                                <span class="gh-lang"><i class="gh-lang-dot" data-lang="<?php echo htmlspecialchars($repo['language'], ENT_QUOTES, 'UTF-8'); ?>"></i><?php echo htmlspecialchars($repo['language'], ENT_QUOTES, 'UTF-8'); ?></span>
                                <?php endif; ?>
                                <span class="gh-stat">★ <?php echo intpFormatNumber((int)$repo['stargazers_count']); ?></span>
                                <span class="gh-stat">⑂ <?php echo intpFormatNumber((int)$repo['forks_count']); ?></span>
                                <time datetime="<?php echo date('c', strtotime($repo['updated_at'])); ?>">更新于 <?php echo date('Y-m-d', strtotime($repo['updated_at'])); ?></time>
                            </span>
                            <?php if ('' !== $homeUrl): ?>
                            <a class="gh-home" href="<?php echo intpScH($homeUrl); ?>" target="_blank" rel="noopener noreferrer">访问主页</a>
                            <?php endif; ?>
                        </div>
                    </article>
                    <?php endforeach; ?>
                </div>
                <?php elseif ($pinned): ?>
                <p class="tpl-empty">暂时无法从 GitHub 获取项目信息，请稍后刷新再试。</p>
                <ul class="tpl-post-list">
                    <?php foreach ($pinned as $full): ?>
                    <li><a href="https://github.com/<?php echo htmlspecialchars($full, ENT_QUOTES, 'UTF-8'); ?>" target="_blank" rel="noopener noreferrer"><?php echo htmlspecialchars($full, ENT_QUOTES, 'UTF-8'); ?></a></li>
                    <?php endforeach; ?>
                </ul>
                <?php else: ?>
                <p class="tpl-empty">
                    请先在「后台 → 更改外观 → 设置外观」中填写 GitHub 主页地址，或在「GitHub 展示仓库」中逐行填写 owner/repo。
                    <?php if ('' !== $user): ?>也可以直接访问 <a href="https://github.com/<?php echo htmlspecialchars($user, ENT_QUOTES, 'UTF-8'); ?>" target="_blank" rel="noopener noreferrer">GitHub 主页</a>。<?php endif; ?>
                </p>
                <?php endif; ?>

            </main>

            <!-- ============ 侧栏 ============ -->
            <?php $this->need('sidebar.php'); ?>

        </div>
    </div>
</div>

<?php $this->need('footer.php'); ?>
