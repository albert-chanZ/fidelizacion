<?php
header("Content-Type: application/json");
include '../config/db.php';

// Solo aceptar método POST
if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    http_response_code(405);
    echo json_encode(["error" => "Método no permitido"]);
    exit;
}

// Leer JSON del cuerpo de la solicitud
$input = json_decode(file_get_contents("php://input"), true);

// Validar datos
if (!isset($input["telefono"])) {
    http_response_code(400);
    echo json_encode(["error" => "Falta el parámetro 'telefono'"]);
    exit;
}

$telefono = $input["telefono"];

// Consultar historial
$sql = "
    SELECT c.fecha, p.nombre, p.descripcion, p.puntos_requeridos
    FROM canjes c
    INNER JOIN premios p ON c.premio_id = p.id
    WHERE c.telefono = ?
    ORDER BY c.fecha DESC
";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $telefono);
$stmt->execute();
$result = $stmt->get_result();

$historial = [];

while ($row = $result->fetch_assoc()) {
    $historial[] = [
        "fecha" => $row["fecha"],
        "premio" => $row["nombre"],
        "descripcion" => $row["descripcion"],
        "puntos" => (int)$row["puntos_requeridos"]
    ];
}

echo json_encode([
    "telefono" => $telefono,
    "total_canjes" => count($historial),
    "historial" => $historial
]);
