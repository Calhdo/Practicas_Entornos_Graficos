<?php
// Si se envió el formulario, guardamos la preferencia en una cookie por 30 días
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['estilo'])) {
    $estilo = $_POST['estilo'];
    setcookie('estilo_preferido', $estilo, time() + (86400 * 30), "/");
    // Recargamos para aplicar el cambio inmediatamente
    header("Location: " . $_SERVER['PHP_SELF']);
    exit;
}

// Determinamos qué estilo usar (cookie o por defecto)
$estilo_actual = $_COOKIE['estilo_preferido'] ?? 'claro';
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Ejercicio 1 - Estilos CSS</title>
    <?php if ($estilo_actual == 'oscuro'): ?>
        <style>
            body { background-color: #222; color: #fff; font-family: Arial, sans-serif; padding: 20px; }
            fieldset { border: 1px solid #555; }
        </style>
    <?php elseif ($estilo_actual == 'pastel'): ?>
        <style>
            body { background-color: #e3f2fd; color: #0d47a1; font-family: Georgia, serif; padding: 20px; }
            fieldset { border: 1px solid #90caf9; }
        </style>
    <?php else: ?>
        <style>
            body { background-color: #fff; color: #333; font-family: Arial, sans-serif; padding: 20px; }
            fieldset { border: 1px solid #ccc; }
        </style>
    <?php endif; ?>
</head>
<body>
    <h1>Personalizador de Aspecto</h1>
    <p>Estilo actual seleccionado: <strong><?php echo htmlspecialchars($estilo_actual); ?></strong></p>

    <form action="" method="POST">
        <fieldset>
            <legend>Elige tu estilo:</legend>
            <p>
                <label><input type="radio" name="estilo" value="claro" <?php echo ($estilo_actual == 'claro') ? 'checked' : ''; ?>> Claro</label><br>
                <label><input type="radio" name="estilo" value="oscuro" <?php echo ($estilo_actual == 'oscuro') ? 'checked' : ''; ?>> Oscuro</label><br>
                <label><input type="radio" name="estilo" value="pastel" <?php echo ($estilo_actual == 'pastel') ? 'checked' : ''; ?>> Pastel</label>
            </p>
            <button type="submit">Guardar Estilo</button>
        </fieldset>
    </form>
</body>
</html>