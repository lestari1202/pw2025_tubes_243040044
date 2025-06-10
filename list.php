<?php
require 'koneksi.php';

// Hapus data jika ada parameter hapus di URL
if (isset($_GET['hapus'])) {
    $id = $_GET['hapus'];

    // Ambil nama file foto untuk dihapus dari folder img (opsional)
    $get = mysqli_query($conn, "SELECT photo FROM movies WHERE id = $id");
    $foto = mysqli_fetch_assoc($get)["photo"];
    if ($foto) {
        unlink("img/" . $foto); // Hapus file dari folder img
    }

    // Hapus data dari database
    mysqli_query($conn, "DELETE FROM movies WHERE id = $id");

    // Redirect ulang agar URL bersih
    header("Location: index.php");
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
    <title>Daftar Movie</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background: url('img/bg_admin.jpg') no-repeat center center fixed;
            background-size: cover;
            color: white;
        }

        .container-custom {
            background-color: rgba(0, 0, 0, 0.85);
            padding: 30px;
            border-radius: 15px;
            margin-top: 30px;
        }

        img {
            width: 80px;
        }
    </style>
</head>

<body>

    <!-- Navbar Bootstrap -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark shadow">
        <div class="container-fluid">
            <a class="navbar-brand" href="#">Movie Collection</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSearch">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarSearch">
                <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                    <!-- Tambah menu lain di sini jika perlu -->
                </ul>
                <form class="d-flex" action="" method="get">
                    <input class="form-control me-2" type="search" name="search" placeholder="Cari Judul" aria-label="Search" value="<?= htmlspecialchars($keyword); ?>">
                    <button class="btn btn-outline-warning" type="submit">Search</button>
                </form>
            </div>
        </div>
    </nav>

    <!-- Konten Utama -->
    <div class="container container-custom">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1 class="text-warning">Daftar Movie</h1>
            <a href="tambah.php" class="btn btn-warning">+ Tambah Movie</a>
        </div>

        <table class="table table-bordered table-dark table-hover text-center align-middle">
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
                </tr>
                <table class="table table-bordered table-dark table-hover text-center align-middle">
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
                            <th>Aksi</th> <!-- Kolom tambahan -->
                        </tr>
                    </thead>
                    <tbody>
                        <?php $i = 1; ?>
                        <?php while ($row = mysqli_fetch_assoc($result)) : ?>
                            <tr>
                                <td><?= $i; ?></td>
                                <td><img src="img/<?= $row["photo"]; ?>" alt="poster"></td>
                                <td><?= $row["title"]; ?></td>
                                <td><?= $row["release_year"]; ?></td>
                                <td><?= $row["director"]; ?></td>
                                <td><?= $row["actor"]; ?></td>
                                <td><?= $row["duration"]; ?> menit</td>
                                <td><?= $row["description"]; ?></td>
                                <td>
                                    <a href="edit.php?id=<?= $row['id']; ?>" class="btn btn-sm btn-warning mb-1">Edit</a><br>
                                    <a href="index.php?hapus=<?= $row['id']; ?>" class="btn btn-sm btn-danger" onclick="return confirm('Yakin ingin menghapus data ini?');">Hapus</a>
                                </td>
                            </tr>
                            <?php $i++; ?>
                        <?php endwhile; ?>
                    </tbody>
                </table>

        </table>
    </div>

    <!-- Bootstrap JS (jika butuh interaktivitas navbar) -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

</body>

</html>