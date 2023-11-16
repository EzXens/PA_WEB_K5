<?php
require '../php/connect.php';

// Fungsi tambah tag
if (isset($_POST['tambah_tag'])) {
    $namaTag = mysqli_real_escape_string($conn, $_POST['nama_tag']);
    $sqlTambah = "INSERT INTO tags (nama_tags) VALUES ('$namaTag')";
    mysqli_query($conn, $sqlTambah);
    header("Location:../admin/index.php"); // Ganti dengan nama file ini
    exit;
}

// Fungsi hapus tag
if (isset($_GET['hapus']) && isset($_GET['id'])) {
    $idTagHapus = mysqli_real_escape_string($conn, $_GET['id']);
    $sqlHapus = "DELETE FROM tags WHERE id_tags = $idTagHapus";
    mysqli_query($conn, $sqlHapus);
    header("Location:../admin/index.php"); // Ganti dengan nama file ini
    exit;
}

// Fungsi edit tag
if (isset($_POST['edit_tag'])) {
    $idTagEdit = mysqli_real_escape_string($conn, $_POST['id_tag']);
    $namaTagEdit = mysqli_real_escape_string($conn, $_POST['nama_tag']);
    $sqlEdit = "UPDATE tags SET nama_tags = '$namaTagEdit' WHERE id_tags = $idTagEdit";
    mysqli_query($conn, $sqlEdit);
    header("Location:../admin/index.php"); // Ganti dengan nama file ini
    exit;
}

$result = mysqli_query($conn, "SELECT * FROM tags");
$tags = [];

while ($row = mysqli_fetch_assoc($result)) {
    $tags[] = $row;
}
?>
<?php
require '../php/connect.php';

// Fungsi tambah kategori
if (isset($_POST['tambah_kategori'])) {
    $namaKategori = mysqli_real_escape_string($conn, $_POST['nama_kategori']);
    $sqlTambah = "INSERT INTO kategori (nama_kategori) VALUES ('$namaKategori')";
    mysqli_query($conn, $sqlTambah);
    header("Location:../admin/index.php"); // Ganti dengan nama file ini
    exit;
}

// Fungsi hapus kategori
if (isset($_GET['hapus_kategori']) && isset($_GET['id_kategori'])) {
    $idKategoriHapus = mysqli_real_escape_string($conn, $_GET['id_kategori']);
    $sqlHapus = "DELETE FROM kategori WHERE id_kategori = $idKategoriHapus";
    mysqli_query($conn, $sqlHapus);
    header("Location:../admin/index.php"); // Ganti dengan nama file ini
    exit;
}

// Fungsi edit kategori
if (isset($_POST['edit_kategori'])) {
    $idKategoriEdit = mysqli_real_escape_string($conn, $_POST['id_kategori']);
    $namaKategoriEdit = mysqli_real_escape_string($conn, $_POST['nama_kategori']);
    $sqlEdit = "UPDATE kategori SET nama_kategori = '$namaKategoriEdit' WHERE id_kategori = $idKategoriEdit";
    mysqli_query($conn, $sqlEdit);
    header("Location:../admin/index.php"); // Ganti dengan nama file ini
    exit;
}

$resultKategori = mysqli_query($conn, "SELECT * FROM kategori");
$kategoris = [];

while ($rowKategori = mysqli_fetch_assoc($resultKategori)) {
    $kategoris[] = $rowKategori;
}
// Ambil data gambar
$resultGambar = mysqli_query($conn, "SELECT * FROM gambar");
$gambar = [];

