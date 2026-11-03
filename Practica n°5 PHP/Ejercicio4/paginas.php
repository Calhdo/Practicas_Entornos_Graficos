<?php
session_start();

$mensaje_estado = "";

// Procesar el formulario
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
        $mensaje_estado = "Error de seguridad: Token CSRF inválido.";
    } else {
        $nombre_remitente = trim($_POST['nombre_remitente'] ?? '');
        $correo_remitente = trim($_POST['correo_remitente'] ?? '');
        $nombre_amigo = trim($_POST['nombre_amigo'] ?? '');
        $correo_amigo = trim($_POST['correo_amigo'] ?? '');
        $mensaje_personal = trim($_POST['mensaje_personal'] ?? '');

        if (empty($nombre_remitente) || empty($correo_remitente) || empty($nombre_amigo) || empty($correo_amigo)) {
            $mensaje_estado = "Por favor, completa todos los campos obligatorios.";
        } elseif (!filter_var($correo_remitente, FILTER_VALIDATE_EMAIL) || !filter_var($correo_amigo, FILTER_VALIDATE_EMAIL)) {
            $mensaje_estado = "Uno de los correos electrónicos no es válido.";
        } else {
            $asunto = "¡Hola " . htmlspecialchars($nombre_amigo) . "! " . htmlspecialchars($nombre_remitente) . " te recomienda este sitio web";

            // Cuerpo del correo en formato HTML
            $cuerpo_html = '
            <p>Hola <strong>' . htmlspecialchars($nombre_amigo) . '</strong>,</p>
            <p>Tu amigo/a <strong>' . htmlspecialchars($nombre_remitente) . '</strong> (' . htmlspecialchars($correo_remitente) . ') ha pensado que te interesaría visitar nuestro sitio web.</p>
            ';

            if (!empty($mensaje_personal)) {
                $cuerpo_html .= '<p><strong>Mensaje personal:</strong><br>' . nl2br(htmlspecialchars($mensaje_personal)) . '</p>';
            }

            $cuerpo_html .= '<p>Puedes visitarnos haciendo clic en el siguiente enlace: <a href="https://www.tudominio.com">www.tudominio.com</a></p>';

            // Cabeceras y seguridad contra inyección
            $remitente_seguro = filter_var($correo_remitente, FILTER_SANITIZE_EMAIL);
            $cabeceras  = "MIME-Version: 1.0" . "\r\n";
            $cabeceras .= "Content-type: text/html; charset=UTF-8" . "\r\n";
            $cabeceras .= "From: Tu Sitio Web <noreply@tudominio.com>" . "\r\n";
            $cabeceras .= "Reply-To: {$remitente_seguro}" . "\r\n";

            // Enviar correo al amigo
            if (mail($correo_amigo, $asunto, $cuerpo_html, $cabeceras)) {
                $_SESSION['flash_msg'] = "¡Recomendación enviada con éxito a tu amigo!";
            } else {
                $_SESSION['flash_msg'] = "Hubo un error al enviar la recomendación.";
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
    <title>Recomendar a un Amigo</title>
</head>
<body>

    <h1>Recomendar sitio a un amigo</h1>

    <?php if (!empty($mensaje_estado)): ?>
        <p><strong><?php echo htmlspecialchars($mensaje_estado); ?></strong></p>
    <?php endif; ?>

    <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="POST">
        <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">

        <fieldset>
            <legend>Tus datos</legend>
            <p>
                <label for="nombre_remitente">Tu nombre:</label><br>
                <input type="text" id="nombre_remitente" name="nombre_remitente" required>
            </p>
            <p>
                <label for="correo_remitente">Tu correo electrónico:</label><br>
                <input type="email" id="correo_remitente" name="correo_remitente" required>
            </p>
        </fieldset>

        <fieldset>
            <legend>Datos de tu amigo</legend>
            <p>
                <label for="nombre_amigo">Nombre del amigo:</label><br>
                <input type="text" id="nombre_amigo" name="nombre_amigo" required>
            </p>
            <p>
                <label for="correo_amigo">Correo del amigo:</label><br>
                <input type="email" id="correo_amigo" name="correo_amigo" required>
            </p>
            <p>
                <label for="mensaje_personal">Mensaje personal (opcional):</label><br>
                <textarea id="mensaje_personal" name="mensaje_personal" rows="4"></textarea>
            </p>
        </fieldset>

        <p>
            <button type="submit">Enviar recomendación</button>
        </p>
    </form>

</body>
</html>