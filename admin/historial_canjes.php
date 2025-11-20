<?php
session_start();
if ($_SESSION["tipo"] !== "admin") {
    header("Location: ../login.php");
    exit();
}
include '../config/db.php';
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Historial de Canjes</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container mt-4">
  <h2 class="text-center mb-4">🎁 Historial de Canjes</h2>
  
  <div class="table-responsive">
    <table class="table table-bordered table-striped text-center">
      <thead class="table-primary">
        <tr>
          <th>ID Canje</th>
          <th>Cliente</th>
          <th>Teléfono</th>
          <th>Premio</th>
          <th>Fecha</th>
        </tr>
      </thead>
      <tbody>
        <?php
       $canjes = $conn->query("SELECT 
                             c.id, 
                             cli.nombre, 
                             cli.telefono, 
                             p.nombre AS premio,  
                             c.fecha
                         FROM 
                             canjes c
                         JOIN 
                             clientes cli ON c.telefono = cli.telefono  -- Usamos telefono para la unión
                         JOIN 
                             premios p ON c.premio_id = p.id
                         ORDER BY 
                             c.fecha DESC");
        while ($row = $canjes->fetch_assoc()) {
            echo "<tr>
              <td>{$row['id']}</td>
              <td>{$row['nombre']}</td>
              <td>{$row['telefono']}</td>
              <td>{$row['premio']}</td>
              <td>{$row['fecha']}</td>
            </tr>";
        }
        ?>
      </tbody>
    </table>
  </div>

  <a href="dashboard.php" class="btn btn-secondary mt-3">⬅ Volver al Panel</a>
</div>
</body>
</html>
