/* ============================================================
   INTP 主题 · 交互脚本
   Author: imoyy
   ============================================================ */
(function () {
    'use strict';

    /* ---------- 移动端菜单 ---------- */
    var menuBtn = document.getElementById('menuBtn');
    var mobileMenu = document.getElementById('mobileMenu');
    if (menuBtn && mobileMenu) {
        menuBtn.addEventListener('click', function () {
            mobileMenu.classList.toggle('open');
        });
        /* 点击菜单链接后自动收起 */
        mobileMenu.querySelectorAll('a').forEach(function (a) {
            a.addEventListener('click', function () {
                mobileMenu.classList.remove('open');
            });
        });
    }

    /* ---------- FAB 返回顶部 ---------- */
    var fab = document.getElementById('fab');
    if (fab) {
        var onScroll = function () {
            fab.classList.toggle('show', window.scrollY > 400);
        };
        window.addEventListener('scroll', onScroll, { passive: true });
        onScroll();
        fab.addEventListener('click', function () {
            window.scrollTo({ top: 0, behavior: 'smooth' });
        });
    }

    /* ---------- 代码块增强：高亮 + 行号 + 语言标记 + 复制 + 长代码折叠 ---------- */
    var CODE_COLLAPSE_LINES = 20;

    /* 把高亮后的 DOM 按换行拆分为 .code-line，保留 hljs 嵌套着色 */
    function splitCodeLines(codeEl, trimTrailing) {
        var lines = [];
        var current = null;

        function newLine() {
            current = document.createElement('span');
            current.className = 'code-line';
            lines.push(current);
            return current;
        }
        function walk(node, parents) {
            Array.prototype.forEach.call(node.childNodes, function (child) {
                if (child.nodeType === 3) {
                    var parts = child.nodeValue.split('\n');
                    parts.forEach(function (part, i) {
                        if (i > 0) {
                            newLine();
                        }
                        if (part.length) {
                            var target = current;
                            parents.forEach(function (p) {
                                var t = p.cloneNode(false);
                                target.appendChild(t);
                                target = t;
                            });
                            target.appendChild(document.createTextNode(part));
                        }
                    });
                } else if (child.nodeType === 1) {
                    walk(child, parents.concat(child));
                }
            });
        }

        newLine();
        walk(codeEl, []);
        if (trimTrailing && lines.length > 1 && !lines[lines.length - 1].firstChild) {
            lines.pop();
        }
        codeEl.textContent = '';
        lines.forEach(function (l) { codeEl.appendChild(l); });
        return lines;
    }

    function initCodeBlocks() {
        document.querySelectorAll('.post-content pre').forEach(function (pre) {
            var code = pre.querySelector('code');
            if (!code || pre.dataset.codeReady) {
                return;
            }
            pre.dataset.codeReady = '1';

            /* 高亮 */
            if (window.hljs) {
                try { hljs.highlightElement(code); } catch (e) { /* ignore */ }
            }

            /* 语言标记 */
            var langMatch = (code.className + ' ' + pre.className)
                .match(/(?:language-|lang-)([A-Za-z0-9+#-]+)/);
            var lang = langMatch ? langMatch[1].toLowerCase() : '';

            /* 复制原文（拆分前保存） */
            var sourceText = code.textContent;

            /* 去掉末尾单个换行后拆行 */
            var trimLast = sourceText.charAt(sourceText.length - 1) === '\n';
            var lineEls = splitCodeLines(code, trimLast);

            pre.classList.add('code-block');

            /* 顶部工具条 */
            var header = document.createElement('div');
            header.className = 'code-header';

            if (lang) {
                var mark = document.createElement('span');
                mark.className = 'code-lang';
                mark.textContent = lang;
                header.appendChild(mark);
            }

            var copyBtn = document.createElement('button');
            copyBtn.className = 'copy-btn';
            copyBtn.type = 'button';
            copyBtn.textContent = '复制';
            copyBtn.addEventListener('click', function () {
                var done = function () {
                    copyBtn.textContent = '已复制 ✓';
                    setTimeout(function () { copyBtn.textContent = '复制'; }, 1600);
                };
                if (navigator.clipboard && navigator.clipboard.writeText) {
                    navigator.clipboard.writeText(sourceText).then(done, function () {
                        copyFallback(sourceText, done);
                    });
                } else {
                    copyFallback(sourceText, done);
                }
            });

            var expandBtn = null;
            var fade = null;
            if (lineEls.length > CODE_COLLAPSE_LINES) {
                pre.classList.add('collapsed');

                expandBtn = document.createElement('button');
                expandBtn.className = 'code-expand';
                expandBtn.type = 'button';
                expandBtn.textContent = '展开全部';
                expandBtn.addEventListener('click', function () {
                    var collapsed = pre.classList.contains('collapsed');
                    if (collapsed) {
                        pre.classList.remove('collapsed');
                        pre.classList.add('expanded');
                        expandBtn.textContent = '折叠代码';
                        fade.style.opacity = '0';
                    } else {
                        pre.scrollTop = 0;
                        pre.classList.add('collapsed');
                        pre.classList.remove('expanded');
                        expandBtn.textContent = '展开全部';
                        fade.style.opacity = '1';
                    }
                });
                header.appendChild(expandBtn);

                fade = document.createElement('div');
                fade.className = 'code-fade';
            }

            header.appendChild(copyBtn);
            pre.insertBefore(header, pre.firstChild);
            if (fade) {
                pre.appendChild(fade);
            }

            /* 滚到底部时隐藏遮罩 */
            if (fade) {
                pre.addEventListener('scroll', function () {
                    var bottom = pre.scrollHeight - pre.scrollTop - pre.clientHeight;
                    fade.style.opacity = bottom < 10 ? '0' : '1';
                });
            }

            /* 精确计算折叠高度：工具条 + 上下内边距 + 20 行 */
            if (lineEls.length > CODE_COLLAPSE_LINES) {
                var cs = window.getComputedStyle(code);
                var lineH = lineEls[0].getBoundingClientRect().height;
                var h = header.offsetHeight
                    + parseFloat(cs.paddingTop)
                    + CODE_COLLAPSE_LINES * lineH
                    + parseFloat(cs.paddingBottom)
                    + (pre.offsetHeight - pre.clientHeight) - 1;
                pre.style.setProperty('--collapse-h', h + 'px');
            }
        });
    }

    function copyFallback(text, done) {
        var ta = document.createElement('textarea');
        ta.value = text;
        ta.style.position = 'fixed';
        ta.style.opacity = '0';
        document.body.appendChild(ta);
        ta.select();
        try {
            document.execCommand('copy');
            done();
        } catch (e) {
            /* ignore */
        }
        document.body.removeChild(ta);
    }

    /* ---------- 文章点赞（事件委托，PJAX 友好） ---------- */
    document.addEventListener('click', function (e) {
        var btn = e.target.closest && e.target.closest('.intp-like-btn');
        if (!btn || btn.dataset.liked === '1' || btn.dataset.busy === '1') return;
        btn.dataset.busy = '1';
        fetch(btn.dataset.url, {
            method: 'POST',
            headers: { 'X-Requested-With': 'XMLHttpRequest' },
            credentials: 'same-origin'
        }).then(function (res) {
            return res.json().then(function (data) {
                return { ok: res.ok, data: data };
            });
        }).then(function (r) {
            if (r.ok && r.data && r.data.ok) {
                btn.dataset.liked = '1';
                btn.classList.add('is-liked');
                var text = btn.querySelector('.like-text');
                if (text) text.textContent = '已赞';
                var count = btn.querySelector('.like-count');
                if (count) count.textContent = r.data.likes;
            } else {
                btn.dataset.busy = '';
            }
        }).catch(function () {
            btn.dataset.busy = '';
        });
    });

    /* ---------- 短代码 Tabs 切换（事件委托） ---------- */
    document.addEventListener('click', function (e) {
        var btn = e.target.closest && e.target.closest('.sc-tabs-nav .sc-tab-link');
        if (!btn) return;
        var tabs = btn.closest('.sc-tabs');
        if (!tabs) return;
        var idx = btn.getAttribute('data-index');
        tabs.querySelectorAll('.sc-tabs-nav .sc-tab-link').forEach(function (b) {
            b.classList.toggle('active', b === btn);
        });
        tabs.querySelectorAll('.sc-tabs-panels > .sc-tab-panel').forEach(function (p, i) {
            p.classList.toggle('active', String(i) === String(idx));
        });
    });

    /* ---------- 章节目录 scrollspy ---------- */
    var tocObserver = null;
    function initToc() {
        var toc = document.querySelector('.intp-toc');
        if (!toc) return;
        if (tocObserver) { tocObserver.disconnect(); tocObserver = null; }

        var links = {};
        toc.querySelectorAll('.toc-link').forEach(function (a) {
            links[a.getAttribute('data-target')] = a;
        });
        var headings = Object.keys(links)
            .map(function (id) { return document.getElementById(id); })
            .filter(Boolean);
        if (!headings.length) return;

        function setActive(id) {
            toc.querySelectorAll('.toc-link.active').forEach(function (a) {
                a.classList.remove('active');
            });
            if (id && links[id]) {
                links[id].classList.add('active');
            }
        }

        if (!('IntersectionObserver' in window)) return;

        var visible = [];
        tocObserver = new IntersectionObserver(function (entries) {
            entries.forEach(function (en) {
                var pos = visible.indexOf(en.target.id);
                if (en.isIntersecting) {
                    if (pos === -1) visible.push(en.target.id);
                } else if (pos !== -1) {
                    visible.splice(pos, 1);
                }
            });
            setActive(visible.length ? visible[0] : null);
        }, { rootMargin: '-80px 0px -75% 0px', threshold: 0 });

        headings.forEach(function (h) { tocObserver.observe(h); });

        /* 点击目录项立即高亮（滚动监听随后校正） */
        toc.addEventListener('click', function (e) {
            var link = e.target.closest && e.target.closest('.toc-link');
            if (link) setActive(link.getAttribute('data-target'));
        });
    }
    window.intpInitToc = initToc;

    /* ---------- 主题配色切换（localStorage 持久化，head 预置脚本防闪烁） ---------- */
    function initThemeColor() {
        var dots = document.querySelectorAll('.color-dot');
        if (!dots.length) return;

        var saved = 'blue';
        try {
            var v = localStorage.getItem('intp_accent');
            if (v) saved = v;
        } catch (e) {}

        function paint(key) {
            if (key && key !== 'blue') {
                document.documentElement.setAttribute('data-accent', key);
            } else {
                document.documentElement.removeAttribute('data-accent');
            }
        }
        function mark(key) {
            dots.forEach(function (dot) {
                dot.classList.toggle('active', dot.getAttribute('data-accent') === key);
            });
        }

        paint(saved);
        mark(saved);
        dots.forEach(function (dot) {
            dot.addEventListener('click', function () {
                var key = dot.getAttribute('data-accent') || 'blue';
                paint(key);
                mark(key);
                try {
                    if (key === 'blue') localStorage.removeItem('intp_accent');
                    else localStorage.setItem('intp_accent', key);
                } catch (e) {}
            });
        });
    }
    window.intpInitThemeColor = initThemeColor;

    /* ---------- PJAX 无刷新跳转 ---------- */
    function refreshBehaviors() {
        initCodeBlocks();
        initToc();
        initThemeColor();
    }
    window.intpRefresh = refreshBehaviors;

    function initPjax() {
        if (document.body.getAttribute('data-pjax') !== '1') return;
        if (!window.fetch || !window.DOMParser || !window.history || !window.history.pushState) return;
        /* 滚动位置由 PJAX 自行保存 / 恢复，避免浏览器自动恢复互相抢占 */
        if ('scrollRestoration' in history) {
            history.scrollRestoration = 'manual';
        }

        var STATIC_RE = /\.(?:jpe?g|png|gif|webp|svg|ico|bmp|css|js|mjs|json|xml|txt|zip|rar|7z|gz|tar|pdf|docx?|xlsx?|pptx?|mp[34]|mov|avi|webm|woff2?|ttf|eot)(?:[?#]|$)/i;

        /* 顶部进度条 */
        var bar = document.createElement('div');
        bar.id = 'pjax-progress';
        document.body.appendChild(bar);
        var progressTimer = null;
        function barStart() {
            bar.classList.add('is-active');
            bar.style.width = '0';
            void bar.offsetWidth; /* 强制重排以重新触发过渡 */
            bar.style.width = '42%';
            clearTimeout(progressTimer);
            progressTimer = setTimeout(function () { bar.style.width = '78%'; }, 350);
        }
        function barDone() {
            clearTimeout(progressTimer);
            bar.style.width = '100%';
            setTimeout(function () {
                bar.classList.remove('is-active');
                bar.style.width = '0';
            }, 180);
        }

        function isInternal(a) {
            if (!a.getAttribute('href')) return false;
            if (a.hasAttribute('download')) return false;
            if (a.hasAttribute('data-no-pjax')) return false;
            var target = a.getAttribute('target');
            if (target && target !== '_self') return false;
            var rel = (a.getAttribute('rel') || '').toLowerCase();
            if (/(^|\s)(external|nofollow)(\s|$)/.test(rel)) return false;
            var url;
            try { url = new URL(a.href, window.location.href); } catch (e) { return false; }
            if (url.origin !== window.location.origin) return false;
            if (url.protocol !== 'http:' && url.protocol !== 'https:') return false;
            if (url.pathname.indexOf('/admin/') !== -1) return false;
            if (url.pathname.indexOf('/admin.php') !== -1) return false;
            if (/(?:^|\/)feed\/?$/.test(url.pathname)) return false;
            if (url.search.indexOf('intp_action=') !== -1) return false;
            if (STATIC_RE.test(url.pathname)) return false;
            return true;
        }

        /* 同步顶部导航 / 移动菜单高亮 */
        function syncNav() {
            var here = window.location.pathname + window.location.search;
            document.querySelectorAll('.nav a, .mobile-menu a').forEach(function (a) {
                var u;
                try { u = new URL(a.href, window.location.href); } catch (e) { return; }
                a.classList.toggle('active', u.pathname + u.search === here);
            });
        }

        /* 同步 meta description 等可替换头信息 */
        function syncMeta(doc) {
            var nd = doc.querySelector('meta[name="description"]');
            var od = document.querySelector('meta[name="description"]');
            if (od) {
                if (nd) {
                    od.setAttribute('content', nd.getAttribute('content') || '');
                }
            } else if (nd) {
                document.head.appendChild(nd.cloneNode());
            }
        }

        var loading = false;

        function renderPage(html, finalUrl, opts) {
            var doc = new DOMParser().parseFromString(html, 'text/html');
            var newWrap = doc.querySelector('.page-wrap');
            var oldWrap = document.querySelector('.page-wrap');
            if (!newWrap || !oldWrap) {
                window.location.href = finalUrl;
                return;
            }

            /* 前进导航：先把当前滚动位置写回当前历史项 */
            if (opts.push) {
                history.replaceState(
                    { pjax: 1, x: window.scrollX, y: window.scrollY },
                    '', window.location.href
                );
            }

            document.title = doc.title || document.title;
            if (doc.body.className) {
                document.body.className = doc.body.className;
            }
            syncMeta(doc);
            oldWrap.replaceWith(newWrap);
            syncNav();
            if (mobileMenu) mobileMenu.classList.remove('open');

            if (opts.push) {
                history.pushState({ pjax: 1, x: 0, y: 0 }, '', finalUrl);
            }

            refreshBehaviors();

            if (opts.push) {
                if (opts.hash) {
                    var target = document.getElementById(opts.hash);
                    if (!target) {
                        try {
                            var esc = window.CSS && CSS.escape ? CSS.escape(opts.hash) : null;
                            target = esc
                                ? document.querySelector('a[name="' + esc + '"]')
                                : null;
                        } catch (e) {
                            target = null;
                        }
                    }
                    if (target) {
                        target.scrollIntoView();
                    } else {
                        window.scrollTo(0, 0);
                    }
                } else {
                    window.scrollTo(0, 0);
                }
            } else {
                /* 前进 / 后退：恢复历史项记录的滚动位置 */
                window.scrollTo(opts.x || 0, opts.y || 0);
            }

            barDone();
            loading = false;
        }

        function fetchPage(url, opts) {
            if (loading) return;
            loading = true;
            barStart();
            fetch(url, {
                method: 'GET',
                credentials: 'same-origin',
                headers: { 'X-PJAX': '1' }
            }).then(function (res) {
                /* 不检查 res.ok：404 主题页同样需要替换显示 */
                return res.text().then(function (html) {
                    return { html: html, finalUrl: res.url || url };
                });
            }).then(function (r) {
                renderPage(r.html, r.finalUrl, opts);
            }).catch(function () {
                barDone();
                loading = false;
                window.location.href = url;
            });
        }

        /* 链接点击拦截 */
        document.addEventListener('click', function (e) {
            if (e.defaultPrevented) return;
            if (e.button !== 0 || e.metaKey || e.ctrlKey || e.shiftKey || e.altKey) return;
            var a = e.target.closest && e.target.closest('a[href]');
            if (!a || !isInternal(a)) return;

            var url = new URL(a.href, window.location.href);
            var fullNew = url.pathname + url.search + url.hash;
            var fullCur = window.location.pathname + window.location.search + window.location.hash;
            if (fullNew === fullCur) return;

            var hash = url.hash ? url.hash.slice(1) : '';
            /* 同页纯 hash 链接交给浏览器原生处理（锚点跳转） */
            if (hash && url.pathname + url.search === window.location.pathname + window.location.search) {
                return;
            }
            if (loading) return;

            e.preventDefault();
            fetchPage(url.pathname + url.search + url.hash, { push: true, hash: hash });
        });

        /* 搜索表单转为 PJAX 导航 */
        document.addEventListener('submit', function (e) {
            var form = e.target.closest && e.target.closest('.search-form');
            if (!form || form.hasAttribute('data-no-pjax')) return;
            var input = form.querySelector('input[name="s"]');
            var u;
            try {
                u = new URL(form.getAttribute('action') || window.location.href, window.location.href);
            } catch (err) {
                return;
            }
            var q = input ? input.value.trim() : '';
            if (q) {
                u.searchParams.set('s', q);
            } else {
                u.searchParams.delete('s');
            }
            if (u.origin !== window.location.origin) return;
            e.preventDefault();
            if (loading) return;
            fetchPage(u.pathname + u.search, { push: true, hash: '' });
        });

        /* 前进 / 后退 */
        window.addEventListener('popstate', function (e) {
            if (loading) return;
            var st = e.state || {};
            fetchPage(window.location.href, {
                push: false,
                x: typeof st.x === 'number' ? st.x : 0,
                y: typeof st.y === 'number' ? st.y : 0
            });
        });
    }

    /* ---------- 注册悬浮窗（header 常驻、不参与 PJAX 替换，绑定一次即可） ---------- */
    function initRegister() {
        var openBtns = document.querySelectorAll('.intp-register-open');
        var modal = document.getElementById('intpRegisterModal');
        if (!modal || !openBtns.length) return;

        var form = document.getElementById('intpRegForm');
        var alertBox = document.getElementById('intpRegAlert');
        var submitBtn = form.querySelector('.intp-reg-submit');
        var successBox = document.getElementById('intpRegSuccess');
        var msgEl = document.getElementById('intpRegMsg');
        var passWrap = document.getElementById('intpRegPassWrap');
        var passCode = document.getElementById('intpRegPass');
        var loginLink = document.getElementById('intpRegLoginLink');
        var enterBtn = document.getElementById('intpRegEnter');
        var mobileMenu = document.getElementById('mobileMenu');

        function clearErrors() {
            Array.prototype.forEach.call(form.querySelectorAll('.intp-field-error'), function (el) {
                el.textContent = '';
            });
            alertBox.hidden = true;
            alertBox.textContent = '';
        }
        function showAlert(msg) {
            alertBox.textContent = msg;
            alertBox.hidden = false;
        }
        function openModal() {
            clearErrors();
            form.hidden = false;
            successBox.hidden = true;
            submitBtn.disabled = false;
            modal.hidden = false;
            document.body.classList.add('intp-modal-open');
            if (mobileMenu) mobileMenu.classList.remove('open');
            window.setTimeout(function () {
                var n = document.getElementById('intpRegName');
                if (n) n.focus();
            }, 40);
        }
        function closeModal() {
            modal.hidden = true;
            document.body.classList.remove('intp-modal-open');
        }

        Array.prototype.forEach.call(openBtns, function (btn) {
            btn.addEventListener('click', openModal);
        });
        modal.addEventListener('click', function (e) {
            if (e.target.closest && e.target.closest('[data-intp-close]')) closeModal();
        });
        document.addEventListener('keydown', function (e) {
            if (e.key === 'Escape' && !modal.hidden) closeModal();
        });
        enterBtn.addEventListener('click', function () { window.location.reload(); });

        form.addEventListener('submit', function (e) {
            e.preventDefault();
            clearErrors();

            var pwd = document.getElementById('intpRegPassword').value;
            var cf = document.getElementById('intpRegConfirm').value;
            if (pwd && pwd !== cf) {
                var cfErr = form.querySelector('.intp-field-error[data-error-for="confirm"]');
                if (cfErr) cfErr.textContent = '两次输入的密码不一致';
                return;
            }

            submitBtn.disabled = true;
            fetch(form.action, {
                method: 'POST',
                headers: { 'X-Requested-With': 'XMLHttpRequest' },
                credentials: 'same-origin',
                body: new URLSearchParams(new FormData(form))
            }).then(function (res) {
                return res.json().then(function (data) {
                    return { status: res.status, data: data };
                });
            }).then(function (r) {
                var d = r.data || {};
                if (d.ok) {
                    form.hidden = true;
                    successBox.hidden = false;
                    msgEl.textContent = d.message || '注册成功';
                    if (d.generatedPassword) {
                        passWrap.hidden = false;
                        passCode.textContent = d.generatedPassword;
                    } else {
                        passWrap.hidden = true;
                    }
                    if (d.logged) {
                        loginLink.hidden = true;
                        enterBtn.hidden = false;
                        /* 自设密码无需展示凭据，稍作提示后自动刷新 */
                        if (!d.generatedPassword) {
                            window.setTimeout(function () { window.location.reload(); }, 900);
                        }
                    } else {
                        loginLink.hidden = false;
                        enterBtn.hidden = true;
                    }
                    return;
                }
                submitBtn.disabled = false;
                if (r.status === 422 && d.errors) {
                    Object.keys(d.errors).forEach(function (key) {
                        var el = form.querySelector('.intp-field-error[data-error-for="' + key + '"]');
                        if (el) el.textContent = d.errors[key];
                    });
                } else {
                    showAlert(d.message || '注册失败，请稍后再试。');
                }
            }).catch(function () {
                submitBtn.disabled = false;
                showAlert('网络异常，请稍后再试。');
            });
        });
    }

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', function () {
            initCodeBlocks();
            initToc();
            initThemeColor();
            initPjax();
            initRegister();
        });
    } else {
        initCodeBlocks();
        initToc();
        initThemeColor();
        initPjax();
        initRegister();
    }
})();
