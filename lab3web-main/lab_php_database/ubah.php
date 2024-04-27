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
