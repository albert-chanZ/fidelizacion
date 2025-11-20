const CACHE_NAME = "fidelizacion-cache-v3";

// Solo archivos públicos y estáticos
const urlsToCache = [
  "/fidelizacion/login.php",
  "/fidelizacion/assets/css/style.css",
  "/fidelizacion/assets/icons/icon-192x192.png",
  "/fidelizacion/assets/icons/icon-512x512.png"
];

// Instalar y cachear archivos públicos
self.addEventListener("install", (event) => {
  event.waitUntil(
    caches.open(CACHE_NAME).then((cache) => {
      return Promise.all(
        urlsToCache.map((url) =>
          fetch(url, { redirect: "follow" })
            .then((response) => {
              if (response.ok) return cache.put(url, response);
            })
            .catch(() => console.warn("No se pudo cachear:", url))
        )
      );
    })
  );
});

// Activar y eliminar caches antiguos
self.addEventListener("activate", (event) => {
  event.waitUntil(
    caches.keys().then((keys) =>
      Promise.all(keys.map((key) => {
        if (key !== CACHE_NAME) return caches.delete(key);
      }))
    )
  );
});

// Interceptar peticiones y responder desde cache si aplica
self.addEventListener("fetch", (event) => {
  // Evitar cachear peticiones con sesión (panel, premios, etc.)
  if (event.request.url.includes("panel.php") ||
      event.request.url.includes("canje_premios.php") ||
      event.request.url.includes("historial.php")) {
    return; // deja pasar sin interceptar
  }

  event.respondWith(
    caches.match(event.request).then((response) => {
      return response || fetch(event.request);
    })
  );
});

// Manejar clics en notificaciones
self.addEventListener("notificationclick", (event) => {
  event.notification.close();
  event.waitUntil(clients.openWindow("/fidelizacion/usuario/panel.php"));
});

