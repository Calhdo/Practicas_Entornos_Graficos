<?php
$visitas = 1;
$primera_vez = true;

if (isset($_COOKIE['contador'])) {
    $visitas = intval($_COOKIE['contador']) + 1;
    $primera_vez = false;
}

// Actualizar la cookie por 1 año
setcookie('contador', $visitas, time() + (86400 * 365), "/");
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Ejercicio 2 - Contador con Cookies</title>
</head>
<body>
    <h1>Contador de Accesos</h1>
    <?php if ($primera_vez): ?>
        <p>¡Bienvenido a nuestra página web! Es la primera vez que nos visitas.</p>
    <?php else: ?>
        <p>Hola de nuevo. Has visitado esta página <strong><?php echo $visitas; ?></strong> veces.</p>
    <?php endif; ?>
</body>
</html>