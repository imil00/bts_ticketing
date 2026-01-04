<?php
include "database.php";
session_start();

if (empty($_POST["username"]) || empty($_POST["password"])) {
    $error = urlencode("Username and password must be filled!");
    header("Location: ../index.php?pesan=$error");
    exit();
}

$username = mysqli_real_escape_string($con, $_POST["username"]);
$password = mysqli_real_escape_string($con, $_POST["password"]);

$query = "
    SELECT 
        u.id_user,
        u.username,
        u.level,
        p.id AS pengunjung_id,
        p.nama_lengkap
    FROM user u
    INNER JOIN pengunjung p ON u.id_pengunjung = p.id
    WHERE u.username = '$username'
    AND u.password = MD5('$password')
";

$result = mysqli_query($con, $query);

if (!$result) {
    $error = urlencode("Database error");
    header("Location: ../index.php?pesan=$error");
    exit();
}

if (mysqli_num_rows($result) == 1) {
    $data = mysqli_fetch_assoc($result);

    $_SESSION["username"] = $data["username"];
    $_SESSION["level"] = $data["level"];
    $_SESSION["user_id"] = $data["id_user"];
    $_SESSION["pengunjung_id"] = $data["pengunjung_id"];
    $_SESSION["nama_lengkap"] = $data["nama_lengkap"];

    if ($data["level"] == 1) {
        header("Location: ../admin.php");
    } else {
        header("Location: ../dashboard.php");
    }
    exit();
} else {
    $error = urlencode("Username or password is incorrect!");
    header("Location: ../index.php?pesan=$error");
    exit();
}

mysqli_close($con);
?>
