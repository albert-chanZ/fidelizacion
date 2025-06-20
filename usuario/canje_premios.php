<?php
session_start();
include '../config/db.php';
if ($_SESSION["tipo"] !== "cliente") header("Location: ../login.php");

$telefono = $_SESSION["telefono"];
$cliente = $conn->query("SELECT * FROM clientes WHERE telefono='$telefono'")->fetch_assoc();
$premios = $conn->query("SELECT * FROM premios");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $premio_id = $_POST["premio_id"];
    $premio = $conn->query("SELECT * FROM premios WHERE id=$premio_id")->fetch_assoc();
    if ($cliente["puntos"] >= $premio["puntos_requeridos"]) {
        $conn->query("INSERT INTO canjes (telefono, premio_id) VALUES ('$telefono', $premio_id)");
        $conn->query("UPDATE clientes SET puntos = puntos - {$premio['puntos_requeridos']} WHERE telefono = '$telefono'");
        header("Location: panel.php");
    }
}
?>
<!DOCTYPE html>
<html>
<head><meta charset="UTF-8"><title>Canje de Premios</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet"></head>
<body class="container mt-4">
<h2>Canjea tus Premios</h2>
<div class="row">
<?php while ($p = $premios->fetch_assoc()) { ?>
  <div class="col-md-4 mb-3">
    <form method="POST" class="card shadow-sm h-100">
      <?php if (!empty($p['imagen'])): ?>
        <img src="../assets/img/<?php echo $p['imagen']; ?>" class="card-img-top" style="max-height: 200px; object-fit: cover;">
      <?php endif; ?>
      <div class="card-body">
        <h5 class="card-title"><?php echo $p['nombre']; ?></h5>
        <p class="card-text"><?php echo $p['descripcion']; ?></p>
        <p><strong><?php echo $p['puntos_requeridos']; ?> pts</strong></p>
        <input type="hidden" name="premio_id" value="<?php echo $p['id']; ?>">
        <button class="btn btn-primary w-100" type="submit">Canjear</button>
      </div>
    </form>
  </div>
<?php } ?>
</div>
<a href="panel.php" class="btn btn-secondary mt-3">Volver</a>
</body>
</html>
