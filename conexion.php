<?php
/* ============================================================
   PASO 1 - LA CONEXIÓN A LA BASE DE DATOS
   ============================================================
   Usamos PDO (PHP Data Objects). Es la forma recomendada hoy
   porque permite "consultas preparadas" y evita inyección SQL.
   ============================================================ */

$servidor  = 'localhost';       // Donde vive MySQL (en XAMPP: tu propio PC)
$basedatos = 'loginapp';        // Nombre de la base de datos
$usuario   = 'root';            // Usuario por defecto de XAMPP
$clave     = '';                // Contraseña por defecto de XAMPP: vacía

try {
    // El DSN es la "dirección" de la base de datos.
    // charset=utf8mb4 -> para que acepte tildes y ñ.
    $dsn = "mysql:host=$servidor;dbname=$basedatos;charset=utf8mb4";

    $conexion = new PDO($dsn, $usuario, $clave);

    // Si algo sale mal, que lance un error claro en vez de fallar en silencio.
    $conexion->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

} catch (PDOException $e) {
    // Si no conecta, detenemos todo y mostramos el motivo.
    die('Error de conexión: ' . $e->getMessage());
}
