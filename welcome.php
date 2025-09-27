<?php
session_start();
$username = $_SESSION['username'] ?? '';
?>
<!DOCTYPE html>
<html>
<head>
    <title>Welcome</title>
    <style>
        body {
            background: #f4f6f8;
            font-family: Arial, sans-serif;
        }
        .welcome-container {
            width: 350px;
            margin: 80px auto;
            padding: 30px 25px;
            background: #fff;
            border-radius: 8px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.08);
            text-align: center;
        }
        .welcome-container h2 {
            margin-bottom: 25px;
            color: #333;
        }
        .welcome-container a {
            display: inline-block;
            margin-top: 18px;
            color: #007bff;
            text-decoration: none;
            font-weight: bold;
        }
        .welcome-container a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <div class="welcome-container">
        <h2>Welcome, <?= htmlspecialchars($username) ?>!</h2>
        <a href="index.php">Go to Home</a>
    </div>
</body>
</html>