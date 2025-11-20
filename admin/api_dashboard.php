<?php
session_start();
if ($_SESSION["tipo"] !== "admin") {
    header("Location: ../login.php");
    exit();
}
include '../config/db.php';

// Consultas generales
$totalClientes = $conn->query("SELECT COUNT(*) AS total FROM clientes")->fetch_assoc()['total'];
$totalPremios = $conn->query("SELECT COUNT(*) AS total FROM premios")->fetch_assoc()['total'];
$totalCanjes = $conn->query("SELECT COUNT(*) AS total FROM canjes")->fetch_assoc()['total'];
$totalPuntos = $conn->query("SELECT SUM(puntos) AS total FROM clientes")->fetch_assoc()['total'];
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>API Dashboard - Administrador</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container mt-4">
  <h2 class="text-center mb-4">📊 Panel Estadístico (API Dashboard)</h2>

  <div class="row text-center">
    <div class="col-md-3">
      <div class="card shadow-sm">
        <div class="card-body">
          <h5>👥 Clientes</h5>
          <h3><?= $totalClientes ?></h3>
        </div>
      </div>
    </div>
    <div class="col-md-3">
      <div class="card shadow-sm">
        <div class="card-body">
          <h5>🎁 Premios</h5>
          <h3><?= $totalPremios ?></h3>
        </div>
      </div>
    </div>
    <div class="col-md-3">
      <div class="card shadow-sm">
        <div class="card-body">
          <h5>🔄 Canjes</h5>
          <h3><?= $totalCanjes ?></h3>
        </div>
      </div>
    </div>
    <div class="col-md-3">
      <div class="card shadow-sm">
        <div class="card-body">
          <h5>⭐ Puntos Totales</h5>
          <h3><?= $totalPuntos ?: 0 ?></h3>
        </div>
      </div>
    </div>
  </div>

  <a href="dashboard.php" class="btn btn-secondary mt-4">⬅ Volver al Panel</a>
</div>
</body>
</html>
