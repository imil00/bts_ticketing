<?php
include 'process/conSQL.php';
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
$user_id = $_SESSION['user_id'] ?? 0;
if($user_id == 0) {
    echo "<script>
            alert('Session error. Silakan login kembali.');
            window.location.href = 'process/logout.php';
          </script>";
    exit();
}
$query = "SELECT * FROM pengunjung WHERE id = $user_id";
$result_query = mysqli_query($con, $query);
if(!$result_query || mysqli_num_rows($result_query) == 0) {
    echo "<script>
            alert('Data user tidak ditemukan. Silakan login kembali.');
            window.location.href = 'process/logout.php';
          </script>";
    exit();
}
$result = mysqli_fetch_array($result_query);
$id = $result['id'];
$nama_lengkap = $result['nama_lengkap'];
$email = $result['email'];
$nomor_telp = $result['nomor_telp'];
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <title>BTS LOVE YOURSELF - My Tickets</title>
  <meta content="width=device-width, initial-scale=1.0" name="viewport">
  <meta content="BTS, BTS concert, BTS tickets" name="keywords">
  <meta content="Lihat dan kelola tiket konser BTS Anda" name="description">
  <link href="img/favicon.jpg" rel="icon">
  <link href="img/apple-touch-icon.png" rel="apple-touch-icon">
  <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,700,700i|Raleway:300,400,500,700,800" rel="stylesheet">
  <link href="lib/bootstrap/css/bootstrap.min.css" rel="stylesheet">
  <link href="lib/font-awesome/css/font-awesome.min.css" rel="stylesheet">
  <link href="lib/animate/animate.min.css" rel="stylesheet">
  <link href="css/style.css" rel="stylesheet">

  <style>
    :root {
      --bts-purple: #8a2be2;
      --bts-pink: #ff69b4;
    }
    .btn-bts {
      background: linear-gradient(45deg, #8a2be2, #ff69b4);
      border: none;
      color: white;
      font-weight: bold;
      padding: 8px 20px;
      border-radius: 20px;
      transition: all 0.3s;
    }
    .btn-bts:hover {
      transform: translateY(-2px);
      box-shadow: 0 5px 15px rgba(138, 43, 226, 0.4);
      color: white;
    }
    .profile-card {
      background: linear-gradient(135deg, #8a2be2, #ff69b4);
      color: white;
      border-radius: 15px;
      padding: 30px;
      margin-bottom: 30px;
      box-shadow: 0 10px 30px rgba(0,0,0,0.2);
    }
    .ticket-card {
      border: 2px solid #8a2be2;
      border-radius: 10px;
      transition: transform 0.3s;
    }
    .ticket-card:hover {
      transform: translateY(-5px);
      box-shadow: 0 10px 20px rgba(138, 43, 226, 0.3);
    }
    .table th {
      background-color: #8a2be2;
      color: white;
    }
    .badge-primary {
      background-color: #8a2be2;
    }
    .stats-box {
      background: white;
      border-radius: 10px;
      padding: 20px;
      text-align: center;
      border: 2px solid #f0f0f0;
      margin-bottom: 20px;
    }
    .stats-box h3 {
      color: #8a2be2;
      font-size: 2.5rem;
      margin: 0;
    }
    .stats-box p {
      color: #666;
      margin: 5px 0 0 0;
    }
  </style>
</head>

<body>
  <header id="header" class="header-fixed">
    <div class="container">
      <div id="logo" class="pull-left">
        <a href="dasbor.php" class="scrollto"><img src="img/logo.png" alt="BTS Logo" height="40"></a>
      </div>
      <nav id="nav-menu-container">
        <ul class="nav-menu">
          <li><a href="dasbor.php"><i class="fa fa-home"></i> Dashboard</a></li>
          <li><a href="konfirmasi.php" style="color: #8a2be2;"><i class="fa fa-ticket"></i> My Tickets</a></li>
          <li class="buy-tickets"><a href="process/logout.php"><i class="fa fa-sign-out"></i> Logout</a></li>
        </ul>
      </nav>
    </div>
  </header>
  <main id="main" class="main-page">
  
    <section id="profile" class="wow fadeInUp" style="padding: 60px 0;">
      <div class="container">
        <div class="profile-card">
          <div class="row align-items-center">
            <div class="col-md-2 text-center">
              <i class="fa fa-user-circle" style="font-size: 80px;"></i>
            </div>
            <div class="col-md-7">
              <h2 style="margin: 0; font-weight: bold;">💜 <?php echo $nama_lengkap; ?></h2>
              <p style="margin: 5px 0; opacity: 0.9;">
                <i class="fa fa-envelope"></i> <?php echo $email; ?><br>
                <i class="fa fa-phone"></i> <?php echo $nomor_telp; ?>
              </p>
              <p style="margin: 5px 0;">
                <span class="badge" style="background: rgba(255,255,255,0.3); padding: 5px 15px; border-radius: 20px;">
                  ARMY Member #<?php echo str_pad($id, 5, '0', STR_PAD_LEFT); ?>
                </span>
              </p>
            </div>
            <div class="col-md-3 text-center">
              <a href="dasbor.php" class="btn" style="background: white; color: #8a2be2; font-weight: bold;">
                <i class="fa fa-shopping-cart"></i> Beli Tiket Lagi
              </a>
            </div>
          </div>
        </div>
        <div class="row">
          <div class="col-md-4">
            <div class="stats-box">
              <h3>
                <?php
                $query_count = "SELECT COUNT(*) as total FROM transaksi WHERE id = $id AND terhapus = 0";
                $result_count = mysqli_query($con, $query_count);
                $row_count = mysqli_fetch_assoc($result_count);
                echo $row_count['total'];
                ?>
              </h3>
              <p><i class="fa fa-shopping-cart"></i> Total Transaksi</p>
            </div>
          </div>
          <div class="col-md-4">
            <div class="stats-box">
              <h3>
                <?php
                $query_tickets = "SELECT SUM(jml_kursi) as total FROM transaksi WHERE id = $id AND terhapus = 0";
                $result_tickets = mysqli_query($con, $query_tickets);
                $row_tickets = mysqli_fetch_assoc($result_tickets);
                echo $row_tickets['total'] ?? 0;
                ?>
              </h3>
              <p><i class="fa fa-ticket"></i> Total Tiket</p>
            </div>
          </div>
          <div class="col-md-4">
            <div class="stats-box">
              <h3 style="font-size: 1.5rem;">
                <?php
                $query_spent = "SELECT SUM(bayar) as total FROM transaksi WHERE id = $id AND terhapus = 0";
                $result_spent = mysqli_query($con, $query_spent);
                $row_spent = mysqli_fetch_assoc($result_spent);
                echo 'Rp ' . number_format($row_spent['total'] ?? 0, 0, ',', '.');
                ?>
              </h3>
              <p><i class="fa fa-money"></i> Total Pengeluaran</p>
            </div>
          </div>
        </div>
      </div>
    </section>

    <section id="tickets" class="wow fadeInUp" style="padding: 60px 0; background: #f8f9fa;">
      <div class="container">
        <div class="section-header">
          <h2>Tiket Konser BTS Saya</h2>
          <p>Lihat dan kelola semua tiket yang telah dibeli</p>
        </div>
        <div class="row">
          <div class="col-12">
            <div class="ticket-card" style="background: white; padding: 20px;">
              <div class="table-responsive">
                <table class="table table-hover">
                  <thead>
                    <tr>
                      <th>ID Transaksi</th>
                      <th>ID Customer</th>
                      <th>Tipe Tiket</th>
                      <th>Jumlah</th>
                      <th>Total Harga</th>
                      <th>Aksi</th>
                    </tr>
                  </thead>
                  <tbody>
                  <?php
                  $query = "SELECT * FROM transaksi WHERE terhapus = 0 AND id = $id ORDER BY tanggal_transaksi DESC";
                  $result = mysqli_query($con, $query);
                  if (mysqli_num_rows($result) > 0){
                      $total_pembelian = 0;
                      while($row = mysqli_fetch_assoc($result)){
                          $id_transaksi = $row["id_transaksi"];
                          $total_pembelian += $row["bayar"];
                          $formatted_bayar = 'Rp ' . number_format($row["bayar"], 0, ',', '.');
                          $formatted_date = date('d M Y H:i', strtotime($row["tanggal_transaksi"]));
                          echo "
                          <tr>
                              <td><strong>TRX" . str_pad($row["id_transaksi"], 6, '0', STR_PAD_LEFT) . "</strong><br>
                                  <small class='text-muted'>" . $formatted_date . "</small>
                              </td>
                              <td>" . str_pad($row["id"], 5, '0', STR_PAD_LEFT) . "</td>
                              <td><span class='badge badge-primary'>" . $row["kelas"] . "</span></td>
                              <td><strong>" . $row["jml_kursi"] . "</strong> tiket</td>
                              <td><strong style='color: #8a2be2;'>" . $formatted_bayar . "</strong></td>
                              <td>
                                  <button class='btn btn-sm btn-bts' onclick=\"alert('🎫 Tiket Anda dikonfirmasi!\\n\\nTransaksi: TRX" . str_pad($row["id_transaksi"], 6, '0', STR_PAD_LEFT) . "\\nSilakan bawa ID Anda pada hari konser.')\">
                                      <i class='fa fa-info-circle'></i> Lihat
                                  </button>
                              </td>
                          </tr>
                          ";
                      }
                      echo "
                      <tr class='table-info' style='background: rgba(138, 43, 226, 0.1);'>
                          <td colspan='4' class='text-right'><strong style='font-size: 1.2rem;'>Total Pengeluaran:</strong></td>
                          <td><strong style='color: #8a2be2; font-size: 1.2rem;'>Rp " . number_format($total_pembelian, 0, ',', '.') . "</strong></td>
                          <td></td>
                      </tr>";
                  } else {
                      echo "
                      <tr>
                          <td colspan='6' class='text-center' style='padding: 50px;'>
                              <i class='fa fa-ticket' style='font-size: 50px; color: #ccc; display: block; margin-bottom: 15px;'></i>
                              <h4 style='color: #666;'>Belum ada tiket yang dibeli</h4>
                              <p class='text-muted'>Dapatkan tiket konser BTS Anda sekarang!</p>
                              <a href='dasbor.php' class='btn btn-bts'>
                                  <i class='fa fa-shopping-cart'></i> Beli Tiket Sekarang
                              </a>
                          </td>
                      </tr>";
                  }
                  ?>
                  </tbody>
                </table>
              </div>
            </div>
          </div>
        </div>
        <div class="row mt-4">
          <div class="col-12">
            <div class="alert alert-info" style="border-left: 4px solid #8a2be2;">
              <h5><i class="fa fa-info-circle"></i> Informasi Penting:</h5>
              <ul class="mb-0">
                <li>Harap bawa ID yang valid sesuai dengan informasi tiket Anda</li>
                <li>Pintu buka pukul 15:00 WIB, konser dimulai pukul 19:00 WIB</li>
                <li>Tidak ada refund atau penukaran tiket</li>
                <li>Untuk paket VIP, check-in dimulai pukul 14:00 WIB</li>
              </ul>
            </div>
          </div>
        </div>
      </div>
    </section>
  </main>
  <footer id="footer">
    <div class="container">
      <div class="copyright">
        &copy; Copyright <strong>BTS LOVE YOURSELF World Tour 2026</strong>. All Rights Reserved
      </div>
      <div class="credits">
        Presented by HYBE & Big Hit Entertainment | Sri Yanti | Official Ticketing System
      </div>
    </div>
  </footer>
  <a href="#" class="back-to-top"><i class="fa fa-angle-up"></i></a>
  <script src="lib/jquery/jquery.min.js"></script>
  <script src="lib/jquery/jquery-migrate.min.js"></script>
  <script src="lib/bootstrap/js/bootstrap.bundle.min.js"></script>
  <script src="lib/easing/easing.min.js"></script>
  <script src="lib/wow/wow.min.js"></script>
  <script src="js/main.js"></script>
</body>
</html>