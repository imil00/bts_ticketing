<?php
include 'process/database.php';
session_start();
if(!isset($_SESSION['username']) || !isset($_SESSION['level'])) {
    header("Location: index.php");
    exit();
}
$username = $_SESSION['username'];
$pengunjung_id = $_SESSION['pengunjung_id'] ?? 0;
if($pengunjung_id == 0) {
    echo "<script>alert('Session error. Silakan login kembali.'); window.location.href = 'process/logout.php';</script>";
    exit();
}
$query_user = mysqli_query($con, "SELECT id_pengunjung FROM user WHERE username = '$username'");
$user_data = mysqli_fetch_assoc($query_user);
$id_pengunjung = $user_data['id_pengunjung'] ?? $pengunjung_id;

$query = "SELECT * FROM pengunjung WHERE id = $id_pengunjung LIMIT 1";
$result_query = mysqli_query($con, $query);

if(!$result_query || mysqli_num_rows($result_query) == 0) {
    echo "<script>alert('Data user tidak ditemukan.'); window.location.href = 'process/logout.php';</script>";
    exit();
}
$result = mysqli_fetch_array($result_query);
$id = $result['id'];
$nama_lengkap = $result['nama_lengkap'];
$query_count = "SELECT COUNT(*) as total FROM transaksi WHERE id = $id_pengunjung AND terhapus = '0'";
$result_count = mysqli_query($con, $query_count);
$row_count = mysqli_fetch_assoc($result_count);
$total_transactions = $row_count['total'];
$query_tickets = "SELECT SUM(jml_kursi) as total FROM transaksi WHERE id = $id_pengunjung AND terhapus = '0'";
$result_tickets = mysqli_query($con, $query_tickets);
$row_tickets = mysqli_fetch_assoc($result_tickets);
$total_tickets = $row_tickets['total'] ?? 0;
$query_spent = "SELECT SUM(bayar) as total FROM transaksi WHERE id = $id_pengunjung AND terhapus = '0'";
$result_spent = mysqli_query($con, $query_spent);
$row_spent = mysqli_fetch_assoc($result_spent);
$total_spent = $row_spent['total'] ?? 0;

