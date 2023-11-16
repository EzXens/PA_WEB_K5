<?php

require "./php/connect.php";
session_start();



// Memeriksa apakah pengguna sudah login atau tidak
if (isset($_SESSION['user_id'])) {
  $userId = $_SESSION['user_id'];

  // Lakukan query yang memerlukan informasi dari pengguna yang terautentikasi
  $query = "SELECT * FROM user WHERE id_user = $userId";
  $result = mysqli_query($conn, $query);

  // Pastikan query berhasil dieksekusi
  if ($result) {
    // Mendapatkan data pengguna
    $user = mysqli_fetch_assoc($result);

    // Melakukan sesuatu dengan informasi pengguna yang didapatkan
    $email = $user['email'];
    $username = $user['username'];

    // Misalnya, menampilkan informasi pengguna
    echo "Logged in as: $username ($email)";
  } else {
    // Jika terjadi kesalahan dalam query
    echo "Error fetching user data";
  }
} else {
  // Jika pengguna belum login
  echo "Guest user";
}


if (isset($_SESSION['email'])) {
  $email = $_SESSION['email'];
  $username = isset($_SESSION['username']) ? $_SESSION['username'] : "guest";
} else {
  $email = "guest";
  $username = "guest";
}
if (isset($_GET["cari"])) {
  $keyword = $_GET["keyword"];
  $result = mysqli_query($conn, "SELECT * FROM gambar WHERE nama_gambar LIKE '%$keyword%'");
} else {
  $result = mysqli_query($conn, "SELECT * FROM gambar");
}

if (isset($_GET['filter'])) {
  $selected_category = $_GET['kategori'];

  // Kueri untuk mengambil gambar berdasarkan kategori yang dipilih
  if ($selected_category === 'all') {
    $query = "SELECT * FROM gambar";
  } else {
    $query = "SELECT * FROM gambar WHERE id_kategori = $selected_category";
  }

  $result = mysqli_query($conn, $query);
}
// Mendapatkan informasi tags berdasarkan ID tags
function getTags($conn, $id_tags)
{
  $query = "SELECT * FROM tags WHERE id_tags = $id_tags";
  $result = mysqli_query($conn, $query);

  $tags = mysqli_fetch_assoc($result);
  return $tags;
}

// Mendapatkan informasi kategori berdasarkan ID kategori
function getCategory($conn, $id_kategori)
{
  $query = "SELECT * FROM kategori WHERE id_kategori = $id_kategori";
  $result = mysqli_query($conn, $query);

  $category = mysqli_fetch_assoc($result);
  return $category;
}



$user = [];

// while ($row = mysqli_fetch_assoc($result)) {
//   $user[] = $row;
// }

while ($row = mysqli_fetch_assoc($result)) {
  $tags = getTags($conn, $row['id_tags']);
  $category = getCategory($conn, $row['id_kategori']);

  $row['tags'] = $tags; // Menambahkan informasi tags ke dalam array hasil
  $row['category'] = $category !== null ? $category : ['nama_kategori' => 'Tidak Ada Kategori']; // Pemeriksaan jika kategori null

  $user[] = $row;
}

?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Document</title>
  <link rel="stylesheet" href="style/styles2.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  <link rel="icon" href="assets/logooo.png">
  <style>
    .liked-button i {
      color: red;
      /* Ubah warna ikon menjadi merah saat tombol sudah di-like */
    }

    .image-details {
      display: flex;
      align-items: center;
    }

    .image-info {
      flex: 1;
      padding: 10px;
    }

    .image-preview img {
      width: 500px;
      height: 500px;
      display: block;
      padding-top: 150px;
      margin-bottom: 20px;
      margin: 10px;
    }

    .comments-section {
      margin-top: 20px;
    }

    .comments {
      /* Your styles for comments */
    }

    .comment {
      margin-bottom: 10px;
    }

    .comment-form textarea {
      width: 100%;
      height: 100px;
      margin-bottom: 10px;
    }

    .detail-item {
      position: fixed;
      top: 50%;
      left: 50%;
      transform: translate(-50%, -50%);
      width: 80%;
      /* Sesuaikan lebar sesuai kebutuhan */
      max-width: 800px;
      /* Atur lebar maksimal jika diperlukan */
      background-color: white;
      /* Atur warna latar belakang */
      padding: 20px;
      border-radius: 8px;
      box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
      /* Efek bayangan */
    }

    .detail-content {
      display: flex;
      flex-direction: column;
    }

    .image-details {
      display: flex;
    }

    .image-preview img {
      max-width: 100%;
      /* Biar gambar tidak melebihi lebar kontainer */
      height: auto;
      /* Sesuaikan tinggi agar proporsional */
    }

    .image-preview {
      flex: 1;
      /* Agar gambar mengambil separuh dari lebar kontainer */
      padding-right: 20px;
      /* Jarak antara gambar dan info */
    }

    .image-info {
      flex: 1;
      /* Agar info mengambil separuh dari lebar kontainer */
    }

    .buy-button {
      margin-top: 20px;
      align-self: flex-end;
      /* Biar button berada di ujung kanan */
      /* Your other button styles */
    }
  </style>
