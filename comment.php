<?php
// Menghubungkan ke database melalui file koneksi.php
require 'koneksi.php';

// Mengambil ID film dari URL (contoh: comment.php?id=2)
$movie_id = $_GET['id'];

// Ambil data film berdasarkan ID dari tabel movies
$movie = mysqli_query($conn, "SELECT * FROM movies WHERE id = $movie_id");
// Mengubah hasil query menjadi array asosiatif (untuk ditampilkan di halaman)
$movie_data = mysqli_fetch_assoc($movie);

// Proses saat form dikirim (ketika tombol submit ditekan)
if (isset($_POST['submit'])) {
    // Mengambil input dari form dan membersihkan dari karakter berbahaya (XSS protection)
    $username = htmlspecialchars($_POST['username']);
    $comment = htmlspecialchars($_POST['comment']);

    // Menyimpan komentar baru ke database (ke tabel comments)
    mysqli_query($conn, "INSERT INTO comments (movie_id, username, comment) VALUES ($movie_id, '$username', '$comment')");

    // Setelah insert, redirect kembali ke halaman ini (agar form tidak terkirim ulang saat refresh)
    header("Location: comment.php?id=$movie_id");
    exit;
}

// Mengambil semua komentar untuk film yang sedang dibuka, urutkan dari terbaru ke terlama
$comments = mysqli_query($conn, "SELECT * FROM comments WHERE movie_id = $movie_id ORDER BY created_at DESC");
?>

<!DOCTYPE html>
<html>

<head>
    <title>Komentar - <?= $movie_data['title']; ?></title>
    <!-- Menggunakan Bootstrap CSS dari CDN -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<!-- Styling dasar halaman (dark mode) -->

<body style="background-color: #1c1c1c; color: white;">

    <div class="container mt-5">
        <!-- Judul halaman dengan nama film -->
        <h2 class="text-warning">Komentar untuk: <?= $movie_data['title']; ?></h2>

        <!-- Tombol kembali ke halaman utama -->
        <a href="index.php" class="btn btn-secondary mb-3">Kembali</a>

        <!-- Form untuk mengirim komentar -->
        <form method="post" class="mb-4">
            <div class="mb-3">
                <label for="username">Nama Anda:</label>
                <input type="text" class="form-control" name="username" required>
            </div>
            <div class="mb-3">
                <label for="comment">Komentar:</label>
                <textarea class="form-control" name="comment" rows="3" required></textarea>
            </div>
            <button type="submit" name="submit" class="btn btn-warning">Kirim Komentar</button>
        </form>

        <!-- Bagian untuk menampilkan daftar komentar -->
        <h4 class="text-light">Komentar:</h4>
        <?php while ($row = mysqli_fetch_assoc($comments)) : ?>
            <div class="border p-3 mb-2 bg-dark rounded">
                <!-- Menampilkan nama pengguna yang memberi komentar -->
                <strong><?= htmlspecialchars($row['username']); ?></strong>
                <!-- Menampilkan waktu komentar dibuat -->
                <small class="text-muted">(<?= $row['created_at']; ?>)</small>
                <!-- Menampilkan isi komentar -->
                <p><?= htmlspecialchars($row['comment']); ?></p>
            </div>
        <?php endwhile; ?>
    </div>

</body>

</html>