<?php
session_start();
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Ejercicio 5 - Recuperar Sesión</title>
</head>
<body>
    <h1>Página 3: Datos de Sesión Recuperados</h1>
    <?php if (isset($_SESSION['usuario'])): ?>
        <p>Usuario recuperado: <strong><?php echo htmlspecialchars($_SESSION['usuario']); ?></strong></p>
        <p>Nivel de acceso: <strong><?php echo htmlspecialchars($_SESSION['nivel']); ?></strong></p>
    <?php else: ?>
        <p>No hay datos de sesión activos. Por favor, <a href="pagina1.php">inicia sesión</a>.</p>
    <?php endif; ?>
</body>
</html>