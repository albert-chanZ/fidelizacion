<?php
session_start();
include '../config/db.php';
if ($_SESSION["tipo"] !== "admin") header("Location: ../login.php");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $empresa = $_POST["empresa"];
    $descripcion = $_POST["descripcion"];
    $conn->query("INSERT INTO beneficios (empresa, descripcion) VALUES ('$empresa', '$descripcion')");
}
$beneficios = $conn->query("SELECT * FROM beneficios");
?>
<!DOCTYPE html>
<html>
<head><meta charset="UTF-8"><title>Beneficios</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet"></head>
<body class="container mt-4">
<h2>Empresas con Beneficios</h2>
<form method="POST" class="mb-3">
  <input name="empresa" placeholder="Nombre de la empresa" class="form-control mb-2">
  <textarea name="descripcion" placeholder="Descripción del beneficio" class="form-control mb-2"></textarea>
  <button class="btn btn-success">Agregar Beneficio</button>
</form>
<table class="table table-bordered">
<tr><th>Empresa</th><th>Descripción</th></tr>
<?php while($b = $beneficios->fetch_assoc()) echo "<tr><td>{$b['empresa']}</td><td>{$b['descripcion']}</td></tr>"; ?>
</table>
<a href="dashboard.php" class="btn btn-secondary">Volver</a>
</body></html>