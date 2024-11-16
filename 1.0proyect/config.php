<?php
$dsn = 'mysql:host=localhost;dbname=tienda_en_linea;charset=utf8';
$username = 'root';
$password = '';

try {
    // Conexión PDO
    $pdo = new PDO($dsn, $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Conexión mysqli (si es necesario)
    $conn = new mysqli('localhost', $username, $password, 'tienda_en_linea');
    if ($conn->connect_error) {
        die('Conexión fallida: ' . $conn->connect_error);
    }
} catch (PDOException $e) {
    echo 'Conexión fallida: ' . $e->getMessage();
    exit();
}

?>

