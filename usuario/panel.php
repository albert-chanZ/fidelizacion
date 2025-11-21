<?php
session_start();
if ($_SESSION["tipo"] !== "cliente") {
    header("Location: ../login.php");
    exit();
}
include '../config/db.php';

$telefono = $_SESSION["telefono"];
$cliente = $conn->query("SELECT * FROM clientes WHERE telefono='$telefono'")->fetch_assoc();

// Foto de perfil si existe en la BD
$foto = (!empty($cliente["foto"]))
    ? "../uploads/" . $cliente["foto"]
    : "../assets/icons/icon-192x192.png";
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Panel del Cliente</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">

  <!-- Bootstrap -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">

  <!-- PWA -->
  <link rel="manifest" href="../manifest.json">
  <meta name="theme-color" content="#0d6efd">
  <link rel="apple-touch-icon" href="../assets/icons/icon-192x192.png">

  <style>
    body {
      background-color: #f2f4f7;
    }
    .card-opcion {
      border-radius: 15px;
      transition: transform .2s;
    }
    .card-opcion:hover {
      transform: scale(1.03);
    }
    .bg-header {
      background: linear-gradient(45deg, #0d6efd, #0bbaf0);
      color:white;
      border-radius: 15px;
      padding: 30px 20px;
      box-shadow: 0px 4px 8px rgba(0,0,0,.15);
      text-align: center;
      position: relative;
    }
    .profile-img {
      width: 85px;
      height: 85px;
      border-radius: 50%;
      border: 3px solid #fff;
      object-fit: cover;
      margin-bottom: 10px;
    }
    .user-menu {
      position:absolute;
      top:10px;
      right:15px;
      font-size:1.8rem;
      cursor:pointer;
      transition:.3s;
    }
    .user-menu:hover { color:#ffc107; }

    /* RESPONSIVE */
    @media(max-width:768px){
      .bg-header h2 { font-size:1.4rem; }
      .card-body h5 { font-size:1rem; }
      .card-body p { font-size:.9rem; }
    }
  </style>
</head>
<body>

<div class="container py-4">

  <!-- ENCABEZADO -->
  <div class="bg-header mb-4">
    <i class="bi bi-person-gear user-menu" data-bs-toggle="modal" data-bs-target="#perfilModal"></i>

    <img src="<?= $foto ?>" class="profile-img" alt="Usuario">

    <h2 class="fw-bold mb-1">Hola, <?= htmlspecialchars($cliente['nombre']) ?> 👋</h2>
    <p class="mb-1 small">Tel: <?= htmlspecialchars($cliente['telefono']) ?></p>

    <span class="badge bg-warning text-dark fs-6 px-3 py-2">
      <?= $cliente['puntos'] ?> puntos ⭐
    </span>
  </div>

  <!-- OPCIONES -->
  <div class="row g-3 text-center">

    <div class="col-6 col-md-3">
      <a href="canje_premios.php" class="text-decoration-none">
        <div class="card card-opcion p-3 shadow-sm">
          <h5 class="mt-2">🎁 Premios</h5>
          <p class="text-muted small">Canjea puntos</p>
        </div>
      </a>
    </div>

    <div class="col-6 col-md-3">
      <a href="tarjeta.php" class="text-decoration-none">
        <div class="card card-opcion p-3 shadow-sm">
          <h5 class="mt-2">📱 Mi Tarjeta</h5>
          <p class="text-muted small">Tarjeta digital</p>
        </div>
      </a>
    </div>

    <div class="col-6 col-md-3">
      <a href="beneficios.php" class="text-decoration-none">
        <div class="card card-opcion p-3 shadow-sm">
          <h5 class="mt-2">🏷️ Beneficios</h5>
          <p class="text-muted small">Promociones</p>
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

    <div class="col-6 col-md-3">
      <a href="perfil.php" class="text-decoration-none">
        <div class="card card-opcion p-3 shadow-sm">
          <h5 class="mt-2">🤵 Perfil</h5>
          <p class="text-muted small">Ver y editar</p>
        </div>
      </a>
    </div>

  </div>

  <!-- BOTÓN LOGOUT -->
  <div class="text-center mt-4">
    <a href="../logout.php" class="btn btn-outline-danger px-4 py-2">
      <i class="bi bi-box-arrow-right"></i> Salir
    </a>
  </div>

</div>

<!-- ============================
     MODAL DE PERFIL (ACTUALIZADO)
     ============================ -->
<div class="modal fade" id="perfilModal" tabindex="-1">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">

      <div class="modal-header bg-primary text-white">
        <h5 class="modal-title">
          <i class="bi bi-person-circle"></i> Mi Perfil
        </h5>
        <button class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
      </div>

      <div class="modal-body text-center">

        <img src="<?= $foto ?>" class="rounded-circle mb-3" width="100" height="100">

        <h5><?= htmlspecialchars($cliente['nombre']) ?></h5>
        <p class="text-muted mb-1"><?= htmlspecialchars($cliente['telefono']) ?></p>

        <span class="badge bg-warning text-dark mb-3"><?= $cliente['puntos'] ?> puntos</span>

        <hr>

        <div class="d-grid gap-2">
          <a href="historial.php" class="btn btn-outline-secondary">
            <i class="bi bi-clock-history"></i> Historial
          </a>
          <a href="canje_premios.php" class="btn btn-outline-primary">
            <i class="bi bi-gift"></i> Canjear Premios
          </a>
          <a href="perfil.php" class="btn btn-outline-success">
            <i class="bi bi-person"></i> Editar Perfil
          </a>
          <a href="../logout.php" class="btn btn-danger">
            <i class="bi bi-box-arrow-right"></i> Cerrar Sesión
          </a>
        </div>

      </div>

    </div>
  </div>
</div>

<!-- SCRIPTS -->
<script src="/fidelizacion/assets/js/contactos.js"></script>
<script src="/fidelizacion/assets/js/notificaciones.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

<script>
document.addEventListener("DOMContentLoaded", () => {
  showNotification();
});

if ("serviceWorker" in navigator) {
  navigator.serviceWorker.register("../sw.js");
}
</script>

</body>
</html>
