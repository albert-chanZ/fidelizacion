<?php
session_start();
if ($_SESSION["tipo"] !== "admin") {
    header("Location: ../login.php");
    exit();
}
include '../config/db.php';

// Consultas
$totalClientes = $conn->query("SELECT COUNT(*) AS total FROM clientes")->fetch_assoc()['total'];
$totalPremios  = $conn->query("SELECT COUNT(*) AS total FROM premios")->fetch_assoc()['total'];
$totalCanjes   = $conn->query("SELECT COUNT(*) AS total FROM canjes")->fetch_assoc()['total'];
$totalPuntos   = $conn->query("SELECT SUM(puntos) AS total FROM clientes")->fetch_assoc()['total'];
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>API Dashboard - Administrador</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">

  <!-- Bootstrap -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

  <style>
    /* Mejoras en tarjeta para móviles */
    @media (max-width: 576px) {
      .card h5 {
        font-size: 0.9rem;
      }
      .card h3 {
        font-size: 1.4rem;
      }
      h2 {
        font-size: 1.4rem;
      }
      .btn {
        width: 100%;
      }
    }
  </style>
</head>

<body class="bg-light">

<div class="container mt-4">

  <h2 class="text-center mb-4 fw-bold">📊 Panel Estadístico</h2>

  <div class="row g-3 text-center">

    <div class="col-6 col-md-3">
      <div class="card shadow-sm border-0">
        <div class="card-body">
          <h5 class="text-muted">👥 Clientes</h5>
          <h3 class="fw-bold"><?= $totalClientes ?></h3>
        </div>
      </div>
    </div>

    <div class="col-6 col-md-3">
      <div class="card shadow-sm border-0">
        <div class="card-body">
          <h5 class="text-muted">🎁 Premios</h5>
          <h3 class="fw-bold"><?= $totalPremios ?></h3>
        </div>
      </div>
    </div>

    <div class="col-6 col-md-3">
      <div class="card shadow-sm border-0">
        <div class="card-body">
          <h5 class="text-muted">🔄 Canjes</h5>
          <h3 class="fw-bold"><?= $totalCanjes ?></h3>
        </div>
      </div>
    </div>

    <div class="col-6 col-md-3">
      <div class="card shadow-sm border-0">
        <div class="card-body">
          <h5 class="text-muted">⭐ Puntos Totales</h5>
          <h3 class="fw-bold"><?= $totalPuntos ?: 0 ?></h3>
        </div>
      </div>
    </div>

  </div>

  <div class="mt-4">
    <a href="dashboard.php" class="btn btn-secondary w-100">Volver al Panel</a>
  </div>

</div>

</body>
</html>
