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
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    .card-opcion {
        transition: transform 0.2s;
    }
    .card-opcion:hover {
        transform: scale(1.03);
    }
    .bg-admin {
        background: linear-gradient(to right, #0d6efd, #0dcaf0);
        color: white;
        border-radius: 10px;
        padding: 20px;
        margin-bottom: 30px;
    }
  </style>
</head>
<body class="bg-light">

<div class="container mt-4">
  <div class="bg-admin text-center">
    <h1 class="fw-bold">Panel de Administrador 🛠️</h1>
    <p class="mb-0">Bienvenido al centro de control del sistema de fidelización.</p>
  </div>

  <div class="row text-center">
    <div class="col-md-6 col-lg-3 mb-4">
      <a href="clientes.php" class="text-decoration-none text-dark">
        <div class="card shadow-sm card-opcion">
          <div class="card-body">
            <h5 class="card-title">👥 Gestión de Clientes</h5>
            <p class="card-text">Registrar, editar o eliminar clientes.</p>
          </div>
        </div>
      </a>
    </div>

    <div class="col-md-6 col-lg-3 mb-4">
      <a href="premios.php" class="text-decoration-none text-dark">
        <div class="card shadow-sm card-opcion">
          <div class="card-body">
            <h5 class="card-title">🎁 Gestión de Premios</h5>
            <p class="card-text">Agregar, editar o eliminar premios.</p>
          </div>
        </div>
      </a>
    </div>

    <div class="col-md-6 col-lg-3 mb-4">
      <a href="beneficios.php" class="text-decoration-none text-dark">
        <div class="card shadow-sm card-opcion">
          <div class="card-body">
            <h5 class="card-title">🏷️ Gestión de Beneficios</h5>
            <p class="card-text">Agregar empresas con promociones.</p>
          </div>
        </div>
      </a>
    </div>

    <div class="col-md-6 col-lg-3 mb-4">
      <a href="alta_puntos.php" class="text-decoration-none text-dark">
        <div class="card shadow-sm card-opcion">
          <div class="card-body">
            <h5 class="card-title">➕ Alta de Puntos</h5>
            <p class="card-text">Bonificar puntos a clientes.</p>
          </div>
        </div>
      </a>
    </div>
  </div>

  <div class="text-center mt-4">
    <a href="../logout.php" class="btn btn-outline-danger">Cerrar Sesión</a>
  </div>
</div>

</body>
</html>
