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
  <meta name="viewport" content="width=device-width, initial-scale=1">

  <!-- Bootstrap -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

  <style>
    /* Ajuste de tamaños en pantallas pequeñas */
    @media (max-width: 576px) {
        h2 {
            font-size: 1.3rem;
        }
        table th, table td {
            font-size: 0.75rem;
            padding: 6px;
        }
        .btn {
            width: 100%;
        }
    }
  </style>
</head>

<body class="bg-light">

<div class="container mt-4">
  <div class="mt-3">
    <a href="dashboard.php" class="btn btn-secondary">Volver al Panel</a>
  </div>

  <div class="text-center mb-4">
      <h2 class="fw-bold">🎁 Historial de Canjes</h2>
      <p class="text-muted">Consulta de todos los canjes realizados por los clientes</p>
  </div>

  <!-- RESPONSIVE WRAPPER -->
  <div class="table-responsive shadow-sm rounded">
    <table class="table table-bordered table-striped text-center align-middle">
      <thead class="table-primary">
        <tr>
          <th>ID</th>
          <th>Cliente</th>
          <th>Teléfono</th>
          <th>Premio</th>
          <th>Fecha</th>
        </tr>
      </thead>

      <tbody>
        <?php
        $canjes = $conn->query("
            SELECT c.id, cli.nombre, cli.telefono, p.nombre AS premio, c.fecha
            FROM canjes c
            JOIN clientes cli ON c.telefono = cli.telefono
            JOIN premios p ON c.premio_id = p.id
            ORDER BY c.fecha DESC
        ");

        while ($row = $canjes->fetch_assoc()) {
            echo "
              <tr>
                <td>{$row['id']}</td>
                <td>{$row['nombre']}</td>
                <td>{$row['telefono']}</td>
                <td>{$row['premio']}</td>
                <td>" . date('d/m/Y H:i', strtotime($row['fecha'])) . "</td>
              </tr>";
        }
        ?>
      </tbody>
    </table>
  </div>

  <div class="mt-3">
    <a href="dashboard.php" class="btn btn-secondary w-100">⬅ Volver al Panel</a>
  </div>

</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
