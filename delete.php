<?php
require 'koneksi.php'; // Menghubungkan ke database melalui file koneksi

$id = $_GET["id"]; // Mengambil ID movie dari URL (parameter GET)

// Melakukan soft delete dengan mengubah nilai kolom 'is_deleted' menjadi 1
// Artinya data tidak benar-benar dihapus dari database, hanya ditandai sebagai 'dihapus'
mysqli_query($conn, "UPDATE movies SET is_deleted = 1 WHERE id = $id");

// Setelah proses update, pengguna diarahkan kembali ke halaman index.php
header("Location: index.php");
exit;