</head>

<body>
  <nav class="header" id="header">
    <img src="assets/logooo.png" alt="Logo">
    <h3 span style="color:var(--text);">Pinter<span style="color: var(--text_span);">1st</span></h3>
    <div class="navbar">
      <form method="get" action="#">
        <input type="text" placeholder="Search" name="keyword" id="search">
        <button class="search-button" name="cari">Search</button>
      </form>
      <a href="index.php" id="home">HOME</a>
      <form method="get" action="index.php">
        <select name="kategori" id="kategori">
          <option value="all">Semua Kategori</option>
          <!-- Tambahkan opsi kategori dari database -->
          <?php
          $kategori_query = mysqli_query($conn, "SELECT * FROM kategori");
          while ($kategori = mysqli_fetch_assoc($kategori_query)) {
            echo '<option value="' . $kategori['id_kategori'] . '">' . $kategori['nama_kategori'] . '</option>';
          }
          ?>
        </select>
        <button type="submit" name="filter">Filter</button>
      </form>

      <a href="about.php" id="user">About Us</a>
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


  <main>
    <!-- banner -->
    <section class="banner" id="banner">
      <main class="content">
        <h1 class="dongo" style="color: white">Welcome To The Website Art Gallery <span style="color:var(--text_span);"> Pinter1st</span></h1>
        <p>Welcome To Our Website ,Explore The Art Gallery</p>
        <?php
        if (isset($_SESSION['user_id'])) {
        // Pengguna sudah login, izinkan untuk melihat sekarang
        echo '<a href="user_profile.php" class="lihat" id="lihat">Buat Sekarang</a>';
        } else {
        // Pengguna belum login, arahkan ke halaman login
        echo '<a href="./login-page/login.php" class="lihat" id="lihat">Login untuk Buat Sekarang</a>';
        }
        ?>
      </main>
      <div class="segitiga-bergerak">
        <div></div>
        <div></div>
        <div></div>
        <div></div>
        <div></div>
        <div></div>
        <div></div>
        <div></div>
        <div></div>
        <div></div>
        <div></div>
        <div></div>
        <div></div>
        <div></div>
        <div></div>
      </div>
    </section>
    <!-- end banner -->



    <div class="container-card" id="container-card">
      <h1 style="color: var(--text);">Gallery</h1>
      <div class="row">
        <?php
        $i = 1;
        if (!empty($user)) : ?>
          <?php foreach ($user as $gallery) : ?>
            <div class="single-gallery">
              <!-- Tampilan galeri gambar -->
              <div class="gallery-img">
                <span class="pro-label"></span>
                <a href="#" id="image<?php echo $i; ?>">
                  <img src="./assets/<?php echo $gallery["gambar"]; ?>" alt="">
                </a>
                <div class="overlay" onclick="opendetail(<?php echo $gallery["id_gambar"]; ?>)">
                  <div class="overlay-content">
                    <form action="./php/toggle_like.php" method="post">
                      <input type="hidden" name="gambar_id" value="<?php echo $gallery["id_gambar"]; ?>">
                      <?php
                      // Tampilkan tombol like dengan kelas yang sesuai berdasarkan kondisi like
                      if (isset($_SESSION['user_id'])) {
                        $userId = $_SESSION['user_id'];
                        $gambarId = $gallery["id_gambar"];
                        $query_check_like = "SELECT * FROM likes WHERE id_users = $userId AND id_gambars = $gambarId";
                        $result = mysqli_query($conn, $query_check_like);

                        if ($result && mysqli_num_rows($result) > 0) {
                          echo '<button style="margin-right: 5px; margin-top:15px;" type="submit" name="like_button" class="like-button liked-button">';
                        } else {
                          echo '<button style="margin-right: 5px; margin-top:15px;" type="submit" name="like_button" class="like-button">';
                        }
                      } else {
                        // Handle jika pengguna belum login
                        echo '<a href="./login-page/login.php"><button style="margin-right: 5px; margin-top:15px;" class="like-button" disabled>';
                      }
                      ?>
                      <i class="fas fa-heart"></i>
                      </button></a>
                    </form>
                    <a class="comment-button"><i class="fas fa-comment"></i></a>
                  </div>
                </div>
              </div>
              <div class="gallery-info">
                <p>Tags: <?php echo $gallery['tags']['nama_tags']; ?></p>
                <p>Category: <?php echo $gallery['category']['nama_kategori']; ?></p>
              </div>
            </div>
          <?php $i++;
          endforeach; ?>
        <?php else : ?>
          <p><span style="color:var(--text)">Data belum ada.</span></p>
        <?php endif; ?>
      </div>
    </div>



    <!-- DETAIL SECTION -->
