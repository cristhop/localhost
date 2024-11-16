<?php
session_start();
include 'config.php';

// Añadir usuario
if (isset($_POST['add_user'])) {
    $first_name = $_POST['first_name'];
    $last_name_paternal = $_POST['last_name_paternal'];
    $last_name_maternal = $_POST['last_name_maternal'];
    $username = $_POST['username'];
    $password = $_POST['password'];
    $password_hash = password_hash($password, PASSWORD_BCRYPT);

    // Insertar usuario en la base de datos
    $stmt = $pdo->prepare("INSERT INTO users (first_name, last_name_paternal, last_name_maternal, username, password_hash) VALUES (?, ?, ?, ?, ?)");
    $stmt->execute([$first_name, $last_name_paternal, $last_name_maternal, $username, $password_hash]);

    $message = "Usuario añadido exitosamente.";
}

// Eliminar usuario
if (isset($_GET['delete_user'])) {
    $user_id = $_GET['delete_user'];

    $stmt = $pdo->prepare("DELETE FROM users WHERE id = ?");
    $stmt->execute([$user_id]);

    $message = "Usuario eliminado exitosamente.";
}

// Obtener usuarios
$stmt = $pdo->query("SELECT * FROM users");
$users = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - Lista de Usuarios</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f8f4f2;
            margin: 0;
            padding: 0;
            display: flex;
            flex-direction: column;
            align-items: center;
            min-height: 100vh;
        }
        .navbar {
            background-color: #6d597a;
            color: white;
            padding: 10px 20px;
            width: 100%;
            display: flex;
            justify-content: space-between;
            align-items: center;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.2);
        }
        .navbar-brand {
            font-size: 1.5em;
        }
        .navbar-cart a {
            color: white;
            text-decoration: none;
            padding: 5px 10px;
            border-radius: 5px;
            background-color: #6d597a;
            transition: background-color 0.3s;
        }
        .navbar-cart a:hover {
            background-color: #5c4b68;
        }
        .container {
            width: 90%;
            max-width: 1200px;
            padding: 20px;
            background: white;
            border-radius: 10px;
            box-shadow: 0 4px 16px rgba(0, 0, 0, 0.1);
            margin-top: 20px;
            margin-bottom: 40px;
        }
        .message {
            padding: 15px;
            margin-bottom: 20px;
            border-radius: 5px;
            background-color: #d9a7c7;
            color: #fff;
        }
        .message.error {
            background-color: #ffe0e0;
            color: #700000;
        }
        .user-list h2 {
            font-size: 1.8em;
            margin-bottom: 20px;
            color: #6d597a;
            text-align: center;
        }
        .user-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
            gap: 20px;
        }
        .user-item {
            background: #f9f7f7;
            border-radius: 10px;
            padding: 20px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            text-align: center;
            transition: transform 0.3s, box-shadow 0.3s;
        }
        .user-item:hover {
            transform: translateY(-5px);
            box-shadow: 0 0 15px rgba(0, 0, 0, 0.2);
        }
        .user-name {
            font-weight: bold;
            margin-bottom: 5px;
            color: #333;
        }
        .user-username {
            color: #555;
            margin-bottom: 15px;
        }
        .user-item button {
            background-color: #6d597a;
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s;
        }
        .user-item button:hover {
            background-color: #5c4b68;
        }
        .add-user {
            margin-top: 40px;
            padding: 20px;
            background-color: #f9f7f7;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            width: 100%;
        }
        .add-user h3 {
            font-size: 1.5em;
            margin-bottom: 20px;
            color: #6d597a;
        }
        .form-group {
            margin-bottom: 15px;
        }
        .form-group label {
            display: block;
            margin-bottom: 5px;
            color: #333;
        }
        .form-group input {
            width: 100%;
            padding: 10px;
            border-radius: 5px;
            border: 1px solid #ddd;
            box-sizing: border-box;
            transition: border-color 0.3s;
        }
        .form-group input:focus {
            border-color: #6d597a;
        }
        button[type="submit"] {
            background-color: #6d597a;
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 5px;
            cursor: pointer;
            font-size: 1em;
            transition: background-color 0.3s;
            width: 100%;
        }
        button[type="submit"]:hover {
            background-color: #5c4b68;
        }
        footer {
            width: 100%;
            padding: 10px;
            background-color: #232f3e;
            color: white;
            text-align: center;
            border-radius: 0 0 10px 10px;
            box-shadow: 0 -2px 8px rgba(0, 0, 0, 0.1);
            position: fixed;
            bottom: 0;
            left: 0;
        }
    </style>
</head>
<body>
    <div class="navbar">
        <div class="navbar-brand">Admin - Mi Tienda</div>
        <div class="navbar-cart">
            <a href="logout.php">Cerrar sesión</a>
        </div>
    </div>
    <div class="container">
        <div class="user-list">
            <h2>Lista de Usuarios</h2>
            
            <!-- Mensaje de éxito o error -->
            <?php if (isset($message)): ?>
                <div class="message <?php echo strpos($message, 'eliminado') ? 'error' : ''; ?>">
                    <?php echo htmlspecialchars($message); ?>
                </div>
            <?php endif; ?>

            <div class="user-grid">
                <?php foreach ($users as $user): ?>
                    <div class="user-item">
                        <div class="user-name">
                            <?php echo htmlspecialchars($user['first_name'] . ' ' . $user['last_name_paternal'] . ' ' . $user['last_name_maternal']); ?>
                        </div>
                        <div class="user-username">
                            <?php echo htmlspecialchars($user['username']); ?>
                        </div>
                        <a href="?delete_user=<?php echo htmlspecialchars($user['id']); ?>" onclick="return confirm('¿Estás seguro de que quieres eliminar este usuario?');">
                            <button>Eliminar Usuario</button>
                        </a>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
        <div class="add-user">
            <h3>Añadir Usuario</h3>
            <form method="post">
                <div class="form-group">
                    <label for="first_name">Nombre:</label>
                    <input type="text" name="first_name" id="first_name" required>
                </div>
                <div class="form-group">
                    <label for="last_name_paternal">Apellido Paterno:</label>
                    <input type="text" name="last_name_paternal" id="last_name_paternal" required>
                </div>
                <div class="form-group">
                    <label for="last_name_maternal">Apellido Materno:</label>
                    <input type="text" name="last_name_maternal" id="last_name_maternal" required>
                </div>
                <div class="form-group">
                    <label for="username">Usuario:</label>
                    <input type="text" name="username" id="username" required>
                </div>
                <div class="form-group">
                    <label for="password">Contraseña:</label>
                    <input type="password" name="password" id="password" required>
                </div>
                <button type="submit" name="add_user">Añadir Usuario</button>
            </form>
        </div>
    </div>

</body>
</html>
