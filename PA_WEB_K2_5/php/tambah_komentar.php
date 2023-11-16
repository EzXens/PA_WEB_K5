<?php
// Mulai session jika belum dimulai
// session_start();
// require "connect.php";

// // Cek apakah pengguna telah login atau belum
// if (!isset($_SESSION['user_id'])) {
//     // Redirect atau melakukan tindakan lain jika pengguna belum login
//     exit("Anda tidak memiliki izin untuk melakukan ini.");
// }

// if ($_SERVER["REQUEST_METHOD"] == "POST") {
//     $id_gambar = $_POST['id_gambar'];
//     $isi_komentar = $_POST['isi_komentar'];
  
//     // Query untuk menambahkan komentar baru
//     $query_add_comment = "INSERT INTO komen (isi_komen, id_user, id_gambar) VALUES ('$isi_komentar', $user_id, $id_gambar)";
  
//     if (mysqli_query($conn, $query_add_comment)) {
//       header("Location: ../index.php"); // Redirect kembali ke halaman galeri setelah menambah komentar
//       exit();
//     } else {
//       echo "Error: " . $query_add_comment . "<br>" . mysqli_error($conn);
//     }
//   }
  
// File: tambah_komentar.php

// Koneksi ke database (gunakan koneksi yang sudah ada)

session_start();
require "connect.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Periksa apakah pengguna sudah login
    if (!isset($_SESSION['user_id'])) {
        // Redirect ke halaman login jika pengguna belum login
        header("Location: ../login-page/login.php"); // Ganti dengan halaman login Anda
        exit();
    }

    // Dapatkan data dari form
    $id_gambar = $_POST['id_gambar'];
    $isi_komentar = $_POST['isi_komentar'];
    $user_id = $_SESSION['user_id']; // Ambil ID pengguna dari sesi

    // Query untuk menambahkan komentar baru
    $query_add_comment = "INSERT INTO komen (isi_komen, id_user, id_gambar) VALUES (?, ?, ?)";
    $stmt = mysqli_prepare($conn, $query_add_comment);

    // Periksa apakah prepared statement berhasil dibuat
    if ($stmt) {
        mysqli_stmt_bind_param($stmt, "sii", $isi_komentar, $user_id, $id_gambar);
        mysqli_stmt_execute($stmt);

        // Redirect kembali ke halaman galeri setelah menambah komentar
        header("Location: ../index.php"); // Ganti dengan halaman galeri Anda
        exit();
    } else {
        // Jika terjadi kesalahan dalam prepared statement
        echo "Error: " . mysqli_error($conn);
    }
}

?>