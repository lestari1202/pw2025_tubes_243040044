<?php
require 'koneksi.php';
$id = $_GET["id"];
mysqli_query($conn, "UPDATE movies SET is_deleted = 1 WHERE id = $id");
header("Location: index.php");
exit;
