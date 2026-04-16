const CACHE_NAME = 'mikem-sav-v2';
const ASSETS = [
    '/login',
    '/css/app.css',
    '/images/logo.png',
    '/images/minilogo.png'
];

// Installation du Service Worker
self.addEventListener('install', event => {
    event.waitUntil(
        caches.open(CACHE_NAME)
            .then(cache => cache.addAll(ASSETS))
            .then(() => self.skipWaiting())
    );
});

// Activation
self.addEventListener('activate', event => {
    event.waitUntil(self.clients.claim());
});

// Stratégie de cache : Network First (on privilégie toujours les données fraîches)
self.addEventListener('fetch', event => {
    event.respondWith(
        fetch(event.request).catch(() => {
            return caches.match(event.request);
        })
    );
});
