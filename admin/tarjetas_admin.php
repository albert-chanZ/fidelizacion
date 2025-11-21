<?php
session_start();
// 1. Verificación de seguridad del administrador
if (!isset($_SESSION["tipo"]) || $_SESSION["tipo"] !== "admin") {
    header("Location: ../login.php");
    exit();
}
include '../config/db.php';

// 2. Lógica para registrar una nueva tarjeta
$mensaje = "";

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['registrar'])) {
    $telefono = $_POST["telefono"] ?? '';
    $numero_tarjeta = $_POST["numero_tarjeta"] ?? '';
    $banco = $_POST["banco"] ?? '';

    if (!empty($telefono) && !empty($numero_tarjeta) && !empty($banco)) {

        $stmt = $conn->prepare("INSERT INTO tarjetas (telefono, numero, banco) VALUES (?, ?, ?)");
        $stmt->bind_param("sss", $telefono, $numero_tarjeta, $banco);

        if ($stmt->execute()) {
            $mensaje = "<div class='alert alert-success text-center'>✅ Tarjeta registrada exitosamente.</div>";
        } else {
            $mensaje = "<div class='alert alert-danger text-center'>❌ Error al registrar la tarjeta: " . $stmt->error . "</div>";
        }
        $stmt->close();
    } else {
        $mensaje = "<div class='alert alert-warning text-center'>⚠ Completa todos los campos.</div>";
    }
}
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Gestión de Tarjetas - Administrador</title>

    <!-- IMPORTANTE PARA RESPONSIVIDAD -->
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">

    <style>
        .card {
            border-radius: 14px;
        }
        table td {
            vertical-align: middle !important;
        }
        .table-responsive {
            border-radius: 10px;
        }
    </style>
</head>

<body class="bg-light">

    <div class="container mt-4 mb-5">

        <h2 class="text-center mb-4">💳 Gestión de Tarjetas</h2>

        <?php echo $mensaje; ?>

        <!-- FORMULARIO RESPONSIVO -->
        <div class="card shadow-sm mb-4">
            <div class="card-header bg-primary text-white">
                <h5 class="m-0">Registrar Nueva Tarjeta</h5>
            </div>

            <div class="card-body">
                <form action="tarjetas_admin.php" method="POST" class="row g-3">

                    <div class="col-md-4 col-12">
                        <label class="form-label">Teléfono del Cliente</label>
                        <input type="text" name="telefono" class="form-control" required>
                    </div>

                    <div class="col-md-4 col-12">
                        <label class="form-label">Número de Tarjeta</label>
                        <input type="text" name="numero_tarjeta" class="form-control" required>
                    </div>

                    <div class="col-md-4 col-12">
                        <label class="form-label">Banco</label>
                        <input type="text" name="banco" class="form-control" required>
                    </div>

                    <div class="col-md-12 col-12 d-grid">
                        <button type="submit" name="registrar" class="btn btn-success">
                            <i class="bi bi-credit-card-fill"></i> Registrar
                        </button>
                    </div>

                </form>
            </div>
        </div>

        <!-- TABLA RESPONSIVA -->
        <h5 class="mt-4">Tarjetas Registradas y sus Clientes</h5>

        <div class="table-responsive shadow-sm">
            <table class="table table-striped table-bordered text-center">

                <thead class="table-primary">
                    <tr>
                        <th>ID</th>
                        <th>Cliente</th>
                        <th>Teléfono</th>
                        <th>Banco</th>
                        <th>Número</th>
                        <th>Acciones</th>
                    </tr>
                </thead>

                <tbody>
                    <?php
                    $sql = "SELECT 
                                t.*, 
                                c.nombre AS nombre_cliente
                            FROM tarjetas t
                            LEFT JOIN clientes c ON t.telefono = c.telefono
                            ORDER BY t.id DESC";

                    $tarjetas = $conn->query($sql);

                    if ($tarjetas->num_rows > 0) {
                        while ($t = $tarjetas->fetch_assoc()) {
                            $nombre_cliente = $t['nombre_cliente'] ?? "Cliente Desconocido";

                            echo "
                            <tr>
                                <td>{$t['id']}</td>
                                <td>" . htmlspecialchars($nombre_cliente) . "</td>
                                <td>" . htmlspecialchars($t['telefono']) . "</td>
                                <td>" . htmlspecialchars($t['banco']) . "</td>
                                <td>**** **** **** " . htmlspecialchars(substr($t['numero'], -4)) . "</td>
                                <td>
                                    <a href='eliminar_tarjeta.php?id={$t['id']}'
                                       class='btn btn-sm btn-danger d-block'>
                                       <i class='bi bi-trash'></i> Eliminar
                                    </a>
                                </td>
                            </tr>";
                        }
                    } else {
                        echo "<tr><td colspan='6'>No hay tarjetas registradas.</td></tr>";
                    }
                    ?>
                </tbody>

            </table>
        </div>

        <!-- BOTÓN DE RETORNO RESPONSIVO -->
        <a href="dashboard.php" class="btn btn-secondary w-100 mt-4">⬅ Volver al Panel</a>

    </div>

</body>

</html>
