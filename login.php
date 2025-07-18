<?php
session_start();
include 'config/db.php'; // Asegúrate de que este archivo conecta a tu base de datos

$error = ""; // Inicializamos la variable de error

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $telefono = $_POST["telefono"] ?? '';
    $password = $_POST["password"] ?? '';

    // Lógica para el login tradicional (teléfono y contraseña)
    if (!empty($telefono) && !empty($password)) {
        $stmt = $conn->prepare("SELECT * FROM clientes WHERE telefono = ?");
        $stmt->bind_param("s", $telefono);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($user = $result->fetch_assoc()) {
            if (password_verify($password, $user["password"])) {
                // Credenciales correctas. Marcar la sesión para 2FA y redirigir.
                $_SESSION["2fa_pending_telefono"] = $user["telefono"]; // Guardamos el teléfono para la verificación de voz
                $_SESSION["2fa_pending_id"] = $user["id"]; // O el ID del cliente si lo necesitas
                
                // Si el usuario no tiene voice_print_id, podrías redirigirlo a una página para registrar su voz,
                // o mostrar un mensaje para que use solo usuario/contraseña si no quieres forzar 2FA para todos.
                // Por ahora, asumimos que si llega aquí, debe pasar 2FA.

                header("Location: verify_voice.php"); // Redirigir a la página de verificación por voz
                exit();
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
    <title>Login - Fidelización</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
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
</body>
</html>