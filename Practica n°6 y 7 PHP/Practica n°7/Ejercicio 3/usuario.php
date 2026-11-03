<?php
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['nombre_usuario'])) {
    $nombre = trim($_POST['nombre_usuario']);
    if (!empty($nombre)) {
        setcookie('ultimo_usuario', $nombre, time() + (86400 * 30), "/");
        // Recargar para limpiar POST y mostrar actualizado
        header("Location: " . $_SERVER['PHP_SELF']);
        exit;
    }
}

$ultimo_usuario = $_COOKIE['ultimo_usuario'] ?? '';
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Ejercicio 3 - Usuario Reciente</title>
</head>
<body>
    <h1>Registro de Usuario</h1>

    <?php if (!empty($ultimo_usuario)): ?>
        <p>Último usuario registrado en este navegador: <strong><?php echo htmlspecialchars($ultimo_usuario); ?></strong></p>
    <?php endif; ?>

    <form action="" method="POST">
        <p>
            <label for="nombre_usuario">Nombre de usuario:</label><br>
            <input type="text" id="nombre_usuario" name="nombre_usuario" required>
        </p>
        <button type="submit">Crear Cookie</button>
    </form>
</body>
</html>