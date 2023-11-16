<?php
session_start();
require "connect.php";

date_default_timezone_set("Asia/Makassar");

if (isset($_SESSION['user_id'])) {
    $nama = mysqli_real_escape_string($conn, $_POST['nama']);
    $deskripsi = mysqli_real_escape_string($conn, $_POST['deskripsi']);
    
    $gambar = $_FILES['gambar']['name'];
    $temp_file = $_FILES['gambar']['tmp_name'];
    $userId = $_SESSION['user_id'];

    $now = strtotime("now");
    $tanggal = date("Y-m-d H:i:s", $now);

    $target_folder = "../assets/";
    $gambar_baru = date("Y-m-d") . " " . $gambar;

    move_uploaded_file($temp_file, $target_folder . $gambar_baru);

    // Mendapatkan ID Tags
    $selected_tags = isset($_POST['selected_tags']) ? $_POST['selected_tags'] : [];
    $selected_kategori = isset($_POST['selected_kategori']) ? $_POST['selected_kategori'] : [];

    $id_tags = [];
    $id_kategori = [];

    foreach ($selected_tags as $tag_name) {
        $tag_query = "SELECT id_tags FROM tags WHERE nama_tags = '$tag_name'";
        $tag_result = mysqli_query($conn, $tag_query);
        $tag_row = mysqli_fetch_assoc($tag_result);
        if ($tag_row) {
            $id_tags[] = $tag_row['id_tags'];
        }
    }

    foreach ($selected_kategori as $kategori_name) {
        $kategori_query = "SELECT id_kategori FROM kategori WHERE nama_kategori = '$kategori_name'";
        $kategori_result = mysqli_query($conn, $kategori_query);
        $kategori_row = mysqli_fetch_assoc($kategori_result);
        if ($kategori_row) {
            $id_kategori[] = $kategori_row['id_kategori'];
        }
    }

    // Gabungkan ID kategori menjadi string untuk dimasukkan ke dalam database
    $id_tags_string = implode(',', $id_tags);
    $id_kategori_string = implode(',', $id_kategori);

    $query = "INSERT INTO gambar (gambar, nama_gambar, deskripsi_gambar, tanggal_gambar, id_tags, id_user, id_kategori) 
              VALUES ('$gambar_baru', '$nama', '$deskripsi', '$tanggal', '$id_tags_string', '$userId', '$id_kategori_string')";

    if (mysqli_query($conn, $query)) {
        header("Location: ../user_profile.php");
        exit();
    } else {
        echo "Error: " . $query . "<br>" . mysqli_error($conn);
    }
} else {
    echo "ID User tidak terdefinisi";
}
?>
