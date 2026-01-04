<?php
include "database.php";
session_start();

if (!isset($_SESSION['username']) || !isset($_SESSION['level']) || $_SESSION['level'] == 1) {
    header("Location: ../index.php");
    exit();
}
$user_id = $_SESSION['user_id'] ?? 0;
$pengunjung_id = $_SESSION['pengunjung_id'] ?? 0;

if ($pengunjung_id == 0 && $user_id > 0) {
    $q_user = mysqli_query($con, "SELECT id_pengunjung FROM user WHERE id_user = $user_id");
    if ($q_user && mysqli_num_rows($q_user) > 0) {
        $data_user = mysqli_fetch_assoc($q_user);
        $pengunjung_id = $data_user['id_pengunjung'];
        $_SESSION['pengunjung_id'] = $pengunjung_id;
    }
}
if ($pengunjung_id == 0) {
    echo "<script>
        alert('Session bermasalah, silakan login ulang');
        window.location.href = '../index.php';
    </script>";
    exit();
}
$cek_pengunjung = mysqli_query($con, "SELECT id FROM pengunjung WHERE id = $pengunjung_id");
if (!$cek_pengunjung || mysqli_num_rows($cek_pengunjung) == 0) {
    echo "<script>
        alert('Data pengunjung tidak ditemukan');
        window.location.href = '../dashboard.php';
    </script>";
    exit();
}
if (isset($_POST['transaksi'])) {

    $id_kelas   = intval($_POST['id_kelas']);
    $kelas      = mysqli_real_escape_string($con, $_POST['kelas']);
    $jml_kursi  = intval($_POST['jml_kursi']);
    $biaya      = intval($_POST['biaya']);

    $total_bayar = $biaya * $jml_kursi;

    $max_tiket = 4;
    if ($id_kelas == 2) $max_tiket = 2;
    if ($id_kelas == 3) $max_tiket = 1;

    if ($jml_kursi > $max_tiket) {
        header("Location: ../dashboard.php?error=Maksimal $max_tiket tiket untuk kelas ini");
        exit();
    }

    $q_kapasitas = mysqli_query($con, "SELECT kapasitas FROM kelas_tiket WHERE id_kelas = $id_kelas");
    $data_kapasitas = mysqli_fetch_assoc($q_kapasitas);
    $kapasitas = $data_kapasitas['kapasitas'] ?? 0;

    $q_terjual = mysqli_query(
        $con,
        "SELECT SUM(jml_kursi) AS total FROM transaksi 
         WHERE id_kelas = $id_kelas AND terhapus = '0'"
    );
    $data_terjual = mysqli_fetch_assoc($q_terjual);
    $total_terjual = $data_terjual['total'] ?? 0;

    if (($total_terjual + $jml_kursi) > $kapasitas) {
        header("Location: ../dashboard.php?error=Tiket untuk kelas ini sudah habis");
        exit();
    }

    $insert = mysqli_query(
        $con,
        "INSERT INTO transaksi 
        (id, id_kelas, kelas, jml_kursi, bayar, tanggal_transaksi, terhapus)
        VALUES 
        ($pengunjung_id, $id_kelas, '$kelas', $jml_kursi, $total_bayar, NOW(), '0')"
    );

    if ($insert) {
        header("Location: ../dashboard.php?success=Pembelian tiket berhasil");
    } else {
        header("Location: ../dashboard.php?error=Gagal melakukan transaksi");
    }
} else {
    header("Location: ../dashboard.php");
}
mysqli_close($con);
?>
