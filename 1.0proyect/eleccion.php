<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Imágenes con Enlaces</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f8f4f2; /* Light pink background */
            margin: 0;
            padding: 0;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
        }
        .horizontal-bar, .bottom-bar {
            background-color: #6d597a; /* Dark purple */
            height: 60px;
            width: 100%;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
            position: fixed;
            z-index: 1000;
        }
        .horizontal-bar {
            top: 0;
            border-radius: 0 0 30px 30px;
        }
        .bottom-bar {
            bottom: 0;
            border-radius: 30px 30px 0 0;
        }
        .image-links {
            display: flex;
            gap: 40px; /* Space between links */
            justify-content: center;
            margin-top: 100px; /* Adjust for top bar space */
            margin-bottom: 100px; /* Adjust for bottom bar space */
            flex-wrap: wrap;
        }
        .image-link {
            display: block;
            text-align: center;
            text-decoration: none;
            color: #333;
            max-width: 240px;
            margin: 20px;
            background-color: #fff; /* White background for images */
            border-radius: 15px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            overflow: hidden;
        }
        .image-link img {
            width: 100%; /* Responsive image size */
            height: auto;
            border-radius: 15px 15px 0 0; /* Rounded top corners */
            display: block;
        }
        .image-link:hover {
            transform: translateY(-10px);
            box-shadow: 0 8px 16px rgba(0, 0, 0, 0.2);
        }
        .image-caption {
            padding: 15px;
            font-size: 1.2em;
            font-weight: bold;
            background-color: #d9a7c7; /* Light purple for captions */
            color: #fff;
            border-radius: 0 0 15px 15px; /* Rounded bottom corners */
        }
    </style>
</head>
<body>
    <div class="horizontal-bar"></div>
    <div class="bottom-bar"></div>
    <div class="image-links">
        <a href="gestionar_productos.php" class="image-link">
            <img src="Add.png" alt="Add Products">
            <div class="image-caption">Add Products</div>
        </a>
        <a href="users.php" class="image-link">
            <img src="users.png" alt="Add Users">
            <div class="image-caption">Add Users</div>
        </a>
    </div>
</body>
</html>
