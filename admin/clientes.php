<?php
session_start();
include '../config/db.php';
if ($_SESSION["tipo"] !== "admin") header("Location: ../login.php");

// Alta de cliente
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["telefono"])) {
    $telefono = $_POST["telefono"];
    $nombre = $_POST["nombre"];
    $apellidos = $_POST["apellidos"];
    $direccion = $_POST["direccion"];
    $correo = $_POST["correo"];
    $estado = $_POST["estado"];
    $ciudad = $_POST["ciudad"];
    $password = password_hash($_POST["password"], PASSWORD_DEFAULT);

    $stmt = $conn->prepare("INSERT INTO clientes (telefono, nombre, apellidos, direccion, correo, estado, ciudad, password) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("ssssssss", $telefono, $nombre, $apellidos, $direccion, $correo, $estado, $ciudad, $password);
    $stmt->execute();
}
$clientes = $conn->query("SELECT * FROM clientes");
?>
<!DOCTYPE html>
<html>
<head>
  <meta charset="UTF-8">
  <title>Clientes</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="container mt-4">
<h2>Gestión de Clientes</h2>
<form method="POST" class="row g-3">
  <div class="col-md-4"><input class="form-control" name="telefono" placeholder="Teléfono" required></div>
  <div class="col-md-4"><input class="form-control" name="nombre" placeholder="Nombre" required></div>
  <div class="col-md-4"><input class="form-control" name="apellidos" placeholder="Apellidos"></div>
  <div class="col-md-6"><input class="form-control" name="direccion" placeholder="Dirección"></div>
  <div class="col-md-6"><input class="form-control" name="correo" placeholder="Correo electrónico"></div>
  <div class="col-md-4"><input class="form-control" name="estado" placeholder="Estado"></div>
  <div class="col-md-4"><input class="form-control" name="ciudad" placeholder="Ciudad"></div>
  <div class="col-md-4"><input type="password" class="form-control" name="password" placeholder="Contraseña"></div>
  <div class="col-12"><button class="btn btn-success">Registrar Cliente</button></div>
</form>
<hr>
<h4>Clientes Registrados</h4>
<table class="table table-bordered">
  <thead><tr><th>Teléfono</th><th>Nombre</th><th>Puntos</th></tr></thead>
  <tbody>
  <?php while ($row = $clientes->fetch_assoc()) { ?>
    <tr><td><?php echo $row['telefono']; ?></td><td><?php echo $row['nombre']; ?></td><td><?php echo $row['puntos']; ?></td></tr>
  <?php } ?>
  </tbody>
</table>
<a href="dashboard.php" class="btn btn-secondary">Volver</a>
</body>
</html>