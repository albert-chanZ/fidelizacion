<?php
session_start();
if ($_SESSION["tipo"] !== "cliente") {
    header("Location: ../login.php");
    exit();
}
include '../config/db.php';

$telefono = $_SESSION["telefono"];

// Verificar si el usuario existe
$cliente = $conn->query("SELECT * FROM clientes WHERE telefono='$telefono'")->fetch_assoc();
if (!$cliente) {
    echo "Error: Cliente no encontrado.";
    exit;
}

// Crear tarjeta
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["accion"]) && $_POST["accion"] === "agregar") {
    $numero = $_POST["numero_tarjeta"];
    $banco = $_POST["banco"];
    $fecha_vencimiento = $_POST["fecha_vencimiento"];

    if (!empty($numero) && !empty($banco) && !empty($fecha_vencimiento)) {
        $stmt = $conn->prepare("INSERT INTO tarjetas (telefono, numero, banco, fecha_vencimiento) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("ssss", $telefono, $numero, $banco, $fecha_vencimiento);
        $stmt->execute();
    }
}

// Editar tarjeta
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["accion"]) && $_POST["accion"] === "editar") {
    $id = $_POST["id"];
    $banco = $_POST["banco"];
    $fecha_vencimiento = $_POST["fecha_vencimiento"];
    $stmt = $conn->prepare("UPDATE tarjetas SET banco=?, fecha_vencimiento=? WHERE id=? AND telefono=?");
    $stmt->bind_param("ssis", $banco, $fecha_vencimiento, $id, $telefono);
    $stmt->execute();
}

// Eliminar tarjeta
if (isset($_GET["eliminar"])) {
    $id = $_GET["eliminar"];
    $stmt = $conn->prepare("DELETE FROM tarjetas WHERE id=? AND telefono=?");
    $stmt->bind_param("is", $id, $telefono);
    $stmt->execute();
}

