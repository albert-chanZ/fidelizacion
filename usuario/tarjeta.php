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
  <meta name="viewport" content="width=device-width, initial-scale=1"> <!-- ✅ Responsividad -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="manifest" href="../manifest.json">
  <meta name="theme-color" content="#0d6efd">
  <!-- Iconos para navegadores -->
  <link rel="icon" type="image/png" sizes="192x192" href="assets/icons/icon-192x192.png">
  <link rel="apple-touch-icon" href="assets/icons/icon-192x192.png">
</head>

<body class="bg-light d-flex flex-column min-vh-100">
  <div class="container my-auto text-center">
    <div class="card p-4 shadow-lg mx-auto" style="max-width: 400px;">
      <div class="card-body">
        <h3 class="card-title mb-3 text-primary fw-bold">🎟️ Tarjeta de Fidelización</h3>
        <p class="mb-2"><strong>Teléfono:</strong></p>
        <p class="fs-5 text-muted"><?php echo htmlspecialchars($telefono); ?></p>
        <div class="my-3 display-5">📱</div>
        <p class="text-success fw-semibold">¡Gracias por tu preferencia!</p>
      </div>
    </div>

    <a href="panel.php" class="btn btn-secondary mt-4 w-100 w-md-auto">Volver</a>
  </div>

  <footer class="mt-auto text-center py-3 small text-muted">
    &copy; <?php echo date("Y"); ?> Tu Empresa. Todos los derechos reservados.
  </footer>

  <!-- ✅ Bootstrap JS -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

  <!-- ✅ Service Worker -->
  <script>
    if ("serviceWorker" in navigator) {
      navigator.serviceWorker.register("../sw.js")
        .then((reg) => console.log("✅ Service Worker registrado:", reg))
        .catch((err) => console.error("❌ Error al registrar SW:", err));
    }
  </script>

</body>
</html>
