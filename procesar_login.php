<?php
session_start();
include 'conexion.php';

$usuario = trim($_POST['usuario']);
$password = $_POST['password'];

$stmt = $conexion->prepare("SELECT id, password FROM usuarios WHERE nombre = ?");
$stmt->bind_param("s", $usuario);
$stmt->execute();
$resultado = $stmt->get_result();

if ($fila = $resultado->fetch_assoc()) {
    if (password_verify($password, $fila['password'])) {
        $_SESSION['empresa_id'] = $fila['id'];
        header("Location: dashboard_empresa.php"); 
        exit(); 
    } else {
        echo "Contraseña incorrecta.";
    }
} else {
    echo "Usuario no encontrado.";
}
?>