<?php
session_start();
if (!isset($_SESSION["telefono"])) {
    header("Location: login.php");
    exit();
}

if ($_SESSION["tipo"] === "admin") {
    header("Location: admin/dashboard.php");
} else {
    header("Location: usuario/panel.php");
}
exit();
?>