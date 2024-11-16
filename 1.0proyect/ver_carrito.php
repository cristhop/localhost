<?php
include 'config.php';
session_start();

if (isset($_POST['checkout'])) {
    // Generar mensaje para WhatsApp
    $message = "¡Hola! Aquí está el resumen de mi pedido:\n\n";
    
    if (isset($_SESSION['carrito']) && !empty($_SESSION['carrito'])) {
        $total = 0;
        foreach ($_SESSION['carrito'] as $id => $item) {
            $subtotal = $item['precio'] * $item['cantidad'];
            $total += $subtotal;
            $message .= $item['nombre'] . " - $" . number_format($item['precio'], 2) . " x " . $item['cantidad'] . " = $" . number_format($subtotal, 2) . "\n";
        }
        $message .= "\nTotal: $" . number_format($total, 2) . "\n\n";
    } else {
        $message .= "El carrito está vacío.\n";
    }

    $message .= "Fecha y Hora: " . date('d-m-Y H:i:s') . "\n\n";
    $message .= "¡Gracias por su compra!";
    
    // Codificar mensaje para URL
    $encodedMessage = urlencode($message);
    
    // Crear URL de WhatsApp
    $whatsappURL = "https://wa.me/?text=" . $encodedMessage;
    
    // Redirigir al usuario a WhatsApp
    header("Location: " . $whatsappURL);
    exit();
}

if (isset($_GET['remove'])) {
    $remove_id = (int)$_GET['remove'];
    if (isset($_SESSION['carrito'][$remove_id])) {
        unset($_SESSION['carrito'][$remove_id]);
        // Reindexar el array para evitar problemas con los índices
        $_SESSION['carrito'] = array_values($_SESSION['carrito']);
    }
    header('Location: ver_carrito.php');
    exit();
}

$total = 0;
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Carrito de Compras</title>
    <style>
        /* General */
        body {
            font-family: Arial, sans-serif;
            background-color: #f8f4f2; /* Fondo en tono rosa pálido */
            margin: 0;
            padding: 0;
            color: #5a4b41; /* Texto en tono café oscuro */
            display: flex;
            min-height: 100vh;
        }

        /* Barra lateral */
        .sidebar {
            width: 180px;
            background-color: #e0d7da; /* Tono morado claro */
            border-right: 1px solid #c8b8be; /* Borde morado más oscuro */
            padding: 20px;
            box-shadow: 2px 0 5px rgba(0, 0, 0, 0.1);
            height: 100vh;
            position: fixed;
            left: 0;
            top: 0;
            border-radius: 0 15px 15px 0;
        }

        .sidebar nav {
            display: flex;
            flex-direction: column;
        }

        .sidebar nav a {
            display: block;
            color: #5a4b41;
            text-decoration: none;
            padding: 10px;
            margin-bottom: 10px;
            border-radius: 10px;
            transition: background-color 0.3s;
            border: 1px solid transparent;
        }

        .sidebar nav a:hover {
            background-color: #f3e3e7; /* Hover en tono rosa */
            border: 1px solid #c8b8be;
        }

        /* Encabezado */
        header {
            background-color: #d9a7c7; /* Fondo rosa-morado */
            color: #ffffff; /* Texto blanco */
            padding: 15px;
            width: calc(100% - 220px);
            margin-left: 220px;
            position: fixed;
            top: 0;
            left: 0;
            z-index: 10;
            text-align: center;
            border-radius: 0 0 15px 15px;
        }

        header h1 {
            margin: 0;
            font-size: 1.8em;
        }

        /* Contenedor principal */
        .container {
            display: flex;
            flex: 1;
            margin: 0;
            padding-top: 60px;
        }

        /* Contenido principal */
        main {
            margin-left: 240px;
            padding: 20px;
            flex: 1;
        }

        /* Tabla del carrito */
        table {
            width: 100%;
            border-collapse: separate;
            border-spacing: 0;
            background-color: #fff;
            border-radius: 15px; /* Curvas en la tabla */
            overflow: hidden;
            border: 1px solid #c8b8be;
        }

        th, td {
            padding: 12px;
            text-align: left;
        }

        thead {
            background-color: #b56576; /* Tono rosado oscuro */
            color: white;
        }

        tbody tr:nth-child(even) {
            background-color: #f9f9f9;
        }

        tfoot {
            background-color: #f1f1f1;
        }

        .checkout-btn {
            background-color: #6d597a; /* Botón morado oscuro */
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 15px; /* Curvas en el botón de checkout */
            cursor: pointer;
            font-size: 1.1em;
            transition: background-color 0.3s;
            margin-top: 20px;
        }

        .checkout-btn:hover {
            background-color: #4b3f5d;
        }

        .remove-item {
            color: #ff0000;
            text-decoration: none;
            border-radius: 5px; /* Curvas en el enlace de eliminación */
        }

        .remove-item:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>

<div class="container">
    <header>
        <h1>Carrito de Compras</h1>
    </header>

    <div class="sidebar">
        <nav>
            <a href="index.php">Inicio</a>
            <a href="ver_carrito.php">Carrito</a>
            <a href="login.php">Locket</a>
            <br>      
            <br>
            <br>
            <br>
            <br>
            <br>
            <br>
            <br>
            <br>
            <br>
            <br>
            <br>
            <br>
            <br>
            <br>
            <br>
            <br>
            <br>
            <br>
            <br>
            <br>
            <br>
            <br>
            <br>
            <br>
            <br>
            <br>
            <br>
            <br>
            <br>
            <br>
            <br>      
            <br>
            <br>
            <br>
            <br>
            <br>
            <br>
          
            <h2>Ref: 77802949</h2>
        </nav>
    </div>

    <main>
        <table>
            <thead>
                <tr>
                    <th>Producto</th>
                    <th>Precio</th>
                    <th>Cantidad</th>
                    <th>Total</th>
                    <th>Acción</th>
                </tr>
            </thead>
            <tbody>
                <?php
                if (isset($_SESSION['carrito']) && !empty($_SESSION['carrito'])) {
                    foreach ($_SESSION['carrito'] as $id => $item) {
                        $subtotal = $item['precio'] * $item['cantidad'];
                        $total += $subtotal;
                        echo '<tr>';
                        echo '<td>' . htmlspecialchars($item['nombre']) . '</td>';
                        echo '<td>$' . number_format($item['precio'], 2) . '</td>';
                        echo '<td>' . htmlspecialchars($item['cantidad']) . '</td>';
                        echo '<td>$' . number_format($subtotal, 2) . '</td>';
                        echo '<td><a href="ver_carrito.php?remove=' . $id . '" class="remove-item">Eliminar</a></td>';
                        echo '</tr>';
                    }
                } else {
                    echo "<tr><td colspan='5'>El carrito está vacío.</td></tr>";
                }
                ?>
            </tbody>
            <tfoot>
                <tr>
                    <td colspan="4">Total</td>
                    <td>$<?php echo number_format($total, 2); ?></td>
                </tr>
            </tfoot>
        </table>
        <?php if (!empty($_SESSION['carrito'])): ?>
            <form method="post">
                <button type="submit" name="checkout" class="checkout-btn">Proceder al pedido</button>
            </form>
        <?php endif; ?>
    </main>
</div>

</body>
</html>

<?php
$conn->close();
?>
