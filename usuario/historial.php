<?php
session_start();
include '../config/db.php';

if (!isset($_SESSION["telefono"]) || $_SESSION["tipo"] !== "cliente") {
    header("Location: ../login.php");
    exit;
}

$telefono = $_SESSION["telefono"];

// Consulta historial de canjes
$sql = "
    SELECT c.fecha, p.nombre, p.descripcion, p.puntos_requeridos
    FROM canjes c
    INNER JOIN premios p ON c.premio_id = p.id
    WHERE c.telefono = ?
    ORDER BY c.fecha DESC
";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $telefono);
$stmt->execute();
$result = $stmt->get_result();
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Historial de Canjes</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="manifest" href="../manifest.json">
  <meta name="theme-color" content="#0d6efd">
  <link rel="icon" type="image/png" sizes="192x192" href="assets/icons/icon-192x192.png">
  <link rel="apple-touch-icon" href="assets/icons/icon-192x192.png">
  <style>
    body {
      background: #f8f9fa;
    }
    .card-table {
      border-radius: 15px;
      overflow: hidden;
      box-shadow: 0 4px 10px rgba(0,0,0,0.1);
    }
    .table thead th {
      background-color: #0d6efd;
      color: #fff;
      text-align: center;
    }
    .table tbody td {
      vertical-align: middle;
      text-align: center;
    }
    .back-btn {
      border-radius: 25px;
      padding: 10px 20px;
    }
    @media (max-width: 768px) {
      .table {
        font-size: 0.85rem;
      }
      h2 {
        font-size: 1.4rem;
      }
    }
  </style>
</head>
<body>

<div class="container mt-5 mb-5">
  <div class="text-center mb-4">
    <h2><i class="bi bi-gift"></i> Historial de Premios Canjeados</h2>
  </div>

  <?php if ($result->num_rows > 0): ?>
    <div class="table-responsive card-table">
      <table class="table table-striped align-middle mb-0">
        <thead>
          <tr>
            <th>Fecha</th>
            <th>Premio</th>
            <th>Descripción</th>
            <th>Puntos</th>
          </tr>
        </thead>
        <tbody>
          <?php while ($row = $result->fetch_assoc()): ?>
            <tr>
              <td><?= date("d/m/Y H:i", strtotime($row["fecha"])) ?></td>
              <td><?= htmlspecialchars($row["nombre"]) ?></td>
              <td><?= htmlspecialchars($row["descripcion"]) ?></td>
              <td><?= $row["puntos_requeridos"] ?></td>
            </tr>
          <?php endwhile; ?>
        </tbody>
      </table>
    </div>
  <?php else: ?>
    <div class="alert alert-info text-center shadow-sm mt-4">
      <i class="bi bi-info-circle"></i> Aún no has canjeado ningún premio.
    </div>
  <?php endif; ?>

  <div class="text-center mt-4">
    <a href="canje_premios.php" class="btn btn-outline-primary back-btn">
      <i class="bi bi-arrow-left"></i> Volver
    </a>
  </div>
</div>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

<!-- Registro del Service Worker -->
<script>
if ("serviceWorker" in navigator) {
  navigator.serviceWorker.register("../sw.js")
    .then(reg => console.log("✅ Service Worker registrado:", reg.scope))
    .catch(err => console.error("❌ Error al registrar SW:", err));
}
</script>
</body>
</html>
