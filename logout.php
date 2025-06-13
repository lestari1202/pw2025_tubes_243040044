<?php
// Mulai sesi untuk mengakses data session
session_start();

// Menghapus semua variabel session
session_unset(); // Menghapus data-data yang tersimpan di dalam session

// Menghancurkan session secara keseluruhan
session_destroy(); // Menghapus session dari server

// Redirect (pindahkan) pengguna ke halaman login
header("Location: login.php");

// Hentikan eksekusi kode setelah redirect
exit;
