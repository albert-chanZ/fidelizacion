<?php
session_start();
include '../config/db.php';
if ($_SESSION["tipo"] !== "admin") header("Location: ../login.php");

// ELIMINAR premio validando canjes
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

// GUARDAR (nuevo o actualizado)
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nombre = $_POST["nombre"];
    $descripcion = $_POST["descripcion"];
    $puntos = $_POST["puntos"];
    $img_name = "";

    if (!empty($_FILES["imagen"]["name"])) {
        $img_name = basename($_FILES["imagen"]["name"]);
        move_uploaded_file($_FILES["imagen"]["tmp_name"], "../assets/img/" . $img_name);
    }

    if (isset($_POST["id"])) {
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
  <meta name="viewport" content="width=device-width, initial-scale=1"> <!-- ⭐ Responsivo real -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

  <style>
    @media (max-width: 576px) {
        h2 { font-size: 1.4rem; }
        .table img { max-height: 45px !important; }
        .btn-sm { padding: 0.25rem 0.5rem; font-size: 0.75rem; }
    }
  </style>
</head>

<body class="container mt-4">

<h2 class="mb-3 text-center"><?php echo $editando ? 'Editar Premio' : 'Agregar Nuevo Premio'; ?></h2>

<?php if (!empty($error_msg)): ?>
  <div class="alert alert-danger text-center"><?php echo $error_msg; ?></div>
<?php endif; ?>

<!-- FORMULARIO RESPONSIVO -->
<div class="card shadow-sm p-3 mb-4">
<form method="POST" enctype="multipart/form-data">

  <?php if ($editando): ?>
    <input type="hidden" name="id" value="<?php echo $edit_data['id']; ?>">
  <?php endif; ?>

  <div class="mb-3">
    <label class="form-label">Nombre del premio</label>
    <input name="nombre" class="form-control"
           value="<?php echo $editando ? $edit_data['nombre'] : ''; ?>" required>
  </div>

  <div class="mb-3">
    <label class="form-label">Descripción</label>
    <textarea name="descripcion" class="form-control" rows="2"><?php echo $editando ? $edit_data['descripcion'] : ''; ?></textarea>
  </div>

  <div class="mb-3">
    <label class="form-label">Puntos requeridos</label>
    <input name="puntos" type="number" class="form-control"
           value="<?php echo $editando ? $edit_data['puntos_requeridos'] : ''; ?>" required>
  </div>

  <div class="mb-3">
    <label class="form-label">Imagen del premio</label>
    <input type="file" name="imagen" class="form-control" accept="image/*">
  </div>

  <button class="btn btn-<?php echo $editando ? 'warning' : 'primary'; ?> w-100 mb-2">
    <?php echo $editando ? 'Actualizar Premio' : 'Guardar Premio'; ?>
  </button>

  <?php if ($editando): ?>
    <a href="premios.php" class="btn btn-secondary w-100">Cancelar</a>
  <?php endif; ?>

</form>
</div>

<!-- TABLA DE PREMIOS RESPONSIVA -->
<h4 class="mt-4 mb-3">Premios Registrados</h4>

<div class="table-responsive">
<table class="table table-bordered align-middle">
  <thead class="table-light">
    <tr>
      <th>Imagen</th>
      <th>Nombre</th>
      <th>Puntos</th>
      <th style="min-width: 120px;">Acciones</th>
    </tr>
  </thead>

  <tbody>
    <?php while ($p = $premios->fetch_assoc()): ?>
      <tr>
        <td style="width: 100px;">
          <?php if (!empty($p['imagen'])): ?>
            <img src="../assets/img/<?php echo $p['imagen']; ?>" class="img-fluid" style="max-height: 60px;">
          <?php else: ?>
            <span class="text-muted">Sin imagen</span>
          <?php endif; ?>
        </td>

        <td><?php echo $p['nombre']; ?></td>
        <td><?php echo $p['puntos_requeridos']; ?></td>

        <td>
          <div class="d-flex gap-2 flex-wrap">
            <a href="premios.php?editar=<?php echo $p['id']; ?>" class="btn btn-warning btn-sm">Editar</a>
            <a href="premios.php?eliminar=<?php echo $p['id']; ?>" 
               class="btn btn-danger btn-sm"
               onclick="return confirm('¿Estás seguro de eliminar este premio?');">
              Eliminar
            </a>
          </div>
        </td>
      </tr>
    <?php endwhile; ?>
  </tbody>
</table>
</div>

<a href="dashboard.php" class="btn btn-secondary mt-3 w-100">Volver</a>

</body>
</html>
