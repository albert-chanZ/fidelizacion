const CACHE_NAME = "fidelizacion-cache-v1";
const urlsToCache = [
  "/fidelizacion/",                // Página principal
  "/fidelizacion/usuario/panel.php",
  "/fidelizacion/manifest.json",
  "/fidelizacion/assets/icons/icon-192x192.png",
  "/fidelizacion/assets/icons/icon-512x512.png",
  "https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css"
];

// Instalar SW y guardar archivos en caché
self.addEventListener("install", event => {
  event.waitUntil(
    caches.open(CACHE_NAME)
      .then(cache => {
        return cache.addAll(urlsToCache);
      })
  );
});

// Activar SW y limpiar cachés viejas
self.addEventListener("activate", event => {
  event.waitUntil(
    caches.keys().then(cacheNames => {
      return Promise.all(
        cacheNames.filter(name => name !== CACHE_NAME)
                  .map(name => caches.delete(name))
      );
    })
  );
});

// Interceptar peticiones y servir desde cache si existe
self.addEventListener("fetch", event => {
  event.respondWith(
    caches.match(event.request).then(response => {
      return response || fetch(event.request);
    })
  );
});
