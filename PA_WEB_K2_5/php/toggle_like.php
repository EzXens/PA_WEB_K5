<?php
// toggle_like.php
session_start();
require "connect.php";

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['like_button'])) {
    $gambarId = $_POST['gambar_id'];
    $userId = $_SESSION['user_id']; // Dapatkan ID pengguna dari sesi

    // Periksa apakah pengguna sudah login
    if (!isset($userId)) {
        // Handle jika pengguna belum login
        header("Location: ../login-page/login.php");
        exit();
    }

    // Cek apakah pengguna sudah like sebelumnya
    $query_check_like = "SELECT COUNT(*) AS count FROM likes WHERE id_gambars = ? AND id_users = ?";
    $stmt = mysqli_prepare($conn, $query_check_like);

    if ($stmt) {
        mysqli_stmt_bind_param($stmt, "ii", $gambarId, $userId);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        $row = mysqli_fetch_assoc($result);

        if ($row['count'] > 0) {
            // Jika sudah like, hapus like dari database
            $query_delete_like = "DELETE FROM likes WHERE id_gambars = ? AND id_users = ?";
            $stmt = mysqli_prepare($conn, $query_delete_like);

            if ($stmt) {
                mysqli_stmt_bind_param($stmt, "ii", $gambarId, $userId);
                mysqli_stmt_execute($stmt);
            }
        } else {
            // Jika belum like, tambahkan like ke database
            $query_add_like = "INSERT INTO likes (id_gambars, id_users) VALUES (?, ?)";
            $stmt = mysqli_prepare($conn, $query_add_like);

            if ($stmt) {
                mysqli_stmt_bind_param($stmt, "ii", $gambarId, $userId);
                mysqli_stmt_execute($stmt);
            }
        }

        // Hitung jumlah like untuk gambar tersebut
        $query_count_likes = "SELECT COUNT(*) AS total_likes FROM likes WHERE id_gambars = ?";
        $stmt = mysqli_prepare($conn, $query_count_likes);

        if ($stmt) {
            mysqli_stmt_bind_param($stmt, "i", $gambarId);
            mysqli_stmt_execute($stmt);
            $result = mysqli_stmt_get_result($stmt);
            $row = mysqli_fetch_assoc($result);

            $likes = $row['total_likes'];

            // Update jumlah like pada tabel gambar
            $query_update_likes = "UPDATE gambar SET likes = ? WHERE id_gambar = ?";
            $stmt = mysqli_prepare($conn, $query_update_likes);

            if ($stmt) {
                mysqli_stmt_bind_param($stmt, "ii", $likes, $gambarId);
                mysqli_stmt_execute($stmt);

                // Redirect kembali ke halaman galeri setelah mengupdate like
                header("Location: ../index.php");
                exit();
            }
        }
    } else {
        echo "Error: " . mysqli_error($conn);
    }
}
?>
