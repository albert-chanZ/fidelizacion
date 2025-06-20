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
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="container mt-4">
<h2>Bonificar Puntos por Cliente</h2>

<?php if (!empty($mensaje)) echo "<div class='alert alert-success'>$mensaje</div>"; ?>

<form method="POST" class="mb-3">
  <label class="form-label">Seleccionar cliente:</label>
  <select name="telefono" class="form-select mb-2" required>
    <option value="">-- Selecciona un cliente --</option>
    <?php while($c = $clientes->fetch_assoc()) {
      echo "<option value='{$c['telefono']}'>{$c['nombre']} ({$c['telefono']})</option>";
    } ?>
  </select>
  <input name="monto" type="number" step="0.01" placeholder="Monto de compra ($)" class="form-control mb-2" required>
  <button class="btn btn-success">Bonificar</button>
</form>

<a href="dashboard.php" class="btn btn-secondary">Volver</a>
</body>
</html>
