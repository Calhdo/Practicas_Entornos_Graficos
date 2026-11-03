<?php

session_start();


if (!isset($_SESSION['contador_paginas'])) {
    $_SESSION['contador_paginas'] = 0;
}

$_SESSION['contador_paginas']++;
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Contador de Sesión</title>
</head>
<body>

    <h1>Navegación en la Sesión</h1>
    
    <p>Has visitado <strong><?php echo $_SESSION['contador_paginas']; ?></strong> páginas durante esta sesión.</p>

    <p>
        <!-- Enlaces de prueba para navegar entre páginas que compartan el script -->
        <a href="index.php">Recargar página actual</a> | 
        <a href="cerrar_sesion.php">Reiniciar contador (Cerrar sesión)</a>
    </p>

</body>
</html>