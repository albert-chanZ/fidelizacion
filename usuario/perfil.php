<?php
session_start();
include '../config/db.php';

if (!isset($_SESSION["telefono"]) || $_SESSION["tipo"] !== "cliente") {
    header("Location: ../login.php");
    exit;
}

$telefono = $_SESSION["telefono"];
$cliente = $conn->query("SELECT * FROM clientes WHERE telefono = '$telefono'")->fetch_assoc();
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Perfil del Usuario</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        body {
            background-color: #f4f6f9;
        }
        .profile-card {
            max-width: 500px;
            margin: 40px auto;
            border-radius: 15px;
            overflow: hidden;
            box-shadow: 0 0 15px rgba(0,0,0,0.1);
        }
        .profile-header {
            background: linear-gradient(135deg, #667eea, #764ba2);
            color: #fff;
            text-align: center;
            padding: 2rem;
        }
        .profile-header i {
            font-size: 4rem;
            margin-bottom: 10px;
        }
        .profile-body {
            background: #fff;
            padding: 2rem;
        }
    </style>
</head>
<body>
<div class="profile-card">
    <div class="profile-header">
        <i class="bi bi-person-circle"></i>
        <h4><?= htmlspecialchars($cliente['nombre'] ?? 'Usuario') ?></h4>
        <p class="mb-0"><?= htmlspecialchars($telefono) ?></p>
    </div>
    <div class="profile-body">
        <p><strong>Puntos actuales:</strong> <?= $cliente['puntos'] ?> pts</p>
        <hr>
        <div class="d-grid gap-2">
            <a href="canje_premios.php" class="btn btn-outline-primary"><i class="bi bi-gift"></i> Canjear Premios</a>
            <a href="historial.php" class="btn btn-outline-secondary"><i class="bi bi-clock-history"></i> Ver Historial</a>
            <a href="../logout.php" class="btn btn-danger"><i class="bi bi-box-arrow-right"></i> Cerrar Sesión</a>
        </div>
    </div>
</div>
</body>
</html>
