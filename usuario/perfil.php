<?php
session_start();
include '../config/db.php';

if (!isset($_SESSION["telefono"]) || $_SESSION["tipo"] !== "cliente") {
    header("Location: ../login.php");
    exit;
}

$telefono = $_SESSION["telefono"];
$cliente = $conn->query("SELECT * FROM clientes WHERE telefono = '$telefono'")->fetch_assoc();

// Imagen por defecto
$fotoPerfil = !empty($cliente['foto'])
    ? "../uploads/" . $cliente['foto']
    : "https://cdn-icons-png.flaticon.com/512/149/149071.png";

?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Perfil del Usuario</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">

    <style>
        body {
            background-color: #f4f6f9;
        }

        .profile-card {
            max-width: 550px;
            margin: 20px auto;
            border-radius: 15px;
            overflow: hidden;
            box-shadow: 0 0 15px rgba(0,0,0,0.08);
            background: #fff;
        }

        .profile-header {
            background: linear-gradient(135deg, #667eea, #764ba2);
            color: #fff;
            text-align: center;
            padding: 2rem;
        }

        .profile-header img {
            width: 120px;
            height: 120px;
            border-radius: 50%;
            object-fit: cover;
            border: 4px solid #fff;
        }

        @media (max-width: 576px) {
            .profile-header {
                padding: 1.5rem;
            }
            .profile-header img {
                width: 90px;
                height: 90px;
            }
            h4 {
                font-size: 1.2rem;
            }
            p {
                font-size: 0.9rem;
            }
            .profile-card {
                margin: 10px;
                border-radius: 10px;
            }
            .profile-body {
                padding: 1rem !important;
            }
            .btn {
                padding: 10px;
                font-size: 0.9rem;
            }
        }
    </style>
</head>

<body>

<div class="profile-card">

    <div class="profile-header">
        <img id="previewFoto" src="<?= $fotoPerfil ?>" alt="Foto">
        <h4 class="mt-3"><?= htmlspecialchars($cliente['nombre'] ?? 'Usuario') ?></h4>
        <p class="mb-0"><?= htmlspecialchars($telefono) ?></p>
    </div>

    <div class="profile-body p-4">

        <h5 class="text-center mb-3 fw-bold">Editar Información</h5>

        <!-- FORMULARIO PARA ACTUALIZAR PERFIL -->
        <form action="actualizar_perfil.php" method="POST" enctype="multipart/form-data" class="mb-4">

            <div class="mb-3">
                <label class="form-label fw-semibold">Nombre:</label>
                <input type="text" name="nombre" class="form-control"
                       value="<?= htmlspecialchars($cliente['nombre']) ?>" required>
            </div>

            <div class="mb-3">
                <label class="form-label fw-semibold">Teléfono (solo lectura):</label>
                <input type="text" class="form-control" value="<?= $telefono ?>" readonly>
            </div>

            <div class="mb-3">
                <label class="form-label fw-semibold">Foto de Perfil:</label>
                <input type="file" name="foto" accept="image/*" class="form-control" 
                       onchange="mostrarVistaPrevia(event)">
            </div>

            <button type="submit" class="btn btn-primary w-100">
                <i class="bi bi-save"></i> Guardar Cambios
            </button>

        </form>
    </div>
    <a href="panel.php" class="btn btn-secondary mt-4 w-100 w-md-auto">Volver</a>
</div>

<script>
function mostrarVistaPrevia(event) {
    const foto = event.target.files[0];
    if (!foto) return;

    const img = document.getElementById("previewFoto");
    img.src = URL.createObjectURL(foto);
}
</script>

</body>
</html>
