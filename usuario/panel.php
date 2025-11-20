<?php
session_start();
if ($_SESSION["tipo"] !== "cliente") {
    header("Location: ../login.php");
    exit();
}
include '../config/db.php';
$telefono = $_SESSION["telefono"];
$cliente = $conn->query("SELECT * FROM clientes WHERE telefono='$telefono'")->fetch_assoc();
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Panel del Cliente</title>
  <meta name="viewport" content="width=device-width, initial-scale=1"> <!-- 🔹 Responsividad -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
  <link rel="manifest" href="../manifest.json">
  <meta name="theme-color" content="#0d6efd">
  <link rel="icon" type="image/png" sizes="192x192" href="../assets/icons/icon-192x192.png">
  <link rel="apple-touch-icon" href="../assets/icons/icon-192x192.png">
  <style>
    body {
      background-color: #f8f9fa;
    }
    .card-opcion {
      transition: transform 0.2s;
    }
    .card-opcion:hover {
      transform: scale(1.03);
    }
    .bg-header {
      background: linear-gradient(45deg, #0d6efd, #0dcaf0);
      color: white;
      border-radius: 12px;
      padding: 25px;
      position: relative;
      box-shadow: 0 3px 6px rgba(0,0,0,0.15);
    }
    .user-menu {
      position: absolute;
      top: 15px;
      right: 15px;
      font-size: 1.8rem;
      color: white;
      cursor: pointer;
      transition: 0.3s;
    }
    .user-menu:hover {
      color: #ffc107;
    }

    /* 🔹 Mejoras móviles */
    @media (max-width: 768px) {
      .bg-header h2 {
        font-size: 1.5rem;
      }
      .card-opcion {
        margin-bottom: 1rem;
      }
      .card-body h5 {
        font-size: 1rem;
      }
      .card-body p {
        font-size: 0.9rem;
      }
    }
  </style>
</head>
<body>
<div class="container py-4">

  <!-- Encabezado -->
  <div class="bg-header text-center mb-4">
    <i class="bi bi-person-circle user-menu" data-bs-toggle="modal" data-bs-target="#perfilModal"></i>
    <h2 class="fw-bold mb-2">¡Hola <?php echo htmlspecialchars($cliente['nombre']); ?>! 👋</h2>
    <p class="mb-1 small">Teléfono: <?php echo htmlspecialchars($cliente['telefono']); ?></p>
    <p class="mb-0">Puntos acumulados: 
      <span class="badge bg-warning text-dark fs-6"><?php echo $cliente['puntos']; ?> pts</span>
    </p>
  </div>

  <!-- Opciones -->
  <div class="row g-3 text-center">
    <div class="col-6 col-md-3">
      <a href="canje_premios.php" class="text-decoration-none">
        <div class="card card-opcion shadow-sm h-100">
          <div class="card-body">
            <h5 class="card-title">🎁 Canjear Premios</h5>
            <p class="card-text text-muted">Redime tus puntos por premios.</p>
          </div>
        </div>
      </a>
    </div>

    <div class="col-6 col-md-3">
      <a href="tarjeta.php" class="text-decoration-none">
        <div class="card card-opcion shadow-sm h-100">
          <div class="card-body">
            <h5 class="card-title">📱 Mi Tarjeta</h5>
            <p class="card-text text-muted">Muestra tu tarjeta digital.</p>
          </div>
        </div>
      </a>
    </div>

    <div class="col-6 col-md-3">
      <a href="beneficios.php" class="text-decoration-none">
        <div class="card card-opcion shadow-sm h-100">
          <div class="card-body">
            <h5 class="card-title">🏷️ Beneficios</h5>
            <p class="card-text text-muted">Descuentos y promociones.</p>
          </div>
        </div>
      </a>
    </div>

    <div class="col-6 col-md-3">
      <a href="tarjetas_debito.php" class="text-decoration-none">
        <div class="card card-opcion shadow-sm h-100">
          <div class="card-body">
            <h5 class="card-title">💳 Tarjetas</h5>
            <p class="card-text text-muted">Administra tus tarjetas.</p>
          </div>
        </div>
      </a>
    </div>
  </div>

  <!-- Botón de Cerrar sesión -->
  <div class="text-center mt-4">
    <a href="../logout.php" class="btn btn-outline-danger px-4 py-2">
      <i class="bi bi-box-arrow-right"></i> Cerrar Sesión
    </a>
  </div>
</div>

<!-- Modal de Perfil de Usuario -->
<div class="modal fade" id="perfilModal" tabindex="-1" aria-labelledby="perfilModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header bg-primary text-white">
        <h5 class="modal-title" id="perfilModalLabel"><i class="bi bi-person-circle"></i> Perfil</h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body text-center">
        <img src="../assets/icons/icon-192x192.png" alt="Usuario" class="rounded-circle mb-3" width="90">
        <h5><?php echo htmlspecialchars($cliente['nombre']); ?></h5>
        <p class="text-muted mb-1"><?php echo htmlspecialchars($cliente['telefono']); ?></p>
        <p><span class="badge bg-warning text-dark"><?php echo $cliente['puntos']; ?> puntos</span></p>
        <hr>
        <div class="d-grid gap-2">
          <a href="historial.php" class="btn btn-outline-secondary"><i class="bi bi-clock-history"></i> Historial</a>
          <a href="canje_premios.php" class="btn btn-outline-primary"><i class="bi bi-gift"></i> Canjear</a>
          <a href="tarjetas_debito.php" class="btn btn-outline-success"><i class="bi bi-credit-card-2-front"></i> Tarjetas</a>
          <a href="../logout.php" class="btn btn-danger"><i class="bi bi-box-arrow-right"></i> Salir</a>
        </div>
      </div>
    </div>
  </div>
</div>

<!-- 🔹 Sección de invitar contacto -->
<div class="container text-center my-4">
  <h5>Invitar a un amigo</h5>
  <button class="btn btn-primary mt-2" onclick="seleccionarContacto()">Elegir contacto</button>
  <div id="resultadoContacto" class="mt-3"></div>
</div>

<!-- Scripts -->
<script src="/fidelizacion/assets/js/contactos.js"></script>
<script src="/fidelizacion/assets/js/notificaciones.js"></script>
<script>
document.addEventListener("DOMContentLoaded", () => {
  showNotification();
});

if ("serviceWorker" in navigator) {
  navigator.serviceWorker.register("../sw.js")
    .then(reg => console.log("✅ Service Worker registrado:", reg.scope))
    .catch(err => console.error("❌ Error al registrar SW:", err));
}
</script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
