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
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    .card-opcion {
        transition: transform 0.2s;
    }
    .card-opcion:hover {
        transform: scale(1.03);
    }
    .bg-header {
        background: linear-gradient(45deg, #0d6efd, #0dcaf0);
        color: white;
        border-radius: 10px;
        padding: 20px;
    }
  </style>
</head>
<body class="bg-light">
<div class="container mt-4">

  <!-- Bienvenida -->
  <div class="bg-header text-center mb-4">
    <h2 class="fw-bold">¡Hola <?php echo $cliente['nombre']; ?>! 👋</h2>
    <p class="mb-1">Teléfono: <?php echo $cliente['telefono']; ?></p>
    <p>Puntos acumulados: <span class="badge bg-warning text-dark"><?php echo $cliente['puntos']; ?> pts</span></p>
  </div>

  <!-- Opciones -->
  <div class="row text-center">
    <div class="col-md-4 mb-3">
      <a href="canje_premios.php" class="text-decoration-none">
        <div class="card card-opcion shadow-sm h-100">
          <div class="card-body">
            <h5 class="card-title">🎁 Canjear Premios</h5>
            <p class="card-text text-muted">Redime tus puntos por increíbles premios.</p>
          </div>
        </div>
      </a>
    </div>
    <div class="col-md-4 mb-3">
      <a href="tarjeta.php" class="text-decoration-none">
        <div class="card card-opcion shadow-sm h-100">
          <div class="card-body">
            <h5 class="card-title">📱 Mi Tarjeta Digital</h5>
            <p class="card-text text-muted">Muestra tu tarjeta con tu número registrado.</p>
          </div>
        </div>
      </a>
    </div>
    <div class="col-md-4 mb-3">
      <a href="beneficios.php" class="text-decoration-none">
        <div class="card card-opcion shadow-sm h-100">
          <div class="card-body">
            <h5 class="card-title">🏷️ Ver Beneficios</h5>
            <p class="card-text text-muted">Conoce los descuentos con empresas afiliadas.</p>
          </div>
        </div>
      </a>
    </div>
  </div>

  <!-- Cerrar sesión -->
  <div class="text-center mt-4">
    <a href="../logout.php" class="btn btn-outline-danger">Cerrar Sesión</a>
  </div>

</div>
</body>
</html>
