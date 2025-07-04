<?php
header('Content-Type: application/json');
include '../config/db.php';

$result = $conn->query("SELECT id, nombre, descripcion, puntos_requeridos, imagen FROM premios");

$beneficios = [];
while ($row = $result->fetch_assoc()) {
    $beneficios[] = $row;
}

echo json_encode($beneficios);
