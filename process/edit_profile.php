<?php
include "database.php";
session_start();

if (!isset($_SESSION['username']) || !isset($_SESSION['level']) || $_SESSION['level'] == 1) {
    header("Location: ../index.php");
    exit();
}
if (isset($_POST['update_profile'])) {

    $id     = intval($_POST['id']);
    $nama   = mysqli_real_escape_string($con, $_POST['nama']);
    $email  = mysqli_real_escape_string($con, $_POST['email']);
    $nomor  = mysqli_real_escape_string($con, $_POST['nomor']);
    $alamat = mysqli_real_escape_string($con, $_POST['alamat']);
    $cek = mysqli_query($con, "
        SELECT id 
        FROM pengunjung 
        WHERE email = '$email' AND id != $id
    ");

    if (mysqli_num_rows($cek) > 0) {
        header("Location: ../dashboard.php?error=Email already exists");
        exit();
    }
    $update = mysqli_query($con, "
        UPDATE pengunjung SET
            nama_lengkap = '$nama',
            email = '$email',
            nomor_telp = '$nomor',
            alamat = '$alamat',
            updated_at = NOW()
        WHERE id = $id
    ");

    if ($update) {
        header("Location: ../dashboard.php?success=Profile updated successfully");
    } else {
        header("Location: ../dashboard.php?error=Failed to update profile");
    }
} else {
    header("Location: ../dashboard.php");
}
mysqli_close($con);
?>
