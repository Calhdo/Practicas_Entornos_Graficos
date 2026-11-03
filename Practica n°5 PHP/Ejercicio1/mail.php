<?php $destinatario = "xx@xx.com ";
$asunto = "Prueba";
$cuerpo = '
  <html>
  <head>
    <title>Envio de mail</title>
  </head>
  <body>
    <h1>Hola!</h1>
    <p>
    <b>Esto es una prueba</b>.
    xxxxxxxxxxxxxxxxxxxxxxxxxxx
    x
    </p>
  </body>
  </html>';
$headers = "MIME-Version: 1.0\r\n";
$headers .= "Content-type: text/html; charset=iso-8859-1\r\n";
$headers .= "From: nn <nn@xx.com>\r\n";
$headers .= "Reply-To: ss@xx.com" . "\r\n";

mail($destinatario, $asunto, $cuerpo, $headers);
?>