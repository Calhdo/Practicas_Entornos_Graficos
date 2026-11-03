<?php
$conexion = mysqli_connect("localhost", "root", "", "prueba");
if (!$conexion) {
    die("Error de conexión: " . mysqli_connect_error());
}
mysqli_set_charset($conexion, "utf8");

$busqueda = trim($_GET['termino'] ?? '');
$resultados = null;

if (!empty($busqueda)) {
    $stmt = mysqli_prepare($conexion, "SELECT canciones FROM buscador WHERE canciones LIKE ?");
    $param_like = "%" . $busqueda . "%";
    mysqli_stmt_bind_param($stmt, "s", $param_like);
    mysqli_stmt_execute($stmt);
    $resultados = mysqli_stmt_get_result($stmt);
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Ejercicio 8 - Buscador de Canciones</title>
</head>
<body>
    <h1>Buscador de Canciones</h1>

    <form action="" method="GET">
        <p>
            <label for="termino">Buscar canción:</label><br>
            <input type="text" id="termino" name="termino" value="<?php echo htmlspecialchars($busqueda); ?>" required>
            <button type="submit">Buscar</button>
        </p>
    </form>

    <?php if ($resultados !== null): ?>
        <h2>Resultados de la búsqueda:</h2>
        <?php if (mysqli_num_rows($resultados) > 0): ?>
            <ul>
                <?php while ($fila = mysqli_fetch_assoc($resultados)): ?>
                    <li><?php echo htmlspecialchars($fila['canciones']); ?></li>
                <?php endwhile; ?>
            </ul>
        <?php else: ?>
            <p>No se encontraron canciones que coincidan con la búsqueda.</p>
        <?php endif; ?>
    <?php endif; ?>

</body>
</html>
<?php 
if (isset($stmt)) mysqli_stmt_close($stmt);
mysqli_close($conexion);
?>