// Obtener las tarjetas del cliente
$sql = "SELECT * FROM tarjetas WHERE telefono = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $telefono);
$stmt->execute();
$tarjetas = $stmt->get_result();
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1"> <!-- ✅ Responsive -->
  <title>Mis Tarjetas de Débito</title>
  
  <!-- Bootstrap + Icons -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">

  <!-- PWA -->
  <link rel="manifest" href="../manifest.json">
  <meta name="theme-color" content="#0d6efd">

  <style>
    body {
      background: #f8f9fa;
    }
    .card-tarjeta {
      border-radius: 15px;
      color: #fff;
      padding: 20px;
      margin-bottom: 15px;
      background: linear-gradient(135deg, #007bff, #00b4d8);
      box-shadow: 0 4px 10px rgba(0,0,0,0.1);
      transition: transform 0.2s;
    }
    .card-tarjeta:hover {
      transform: scale(1.02);
    }
    .card-tarjeta .numero {
      font-size: 1.1rem;
      letter-spacing: 2px;
    }
    .add-btn {
      position: fixed;
      bottom: 25px;
      right: 25px;
      border-radius: 50%;
      width: 60px;
      height: 60px;
      font-size: 1.5rem;
      display: flex;
      align-items: center;
      justify-content: center;
      box-shadow: 0 4px 10px rgba(0,0,0,0.2);
      z-index: 1050;
    }
    @media (max-width: 768px) {
      .card-tarjeta {
        font-size: 0.9rem;
      }
    }
  </style>
</head>

<body>
<div class="container my-4">
  <h2 class="text-center mb-4 text-primary"><i class="bi bi-credit-card-2-front"></i> Mis Tarjetas de Débito</h2>

  <!-- Lista de tarjetas -->
  <div class="row justify-content-center">
    <div class="col-12 col-md-10 col-lg-8">
      <?php if ($tarjetas->num_rows > 0): ?>
        <?php while ($t = $tarjetas->fetch_assoc()): ?>
          <div class="card-tarjeta">
            <div class="d-flex justify-content-between align-items-center flex-wrap">
              <div>
                <h5 class="mb-1"><?= htmlspecialchars($t["banco"]) ?></h5>
                <p class="numero mt-2 mb-0"><?= str_repeat("•", 12) . " " . substr($t["numero"], -4) ?></p>
              </div>
              <div class="text-end mt-2 mt-sm-0">
                <small>Vence: <?= htmlspecialchars($t["fecha_vencimiento"]) ?></small>
                <div class="mt-2">
                  <button class="btn btn-light btn-sm" data-bs-toggle="modal" data-bs-target="#editarModal<?= $t['id'] ?>">
                    <i class="bi bi-pencil-square"></i>
                  </button>
                  <a href="?eliminar=<?= $t['id'] ?>" class="btn btn-danger btn-sm" onclick="return confirm('¿Eliminar esta tarjeta?')">
                    <i class="bi bi-trash"></i>
                  </a>
                </div>
              </div>
            </div>
          </div>

          <!-- Modal Editar -->
          <div class="modal fade" id="editarModal<?= $t['id'] ?>" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
              <div class="modal-content">
                <div class="modal-header bg-warning text-white">
                  <h5 class="modal-title"><i class="bi bi-pencil-square"></i> Editar Tarjeta</h5>
                  <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form method="POST">
                  <div class="modal-body">
                    <input type="hidden" name="accion" value="editar">
                    <input type="hidden" name="id" value="<?= $t['id'] ?>">
                    <div class="mb-3">
                      <label class="form-label">Banco</label>
                      <input type="text" name="banco" class="form-control" value="<?= htmlspecialchars($t['banco']) ?>" required>
                    </div>
                    <div class="mb-3">
                      <label class="form-label">Fecha de Vencimiento</label>
                      <input type="month" name="fecha_vencimiento" class="form-control" value="<?= htmlspecialchars($t['fecha_vencimiento']) ?>" required>
                    </div>
                  </div>
                  <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-warning text-white">Guardar Cambios</button>
                  </div>
                </form>
              </div>
            </div>
          </div>
        <?php endwhile; ?>
      <?php else: ?>
        <div class="alert alert-info text-center shadow-sm">No tienes tarjetas registradas aún.</div>
      <?php endif; ?>

      <div class="text-center mt-4">
        <a href="panel.php" class="btn btn-outline-secondary w-100 w-md-auto"><i class="bi bi-arrow-left"></i> Volver al Panel</a>
      </div>
    </div>
  </div>
</div>

<!-- Botón flotante para agregar tarjeta -->
<button class="btn btn-primary add-btn" data-bs-toggle="modal" data-bs-target="#agregarTarjetaModal">
  <i class="bi bi-plus-lg"></i>
</button>

<!-- Modal para agregar tarjeta -->
<div class="modal fade" id="agregarTarjetaModal" tabindex="-1" aria-labelledby="agregarTarjetaLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header bg-primary text-white">
        <h5 class="modal-title" id="agregarTarjetaLabel"><i class="bi bi-credit-card"></i> Agregar Tarjeta</h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
      </div>
      <form method="POST">
        <div class="modal-body">
          <input type="hidden" name="accion" value="agregar">
          <div class="mb-3">
            <label for="banco" class="form-label">Banco</label>
            <input type="text" class="form-control" id="banco" name="banco" required>
          </div>
          <div class="mb-3">
            <label for="numero_tarjeta" class="form-label">Número de Tarjeta</label>
            <input type="text" class="form-control" id="numero_tarjeta" name="numero_tarjeta" maxlength="16" pattern="\d{16}" required>
            <small class="text-muted">16 dígitos sin espacios.</small>
          </div>
          <div class="mb-3">
            <label for="fecha_vencimiento" class="form-label">Fecha de Vencimiento</label>
            <input type="month" class="form-control" id="fecha_vencimiento" name="fecha_vencimiento" required>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
          <button type="submit" class="btn btn-primary">Guardar</button>
        </div>
      </form>
    </div>
  </div>
</div>

<!-- Scripts -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
if ("serviceWorker" in navigator) {
  navigator.serviceWorker.register("../sw.js")
    .then(reg => console.log("✅ Service Worker activo:", reg.scope))
    .catch(err => console.error("❌ Error al registrar SW:", err));
}
</script>
</body>
</html>
