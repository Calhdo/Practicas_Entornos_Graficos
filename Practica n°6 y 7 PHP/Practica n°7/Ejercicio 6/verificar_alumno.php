<?php
session_start();

$conexion = mysqli_connect("localhost", "root", "", "base2");
if (!$conexion) {
    die("Error de conexión: " . mysqli_connect_error());
}
mysqli_set_charset($conexion, "utf8");

$mail = trim($_POST['mail'] ?? '');

if (!empty($mail)) {
    $stmt = mysqli_prepare($conexion, "SELECT nombre FROM alumnos WHERE mail = ?");
    mysqli_stmt_bind_param($stmt, "s", $mail);
    mysqli_stmt_execute($stmt);
    $resultado = mysqli_stmt_get_result($stmt);

    if ($fila = mysqli_fetch_assoc($resultado)) {
        // Rescatar nombre y almacenar en variable de sesión
        $_SESSION['nombre'] = $fila['nombre'];
        header("Location: perfil_alumno.php");
        exit;
    } else {
        $error = "El correo ingresado no se encuentra registrado.";
    }
    mysqli_stmt_close($stmt);
}
mysqli_close($conexion);
?>
<!DOCTYPE html>
<html lang="es">
<head><meta charset="UTF-8"><title>Error</title></head>
<body>
    <p style="color:red;"><?php echo $error ?? ''; ?></p>
    <p><a href="index_alumno.php">Volver al formulario</a></p>
</body>
</html>