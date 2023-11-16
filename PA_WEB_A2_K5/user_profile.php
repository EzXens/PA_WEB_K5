<?php


require "./php/connect.php";
session_start();

if (isset($_SESSION['user_id'])) {
    $userId = $_SESSION['user_id'];

    $query = "SELECT * FROM user WHERE id_user = $userId";
    $result = mysqli_query($conn, $query);

    if ($result) {
        $user = mysqli_fetch_assoc($result);
        $email = $user['email'];
        $username = $user['username'];
        echo "Logged in as: $username ($email)";
    } else {
        echo "Error fetching user data";
    }
} else {
    echo "Guest user";
}

if (isset($_POST['cari'])) {
    $keyword = $_POST['keyword'];

    // Cari gambar berdasarkan nama
    $read_select_sql = "SELECT g.id_gambar, g.* FROM gambar g WHERE g.nama_gambar LIKE '%$keyword%' AND g.id_user = $userId";

    // Cari gambar berdasarkan tag yang sesuai dengan kata kunci
    $read_select_sql .= " UNION SELECT g.id_gambar, g.* FROM gambar g
                        JOIN tag t ON g.id = t.gambar_id
                        WHERE t.tag_name LIKE '%$keyword%' AND g.id_user = $userId";

    $result = mysqli_query($conn, $read_select_sql);
} else {
    if (isset($_GET["id_gambar"])) {
        $id = $_GET["id_gambar"];
        $result = mysqli_query($conn, "SELECT * FROM gambar WHERE id_gambar = $id");
    } else {
        $read_sql = "SELECT * FROM gambar WHERE id_user = $userId";
        $result = mysqli_query($conn, $read_sql);
    }
}

if (isset($_GET["cari"])) {
  $keyword = $_GET["keyword"];
  $result = mysqli_query($conn, "SELECT * FROM gambar WHERE nama_gambar LIKE '%$keyword%'");
} else {
  $result = mysqli_query($conn, "SELECT * FROM gambar");
}

$user = [];

