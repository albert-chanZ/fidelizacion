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
</body>
</html>
