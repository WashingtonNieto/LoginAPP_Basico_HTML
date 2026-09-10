<?php
session_start();          // Necesario para recordar quién inició sesión
require 'conexion.php';

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $correo   = trim($_POST['correo'] ?? '');
    $password = $_POST['password']    ?? '';

    if ($correo === '' || $password === '') {
        $error = 'Escribe tu correo y tu contraseña.';

    } else {

        /* ====================================================
           PASO 4 - BUSCAR AL USUARIO POR SU CORREO
           ====================================================
           Ojo: NO buscamos por contraseña. Es imposible
           comparar el hash directamente en el SQL porque cada
           hash lleva una sal distinta.
           ==================================================== */
        $sentencia = $conexion->prepare("SELECT * FROM usuarios_basico WHERE correo = ?");
        $sentencia->execute([$correo]);

        $usuario = $sentencia->fetch(PDO::FETCH_ASSOC);   // Devuelve el registro o false

        /* ====================================================
           PASO 5 - VERIFICAR LA CONTRASEÑA
           ====================================================
           password_verify() vuelve a encriptar lo que escribió
           el usuario (usando la misma sal que hay dentro del
           hash guardado) y compara los dos resultados.
           Devuelve true o false.
           ==================================================== */
        if ($usuario && password_verify($password, $usuario['password'])) {

            // Credenciales correctas: guardamos los datos en la sesión
            $_SESSION['id']     = $usuario['id'];
            $_SESSION['nombre'] = $usuario['nombre'];

            header('Location: bienvenido.php');   // Redirigimos
            exit;

        } else {
            // Mensaje genérico a propósito: no revelamos si el
            // que falló fue el correo o la contraseña.
            $error = 'Correo o contraseña incorrectos.';
        }
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Iniciar sesión</title>
    <link rel="stylesheet" href="estilos.css">
</head>
<body>

    <div class="caja">
        <h1>Iniciar sesión</h1>
        <p class="subtitulo">Ingresa con tu correo y contraseña</p>

        <?php if ($error !== ''): ?>
            <p class="error"><?= htmlspecialchars($error) ?></p>
        <?php endif; ?>

        <form method="post" action="login.php">
            <label for="correo">Correo</label>
            <input type="email" id="correo" name="correo" required>

            <label for="password">Contraseña</label>
            <input type="password" id="password" name="password" required>

            <button type="submit">Entrar</button>
        </form>

        <a class="enlace" href="registro.php">No tengo cuenta, registrarme</a>
    </div>

</body>
</html>
