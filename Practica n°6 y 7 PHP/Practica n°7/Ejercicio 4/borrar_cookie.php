<?php

if (isset($_COOKIE['tipo_titular'])) {
    setcookie('tipo_titular', '', time() - 3600, "/");
}
header("Location: periodico.php");
exit;
?>