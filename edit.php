<?php
require 'koneksi.php';

// Ambil ID dari URL
$id = $_GET["id"];

// Ambil data film berdasarkan ID
$result = mysqli_query($conn, "SELECT * FROM movies WHERE id = $id");
$row = mysqli_fetch_assoc($result);

// Jika form disubmit
if (isset($_POST["submit"])) {
    $title = $_POST["title"];
    $release_year = $_POST["release_year"];
    $director = $_POST["director"];
    $actor = $_POST["actor"];
    $duration = $_POST["duration"];
    $description = $_POST["description"];

    // Upload foto baru jika ada
    if ($_FILES["photo"]["name"] != "") {
        $photo = $_FILES["photo"]["name"];
        $tmp = $_FILES["photo"]["tmp_name"];
        move_uploaded_file($tmp, "img/" . $photo);
    } else {
        $photo = $row["photo"];
    }

    // Update data
    $query = "UPDATE movies SET
                title = '$title',
                release_year = '$release_year',
                director = '$director',
                actor = '$actor',
                duration = '$duration',
                photo = '$photo',
                description = '$description'
              WHERE id = $id";

    mysqli_query($conn, $query);

    header("Location: dashboard.php");
    exit;
}
?>

<!DOCTYPE html>
<html>

<head>
    <title>Edit Movie</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background: url('img/edit.jpg') no-repeat center center fixed;
            background-size: cover;
            color: white;
        }

        .container-custom {
            background-color: rgba(0, 0, 0, 0.85);
            padding: 30px;
            border-radius: 15px;
            margin-top: 40px;
        }

        label {
            font-weight: bold;
        }
    </style>
</head>

<body>

    <div class="container container-custom">
        <h2 class="text-warning text-center mb-4">Edit Movie</h2>
        <form action="" method="post" enctype="multipart/form-data">
            <div class="mb-3">
                <label>Judul</label>
                <input type="text" name="title" class="form-control" required value="<?= $row["title"]; ?>">
            </div>
            <div class="mb-3">
                <label>Tahun Rilis</label>
                <input type="text" name="release_year" class="form-control" required value="<?= $row["release_year"]; ?>">
            </div>
            <div class="mb-3">
                <label>Sutradara</label>
                <input type="text" name="director" class="form-control" required value="<?= $row["director"]; ?>">
            </div>
            <div class="mb-3">
                <label>Aktor</label>
                <input type="text" name="actor" class="form-control" required value="<?= $row["actor"]; ?>">
            </div>
            <div class="mb-3">
                <label>Durasi (menit)</label>
                <input type="number" name="duration" class="form-control" required value="<?= $row["duration"]; ?>">
            </div>
            <div class="mb-3">
                <label>Deskripsi</label>
                <textarea name="description" class="form-control" rows="3" required><?= $row["description"]; ?></textarea>
            </div>
            <div class="mb-3">
                <label>Poster (biarkan kosong jika tidak ganti)</label>
                <input type="file" name="photo" class="form-control">
                <p class="mt-2">Preview sekarang: <img src="img/<?= $row["photo"]; ?>" width="80"></p>
            </div>
            <button type="submit" name="submit" class="btn btn-warning">Simpan Perubahan</button>
            <a href="dashboard.php" class="btn btn-secondary">Kembali</a>
        </form>
    </div>

</body>

</html>