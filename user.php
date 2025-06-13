<?php
session_start(); // Memulai session

// Cek apakah user sudah login dan punya role 'user'
if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'user') {
    header("Location: login.php"); // Jika belum login atau bukan user, redirect ke login
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>User Dashboard - Movie Collection</title>
    <style>
        /* Gaya umum body */
        body {
            font-family: Arial, sans-serif;
            background: url('img/bg_logo.jpg') no-repeat center center fixed;
            /* Background gambar */
            background-size: cover;
            color: white;
            margin: 0;
            padding: 0;
        }

        /* Kotak utama container */
        .container {
            background-color: rgba(0, 0, 0, 0.8);
            /* Warna latar transparan */
            max-width: 800px;
            margin: 50px auto;
            padding: 30px;
            border-radius: 15px;
            box-shadow: 0 0 20px rgba(34, 255, 113, 0.9);
            /* Efek bayangan hijau */
            text-align: center;
        }

        /* Judul utama */
        h1 {
            color: #22ff71;
            /* Warna hijau neon */
        }

        /* Teks sambutan */
        .welcome {
            font-size: 1.2rem;
            margin-bottom: 20px;
        }

        /* Link biasa */
        a {
            color: #00ffcc;
            text-decoration: none;
            font-weight: bold;
        }

        a:hover {
            text-decoration: underline;
        }

        /* Tombol logout */
        .btn-logout {
            margin-top: 20px;
            display: inline-block;
            padding: 10px 20px;
            background-color: #22ff71;
            color: #000;
            border-radius: 8px;
            font-weight: bold;
            transition: background-color 0.3s;
        }

        .btn-logout:hover {
            background-color: #00cc5c;
        }
    </style>
</head>

<body>
    <div class="container">
        <h1>Halo, User!</h1> <!-- Judul halaman -->

        <!-- Sambutan dan nama user yang login -->
        <p class="welcome">Selamat datang, <strong><?= htmlspecialchars($_SESSION['username']); ?></strong>!</p>

        <p>Ini adalah halaman khusus pengguna biasa.</p> <!-- Keterangan hak akses -->

        <!-- Link ke daftar film -->
        <p><a href="dashboard_user.php">Lihat Daftar Film</a></p>

        <!-- Tombol logout -->
        <a href="logout.php" class="btn-logout">Logout</a>
    </div>
</body>

</html>