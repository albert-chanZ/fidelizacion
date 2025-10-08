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
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="manifest" href="../manifest.json">
  <meta name="theme-color" content="#0d6efd">
  <!-- Iconos para navegadores -->
  <link rel="icon" type="image/png" sizes="192x192" href="assets/icons/icon-192x192.png">
  <link rel="apple-touch-icon" href="assets/icons/icon-192x192.png">
</head>
<body class="bg-light">
<div class="container mt-5">
  <h2 class="text-center mb-4">Historial de Premios Canjeados</h2>

  <?php if ($result->num_rows > 0): ?>
    <table class="table table-striped table-hover shadow-sm">
      <thead class="table-dark">
        <tr>
          <th>Fecha</th>
          <th>Premio</th>
          <th>Descripción</th>
          <th>Puntos Canjeados</th>
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
  <?php else: ?>
    <div class="alert alert-info text-center">
      Aún no has canjeado ningún premio.
    </div>
  <?php endif; ?>

  <div class="text-center mt-4">
    <a href="canje_premios.php" class="btn btn-secondary">← Volver</a>
  </div>
</div>
<script>
  if ("serviceWorker" in navigator) {
    navigator.serviceWorker.register("../sw.js")
      .then((reg) => console.log("✅ Service Worker registrado:", reg))
      .catch((err) => console.error("❌ Error al registrar SW:", err));
  }
</script>

<script>
if ("serviceWorker" in navigator) {
  navigator.serviceWorker.register("../service-worker.js")
    .then(reg => console.log("✅ Service Worker registrado:", reg.scope))
    .catch(err => console.error("❌ Error al registrar SW:", err));
}
</script>

</body>
</html>
