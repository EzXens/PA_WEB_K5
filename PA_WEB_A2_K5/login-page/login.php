<?php
session_start();
require '../php/connect.php';
if (isset($_POST['login_user'])) {
  $email = $_POST['email_user'];
  $password = $_POST['password_user'];

  $result  = mysqli_query($conn, "SELECT * from user WHERE email = '$email' ");
if (mysqli_num_rows($result) > 0) {
    $row  = mysqli_fetch_assoc($result);

    if (password_verify($password, $row['password'])) {
        $_SESSION['email'] = $email;
        $_SESSION['username'] = $row['username'];
        $_SESSION['user_id'] = $row['id_user'];
        $_SESSION['login_user'] = true;
            // Periksa peran dan arahkan sesuai
        if ($row['role'] == 'admin') {
            header("location: ../admin/index.php");
        } else {
            header("location: ../index.php");
        }
        exit;
    }
  }
  $error = true;
}
?>



<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <link rel="stylesheet" href="style.css">
    <title>Modern Login Page | AsmrProg</title>
</head>

<body>
    <div class="banner">
    <video autoplay loop muted>
        <source src="../assets/100 Photo - Logo Reveal After Effects Templates.mp4">
    </video>
    <div class="container" id="container">
        <div class="form-container sign-up">
            <form action="../php/req_register.php" method="post">
                <h1>Sign Up</h1>
                <span>Create Your Account</span>
                <input type="text" placeholder="username" name="username">
                <input type="email" placeholder="email" name="email">
                <input type="password" placeholder="password" name="password">
                <input type="password" placeholder="cpassword" name="cpassword">
                <input type="hidden" name="role" value="user">
                <button name="register">Sign Up</button>
            </form>
        </div>
        <div class="form-container sign-in">
            <form action="login.php" method="POST">
                <h1>Sign In</h1>
                <span>use your username password</span>
                <br>
                <!-- <input type="email" placeholder="email" name="email"> -->
                <input type="email" placeholder="email" name="email_user">
                <input type="password" placeholder="password" name="password_user">
                <br>
                <button type="submit" name="login_user" >Sign In</button>
                <?php
        if (isset($error)) {
            echo "<p style='color: red;'>$error</p>";
        }
        ?>
            </form>
        </div>
        <div class="toggle-container">
            <div class="toggle">
                <div class="toggle-panel toggle-left">
                    <h1>Welcome Back!</h1>
                    <p>Enter your personal details to use all of site features</p>
                    <button class="hidden" id="login">Sign In</button>
                </div>
                <div class="toggle-panel toggle-right">
                    <h1>Hello, Friend!</h1>
                    <p>Register with your personal details to use all of site features</p>
                    <button class="hidden" id="register" >Sign Up</button>
                </div>
            </div>
        </div>
    </div>
</div>

    <script src="script.js"></script>
</body>

</html>