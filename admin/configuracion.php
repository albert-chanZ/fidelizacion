<?php
session_start();
if ($_SESSION["tipo"] !== "admin") {
    header("Location: ../login.php");
    exit();
}
include '../config/db.php';
$telefono = $_SESSION["telefono"];
$admin = $conn->query("SELECT * FROM clientes WHERE telefono='$telefono'")->fetch_assoc();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nombre = $_POST["nombre"];
    $password = $_POST["password"];

    if (!empty($password)) {
        $hash = password_hash($password, PASSWORD_DEFAULT);
        $conn->query("UPDATE clientes SET nombre='$nombre', password='$hash' WHERE telefono='$telefono'");
    } else {
        $conn->query("UPDATE clientes SET nombre='$nombre' WHERE telefono='$telefono'");
    }

    echo "<script>alert('Configuración actualizada correctamente'); window.location='configuracion.php';</script>";
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Configuración del Administrador</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<div class="container mt-4">
  <h2 class="text-center mb-4">⚙️ Configuración del Administrador</h2>

  <div class="card shadow-sm">
    <div class="card-body">
      <form method="POST">
        <div class="mb-3">
          <label class="form-label">Nombre</label>
          <input type="text" name="nombre" class="form-control" value="<?= $admin['nombre'] ?>" required>
        </div>
        <div class="mb-3">
          <label class="form-label">Nueva Contraseña (opcional)</label>
          <input type="password" name="password" class="form-control" placeholder="Dejar vacío si no desea cambiarla">
        </div>
        <button type="submit" class="btn btn-primary w-100">Guardar Cambios</button>
      </form>
    </div>
  </div>

  <a href="dashboard.php" class="btn btn-secondary mt-3">⬅ Volver al Panel</a>
</div>
</body>
</html>
