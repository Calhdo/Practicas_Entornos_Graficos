<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Almacenamos datos en variables de sesión
    $_SESSION['usuario'] = trim($_POST['usuario'] ?? 'Invitado');
    $_SESSION['nivel'] = 'Administrador';
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Ejercicio 5 - Sesión Iniciada</title>
</head>
<body>
    <h1>Página 2: Variables de sesión creadas</h1>
    <p>Los datos han sido almacenados correctamente en la sesión.</p>
    <p><a href="pagina3.php">Ir a la Página 3 para recuperar los datos</a></p>
</body>
</html>