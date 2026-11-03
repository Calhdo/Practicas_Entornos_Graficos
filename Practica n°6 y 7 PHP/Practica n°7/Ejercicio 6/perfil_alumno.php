<?php
session_start();
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Perfil del Alumno</title>
</head>
<body>
    <?php if (isset($_SESSION['nombre'])): ?>
        <h1>¡Bienvenido/a, <?php echo htmlspecialchars($_SESSION['nombre']); ?>!</h1>
        <p>Has ingresado correctamente a la zona protegida de alumnos.</p>
        <p><a href="cerrar_sesion_alumno.php">Cerrar Sesión</a></p>
    <?php else: ?>
        <h1>Acceso Denegado</h1>
        <p>No puedes visitar esta página. Debes registrarte previamente con tu correo.</p>
        <p><a href="index_alumno.php">Ir al inicio</a></p>
    <?php endif; ?>
</body>
</html>