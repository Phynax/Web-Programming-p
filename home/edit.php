<?php
session_start();
include "../database/database.php"; // Pastikan path ini benar

// Cek apakah ID telah diberikan
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    $_SESSION['error'] = "ID tidak valid!";
    header("Location: index.php");
    exit();
}

$id = intval($_GET['id']); // Ambil ID dari URL

// Ambil data siswa berdasarkan ID
$result = mysqli_query($database, "SELECT * FROM siswa WHERE id = $id");

if (!$result || mysqli_num_rows($result) == 0) {
    $_SESSION['error'] = "Siswa tidak ditemukan!";
    header("Location: index.php");
    exit();
}

$siswa = mysqli_fetch_assoc($result); // Ambil data siswa

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Proses form edit
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
        // Update data siswa
        $updateQuery = "UPDATE siswa SET nama='$nama', nis='$nis', alamat='$alamat' WHERE id=$id";

        if (mysqli_query($database, $updateQuery)) {
            $_SESSION['sukses'] = "Data siswa berhasil diperbarui!";
            header("Location: index.php");
            exit();
        } else {
            $_SESSION['error'] = "Data siswa gagal diperbarui: " . mysqli_error($database);
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Siswa</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        function confirmUpdate(event) {
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

            // Tampilkan dialog konfirmasi menggunakan SweetAlert2
            Swal.fire({
                title: 'Apakah Anda yakin?',
                text: "Anda akan mengubah data siswa ini!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Ya, ubah!',
                cancelButtonText: 'Tidak, batalkan'
            }).then((result) => {
                if (result.isConfirmed) {
                    form.submit(); // Jika pengguna mengonfirmasi, kirim form
                }
            });
        }
    </script>
</head>
<body>
    <div class="container mt-4">
        <h2>Edit Siswa</h2>
        <?php if (isset($_SESSION['error'])): ?>
            <div class="alert alert-danger">
                <?php echo $_SESSION['error']; unset($_SESSION['error']); ?>
            </div>
        <?php endif; ?>

        <form method="POST" action="" onsubmit="confirmUpdate(event)">
            <div class="form-group">
                <label for="nama">Nama</label>
                <input type="text" class="form-control" id="nama" name="nama" value="<?php echo htmlspecialchars($siswa['nama']); ?>" required>
            </div>
            <div class="form-group">
                <label for="nis">NIS</label>
                <input type="text" class="form-control" id="nis" name="nis" value="<?php echo htmlspecialchars($siswa['nis']); ?>" required>
            </div>
            <div class="form-group">
                <label for="alamat">Alamat</label>
                <textarea class="form-control" id="alamat" name="alamat" required><?php echo htmlspecialchars($siswa['alamat']); ?></textarea>
            </div>
            <button type="submit" class="btn btn-primary">Update</button>
            <a href="index.php" class="btn btn-secondary">Batal</a>
        </form>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</body>
</html>
