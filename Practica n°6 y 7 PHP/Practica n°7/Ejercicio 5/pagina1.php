<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Ejercicio 5 - Login</title>
</head>
<body>
    <h1>Página 1: Identificación</h1>
    <form action="pagina2.php" method="POST">
        <p>
            <label for="usuario">Usuario:</label><br>
            <input type="text" id="usuario" name="usuario" required>
        </p>
        <p>
            <label for="clave">Clave:</label><br>
            <input type="password" id="clave" name="clave" required>
        </p>
        <button type="submit">Ingresar</button>
    </form>
</body>
</html>