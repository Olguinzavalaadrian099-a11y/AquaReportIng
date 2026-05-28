<?php
include 'conexion.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $fecha = $_POST['fecha_reporte'];
    if (empty($fecha) || !preg_match("/^[0-9]{4}-[0-9]{2}-[0-9]{2}$/", $fecha)) {
        die("Error: Formato de fecha inválido. Asegúrate de seleccionar una fecha.");
    }

    if (!isset($_POST['nombre_completo']) || !isset($_FILES['foto_reporte'])) {
        die("Error: El formulario no envió los datos correctamente. Verifica el enctype='multipart/form-data'");
    }

    $nombre = trim($_POST['nombre_completo']);
    $edad = intval($_POST['edad']);
    $telefono = trim($_POST['telefono']);
    $email = trim($_POST['email']);
    $referencias = trim($_POST['referencias']);
    $lat = floatval($_POST['latitud']);
    $lng = floatval($_POST['longitud']);
    
    $ruta_foto = 'uploads/' . time() . '_' . basename($_FILES['foto_reporte']['name']);
    $ruta_pdf = 'uploads/' . time() . '_' . basename($_FILES['archivo_predial']['name']);

    if (move_uploaded_file($_FILES['foto_reporte']['tmp_name'], $ruta_foto) && 
        move_uploaded_file($_FILES['archivo_predial']['tmp_name'], $ruta_pdf)) {
        
        $sql = "INSERT INTO reportes (nombre_completo, edad, fecha_reporte, telefono, email, referencias, latitud, longitud, foto_ruta, pdf_ruta) 
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
        
        $stmt = $conexion->prepare($sql);
        $stmt->bind_param("sissssssss", $nombre, $edad, $fecha, $telefono, $email, $referencias, $lat, $lng, $ruta_foto, $ruta_pdf);
        if ($stmt->execute()) {
            $nuevo_id = $conexion->insert_id;
            header("Location: reporte.html?id=" . $nuevo_id);
            exit(); 
        } else {
            echo "Error SQL: " . $conexion->error;
        }
        $stmt->close();
    } else {
        echo "Error al subir archivos. Verifica que la carpeta 'uploads' exista y el tamaño de archivo.";
    }
}
?>