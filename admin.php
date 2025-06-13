<?php
session_start(); // Memulai session untuk mengecek status login

// Mengecek apakah user sudah login, jika belum, redirect ke halaman login
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <title>Admin Dashboard - Movie Collection</title>
    <style>
        /* Styling untuk tampilan dashboard admin */
        body {
            font-family: Arial, sans-serif;
            background: url('img/bg_logo.jpg') no-repeat center center fixed;
            background-size: cover;
            color: white;
            margin: 0;
            padding: 0;
        }

        .container {
            background-color: rgba(0, 0, 0, 0.8);
            /* Latar transparan hitam */
            max-width: 900px;
            margin: 50px auto;
            padding: 30px;
            border-radius: 15px;
            box-shadow: 0 0 20px rgba(245, 22, 22, 0.9);
            /* Bayangan merah */
        }

        h1 {
            color: rgb(245, 13, 13);
            /* Judul berwarna merah */
            text-align: center;
        }

        .welcome {
            margin-bottom: 20px;
            font-size: 1.2rem;
            text-align: center;
        }

        .btn-logout {
            display: block;
            width: 120px;
            margin: 20px auto;
            padding: 10px;
            text-align: center;
            background-color: rgb(254, 34, 34);
            /* Tombol merah */
            color: black;
            font-weight: bold;
            text-decoration: none;
            border-radius: 8px;
            transition: background-color 0.3s ease;
        }

        .btn-logout:hover {
            background-color: rgb(251, 250, 249);
            /* Hover effect */
        }
    </style>
</head>

<body>
    <div class="container">
        <h1>Admin Dashboard</h1>

        <!-- Menampilkan username yang sedang login -->
        <p class="welcome">Selamat datang, <strong><?= htmlspecialchars($_SESSION['username']); ?></strong>!</p>

        <!-- Penjelasan singkat halaman admin -->
        <p>Ini adalah halaman admin untuk mengelola data film.</p>

        <!-- Link menuju dashboard atau daftar film -->
        <p><a href="dashboard.php" style="color:#ffcc00; text-decoration:underline;">Lihat Daftar Film</a></p>

        <!-- Tombol logout -->
        <a href="logout.php" class="btn-logout">Logout</a>
    </div>
</body>

</html>