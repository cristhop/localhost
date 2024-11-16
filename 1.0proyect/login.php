<?php
session_start();
include 'config.php';

$login_message = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    $stmt = $pdo->prepare("SELECT * FROM users WHERE username = ?");
    $stmt->execute([$username]);
    $user = $stmt->fetch();

    if ($user && password_verify($password, $user['password_hash'])) {
        $_SESSION['user_id'] = $user['id'];
        header('Location: eleccion.php');
        exit();
    } else {
        $login_message = "Usuario o contraseña incorrectos.";
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Iniciar Sesión</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.10.5/font/bootstrap-icons.min.css">
    <style>
        /* General styles */
        body {
            font-family: Arial, sans-serif;
            background-color: #f8f4f2; /* Light pink background */
            margin: 0;
            padding: 20px;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            flex-direction: column;
        }
        .login-container {
            background-color: #fff; /* White background for contrast */
            padding: 30px;
            border-radius: 20px; /* Rounded corners */
            box-shadow: 0 4px 16px rgba(0, 0, 0, 0.2); /* Subtle shadow */
            width: 100%;
            max-width: 400px;
            text-align: center;
            border: 2px solid #e0d7da; /* Light purple border */
        }
        h2 {
            color: #6d597a; /* Darker purple for headings */
            font-size: 1.8em;
            margin-bottom: 20px;
        }
        label {
            display: block;
            margin-bottom: 5px;
            color: #794c74; /* Purple for labels */
            font-weight: bold;
            text-align: left;
        }
        input[type="text"], input[type="password"] {
            width: 100%;
            padding: 12px;
            margin-bottom: 15px;
            border: 1px solid #ddd;
            border-radius: 15px; /* Rounded input fields */
            box-sizing: border-box;
            font-size: 1em;
        }
        button {
            background-color: #b56576; /* Button with dark pink color */
            color: white;
            border: none;
            padding: 12px 20px;
            border-radius: 15px; /* Rounded button */
            cursor: pointer;
            font-size: 1.1em;
            width: 100%;
            transition: background-color 0.3s;
        }
        button:hover {
            background-color: #794c74; /* Darker purple on hover */
        }
        a {
            color: #007185;
            text-decoration: none;
        }
        a:hover {
            text-decoration: underline;
        }
        .login-message {
            margin-top: 20px;
            color: #d9534f; /* Red for errors */
            font-weight: bold;
        }
    </style>
</head>
<body>
    <div class="login-container">
        <h2>Iniciar Sesión</h2>
        <form method="post">
            <label for="username">Usuario:</label>
            <input type="text" name="username" id="username" required>
            <label for="password">Contraseña:</label>
            <input type="password" name="password" id="password" required>
            <button type="submit">Iniciar Sesión</button>
        </form>
        <?php if ($login_message): ?>
            <div class="login-message"><?php echo htmlspecialchars($login_message); ?></div>
        <?php endif; ?>
    </div>
</body>
</html>
