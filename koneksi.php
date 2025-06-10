<?php
// Membuat koneksi ke database MySQL
$conn = mysqli_connect("localhost", "root", "", "movie_collection");

// Cek apakah koneksi berhasil atau tidak
if (!$conn) {
    // Jika gagal, tampilkan pesan error
    die("Koneksi gagal: " . mysqli_connect_error());
}

