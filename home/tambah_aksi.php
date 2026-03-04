<?php
session_start();
include "../database/database.php"; // Pastikan path ini benar

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nama = htmlspecialchars(trim($_POST['nama']));
    $nis = htmlspecialchars(trim($_POST['nis']));
    $alamat = htmlspecialchars(trim($_POST['alamat']));

    // Validasi: Pastikan semua field terisi
    if (empty($nama) || empty($nis) || empty($alamat)) {
        $_SESSION['error'] = "Semua field harus diisi!";
        header("Location: tambah.php");
        exit();
    } else if (!is_numeric($nis)) { // Validasi NIS harus angka
        $_SESSION['error'] = "Kolom NIS tidak boleh berisi huruf!";
        header("Location: tambah.php");
        exit();
    } else {
        // Insert data siswa
        $query = "INSERT INTO siswa (nama, nis, alamat) VALUES (?, ?, ?)";
        $stmt = mysqli_prepare($database, $query);

        if ($stmt) {
            // Bind parameter
            mysqli_stmt_bind_param($stmt, "sss", $nama, $nis, $alamat);

            // Eksekusi statement
            if (mysqli_stmt_execute($stmt)) {
                $_SESSION['sukses'] = "Data siswa berhasil ditambahkan!";
            } else {
                $_SESSION['error'] = "Data siswa gagal ditambahkan: " . mysqli_stmt_error($stmt);
            }

            // Tutup statement
            mysqli_stmt_close($stmt);
        } else {
            $_SESSION['error'] = "Query tidak valid: " . mysqli_error($database);
        }
    }

    // Redirect ke tambah.php jika ada kesalahan
    header("Location: tambah.php");
    exit();
} else {
    $_SESSION['error'] = "Aksi tidak valid.";
    header("Location: tambah.php");
    exit();
}
