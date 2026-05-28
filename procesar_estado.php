<?php
include 'conexion.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['id'];
    $nuevo_estado = $_POST['estado'];
    $stmt = $conexion->prepare("UPDATE reportes SET estado = ? WHERE id_reporte = ?");
    $stmt->bind_param("si", $nuevo_estado, $id);
    
    if ($stmt->execute()) {
        echo "Éxito";
    } else {
        echo "Error: " . $conexion->error;
    }
    $stmt->close();
}
?>