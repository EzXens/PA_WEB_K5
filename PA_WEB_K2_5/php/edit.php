<?php
session_start();
require "connect.php";

if (isset($_SESSION['user_id'])) {
    $userId = $_SESSION['user_id'];

    if (isset($_GET["id_gambar"])) {
        $id = $_GET["id_gambar"];
        $read_sql = "SELECT * FROM gambar WHERE id_gambar = $id";
        $result = mysqli_query($conn, $read_sql);

        if ($result) {
            $data_gambar = mysqli_fetch_assoc($result);

            if (isset($_POST["edit"])) {
                $nama = htmlspecialchars($_POST["nama"]);
                $deskripsi = htmlspecialchars($_POST["deskripsi"]);

                $gambar_baru = $data_gambar['gambar']; // Default to existing image

                // Check if a new image is uploaded
                if (!empty($_FILES['gambar']['name'])) {
                    $gambar = htmlspecialchars($_FILES['gambar']['name']);
                    $temp_file = htmlspecialchars($_FILES['gambar']['tmp_name']);

                    // Remove the old image if it exists
                    if (!empty($data_gambar['gambar'])) {
                        $oldImagePath = "../assets/" . $data_gambar['gambar'];
                        if (file_exists($oldImagePath)) {
                            unlink($oldImagePath);
                        }
                    }

                    // Move the new image to the target folder
                    $target_folder = "../assets/";
                    $gambar_baru = date("Y-m-d") . " " . $gambar;
                    move_uploaded_file($temp_file, $target_folder . $gambar_baru);
                }

                // Use $gambar_baru (updated or existing image) in the UPDATE query
                $sql = "UPDATE gambar SET gambar = '$gambar_baru', nama_gambar = '$nama', deskripsi_gambar = '$deskripsi' WHERE id_gambar = '$id'";
                $result_update = mysqli_query($conn, $sql);

                if ($result_update) {
                    echo "
                    <script>
                    alert('Your Art has Been Updated');
                    document.location.href = '../user_profile.php'
                    </script>";
                } else {
                    echo "
                    <script>
                    alert('Failed to edit your art');
                    document.location.href = 'edit.php?id_gambar=$id'
                    </script>";
                }
            }
        } else {
            echo "Error: " . mysqli_error($conn);
        }
    } else {
        echo "ID Gambar tidak tersedia dalam URL";
    }
} else {
    echo "User ID tidak tersedia di sesi";
}
?>