<?php
require 'connect.php'; // Sesuaikan dengan lokasi file connect.php

if (isset($_GET['id'])) {
    $idGambarHapus = mysqli_real_escape_string($conn, $_GET['id']);

    // Hapus gambar dari database
    $sqlHapusGambar = "DELETE FROM gambar WHERE id_gambar = $idGambarHapus";
    mysqli_query($conn, $sqlHapusGambar);

    // Redirect ke halaman sebelumnya atau halaman yang sesuai
    header("Location: ../admin/index.php"); // Ganti dengan nama file atau URL yang sesuai
    exit;
} else {
    // Redirect ke halaman sebelumnya jika parameter tidak valid
    header("Location: ../admin/index.php"); // Ganti dengan nama file atau URL yang sesuai
    exit;
}
?>
