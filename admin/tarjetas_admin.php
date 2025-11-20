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
    // Usamos el operador de coalescencia nula para evitar warnings
    $telefono = $_POST["telefono"] ?? '';
    $numero_tarjeta = $_POST["numero_tarjeta"] ?? '';
    $banco = $_POST["banco"] ?? '';

    if (!empty($telefono) && !empty($numero_tarjeta) && !empty($banco)) {
        // En un entorno de producción, DEBES verificar que el cliente exista antes de registrar la tarjeta.
        
        // Usamos prepared statements para mayor seguridad
        $stmt = $conn->prepare("INSERT INTO tarjetas (telefono, numero, banco) VALUES (?, ?, ?)");
        $stmt->bind_param("sss", $telefono, $numero_tarjeta, $banco); // 'sss' para tres strings
        
        if ($stmt->execute()) {
            $mensaje = "<div class='alert alert-success'>✅ Tarjeta registrada exitosamente.</div>";
        } else {
            $mensaje = "<div class='alert alert-danger'>❌ Error al registrar la tarjeta: " . $stmt->error . "</div>";
        }
        $stmt->close();
    } else {
        $mensaje = "<div class='alert alert-warning'>Por favor, completa todos los campos para registrar la tarjeta.</div>";
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Gestión de Tarjetas - Administrador</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
</head>
<body class="bg-light">

<div class="container mt-4">
    <h2 class="text-center mb-4">💳 Gestión de Tarjetas</h2>

    <?php echo $mensaje; // Mostrar mensajes de registro/error ?>

    <div class="card shadow-sm mb-4">
        <div class="card-header bg-primary text-white">
            <h5>Registrar Nueva Tarjeta</h5>
        </div>
        <div class="card-body">
            <form action="tarjetas_admin.php" method="POST" class="row g-3">
                <div class="col-md-3">
                    <label for="telefono" class="form-label">Teléfono del Cliente</label>
                    <input type="text" name="telefono" id="telefono" class="form-control" required>
                </div>
                <div class="col-md-3">
                    <label for="numero_tarjeta" class="form-label">Número de Tarjeta</label>
                    <input type="text" name="numero_tarjeta" id="numero_tarjeta" class="form-control" required>
                </div>
                <div class="col-md-3">
                    <label for="banco" class="form-label">Banco</label>
                    <input type="text" name="banco" id="banco" class="form-control" required>
                </div>
                <div class="col-md-3 d-flex align-items-end">
                    <button type="submit" name="registrar" class="btn btn-success w-100"><i class="bi bi-credit-card-fill"></i> Registrar</button>
                </div>
            </form>
        </div>
    </div>

    <div class="mt-4">
        <h5>Tarjetas Registradas y sus Clientes</h5>
        <table class="table table-striped table-bordered text-center align-middle">
            <thead class="table-primary">
                <tr>
                    <th>ID Tarjeta</th>
                    <th>Cliente</th>
                    <th>Teléfono</th>
                    <th>Banco</th>
                    <th>Número</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php
                // 3. Consulta con JOIN para obtener el nombre del cliente
                $sql = "SELECT 
                            t.*, 
                            c.nombre AS nombre_cliente
                        FROM 
                            tarjetas t
                        LEFT JOIN 
                            clientes c ON t.telefono = c.telefono
                        ORDER BY 
                            t.id DESC";

                $tarjetas = $conn->query($sql);

                if ($tarjetas->num_rows > 0) {
                    while ($t = $tarjetas->fetch_assoc()) {
                        // Usamos un operador ternario para manejar el caso si el cliente no existe (resultado de LEFT JOIN)
                        $nombre_cliente = $t['nombre_cliente'] ?? "Cliente Desconocido"; 
                        
                        echo "<tr>
                            <td>{$t['id']}</td>
                            <td>" . htmlspecialchars($nombre_cliente) . "</td>
                            <td>" . htmlspecialchars($t['telefono']) . "</td>
                            <td>" . htmlspecialchars($t['banco']) . "</td>
                            <td>**** **** **** " . htmlspecialchars(substr($t['numero'], -4)) . "</td>
                            <td><a href='eliminar_tarjeta.php?id={$t['id']}' class='btn btn-sm btn-danger'><i class='bi bi-trash'></i> Eliminar</a></td>
                        </tr>";
                    }
                } else {
                    echo "<tr><td colspan='6'>No hay tarjetas registradas.</td></tr>";
                }
                ?>
            </tbody>
        </table>
    </div>

    <a href="dashboard.php" class="btn btn-secondary mt-3">⬅ Volver al Panel</a>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>