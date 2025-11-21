<?php
session_start();
include '../config/db.php';

if (!isset($_SESSION["telefono"]) || $_SESSION["tipo"] !== "cliente") {
    header("Location: ../login.php");
    exit;
}

$telefono = $_SESSION["telefono"];
$cliente = $conn->query("SELECT * FROM clientes WHERE telefono='$telefono'")->fetch_assoc();

// =====================================================
// 1. Obtener datos del formulario
// =====================================================
$nombre = $_POST["nombre"];

// =====================================================
// 2. Procesar la foto (si se envió)
// =====================================================
$fotoNombreFinal = $cliente["foto"]; // por si no cambia

if (!empty($_FILES["foto"]["name"])) {

    $foto = $_FILES["foto"];
    $tmp = $foto["tmp_name"];
    $nombreArchivo = time() . "_" . basename($foto["name"]);
    $rutaDestino = "../uploads/" . $nombreArchivo;

    // Validar tipo de archivo
    $extensionesPermitidas = ["jpg","jpeg","png","webp"];
    $extension = strtolower(pathinfo($nombreArchivo, PATHINFO_EXTENSION));

    if (!in_array($extension, $extensionesPermitidas)) {
        echo "<script>alert('❌ Solo se permiten imágenes JPG, PNG o WEBP'); window.history.back();</script>";
        exit;
    }

    // Subir archivo
    if (move_uploaded_file($tmp, $rutaDestino)) {

        // Si había una foto anterior, borrarla
        if (!empty($cliente["foto"]) && file_exists("../uploads/" . $cliente["foto"])) {
            unlink("../uploads/" . $cliente["foto"]);
        }

        // Guardar el nombre de la nueva imagen
        $fotoNombreFinal = $nombreArchivo;

    } else {
        echo "<script>alert('❌ Error al subir la imagen.'); window.history.back();</script>";
        exit;
    }
}

// =====================================================
// 3. Actualizar en la base de datos
// =====================================================
$stmt = $conn->prepare("UPDATE clientes SET nombre=?, foto=? WHERE telefono=?");
$stmt->bind_param("sss", $nombre, $fotoNombreFinal, $telefono);
$stmt->execute();

// =====================================================
// 4. Redirección
// =====================================================
echo "<script>
        alert('✅ Perfil actualizado correctamente');
        window.location='perfil.php';
      </script>";
exit;
