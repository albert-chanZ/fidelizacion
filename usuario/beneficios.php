<?php
session_start();
include '../config/db.php';
if ($_SESSION["tipo"] !== "cliente") header("Location: ../login.php");

$beneficios = $conn->query("SELECT * FROM beneficios");
?>
<!DOCTYPE html>
<html>
<head>
  <meta charset="UTF-8">
  <title>Beneficios para Clientes</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="container mt-4">
  <h2>Empresas con Beneficios</h2>
  <p class="text-muted">Estas son las empresas donde puedes aprovechar descuentos u ofertas especiales:</p>
  <div class="row">
    <?php while($b = $beneficios->fetch_assoc()) { ?>
      <div class="col-md-6 mb-3">
        <div class="card shadow-sm">
          <div class="card-body">
            <h5 class="card-title"><?php echo $b['empresa']; ?></h5>
            <p class="card-text"><?php echo $b['descripcion']; ?></p>
          </div>
        </div>
      </div>
    <?php } ?>
  </div>
  <a href="panel.php" class="btn btn-secondary mt-3">Volver</a>
</body>
</html>