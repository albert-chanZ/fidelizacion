<?php
session_start();
if ($_SESSION["tipo"] !== "admin") {
    header("Location: ../login.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Administrador</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-4">
  <h1 class="mb-4">Panel de Administrador</h1>
  <a href="clientes.php" class="btn btn-primary mb-2">Gestión de Clientes</a>
  <a href="premios.php" class="btn btn-secondary mb-2">Gestión de Premios</a>
  <a href="beneficios.php" class="btn btn-info mb-2">Gestión de Beneficios</a>
  <a href="alta_puntos.php" class="btn btn-success mb-2">Alta de Puntos</a>
  <br><a href="../logout.php" class="btn btn-danger">Cerrar Sesión</a>
</div>
</body>
</html>