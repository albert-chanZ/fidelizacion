<?php
session_start();
if ($_SESSION["tipo"] !== "admin") {
    header("Location: ../login.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Panel de Administrador</title>

  <!-- Bootstrap -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">

  <link rel="manifest" href="../manifest.json">
  <meta name="theme-color" content="#0d6efd">

  <style>
    body {
      background: #f8f9fa;
    }

    .bg-admin {
      background: linear-gradient(45deg, #0d6efd, #0dcaf0);
      color: white;
      border-radius: 10px;
      padding: 25px;
      margin-bottom: 30px;
    }

    .card-opcion {
      transition: transform 0.2s, box-shadow 0.2s;
      border-radius: 12px;
    }

    .card-opcion:hover {
      transform: scale(1.04);
      box-shadow: 0 6px 15px rgba(0,0,0,0.15);
    }

    .card-body i {
      font-size: 2.4rem;
      color: #0d6efd;
      margin-bottom: 10px;
    }
  </style>
</head>

<body>

<div class="container mt-4">

  <!-- Encabezado Responsivo -->
  <div class="bg-admin text-center">
    <h1 class="fw-bold"><i class="bi bi-tools"></i> Panel de Administrador</h1>
    <p class="mb-0">Centro de control del sistema de fidelización.</p>
  </div>

  <!-- Grid de opciones -->
  <div class="row g-3">

    <!-- CLIENTES -->
    <div class="col-12 col-sm-6 col-lg-3">
      <a href="clientes.php" class="text-decoration-none text-dark">
        <div class="card shadow-sm card-opcion h-100">
          <div class="card-body text-center">
            <i class="bi bi-people-fill"></i>
            <h5 class="card-title mt-2">Gestión de Clientes</h5>
            <p class="text-muted">Registrar, editar o eliminar clientes.</p>
          </div>
        </div>
      </a>
    </div>

    <!-- PREMIOS -->
    <div class="col-12 col-sm-6 col-lg-3">
      <a href="premios.php" class="text-decoration-none text-dark">
        <div class="card shadow-sm card-opcion h-100">
          <div class="card-body text-center">
            <i class="bi bi-gift-fill"></i>
            <h5 class="card-title mt-2">Gestión de Premios</h5>
            <p class="text-muted">Agregar, editar o eliminar premios.</p>
          </div>
        </div>
      </a>
    </div>

    <!-- BENEFICIOS -->
    <div class="col-12 col-sm-6 col-lg-3">
      <a href="beneficios.php" class="text-decoration-none text-dark">
        <div class="card shadow-sm card-opcion h-100">
          <div class="card-body text-center">
            <i class="bi bi-tags-fill"></i>
            <h5 class="card-title mt-2">Gestión de Beneficios</h5>
            <p class="text-muted">Agregar empresas y promociones.</p>
          </div>
        </div>
      </a>
    </div>

    <!-- ALTA DE PUNTOS -->
    <div class="col-12 col-sm-6 col-lg-3">
      <a href="alta_puntos.php" class="text-decoration-none text-dark">
        <div class="card shadow-sm card-opcion h-100">
          <div class="card-body text-center">
            <i class="bi bi-plus-circle-fill"></i>
            <h5 class="card-title mt-2">Alta de Puntos</h5>
            <p class="text-muted">Bonificar puntos a clientes.</p>
          </div>
        </div>
      </a>
    </div>

    <!-- TARJETAS -->
    <div class="col-12 col-sm-6 col-lg-3">
      <a href="tarjetas_admin.php" class="text-decoration-none text-dark">
        <div class="card shadow-sm card-opcion h-100">
          <div class="card-body text-center">
            <i class="bi bi-credit-card-2-front-fill"></i>
            <h5 class="card-title mt-2">Tarjetas de Débito</h5>
            <p class="text-muted">Ver, editar o eliminar tarjetas registradas.</p>
          </div>
        </div>
      </a>
    </div>

    <!-- HISTORIAL -->
    <div class="col-12 col-sm-6 col-lg-3">
      <a href="historial_canjes.php" class="text-decoration-none text-dark">
        <div class="card shadow-sm card-opcion h-100">
          <div class="card-body text-center">
            <i class="bi bi-clock-history"></i>
            <h5 class="card-title mt-2">Historial de Canjes</h5>
            <p class="text-muted">Revisar todos los canjes realizados.</p>
          </div>
        </div>
      </a>
    </div>

    <!-- API -->
    <div class="col-12 col-sm-6 col-lg-3">
      <a href="api_dashboard.php" class="text-decoration-none text-dark">
        <div class="card shadow-sm card-opcion h-100">
          <div class="card-body text-center">
            <i class="bi bi-code-slash"></i>
            <h5 class="card-title mt-2">APIs REST</h5>
            <p class="text-muted">Probar o monitorear las API.</p>
          </div>
        </div>
      </a>
    </div>

    <!-- CONFIGURACIÓN -->
    <div class="col-12 col-sm-6 col-lg-3">
      <a href="configuracion.php" class="text-decoration-none text-dark">
        <div class="card shadow-sm card-opcion h-100">
          <div class="card-body text-center">
            <i class="bi bi-gear-fill"></i>
            <h5 class="card-title mt-2">Configuración</h5>
            <p class="text-muted">Opciones generales y seguridad.</p>
          </div>
        </div>
      </a>
    </div>

  </div>

  <!-- Cerrar sesión -->
  <div class="text-center mt-4">
    <a href="../logout.php" class="btn btn-outline-danger px-4">
      <i class="bi bi-box-arrow-right"></i> Cerrar Sesión
    </a>
  </div>

</div>

<!-- Service Worker -->
<script>
if ("serviceWorker" in navigator) {
  navigator.serviceWorker.register("../sw.js")
    .then(reg => console.log("SW activo:", reg.scope))
    .catch(err => console.error("Error SW:", err));
}
</script>

</body>
</html>
