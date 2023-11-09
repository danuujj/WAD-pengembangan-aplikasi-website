<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Penjualan Album Musik</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }

        form {
            background-color: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        table, th, td {
            border: 1px solid #ddd;
            text-align: left;
        }

        th, td {
            padding: 15px;
        }

        th {
            background-color: #f2f2f2;
        }

        h2 {
            text-align: center;
        }

        .notification {
            padding: 15px;
            margin-bottom: 20px;
            background-color: #4CAF50;
            color: white;
            border-radius: 5px;
        }

        .delete-btn {
            background-color: #f44336;
            color: white;
            padding: 10px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }

        .delete-btn:hover {
            background-color: #d32f2f;
        }
    </style>
</head>
<body>

<?php

$host = "localhost:3308";
$user = "username"; 
$pass = "password"; 
$db = "penjualan_album"; 

$koneksi = mysqli_connect($host, $user, $pass, $db);

if (!$koneksi) {
    die("Koneksi gagal: " . mysqli_connect_error());
}


if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nama = $_POST['nama'];
    $kode_barang = $_POST['kode_barang'];
    $label = $_POST['label'];
    $harga = $_POST['harga'];
    $stok = $_POST['stok'];

    $sql = "INSERT INTO barang (nama, kode_barang, label, harga, stok) VALUES ('$nama', '$kode_barang', '$label', $harga, $stok)";

    if (mysqli_query($koneksi, $sql)) {
        echo '<div class="notification">Data berhasil disimpan.</div>';
    } else {
        echo '<div class="notification" style="background-color: #f44336;">Gagal menyimpan data: ' . mysqli_error($koneksi) . '</div>';
    }
}


?>

    <form action="" method="post">
        <h2>Input Data Barang</h2>
        <label for="nama">Nama:</label>
        <input type="text" name="nama" required><br>
        <label for="kode_barang">Kode Barang:</label>
        <input type="text" name="kode_barang" required><br>
        <label for="label">Label:</label>
        <input type="text" name="label" required><br>
        <label for="harga">Harga:</label>
        <input type="number" name="harga" required><br>
        <label for="stok">Stok:</label>
        <input type="number" name="stok" required><br>
        <input type="submit" value="Simpan">
    </form>

<?php

if (isset($_GET['edit'])) {
    $id = $_GET['edit'];
    $result = mysqli_query($koneksi, "SELECT * FROM barang WHERE id=$id");

    while ($row = mysqli_fetch_array($result)) {
        $nama = $row['nama'];
        $kode_barang = $row['kode_barang'];
        $label = $row['label'];
        $harga = $row['harga'];
        $stok = $row['stok'];
    }

    echo '
    <form action="update.php" method="post">
        <h2>Ubah Data Barang</h2>
        <input type="hidden" name="id" value="' . $id . '">
        <label for="nama">Nama:</label>
        <input type="text" name="nama" value="' . $nama . '" required><br>
        <label for="kode_barang">Kode Barang:</label>
        <input type="text" name="kode_barang" value="' . $kode_barang . '" required><br>
        <label for="label">Label:</label>
        <input type="text" name="label" value="' . $label . '" required><br>
        <label for="harga">Harga:</label>
        <input type="number" name="harga" value="' . $harga . '" required><br>
        <label for="stok">Stok:</label>
        <input type="number" name="stok" value="' . $stok . '" required><br>
        <input type="submit" value="Update">
    </form>';
}


if ($_SERVER['REQUEST_METHOD'] == 'GET' && isset($_GET['status'])) {
    if ($_GET['status'] == 'update_success') {
        echo '<div class="notification">Data berhasil diubah.</div>';
    } elseif ($_GET['status'] == 'update_failed') {
        echo '<div class="notification" style="background-color: #f44336;">Gagal mengubah data: ' . mysqli_error($koneksi) . '</div>';
    }
}

if ($_SERVER['REQUEST_METHOD'] == 'GET' && isset($_GET['delete'])) {
    $id = $_GET['delete'];
    $result = mysqli_query($koneksi, "DELETE FROM barang WHERE id=$id");

    if ($result) {
        echo '<div class="notification">Data berhasil dihapus.</div>';
    } else {
        echo '<div class="notification" style="background-color: #f44336;">Gagal menghapus data: ' . mysqli_error($koneksi) . '</div>';
    }
}


if ($_SERVER['REQUEST_METHOD'] == 'GET' && isset($_GET['status'])) {
    if ($_GET['status'] == 'delete_success') {
        echo '<div class="notification">Data berhasil dihapus.</div>';
    } elseif ($_GET['status'] == 'delete_failed') {
        echo '<div class="notification" style="background-color: #f44336;">Gagal menghapus data: ' . mysqli_error($koneksi) . '</div>';
    }
}


$result = mysqli_query($koneksi, "SELECT * FROM barang");
?>

    <table>
        <tr>
            <th>ID</th>
            <th>Nama</th>
            <th>Kode Barang</th>
            <th>Label</th>
            <th>Harga</th>
            <th>Stok</th>
            <th>Aksi</th>
        </tr>

<?php
while ($row = mysqli_fetch_array($result)) {
    echo '<tr>';
    echo '<td>' . $row['id'] . '</td>';
    echo '<td>' . $row['nama'] . '</td>';
    echo '<td>' . $row['kode_barang'] . '</td>';
    echo '<td>' . $row['label'] . '</td>';
    echo '<td>' . $row['harga'] . '</td>';
    echo '<td>' . $row['stok'] . '</td>';
    echo '<td><a href="?edit=' . $row['id'] . '">Edit</a> | <a href="?delete=' . $row['id'] . '" class="delete-btn">Delete</a></td>';
    echo '</tr>';
}
?>

    </table>
</body>
</html>
