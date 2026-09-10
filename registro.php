<?php
require 'conexion.php';   // Traemos la variable $conexion

$error   = '';
$exito   = '';
$hash    = '';   // Solo para mostrar en pantalla cómo queda la contraseña

// ¿El usuario envió el formulario? (method="post")
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // trim() quita los espacios de sobra al inicio y al final
    $nombre   = trim($_POST['nombre']   ?? '');
    $correo   = trim($_POST['correo']   ?? '');
    $password = $_POST['password']      ?? '';

    // ---------- Validaciones simples ----------
    if ($nombre === '' || $correo === '' || $password === '') {
        $error = 'Todos los campos son obligatorios.';

    } elseif (!filter_var($correo, FILTER_VALIDATE_EMAIL)) {
        $error = 'El correo no tiene un formato válido.';

    } elseif (strlen($password) < 6) {
        $error = 'La contraseña debe tener al menos 6 caracteres.';

    } else {

        /* ====================================================
           PASO 2 - ENCRIPTAR LA CONTRASEÑA
           ====================================================
           password_hash() convierte "123456" en algo como:
           $2y$10$Xy8...  (60 caracteres)

           Es de UNA SOLA VÍA: no existe forma de devolverlo
           a texto plano. Por eso NUNCA se guarda la contraseña
           tal cual la escribió el usuario.

           Además agrega automáticamente una "sal" aleatoria,
           así dos personas con la misma contraseña tendrán
           hashes diferentes.
           ==================================================== */
        $hash = password_hash($password, PASSWORD_DEFAULT);

        /* ====================================================
           PASO 3 - GUARDAR CON CONSULTA PREPARADA
           ====================================================
           Los signos ? son "marcadores". Los valores viajan
           aparte del SQL, así nadie puede inyectar código.
           ==================================================== */
        $sql = "INSERT INTO usuarios_basico (nombre, correo, password) VALUES (?, ?, ?)";

        try {
            $sentencia = $conexion->prepare($sql);
            $sentencia->execute([$nombre, $correo, $hash]);   // <-- guardamos el HASH, no la clave

            $exito = 'Usuario registrado correctamente. Ya puedes iniciar sesión.';

        } catch (PDOException $e) {
            // El código 23000 es "clave duplicada" (el correo es UNIQUE)
            if ($e->getCode() === '23000') {
                $error = 'Ese correo ya está registrado.';
            } else {
                $error = 'Error al registrar: ' . $e->getMessage();
            }
            $hash = '';
        }
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro</title>
    <link rel="stylesheet" href="estilos.css">
</head>
<body>

    <div class="caja">
        <h1>Crear cuenta</h1>
        <p class="subtitulo">La contraseña se guarda encriptada</p>

        <?php if ($error !== ''): ?>
            <!-- htmlspecialchars evita que se ejecute HTML dentro del mensaje -->
            <p class="error"><?= htmlspecialchars($error) ?></p>
        <?php endif; ?>

        <?php if ($exito !== ''): ?>
            <p class="exito"><?= htmlspecialchars($exito) ?></p>
            <p><strong>Esto fue lo que se guardó en la base de datos:</strong></p>
            <div class="hash"><?= htmlspecialchars($hash) ?></div>
        <?php endif; ?>

        <form method="post" action="registro.php">
            <label for="nombre">Nombre</label>
            <input type="text" id="nombre" name="nombre" required>

            <label for="correo">Correo</label>
            <input type="email" id="correo" name="correo" required>

            <label for="password">Contraseña</label>
            <input type="password" id="password" name="password" required>

            <button type="submit">Registrarme</button>
        </form>

        <a class="enlace" href="login.php">Ya tengo cuenta, iniciar sesión</a>
    </div>

</body>
</html>
