<?php
include_once 'database.php';
session_start();
header('Content-Type: application/json');
if (!isset($_SESSION['username']) || !isset($_SESSION['level'])) {
    echo json_encode(['error' => 'Unauthorized']);
    exit();
}
if ($_SESSION['level'] == 1) {
    echo json_encode(['error' => 'Admin not allowed']);
    exit();
}
$pengunjung_id = $_SESSION['pengunjung_id'] ?? 0;

if ($pengunjung_id == 0) {
    echo json_encode(['error' => 'Session error']);
    exit();
}
if (!isset($_GET['id']) || $_GET['id'] == '') {
    echo json_encode(['error' => 'Ticket ID required']);
    exit();
}
$ticket_id = intval($_GET['id']);
$query = "
    SELECT t.*, p.nama_lengkap, p.email, p.nomor_telp
    FROM transaksi t
    INNER JOIN pengunjung p ON t.id = p.id
    WHERE t.id_transaksi = $ticket_id
    AND t.id = $pengunjung_id
    AND t.terhapus = '0'
";

$result = mysqli_query($con, $query);

if (!$result || mysqli_num_rows($result) == 0) {
    echo json_encode(['error' => 'Ticket not found']);
    exit();
}

$ticket = mysqli_fetch_assoc($result);
$seat_row = rand(1, 50);
$seat_number = rand(1, 30);
$gate = chr(rand(65, 69));
$ticket_colors = [
    'ARMY Zone' => ['primary' => '#8a2be2', 'secondary' => '#ff69b4'],
    'VIP Soundcheck' => ['primary' => '#ff69b4', 'secondary' => '#ff1493'],
    'Meet & Greet Package' => ['primary' => '#ffd700', 'secondary' => '#ff8c00'],
    'VIP A' => ['primary' => '#00bfff', 'secondary' => '#0080ff']
];
$color = $ticket_colors[$ticket['kelas']] ?? $ticket_colors['ARMY Zone'];

$response = [
    'success' => true,
    'ticket' => [
        'id_transaksi' => $ticket['id_transaksi'],
        'kelas' => $ticket['kelas'],
        'jml_kursi' => $ticket['jml_kursi'],
        'bayar' => $ticket['bayar'],
        'tanggal_transaksi' => date('F d, Y H:i', strtotime($ticket['tanggal_transaksi'])),
        'nama_lengkap' => $ticket['nama_lengkap'],
        'email' => $ticket['email'],
        'nomor_telp' => $ticket['nomor_telp'],
        'army_id' => str_pad($ticket['id'], 5, '0', STR_PAD_LEFT),
        'seat_row' => $seat_row,
        'seat_number' => $seat_number,
        'gate' => $gate,
        'price_per_ticket' => number_format($ticket['bayar'] / $ticket['jml_kursi'], 0, ',', '.'),
        'total_formatted' => number_format($ticket['bayar'], 0, ',', '.'),
        'colors' => $color
    ]
];
echo json_encode($response);
mysqli_close($con);
?>