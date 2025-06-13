<?php
// Mulai session dan koneksi ke database
require 'koneksi.php';
session_start();

// Cek apakah user sudah login
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit;
}

// Fitur Hapus Data Movie
if (isset($_GET['hapus'])) {
    $id = $_GET['hapus'];
    $get = mysqli_query($conn, "SELECT photo FROM movies WHERE id = $id");
    $foto = mysqli_fetch_assoc($get)["photo"];
    if ($foto) unlink("img/" . $foto); // Hapus gambar dari folder
    mysqli_query($conn, "DELETE FROM movies WHERE id = $id"); // Hapus data dari DB
    header("Location: dashboard.php");
    exit;
}

// Proses pencarian keyword
$keyword = isset($_GET['search']) ? $_GET['search'] : '';

// Sorting data berdasarkan kolom tertentu
$sort = isset($_GET['sort']) ? $_GET['sort'] : 'id';
$order = isset($_GET['order']) && $_GET['order'] === 'desc' ? 'DESC' : 'ASC';

// Validasi kolom yang bisa disort
$allowed_sort = ['title', 'release_year', 'director', 'id'];
if (!in_array($sort, $allowed_sort)) $sort = 'id';

// Query data dari tabel movies
$query = "SELECT * FROM movies";
if ($keyword !== '') {
    $query .= " WHERE title LIKE '%$keyword%'";
}
$query .= " ORDER BY $sort $order";

$result = mysqli_query($conn, $query);
?>

<!DOCTYPE html>
<html>

<head>
    <title>Daftar Movie</title>
    <!-- Bootstrap untuk styling -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background: url('img/bg_admin.jpg') no-repeat center center fixed;
            background-size: cover;
            color: white;
        }

        .container-custom {
            background-color: rgba(252, 86, 191, 0.85);
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

        thead th {
            color: black;
            background-color: #f8f9fa;
        }

        th a {
            text-decoration: none;
            color: inherit;
        }

        th a:hover {
            text-decoration: underline;
        }
    </style>
</head>

<body>

    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark shadow">
        <div class="container-fluid">
            <a class="navbar-brand" href="#">Movie Collection</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSearch">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarSearch">
                <ul class="navbar-nav me-auto mb-2 mb-lg-0"></ul>

                <!-- Form Search -->
                <form class="d-flex me-2" id="searchForm" method="get">
                    <input class="form-control me-2" type="search" name="search" placeholder="Cari Judul"
                        aria-label="Search" id="searchInput" value="<?= htmlspecialchars($keyword); ?>">
                    <input type="hidden" name="sort" value="<?= htmlspecialchars($sort); ?>">
                    <input type="hidden" name="order" value="<?= htmlspecialchars($order); ?>">
                </form>

                <!-- Logout Button -->
                <a href="logout.php" class="btn btn-outline-light">Logout</a>
            </div>
        </div>
    </nav>

    <!-- Konten Utama -->
    <div class="container container-custom">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1 class="text-dark">Daftar Movie</h1>
            <a href="tambah.php" class="btn btn-dark">+ Tambah Movie</a>
        </div>

        <table class="table table-bordered table-white table-hover text-center align-middle">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Poster</th>
                    <th><a href="?sort=title&order=<?= $order === 'ASC' ? 'desc' : 'asc'; ?>">Judul</a></th>
                    <th><a href="?sort=release_year&order=<?= $order === 'ASC' ? 'desc' : 'asc'; ?>">Tahun</a></th>
                    <th><a href="?sort=director&order=<?= $order === 'ASC' ? 'desc' : 'asc'; ?>">Sutradara</a></th>
                    <th>Aktor</th>
                    <th>Durasi</th>
                    <th>Deskripsi</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php $i = 1; ?>
                <?php while ($row = mysqli_fetch_assoc($result)) : ?>
                    <tr>
                        <td><?= $i++; ?></td>
                        <td><img src="img/<?= htmlspecialchars($row["photo"]); ?>" alt="poster"></td>
                        <td><?= htmlspecialchars($row["title"]); ?></td>
                        <td><?= $row["release_year"]; ?></td>
                        <td><?= htmlspecialchars($row["director"]); ?></td>
                        <td><?= htmlspecialchars($row["actor"]); ?></td>
                        <td><?= htmlspecialchars($row["duration"]); ?> menit</td>
                        <td><?= htmlspecialchars($row["description"]); ?></td>
                        <td>
                            <a href="edit.php?id=<?= $row['id']; ?>" class="btn btn-sm btn-dark mb-1">Edit</a><br>
                            <a href="dashboard.php?hapus=<?= $row['id']; ?>" class="btn btn-sm btn-danger"
                                onclick="return confirm('Yakin ingin menghapus data ini?');">Hapus</a><br>
                            <a href="comment.php?id=<?= $row['id']; ?>" class="btn btn-sm btn-warning mt-1">Komentar</a>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>

    <!-- Script Bootstrap -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <!-- Live Search otomatis -->
    <script>
        const input = document.getElementById("searchInput");
        const form = document.getElementById("searchForm");
        let timeout = null;

        input.addEventListener("input", function() {
            clearTimeout(timeout);
            timeout = setTimeout(() => {
                form.submit(); // submit otomatis setelah 500ms user berhenti mengetik
            }, 500);
        });
    </script>
</body>

</html>