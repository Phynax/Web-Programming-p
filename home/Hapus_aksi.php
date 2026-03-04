<?php
session_start();
include "../database/database.php"; // Pastikan path ini benar

if (isset($_POST['id'])) {
    $id = intval($_POST['id']); // Ambil ID dari metode POST

    // Pastikan ID valid
    if ($id > 0) {
        // Siapkan pernyataan SQL untuk mencegah SQL injection
        $query = "DELETE FROM siswa WHERE id = ?";
        $stmt = mysqli_prepare($database, $query);

        if ($stmt) {
            // Ikat parameter ID
            mysqli_stmt_bind_param($stmt, "i", $id);

            // Eksekusi pernyataan yang telah disiapkan
            if (mysqli_stmt_execute($stmt)) {
                $_SESSION['sukses'] = "Data siswa berhasil dihapus!";
            } else {
                $_SESSION['error'] = "Data siswa gagal dihapus: " . mysqli_stmt_error($stmt);
            }

            // Tutup pernyataan
            mysqli_stmt_close($stmt);
        } else {
            $_SESSION['error'] = "Query tidak valid: " . mysqli_error($database);
        }
    } else {
        $_SESSION['error'] = "ID tidak valid!";
    }
} else {
    $_SESSION['error'] = "Aksi penghapusan tidak valid.";
}

// Redirect to index.php
header("Location: index.php");
exit();
?>
