<?php
include 'config.php';
session_start();

// Obtener todas las categorías
$categories_sql = "SELECT * FROM categorias";
$categories_result = $pdo->query($categories_sql);

// Añadir un nuevo producto
if (isset($_POST['add_product'])) {
    $categoria_id = (int)$_POST['categoria_id'];
    $nombre = $_POST['nombre'];
    $descripcion = $_POST['descripcion'];
    $precio = (float)$_POST['precio'];

    // Asegúrate de que la categoría existe
    $categoria_check_sql = "SELECT id FROM categorias WHERE id = ?";
    $stmt = $pdo->prepare($categoria_check_sql);
    $stmt->execute([$categoria_id]);

    if ($stmt->rowCount() > 0) {
        // Manejo de subida de imagen
        if (isset($_FILES['imagen']) && $_FILES['imagen']['error'] == UPLOAD_ERR_OK) {
            $imagen_tmp = $_FILES['imagen']['tmp_name'];
            $imagen_nombre = basename($_FILES['imagen']['name']);
            $imagen_ruta = 'uploads/' . $imagen_nombre;

            // Asegúrate de que la carpeta 'uploads/' existe y tiene permisos de escritura
            if (!is_dir('uploads')) {
                mkdir('uploads', 0777, true);
            }

            // Mover la imagen subida a la carpeta de destino
            if (move_uploaded_file($imagen_tmp, $imagen_ruta)) {
                $add_product_sql = "INSERT INTO productos (nombre, descripcion, precio, imagen_url, categoria_id) VALUES (?, ?, ?, ?, ?)";
                $stmt = $pdo->prepare($add_product_sql);
                if ($stmt->execute([$nombre, $descripcion, $precio, $imagen_ruta, $categoria_id])) {
                    echo "<script>alert('Producto añadido correctamente.');</script>";
                } else {
                    echo "Error al añadir producto.";
                }
            } else {
                echo "Error al subir la imagen.";
            }
        } else {
            echo "Debe seleccionar una imagen.";
        }
    } else {
        echo "La categoría seleccionada no es válida.";
    }
}

// Eliminar un producto
if (isset($_GET['remove'])) {
    $remove_id = (int)$_GET['remove'];
    $remove_product_sql = "DELETE FROM productos WHERE id = ?";
    $stmt = $pdo->prepare($remove_product_sql);
    if ($stmt->execute([$remove_id])) {
        echo "<script>alert('Producto eliminado correctamente.');</script>";
    } else {
        echo "Error al eliminar producto.";
    }
}

// Obtener productos de la categoría seleccionada
$selected_category = isset($_GET['categoria']) ? (int)$_GET['categoria'] : 0;
$products_sql = $selected_category > 0 ? "SELECT * FROM productos WHERE categoria_id = ?" : "SELECT * FROM productos";
$stmt = $pdo->prepare($products_sql);

// Ejecutar consulta con parámetros si es necesario
if ($selected_category > 0) {
    $stmt->execute([$selected_category]);
} else {
    $stmt->execute();
}

$products_result = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestionar Productos por Categoría</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f8f4f2;
            color: #5a4b41;
            margin: 0;
            padding: 20px;
        }

        h1 {
            color: #6d597a;
        }

        .category-select, .product-form, .product-list {
            margin-bottom: 20px;
        }

        .product-form input[type="text"], .product-form input[type="number"] {
            padding: 10px;
            margin: 5px 0;
            width: calc(100% - 22px);
            border-radius: 10px;
            border: 1px solid #c8b8be;
        }

        .product-form input[type="file"] {
            margin: 10px 0;
        }

        .product-form button {
            background-color: #6d597a;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 15px;
            cursor: pointer;
            transition: background-color 0.3s;
        }

        .product-form button:hover {
            background-color: #4b3f5d;
        }

        .product-list table {
            width: 100%;
            border-collapse: collapse;
            border: 1px solid #c8b8be;
            border-radius: 10px;
            overflow: hidden;
        }

        .product-list th, .product-list td {
            padding: 10px;
            text-align: left;
            border-bottom: 1px solid #c8b8be;
        }

        .product-list th {
            background-color: #b56576;
            color: white;
        }

        .product-list tbody tr:nth-child(even) {
            background-color: #f9f9f9;
        }

        .product-list .remove-link {
            color: #ff0000;
            text-decoration: none;
        }

        .product-list .remove-link:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <h1>Gestionar Productos por Categoría</h1>

    <div class="category-select">
        <form method="get" action="gestionar_productos.php">
            <label for="categoria">Seleccionar Categoría:</label>
            <select name="categoria" id="categoria" onchange="this.form.submit()">
                <option value="0">Todas</option>
                <?php while ($row = $categories_result->fetch(PDO::FETCH_ASSOC)): ?>
                    <option value="<?php echo $row['id']; ?>" <?php if ($selected_category == $row['id']) echo 'selected'; ?>><?php echo htmlspecialchars($row['nombre']); ?></option>
                <?php endwhile; ?>
            </select>
        </form>
    </div>

    <div class="product-form">
        <h2>Añadir Nuevo Producto</h2>
        <form method="post" action="gestionar_productos.php" enctype="multipart/form-data">
            <input type="hidden" name="categoria_id" value="<?php echo $selected_category; ?>">
            <input type="text" name="nombre" placeholder="Nombre del Producto" required>
            <input type="text" name="descripcion" placeholder="Descripción del Producto" required>
            <input type="number" name="precio" placeholder="Precio" step="0.01" required>
            <input type="file" name="imagen" accept="image/*" required>
            <button type="submit" name="add_product">Añadir Producto</button>
        </form>
    </div>

    <div class="product-list">
        <h2>Lista de Productos</h2>
        <table>
            <thead>
                <tr>
                    <th>Nombre</th>
                    <th>Descripción</th>
                    <th>Precio</th>
                    <th>Imagen</th>
                    <th>Acción</th>
                </tr>
            </thead>
            <tbody>
                <?php if (count($products_result) > 0): ?>
                    <?php foreach ($products_result as $row): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($row['nombre']); ?></td>
                            <td><?php echo htmlspecialchars($row['descripcion']); ?></td>
                            <td>$<?php echo number_format($row['precio'], 2); ?></td>
                            <td><img src="<?php echo htmlspecialchars($row['imagen_url']); ?>" alt="<?php echo htmlspecialchars($row['nombre']); ?>" width="50"></td>
                            <td><a href="gestionar_productos.php?categoria=<?php echo $selected_category; ?>&remove=<?php echo $row['id']; ?>" class="remove-link">Eliminar</a></td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr><td colspan="5">No hay productos en esta categoría.</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</body>
</html>

<?php
$pdo = null;
?>
