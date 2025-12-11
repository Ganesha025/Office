const CACHE = "savage-v1";

self.addEventListener("install", e => {
  e.waitUntil(
    caches.open(CACHE).then(cache => cache.addAll([
      "../",
      "../index.html",
      "../style.css",
      "../tv.css",
      "../main.js",
      "../Profile.webp",
      "../typing/test.html",
      "../images/image.html",
      "../certify/certificate.html",
      "../skill.html"
    ]))
  );
});

self.addEventListener("fetch", e => {
  e.respondWith(
    caches.match(e.request).then(r => r || fetch(e.request))
  );
});
