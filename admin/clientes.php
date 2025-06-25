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
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="container mt-4">

<h2><?php echo $editando ? 'Editar Cliente' : 'Registrar Cliente'; ?></h2>

<?php if (!empty($error)): ?>
  <div class="alert alert-danger"><?php echo $error; ?></div>
<?php endif; ?>

<form method="POST" class="row g-3 mb-4">
  <?php if ($editando): ?>
    <input type="hidden" name="original_tel" value="<?php echo $edit_data['telefono']; ?>">
  <?php endif; ?>

  <div class="col-md-4"><input class="form-control" name="telefono" placeholder="Teléfono" required value="<?php echo $editando ? $edit_data['telefono'] : ''; ?>"></div>
  <div class="col-md-4"><input class="form-control" name="nombre" placeholder="Nombre" required value="<?php echo $editando ? $edit_data['nombre'] : ''; ?>"></div>
  <div class="col-md-4"><input class="form-control" name="apellidos" placeholder="Apellidos" value="<?php echo $editando ? $edit_data['apellidos'] : ''; ?>"></div>
  <div class="col-md-6"><input class="form-control" name="direccion" placeholder="Dirección" value="<?php echo $editando ? $edit_data['direccion'] : ''; ?>"></div>
  <div class="col-md-6"><input class="form-control" name="correo" placeholder="Correo electrónico" value="<?php echo $editando ? $edit_data['correo'] : ''; ?>"></div>
  <div class="col-md-4"><input class="form-control" name="estado" placeholder="Estado" value="<?php echo $editando ? $edit_data['estado'] : ''; ?>"></div>
  <div class="col-md-4"><input class="form-control" name="ciudad" placeholder="Ciudad" value="<?php echo $editando ? $edit_data['ciudad'] : ''; ?>"></div>
  <div class="col-md-4"><input type="password" class="form-control" name="password" placeholder="<?php echo $editando ? 'Dejar vacío para no cambiar' : 'Contraseña'; ?>"></div>
  <div class="col-12">
    <button class="btn btn-<?php echo $editando ? 'warning' : 'success'; ?>">
      <?php echo $editando ? 'Actualizar Cliente' : 'Registrar Cliente'; ?>
    </button>
    <?php if ($editando): ?>
      <a href="clientes.php" class="btn btn-secondary">Cancelar</a>
    <?php endif; ?>
  </div>
</form>

<h4>Clientes Registrados</h4>
<table class="table table-bordered table-striped">
  <thead><tr><th>Teléfono</th><th>Nombre</th><th>Puntos</th><th>Acciones</th></tr></thead>
  <tbody>
  <?php while ($row = $clientes->fetch_assoc()) { ?>
    <tr>
      <td><?php echo $row['telefono']; ?></td>
      <td><?php echo $row['nombre'] . ' ' . $row['apellidos']; ?></td>
      <td><?php echo $row['puntos']; ?></td>
      <td>
        <a href="clientes.php?editar=<?php echo $row['telefono']; ?>" class="btn btn-sm btn-warning">Editar</a>
        <a href="clientes.php?eliminar=<?php echo $row['telefono']; ?>" class="btn btn-sm btn-danger" onclick="return confirm('¿Eliminar este cliente?');">Eliminar</a>
      </td>
    </tr>
  <?php } ?>
  </tbody>
</table>

<a href="dashboard.php" class="btn btn-secondary">Volver</a>

</body>
</html>
