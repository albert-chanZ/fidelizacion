<?php
session_start();
include '../config/db.php';
if ($_SESSION["tipo"] !== "admin") header("Location: ../login.php");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $telefono = $_POST["telefono"];
    $monto = floatval($_POST["monto"]);
    $puntos = intval($monto / 100) * 5;
    $conn->query("UPDATE clientes SET puntos = puntos + $puntos WHERE telefono = '$telefono'");
}
?>
<!DOCTYPE html>
<html>
<head><meta charset="UTF-8"><title>Alta Puntos</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet"></head>
<body class="container mt-4">
<h2>Bonificar Puntos</h2>
<form method="POST" class="mb-3">
  <input name="telefono" placeholder="Teléfono del cliente" class="form-control mb-2">
  <input name="monto" type="number" placeholder="Monto de compra ($)" class="form-control mb-2">
  <button class="btn btn-success">Bonificar</button>
</form>
<a href="dashboard.php" class="btn btn-secondary">Volver</a>
</body></html>