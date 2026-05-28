<?php
// includes/conexion.php

$host = 'bzunb4gksjklhdafupl2-mysql.services.clever-cloud.com';
$db   = 'bzunb4gksjklhdafupl2'; // Nombre asignado por Clever Cloud
$user = 'u4hif5lrq5fuadiq';
$pass = 'TU_CONTRASEÑA_SECRETA_AQUÍ'; // Pega aquí la contraseña de Clever Cloud
$port = '3306';
$charset = 'utf8mb4';

$dsn = "mysql:host=$host;dbname=$db;port=$port;charset=$charset";
$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES   => false,
];

try {
    $conn = new PDO($dsn, $user, $pass, $options);
} catch (\PDOException $e) {
    // En producción es mejor no mostrar detalles internos del servidor
    die("Error de conexión a la base de datos.");
}
