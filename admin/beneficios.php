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
  <meta name="viewport" content="width=device-width, initial-scale=1"> <!-- RESPONSIVO -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

<div class="container mt-4">

  <h2 class="text-center mb-4">
    <?php echo $editando ? 'Editar Beneficio' : 'Agregar Beneficio'; ?>
  </h2>

  <div class="card shadow p-4 mb-4">
    <form method="POST" enctype="multipart/form-data">
      <?php if ($editando): ?>
        <input type="hidden" name="id" value="<?php echo $edit_data['id']; ?>">
      <?php endif; ?>

      <div class="mb-3">
        <label class="form-label">Empresa</label>
        <input name="empresa" class="form-control" required
               value="<?php echo $editando ? $edit_data['empresa'] : ''; ?>">
      </div>

      <div class="mb-3">
        <label class="form-label">Descripción</label>
        <textarea name="descripcion" class="form-control"><?php echo $editando ? $edit_data['descripcion'] : ''; ?></textarea>
      </div>

      <div class="mb-3">
        <label class="form-label">Logo</label>
        <input type="file" name="logo" class="form-control" accept="image/*">
      </div>

      <button class="btn btn-<?php echo $editando ? 'warning' : 'success'; ?> w-100 mb-2">
        <?php echo $editando ? 'Actualizar Beneficio' : 'Agregar Beneficio'; ?>
      </button>

      <?php if ($editando): ?>
        <a href="beneficios.php" class="btn btn-secondary w-100">Cancelar</a>
      <?php endif; ?>
    </form>
  </div>

  <h4 class="text-center mt-4">Empresas con Beneficios</h4>

  <div class="table-responsive mt-3"> <!-- RESPONSIVO -->
    <table class="table table-striped table-bordered align-middle">
      <thead class="table-primary text-center">
        <tr>
          <th>Logo</th>
          <th>Empresa</th>
          <th>Descripción</th>
          <th>Acciones</th>
        </tr>
      </thead>
      <tbody>
      <?php while($b = $beneficios->fetch_assoc()) { ?>
        <tr>
          <td style="width: 90px;" class="text-center">
            <?php if (!empty($b['logo'])): ?>
              <img src="../assets/img/<?php echo $b['logo']; ?>" class="img-fluid" style="max-height: 60px;">
            <?php else: ?> Sin logo <?php endif; ?>
          </td>
          <td><?php echo $b['empresa']; ?></td>
          <td><?php echo $b['descripcion']; ?></td>
          <td class="text-center">
            <a href="beneficios.php?editar=<?php echo $b['id']; ?>" class="btn btn-sm btn-warning mb-1 w-100">Editar</a>
            <a href="beneficios.php?eliminar=<?php echo $b['id']; ?>" class="btn btn-sm btn-danger w-100"
               onclick="return confirm('¿Eliminar este beneficio?');">Eliminar</a>
          </td>
        </tr>
      <?php } ?>
      </tbody>
    </table>
  </div>

  <div class="text-center">
    <a href="dashboard.php" class="btn btn-secondary mt-3 w-100">Volver</a>
  </div>

</div>

</body>
</html>
