<?php
session_start();
include '../config/db.php';
if ($_SESSION["tipo"] !== "admin") header("Location: ../login.php");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nombre = $_POST["nombre"];
    $descripcion = $_POST["descripcion"];
    $puntos = $_POST["puntos"];
    $conn->query("INSERT INTO premios (nombre, descripcion, puntos_requeridos) VALUES ('$nombre', '$descripcion', $puntos)");
}
$premios = $conn->query("SELECT * FROM premios");
?>
<!DOCTYPE html>
<html>
<head><meta charset="UTF-8"><title>Premios</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="container mt-4">
<h2>Gestión de Premios</h2>
<form method="POST" class="mb-3">
  <input name="nombre" placeholder="Nombre del premio" class="form-control mb-2">
  <textarea name="descripcion" placeholder="Descripción" class="form-control mb-2"></textarea>
  <input name="puntos" type="number" placeholder="Puntos requeridos" class="form-control mb-2">
  <button class="btn btn-primary">Guardar Premio</button>
</form>
<table class="table table-bordered">
<tr><th>Nombre</th><th>Puntos</th></tr>
<?php while($p = $premios->fetch_assoc()) echo "<tr><td>{$p['nombre']}</td><td>{$p['puntos_requeridos']}</td></tr>"; ?>
</table>
<a href="dashboard.php" class="btn btn-secondary">Volver</a>
</body></html>