<?php
include 'config.php';
session_start();

// Obtener categorías
$categories_sql = "SELECT * FROM categorias";
$categories_result = $conn->query($categories_sql);

// Obtener productos por categoría
$selected_category = isset($_GET['categoria']) ? (int)$_GET['categoria'] : 0;
$products_sql = $selected_category > 0 ? "SELECT * FROM productos WHERE categoria_id = $selected_category" : "SELECT * FROM productos";

// Ejecutar consulta de productos
$products_result = $conn->query($products_sql);

// Comprobar si la consulta de productos falló
if ($products_result === false) {
    die("Error en la consulta de productos: " . $conn->error);
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tienda en Línea</title>
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
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        .sidebar img {
    width: 60px; /* Ajusta el tamaño del logo */
    height: 60px; /* Asegúrate de que la altura sea igual al ancho para que sea circular */
    border-radius: 50%; /* Hace que el logo sea circular */
    margin-bottom: 20px; /* Espacio debajo del logo */
    object-fit: cover; /* Mantiene la imagen dentro del contenedor circular */
}

        .sidebar nav {
            display: flex;
            flex-direction: column;
            width: 100%;
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

        /* Menú desplegable de categorías */
        .dropdown {
            position: relative;
        }

        .dropdown-content {
            display: none;
            position: absolute;
            left: 0;
            top: 100%;
            background-color: #e0d7da;
            min-width: 220px;
            border-radius: 0 0 15px 15px;
            box-shadow: 0 8px 16px rgba(0, 0, 0, 0.2);
            z-index: 1;
        }

        .dropdown-content a {
            color: #5a4b41;
            padding: 10px;
            text-decoration: none;
            display: block;
            transition: background-color 0.3s;
            border-radius: 10px;
        }

        .dropdown-content a:hover {
            background-color: #f3e3e7;
        }

        .dropdown:hover .dropdown-content {
            display: block;
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

        /* Productos */
        .product {
            background-color: #fff;
            border: 1px solid #c8b8be;
            border-radius: 15px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            margin: 15px;
            padding: 15px;
            display: inline-block;
            vertical-align: top;
            width: 200px;
            text-align: center;
            position: relative;
        }

        .product img {
            width: 100%;
            height: auto;
            border-radius: 10px;
        }

        .product-info {
            padding: 10px 0;
        }

        .product-name {
            font-size: 1.2em;
            margin: 10px 0;
            font-weight: bold;
            color: #794c74; /* Tono morado más oscuro */
        }

        .product-price {
            color: #b56576; /* Tono rosado oscuro */
            font-size: 1.1em;
        }

        .add-to-cart {
            display: inline-block;
            margin-top: 10px;
            padding: 8px 15px;
            font-size: 1em;
            color: #fff;
            background-color: #6d597a; /* Botón morado oscuro */
            border-radius: 15px;
            text-decoration: none;
            transition: background-color 0.3s;
        }

        .add-to-cart:hover {
            background-color: #4b3f5d;
        }
    </style>
</head>
<body>

<div class="container">
    <header>
        <h1>Bienvenido a Manos Magicas</h1>
 
    </header>
    <div class="sidebar">
        <img src="Logo.png" alt="Logo de la Tienda"> <!-- Añade tu logo aquí -->
        <nav>
            <a href="index.php">Inicio</a>
            <a href="ver_carrito.php">Carrito</a>
            <div class="dropdown">
                <a href="#">Categorías</a>
                <div class="dropdown-content">
                    <?php
                    if ($categories_result->num_rows > 0) {
                        while ($category = $categories_result->fetch_assoc()) {
                            echo '<a href="index.php?categoria=' . $category['id'] . '">' . $category['nombre'] . '</a>';
                        }
                    } else {
                        echo '<p>No hay categorías disponibles.</p>';
                    }
                    ?>
                </div>
            </div>
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
            <h2>Ref: 77802949</h2>
            
        </nav>
    </div>

    <main>
        <?php
        if ($products_result->num_rows > 0) {
            while($row = $products_result->fetch_assoc()) {
                echo '<div class="product">';
                echo '<img src="' . $row['imagen_url'] . '" alt="' . $row['nombre'] . '">';
                echo '<div class="product-info">';
                echo '<p>' . $row['descripcion'] . '</p>';
                echo '</div>';
                echo '<div class="product-name">' . $row['nombre'] . '</div>';
                echo '<div class="product-price">$' . number_format($row['precio'], 2) . '</div>';
                echo '<a href="agregar_carrito.php?id=' . $row['id'] . '" class="add-to-cart">Añadir al Carrito</a>';
                echo '</div>';
            }
        } else {
            echo "<p>No hay productos disponibles.</p>";
        }
        ?>
    </main>
</div>

</body>
</html>
