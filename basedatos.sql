-- ============================================================
--  Tabla para la demo de registro / login
--  Ejecutar en phpMyAdmin sobre la base de datos "loginapp"
-- ============================================================

USE loginapp;

CREATE TABLE IF NOT EXISTS usuarios_basico (
    id       INT AUTO_INCREMENT PRIMARY KEY,
    nombre   VARCHAR(100) NOT NULL,
    correo   VARCHAR(150) NOT NULL UNIQUE,  -- UNIQUE = no se repite el correo
    password VARCHAR(255) NOT NULL          -- 255 porque el hash es largo (~60 caracteres)
);
