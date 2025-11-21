<?php
session_start();
include '../config/db.php';
if ($_SESSION["tipo"] !== "admin") header("Location: ../login.php");

// Obtener lista de clientes para el select
$clientes = $conn->query("SELECT telefono, nombre FROM clientes");

// Procesar formulario
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $telefono = $_POST["telefono"];
    $monto = floatval($_POST["monto"]);
    $puntos = intval($monto / 100) * 5;
    $conn->query("UPDATE clientes SET puntos = puntos + $puntos WHERE telefono = '$telefono'");
    $mensaje = "✅ Se han bonificado $puntos puntos.";
}
?>
<!DOCTYPE html>
<html>
<head>
  <meta charset="UTF-8">
  <title>Alta Puntos</title>

  <!-- RESPONSIVO -->
  <meta name="viewport" content="width=device-width, initial-scale=1">

  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

  <style>
    /* Opcional: suaviza el diseño en mobile */
    .card {
      border-radius: 15px;
      padding: 20px;
    }
  </style>
</head>
<body>

<div class="container mt-4 mb-5">

  <h2 class="text-center mb-4">Bonificar Puntos por Cliente</h2>

  <?php if (!empty($mensaje)): ?>
      <div class="alert alert-success text-center">
        <?= $mensaje ?>
      </div>
  <?php endif; ?>

  <!-- CARD RESPONSIVA -->
  <div class="card shadow mb-4">

    <form method="POST">

      <div class="mb-3">
        <label class="form-label">Seleccionar cliente:</label>
        <select name="telefono" class="form-select" required>
          <option value="">-- Selecciona un cliente --</option>
          <?php while($c = $clientes->fetch_assoc()) { ?>
            <option value="<?= $c['telefono'] ?>">
              <?= $c['nombre'] ?> (<?= $c['telefono'] ?>)
            </option>
          <?php } ?>
        </select>
      </div>

      <div class="mb-3">
        <label class="form-label">Monto de compra ($):</label>
        <input name="monto" type="number" step="0.01" class="form-control" placeholder="Ej. 250.50" required>
      </div>

      <button class="btn btn-success w-100">Bonificar</button>

    </form>
  </div>

  <div class="text-center">
    <a href="dashboard.php" class="btn btn-secondary w-100">Volver</a>
  </div>

</div>

</body>
</html>
