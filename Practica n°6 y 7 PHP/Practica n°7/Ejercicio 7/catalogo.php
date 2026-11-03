<?php
session_start();

$conexion = mysqli_connect("localhost", "root", "", "Compras");
if (!$conexion) {
    die("Error de conexión: " . mysqli_connect_error());
}
mysqli_set_charset($conexion, "utf8");

// Agregar producto al carrito (sesión)
if (isset($_GET['comprar'])) {
    $id_producto = intval($_GET['comprar']);
    $_SESSION['carrito'][$id_producto] = ($_SESSION['carrito'][$id_producto] ?? 0) + 1;
    header("Location: catalogo.php");
    exit;
}

// Vaciar carrito
if (isset($_GET['vaciar'])) {
    unset($_SESSION['carrito']);
    header("Location: catalogo.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Ejercicio 7 - Carrito de Compras</title>
</head>
<body>
    <h1>Catálogo de Productos</h1>
    <table border="1">
        <tr>
            <th>ID</th>
            <th>Producto</th>
            <th>Precio</th>
            <th>Acción</th>
        </tr>
        <?php
        $resultado = mysqli_query($conexion, "SELECT id, producto, precio FROM catalogo");
        while ($fila = mysqli_fetch_assoc($resultado)):
        ?>
        <tr>
            <td><?php echo $fila['id']; ?></td>
            <td><?php echo htmlspecialchars($fila['producto']); ?></td>
            <td>$<?php echo number_format($fila['precio'], 2); ?></td>
            <td><a href="?comprar=<?php echo $fila['id']; ?>">Agregar al carrito</a></td>
        </tr>
        <?php endwhile; ?>
    </table>

    <h2>Tu Carrito de Compras</h2>
    <?php if (!empty($_SESSION['carrito'])): ?>
        <table border="1">
            <tr>
                <th>Producto</th>
                <th>Precio Unitario</th>
                <th>Cantidad</th>
                <th>Subtotal</th>
            </tr>
            <?php
            $total_general = 0;
            foreach ($_SESSION['carrito'] as $id => $cantidad):
                $stmt = mysqli_prepare($conexion, "SELECT producto, precio FROM catalogo WHERE id = ?");
                mysqli_stmt_bind_param($stmt, "i", $id);
                mysqli_stmt_execute($stmt);
                $res_item = mysqli_stmt_get_result($stmt);
                if ($item = mysqli_fetch_assoc($res_item)):
                    $subtotal = $item['precio'] * $cantidad;
                    $total_general += $subtotal;
            ?>
            <tr>
                <td><?php echo htmlspecialchars($item['producto']); ?></td>
                <td>$<?php echo number_format($item['precio'], 2); ?></td>
                <td><?php echo $cantidad; ?></td>
                <td>$<?php echo number_format($subtotal, 2); ?></td>
            </tr>
            <?php 
                endif;
                mysqli_stmt_close($stmt);
            endforeach; 
            ?>
        </table>
        <p><strong>Total General: $<?php echo number_format($total_general, 2); ?></strong></p>
        <p><a href="?vaciar=1">Vaciar Carrito</a></p>
    <?php else: ?>
        <p>El carrito está vacío.</p>
    <?php endif; ?>

</body>
</html>
<?php mysqli_close($conexion); ?>