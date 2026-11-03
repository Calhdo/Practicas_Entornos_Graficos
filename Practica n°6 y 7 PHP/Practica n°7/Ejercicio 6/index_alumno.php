<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Ejercicio 6 - Ingreso de Alumno</title>
</head>
<body>
    <h1>Verificación de Alumno</h1>
    <form action="verificar_alumno.php" method="POST">
        <p>
            <label for="mail">Ingrese su correo electrónico:</label><br>
            <input type="email" id="mail" name="mail" required>
        </p>
        <button type="submit">Verificar</button>
    </form>
</body>
</html>