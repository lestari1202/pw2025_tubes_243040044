<?php
session_start();
require 'koneksi.php';

if (isset($_POST['register'])) {
    $username = htmlspecialchars($_POST['username']);
    $password = $_POST['password'];
    $password2 = $_POST['password2'];

    if ($password !== $password2) {
        $error = "Konfirmasi password tidak cocok!";
    } else {
        $result = mysqli_query($conn, "SELECT username FROM user WHERE username='$username'");
        if (mysqli_fetch_assoc($result)) {
            $error = "Username sudah digunakan!";
        } else {
            $password = $_POST['password'];
            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
            mysqli_query($conn, "INSERT INTO user (username, password, role) VALUES ('$username', '$hashedPassword', 'user')");
            $success = "Registrasi berhasil! Silakan <a href='login.php'>login</a>.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Register - Movie Collection</title>
    <style>
        body {
            margin: 0;
            padding: 0;
            background: url('img/bg_logo.jpg') no-repeat center center fixed;
            background-size: cover;
            font-family: Arial, sans-serif;
            color: #fff;
        }

        .container {
            background-color: rgba(0, 0, 0, 0.7);
            padding: 30px;
            border-radius: 15px;
            width: 350px;
            margin: 100px auto;
            box-shadow: 0 0 15px rgba(0, 0, 0, 0.8);
            text-align: center;
        }

        label {
            display: block;
            margin-top: 10px;
            font-weight: bold;
            text-align: left;
        }

        input[type="text"],
        input[type="password"] {
            width: 100%;
            padding: 10px;
            border: none;
            border-radius: 8px;
            margin-top: 5px;
        }

        button {
            width: 100%;
            margin-top: 20px;
            padding: 10px;
            background-color: #ffcc00;
            border: none;
            border-radius: 8px;
            font-size: 16px;
            font-weight: bold;
            cursor: pointer;
            transition: background 0.3s ease;
        }

        button:hover {
            background-color: #ffaa00;
        }

        .error {
            color: red;
            font-style: italic;
            margin-bottom: 10px;
        }

        .success {
            color: #90ee90;
            font-style: italic;
            margin-bottom: 10px;
        }

        a {
            color: #ffcc00;
            text-decoration: none;
        }

        a:hover {
            text-decoration: underline;
        }
    </style>
</head>

<body>
    <div class="container">
        <h1>Register</h1>
        <?php if (isset($error)) : ?>
            <p class="error"><?= $error ?></p>
        <?php endif; ?>
        <?php if (isset($success)) : ?>
            <p class="success"><?= $success ?></p>
        <?php endif; ?>
        <form action="" method="post">
            <label for="username">Username :</label>
            <input type="text" name="username" id="username" required>

            <label for="password">Password :</label>
            <input type="password" name="password" id="password" required>

            <label for="password2">Konfirmasi Password :</label>
            <input type="password" name="password2" id="password2" required>

            <button type="submit" name="register">Register</button>
        </form>
        <p>Sudah punya akun? <a href="login.php">Login di sini</a></p>
    </div>
</body>

</html>