<?php foreach ($user as $gallery) : ?>
  <div id="item_gambar_<?php echo $gallery["id_gambar"]; ?>" class="detail-item" style="display: none;">
    <div class="detail-content">
      <div class="image-details">
        <div class="image-preview">
          <img src="./assets/<?php echo $gallery["gambar"]; ?>" alt="">
        </div>
        <div class="image-info">
          <h2><?php echo $gallery["nama_gambar"]; ?></h2>
          <p>terakhir ditambahkan : <?php echo $gallery["tanggal_gambar"]; ?></p>
          <?php
          // Mendapatkan informasi username dari tabel 'user' berdasarkan 'id_user'
          $userId = $gallery["id_user"];
          $query_username = "SELECT username FROM user WHERE id_user = $userId";
          $result_username = mysqli_query($conn, $query_username);

          if ($result_username && mysqli_num_rows($result_username) > 0) {
            $username_row = mysqli_fetch_assoc($result_username);
            $username = $username_row["username"];

            // Menampilkan username yang menambahkan gambar
            echo "<p>Added by: <b>$username</b></p>";
          } else {
            echo "<p>Added by: Unknown User</p>";
          }
          ?>
          <p> <b>Deskripsi</b> :
            <hr> <?php echo $gallery["deskripsi_gambar"]; ?>
          </p>
          <i class="fas fa-heart" style="color:crimson">
            <span class="like-count" style="color:black"><?php echo $gallery["likes"]; ?></span>
          </i>
        </div>
      </div>

          <div class="gallery-info">
            <p>Tags: <?php echo $gallery['tags']['nama_tags']; ?></p>
            <p>Category: <?php echo $gallery['category']['nama_kategori']; ?></p>
          </div>

          <!-- Menampilkan komentar -->
          <div class="comments-section">
            <h3>Komentar:</h3>
            <div class="comments">
              <?php
              // Query untuk mengambil komentar dari database dengan informasi nama pengguna
              $query_get_comments = "SELECT komen.isi_komen, user.username 
                      FROM komen 
                      INNER JOIN user ON komen.id_user = user.id_user 
                      WHERE komen.id_gambar = ?";
              $stmt = mysqli_prepare($conn, $query_get_comments);

              if ($stmt) {
                mysqli_stmt_bind_param($stmt, "i", $gallery['id_gambar']);
                mysqli_stmt_execute($stmt);
                $result = mysqli_stmt_get_result($stmt);

                while ($row = mysqli_fetch_assoc($result)) {
                  echo "<div class='comment'><strong>" . $row['username'] . ":</strong> " . $row['isi_komen'] . "</div>";
                  // Tambahkan informasi lainnya yang ingin ditampilkan di sini
                }
              } else {
                echo "Error: " . mysqli_error($conn);
              }
              ?>
            </div>


            <form action="./php/tambah_komentar.php" method="post" class="comment-form">
              <input type="hidden" name="id_gambar" value="<?php echo $gallery['id_gambar']; ?>">
              <textarea name="isi_komentar" placeholder="Tambahkan komentar..." required></textarea>
              <button type="submit">Tambahkan Komentar</button>
            </form>
          </div>
          <a href="#"><button class="buy-button" onclick="closedetail(<?php echo $gallery["id_gambar"]; ?>)">Kembali</button></a>
        </div>
      </div>
    <?php endforeach; ?>
    <!-- END OF DETAIL SECTION -->




  </main>

  <footer style="color: var(--text);">
    <p>&copy; 2023 Nama Perusahaan. Hak Cipta Dilindungi.</p>
  </footer>
  </div>


  <script src="java/main.js">
  </script>

</body>

</html>