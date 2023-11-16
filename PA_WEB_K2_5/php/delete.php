<?php
        // require "connect.php";
        // $id = $_GET['id'];

        // // Ambil nama file gambar yang terkait dengan data yang akan dihapus
        // $result = mysqli_query($conn, "SELECT gambar_path FROM data_anime WHERE id = $id");
        // if ($result && mysqli_num_rows($result) > 0) {
        //     $row = mysqli_fetch_assoc($result);
        //     $gambarFilename = $row['gambar_path'];

        //     // Hapus data dari database
        //     $deleteQuery = "DELETE FROM data_anime WHERE id = $id";
        //     $deleteResult = mysqli_query($conn, $deleteQuery);

        //     if ($deleteResult) {
        //         // Hapus file gambar dari direktori
        //         $gambarPath = "image/" . $gambarFilename; 
        //         if (file_exists($gambarPath)) {
        //             unlink($gambarPath);
        //         }

        //         header('location: crud.php');
        //     } else {
        //         echo "
        //         <script>
        //             alert('Data gagal dihapus!');
        //             document.location.href = 'crud.php';
        //         </script>";
        //     }
        // } else {
        //     echo "
        //     <script>
        //         alert('Data tidak ditemukan!');
        //         document.location.href = 'crud.php';
        //     </script>";
        // }


session_start();
require "connect.php";

if (isset($_SESSION['user_id'])) {
    $userId = $_SESSION['user_id'];

    
    $id = $_GET["id"];
    
    $sql = "DELETE FROM gambar WHERE id_gambar = $id";
    
    $result = mysqli_query($conn, $sql);
    
    
    if($result)
    {
        echo
        "<script>
        alert('Your Art Has Been Deleted');
        document.location.href = '../user_profile.php'
        </script>";
    }
    
    else
    {
        echo
        "<script>
        alert('Failed to delete your art:(');
        document.location.href = '../user_profile.php'
        </script>";
    }

}

?>
