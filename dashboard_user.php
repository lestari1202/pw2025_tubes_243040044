<?php
session_start();
require 'koneksi.php';

// Cek role, pastikan hanya user biasa yang bisa akses
if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'user') {
    header("Location: login.php");
    exit;
}

// Proses pencarian
$keyword = isset($_GET['search']) ? $_GET['search'] : '';
if ($keyword != '') {
    $result = mysqli_query($conn, "SELECT * FROM movies WHERE title LIKE '%$keyword%'");
} else {
    $result = mysqli_query($conn, "SELECT * FROM movies");
}
?>
<!DOCTYPE html>
<html>

<head>
    <title>Daftar Movie - User</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background: url('img/index_user.jpg') no-repeat center center fixed;
            background-size: cover;
            color: white;
        }

        .container-custom {
            background-color: rgba(0, 0, 0, 0.75);
            padding: 30px;
            border-radius: 15px;
            margin-top: 30px;
        }

        img {
            width: 80px;
        }

        input[type="search"] {
            background-color: white;
            color: black;
        }
    </style>
</head>

<body>

    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark shadow">
        <div class="container-fluid">
            <a class="navbar-brand" href="#">Movie Collection - User</a>
            <div class="d-flex">
                <form class="d-flex me-2" id="searchForm" method="get">
                    <input class="form-control me-2" type="search" name="search" placeholder="Cari Judul"
                        aria-label="Search" id="searchInput" value="<?= htmlspecialchars($keyword); ?>">
                </form>
                <a href="logout.php" class="btn btn-outline-light">Logout</a>
            </div>
        </div>
    </nav>

    <!-- Konten -->
    <div class="container container-custom">
        <h1 class="text-center mb-4">Daftar Film</h1>
        <table class="table table-bordered table-white table-hover text-center align-middle">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Poster</th>
                    <th>Judul</th>
                    <th>Tahun</th>
                    <th>Sutradara</th>
                    <th>Aktor</th>
                    <th>Durasi</th>
                    <th>Deskripsi</th>
                    <th>Komentar</th>
                </tr>
            </thead>
            <tbody>
                <?php $i = 1; ?>
                <?php while ($row = mysqli_fetch_assoc($result)) : ?>
                    <tr>
                        <td><?= $i++; ?></td>
                        <td><img src="img/<?= $row["photo"]; ?>" alt="poster"></td>
                        <td><?= $row["title"]; ?></td>
                        <td><?= $row["release_year"]; ?></td>
                        <td><?= $row["director"]; ?></td>
                        <td><?= $row["actor"]; ?></td>
                        <td><?= $row["duration"]; ?> menit</td>
                        <td><?= $row["description"]; ?></td>
                        <td>
                            <a href="comment.php?id=<?= $row['id']; ?>" class="btn btn-sm btn-info">Komentar</a>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>

    <!-- Script Bootstrap & Live Search -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        const input = document.getElementById("searchInput");
        const form = document.getElementById("searchForm");
        let timeout = null;

        input.addEventListener("input", function() {
            clearTimeout(timeout);
            timeout = setTimeout(() => {
                form.submit();
            }, 200); // Delay 600ms setelah user berhenti ngetik
        });
    </script>
</body>

</html>