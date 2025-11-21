<?php
session_start();
include '../config/db.php';
if ($_SESSION["tipo"] !== "admin") header("Location: ../login.php");

$error = "";
$editando = false;
$edit_data = null;

// ELIMINAR CLIENTE
if (isset($_GET['eliminar'])) {
    $tel = $_GET['eliminar'];
    $check = $conn->query("SELECT COUNT(*) AS total FROM canjes WHERE telefono = '$tel'")->fetch_assoc();
    if ($check['total'] > 0) {
        $error = "❌ No se puede eliminar el cliente, tiene canjes registrados.";
    } else {
        $conn->query("DELETE FROM clientes WHERE telefono = '$tel'");
        header("Location: clientes.php");
        exit();
    }
}

// EDITAR CLIENTE (cargar datos)
if (isset($_GET['editar'])) {
    $editando = true;
    $edit_tel = $_GET['editar'];
    $edit_data = $conn->query("SELECT * FROM clientes WHERE telefono='$edit_tel'")->fetch_assoc();
}

// GUARDAR NUEVO O ACTUALIZADO
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["telefono"])) {
    $telefono = $_POST["telefono"];
    $nombre = $_POST["nombre"];
    $apellidos = $_POST["apellidos"];
    $direccion = $_POST["direccion"];
    $correo = $_POST["correo"];
    $estado = $_POST["estado"];
    $ciudad = $_POST["ciudad"];
    $password = !empty($_POST["password"]) ? password_hash($_POST["password"], PASSWORD_DEFAULT) : null;

    if (isset($_POST["original_tel"])) {
        // Editar
        $original_tel = $_POST["original_tel"];
        if ($password) {
            $stmt = $conn->prepare("UPDATE clientes SET telefono=?, nombre=?, apellidos=?, direccion=?, correo=?, estado=?, ciudad=?, password=? WHERE telefono=?");
            $stmt->bind_param("sssssssss", $telefono, $nombre, $apellidos, $direccion, $correo, $estado, $ciudad, $password, $original_tel);
        } else {
            $stmt = $conn->prepare("UPDATE clientes SET telefono=?, nombre=?, apellidos=?, direccion=?, correo=?, estado=?, ciudad=? WHERE telefono=?");
            $stmt->bind_param("ssssssss", $telefono, $nombre, $apellidos, $direccion, $correo, $estado, $ciudad, $original_tel);
        }
        $stmt->execute();
    } else {
        // Nuevo
        $stmt = $conn->prepare("INSERT INTO clientes (telefono, nombre, apellidos, direccion, correo, estado, ciudad, password) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("ssssssss", $telefono, $nombre, $apellidos, $direccion, $correo, $estado, $ciudad, $password);
        $stmt->execute();
    }

    header("Location: clientes.php");
    exit();
}

$clientes = $conn->query("SELECT * FROM clientes");
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Gestión de Clientes</title>
  <meta name="viewport" content="width=device-width, initial-scale=1"> <!-- 🚀 RESPONSIVO -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

  <style>
      body {
          background: #f8f9fa;
      }
      .card-form {
          background: white;
          padding: 20px;
          border-radius: 10px;
          box-shadow: 0 2px 10px rgba(0,0,0,0.08);
      }
  </style>
</head>

<body class="container py-4">

<h2 class="mb-4 text-center"><?php echo $editando ? 'Editar Cliente' : 'Registrar Cliente'; ?></h2>

<?php if (!empty($error)): ?>
  <div class="alert alert-danger text-center"><?php echo $error; ?></div>
<?php endif; ?>

<!-- FORMULARIO RESPONSIVO -->
<div class="card-form mb-4">
<form method="POST" class="row g-3">

  <?php if ($editando): ?>
    <input type="hidden" name="original_tel" value="<?php echo $edit_data['telefono']; ?>">
  <?php endif; ?>

  <div class="col-md-4 col-12">
      <input class="form-control" name="telefono" placeholder="Teléfono" required value="<?php echo $editando ? $edit_data['telefono'] : ''; ?>">
  </div>

  <div class="col-md-4 col-12">
      <input class="form-control" name="nombre" placeholder="Nombre" required value="<?php echo $editando ? $edit_data['nombre'] : ''; ?>">
  </div>

  <div class="col-md-4 col-12">
      <input class="form-control" name="apellidos" placeholder="Apellidos" value="<?php echo $editando ? $edit_data['apellidos'] : ''; ?>">
  </div>

  <div class="col-md-6 col-12">
      <input class="form-control" name="direccion" placeholder="Dirección" value="<?php echo $editando ? $edit_data['direccion'] : ''; ?>">
  </div>

  <div class="col-md-6 col-12">
      <input class="form-control" name="correo" placeholder="Correo electrónico" value="<?php echo $editando ? $edit_data['correo'] : ''; ?>">
  </div>

  <div class="col-md-4 col-12">
      <input class="form-control" name="estado" placeholder="Estado" value="<?php echo $editando ? $edit_data['estado'] : ''; ?>">
  </div>

  <div class="col-md-4 col-12">
      <input class="form-control" name="ciudad" placeholder="Ciudad" value="<?php echo $editando ? $edit_data['ciudad'] : ''; ?>">
  </div>

  <div class="col-md-4 col-12">
      <input type="password" class="form-control" name="password" placeholder="<?php echo $editando ? 'Dejar vacío para no cambiar' : 'Contraseña'; ?>">
  </div>

  <div class="col-12 text-center">
    <button class="btn btn-<?php echo $editando ? 'warning' : 'success'; ?> px-4">
      <?php echo $editando ? 'Actualizar Cliente' : 'Registrar Cliente'; ?>
    </button>
    <?php if ($editando): ?>
      <a href="clientes.php" class="btn btn-secondary px-4">Cancelar</a>
    <?php endif; ?>
  </div>

</form>
</div>

<h4 class="mt-4 mb-3 text-center">Clientes Registrados</h4>

<!-- TABLA 100% RESPONSIVA -->
<div class="table-responsive shadow-sm">
<table class="table table-bordered table-striped align-middle">
  <thead class="table-primary text-center">
    <tr>
      <th>Teléfono</th>
      <th>Nombre</th>
      <th>Puntos</th>
      <th>Acciones</th>
    </tr>
  </thead>
  <tbody>
  <?php while ($row = $clientes->fetch_assoc()) { ?>
    <tr>
      <td><?php echo $row['telefono']; ?></td>
      <td><?php echo $row['nombre'] . ' ' . $row['apellidos']; ?></td>
      <td><?php echo $row['puntos']; ?></td>
      <td class="text-center">
        <a href="clientes.php?editar=<?php echo $row['telefono']; ?>" class="btn btn-sm btn-warning">Editar</a>
        <a href="clientes.php?eliminar=<?php echo $row['telefono']; ?>" class="btn btn-sm btn-danger" onclick="return confirm('¿Eliminar este cliente?');">Eliminar</a>
      </td>
    </tr>
  <?php } ?>
  </tbody>
</table>
</div>

<div class="text-center mt-4">
    <a href="dashboard.php" class="btn btn-secondary px-4">Volver</a>
</div>

</body>
</html>
