<?php
include 'process/database.php';
session_start();
if(!isset($_SESSION['username']) || !isset($_SESSION['level'])) {
    header("Location: index.php");
    exit();
}
if($_SESSION['level'] == 1) {
    header("Location: admin.php");
    exit();
}
$username = $_SESSION['username'];
$pengunjung_id = $_SESSION['pengunjung_id'] ?? 0;

if($pengunjung_id == 0) {
    echo "<script>alert('Session error. Silakan login kembali.'); window.location.href = 'process/logout.php';</script>";
    exit();
}
$query = "SELECT p.* FROM pengunjung p 
          INNER JOIN user u ON p.id = u.id_pengunjung 
          WHERE u.username = '$username' LIMIT 1";
$result_query = mysqli_query($con, $query);

if(!$result_query || mysqli_num_rows($result_query) == 0) {
    echo "<script>alert('User tidak ditemukan.'); window.location.href = 'process/logout.php';</script>";
    exit();
}
$result = mysqli_fetch_array($result_query);
$nama_lengkap = $result['nama_lengkap'];
$email = $result['email'];
$phone = $result['nomor_telp'];
$query_count = "SELECT COUNT(*) as total FROM transaksi WHERE id = $pengunjung_id AND terhapus = '0'";
$result_count = mysqli_query($con, $query_count);
$row_count = mysqli_fetch_assoc($result_count);
$total_transactions = $row_count['total'];
$query_tickets = "SELECT SUM(jml_kursi) as total FROM transaksi WHERE id = $pengunjung_id AND terhapus = '0'";
$result_tickets = mysqli_query($con, $query_tickets);
$row_tickets = mysqli_fetch_assoc($result_tickets);
$total_tickets = $row_tickets['total'] ?? 0;
$query_spent = "SELECT SUM(bayar) as total FROM transaksi WHERE id = $pengunjung_id AND terhapus = '0'";
$result_spent = mysqli_query($con, $query_spent);
$row_spent = mysqli_fetch_assoc($result_spent);
$total_spent = $row_spent['total'] ?? 0;
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Tickets - BTS LOVE YOURSELF</title>
    <link rel="stylesheet" href="assets/css/style.css">
    <link rel="icon" href="assets/img/favicon.jpg">
    <style>
        .ticket-actions {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 10px;
            margin-top: 20px;
        }
        .btn-view-details {
            background: linear-gradient(135deg, #3498db, #2980b9);
            color: white;
        }
        .ticket-status {
            margin-top: 15px;
            padding: 12px;
            background: linear-gradient(135deg, rgba(52, 152, 219, 0.1), rgba(41, 128, 185, 0.1));
            border-radius: 10px;
            border-left: 4px solid #3498db;
            text-align: center;
        }
        .ticket-status p {
            margin: 0;
            color: #3498db;
            font-weight: bold;
            font-size: 0.9rem;
        }
    </style>
</head>
<body>
    <header class="header">
        <div class="container">
            <nav class="navbar">
                <div class="logo"><a href="dashboard.php"><img src="assets/img/logo.png" alt="BTS LOVE YOURSELF"></a></div>
                <button class="menu-toggle" id="menuToggle"><i class="icon icon-menu"></i></button>
                <ul class="nav-menu" id="navMenu">
                    <li><a href="dashboard.php"><i class="icon icon-home"></i> Dashboard</a></li>
                    <li><a href="my_tickets.php" style="background: rgba(255,255,255,0.2); padding: 8px 20px; border-radius: 25px;"><i class="icon icon-ticket"></i> My Tickets</a></li>
                    <li><a href="process/logout.php"><i class="icon icon-logout"></i> Logout</a></li>
                </ul>
            </nav>
        </div>
    </header>
    <div id="mobile-body-overlay" style="display: none;"></div>
    <main style="padding-top: 100px; min-height: 80vh; background: var(--bts-dark-secondary);">
        <section style="padding: 60px 0;">
            <div class="container">
                <div class="section-header">
                    <h2>Tiket Konser BTS Saya</h2>
                    <p>Semua tiket yang telah Anda beli dalam satu tempat</p>
                </div>
                <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 20px; margin-bottom: 40px;">
                    <div style="background: var(--bts-dark-tertiary); padding: 25px; border-radius: 15px; text-align: center; border: 2px solid rgba(138, 43, 226, 0.3);">
                        <h3 style="margin-bottom: 10px; font-size: 2.5rem; background: var(--bts-gradient); -webkit-background-clip: text; -webkit-text-fill-color: transparent;">
                            <?php echo $total_transactions; ?>
                        </h3>
                        <p style="color: var(--text-muted);">Total Transaksi</p>
                    </div>           
                    <div style="background: var(--bts-dark-tertiary); padding: 25px; border-radius: 15px; text-align: center; border: 2px solid rgba(138, 43, 226, 0.3);">
                        <h3 style="margin-bottom: 10px; font-size: 2.5rem; background: var(--bts-gradient); -webkit-background-clip: text; -webkit-text-fill-color: transparent;">
                            <?php echo $total_tickets; ?>
                        </h3>
                        <p style="color: var(--text-muted);">Total Tiket</p>
                    </div>                   
                    <div style="background: var(--bts-dark-tertiary); padding: 25px; border-radius: 15px; text-align: center; border: 2px solid rgba(138, 43, 226, 0.3);">
                        <h3 style="margin-bottom: 10px; font-size: 1.8rem; background: var(--bts-gradient); -webkit-background-clip: text; -webkit-text-fill-color: transparent;">
                            Rp <?php echo number_format($total_spent, 0, ',', '.'); ?>
                        </h3>
                        <p style="color: var(--text-muted);">Total Pembelanjaan</p>
                    </div>
                </div>

                <?php
                $query = "SELECT * FROM transaksi WHERE id = $pengunjung_id AND terhapus = '0' ORDER BY tanggal_transaksi DESC";
                $result = mysqli_query($con, $query);
                $ticket_count = mysqli_num_rows($result);
                if ($ticket_count > 0) {
                    echo '<div class="tickets-grid">';
                    while($row = mysqli_fetch_assoc($result)) {
                        $ticket_id = $row["id_transaksi"];
                        $formatted_date = date('d M Y, H:i', strtotime($row["tanggal_transaksi"]));
                        $total = 'Rp ' . number_format($row["bayar"], 0, ',', '.'); 
                        echo '
                        <div class="ticket-card">
                            <div class="ticket-card-header" style="background: var(--bts-gradient);">
                                <div style="display: flex; justify-content: space-between; align-items: center;">
                                    <div style="text-align: left;">
                                        <h3 style="margin: 0;">'.htmlspecialchars($row["kelas"]).'</h3>
                                        <p style="margin: 5px 0 0 0; opacity: 0.9; font-size: 0.9rem;">TRX'.str_pad($ticket_id, 6, '0', STR_PAD_LEFT).'</p>
                                    </div>
                                    <div class="ticket-badge" style="background: rgba(255,255,255,0.3); font-size: 1.2rem;">'.$row["jml_kursi"].' 🎫</div>
                                </div>
                            </div>
                            <div class="ticket-card-body">
                                <div style="background: rgba(138, 43, 226, 0.1); padding: 20px; border-radius: 15px; margin-bottom: 20px;">
                                    <div style="display: grid; grid-template-columns: repeat(2, 1fr); gap: 20px; text-align: center;">
                                        <div>
                                            <p style="color: var(--text-muted); font-size: 0.9rem; margin-bottom: 5px;">Customer</p>
                                            <p style="font-weight: bold; color: var(--text-light);">'.htmlspecialchars($nama_lengkap).'</p>
                                        </div>
                                        <div>
                                            <p style="color: var(--text-muted); font-size: 0.9rem; margin-bottom: 5px;">ARMY ID</p>
                                            <p style="font-weight: bold; color: var(--text-light);">#'.str_pad($pengunjung_id, 5, '0', STR_PAD_LEFT).'</p>
                                        </div>
                                    </div>
                                </div>
                                <div style="text-align: center; padding: 25px; background: linear-gradient(135deg, rgba(138, 43, 226, 0.2), rgba(255, 105, 180, 0.2)); border-radius: 15px; margin-bottom: 20px;">
                                    <p style="color: var(--text-muted); margin-bottom: 5px;">Total Harga</p>
                                    <p style="font-size: 2rem; font-weight: bold; background: var(--bts-gradient); -webkit-background-clip: text; -webkit-text-fill-color: transparent;">'.$total.'</p>
                                </div>
                                <div style="text-align: center; margin-bottom: 20px;">
                                    <p style="color: var(--text-muted); font-size: 0.9rem;"><i class="icon icon-calendar"></i> Dibeli pada '.$formatted_date.'</p>
                                </div>
                                <div class="ticket-actions">
                                    <a href="process/ticketPrint.php?id='.$ticket_id.'" target="_blank" class="btn" style="padding: 12px; font-size: 14px;">
                                        <i class="icon icon-print"></i> Print/View
                                    </a>
                                    <button class="btn btn-view-details" onclick="viewTicketPreview('.$ticket_id.')" style="padding: 12px; font-size: 14px;">
                                        <i class="icon icon-eye"></i> Quick Preview
                                    </button>
                                </div>
                            </div>
                        </div>';
                    }
                    echo '</div>';
                } else {
                    echo '
                    <div style="text-align: center; padding: 100px 20px; background: var(--bts-dark-tertiary); border-radius: 20px; border: 2px dashed rgba(138, 43, 226, 0.3);">
                        <div style="font-size: 100px; margin-bottom: 30px; opacity: 0.3;">🎫</div>
                        <h3 style="color: var(--text-light); margin-bottom: 15px; font-size: 2rem;">Tidak Ada Tiket Ditemukan</h3>
                        <p style="color: var(--text-muted); margin-bottom: 40px;">
                            Anda belum membeli tiket apapun. Dapatkan tiket konser BTS Anda sekarang!
                        </p>
                        <a href="dashboard.php#buy-tickets" class="btn" style="padding: 15px 40px; font-size: 1.1rem;"><i class="icon icon-shopping-cart"></i> Beli Tiket Sekarang</a>
                    </div>';
                }
                ?>
            </div>
        </section>
    </main>

    <footer class="footer">
        <div class="container">
            <p style="margin-bottom: 10px; font-size: 14px;">© Copyright BTS LOVE YOURSELF World Tour 2026</p>
            <p style="font-size: 12px; opacity: 0.7;">Presented by HYBE & Big Hit Entertainment | Sri Yanti | Official Ticketing System</p>
        </div>
    </footer>

    <div class="modal" id="ticketPreviewModal">
        <div class="modal-content" style="max-width: 900px;">
            <div class="modal-header">
                <h3><i class="icon icon-ticket"></i> 💜 Preview Tiket BTS</h3>
                <button class="modal-close" onclick="closeModal('ticketPreviewModal')">×</button>
            </div>
            <div class="modal-body" id="ticketPreviewContent">
            </div>
        </div>
    </div>
    <script src="assets/js/main.js"></script>
    <script src="assets/js/ticket_system.js"></script>
</body>
</html>