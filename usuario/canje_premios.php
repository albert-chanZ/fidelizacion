<?php
session_start();
include '../config/db.php';
if ($_SESSION["tipo"] !== "cliente") {
    header("Location: ../login.php");
    exit();
}

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
        exit();
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1"> <!-- 🔹 Responsividad -->
  <title>Canje de Premios</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
  <link rel="manifest" href="../manifest.json">
  <meta name="theme-color" content="#0d6efd">
  <link rel="icon" type="image/png" sizes="192x192" href="../assets/icons/icon-192x192.png">
  <link rel="apple-touch-icon" href="../assets/icons/icon-192x192.png">
  <style>
    body {
      background-color: #f8f9fa;
    }
    .titulo {
      text-align: center;
      background: linear-gradient(45deg, #0d6efd, #0dcaf0);
      color: white;
      border-radius: 10px;
      padding: 20px;
      margin-bottom: 25px;
      box-shadow: 0 3px 6px rgba(0,0,0,0.1);
    }
    .card {
      transition: transform 0.2s;
    }
    .card:hover {
      transform: scale(1.03);
    }

    /* 🔹 Mejor visualización en pantallas pequeñas */
    @media (max-width: 768px) {
      .titulo h2 {
        font-size: 1.5rem;
      }
      .card-body h5 {
        font-size: 1rem;
      }
      .card-body p {
        font-size: 0.9rem;
      }
      .btn {
        font-size: 0.9rem;
      }
    }
  </style>
</head>
<body>

<div class="container py-4">
  <!-- Encabezado -->
  <div class="titulo">
    <h2 class="fw-bold mb-0">🎁 Canjea tus Premios</h2>
    <p class="mb-0 mt-2">Tienes <span class="badge bg-warning text-dark fs-6"><?php echo $cliente['puntos']; ?> puntos</span></p>
  </div>

  <!-- Botón historial -->
  <div class="text-center mb-4">
    <a href="historial.php" class="btn btn-outline-dark">
      <i class="bi bi-clock-history"></i> Ver historial de canjes
    </a>
  </div>

  <!-- Lista de premios -->
  <div class="row g-3">
    <?php while ($p = $premios->fetch_assoc()) { ?>
      <div class="col-12 col-sm-6 col-md-4 col-lg-3">
        <form method="POST" class="card shadow-sm h-100">
          <?php if (!empty($p['imagen'])): ?>
            <img src="../assets/img/<?php echo $p['imagen']; ?>" class="card-img-top" alt="Premio" style="max-height: 180px; object-fit: cover;">
          <?php endif; ?>
          <div class="card-body d-flex flex-column">
            <h5 class="card-title"><?php echo htmlspecialchars($p['nombre']); ?></h5>
            <p class="card-text flex-grow-1"><?php echo htmlspecialchars($p['descripcion']); ?></p>
            <p class="fw-bold mb-2 text-primary"><?php echo $p['puntos_requeridos']; ?> pts</p>
            <input type="hidden" name="premio_id" value="<?php echo $p['id']; ?>">
            <button class="btn btn-primary w-100 mt-auto" type="submit">
              <i class="bi bi-gift-fill"></i> Canjear
            </button>
          </div>
        </form>
      </div>
    <?php } ?>
  </div>

  <!-- Volver -->
  <div class="text-center mt-4">
    <a href="panel.php" class="btn btn-secondary px-4 py-2">
      <i class="bi bi-arrow-left"></i> Volver al Panel
    </a>
  </div>
</div>

<!-- Service Worker -->
<script>
if ("serviceWorker" in navigator) {
  navigator.serviceWorker.register("../sw.js")
    .then(reg => console.log("✅ Service Worker registrado:", reg.scope))
    .catch(err => console.error("❌ Error al registrar SW:", err));
}
</script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
