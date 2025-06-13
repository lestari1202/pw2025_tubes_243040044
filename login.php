<?php
session_start(); // Memulai session
require 'koneksi.php'; // Menghubungkan ke database

// Jika sudah login, arahkan ke halaman sesuai role
if (isset($_SESSION['username'])) {
    if ($_SESSION['role'] === 'admin') {
        header("Location: admin.php"); // Jika admin, ke halaman admin
    } else {
        header("Location: index.php"); // Jika user biasa, ke halaman user/index
    }
    exit;
}

// Proses ketika tombol login ditekan
if (isset($_POST['login'])) {
    $username = htmlspecialchars($_POST['username']); // Hindari XSS dengan htmlspecialchars
    $password = $_POST['password'];

    // Cek username di database
    $result = mysqli_query($conn, "SELECT * FROM user WHERE username='$username'");
    if (mysqli_num_rows($result) === 1) {
        $row = mysqli_fetch_assoc($result);

        // Verifikasi password dengan hash
        if (password_verify($password, $row['password'])) {
            session_regenerate_id(true); // Regenerasi session ID untuk keamanan
            $_SESSION['username'] = $username;
            $_SESSION['role'] = $row['role']; // Simpan role (admin/user)

            // Redirect sesuai role
            if ($row['role'] === 'admin') {
                header("Location: admin.php");
            } else {
                header("Location: user.php");
            }
            exit;
        } else {
            $error = "Password salah!"; // Jika password salah
        }
    } else {
        $error = "Username tidak ditemukan!"; // Jika username tidak ada
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Login - Movie Collection</title>
    <style>
        /* Gaya tampilan halaman login */
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
            /* Kotak transparan hitam */
            padding: 30px;
            border-radius: 15px;
            width: 350px;
            margin: 100px auto;
            box-shadow: 0 0 15px rgba(0, 0, 0, 0.8);
            /* Bayangan di kotak login */
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
            background-color: rgb(253, 42, 190);
            /* Tombol pink */
            border: none;
            border-radius: 8px;
            font-size: 16px;
            font-weight: bold;
            cursor: pointer;
            transition: background 0.3s ease;
        }

        button:hover {
            background-color: rgb(237, 153, 206);
            /* Hover tombol */
        }

        .error {
            color: red;
            font-style: italic;
            margin-bottom: 10px;
        }

        a {
            color: rgb(241, 85, 218);
            text-decoration: none;
        }

        a:hover {
            text-decoration: underline;
        }
    </style>
</head>

<body>
    <div class="container">
        <h1>Login</h1>
        <!-- Tampilkan pesan error jika ada -->
        <?php if (isset($error)) : ?>
            <p class="error"><?= $error ?></p>
        <?php endif; ?>

        <!-- Form login -->
        <form action="" method="post">
            <label for="username">Username :</label>
            <input type="text" name="username" id="username" required>

            <label for="password">Password :</label>
            <input type="password" name="password" id="password" required>

            <button type="submit" name="login">Login</button>
        </form>

        <!-- Link ke halaman registrasi -->
        <p>Belum punya akun? <a href="register.php">Register di sini</a></p>
    </div>
</body>

</html>