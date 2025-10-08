<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="manifest" href="manifest.json">
<meta name="theme-color" content="#0d6efd">

<!-- Iconos para navegadores -->
<link rel="icon" type="image/png" sizes="192x192" href="assets/icons/icon-192x192.png">
<link rel="apple-touch-icon" href="assets/icons/icon-192x192.png">
</head>
<body>
    <script>
  if ("serviceWorker" in navigator) {
    navigator.serviceWorker.register("sw.js")
      .then(reg => console.log("Service Worker registrado:", reg))
      .catch(err => console.error("Error al registrar SW:", err));
  }
</script>
</body>
</html>
<?php
session_start();
if (!isset($_SESSION["telefono"])) {
    header("Location: login.php");
    exit();
}

if ($_SESSION["tipo"] === "admin") {
    header("Location: admin/dashboard.php");
} else {
    header("Location: usuario/panel.php");
}
exit();
?>