<?php
include "database.php";
session_start();
if (!isset($_SESSION['level']) || $_SESSION['level'] != 1) {
    header("Location: ../index.php");
    exit();
}
if (!isset($_GET['id'])) {
    header("Location: ../admin.php#transactions");
    exit();
}
$id = (int) $_GET['id'];
$query = "UPDATE transaksi SET terhapus = '1' WHERE id_transaksi = $id";
mysqli_query($con, $query);
header("Location: ../admin.php#transactions");
mysqli_close($con);
?>
