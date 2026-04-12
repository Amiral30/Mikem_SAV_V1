// Service Worker minimal pour permettre l'installation PWA
const CACHE_NAME = 'sav-mikem-v1';
const urlsToCache = [
  '/',
  '/css/app.css',
  '/images/minilogo.png',
  '/images/logom.png'
];

self.addEventListener('install', event => {
  event.waitUntil(
    caches.open(CACHE_NAME)
      .then(cache => {
        return cache.addAll(urlsToCache);
      })
  );
});

self.addEventListener('fetch', event => {
  event.respondWith(
    caches.match(event.request)
      .then(response => {
        if (response) {
          return response;
        }
        return fetch(event.request);
      })
  );
});
