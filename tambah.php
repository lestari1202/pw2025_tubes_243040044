<?php
require 'koneksi.php';

// Ambil semua genre dari database
$genres = mysqli_query($conn, "SELECT * FROM genres");

if (!$genres) {
    die("Query error: " . mysqli_error($conn)); // Jika query genre gagal
}

if (isset($_POST["submit"])) {
    $title = $_POST["title"];
    $release_year = $_POST["release_year"];
    $director = $_POST["director"];
    $actor = $_POST["actor"];
    $duration = $_POST["duration"];
    $description = $_POST["description"];
    $genre_id = $_POST["genre_id"];

    // Ambil dan simpan gambar
    $photo = $_FILES["photo"]["name"];
    $tmp = $_FILES["photo"]["tmp_name"];

    // Pastikan folder img/ ada
    if (!is_dir("img")) {
        mkdir("img");
    }

    move_uploaded_file($tmp, "img/" . $photo);

    // Gunakan prepared statement untuk keamanan
    $stmt = mysqli_prepare($conn, "INSERT INTO movies (title, release_year, director, actor, duration, photo, description, genre_id) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
    if (!$stmt) {
        die("Prepare failed: " . mysqli_error($conn));
    }

    mysqli_stmt_bind_param($stmt, "sssssssi", $title, $release_year, $director, $actor, $duration, $photo, $description, $genre_id);
    mysqli_stmt_execute($stmt);

    if (mysqli_stmt_affected_rows($stmt) > 0) {
        header("Location: list.php");
        exit;
    } else {
        echo "<script>alert('Gagal menyimpan data.');</script>";
    }

    mysqli_stmt_close($stmt);
}
?>

<!DOCTYPE html>
<html>

<head>
    <title>Tambah Movie</title>
    <style>
        body {
            background: url('img/bg_tambah.jpg') no-repeat center center fixed;
            background-size: cover;
            font-family: Arial, sans-serif;
            color: #fff;
        }

        .form-container {
            background-color: rgba(0, 0, 0, 0.8);
            width: 450px;
            margin: 60px auto;
            padding: 30px;
            border-radius: 15px;
        }

        h1 {
            text-align: center;
            color: #ffcc00;
        }

        label {
            display: block;
            margin-top: 10px;
        }

        input,
        textarea,
        select {
            width: 100%;
            padding: 8px;
            margin-top: 5px;
            border-radius: 8px;
            border: none;
        }

        button {
            margin-top: 15px;
            width: 100%;
            padding: 10px;
            background-color: #ffcc00;
            border: none;
            border-radius: 10px;
            font-weight: bold;
            cursor: pointer;
        }

        button:hover {
            background-color: #ffaa00;
        }
    </style>
</head>

<body>
    <div class="form-container">
        <h1>Tambah Movie</h1>
        <form action="" method="post" enctype="multipart/form-data">
            <label for="title">Judul:</label>
            <input type="text" name="title" id="title" required>

            <label for="release_year">Tahun Rilis:</label>
            <input type="text" name="release_year" id="release_year" required>

            <label for="director">Sutradara:</label>
            <input type="text" name="director" id="director" required>

            <label for="actor">Aktor Utama:</label>
            <input type="text" name="actor" id="actor" required>

            <label for="duration">Durasi (menit):</label>
            <input type="text" name="duration" id="duration" required>

            <label for="photo">Foto/Poster:</label>
            <input type="file" name="photo" id="photo" required>

            <label for="description">Deskripsi:</label>
            <textarea name="description" id="description" rows="4" required></textarea>

            <!-- Input Genre -->
            <label for="genre_id">Genre:</label>
            <select name="genre_id" id="genre_id" required>
                <option value="">-- Pilih Genre --</option>
                <?php while ($row = mysqli_fetch_assoc($genres)) : ?>
                    <option value="<?= $row['id']; ?>"><?= htmlspecialchars($row['name']); ?></option>
                <?php endwhile; ?>
            </select>

            <button type="submit" name="submit">Simpan</button>
        </form>
    </div>
</body>

</html>