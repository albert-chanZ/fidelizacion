<?php
include 'config/db.php';

$mensaje = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
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

    if ($stmt->execute()) {
        $mensaje = "✅ Cliente registrado con éxito.";
    } else {
        $mensaje = "❌ Error al registrar: " . $stmt->error;
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Registro de Cliente</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container mt-5">
  <div class="card shadow p-4">
    <h2 class="text-center mb-4">Registro de Cliente</h2>

    <?php if ($mensaje): ?>
      <div class="alert alert-info"><?php echo $mensaje; ?></div>
    <?php endif; ?>

    <form method="POST">
      <div class="row g-3">
        <div class="col-md-6">
          <label class="form-label">Teléfono</label>
          <input type="text" name="telefono" class="form-control" required>
        </div>
        <div class="col-md-6">
          <label class="form-label">Correo electrónico</label>
          <input type="email" name="correo" class="form-control" required>
        </div>
        <div class="col-md-6">
          <label class="form-label">Nombre</label>
          <input type="text" name="nombre" class="form-control" required>
        </div>
        <div class="col-md-6">
          <label class="form-label">Apellidos</label>
          <input type="text" name="apellidos" class="form-control">
        </div>
        <div class="col-12">
          <label class="form-label">Dirección</label>
          <input type="text" name="direccion" class="form-control">
        </div>
        <div class="col-md-6">
          <label class="form-label">Estado</label>
          <input type="text" name="estado" class="form-control">
        </div>
        <div class="col-md-6">
          <label class="form-label">Ciudad</label>
          <input type="text" name="ciudad" class="form-control">
        </div>
        <div class="col-md-6">
          <label class="form-label">Contraseña</label>
          <input type="password" name="password" class="form-control" required>
        </div>
        <div class="col-12">
          <button class="btn btn-success w-100">Registrar Cliente</button>
        </div>
      </div>
    </form>
    <div class="text-center mt-3">
      <a href="login.php">¿Ya tienes cuenta? Iniciar sesión</a>
    </div>
  </div>
</div>
</body>
</html>
