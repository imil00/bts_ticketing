<?php
include 'process/database.php';
session_start();
if(isset($_SESSION['username']) and isset($_SESSION['level'])) {
    if($_SESSION['level'] == '1') {
        header("Location: admin.php");
        exit();
    }
    else if ($_SESSION['level'] == '2') {
      header("Location: dashboard.php");
      exit();
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BTS LOVE YOURSELF World Tour - Jakarta 2026</title>
    <link rel="stylesheet" href="assets/css/style.css">
    <link rel="icon" href="assets/img/favicon.jpg">
</head>
<body>
    <header class="header">
        <div class="container">
            <nav class="navbar">
                <div class="logo">
                    <a href="#">
                        <img src="assets/img/logo.png" alt="BTS LOVE YOURSELF">
                    </a>
                </div>
                <button class="menu-toggle" id="menuToggle">
                    <i class="icon icon-menu"></i>
                </button>
                <ul class="nav-menu" id="navMenu">
                    <li><a href="#about"><i class="icon icon-info"></i> About Concert</a></li>
                    <li><a href="#members"><i class="icon icon-users"></i> BTS Members</a></li>
                    <li><a href="#venue"><i class="icon icon-map-marker"></i> Venue</a></li>
                    <li><a href="#schedule"><i class="icon icon-calendar"></i> Schedule</a></li>
                    <li><a href="#" onclick="openModal('loginModal')" class="btn btn-small">
                        <i class="icon icon-ticket"></i> Login
                    </a></li>
                </ul>
            </nav>
        </div>
    </header>
    <div id="mobile-body-overlay" style="display: none;"></div>

    <section class="hero" id="home">
        <div class="hero-content">
            <h1>BTS<br><span>LOVE YOURSELF</span><br>WORLD TOUR</h1>
            <p>"Speak Yourself" Finale<br>15-17 December 2026, Gelora Bung Karno Stadium, Jakarta</p>
            
            <div style="margin: 30px 0;">
                <button class="btn" onclick="openModal('loginModal')">
                    <i class="icon icon-ticket"></i> Login & Buy Tickets
                </button>
            </div>
            <p style="color: #ff69b4; margin-top: 20px; font-size: 18px;">
                <i class="icon icon-heart"></i> #BTSinJAKARTA #LOVE_YOURSELF #ARMY
            </p>
        </div>
    </section>

    <section class="section" id="about">
        <div class="container">
            <div class="section-header">
                <h2>About The Concert</h2>
                <p>Rasakan fenomena global BTS secara langsung di Jakarta!</p>
            </div>
            <div class="card">
                <div class="card-header">
                    <h3>LOVE YOURSELF World Tour</h3>
                </div>
                <div style="padding: 30px;">
                    <p style="margin-bottom: 20px; line-height: 1.8;">
                        LOVE YOURSELF World Tour datang ke Indonesia untuk tiga malam yang magis. Bergabunglah dengan RM, Jin, SUGA, j-hope, Jimin, V, dan Jung Kook saat mereka membawakan lagu-lagu hit dari seri LOVE YOURSELF yang fenomenal.
                    </p>
                    <p style="margin-bottom: 30px; line-height: 1.8;">
                        Ini bukan sekadar konser - ini adalah perayaan musik, cinta, dan koneksi. Dengan penampilan yang memukau, visual yang stunning, dan energi elektrik dari ARMY, ini akan menjadi acara K-pop terbesar tahun ini!
                    </p>
                    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(280px, 1fr)); gap: 30px;">
                        <div style="background: rgba(138, 43, 226, 0.1); padding: 25px; border-radius: 15px; border-left: 4px solid var(--bts-purple);">
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
                        <div style="background: rgba(255, 105, 180, 0.1); padding: 25px; border-radius: 15px; border-left: 4px solid var(--bts-pink);">
                            <h4 style="color: var(--bts-pink); margin-bottom: 15px;">
                                <i class="icon icon-map-marker"></i> Lokasi
                            </h4>
                            <p style="margin-bottom: 10px;"><strong>Stadion Utama Gelora Bung Karno</strong></p>
                            <p style="margin-bottom: 15px;">Jl. Pintu Satu Senayan, Jakarta</p>
                            <p><strong>Kapasitas:</strong> 77,000+ ARMY</p>
                            <p><i class="icon icon-car"></i> Parkir: 5,000+ kendaraan</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="section section-bg" id="members">
        <div class="container">
            <div class="section-header">
                <h2>Meet BTS</h2>
                <p>7 member luar biasa yang akan tampil di Jakarta</p>
            </div>
            <div class="members-grid" style="margin-bottom: 40px;">
                <?php
                $members_row1 = [
                    ['name' => 'RM', 'role' => 'Leader, Main Rapper', 'color' => '#8a2be2', 'img' => 'assets/img/members/namjoon.jpg'],
                    ['name' => 'Jin', 'role' => 'Vocalist, Visual', 'color' => '#ff69b4', 'img' => 'assets/img/members/seokjin.jpg'],
                    ['name' => 'SUGA', 'role' => 'Lead Rapper', 'color' => '#8a2be2', 'img' => 'assets/img/members/yoongi.jpg'],
                    ['name' => 'j-hope', 'role' => 'Main Dancer, Rapper', 'color' => '#ff69b4', 'img' => 'assets/img/members/jhope.jpg']
                ];
                foreach ($members_row1 as $member) {
                    $imgPath = file_exists($member['img']) ? $member['img'] : 'https://via.placeholder.com/300x400?text=' . urlencode($member['name']);
                    echo '
                    <div class="ticket-card" style="text-align: center;">
                        <div style="position: relative; overflow: hidden; height: 250px;">
                            <img src="'.$imgPath.'" alt="'.$member['name'].'" 
                                 style="width: 100%; height: 100%; object-fit: cover; transition: transform 0.3s;"
                                 onmouseover="this.style.transform=\'scale(1.1)\'" 
                                 onmouseout="this.style.transform=\'scale(1)\'">
                            <div style="position: absolute; bottom: 0; left: 0; right: 0; background: linear-gradient(to top, '.$member['color'].', transparent); padding: 20px 15px 15px; color: white;">
                                <h3 style="margin: 0; font-size: 1.8rem; text-shadow: 0 2px 10px rgba(0,0,0,0.5);">'.$member['name'].'</h3>
                            </div>
                        </div>
                        <div class="ticket-card-body">
                            <p style="color: '.$member['color'].'; font-weight: 600; margin: 0;">'.$member['role'].'</p>
                        </div>
                    </div>';
                }
                ?>
            </div>
            <div class="members-grid" style="grid-template-columns: repeat(3, 1fr); max-width: 900px; margin: 0 auto;">
                <?php
                $members_row2 = [
                    ['name' => 'Jimin', 'role' => 'Main Dancer, Lead Vocalist', 'color' => '#8a2be2', 'img' => 'assets/img/members/jimin.jpg'],
                    ['name' => 'V', 'role' => 'Vocalist, Dancer', 'color' => '#ff69b4', 'img' => 'assets/img/members/taehyung.jpg'],
                    ['name' => 'Jung Kook', 'role' => 'Main Vocalist, Center, Maknae', 'color' => '#8a2be2', 'img' => 'assets/img/members/jungkook.jpg']
                ];
                foreach ($members_row2 as $member) {
                    $imgPath = file_exists($member['img']) ? $member['img'] : 'https://via.placeholder.com/300x400?text=' . urlencode($member['name']);
                    echo '
                    <div class="ticket-card" style="text-align: center;">
                        <div style="position: relative; overflow: hidden; height: 250px;">
                            <img src="'.$imgPath.'" alt="'.$member['name'].'" 
                                 style="width: 100%; height: 100%; object-fit: cover; transition: transform 0.3s;"
                                 onmouseover="this.style.transform=\'scale(1.1)\'" 
                                 onmouseout="this.style.transform=\'scale(1)\'">
                            <div style="position: absolute; bottom: 0; left: 0; right: 0; background: linear-gradient(to top, '.$member['color'].', transparent); padding: 20px 15px 15px; color: white;">
                                <h3 style="margin: 0; font-size: 1.8rem; text-shadow: 0 2px 10px rgba(0,0,0,0.5);">'.$member['name'].'</h3>
                            </div>
                        </div>
                        <div class="ticket-card-body">
                            <p style="color: '.$member['color'].'; font-weight: 600; margin: 0;">'.$member['role'].'</p>
                        </div>
                    </div>';
                }
                ?>
            </div>
        </div>
    </section>

    <section class="section" id="venue" style="margin-top: 30px;">
        <div class="container">
            <div class="section-header">
                <h2>Concert Venue</h2>
                <p>Stadion Utama Gelora Bung Karno</p>
            </div>
            <div class="card">
                <div class="card-header">
                    <h3>Lokasi & Fasilitas</h3>
                </div>
                <div style="padding: 30px;">
                    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(320px, 1fr)); gap: 40px;">
                        <div>
                            <h4 style="color: var(--bts-purple); margin-bottom: 15px;">
                                <i class="icon icon-map-marker"></i> Lokasi
                            </h4>
                            <p><strong>Stadion Utama Gelora Bung Karno</strong><br>
                            Jl. Pintu Satu Senayan, Gelora<br>
                            Tanah Abang, Jakarta Pusat</p>
                            
                            <h4 style="margin-top: 25px; color: var(--bts-pink); margin-bottom: 15px;">
                                <i class="icon icon-star"></i> Fasilitas
                            </h4>
                            <ul class="ticket-features">
                                <li><i class="icon icon-check"></i> Pintu masuk & keluar multiple</li>
                                <li><i class="icon icon-check"></i> Toko merchandise ARMY</li>
                                <li><i class="icon icon-check"></i> Outlet makanan & minuman</li>
                                <li><i class="icon icon-check"></i> Pos pertolongan pertama</li>
                                <li><i class="icon icon-check"></i> Musholla</li>
                            </ul>
                        </div>
                        <div>
                            <h4 style="color: var(--bts-purple); margin-bottom: 15px;">
                                <i class="icon icon-bus"></i> Transportasi
                            </h4>
                            <ul class="ticket-features">
                                <li><i class="icon icon-check"></i> MRT: Stasiun GBK (5 menit jalan kaki)</li>
                                <li><i class="icon icon-check"></i> TransJakarta: Gelora Bung Karno</li>
                                <li><i class="icon icon-check"></i> Parkir untuk 5,000+ kendaraan</li>
                                <li><i class="icon icon-check"></i> Area drop-off ojek online</li>
                            </ul>
                            <div style="background: rgba(138, 43, 226, 0.15); padding: 20px; border-radius: 10px; margin-top: 25px; border-left: 4px solid var(--bts-purple);">
                                <h5 style="color: var(--bts-purple); margin-bottom: 10px;">
                                    <i class="icon icon-info"></i> Catatan Penting
                                </h5>
                                <ul class="ticket-features" style="margin: 0;">
                                    <li>Pintu buka 4 jam sebelum konser</li>
                                    <li>Dilarang membawa makanan/minuman dari luar</li>
                                    <li>Pembatasan ukuran tas berlaku</li>
                                    <li>Verifikasi ID diperlukan</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="section section-bg" id="schedule">
        <div class="container">
            <div class="section-header">
                <h2>Jadwal Konser</h2>
                <p>Tiga malam tak terlupakan bersama BTS</p>
            </div>
            <div class="tickets-grid">
                <div class="ticket-card">
                    <div class="ticket-card-header" style="background: linear-gradient(135deg, #8a2be2, #6a1bb2);">
                        <h3>Hari 1</h3>
                    </div>
                    <div class="ticket-card-body">
                        <p style="font-size: 1.3rem; font-weight: bold; margin-bottom: 15px;">Jumat, 15 Desember 2026</p>
                        <ul class="ticket-features">
                            <li><i class="icon icon-calendar"></i> Pintu Buka: 15:00 WIB</li>
                            <li><i class="icon icon-clock"></i> Konser Mulai: 19:00 WIB</li>
                            <li><i class="icon icon-clock"></i> Estimasi Selesai: 22:30 WIB</li>
                        </ul>
                        <p style="color: var(--text-muted); font-style: italic; margin-top: 15px;">
                            Malam pembuka dengan efek panggung spesial
                        </p>
                    </div>
                </div>
                <div class="ticket-card">
                    <div class="ticket-card-header" style="background: linear-gradient(135deg, #ff69b4, #ff1493);">
                        <h3>Hari 2</h3>
                    </div>
                    <div class="ticket-card-body">
                        <p style="font-size: 1.3rem; font-weight: bold; margin-bottom: 15px;">Sabtu, 16 Desember 2026</p>
                        <ul class="ticket-features">
                            <li><i class="icon icon-calendar"></i> Pintu Buka: 15:00 WIB</li>
                            <li><i class="icon icon-clock"></i> Konser Mulai: 19:00 WIB</li>
                            <li><i class="icon icon-clock"></i> Estimasi Selesai: 22:30 WIB</li>
                        </ul>
                        <p style="color: var(--text-muted); font-style: italic; margin-top: 15px;">
                            Pertunjukan tengah dengan variasi setlist unik
                        </p>
                    </div>
                </div>
                <div class="ticket-card">
                    <div class="ticket-card-header" style="background: linear-gradient(135deg, #ffd700, #ff8c00);">
                        <h3>Hari 3 - Finale</h3>
                    </div>
                    <div class="ticket-card-body">
                        <p style="font-size: 1.3rem; font-weight: bold; margin-bottom: 15px;">Minggu, 17 Desember 2026</p>
                        <ul class="ticket-features">
                            <li><i class="icon icon-calendar"></i> Pintu Buka: 15:00 WIB</li>
                            <li><i class="icon icon-clock"></i> Konser Mulai: 19:00 WIB</li>
                            <li><i class="icon icon-clock"></i> Estimasi Selesai: 23:00 WIB</li>
                        </ul>
                        <p style="color: var(--text-muted); font-style: italic; margin-top: 15px;">
                            Pertunjukan final dengan encore extended
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="section section-bg" id="tickets-preview" style="margin-top: 30px;">
        <div class="container">
            <div class="section-header">
                <h2>Tipe Tiket Konser</h2>
                <p>Pilih pengalaman ARMY sempurna Anda</p>
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
                                <button class="btn" onclick="openModal(\'loginModal\')">
                                    <i class="icon icon-heart"></i> Login untuk Beli
                                </button>
                            </div>
                        </div>
                    </div>';
                    $color_index++;
                }
                if(mysqli_num_rows($query_tickets) == 0) {
                    echo '<div style="grid-column: 1 / -1; text-align: center; padding: 60px 20px;">
                        <h3 style="color: var(--text-muted); margin-bottom: 20px;">Tiket segera hadir!</h3>
                        <p style="color: var(--text-muted);">Pantau terus untuk pengumuman ketersediaan tiket</p>
                    </div>';
                }
                ?>
            </div>
            <div style="text-align: center; margin-top: 50px;">
                <button class="btn" style="padding: 15px 50px; font-size: 1.1rem;" onclick="openModal('loginModal')">
                    <i class="icon icon-ticket"></i> Login sebagai ARMY untuk Pesan Tiket
                </button>
                <p style="margin-top: 20px; color: var(--text-muted); font-size: 1.1rem;">
                    <i class="icon icon-info"></i> Akses tiket eksklusif hanya untuk ARMY member terdaftar
                </p>
            </div>
        </div>
    </section>

    <div class="modal" id="loginModal">
        <div class="modal-content">
            <div class="modal-header">
                <h3><i class="icon icon-heart"></i> ARMY Login</h3>
                <button class="modal-close" onclick="closeModal('loginModal')">×</button>
            </div>
            <div class="modal-body">
                <form method="POST" action="process/userLogin.php" id="loginForm">
                    <div class="form-group">
                        <label class="form-label">Username</label>
                        <input type="text" name="username" class="form-control" placeholder="Masukkan username ARMY Anda" required>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Password</label>
                        <input type="password" name="password" class="form-control" placeholder="Masukkan password" required>
                    </div>
                    <div class="alert alert-info">
                        <i class="icon icon-info"></i>
                        <strong>ARMY Members Only:</strong> Konser ini eksklusif untuk ARMY member terdaftar.
                    </div>
                    <div style="text-align: center;">
                        <button type="submit" class="btn" style="width: 100%; padding: 15px;">
                            <i class="icon icon-heart"></i> Login & Akses Tiket
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <footer class="footer">
        <div class="container">
            <p style="margin-bottom: 10px; font-size: 14px;">
                © Copyright BTS LOVE YOURSELF World Tour 2026
            </p>
            <p style="font-size: 12px; opacity: 0.7;">
                Presented by HYBE & Big Hit Entertainment | Sri Yanti | Official Ticketing System
            </p>
        </div>
    </footer>

    <script src="assets/js/main.js"></script>
    <script>
    document.getElementById('loginForm')?.addEventListener('submit', function(e) {
        const submitBtn = this.querySelector('button[type="submit"]');
        if (submitBtn) {
            submitBtn.innerHTML = '<i class="icon icon-spinner"></i> Logging in...';
            submitBtn.disabled = true;
        }
    });
    </script>
</body>
</html>