while ($rowGambar = mysqli_fetch_assoc($resultGambar)) {
    $gambar[] = $rowGambar;
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="../assets/webicon.png">
    <link rel="stylesheet" href="adminn.css">

    <script src="https://kit.fontawesome.com/9746b2e4c8.js" crossorigin="anonymous"></script>
    <title>Pinter1st</title>
</head>

<body>
    <nav class="header" id="header">
        <img src="../assets/logooo.png" alt="Logo">
        <h3 span style="color:var(--text);">Pinter<span style="color: var(--text_span);">1st</span></h3>
        <div class="navbar">
            <!-- <button class="login"><a href="login-page/login.php" style="text-decoration: none; color: #fff;">Log in</a></button>
            <button class="signup">Sign up</button> -->
            <?php
            if (isset($_SESSION['login_user'])) {
                echo "<div class='user-container'><a href='./php/req_logout.php'><button type='button' class='login'>Logout</button></a><h3 style='color:var(--text);' class='username'>Welcome!! <a href='user_profile.php'><span style='color:var(--text_span); font-weight: bolder;'>$username</span></a>  ^.^</h3></div>";
            } else {
                echo "<div class='user-container'><a href='../login-page/login.php'><button type='button' class='login'>Logout</button></a></div>";
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

    <section>
        <div class="report-container">
            <div class="report-header">
                <h1 class="recent-Articles">Data Tags</h1>
            </div>
            <form action="" method="post">
                <input type="text" name="nama_tag" placeholder="Nama Tag" required>
                <button type="submit" name="tambah_tag">Tambah</button>
            </form>
            <div class="report-body">
                <div class="items">
                    <table>
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Nama Tags</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $i = 1;
                            foreach ($tags as $tag) : ?>
                                <tr>
                                    <td><?php echo $i ?></td>
                                    <td><?php echo $tag["nama_tags"] ?></td>
                                    <td class="action">
                                        <form action="" method="post">
                                            <input type="hidden" name="id_tag" value="<?php echo $tag['id_tags']; ?>">
                                            <input type="text" name="nama_tag" value="<?php echo $tag['nama_tags']; ?>" required>
                                            <button type="submit" name="edit_tag">Edit</button>
                                        </form>
                                        <a href="?hapus&id=<?php echo $tag["id_tags"] ?>"><button name="hapus">Delete</button></a>
                                    </td>
                                </tr>
                            <?php $i++;
                            endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        </div>
        </div>

    </section>
    <section>
        <div class="report-container">
            <div class="report-header">
                <h1 class="recent-Articles">Data Kategori</h1>
            </div>
            <form action="" method="post">
                <input type="text" name="nama_kategori" placeholder="Nama Kategori" required>
                <button type="submit" name="tambah_kategori">Tambah</button>
            </form>
            <div class="report-body">
                <div class="items">
                    <table>
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Nama Kategori</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $i = 1;
                            foreach ($kategoris as $kategori) : ?>
                                <tr>
                                    <td><?php echo $i ?></td>
                                    <td><?php echo $kategori["nama_kategori"] ?></td>
                                    <td class="action">
                                        <form action="" method="post">
                                            <input type="hidden" name="id_kategori" value="<?php echo $kategori['id_kategori']; ?>">
                                            <input type="text" name="nama_kategori" value="<?php echo $kategori['nama_kategori']; ?>" required>
                                            <button type="submit" name="edit_kategori">Edit</button>
                                        </form>
                                        <a href="?hapus_kategori&id_kategori=<?php echo $kategori["id_kategori"] ?>"><button name="hapus_kategori">Delete</button></a>
                                    </td>
                                </tr>
                            <?php $i++;
                            endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        </div>
        </div>

    </section>

    <section>
        <div class="report-container">
            <div class="report-header">
                <h1 class="recent-Articles">Data Gambar</h1>
            </div>

            <div class="report-body">
                <div class="items">
                    <table>
                        <thead>
                            <tr>
                                <th>
                                    <h3 class="t-op">ID Gambar</h3>
                                </th>
                                <th>
                                    <h3 class="t-op">Gambar</h3>
                                </th>
                                <th>
                                    <h3 class="t-op">Nama Gambar</h3>
                                </th>
                                <th>
                                    <h3 class="t-op">Deskripsi Gambar</h3>
                                </th>
                                <th>
                                    <h3 class="t-op">Tanggal Gambar</h3>
                                </th>
                                <th>
                                    <h3 class="t-op">ID Tag</h3>
                                </th>
                                <th>
                                    <h3 class="t-op">ID User</h3>
                                </th>
                                <th>
                                    <h3 class="t-op">ID Kategori</h3>
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $i = 1;
                            foreach ($gambar as $gmbr) : ?>
                                <tr>
                                    <td><?php echo $gmbr['id_gambar']; ?></td>
                                    <td><img src="../assets/<?php echo $gmbr['gambar']; ?>" alt="" height="100px"></td>
                                    <td><?php echo $gmbr['nama_gambar']; ?> </td>
                                    <td><?php echo $gmbr['deskripsi_gambar']; ?></td>
                                    <td><?php echo $gmbr['tanggal_gambar']; ?></td>
                                    <td><?php echo $gmbr['id_tags']; ?></td>
                                    <td><?php echo $gmbr['id_user']; ?></td>
                                    <td><?php echo $gmbr['id_kategori']; ?></td>
                                    <td class="action">
                                        <a href="../php/admindel.php?id=<?php echo $gmbr['id_gambar'] ?>"><button class="delete-btn" onclick="confirm('Yakin ingin menghapus <?php echo $gmbr['nama_gambar'] ?>?')"><svg xmlns="http://www.w3.org/2000/svg" height="24" viewBox="0 -960 960 960" width="24" fill="white">
                                                    <path d="M280-120q-33 0-56.5-23.5T200-200v-520h-40v-80h200v-40h240v40h200v80h-40v520q0 33-23.5 56.5T680-120H280Zm400-600H280v520h400v-520ZM360-280h80v-360h-80v360Zm160 0h80v-360h-80v360ZM280-720v520-520Z" />
                                                </svg></button></a>
                                    </td>
                                </tr>
                            <?php $i++;
                            endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        </div>
        </div>

    </section>

    <footer style="color: var(--text);">
        <p>&copy; 2023 Pinter1st. All rights reserved.</p>
    </footer>
    </div>


    <script src="../java/mainn.js">
    </script>

</body>

</html>