<?php
session_start();

/* ============================================================
   PASO 6 - PROTEGER LA PÁGINA
   ============================================================
   Si no hay una sesión abierta, esta página no se debe ver.
   Lo mandamos al login y cortamos la ejecución con exit.
   ============================================================ */
if (!isset($_SESSION['id'])) {
    header('Location: login.php');
    exit;
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bienvenido</title>
    <link rel="stylesheet" href="estilos.css">
</head>
<body>

    <div class="caja">
        <h1>¡Hola, <?= htmlspecialchars($_SESSION['nombre']) ?>!</h1>
        <p class="subtitulo">Iniciaste sesión correctamente</p>

        <p class="exito">
            Tu contraseña nunca se comparó en texto plano:
            se verificó con <strong>password_verify()</strong>.
        </p>

        <a class="boton" href="salir.php">Cerrar sesión</a>
    </div>

</body>
</html>
