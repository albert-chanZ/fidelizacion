<?php
session_start();
include '../config/db.php';
if ($_SESSION["tipo"] !== "admin") header("Location: ../login.php");

$error = "";
$editando = false;
$edit_data = null;

// ELIMINAR beneficio
if (isset($_GET['eliminar'])) {
    $id = $_GET['eliminar'];
    $conn->query("DELETE FROM beneficios WHERE id = $id");
    header("Location: beneficios.php");
    exit();
}

// EDITAR beneficio
if (isset($_GET['editar'])) {
    $editando = true;
    $id = $_GET['editar'];
    $edit_data = $conn->query("SELECT * FROM beneficios WHERE id = $id")->fetch_assoc();
}

// INSERTAR o ACTUALIZAR
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $empresa = $_POST["empresa"];
    $descripcion = $_POST["descripcion"];
    $logo = "";

    if (isset($_FILES["logo"]) && $_FILES["logo"]["error"] === 0) {
        $logo = basename($_FILES["logo"]["name"]);
        move_uploaded_file($_FILES["logo"]["tmp_name"], "../assets/img/" . $logo);
    }

    if (isset($_POST["id"])) {
        $id = $_POST["id"];
        if ($logo != "") {
            $stmt = $conn->prepare("UPDATE beneficios SET empresa=?, descripcion=?, logo=? WHERE id=?");
            $stmt->bind_param("sssi", $empresa, $descripcion, $logo, $id);
        } else {
            $stmt = $conn->prepare("UPDATE beneficios SET empresa=?, descripcion=? WHERE id=?");
            $stmt->bind_param("ssi", $empresa, $descripcion, $id);
        }
        $stmt->execute();
    } else {
        $stmt = $conn->prepare("INSERT INTO beneficios (empresa, descripcion, logo) VALUES (?, ?, ?)");
        $stmt->bind_param("sss", $empresa, $descripcion, $logo);
        $stmt->execute();
    }

    header("Location: beneficios.php");
    exit();
}

$beneficios = $conn->query("SELECT * FROM beneficios");
?>

<!DOCTYPE html>
<html>
<head>
  <meta charset="UTF-8">
  <title>Beneficios</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="container mt-4">

<h2><?php echo $editando ? 'Editar Beneficio' : 'Agregar Beneficio'; ?></h2>

<form method="POST" enctype="multipart/form-data" class="mb-4">
  <?php if ($editando): ?>
    <input type="hidden" name="id" value="<?php echo $edit_data['id']; ?>">
  <?php endif; ?>
  <input name="empresa" class="form-control mb-2" placeholder="Nombre de la empresa" required
         value="<?php echo $editando ? $edit_data['empresa'] : ''; ?>">
  <textarea name="descripcion" class="form-control mb-2" placeholder="Descripción del beneficio"><?php echo $editando ? $edit_data['descripcion'] : ''; ?></textarea>
  <input type="file" name="logo" class="form-control mb-2" accept="image/*">
  <button class="btn btn-<?php echo $editando ? 'warning' : 'success'; ?>">
    <?php echo $editando ? 'Actualizar Beneficio' : 'Agregar Beneficio'; ?>
  </button>
  <?php if ($editando): ?>
    <a href="beneficios.php" class="btn btn-secondary">Cancelar</a>
  <?php endif; ?>
</form>

<h4>Empresas con Beneficios</h4>
<table class="table table-bordered">
  <thead><tr><th>Logo</th><th>Empresa</th><th>Descripción</th><th>Acciones</th></tr></thead>
  <tbody>
  <?php while($b = $beneficios->fetch_assoc()) { ?>
    <tr>
      <td style="width: 100px;">
        <?php if (!empty($b['logo'])): ?>
          <img src="../assets/img/<?php echo $b['logo']; ?>" class="img-fluid" style="max-height: 60px;">
        <?php else: ?> Sin logo <?php endif; ?>
      </td>
      <td><?php echo $b['empresa']; ?></td>
      <td><?php echo $b['descripcion']; ?></td>
      <td>
        <a href="beneficios.php?editar=<?php echo $b['id']; ?>" class="btn btn-sm btn-warning">Editar</a>
        <a href="beneficios.php?eliminar=<?php echo $b['id']; ?>" class="btn btn-sm btn-danger"
           onclick="return confirm('¿Eliminar este beneficio?');">Eliminar</a>
      </td>
    </tr>
  <?php } ?>
  </tbody>
</table>

<a href="dashboard.php" class="btn btn-secondary">Volver</a>
</body>
</html>
