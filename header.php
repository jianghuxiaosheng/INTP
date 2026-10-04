<?php if (!defined('__TYPECHO_ROOT_DIR__')) exit; ?>
<!DOCTYPE html>
<html lang="zh-CN">
<head>
<meta charset="<?php $this->options->charset(); ?>">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<script>(function(){try{var c=localStorage.getItem('intp_accent');if(c&&c!=='blue')document.documentElement.setAttribute('data-accent',c);}catch(e){}})();</script>
<meta name="generator" content="Typecho">
<meta name="template" content="INTP theme by imoyy">
<title><?php $this->archiveTitle(array(
    'category' => _t('分类 %s'),
    'search'   => _t('搜索 %s'),
    'tag'      => _t('标签 %s'),
    'author'   => _t('%s 的文章')
), '', ' - '); ?><?php $this->options->title(); ?></title>
<link rel="pingback" href="<?php $this->options->xmlRpcUrl(); ?>">
<link rel="alternate" type="application/rss+xml" title="RSS 2.0" href="<?php $this->options->feedUrl(); ?>">
<link rel="alternate" type="application/atom+xml" title="Atom 0.3" href="<?php $this->options->feedUrl('/atom/'); ?>">

<link rel="stylesheet" href="<?php $this->options->themeUrl('assets/css/style.css'); ?>">

<?php if ($this->is('post') || $this->is('page')): ?>
<!-- 代码高亮 highlight.js（仅文章/独立页面加载，本地托管） -->
<link rel="stylesheet" href="<?php $this->options->themeUrl('assets/vendor/hljs/github-dark.min.css'); ?>">
<?php endif; ?>

<?php $this->header('rss2=&rss1=&atom='); ?>
<?php intpSeoExtra($this); ?>
</head>
<body class="<?php echo themeSidebarClass(); ?>"<?php if ('0' !== (string)$this->options->show_pjax): ?> data-pjax="1"<?php endif; ?>>

<!-- ============ AppBar ============ -->
<header class="appbar">
  <div class="container appbar-inner">
    <a class="site-name" href="<?php $this->options->siteUrl(); ?>"><?php $this->options->title(); ?><b>.</b></a>

    <nav class="nav">
      <a href="<?php $this->options->siteUrl(); ?>"<?php if ($this->is('index')): ?> class="active"<?php endif; ?>>首页</a>
      <?php \Widget\Contents\Page\Rows::alloc()->to($navPages); while ($navPages->next()): ?>
      <a href="<?php $navPages->permalink(); ?>"<?php if ($this->is('page', $navPages->slug)): ?> class="active"<?php endif; ?>><?php $navPages->title(); ?></a>
      <?php endwhile; ?>
    </nav>

    <button id="menuBtn" class="menu-btn" aria-label="菜单">☰</button>

    <!-- IconPark: search -->
    <form class="search-form" method="get" action="<?php $this->options->siteUrl(); ?>">
      <span class="ic">
        <svg width="16" height="16" viewBox="0 0 48 48" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true"><circle cx="21" cy="21" r="11" stroke="currentColor" stroke-width="3.5"/><path d="M29.5 29.5L41 41" stroke="currentColor" stroke-width="3.5" stroke-linecap="round"/></svg>
      </span>
      <input class="search-input" type="search" name="s" placeholder="搜索文章…" aria-label="搜索">
    </form>

    <?php if ($this->options->allowRegister && !\Widget\User::alloc()->hasLogin()): ?>
    <button type="button" class="register-btn intp-register-open">注册</button>
    <?php endif; ?>
  </div>

  <!-- 移动端菜单 -->
  <nav class="mobile-menu" id="mobileMenu">
    <a href="<?php $this->options->siteUrl(); ?>"<?php if ($this->is('index')): ?> class="active"<?php endif; ?>>首页</a>
    <?php \Widget\Contents\Page\Rows::alloc()->to($mobilePages); while ($mobilePages->next()): ?>
    <a href="<?php $mobilePages->permalink(); ?>"<?php if ($this->is('page', $mobilePages->slug)): ?> class="active"<?php endif; ?>><?php $mobilePages->title(); ?></a>
    <?php endwhile; ?>
    <?php if ($this->options->allowRegister && !\Widget\User::alloc()->hasLogin()): ?>
    <button type="button" class="intp-register-open intp-mobile-reg">注册</button>
    <?php endif; ?>
  </nav>
</header>

<?php if ($this->options->allowRegister && !\Widget\User::alloc()->hasLogin()): ?>
<!-- ============ 注册悬浮窗 ============ -->
<div class="intp-modal" id="intpRegisterModal" hidden role="dialog" aria-modal="true" aria-labelledby="intpRegTitle">
  <div class="intp-modal-overlay" data-intp-close></div>
  <div class="intp-modal-card" role="document">
    <button type="button" class="intp-modal-close" data-intp-close aria-label="关闭注册窗口">
      <svg width="16" height="16" viewBox="0 0 48 48" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true"><path d="M8 8L40 40M40 8L8 40" stroke="currentColor" stroke-width="4" stroke-linecap="round"/></svg>
    </button>
    <h2 class="intp-modal-title" id="intpRegTitle">注册账号</h2>
    <p class="intp-modal-sub">注册后即可参与评论互动</p>

    <form id="intpRegForm" class="intp-reg-form" method="post" action="<?php $this->options->siteUrl('?intp_action=register'); ?>">
      <div class="intp-field">
        <label for="intpRegName">用户名</label>
        <input type="text" id="intpRegName" name="name" required minlength="2" maxlength="32" autocomplete="username" placeholder="2-32 个字符">
        <p class="intp-field-error" data-error-for="name"></p>
      </div>
      <div class="intp-field">
        <label for="intpRegMail">邮箱</label>
        <input type="email" id="intpRegMail" name="mail" required maxlength="64" autocomplete="email" placeholder="you@example.com">
        <p class="intp-field-error" data-error-for="mail"></p>
      </div>
      <div class="intp-field">
        <label for="intpRegPassword">密码 <span class="intp-label-hint">（留空则自动生成）</span></label>
        <input type="password" id="intpRegPassword" name="password" minlength="6" maxlength="18" autocomplete="new-password" placeholder="6-18 位，留空则随机生成">
        <p class="intp-field-error" data-error-for="password"></p>
      </div>
      <div class="intp-field">
        <label for="intpRegConfirm">确认密码</label>
        <input type="password" id="intpRegConfirm" name="confirm" minlength="6" maxlength="18" autocomplete="new-password" placeholder="再次输入密码">
        <p class="intp-field-error" data-error-for="confirm"></p>
      </div>

      <div class="intp-modal-alert" id="intpRegAlert" hidden role="alert"></div>

      <button type="submit" class="submit-btn intp-reg-submit">注册</button>
      <p class="intp-modal-foot">已有账号？<a href="<?php $this->options->adminUrl(); ?>">直接登录</a></p>
    </form>

    <div class="intp-reg-success" id="intpRegSuccess" hidden>
      <p class="intp-reg-msg" id="intpRegMsg"></p>
      <p class="intp-reg-pass" id="intpRegPassWrap" hidden>请立即保存你的密码：<code id="intpRegPass"></code></p>
      <div class="intp-reg-actions">
        <a class="submit-btn" id="intpRegLoginLink" href="<?php $this->options->adminUrl(); ?>" hidden>前往登录</a>
        <button type="button" class="submit-btn" id="intpRegEnter" hidden>我已保存，进入站点</button>
      </div>
    </div>
  </div>
</div>
<?php endif; ?>
