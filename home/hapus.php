<?php

include '../database/database.php';

$id = $_GET['id'];

mysqli_query($database, "delete from siswa where id = '$id'");

header('location:index.php');