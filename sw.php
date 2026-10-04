<?php
/**
 * Service Worker（PHP 包装输出 JS）
 * 通过 Service-Worker-Allowed: / 将作用域提升到站点根。
 */
header('Content-Type: application/javascript; charset=utf-8');
header('Service-Worker-Allowed: /');
header('Cache-Control: no-cache');
?>/* INTP theme Service Worker */
var CACHE = 'intp-sw-v2';
var OFFLINE_URL = '/';

self.addEventListener('install', function (event) {
    event.waitUntil(
        caches.open(CACHE).then(function (cache) {
            return cache.addAll([OFFLINE_URL]);
        }).then(function () {
            return self.skipWaiting();
        })
    );
});

self.addEventListener('activate', function (event) {
    event.waitUntil(
        caches.keys().then(function (keys) {
            return Promise.all(keys.map(function (key) {
                if (key !== CACHE) return caches.delete(key);
            }));
        }).then(function () {
            return self.clients.claim();
        })
    );
});

self.addEventListener('fetch', function (event) {
    var req = event.request;
    if (req.method !== 'GET') return;

    var url = new URL(req.url);

    // 后台与动态接口一律放行
    if (url.pathname.indexOf('/admin') === 0 || url.pathname.indexOf('/admin.php') === 0) return;
    if (url.search.indexOf('intp_action=') !== -1) return;

    // 跨源：network-first，失败回退缓存（CDN、头像等）
    if (url.origin !== self.location.origin) {
        event.respondWith(
            fetch(req).then(function (res) {
                if (res && (res.ok || res.type === 'opaque')) {
                    var copy = res.clone();
                    caches.open(CACHE).then(function (cache) { cache.put(req, copy); });
                }
                return res;
            }).catch(function () {
                return caches.match(req);
            })
        );
        return;
    }

    // 页面导航：network-first，离线时回退缓存 / 首页
    if (req.mode === 'navigate') {
        event.respondWith(
            fetch(req).then(function (res) {
                var copy = res.clone();
                caches.open(CACHE).then(function (cache) { cache.put(req, copy); });
                return res;
            }).catch(function () {
                return caches.match(req).then(function (cached) {
                    return cached || caches.match(OFFLINE_URL);
                });
            })
        );
        return;
    }

    // 同源静态资源：stale-while-revalidate
    if (/\.(?:jpe?g|png|gif|webp|svg|ico|bmp|css|js|mjs|woff2?|ttf|eot)(?:[?#]|$)/i.test(url.pathname)
        || url.pathname.indexOf('/usr/themes/INTP/assets/') !== -1) {
        event.respondWith(
            caches.match(req).then(function (cached) {
                var network = fetch(req).then(function (res) {
                    if (res && res.ok) {
                        var copy = res.clone();
                        caches.open(CACHE).then(function (cache) { cache.put(req, copy); });
                    }
                    return res;
                }).catch(function () { return cached; });
                return cached || network;
            })
        );
        return;
    }
});
