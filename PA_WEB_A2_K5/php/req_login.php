
<?php
// session_start();
require 'req_register.php';
require 'connect.php';

// if ($_SERVER["REQUEST_METHOD"] == "POST") {
if (isset($_POST['login'])) {
    $email = $_POST['email'];
    $password = $_POST['password'];

    $result  = mysqli_query($conn,"SELECT * from user WHERE email = '$email' ");
    if(mysqli_num_rows($result) > 0){
        $row  = mysqli_fetch_assoc($result);
        if(password_verify($password, $row['password'])){
            $_SESSION['user_id'] = $row['id_user'];
            $_SESSION['login'] = True;
            echo "<script> alert( 'Registrasi wewdwdd!!');
            document.location.href='../login-page/login.php';
            </script>";
            // exit;   
        }
    }
    $error = true;
}
// }
?>