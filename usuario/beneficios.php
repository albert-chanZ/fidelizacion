<?php
session_start();
include '../config/db.php';
if ($_SESSION["tipo"] !== "cliente") header("Location: ../login.php");

$beneficios = $conn->query("SELECT * FROM beneficios");
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Beneficios para Clientes</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="manifest" href="../manifest.json">
  <meta name="theme-color" content="#0d6efd">
  <!-- Iconos para navegadores -->
  <link rel="icon" type="image/png" sizes="192x192" href="assets/icons/icon-192x192.png">
  <link rel="apple-touch-icon" href="assets/icons/icon-192x192.png">
  <style>
  <style>
    .card-beneficio {
      transition: transform 0.2s;
    }
    .card-beneficio:hover {
      transform: scale(1.02);
    }
    .logo-img {
      max-height: 80px;
      object-fit: contain;
    }
  </style>
</head>
<body class="bg-light">
<div class="container mt-4">
  <div class="text-center mb-4">
    <h2 class="fw-bold">🏷️ Beneficios para Clientes</h2>
    <p class="text-muted">Conoce las empresas donde puedes obtener descuentos y promociones especiales por ser parte del programa.</p>
  </div>

  <div class="row">
    <?php while($b = $beneficios->fetch_assoc()) { ?>
      <div class="col-md-6 col-lg-4 mb-4">
        <div class="card card-beneficio shadow-sm h-100">
          <?php if (!empty($b['logo'])): ?>
            <img src="../assets/img/<?php echo $b['logo']; ?>" class="card-img-top p-3 logo-img mx-auto d-block" alt="Logo">
          <?php endif; ?>
          <div class="card-body">
            <h5 class="card-title text-primary"><?php echo $b['empresa']; ?></h5>
            <p class="card-text"><?php echo $b['descripcion']; ?></p>
          </div>
        </div>
      </div>
    <?php } ?>
  </div>

  <div class="text-center mt-4">
    <a href="panel.php" class="btn btn-outline-secondary">Volver al Panel</a>
  </div>
</div>
<script>
  if ("serviceWorker" in navigator) {
    navigator.serviceWorker.register("../sw.js")
      .then((reg) => console.log("✅ Service Worker registrado:", reg))
      .catch((err) => console.error("❌ Error al registrar SW:", err));
  }
</script>

<script>
if ("serviceWorker" in navigator) {
  navigator.serviceWorker.register("../service-worker.js")
    .then(reg => console.log("✅ Service Worker registrado:", reg.scope))
    .catch(err => console.error("❌ Error al registrar SW:", err));
}
</script>

</body>
</html>