while ($row = mysqli_fetch_assoc($result)) {
    $user[] = $row;
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Document</title>
  <link rel="stylesheet" href="style/styles.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  <link rel="icon" href="assets/logooo.png">
  <style>
    *,
    button,
    input {
      font-family: Arial, sans-serif;
    }


    body {
      display: flex;
      flex-direction: column;
      min-height: 100vh;
      margin: 0;
      padding: 0;
      --nav-itam: linear-gradient(to top, #efefef, #ffffff);
      --nav-itam-samping: linear-gradient(to top, #efefef, #ffffff);
      --segitiga: #3498db;
      --text: black;
      --text_span: #0515cc;
      --primary: white;
      --buton: linear-gradient(to top, #3498db, #0515cc);
      --buton2: linear-gradient(to top, #3498db, #0515cc);

    }

    .dark {
      --nav-itam: linear-gradient(to top, #2e3132, #060606);
      --nav-itam-samping: linear-gradient(to bottom, #2e3132, #060606);
      --segitiga: black;
      --text: white;
      --primary: black;
      --text_span: #e63946;
      --buton: linear-gradient(to top, #2e3132, #060606);
      --buton2: linear-gradient(to top, #f94949, #7d0000);
    }


    .header img {
      width: 65px;
      height: 65px;
    }

    .header h3 {
      color: white;
      font-size: 20px;
      font-weight: 700;
    }



    .body {
      margin: 0;
      padding: 0;
      font-family: Arial, sans-serif;
    }

    .header {
      background: var(--nav-itam);
      display: flex;
      justify-content: space-between;
      align-items: center;
      position: fixed;
      top: 0;
      right: 0;
      left: 0;
      z-index: 9999;
      padding: 10px 20px;
    }

    .header img {
      width: 65px;
      height: 65px;
    }

    .header h3 {
      color: white;
      font-size: 20px;
      font-weight: 700;
      margin: 0;
      padding: 0 10px;
    }

    .header .navbar {
      display: flex;
      align-items: center;
    }

    .header .navbar input[type="text"] {
      padding: 8px;
      border: 1px solid #ccc;
      border-radius: 24px;
      width: 500px;
      margin-right: 10px;
    }

    .header .navbar button.search-button {
      background: var(--buton2);
      color: #fff;
      border: none;
      border-radius: 24px;
      padding: 8px 12px;
      margin-right: 10px;
    }


    .header .navbar a {
      text-decoration: none;
      font-size: 16px;
      color: var(--text);
      font-weight: 700;
      margin: 0 10px;
      display: inline-block;
      align-items: center;
      position: relative;
      transition: color 0.3s ease;
      justify-content: center;
    }

    .header .navbar a:hover {
      color: rgb(18, 12, 31);
      transition: all .2s ease-in-out;
      transform: translateX(2px);
    }

    .header .navbar a::after {
      content: '';
      display: block;
      padding-bottom: 0.5rem;
      border-bottom: 0.1rem solid #e63946;
      transform: scaleX(0);
    }


    .header .navbar a:hover::after {
      transform: scaleX(1);
    }

    .header .navbar button.login {
      color: #fff;
      background: var(--buton2);
      padding: 8px 12px;
      border: none;
      border-radius: 24px;
      display: flex;
      align-items: center;
      margin: 1rem;
    }

    .header .navbar button.login:hover {
      background-color: #3498db;
      transition: 0.5s ease-in-out;
    }

    .header .navbar button.signup {
      background: #efefef;
      padding: 8px 12px;
      border: none;
      border-radius: 24px;
    }

    .header .navbar-extra a {
      color: wheat;
      margin: 0 10px;
    }

    .checkbox {
      opacity: 0;
      position: absolute;
    }

    .checkbox-label {
      background: var(--buton);
      width: 70px;
      height: 30px;
      border-radius: 50px;
      position: relative;
      padding: 2px;
      cursor: pointer;
      display: flex;
      justify-content: space-around;
      align-items: center;
      align-self: center;
    }

    .fa-moon {
      color: #3498db;
    }

    .fa-sun {
      color: #0515cc;
    }

    .checkbox-label .ball {
      background-color: wheat;
      width: 25px;
      height: 25px;
      position: absolute;
      left: 4px;
      top: 4px;
      right: 4px;
      border-radius: 50%;
      transition: transform 0.2s ease;
    }

    .checkbox:checked+.checkbox-label .ball {
      transform: translateX(38px);
    }

    #menu {
      display: none;
    }

    /* 
main {
    flex: 1;
    padding: 20px;
    margin-bottom: 400px;
} */


    /* gallery section */
    .container-card {
      text-align: center;
      padding: 20px;
      background: var(--nav-itam-samping);
    }

    .gallery-img {
      position: relative;
    }

    .gallery-img a {
      display: block;
    }

    .row {
      display: flex;
      flex-wrap: wrap;
      align-items: center;
      justify-content: space-evenly;
    }

    .fix {
      overflow: hidden
    }

    .gallery-img a img {
      width: 300px;
      height: 300px;
      object-fit: cover;
    }

    .overlay {
      position: absolute;
      top: 0;
      left: 0;
      width: 300px;
      height: 300px;
      background-color: rgba(0, 0, 0, 0.7);
      display: flex;
      justify-content: center;
      align-items: center;
      opacity: 0;
      transition: opacity 0.3s;
    }

    .overlay-content {
      align-items: center;
      display: flex;
      justify-content: center;
    }

    /* Style untuk tombol "Like" dan "Comment" */
    .like-button,
    .comment-button {
      color: #fff;
      text-decoration: none;
      font-size: 25px;
      position: relative;
      top: 120px;
      /* Mengatur di bagian bawah */
      left: 100px;
      /* Mengatur di bagian kanan */
      background: rgba(0, 0, 0, 0.7);
      /* Latar belakang semi-transparan */
      padding: 5px 10px;
      border-radius: 5px;
    }

    /* Tampilkan tombol saat mengarahkan kursor ke galeri */
    .single-gallery:hover .like-button,
    .single-gallery:hover .comment-button {
      display: inline-block;
    }

    .single-gallery {
      margin: 10px;
      width: 300px;
      /* Ubah ukuran galeri */
      height: 300px;
      position: relative;
      overflow: hidden;
      background-color: gainsboro;
      border: solid 1px lightslategray;
      border-radius: 2%;
      box-shadow: 0px 25px 45px 0px rgba(0, 0, 0, 1);
      -webkit-box-shadow: 10px 10px 5px 0px rgba(93, 86, 86, 0.57);
      -moz-box-shadow: 10px 10px 5px 0px rgba(93, 86, 86, 0.57);

    }

    .like-button:hover {
      color: red;
      transition: .5s ease-in-out;
    }

    .comment-button:hover {
      color: lightskyblue;
      transition: .5s ease-in-out;
    }

    .overlay a:hover {
      text-decoration: none;
    }

    .single-gallery:hover .overlay {
      opacity: 1;
    }

    /* image popup */
    /* .popup img {
  max-width: 80%;
  max-height: 80%;
  margin-top: 10%;
} */

    .popup {
      display: none;
      position: fixed;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      background-color: rgba(0, 0, 0, 0.7);
      z-index: 1;
    }

    .popup-content {
      position: absolute;
      top: 50%;
      left: 50%;
      transform: translate(-50%, -50%);
      background: white;
      padding: 20px;
      text-align: center;
    }

    /* modal style */
    .popup {
      display: none;
      position: fixed;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      background-color: rgba(0, 0, 0, 0.7);
      z-index: 1;
    }

    .popup-content {
      position: absolute;
      top: 50%;
      left: 50%;
      transform: translate(-50%, -50%);
      background: white;
      padding: 20px;
      text-align: center;
      border: 1px solid #ccc;
      border-radius: 5px;
    }

    #popupImage {
      max-width: 100%;
      max-height: 70vh;
    }

    .comment-section {
      margin-top: 20px;
    }

    #commentList {
      list-style: none;
      padding: 0;
    }

    .comment {
      margin: 10px 0;
      border: 1px solid #ccc;
      padding: 10px;
      border-radius: 5px;
    }


    footer {
      background: var(--nav-itam-samping);
      margin-top: auto;
      margin-bottom: 2px;
      justify-content: center;
      align-items: center;
      text-align: center;
    }

    .button_tambah {
      background-color: var(--text_span);
      color: white;
      font-weight: bolder;
      padding: 14px 20px;
      margin-top: 15px;
      border: none;
      border-radius: 5px;
      cursor: pointer;
      width: 100%;
    }

    .button_tambah:hover {
      background-color: var(--text);
      transition: 1.5s ease-in-out;
    }

    .button_gallery {
      margin: 150px;
    }


    .btn-add {
      background-color: aqua;
      /* Blue color for the button */
      color: black;
      padding: 14px 20px;
      margin: 8px 0;
      border: none;
      cursor: pointer;
      width: 20%;
    }

    .modal {
      display: none;
      position: fixed;
      z-index: 9999999;
      left: 0;
      top: 0;
      width: 100%;
      height: 100%;
      overflow: auto;
      background-color: rgba(0, 0, 0, 0.4);
      padding-top: 60px;
      box-sizing: border-box;
    }

    .modal-content {
      background-color: #fefefe;
      margin: 5% auto;
      padding: 30px;
      border: 1px solid #ccc;
      border-radius: 10px;
      width: 80%;
      max-width: 400px;
      box-sizing: border-box;
      box-shadow: 0 0 10px rgba(0, 0, 0, 0.3);
    }

    .modal-title {
      margin-bottom: 15px;
      text-align: center;
      font-size: 24px;
    }

    .close {
      float: right;
      font-size: 28px;
      font-weight: bold;
      cursor: pointer;
    }

    .form-group {
      margin-bottom: 15px;
    }

    .form-group label {
      display: block;
      font-weight: bold;
      margin-bottom: 5px;
    }

    .form-control {
      width: 100%;
      padding: 10px;
      box-sizing: border-box;
      border: 1px solid #ccc;
      border-radius: 5px;
    }

    .form-control:focus {
      outline: none;
      border-color: #4CAF50;
    }

    .btn-add {
      background-color: aqua;
      color: black;
      padding: 14px 20px;
      margin-top: 15px;
      border: none;
      border-radius: 5px;
      cursor: pointer;
      width: 100%;
    }

    .btn-add:hover {
      background-color: lightskyblue;
      transition: 1.5s ease-in-out;
    }

    .detail-item
{
    display: none;
    position: fixed;
    z-index: 1;
    left: 0;
    top: 0;
    width: 100%;
    height: 100%;
    overflow: auto;
    background-color: rgba(0, 0, 0, 0.7);
}

.detail-item img
{
    width: 400px;
    height: 400px;
}

.desc
{   margin: 5% auto;
    padding: 20px;
    background-color: #fafafa;
    position: relative;
    box-shadow: 0 0 10px gray;
    border-radius: 10px;
    max-width: 800px;
}

.desc p
{
    font-size: 30px;
}

.editBtn, .deleteBtn
       {
            border: none;
            border-radius: 5px;
            height: 40px; 
            width: 100px;
            align-items: center;
            cursor: pointer;
       }

       .editBtn
       {
            background-color: #72CC50;
            color: white;
       }

       .deleteBtn
       {
            background-color: crimson;
            color: white;
       }

  </style>
</head>


<body>
  <nav class="header" id="header">
    <img src="assets/logooo.png" alt="Logo">
    <h3 span style="color:var(--text);">Pinter<span style="color: var(--text_span);">1st</span></h3>
    <div class="navbar">
      <input type="text" placeholder="Search" id="search">
      <button class="search-button">Search</button>
      <a href="index.php" id="home">HOME</a>
      <a href="#" id="list">Category</a>
      <a href="#" id="user">About Us</a>
      <a href="#" id="admin">Contact</a>
      <!-- <button class="login"><a href="login-page/login.php" style="text-decoration: none; color: #fff;">Log in</a></button>
            <button class="signup">Sign up</button> -->
      <?php
      if (isset($_SESSION['login_user'])) {
        echo "<div class='user-container'><a href='./php/req_logout.php'><button type='button' class='login'>Logout</button></a><h3 style='color:var(--text);' class='username'>Welcome!! <a href='user_profile.php'><span style='color:var(--text_span); font-weight: bolder;'>$username</span></a>  ^.^</h3></div>";
      } else {
        echo "<div class='user-container'><a href='login-page/login.php'><button type='button' class='login'>Login</button></a>
                <a href='login-page/login.php'><button class='signup'>Sign up</button></a><h3 style='color:var(--text);' class='username'>You Are $username </h3></div>";
      }
      ?>
    </div>

    <div class="toggle">
      <input type="checkbox" class="checkbox" id="checkbox">
      <label for="checkbox" class="checkbox-label">
        <i class="fas fa-moon"></i>
        <i class="fas fa-sun"></i>
        <span class="ball"></span>
      </label>
    </div>

    <div class="navbar-extra">
      <a href="#" id="menu"><i class="fas fa-bars"></i></a>
    </div>
  </nav>
  <!-- end header & navbar -->

  <!-- <nav class="navbar" id="navbar">
      <div class="search">
        <input type="text" placeholder="Search">
        <button class="search-button">Search</button>
    </div>
        <ul>
            <li><a href="#">Home</a></li>
            <li><a href="#">Category</a></li>
            <li><a href="#">About Us</a></li>
            <li><a href="#">Contact</a></li>
        </ul>
        <div>
          <input type="checkbox" class="checkbox" id="checkbox">
          <label for="checkbox" class="checkbox-label">
              <i class="fas fa-moon"></i>
              <i class="fas fa-sun"></i>
              <span class="ball"></span>
          </label>
      </div>
          <div class="buttons">
            <button class="login"><a href="login-page/" style="text-decoration: none; color: #fff;">Log in</a></button>
            <button class="signup">Sign up</button>
        </div>    

        <div class="navbar-extra">
          <a href="#" id="menu"><i class="fas fa-bars"></i></a>
          </div>
    </nav> -->


  <main>
    <div class="button_gallery">
      <button class="button_tambah" onclick="openModal2()">Tambah Gallery</button>
    </div>
    <div class="container-card" id="container-card">
    <h1 style="color: var(--text);">Gallery</h1>
    <div class="row">
        <?php $i = 1;
        if (!empty($user)) : ?>
            <?php foreach ($user as $gallery) : ?>
                <div class="single-gallery">
                    <div class="gallery-img">
                        <span class="pro-label"></span>
                        <a href="#" id="image_<?php echo $gallery["id_gambar"]; ?>">
                            <img src="./assets/<?php echo $gallery["gambar"]; ?>" alt="">
                        </a>
                        <div class="overlay" onclick="opendetail(<?php echo $gallery["id_gambar"]; ?>)">
                            <div class="overlay-content">
                                <a href="#" class="like-button" onclick="likeImage(event)"><i class="fas fa-heart"></i></a>
                                <a href="#" class="comment-button"><i class="fas fa-comment"></i></a>
                            </div>
                        </div>
                    </div>
                </div>
            <?php $i++;
            endforeach; ?>
        <?php else : ?>
            <p><span style="color:var(--text)">Data belum ada.</span></p>
        <?php endif; ?>
    </div>
</div>


    <!-- Edit Data Memakai Modal / POPUP -->
    <div id="EditDataModal" class="modal">
      <div class="modal-content">
        <span class="close" onclick="closeModal3()">&times;</span>
        <h2 class="modal-title">Edit Data</h2>
        <form action="./php/edit.php?id_gambar=<?php echo $gallery["id_gambar"];?>" method="POST" enctype="multipart/form-data">
          <div class="form-group">
            <label for="judul">Nama Gambar</label>
            <input type="text" class="form-control" id="judul" name="nama" placeholder="Nama Gambar" required>
          </div>
          <div class="form-group">
            <label for="genre">Deskripsi</label>
            <textarea type="text" class="form-control" id="genre" name="deskripsi" placeholder="Tambahkan Deskripsi" required></textarea>
          </div>

          <div class="form-group">
            <label for="tags">Pilih Tag</label>
            <select multiple class="form-control" id="tags" name="selected_tags[]" required>
              <?php
              // Ambil data tag dari tabel tags untuk ditampilkan dalam dropdown
              $tag_query = "SELECT * FROM tags";
              $tag_result = mysqli_query($conn, $tag_query);

              // Tampilkan tag dalam dropdown
              while ($tag_row = mysqli_fetch_assoc($tag_result)) {
                echo "<option value='" . $tag_row['nama_tags'] . "'>" . $tag_row['nama_tags'] . "</option>";
              }
              ?>
            </select>
          </div>
          <div class="form-group">
            <label for="tags">Pilih Kategori</label>
            <select multiple class="form-control" id="tags" name="selected_kategori[]" required>
              <?php
              // Ambil data tag dari tabel tags untuk ditampilkan dalam dropdown
              $kategori_query = "SELECT * FROM kategori";
              $kategori_result = mysqli_query($conn, $kategori_query);

              // Tampilkan tag dalam dropdown
              while ($kategori_row = mysqli_fetch_assoc($kategori_result)) {
                echo "<option value='" . $kategori_row['nama_kategori'] . "'>" . $kategori_row['nama_kategori'] . "</option>";
              }
              ?>
            </select>
          </div>

          <div class="form-group">
            <label>Gambar</label>
            <input type="file" id="gambar" name="gambar" required>
          </div>

          <input type="submit" name="edit" class="btn-add" value="edit"></input>
        </form>
      </div>
    </div>


    <!-- Add Data Memakai Modal / POPUP -->
    <div id="addDataModal" class="modal">
      <div class="modal-content">
        <span class="close" onclick="closeModal2()">&times;</span>
        <h2 class="modal-title">Add Data</h2>
        <form action="./php/create.php" method="POST" enctype="multipart/form-data">
          <div class="form-group">
            <label for="judul">Nama Gambar</label>
            <input type="text" class="form-control" id="judul" name="nama" placeholder="Nama Gambar" required>
          </div>
          <div class="form-group">
            <label for="genre">Deskripsi</label>
            <textarea type="text" class="form-control" id="genre" name="deskripsi" placeholder="Tambahkan Deskripsi" required></textarea>
          </div>

          <div class="form-group">
            <label for="tags">Pilih Tag</label>
            <select multiple class="form-control" id="tags" name="selected_tags[]" required>
              <?php
              // Ambil data tag dari tabel tags untuk ditampilkan dalam dropdown
              $tag_query = "SELECT * FROM tags";
              $tag_result = mysqli_query($conn, $tag_query);

              // Tampilkan tag dalam dropdown
              while ($tag_row = mysqli_fetch_assoc($tag_result)) {
                echo "<option value='" . $tag_row['nama_tags'] . "'>" . $tag_row['nama_tags'] . "</option>";
              }
              ?>
            </select>
          </div>
          <div class="form-group">
            <label for="tags">Pilih Kategori</label>
            <select multiple class="form-control" id="tags" name="selected_kategori[]" required>
              <?php
              // Ambil data tag dari tabel tags untuk ditampilkan dalam dropdown
              $kategori_query = "SELECT * FROM kategori";
              $kategori_result = mysqli_query($conn, $kategori_query);

              // Tampilkan tag dalam dropdown
              while ($kategori_row = mysqli_fetch_assoc($kategori_result)) {
                echo "<option value='" . $kategori_row['nama_kategori'] . "'>" . $kategori_row['nama_kategori'] . "</option>";
              }
              ?>
            </select>
          </div>

          <div class="form-group">
            <label>Gambar</label>
            <input type="file" id="gambar" name="gambar" required>
          </div>

          <input type="submit" name="tambah" class="btn-add" value="Tambahkan"></input>
        </form>
      </div>
    </div>


<!-- DETAIL SECTION -->
<?php foreach ($user as $gallery) : ?>
    <div id="item_gambar_<?php echo $gallery["id_gambar"]; ?>" class="detail-item" style="display: none;">
        <div class="desc">
            <a href="#" id="image_<?php echo $gallery["id_gambar"]; ?>">
                <img src="./assets/<?php echo $gallery["gambar"]; ?>" alt="">
            </a>
            <p style="color: #ffc052; font-size: 40px;"><?php echo $gallery["nama_gambar"]; ?></p> <br>
            <p><?php echo $gallery["tanggal_gambar"]; ?></p><br>
            <p style="font-size: 20px; background-color: #e6e6e6; padding: 10px;"><?php echo "deskripsi : <br>" . $gallery["deskripsi_gambar"]; ?></p>
            <a href="#" onclick="openModal3()"></i><button class="editBtn"><i class="fa-solid fa-pencil"></i>EDIT</button></a></button></a>
            <a href="./php/delete.php?id=<?php echo $gallery["id_gambar"];?>"><button class="deleteBtn"><i class="fa-solid fa-trash"></i>DELETE</button></a>
        </div>
        <a href="#"><button class="buy-button" onclick="closedetail(<?php echo $gallery["id_gambar"]; ?>)" style="margin-left: 1120px;">kembali</button></a>
    </div>
<?php endforeach; ?>
<!-- END OF DETAIL SECTION -->

  </main>

  <footer style="color: var(--text);">
    <p>&copy; 2023 Nama Perusahaan. Hak Cipta Dilindungi.</p>
  </footer>
  </div>


  <script>
    function openModal() {
      var modal = document.getElementById('imagePopup');
      modal.style.display = 'block';
    }

    function closeModal() {
      var modal = document.getElementById('imagePopup');
      modal.style.display = 'none';
    }

    function addData() {
      alert('Data added!');
      closeModal();
    }

    // Fungsi untuk mengatur tombol "like"
    function likeImage(event) {
      // Menghentikan penyebaran klik untuk menghindari memicu deskripsi gambar
      event.stopPropagation();
      // Tambahkan logika untuk mengatur tombol "like" di sini
      // Misalnya, Anda dapat menambahkan kode untuk mengganti warna tombol "like"
    }
    const navbar = document.querySelector('.navbar', '.toggle');

    // ketika hamburger menu di klik
    document.querySelector('#menu').
    onclick = () => {
      navbar.classList.toggle('active');
    };

    // menghilangkan nav jika klik hamburger menu diluar side bar
    const menu = document.querySelector('#menu');

    document.addEventListener('click', function(e) {
      if (!menu.contains(e.target) && !navbar.contains(e.target)) {
        navbar.classList.remove('active');
      }
    });



    const checkbox = document.getElementById("checkbox");

    checkbox.addEventListener("change", () => {
      document.body.classList.toggle("dark");
    });

    function openModal2() {
      var modal = document.getElementById('addDataModal');
      modal.style.display = 'block';
    }

    function closeModal2() {
      var modal = document.getElementById('addDataModal');
      modal.style.display = 'none';
    }
    function openModal3() {
      var modal = document.getElementById('EditDataModal');
      modal.style.display = 'block';
    }

    function closeModal3() {
      var modal = document.getElementById('EditDataModal');
      modal.style.display = 'none';
    }

    function opendetail(gambarId) {
        var detail = document.getElementById('item_gambar_' + gambarId);
        detail.style.display = 'block';
    }

    function closedetail(gambarId) {
        var detail = document.getElementById('item_gambar_' + gambarId);
        detail.style.display = 'none';
    }


    function addData() {
      alert('Data added!');
      closeModal();
    }
  </script>

</body>

</html>