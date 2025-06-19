<?php
session_start();
include '../config/db.php';
if ($_SESSION["tipo"] !== "cliente") header("Location: ../login.php");

$telefono = $_SESSION["telefono"];
$cliente = $conn->query("SELECT * FROM clientes WHERE telefono='$telefono'")->fetch_assoc();
$premios = $conn->query("SELECT * FROM premios");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $premio_id = $_POST["premio_id"];
    $premio = $conn->query("SELECT * FROM premios WHERE id=$premio_id")->fetch_assoc();
    if ($cliente["puntos"] >= $premio["puntos_requeridos"]) {
        $conn->query("INSERT INTO canjes (telefono, premio_id) VALUES ('$telefono', $premio_id)");
        $conn->query("UPDATE clientes SET puntos = puntos - {$premio['puntos_requeridos']} WHERE telefono = '$telefono'");
        header("Location: panel.php");
    }
}
?>
<!DOCTYPE html>
<html>
<head><meta charset="UTF-8"><title>Canje de Premios</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet"></head>
<body class="container mt-4">
<h2>Canje de Premios</h2>
<form method="POST">
  <select name="premio_id" class="form-select mb-2">
    <?php while($p = $premios->fetch_assoc()) echo "<option value='{$p['id']}'>{$p['nombre']} - {$p['puntos_requeridos']} pts</option>"; ?>
  </select>
  <button class="btn btn-primary">Canjear</button>
</form>
<a href="panel.php" class="btn btn-secondary">Volver</a>
</body></html>