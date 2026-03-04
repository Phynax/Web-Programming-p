<?php
include '../database/database.php';

if (isset($_POST['update'])) {
    $id = $_POST['id'];
    $nama = $_POST['nama'];
    $nis = $_POST['nis'];
    $alamat = $_POST['alamat'];

    if (empty($nama) || empty($nis) || empty($alamat)) {
        echo "<script>alert('Semua field harus diisi!');</script>";
        echo "<script>location='edit.php?id=$id';</script>";
        exit;
    }

    $stmt = $database->prepare("UPDATE  SET nama = ?, nis = ?, alamat = ? WHERE id = ?");
    $stmt->bind_param("sssi", $nama, $nis, $alamat, $id); 

    if ($stmt->execute()) {
        echo "<script>alert('Data berhasil diperbarui!');</script>";
        echo "<script>location='index.php';</script>";
    } else {
        echo "<script>alert('Data gagal diperbarui: " . $stmt->error . "');</script>";
        echo "<script>location='edit.php?id=$id';</script>";
    }

    $stmt->close();
}

$database->close();
?>
