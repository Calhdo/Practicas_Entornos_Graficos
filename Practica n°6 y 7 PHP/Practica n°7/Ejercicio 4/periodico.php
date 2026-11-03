<?php
// Procesar selección de titular
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['titular'])) {
    $titular = $_POST['titular'];
    setcookie('tipo_titular', $titular, time() + (86400 * 30), "/");
    header("Location: " . $_SERVER['PHP_SELF']);
    exit;
}

$titular_elegido = $_COOKIE['tipo_titular'] ?? 'todos';
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Ejercicio 4 - Periódico</title>
</head>
<body>
    <h1>El Diario Digital</h1>
    <p><a href="borrar_cookie.php">Borrar configuración de titular</a></p>
    <hr>

    <form action="" method="POST">
        <fieldset>
            <legend>Configurar vista de noticias:</legend>
            <label><input type="radio" name="titular" value="politica" <?php echo ($titular_elegido == 'politica') ? 'checked' : ''; ?>> Noticia política</label><br>
            <label><input type="radio" name="titular" value="economica" <?php echo ($titular_elegido == 'economica') ? 'checked' : ''; ?>> Noticia económica</label><br>
            <label><input type="radio" name="titular" value="deportiva" <?php echo ($titular_elegido == 'deportiva') ? 'checked' : ''; ?>> Noticia deportiva</label><br>
            <label><input type="radio" name="titular" value="todos" <?php echo ($titular_elegido == 'todos') ? 'checked' : ''; ?>> Mostrar todos (Por defecto)</label><br><br>
            <button type="submit">Aplicar Configuración</button>
        </fieldset>
    </form>

    <hr>

    <?php if ($titular_elegido == 'todos' || $titular_elegido == 'politica'): ?>
        <section>
            <h2>Sección Política</h2>
            <p>El congreso debate nuevas leyes de presupuesto anual.</p>
        </section>
    <?php endif; ?>

    <?php if ($titular_elegido == 'todos' || $titular_elegido == 'economica'): ?>
        <section>
            <h2>Sección Económica</h2>
            <p>La cotización de las principales monedas extranjeras se mantiene estable.</p>
        </section>
    <?php endif; ?>

    <?php if ($titular_elegido == 'todos' || $titular_elegido == 'deportiva'): ?>
        <section>
            <h2>Sección Deportiva</h2>
            <p>Gran victoria del equipo local en el campeonato de la liga.</p>
        </section>
    <?php endif; ?>
</body>
</html>