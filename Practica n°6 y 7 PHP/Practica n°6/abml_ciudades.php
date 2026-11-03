<?php
// Conexión a la base de datos Capitales
$conexion = mysqli_connect("localhost", "root", "", "Capitales");
if (!$conexion) {
    die("Error de conexión: " . mysqli_connect_error());
}
mysqli_set_charset($conexion, "utf8");

// Determinar la acción solicitada
$accion = $_GET['accion'] ?? 'listar';
$mensaje = "";

// 1. Procesar ALTA (Insertar)
if ($accion == 'guardar_alta' && $_SERVER['REQUEST_METHOD'] == 'POST') {
    $ciudad = trim($_POST['ciudad'] ?? '');
    $pais = trim($_POST['pais'] ?? '');
    $habitantes = intval($_POST['habitantes'] ?? 0);
    $superficies = floatval($_POST['superficies'] ?? 0);
    $tieneMetro = isset($_POST['tieneMetro']) ? 1 : 0;

    if (!empty($ciudad) && !empty($pais)) {
        $stmt = mysqli_prepare($conexion, "INSERT INTO ciudades (ciudad, pais, habitantes, superficies, tieneMetro) VALUES (?, ?, ?, ?, ?)");
        mysqli_stmt_bind_param($stmt, "ssidi", $ciudad, $pais, $habitantes, $superficies, $tieneMetro);
        if (mysqli_stmt_execute($stmt)) {
            $mensaje = "Ciudad agregada exitosamente.";
        } else {
            $mensaje = "Error al agregar la ciudad.";
        }
        mysqli_stmt_close($stmt);
    } else {
        $mensaje = "Los campos Ciudad y País son obligatorios.";
    }
    $accion = 'listar';
}

// 2. Procesar MODIFICACIÓN (Actualizar)
if ($accion == 'guardar_modificacion' && $_SERVER['REQUEST_METHOD'] == 'POST') {
    $id = intval($_POST['id'] ?? 0);
    $ciudad = trim($_POST['ciudad'] ?? '');
    $pais = trim($_POST['pais'] ?? '');
    $habitantes = intval($_POST['habitantes'] ?? 0);
    $superficies = floatval($_POST['superficies'] ?? 0);
    $tieneMetro = isset($_POST['tieneMetro']) ? 1 : 0;

    if ($id > 0 && !empty($ciudad) && !empty($pais)) {
        $stmt = mysqli_prepare($conexion, "UPDATE ciudades SET ciudad = ?, pais = ?, habitantes = ?, superficies = ?, tieneMetro = ? WHERE id = ?");
        mysqli_stmt_bind_param($stmt, "ssidii", $ciudad, $pais, $habitantes, $superficies, $tieneMetro, $id);
        if (mysqli_stmt_execute($stmt)) {
            $mensaje = "Ciudad actualizada exitosamente.";
        } else {
            $mensaje = "Error al actualizar la ciudad.";
        }
        mysqli_stmt_close($stmt);
    } else {
        $mensaje = "Datos inválidos para la modificación.";
    }
    $accion = 'listar';
}

