# lab3web
Stephen Pratama Kurnia TI.22.A5 312210635
## Database
### Tabel

![MySQLWorkbench_o5csRpxTpF](https://github.com/steprtm/lab3web/assets/129705802/1027e387-7f1f-4097-b21d-52bdf4aa7977)


Hasil:

![MySQLWorkbench_85pJf4jHiX](https://github.com/steprtm/lab3web/assets/129705802/5ead0590-6e62-4eff-a659-e531fdab9fb3)

## PHP
### Koneksi.php
![Code_S5yf5XtM3X](https://github.com/steprtm/lab3web/assets/129705802/d1b6a856-1c2e-46c3-b10a-bb87cea97580)

Hasil:

![firefox_Zv5W8gEEJq](https://github.com/steprtm/lab3web/assets/129705802/9724394c-8767-4623-a30f-5399a3f9c11a)


### index.php
![Code_JVB25y91L3](https://github.com/steprtm/lab3web/assets/129705802/b29b609b-9f7e-4fd1-968d-66e5d78388db)

Hasil:

![firefox_WLJ2nEAWZv](https://github.com/steprtm/lab3web/assets/129705802/d29c88b0-447d-4ff1-89e1-646abf9e3440)



### tambah.php
```
<?php
error_reporting(E_ALL);
include_once 'koneksi.php';
if (isset($_POST['submit']))
{
$nama = $_POST['nama'];
$kategori = $_POST['kategori'];
$harga_jual = $_POST['harga_jual'];
$harga_beli = $_POST['harga_beli'];
$stok = $_POST['stok'];
$file_gambar = $_FILES['file_gambar'];
$gambar = null;
if ($file_gambar['error'] == 0)
{
$filename = str_replace(' ', '_',$file_gambar['name']);
$destination = dirname(__FILE__) .'/gambar/' . $filename;
if(move_uploaded_file($file_gambar['tmp_name'], $destination))
{
$gambar = 'gambar/' . $filename;;
}
}
$sql = 'INSERT INTO data_barang (nama, kategori,harga_jual, harga_beli,
stok, gambar) ';
$sql .= "VALUE ('{$nama}', '{$kategori}','{$harga_jual}',
'{$harga_beli}', '{$stok}', '{$gambar}')";
$result = mysqli_query($conn, $sql);
header('location: index.php');
}
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <link href="style.css" rel="stylesheet" type="text/css" />
    <title>Tambah Barang</title>
  </head>
  <body>
    <div class="container">
      <h1>Tambah Barang</h1>
      <div class="main">
        <form
          method="post"
          action="tambah.php"
          enctype="multipart/form-
data"
        >
          <div class="input">
            <label>Nama Barang</label>
            <input type="text" name="nama" />
          </div>
          <div class="input">
            <label>Kategori</label>
            <select name="kategori">
              <option value="Komputer">Komputer</option>
              <option value="Elektronik">Elektronik</option>
              <option value="Hand Phone">Hand Phone</option>
            </select>
          </div>
          <div class="input">
            <label>Harga Jual</label>
            <input type="text" name="harga_jual" />
          </div>
          <div class="input">
            <label>Harga Beli</label>
            <input type="text" name="harga_beli" />
          </div>
          <div class="input">
            <label>Stok</label>
            <input type="text" name="stok" />
          </div>
          <div class="input">
            <label>File Gambar</label>
            <input type="file" name="file_gambar" />
          </div>
          <div class="submit">
            <input type="submit" name="submit" value="Simpan" />
          </div>
        </form>
      </div>
    </div>
  </body>
</html>
```

Hasil:

![firefox_20yBI4fSuM](https://github.com/steprtm/lab3web/assets/129705802/3c36aba0-af90-4ad8-b1da-13ad119304b3)


### ubah.php

```
<?php
include_once 'koneksi.php';


if (!isset($_GET['id']) || empty($_GET['id'])) {
    die('Error: ID is required to edit an entry. <a href="index.php">Back to list</a>');
}

$id = $_GET['id'];


$sql = "SELECT * FROM data_barang WHERE id_barang = ?";
$stmt = mysqli_prepare($conn, $sql);
mysqli_stmt_bind_param($stmt, "i", $id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

if ($data = mysqli_fetch_assoc($result)) {
    
} else {
    die('Error: Data not found with ID ' . htmlspecialchars($id) . '. <a href="index.php">Back to list</a>');
}


if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['submit'])) {
    $nama = $_POST['nama'] ?? '';
    $kategori = $_POST['kategori'] ?? '';
    $harga_jual = $_POST['harga_jual'] ?? '';
    $harga_beli = $_POST['harga_beli'] ?? '';
    $stok = $_POST['stok'] ?? '';
    $file_gambar = $_FILES['file_gambar'] ?? null;
    $gambar = $data['gambar']; 

    if ($file_gambar && $file_gambar['error'] == 0) {
        $filename = str_replace(' ', '_', $file_gambar['name']);
        $destination = dirname(__FILE__) . '/gambar/' . $filename;
        if (move_uploaded_file($file_gambar['tmp_name'], $destination)) {
            $gambar = 'gambar/' . $filename;
        }
    }

    $update_sql = "UPDATE data_barang SET nama = ?, kategori = ?, harga_jual = ?, harga_beli = ?, stok = ?, gambar = ? WHERE id_barang = ?";
    $update_stmt = mysqli_prepare($conn, $update_sql);
    mysqli_stmt_bind_param($update_stmt, "ssiiisi", $nama, $kategori, $harga_jual, $harga_beli, $stok, $gambar, $id);
    if (mysqli_stmt_execute($update_stmt)) {
        header('Location: index.php');
        exit;
    } else {
        echo "Error updating record: " . mysqli_error($conn);
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Edit Barang</title>
    <link href="style.css" rel="stylesheet" type="text/css">
</head>
<body>
<div class="container">
    <h1>Edit Barang</h1>
    <form method="post" action="" enctype="multipart/form-data">
        <label for="nama">Nama Barang:</label>
        <input type="text" name="nama" value="<?= htmlspecialchars($data['nama']); ?>" required><br>

        <label for="kategori">Kategori:</label>
        <select name="kategori" required>
            <option value="Komputer" <?= $data['kategori'] === 'Komputer' ? 'selected' : ''; ?>>Komputer</option>
            <option value="Elektronik" <?= $data['kategori'] === 'Elektronik' ? 'selected' : ''; ?>>Elektronik</option>
            <option value="Hand Phone" <?= $data['kategori'] === 'Hand Phone' ? 'selected' : ''; ?>>Hand Phone</option>
        </select><br>

        <label for="harga_jual">Harga Jual:</label>
        <input type="number" name="harga_jual" value="<?= htmlspecialchars($data['harga_jual']); ?>" required><br>

        <label for="harga_beli">Harga Beli:</label>
        <input type="number" name="harga_beli" value="<?= htmlspecialchars($data['harga_beli']); ?>" required><br>

        <label for="stok">Stok:</label>
        <input type="number" name="stok" value="<?= htmlspecialchars($data['stok']); ?>" required><br>

        <label for="file_gambar">File Gambar:</label>
        <input type="file" name="file_gambar"><br>
        <img src="<?= htmlspecialchars($data['gambar']); ?>" alt="Current Image" width="100"><br>

        <input type="submit" name="submit" value="Update">
    </form>
</div>
</body>
</html>
```

Hasil:

![firefox_hjXHqzAuwx](https://github.com/steprtm/lab3web/assets/129705802/b93e944d-aaef-4e9b-8b07-57ab812326d6)








