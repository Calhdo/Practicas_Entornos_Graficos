<?php
session_start();


$mensaje_estado = "";

// Procesar el formulario
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
        $mensaje_estado = "Error de seguridad: Token CSRF inválido.";
    } else {
        $nombre = trim($_POST['nombre'] ?? '');
        $correo = trim($_POST['correo'] ?? '');
        $asunto = trim($_POST['asunto'] ?? '');
        $mensaje = trim($_POST['mensaje'] ?? '');

        if (empty($nombre) || empty($correo) || empty($asunto) || empty($mensaje)) {
            $mensaje_estado = "Por favor, completa todos los campos.";
        } elseif (!filter_var($correo, FILTER_VALIDATE_EMAIL)) {
            $mensaje_estado = "El correo electrónico no es válido.";
        } else {
            $destinatario = "webmaster@tudominio.com";
            $asunto_correo = "Contacto: " . htmlspecialchars($asunto);

            $cuerpo_html = '
            <p><strong>Nombre:</strong> ' . htmlspecialchars($nombre) . '</p>
            <p><strong>Correo:</strong> ' . htmlspecialchars($correo) . '</p>
            <p><strong>Asunto:</strong> ' . htmlspecialchars($asunto) . '</p>
            <p><strong>Mensaje:</strong><br>' . nl2br(htmlspecialchars($mensaje)) . '</p>
            ';

            $remitente_seguro = filter_var($correo, FILTER_SANITIZE_EMAIL);
            $headers  = "MIME-Version: 1.0" . "\r\n";
            $headers .= "Content-type: text/html; charset=UTF-8" . "\r\n";
            $headers .= "From: {$nombre} <{$remitente_seguro}>" . "\r\n";
            $headers .= "Reply-To: {$remitente_seguro}" . "\r\n";

            if (mail($destinatario, $asunto_correo, $cuerpo_html, $headers)) {
                $_SESSION['flash_msg'] = "Mensaje enviado con éxito.";
            } else {
                $_SESSION['flash_msg'] = "Hubo un error al enviar el mensaje.";
            }

            header("Location: " . $_SERVER['PHP_SELF']);
            exit;
        }
    }
}

// Recuperar mensaje flash de la sesión
if (isset($_SESSION['flash_msg'])) {
    $mensaje_estado = $_SESSION['flash_msg'];
    unset($_SESSION['flash_msg']);
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Contacto</title>
</head>
<body>

    <h1>Formulario de Contacto</h1>

    <?php if (!empty($mensaje_estado)): ?>
        <p><strong><?php echo htmlspecialchars($mensaje_estado); ?></strong></p>
    <?php endif; ?>

    <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="POST">
        <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">

        <p>
            <label for="nombre">Nombre:</label><br>
            <input type="text" id="nombre" name="nombre" required>
        </p>

        <p>
            <label for="correo">Correo:</label><br>
            <input type="email" id="correo" name="correo" required>
        </p>

        <p>
            <label for="asunto">Asunto:</label><br>
            <input type="text" id="asunto" name="asunto" required>
        </p>

        <p>
            <label for="mensaje">Mensaje:</label><br>
            <textarea id="mensaje" name="mensaje" rows="5" required></textarea>
        </p>

        <p>
            <button type="submit">Enviar</button>
        </p>
    </form>

</body>
</html>