if($_SESSION['level'] == 1) {
    header("Location: admin.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BTS LOVE YOURSELF - Dashboard</title>
    <link rel="stylesheet" href="assets/css/style.css">
    <link rel="icon" href="assets/img/favicon.jpg">
</head>
<body>
    <header class="header">
        <div class="container">
            <nav class="navbar">
                <div class="logo">
                    <a href="dashboard.php">
                        <img src="assets/img/logo.png" alt="BTS LOVE YOURSELF">
                    </a>
                </div>
                <button class="menu-toggle" id="menuToggle">
                    <i class="icon icon-menu"></i>
                </button>
                <ul class="nav-menu" id="navMenu">
                    <li><a href="#welcome"><i class="icon icon-home"></i> Dashboard</a></li>
                    <li><a href="my_tickets.php"><i class="icon icon-ticket"></i> My Tickets</a></li>
                    <li><a href="#buy-tickets"><i class="icon icon-shopping-cart"></i> Buy Tickets</a></li>
                    <li><a href="#about-concert"><i class="icon icon-info"></i> About Concert</a></li>
                    <li><a href="process/logout.php"><i class="icon icon-logout"></i> Logout</a></li>
                </ul>
            </nav>
        </div>
    </header>
    
    <div id="mobile-body-overlay" style="display: none;"></div>
    <section class="dashboard-section" id="welcome" style="text-align: center;">
        <div class="container">
            <div class="hero-content" style="text-align: center;">
                <h2 style="font-size: 3rem; margin-bottom: 15px;">💜 Welcome Back, ARMY!</h2>
                <h1 style="font-size: 2.5rem; margin-bottom: 20px; background: var(--bts-gradient); -webkit-background-clip: text; -webkit-text-fill-color: transparent;">
                    <?php echo $nama_lengkap; ?>
                </h1>
                <p style="font-size: 1.2rem; margin-bottom: 20px;">
                    Siap untuk pengalaman BTS yang luar biasa?
                </p>
                <div style="background: rgba(255,255,255,0.1); padding: 15px 30px; border-radius: 25px; display: inline-block; backdrop-filter: blur(10px);">
                    <span style="font-size: 1.1rem;">ARMY Member ID: #<?php echo str_pad($id, 5, '0', STR_PAD_LEFT); ?></span>
                </div>
            </div>
        </div>
    </section>

    <section class="dashboard-section section-bg" id="my-tickets-summary">
        <div class="container">
            <div class="section-header">
                <h2>Ringkasan Tiket Saya</h2>
                <p>Gambaran cepat tiket konser Anda</p>
            </div>
            <div class="tickets-summary">
                <div class="summary-item">
                    <div class="summary-number">
                        <?php echo $total_transactions; ?>
                    </div>
                    <p>Transaksi</p>
                </div>
                <div class="summary-item">
                    <div class="summary-number">
                        <?php echo $total_tickets; ?>
                    </div>
                    <p>Total Tiket</p>
                </div>
                <div class="summary-item">
                    <div class="summary-number" style="font-size: 1.5rem;">
                        <?php echo 'Rp ' . number_format($total_spent, 0, ',', '.'); ?>
                    </div>
                    <p>Total Pembelanjaan</p>
                </div>
            </div>
            
            <div style="text-align: center; margin-top: 30px;">
                <a href="my_tickets.php" class="btn">
                    <i class="icon icon-ticket"></i> Lihat Semua Tiket Saya
                </a>
            </div>
            
            <?php
            $query_recent = "SELECT * FROM transaksi WHERE id = $id_pengunjung AND terhapus = 0 ORDER BY tanggal_transaksi DESC LIMIT 3";
            $result_recent = mysqli_query($con, $query_recent);
            
            if (mysqli_num_rows($result_recent) > 0) {
                echo '<div style="margin-top: 50px;">
                    <h3 style="text-align: center; margin-bottom: 30px; color: var(--text-light);">Tiket Terbaru</h3>
                    <div class="tickets-grid">';
                while($ticket = mysqli_fetch_assoc($result_recent)) {
                    echo '
                    <div class="ticket-card">
                        <div class="ticket-card-header" style="background: var(--bts-gradient);">
                            <h3>'.$ticket['kelas'].'</h3>
                            <p style="margin: 10px 0 0 0; opacity: 0.9;">TRX'.str_pad($ticket['id_transaksi'], 6, '0', STR_PAD_LEFT).'</p>
                        </div>
                        <div class="ticket-card-body">
                            <div style="text-align: center; margin: 20px 0;">
                                <p style="color: var(--text-muted); margin-bottom: 5px;">Jumlah</p>
                                <p style="font-size: 2rem; font-weight: bold; background: var(--bts-gradient); -webkit-background-clip: text; -webkit-text-fill-color: transparent;">
                                    '.$ticket['jml_kursi'].' Tiket
                                </p>
                            </div>
                            <div style="text-align: center; padding: 15px; background: rgba(138, 43, 226, 0.1); border-radius: 10px;">
                                <p style="color: var(--text-muted); margin-bottom: 5px;">Total Dibayar</p>
                                <p style="font-size: 1.5rem; font-weight: bold; color: var(--bts-pink);">
                                    Rp '.number_format($ticket['bayar'], 0, ',', '.').'
                                </p>
                            </div>
                            <p style="text-align: center; margin-top: 15px; color: var(--text-muted); font-size: 0.9rem;">
                                Dibeli: '.date('d M Y', strtotime($ticket['tanggal_transaksi'])).'
                            </p>
                            <div style="text-align: center; margin-top: 15px;">
                                <a href="process/ticketPrint.php?id='.$ticket['id_transaksi'].'" target="_blank" class="btn btn-small" style="margin: 5px;">
                                    <i class="icon icon-print"></i> Print
                                </a>
                                <button class="btn btn-small btn-outline" onclick="viewTicketPreview('.$ticket['id_transaksi'].')" style="margin: 5px;">
                                    <i class="icon icon-eye"></i> Preview
                                </button>
                            </div>
                        </div>
                    </div>';
                }  
                echo '</div></div>';
            }
            ?>
        </div>
    </section>

    <section class="dashboard-section" id="buy-tickets">
        <div class="container">
            <div class="section-header">
                <h2>Beli Tiket Konser</h2>
                <p>Pilih pengalaman konser sempurna Anda</p>
            </div>
            
            <div class="tickets-grid">
                <?php
                $query_tickets = mysqli_query($con, "SELECT * FROM kelas_tiket ORDER BY harga ASC");
                $ticket_colors = [
                    ['header' => 'linear-gradient(135deg, #8a2be2, #6a1bb2)', 'badge' => 'PALING POPULER'],
                    ['header' => 'linear-gradient(135deg, #ff69b4, #ff1493)', 'badge' => 'VIP ACCESS'],
                    ['header' => 'linear-gradient(135deg, #ffd700, #ff8c00)', 'badge' => 'VVIP EXCLUSIVE'],
                    ['header' => 'linear-gradient(135deg, #00bfff, #0080ff)', 'badge' => 'PREMIUM'],
                    ['header' => 'linear-gradient(135deg, #32cd32, #228b22)', 'badge' => 'SPESIAL'],
                ]; 
                $color_index = 0;
                while($ticket = mysqli_fetch_assoc($query_tickets)) {
                    $color = $ticket_colors[$color_index % count($ticket_colors)];
                    $modal_id = 'buyTicketModal_' . $ticket['id_kelas'];
                    echo '
                    <div class="ticket-card">
                        <div class="ticket-card-header" style="background: ' . $color['header'] . ';">
                            <h3>' . htmlspecialchars($ticket['nama_kelas']) . '</h3>
                            <div class="ticket-badge">' . $color['badge'] . '</div>
                        </div>
                        <div class="ticket-card-body">
                            <div class="ticket-price">Rp ' . number_format($ticket['harga'], 0, ',', '.') . '</div>
                            <ul class="ticket-features">
                                <li><i class="icon icon-check"></i> ' . htmlspecialchars($ticket['deskripsi']) . '</li>
                                <li><i class="icon icon-check"></i> Kapasitas: ' . $ticket['kapasitas'] . ' tiket</li>
                                <li><i class="icon icon-check"></i> Official BTS Merchandise</li>
                            </ul>
                            <div style="text-align: center; margin-top: 25px;">
                                <button class="btn" onclick="openModal(\'' . $modal_id . '\')">
                                    <i class="icon icon-heart"></i> Beli Sekarang
                                </button>
                            </div>
                        </div>
                    </div>
                    <div class="modal" id="' . $modal_id . '">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h3><i class="icon icon-heart"></i> Beli ' . htmlspecialchars($ticket['nama_kelas']) . '</h3>
                                <button class="modal-close" onclick="closeModal(\'' . $modal_id . '\')">×</button>
                            </div>
                            <div class="modal-body">
                                <form method="POST" action="process/addTransaksi.php">
                                    <input type="hidden" name="id_kelas" value="' . $ticket['id_kelas'] . '">
                                    <input type="hidden" name="kelas" value="' . htmlspecialchars($ticket['nama_kelas']) . '">
                                    <input type="hidden" name="biaya" value="' . $ticket['harga'] . '">
                                    <div class="form-group">
                                        <label class="form-label">Jumlah Tiket (Maks 4)</label>
                                        <input type="number" name="jml_kursi" class="form-control" min="1" max="4" value="1" required>
                                        <small class="text-muted">Harga: Rp ' . number_format($ticket['harga'], 0, ',', '.') . ' per tiket</small>
                                    </div>
                                    <div class="form-group">
                                        <label class="form-label">Tanggal Konser</label>
                                        <select class="form-control" name="tanggal" required>
                                            <option value="">-- Pilih Tanggal --</option>
                                            <option value="2026-12-15">Jumat, 15 Des 2026</option>
                                            <option value="2026-12-16">Sabtu, 16 Des 2026</option>
                                            <option value="2026-12-17">Minggu, 17 Des 2026</option>
                                        </select>
                                    </div>
                                    <div class="alert alert-info">
                                        <i class="icon icon-info"></i>
                                        <span>' . htmlspecialchars($ticket['deskripsi']) . '</span>
                                    </div>
                                    <div style="text-align: center; margin-top: 25px;">
                                        <button type="submit" name="transaksi" class="btn">
                                            <i class="icon icon-shopping-cart"></i> Konfirmasi Pembelian
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>';
                    $color_index++;
                }
                
                if(mysqli_num_rows($query_tickets) == 0) {
                    echo '<div style="grid-column: 1 / -1; text-align: center; padding: 60px 20px;">
                        <h3 style="color: var(--text-muted); margin-bottom: 20px;">Tiket belum tersedia saat ini</h3>
                        <p style="color: var(--text-muted);">Silakan cek kembali nanti untuk ketersediaan tiket</p>
                    </div>';
                }
                ?>
            </div>
        </div>
    </section>

    <section class="dashboard-section section-bg" id="about-concert">
        <div class="container">
            <div class="section-header">
                <h2>Informasi Konser</h2>
            </div>
            <div class="card">
                <div class="card-header">
                    <h3>BTS LOVE YOURSELF World Tour - Jakarta 2026</h3>
                </div>
                <div style="padding: 30px;">
                    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 40px;">
                        <div>
                            <h4 style="color: var(--bts-purple); margin-bottom: 15px;">
                                <i class="icon icon-calendar"></i> Jadwal
                            </h4>
                            <ul class="ticket-features">
                                <li><strong>15 Desember 2026:</strong> Hari 1</li>
                                <li><strong>16 Desember 2026:</strong> Hari 2</li>
                                <li><strong>17 Desember 2026:</strong> Hari 3 (Finale)</li>
                            </ul>
                            <p style="margin-top: 15px;"><strong>Pintu Buka:</strong> 15:00 WIB</p>
                            <p><strong>Konser Mulai:</strong> 19:00 WIB</p>
                        </div>
                        <div>
                            <h4 style="color: var(--bts-pink); margin-bottom: 15px;">
                                <i class="icon icon-map-marker"></i> Lokasi
                            </h4>
                            <p><strong>Stadion Utama Gelora Bung Karno</strong></p>
                            <p>Jl. Pintu Satu Senayan, Jakarta</p>
                            <p style="margin: 15px 0;">Kapasitas: 77,000+ ARMY</p>
                            <p><i class="icon icon-car"></i> Parkir tersedia</p>
                            
                            <div style="background: rgba(138, 43, 226, 0.15); padding: 20px; border-radius: 10px; margin-top: 25px; border-left: 4px solid var(--bts-purple);">
                                <h5 style="color: var(--bts-purple); margin-bottom: 10px;">
                                    <i class="icon icon-info"></i> Catatan Penting
                                </h5>
                                <ul class="ticket-features" style="margin: 0;">
                                    <li>Bawa ID yang sesuai dengan tiket</li>
                                    <li>Pintu buka pukul 15:00 WIB</li>
                                    <li>Tidak ada refund atau tukar tiket</li>
                                    <li>Check-in VIP mulai pukul 14:00 WIB</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <footer class="footer">
        <div class="container">
            <p style="margin-bottom: 10px; font-size: 14px;">
                © Copyright BTS LOVE YOURSELF World Tour 2026
            </p>
            <p style="font-size: 12px; opacity: 0.7;">
                 by HYBE & Big Hit Entertainment | Sri Yanti | Official Ticketing System
            </p>
        </div>
    </footer>

    <div class="modal" id="ticketPreviewModal">
        <div class="modal-content" style="max-width: 900px;">
            <div class="modal-header">
                <h3><i class="icon icon-ticket"></i> 💜 Preview Tiket BTS</h3>
                <button class="modal-close" onclick="closeModal('ticketPreviewModal')">×</button>
            </div>
            <div class="modal-body">
                <div id="ticketPreviewContent">
                </div>
            </div>
        </div>
    </div>
    <script src="assets/js/main.js"></script>
    <script src="assets/js/ticket_system.js"></script>
</body>
</html>