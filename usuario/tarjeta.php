<?php
session_start();
if ($_SESSION["tipo"] !== "cliente") {
    header("Location: ../login.php");
    exit();
}
$telefono = $_SESSION["telefono"];
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Tarjeta Digital</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-5 text-center">
  <div class="card p-4 shadow" style="max-width: 400px; margin: auto;">
    <h3>Tarjeta de Fidelización</h3>
    <p><strong>Teléfono:</strong> <?php echo $telefono; ?></p>
    <div style="font-size: 2rem;">📱</div>
    <p>Gracias por tu preferencia</p>
  </div>
  <br><a href="panel.php" class="btn btn-secondary">Volver</a>
</div>
</body>
</html>