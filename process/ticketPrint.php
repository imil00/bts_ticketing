<?php
// File ini ada di folder process/
include 'database.php';
session_start();

// Cek session dan authorization
if(!isset($_SESSION['username']) || !isset($_SESSION['level'])) {
    header("Location: ../index.php");
    exit();
}

if($_SESSION['level'] == 1) {
    header("Location: ../admin.php");
    exit();
}

$pengunjung_id = $_SESSION['pengunjung_id'] ?? 0;

if($pengunjung_id == 0) {
    echo "<script>alert('Session error. Please login again.'); window.location.href = 'logout.php';</script>";
    exit();
}

// Cek parameter ticket ID
if(!isset($_GET['id']) || empty($_GET['id'])) {
    echo "<script>alert('Ticket ID is required.'); window.location.href = '../my_tickets.php';</script>";
    exit();
}

$ticket_id = intval($_GET['id']);

// Query untuk mendapatkan detail tiket dengan verifikasi kepemilikan
$query = "SELECT t.*, p.nama_lengkap, p.email, p.nomor_telp, p.alamat 
          FROM transaksi t 
          INNER JOIN pengunjung p ON t.id = p.id 
          WHERE t.id_transaksi = $ticket_id 
          AND t.id = $pengunjung_id 
          AND t.terhapus = '0'";

$result = mysqli_query($con, $query);

if(!$result || mysqli_num_rows($result) == 0) {
    echo "<script>alert('Ticket not found or unauthorized access!'); window.location.href = '../my_tickets.php';</script>";
    exit();
}

$ticket = mysqli_fetch_assoc($result);

// Generate data untuk tampilan
$seat_section = chr(rand(65, 69)); // A-E
$seat_row = rand(1, 30);
$seat_number = rand(1, 50);
$gate_number = rand(1, 8);

// Determine ticket color based on class
$ticket_colors = [
    'ARMY Zone' => ['primary' => '#8a2be2', 'secondary' => '#ff69b4', 'bg' => '#f5e6ff'],
    'VIP Soundcheck' => ['primary' => '#ff1493', 'secondary' => '#ff69b4', 'bg' => '#ffe6f5'],
    'Meet & Greet Package' => ['primary' => '#ffd700', 'secondary' => '#ff8c00', 'bg' => '#fff9e6'],
    'VIP A' => ['primary' => '#00bfff', 'secondary' => '#0080ff', 'bg' => '#e6f7ff']
];

$color = $ticket_colors[$ticket['kelas']] ?? $ticket_colors['ARMY Zone'];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BTS Ticket - TRX<?php echo str_pad($ticket['id_transaksi'], 6, '0', STR_PAD_LEFT); ?></title>
    <link rel="stylesheet" href="../assets/css/style.css">
    <style>
        /* Dynamic colors based on ticket type */
        .ticket-left {
            background: <?php echo $color['bg']; ?>;
        }
        
        .ticket-type {
            background: linear-gradient(135deg, <?php echo $color['primary']; ?>, <?php echo $color['secondary']; ?>);
        }
        
        .seat-info {
            background: linear-gradient(135deg, <?php echo $color['primary']; ?>, <?php echo $color['secondary']; ?>);
        }
        
        .ticket-header h1 {
            color: <?php echo $color['primary']; ?>;
        }
        
        .transaction-id,
        .price-value {
            color: <?php echo $color['primary']; ?>;
        }
        
        .qr-code {
            border-color: <?php echo $color['primary']; ?>;
        }
        
        .price-box {
            border-color: <?php echo $color['primary']; ?>;
            background: <?php echo $color['bg']; ?>;
        }
        
        .btn-print-primary {
            background: linear-gradient(135deg, <?php echo $color['primary']; ?>, <?php echo $color['secondary']; ?>);
        }
        
        .btn-print-secondary {
            border-color: <?php echo $color['primary']; ?>;
            color: <?php echo $color['primary']; ?>;
        }
    </style>
