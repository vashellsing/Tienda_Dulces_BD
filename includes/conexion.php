<?php
// Credenciales por defecto de Laragon
$host = 'localhost';
$dbname = 'tienda_dulces';
$username = 'root';
$password = '';

try {
    // Creamos la conexión PDO
    $conn = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);

    // Configuramos PDO para que nos avise si hay algún error
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Descomenta la siguiente línea solo para probar si funciona:
    // echo "¡Conexión exitosa a la base de datos!";

} catch (PDOException $e) {
    // Si algo falla, detenemos todo y mostramos el error
    die("Error de conexión: " . $e->getMessage());
}
