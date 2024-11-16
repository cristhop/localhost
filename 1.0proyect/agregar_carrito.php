<?php
include 'config.php';
session_start();

if (!isset($_SESSION['carrito'])) {
    $_SESSION['carrito'] = array();
}

if (isset($_GET['id'])) {
    $producto_id = intval($_GET['id']);
    $encontrado = false;

    foreach ($_SESSION['carrito'] as &$item) {
        if ($item['id'] == $producto_id) {
            $item['cantidad']++;
            $encontrado = true;
            break;
        }
    }

    if (!$encontrado) {
        $sql = "SELECT * FROM productos WHERE id = $producto_id";
        $result = $conn->query($sql);
        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            $producto = array(
                'id' => $row['id'],
                'nombre' => $row['nombre'],
                'precio' => $row['precio'],
                'cantidad' => 1
            );
            $_SESSION['carrito'][] = $producto;
        }
    }
}

$conn->close();

header('Location: ver_carrito.php');
exit();
?>
