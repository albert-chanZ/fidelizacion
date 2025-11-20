'use strict';

// Selecciona el botón (debes tener uno en tu HTML con este id)
const btnNotificar = document.getElementById("btnNotificar");

const showNotification = () => {
  const permission = Notification.permission;

  if (permission === 'granted') {
    new Notification('🎉 Fidelización', {
      body: '¡Bienvenido! Tu sesión se ha iniciado correctamente.',
      icon: '/fidelizacion/assets/icons/icon-192x192.png'
    });
  } else if (permission === 'denied') {
    console.warn('El usuario no aceptó recibir notificaciones');
  } else {
    Notification.requestPermission().then(result => {
      if (result === 'granted') {
        showNotification();
      }
    });
  }
};

// Asocia el evento al botón
if (btnNotificar) {
  btnNotificar.addEventListener("click", showNotification);
}
