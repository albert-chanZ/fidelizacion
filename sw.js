const CACHE_NAME = "fidelizacion-cache-v1";
const urlsToCache = [
  "/",
  "/index.php",
  "/login.php",
  "/panel.php",
  "/canje_premios.php",
  "/historial.php",
  "/assets/css/style.css",
  "/assets/icons/icon-192x192.png",
  "/assets/icons/icon-512x512.png"
];

// Instalar y cachear archivos
self.addEventListener("install", (event) => {
  event.waitUntil(
    caches.open(CACHE_NAME).then((cache) => {
      return cache.addAll(urlsToCache);
    })
  );
});

// Activar y limpiar caches viejos
self.addEventListener("activate", (event) => {
  event.waitUntil(
    caches.keys().then((cacheNames) => {
      return Promise.all(
        cacheNames.map((cache) => {
          if (cache !== CACHE_NAME) {
            return caches.delete(cache);
          }
        })
      );
    })
  );
});

// Interceptar peticiones y responder desde cache si está disponible
self.addEventListener("fetch", (event) => {
  event.respondWith(
    caches.match(event.request).then((response) => {
      return response || fetch(event.request);
    })
  );
});
