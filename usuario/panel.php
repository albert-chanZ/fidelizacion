<?php
session_start();
if ($_SESSION["tipo"] !== "cliente") {
    header("Location: ../login.php");
    exit();
}
include '../config/db.php';
$telefono = $_SESSION["telefono"];
$cliente = $conn->query("SELECT * FROM clientes WHERE telefono='$telefono'")->fetch_assoc();
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Panel de Usuario</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-4">
  <h2>Bienvenido, <?php echo $cliente['nombre']; ?></h2>
  <p><strong>Teléfono:</strong> <?php echo $cliente['telefono']; ?></p>
  <p><strong>Puntos acumulados:</strong> <?php echo $cliente['puntos']; ?></p>

  <h4>Opciones:</h4>
  <a href="canje_premios.php" class="btn btn-warning">Canjear Premios</a>
  <a href="tarjeta.php" class="btn btn-info">Mi Tarjeta Digital</a>
  <a href="beneficios.php" class="btn btn-success">Ver Beneficios</a>
  <br><br><a href="../logout.php" class="btn btn-danger">Cerrar Sesión</a>
</div>
</body>
</html>