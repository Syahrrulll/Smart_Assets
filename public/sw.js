const CACHE_NAME = 'smart-asset-v1';
const urlsToCache = [
  '/',
  '/manifest.json',
  // In a real app we'd add offline fallback pages and build assets here
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
        // Cache hit - return response
        if (response) {
          return response;
        }
        return fetch(event.request).catch(() => {
            // Fallback for offline mode, ideally an offline.html page
        });
      }
    )
  );
});
