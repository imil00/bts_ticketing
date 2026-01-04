<?php
include "database.php";
session_start();
if (!isset($_SESSION['level']) || $_SESSION['level'] != 1) {
    header("Location: ../index.php");
    exit();
}
if (!isset($_GET['id'])) {
    header("Location: ../admin.php");
    exit();
}
$id = (int) $_GET['id'];
$query_transaksi = "UPDATE transaksi SET terhapus = '1' WHERE id = $id";
$query_user      = "DELETE FROM user WHERE id_pengunjung = $id";
$query_pengunjung = "DELETE FROM pengunjung WHERE id = $id";
mysqli_autocommit($con, false);
$eksekusi1 = mysqli_query($con, $query_transaksi);
$eksekusi2 = mysqli_query($con, $query_user);
$eksekusi3 = mysqli_query($con, $query_pengunjung);

if ($eksekusi1 && $eksekusi2 && $eksekusi3) {
    mysqli_commit($con);
    header("Location: ../admin.php#users?success=User berhasil dihapus");
} else {
    mysqli_rollback($con);
    header("Location: ../admin.php#users?error=Gagal menghapus user");
}
mysqli_autocommit($con, true);
mysqli_close($con);
?>
