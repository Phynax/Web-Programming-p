<?php
session_start();
include "../database/database.php"; // Pastikan path ini benar

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Proses form tambah
    $nama = htmlspecialchars(trim($_POST['nama']));
    $nis = htmlspecialchars(trim($_POST['nis']));
    $alamat = htmlspecialchars(trim($_POST['alamat']));

    // Validasi: Pastikan semua field terisi
    if (empty($nama) || empty($nis) || empty($alamat)) {
        $_SESSION['error'] = "Semua field harus diisi!";
    } else if (!is_numeric($nis)) { // Validasi NIS harus angka
        echo "<script>
                Swal.fire({
                    icon: 'error',
                    title: 'Kolom Tidak Bisa Diisi Dengan Huruf',
                    text: 'Silakan masukkan NIS yang valid!'
                }).then(() => {
                    window.history.back(); // Kembali ke halaman sebelumnya
                });
              </script>";
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
                header("Location: index.php");
                exit();
            } else {
                $_SESSION['error'] = "Data siswa gagal ditambahkan: " . mysqli_stmt_error($stmt);
            }

            // Tutup statement
            mysqli_stmt_close($stmt);
        } else {
            $_SESSION['error'] = "Query tidak valid: " . mysqli_error($database);
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Siswa</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        function validateForm(event) {
            event.preventDefault(); // Mencegah pengiriman form secara default
            const form = event.target;

            // Cek NIS untuk validasi
            const nisInput = document.getElementById('nis').value;

            // Validasi: NIS tidak boleh berisi huruf
            if (isNaN(nisInput)) {
                Swal.fire({
                    icon: 'error',
                    title: 'Kolom Tidak Bisa Diisi Dengan Huruf',
                    text: 'Silakan masukkan NIS yang valid!'
                });
                return; // Hentikan eksekusi
            }

            // Jika semua validasi lulus, kirim form
            form.submit();
        }
    </script>
</head>
<body>
    <div class="container mt-4">
        <h2>Tambah Siswa</h2>
        <?php if (isset($_SESSION['error'])): ?>
            <div class="alert alert-danger">
                <?php echo $_SESSION['error']; unset($_SESSION['error']); ?>
            </div>
        <?php endif; ?>

        <form method="POST" action="" onsubmit="validateForm(event)">
            <div class="form-group">
                <label for="nama">Nama</label>
                <input type="text" class="form-control" id="nama" name="nama" required>
            </div>
            <div class="form-group">
                <label for="nis">NIS</label>
                <input type="text" class="form-control" id="nis" name="nis" required>
            </div>
            <div class="form-group">
                <label for="alamat">Alamat</label>
                <textarea class="form-control" id="alamat" name="alamat" required></textarea>
            </div>
            <button type="submit" class="btn btn-primary">Tambah</button>
            <a href="index.php" class="btn btn-secondary">Batal</a>
        </form>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</body>
</html>
