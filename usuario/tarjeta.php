<?php
session_start();
if ($_SESSION["tipo"] !== "cliente") {
    header("Location: ../login.php");
    exit();
}
$telefono = $_SESSION["telefono"];
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Tarjeta Digital</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="manifest" href="../manifest.json">
  <meta name="theme-color" content="#0d6efd">
  <!-- Iconos para navegadores -->
  <link rel="icon" type="image/png" sizes="192x192" href="assets/icons/icon-192x192.png">
  <link rel="apple-touch-icon" href="assets/icons/icon-192x192.png">
</head>
<body>
<div class="container mt-5 text-center">
  <div class="card p-4 shadow" style="max-width: 400px; margin: auto;">
    <h3>Tarjeta de Fidelización</h3>
    <p><strong>Teléfono:</strong> <?php echo $telefono; ?></p>
    <div style="font-size: 2rem;">📱</div>
    <p>Gracias por tu preferencia</p>
  </div>
  <br><a href="panel.php" class="btn btn-secondary">Volver</a>
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