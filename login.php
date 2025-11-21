<?php
session_start();
include 'config/db.php'; // Conexión a la base de datos

$error = ""; // Inicializamos la variable de error

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $telefono = $_POST["telefono"] ?? '';
    $password = $_POST["password"] ?? '';

   if (!empty($telefono) && !empty($password)) {
        $stmt = $conn->prepare("SELECT * FROM clientes WHERE telefono = ?");
        $stmt->bind_param("s", $telefono);
        $stmt->execute();
        $result = $stmt->get_result();
        $user = $result->fetch_assoc();

        if ($user) {
            if (password_verify($password, $user["password"])) {
                $user_rol = $user["telefono"] ?? 'cliente'; 

                if ($user_rol == "admin") {
                    $_SESSION["telefono"] = $user["telefono"];
                    $_SESSION["tipo"] = "admin";
                    header("Location: admin/dashboard.php");
                    exit();
                } else {
                    $_SESSION["telefono"] = $user["telefono"];
                    $_SESSION["tipo"] = "cliente";
                    header("Location: usuario/panel.php");
                    exit();
                }
            } else {
                $error = "Contraseña incorrecta.";
            }
        } else {
            $error = "Usuario no encontrado.";
        }
    } else {
        $error = "Por favor, ingresa tu teléfono y contraseña.";
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0"> <title>Login - Fidelización</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <link rel="manifest" href="/manifest.json">
    <meta name="theme-color" content="#0d6efd">
    <link rel="icon" type="image/png" sizes="192x192" href="assets/icons/icon-192x192.png">
    <link rel="apple-touch-icon" href="assets/icons/icon-192x192.png">
    <!-- Iconos para navegadores -->
    <link rel="icon" type="image/png" sizes="192x192" href="assets/icons/icon-192x192.png">
    <link rel="apple-touch-icon" href="assets/icons/icon-192x192.png">
    <style>
        body {
            background: linear-gradient(to right, #667eea, #764ba2);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .login-card {
            width: 100%;
            max-width: 400px;
            border-radius: 20px;
            overflow: hidden;
        }
        .login-card .card-header {
            background-color: #fff;
            text-align: center;
            padding: 2rem 1rem 1rem;
            border-bottom: none;
        }
        .login-card .card-body {
            background-color: #f8f9fa;
            padding: 2rem;
        }
        .login-card .form-control {
            border-radius: 10px;
        }
        .login-card .btn-primary {
            border-radius: 10px;
        }
        .brand-icon {
            font-size: 3rem;
            color: #764ba2;
        }
    </style>
</head>
<body>
<div class="card shadow login-card">
    <div class="card-header">
        <div class="brand-icon mb-2">
            <i class="bi bi-person-circle"></i>
        </div>
        <h4 class="mb-0">Iniciar Sesión</h4>
    </div>
    <div class="card-body">
        <?php if (!empty($error)): ?>
            <div class="alert alert-danger text-center"><?= $error ?></div>
        <?php endif; ?>
        
        <form method="POST">
            <div class="mb-3">
                <label for="telefono" class="form-label">Teléfono</label>
                <input type="text" class="form-control" name="telefono" placeholder="Ingresa tu teléfono" required>
            </div>
            <div class="mb-3">
                <label for="password" class="form-label">Contraseña</label>
                <input type="password" class="form-control" name="password" placeholder="Ingrese su contraseña" required>
            </div>
            <button type="submit" class="btn btn-primary w-100">Entrar</button>
        </form>
    </div>
</div>

<script src="/fidelizacion/assets/js/notificaciones.js"></script>
<script>
document.addEventListener("DOMContentLoaded", () => {
  showNotification();
});
</script>


<script>
  if ("serviceWorker" in navigator) {
    navigator.serviceWorker.register("sw.js")
      .then((reg) => console.log("✅ Service Worker registrado:", reg))
      .catch((err) => console.error("❌ Error al registrar SW:", err));
  }
</script>
</body>
</html>
