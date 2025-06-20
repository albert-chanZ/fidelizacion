<?php
session_start();
include '../config/db.php';
if ($_SESSION["tipo"] !== "admin") header("Location: ../login.php");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nombre = $_POST["nombre"];
    $descripcion = $_POST["descripcion"];
    $puntos = $_POST["puntos"];

    // Procesar imagen
    $img_name = "";
    if (isset($_FILES["imagen"]) && $_FILES["imagen"]["error"] === 0) {
        $img_name = basename($_FILES["imagen"]["name"]);
        move_uploaded_file($_FILES["imagen"]["tmp_name"], "../assets/img/" . $img_name);
    }

    $stmt = $conn->prepare("INSERT INTO premios (nombre, descripcion, puntos_requeridos, imagen) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("ssis", $nombre, $descripcion, $puntos, $img_name);
    $stmt->execute();
}

$premios = $conn->query("SELECT * FROM premios");
?>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<body class="container mt-4">
<form method="POST" enctype="multipart/form-data" class="mb-3">
  <input name="nombre" placeholder="Nombre del premio" class="form-control mb-2">
  <textarea name="descripcion" placeholder="Descripción" class="form-control mb-2"></textarea>
  <input name="puntos" type="number" placeholder="Puntos requeridos" class="form-control mb-2">
  <input type="file" name="imagen" class="form-control mb-2" accept="image/*">
  <button class="btn btn-primary">Guardar Premio</button>
</form>
</body>