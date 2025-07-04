<?php
header("Content-Type: application/json");
include '../config/db.php';

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    http_response_code(405);
    echo json_encode(["error" => "Método no permitido"]);
    exit;
}

// Recibe los datos JSON
$data = json_decode(file_get_contents("php://input"), true);

$telefono = $data["telefono"] ?? null;
$premio_id = $data["premio_id"] ?? null;

if (!$telefono || !$premio_id) {
    echo json_encode(["error" => "Faltan datos: 'telefono' o 'premio_id'"]);
    exit;
}

// Buscar cliente
$stmt = $conn->prepare("SELECT puntos FROM clientes WHERE telefono = ?");
$stmt->bind_param("s", $telefono);
$stmt->execute();
$resCliente = $stmt->get_result();
$cliente = $resCliente->fetch_assoc();

if (!$cliente) {
    echo json_encode(["error" => "Cliente no encontrado"]);
    exit;
}

// Buscar premio
$stmt = $conn->prepare("SELECT puntos_requeridos FROM premios WHERE id = ?");
$stmt->bind_param("i", $premio_id);
$stmt->execute();
$resPremio = $stmt->get_result();
$premio = $resPremio->fetch_assoc();

if (!$premio) {
    echo json_encode(["error" => "Premio no encontrado"]);
    exit;
}

// Validar puntos
if ($cliente["puntos"] < $premio["puntos_requeridos"]) {
    echo json_encode(["error" => "Puntos insuficientes"]);
    exit;
}

// Registrar canje
$stmt = $conn->prepare("INSERT INTO canjes (telefono, premio_id) VALUES (?, ?)");
$stmt->bind_param("si", $telefono, $premio_id);
$stmt->execute();

// Descontar puntos
$nuevos_puntos = $cliente["puntos"] - $premio["puntos_requeridos"];
$stmt = $conn->prepare("UPDATE clientes SET puntos = ? WHERE telefono = ?");
$stmt->bind_param("is", $nuevos_puntos, $telefono);
$stmt->execute();

// Respuesta de éxito
echo json_encode([
    "success" => true,
    "mensaje" => "Premio canjeado correctamente",
    "nuevo_puntaje" => $nuevos_puntos
]);