// 3. Procesar BAJA (Eliminar)
if ($accion == 'baja' && isset($_GET['id'])) {
    $id = intval($_GET['id']);
    $stmt = mysqli_prepare($conexion, "DELETE FROM ciudades WHERE id = ?");
    mysqli_stmt_bind_param($stmt, "i", $id);
    if (mysqli_stmt_execute($stmt)) {
        $mensaje = "Ciudad eliminada exitosamente.";
    } else {
        $mensaje = "Error al eliminar la ciudad.";
    }
    mysqli_stmt_close($stmt);
    $accion = 'listar';
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>ABML de Ciudades</title>
</head>
<body>

    <h1>Gestión de Ciudades</h1>

    <!-- Menú principal -->
    <p>
        <a href="?accion=listar">Listar Ciudades</a> | 
        <a href="?accion=alta">Agregar Ciudad</a>
    </p>

    <hr>

    <?php if (!empty($mensaje)): ?>
        <p><strong><?php echo htmlspecialchars($mensaje); ?></strong></p>
    <?php endif; ?>

    <!-- VISTA: LISTADO -->
    <?php if ($accion == 'listar'): ?>
        <h2>Listado de Ciudades</h2>
        <?php
        $resultado = mysqli_query($conexion, "SELECT id, ciudad, pais, habitantes, superficies, tieneMetro FROM ciudades");
        if ($resultado && mysqli_num_rows($resultado) > 0) {
        ?>
            <table border="1">
                <tr>
                    <th>ID</th>
                    <th>Ciudad</th>
                    <th>País</th>
                    <th>Habitantes</th>
                    <th>Superficie (km²)</th>
                    <th>Tiene Metro</th>
                    <th>Acciones</th>
                </tr>
                <?php while ($fila = mysqli_fetch_assoc($resultado)): ?>
                <tr>
                    <td><?php echo $fila['id']; ?></td>
                    <td><?php echo htmlspecialchars($fila['ciudad']); ?></td>
                    <td><?php echo htmlspecialchars($fila['pais']); ?></td>
                    <td><?php echo number_format($fila['habitantes']); ?></td>
                    <td><?php echo $fila['superficies']; ?></td>
                    <td><?php echo $fila['tieneMetro'] ? 'Sí' : 'No'; ?></td>
                    <td>
                        <a href="?accion=modificar&id=<?php echo $fila['id']; ?>">Modificar</a> | 
                        <a href="?accion=baja&id=<?php echo $fila['id']; ?>" onclick="return confirm('¿Seguro que deseas eliminar esta ciudad?');">Eliminar</a>
                    </td>
                </tr>
                <?php endwhile; ?>
            </table>
        <?php
            mysqli_free_result($resultado);
        } else {
            echo "<p>No hay ciudades registradas en la base de datos.</p>";
        }
        ?>

    <!-- VISTA: FORMULARIO DE ALTA -->
    <?php elseif ($accion == 'alta'): ?>
        <h2>Agregar Nueva Ciudad</h2>
        <form action="?accion=guardar_alta" method="POST">
            <p>
                <label for="ciudad">Ciudad:</label><br>
                <input type="text" id="ciudad" name="ciudad" required>
            </p>
            <p>
                <label for="pais">País:</label><br>
                <input type="text" id="pais" name="pais" required>
            </p>
            <p>
                <label for="habitantes">Habitantes:</label><br>
                <input type="number" id="habitantes" name="habitantes" required>
            </p>
            <p>
                <label for="superficies">Superficie (km²):</label><br>
                <input type="number" step="0.01" id="superficies" name="superficies" required>
            </p>
            <p>
                <input type="checkbox" id="tieneMetro" name="tieneMetro" value="1">
                <label for="tieneMetro">¿Tiene Metro?</label>
            </p>
            <p>
                <button type="submit">Guardar</button>
                <a href="?accion=listar">Cancelar</a>
            </p>
        </form>

    <!-- VISTA: FORMULARIO DE MODIFICACIÓN -->
    <?php elseif ($accion == 'modificar' && isset($_GET['id'])): ?>
        <?php
        $id = intval($_GET['id']);
        $stmt = mysqli_prepare($conexion, "SELECT id, ciudad, pais, habitantes, superficies, tieneMetro FROM ciudades WHERE id = ?");
        mysqli_stmt_bind_param($stmt, "i", $id);
        mysqli_stmt_execute($stmt);
        $resultado = mysqli_stmt_get_result($stmt);
        $ciudad_data = mysqli_fetch_assoc($resultado);
        mysqli_stmt_close($stmt);

        if ($ciudad_data):
        ?>
            <h2>Modificar Ciudad</h2>
            <form action="?accion=guardar_modificacion" method="POST">
                <input type="hidden" name="id" value="<?php echo $ciudad_data['id']; ?>">
                <p>
                    <label for="ciudad">Ciudad:</label><br>
                    <input type="text" id="ciudad" name="ciudad" value="<?php echo htmlspecialchars($ciudad_data['ciudad']); ?>" required>
                </p>
                <p>
                    <label for="pais">País:</label><br>
                    <input type="text" id="pais" name="pais" value="<?php echo htmlspecialchars($ciudad_data['pais']); ?>" required>
                </p>
                <p>
                    <label for="habitantes">Habitantes:</label><br>
                    <input type="number" id="habitantes" name="habitantes" value="<?php echo $ciudad_data['habitantes']; ?>" required>
                </p>
                <p>
                    <label for="superficies">Superficie (km²):</label><br>
                    <input type="number" step="0.01" id="superficies" name="superficies" value="<?php echo $ciudad_data['superficies']; ?>" required>
                </p>
                <p>
                    <input type="checkbox" id="tieneMetro" name="tieneMetro" value="1" <?php echo $ciudad_data['tieneMetro'] ? 'checked' : ''; ?>>
                    <label for="tieneMetro">¿Tiene Metro?</label>
                </p>
                <p>
                    <button type="submit">Actualizar</button>
                    <a href="?accion=listar">Cancelar</a>
                </p>
            </form>
        <?php else: ?>
            <p>Ciudad no encontrada.</p>
            <p><a href="?accion=listar">Volver al listado</a></p>
        <?php endif; ?>

    <?php endif; ?>

</body>
</html>
<?php
mysqli_close($conexion);
?>