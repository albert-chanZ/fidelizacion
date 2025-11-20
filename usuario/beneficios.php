<?php
session_start();
include '../config/db.php';
if ($_SESSION["tipo"] !== "cliente") {
  header("Location: ../login.php");
  exit();
}

$beneficios = $conn->query("SELECT * FROM beneficios");
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1"> <!-- ✅ Responsivo -->
  <title>Beneficios para Clientes</title>
  
  <!-- ✅ Bootstrap -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  
  <!-- PWA -->
  <link rel="manifest" href="../manifest.json">
  <meta name="theme-color" content="#0d6efd">
  <link rel="icon" type="image/png" sizes="192x192" href="assets/icons/icon-192x192.png">
  <link rel="apple-touch-icon" href="assets/icons/icon-192x192.png">

  <style>
    .card-beneficio {
      transition: transform 0.25s ease;
      border-radius: 1rem;
      overflow: hidden;
    }
    .card-beneficio:hover {
      transform: scale(1.03);
    }
    .logo-img {
      max-height: 100px;
      width: auto;
      object-fit: contain;
    }
    .card-body p {
      font-size: 0.95rem;
    }
  </style>
</head>

<body class="bg-light d-flex flex-column min-vh-100">
  <div class="container py-4 flex-grow-1">
    <div class="text-center mb-4">
      <h2 class="fw-bold text-primary">🏷️ Beneficios para Clientes</h2>
      <p class="text-muted">Conoce las empresas donde puedes obtener descuentos y promociones especiales por ser parte del programa.</p>
    </div>

    <div class="row g-3">
      <?php while($b = $beneficios->fetch_assoc()) { ?>
        <div class="col-12 col-sm-6 col-lg-4">
          <div class="card card-beneficio shadow-sm h-100 border-0">
            <?php if (!empty($b['logo'])): ?>
              <img src="../assets/img/<?php echo htmlspecialchars($b['logo']); ?>" 
                   class="card-img-top p-3 logo-img mx-auto d-block" 
                   alt="Logo de <?php echo htmlspecialchars($b['empresa']); ?>">
            <?php endif; ?>
            <div class="card-body text-center">
              <h5 class="card-title text-primary fw-semibold"><?php echo htmlspecialchars($b['empresa']); ?></h5>
              <p class="card-text text-secondary"><?php echo htmlspecialchars($b['descripcion']); ?></p>
            </div>
          </div>
        </div>
      <?php } ?>
    </div>

    <div class="text-center mt-4">
      <a href="panel.php" class="btn btn-outline-secondary w-100 w-md-auto">Volver al Panel</a>
    </div>
  </div>

  <footer class="text-center py-3 bg-white border-top mt-auto small text-muted">
    &copy; <?php echo date("Y"); ?> Programa de Fidelización. Todos los derechos reservados.
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
