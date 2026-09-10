<?php
session_start();

session_unset();      // Borra las variables de la sesión
session_destroy();    // Cierra la sesión

header('Location: login.php');
exit;
