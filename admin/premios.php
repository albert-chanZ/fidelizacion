<?php
session_start();
include '../config/db.php';
if ($_SESSION["tipo"] !== "admin") header("Location: ../login.php");


// ELIMINAR premio
// ELIMINAR premio con verificación de canjes relacionados
if (isset($_GET['eliminar'])) {
    $id = $_GET['eliminar'];
    $check = $conn->query("SELECT COUNT(*) AS total FROM canjes WHERE premio_id = $id")->fetch_assoc();

    if ($check['total'] > 0) {
        $error_msg = "❌ No se puede eliminar este premio porque ya ha sido canjeado por clientes.";
    } else {
        $conn->query("DELETE FROM premios WHERE id = $id");
        header("Location: premios.php");
        exit();
    }
}


// EDITAR premio
$editando = false;
$edit_data = null;
if (isset($_GET['editar'])) {
    $editando = true;
    $id = $_GET['editar'];
    $edit_data = $conn->query("SELECT * FROM premios WHERE id = $id")->fetch_assoc();
}

// GUARDAR NUEVO o ACTUALIZADO premio
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nombre = $_POST["nombre"];
    $descripcion = $_POST["descripcion"];
    $puntos = $_POST["puntos"];
    $img_name = "";

    if (isset($_FILES["imagen"]) && $_FILES["imagen"]["error"] === 0) {
        $img_name = basename($_FILES["imagen"]["name"]);
        move_uploaded_file($_FILES["imagen"]["tmp_name"], "../assets/img/" . $img_name);
    }

    if (isset($_POST["id"])) {
        // Actualizar
        $id = $_POST["id"];
        if ($img_name !== "") {
            $stmt = $conn->prepare("UPDATE premios SET nombre=?, descripcion=?, puntos_requeridos=?, imagen=? WHERE id=?");
            $stmt->bind_param("ssisi", $nombre, $descripcion, $puntos, $img_name, $id);
        } else {
            $stmt = $conn->prepare("UPDATE premios SET nombre=?, descripcion=?, puntos_requeridos=? WHERE id=?");
            $stmt->bind_param("ssii", $nombre, $descripcion, $puntos, $id);
        }
        $stmt->execute();
    } else {
        // Insertar nuevo
        $stmt = $conn->prepare("INSERT INTO premios (nombre, descripcion, puntos_requeridos, imagen) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("ssis", $nombre, $descripcion, $puntos, $img_name);
        $stmt->execute();
    }

    header("Location: premios.php");
    exit();
}

$premios = $conn->query("SELECT * FROM premios");
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Premios</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="container mt-4">

<h2><?php echo $editando ? 'Editar Premio' : 'Agregar Nuevo Premio'; ?></h2>
<?php if (!empty($error_msg)): ?>
  <div class="alert alert-danger"><?php echo $error_msg; ?></div>
<?php endif; ?>
<form method="POST" enctype="multipart/form-data" class="mb-4">
  <?php if ($editando): ?>
    <input type="hidden" name="id" value="<?php echo $edit_data['id']; ?>">
  <?php endif; ?>
  <input name="nombre" placeholder="Nombre del premio" class="form-control mb-2"
         value="<?php echo $editando ? $edit_data['nombre'] : ''; ?>" required>
  <textarea name="descripcion" placeholder="Descripción" class="form-control mb-2"><?php echo $editando ? $edit_data['descripcion'] : ''; ?></textarea>
  <input name="puntos" type="number" placeholder="Puntos requeridos" class="form-control mb-2"
         value="<?php echo $editando ? $edit_data['puntos_requeridos'] : ''; ?>" required>
  <input type="file" name="imagen" class="form-control mb-2" accept="image/*">
  <button class="btn btn-<?php echo $editando ? 'warning' : 'primary'; ?>">
    <?php echo $editando ? 'Actualizar Premio' : 'Guardar Premio'; ?>
  </button>
  <?php if ($editando): ?>
    <a href="premios.php" class="btn btn-secondary">Cancelar</a>
  <?php endif; ?>
</form>

<h4>Premios registrados</h4>
<table class="table table-bordered">
  <thead>
    <tr>
      <th>Imagen</th>
      <th>Nombre</th>
      <th>Puntos</th>
      <th>Acciones</th>
    </tr>
  </thead>
  <tbody>
    <?php while ($p = $premios->fetch_assoc()): ?>
      <tr>
        <td style="width: 100px;">
          <?php if (!empty($p['imagen'])): ?>
            <img src="../assets/img/<?php echo $p['imagen']; ?>" alt="img" class="img-fluid" style="max-height: 60px;">
          <?php else: ?>
            Sin imagen
          <?php endif; ?>
        </td>
        <td><?php echo $p['nombre']; ?></td>
        <td><?php echo $p['puntos_requeridos']; ?></td>
        <td>
          <a href="premios.php?editar=<?php echo $p['id']; ?>" class="btn btn-sm btn-warning">Editar</a>
          <a href="premios.php?eliminar=<?php echo $p['id']; ?>" class="btn btn-sm btn-danger"
             onclick="return confirm('¿Estás seguro de eliminar este premio?');">Eliminar</a>
        </td>
      </tr>
    <?php endwhile; ?>
  </tbody>
</table>

<a href="dashboard.php" class="btn btn-secondary">Volver</a>

</body>
</html>