</head>
<body class="ticket-print-page">
    <div class="ticket-container">
        <div class="ticket-content">
            <!-- Left Side - Ticket Details -->
            <div class="ticket-left">
                <div class="ticket-header">
                    <h1>🎤 BTS LOVE YOURSELF</h1>
                    <div class="subtitle">World Tour 2026 • Jakarta</div>
                </div>
                
                <div class="ticket-type">
                    <?php echo htmlspecialchars($ticket['kelas']); ?>
                </div>
                
                <div class="info-section">
                    <div class="info-row">
                        <span class="info-label">Customer Name</span>
                        <span class="info-value"><?php echo htmlspecialchars($ticket['nama_lengkap']); ?></span>
                    </div>
                    <div class="info-row">
                        <span class="info-label">ARMY ID</span>
                        <span class="info-value">#<?php echo str_pad($ticket['id'], 5, '0', STR_PAD_LEFT); ?></span>
                    </div>
                    <div class="info-row">
                        <span class="info-label">Email</span>
                        <span class="info-value"><?php echo htmlspecialchars($ticket['email']); ?></span>
                    </div>
                    <div class="info-row">
                        <span class="info-label">Phone</span>
                        <span class="info-value"><?php echo htmlspecialchars($ticket['nomor_telp']); ?></span>
                    </div>
                    <div class="info-row">
                        <span class="info-label">Ticket Quantity</span>
                        <span class="info-value"><?php echo $ticket['jml_kursi']; ?> Ticket(s)</span>
                    </div>
                    <div class="info-row">
                        <span class="info-label">Purchase Date</span>
                        <span class="info-value"><?php echo date('d M Y, H:i', strtotime($ticket['tanggal_transaksi'])); ?></span>
                    </div>
                </div>
                
                <div class="seat-info">
                    <div class="seat-item">
                        <div class="seat-label">SECTION</div>
                        <div class="seat-value"><?php echo $seat_section; ?></div>
                    </div>
                    <div class="seat-item">
                        <div class="seat-label">ROW</div>
                        <div class="seat-value"><?php echo $seat_row; ?></div>
                    </div>
                    <div class="seat-item">
                        <div class="seat-label">SEAT</div>
                        <div class="seat-value"><?php echo $seat_number; ?></div>
                    </div>
                </div>
                
                <div class="concert-info">
                    <div class="concert-info-item">
                        <strong>📅 Date:</strong> December 15-17, 2026
                    </div>
                    <div class="concert-info-item">
                        <strong>📍 Venue:</strong> Gelora Bung Karno Stadium
                    </div>
                    <div class="concert-info-item">
                        <strong>🕒 Doors Open:</strong> 3:00 PM | <strong>Show:</strong> 7:00 PM
                    </div>
                    <div class="concert-info-item">
                        <strong>🚪 Gate:</strong> Gate <?php echo $gate_number; ?>
                    </div>
                </div>
            </div>
            
            <!-- Right Side - QR & Price -->
            <div class="ticket-right">
                <div class="transaction-id">
                    TRX<?php echo str_pad($ticket['id_transaksi'], 6, '0', STR_PAD_LEFT); ?>
                </div>
                
                <div class="qr-section">
                    <div class="qr-code">
                        <div class="qr-bars">
                            <?php
                            // Generate barcode-style pattern based on transaction ID
                            $code = str_pad($ticket['id_transaksi'], 6, '0', STR_PAD_LEFT);
                            $seed = hexdec(substr(md5($code), 0, 8));
                            srand($seed);
                            
                            for($i = 0; $i < 40; $i++) {
                                $width = rand(1, 3);
                                echo '<div class="qr-bar" style="flex: '.$width.';"></div>';
                            }
                            ?>
                        </div>
                    </div>
                    <div class="scan-text">
                        Scan at venue entrance
                    </div>
                </div>
                
                <div class="price-box">
                    <div class="price-label">Total Amount</div>
                    <div class="price-value">
                        Rp <?php echo number_format($ticket['bayar'], 0, ',', '.'); ?>
                    </div>
                </div>
                
                <div style="margin-top: 25px; padding: 15px; background: rgba(255, 193, 7, 0.1); border-radius: 8px; font-size: 0.75rem; color: #856404; border: 1px solid rgba(255, 193, 7, 0.3);">
                    <strong>⚠️ Important:</strong><br>
                    • Bring valid ID<br>
                    • No refunds/exchanges<br>
                    • Arrive 1 hour early
                </div>
            </div>
        </div>
    </div>
    
    <!-- Action Buttons -->
    <div class="ticket-print-actions">
        <button class="btn-print-primary" onclick="window.print()">
            🖨️ Print Ticket
        </button>
        <a href="../my_tickets.php" class="btn-print-secondary">
            ⬅️ Back to My Tickets
        </a>
    </div>
    
    <script>
    // Keyboard shortcuts
    document.addEventListener('keydown', function(e) {
        if ((e.ctrlKey || e.metaKey) && e.key === 'p') {
            e.preventDefault();
            window.print();
        }
        if (e.key === 'Escape') {
            window.location.href = '../my_tickets.php';
        }
    });
    </script>
</body>
</html>