<?php

$database = mysqli_connect("localhost", "root", "", "belajarphpnative");

if(mysqli_connect_error()){
    echo "Koneksi database gagal: " . mysqli_connect_error(); // Menggunakan titik untuk menggabungkan string
}
?>
