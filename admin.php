<?php
include 'process/database.php';
session_start();

if (!isset($_SESSION['username']) || !isset($_SESSION['level']) || $_SESSION['level'] != 1) {
    header("Location: index.php");
    exit();
}
$username = $_SESSION['username'];
if (isset($_POST['edit_user'])) {
    $id = intval($_POST['edit_user_id']);
    $nama = mysqli_real_escape_string($con, $_POST['edit_nama']);
    $email = mysqli_real_escape_string($con, $_POST['edit_email']);
    $telepon = mysqli_real_escape_string($con, $_POST['edit_telepon']);
    $alamat = mysqli_real_escape_string($con, $_POST['edit_alamat']);
    $query = "UPDATE pengunjung SET 
              nama_lengkap = '$nama',
              email = '$email',
              nomor_telp = '$telepon',
              alamat = '$alamat'
              WHERE id = $id";
    
    if (mysqli_query($con, $query)) {
        echo "<script>alert('Data user berhasil diperbarui!'); window.location.href='admin.php#users';</script>";
    } else {
        echo "<script>alert('Gagal memperbarui data user!'); window.location.href='admin.php#users';</script>";
    }
}
if (isset($_POST['edit_ticket'])) {
    $id = intval($_POST['edit_ticket_id']);
    $nama_kelas = mysqli_real_escape_string($con, $_POST['edit_nama_kelas']);
    $harga = intval($_POST['edit_harga']);
    $kapasitas = intval($_POST['edit_kapasitas']);
    $deskripsi = mysqli_real_escape_string($con, $_POST['edit_deskripsi']);
    $query = "UPDATE kelas_tiket SET 
              nama_kelas = '$nama_kelas',
              harga = $harga,
              kapasitas = $kapasitas,
              deskripsi = '$deskripsi'
              WHERE id_kelas = $id";
    
    if (mysqli_query($con, $query)) {
        echo "<script>alert('Tiket berhasil diperbarui!'); window.location.href='admin.php#tickets';</script>";
    } else {
        echo "<script>alert('Gagal memperbarui tiket!'); window.location.href='admin.php#tickets';</script>";
    }
}
if (isset($_POST['add_user'])) {
    $nama = mysqli_real_escape_string($con, $_POST['nama']);
    $email = mysqli_real_escape_string($con, $_POST['email']);
    $telepon = mysqli_real_escape_string($con, $_POST['telepon']);
    $alamat = mysqli_real_escape_string($con, $_POST['alamat']);
    $username_input = mysqli_real_escape_string($con, $_POST['username']);
    $query = "INSERT INTO pengunjung (nama_lengkap, email, nomor_telp, alamat) 
              VALUES ('$nama', '$email', '$telepon', '$alamat')";
    
    if (mysqli_query($con, $query)) {
        $pengunjung_id = mysqli_insert_id($con);
        
        $query2 = "INSERT INTO user (username, password, level, id_pengunjung) 
                   VALUES ('$username_input', MD5('123456'), 2, $pengunjung_id)";
        
        if (mysqli_query($con, $query2)) {
            echo "<script>alert('User berhasil ditambahkan! Password default: 123456'); window.location.href='admin.php#users';</script>";
        }
    }
}
if (isset($_POST['add_ticket'])) {
    $nama_kelas = mysqli_real_escape_string($con, $_POST['nama_kelas']);
    $harga = intval($_POST['harga']);
    $kapasitas = intval($_POST['kapasitas']);
    $deskripsi = mysqli_real_escape_string($con, $_POST['deskripsi']);
    $query = "INSERT INTO kelas_tiket (nama_kelas, harga, kapasitas, deskripsi) 
              VALUES ('$nama_kelas', $harga, $kapasitas, '$deskripsi')";
    if (mysqli_query($con, $query)) {
        echo "<script>alert('Tiket berhasil ditambahkan!'); window.location.href='admin.php#tickets';</script>";
    }
}
if (isset($_GET['delete_user'])) {
    $id = intval($_GET['delete_user']);
    $query1 = "DELETE FROM user WHERE id_pengunjung = $id";
    $query2 = "DELETE FROM pengunjung WHERE id = $id";
    if (mysqli_query($con, $query1) && mysqli_query($con, $query2)) {
        echo "<script>alert('User berhasil dihapus!'); window.location.href='admin.php#users';</script>";
    }
}
if (isset($_GET['delete_ticket'])) {
    $id = intval($_GET['delete_ticket']);
    $query = "DELETE FROM kelas_tiket WHERE id_kelas = $id";
    if (mysqli_query($con, $query)) {
        echo "<script>alert('Tiket berhasil dihapus!'); window.location.href='admin.php#tickets';</script>";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel - BTS LOVE YOURSELF</title>
    <link rel="stylesheet" href="assets/css/style.css">
    <link rel="stylesheet" href="assets/css/admin.css">
    <link rel="icon" href="assets/img/favicon.jpg">
</head>
<body style="background: #0a0a0a;">
    <button class="admin-mobile-toggle" onclick="toggleSidebar()">
        <i class="icon icon-menu"></i>
    </button>
    <aside class="admin-sidebar" id="adminSidebar">
        <div class="admin-sidebar-header">
            <div class="admin-sidebar-logo">
                <img src="assets/img/logo.png" alt="BTS Logo">
            </div>
            <h3>Admin Panel</h3>
        </div> 
        <ul class="admin-sidebar-menu">
            <li>
                <a href="#dashboard" class="active">
                    <i class="icon icon-home"></i>
                    <span>Dashboard</span>
                </a>
            </li>
            <li>
                <a href="#users">
                    <i class="icon icon-users"></i>
                    <span>Manajemen User</span>
                </a>
            </li>
            <li>
                <a href="#tickets">
                    <i class="icon icon-ticket"></i>
                    <span>Manajemen Tiket</span>
                </a>
            </li>
            <li>
                <a href="#transactions">
                    <i class="icon icon-shopping-cart"></i>
                    <span>Transaksi</span>
                </a>
            </li>
            <li>
                <a href="#reports">
                    <i class="icon icon-chart"></i>
                    <span>Laporan</span>
                </a>
            </li>
            <li>
                <a href="process/logout.php">
                    <i class="icon icon-logout"></i>
                    <span>Logout</span>
                </a>
            </li>
        </ul>
    </aside>
    <div id="admin-overlay" onclick="toggleSidebar()"></div>
    <main class="admin-main"> 
        <section id="dashboard">
            <div class="admin-header-top">
                <h2>Dashboard Overview</h2>
                <div class="user-info">
                    <span>Selamat datang, <strong><?php echo $username; ?></strong></span>
                </div>
            </div>
            <div class="stats-grid">
                <div class="admin-stat-card">
                    <div class="admin-stat-number">
                        <?php
                        $q = mysqli_query($con, "SELECT COUNT(*) total FROM transaksi WHERE terhapus='0'");
                        $d = mysqli_fetch_assoc($q);
                        echo $d['total'];
                        ?>
                    </div>
                    <div class="admin-stat-label">Total Transaksi</div>
                </div>
                <div class="admin-stat-card">
                    <div class="admin-stat-number">
                        <?php
                        $q = mysqli_query($con, "SELECT SUM(bayar) total FROM transaksi WHERE terhapus='0'");
                        $d = mysqli_fetch_assoc($q);
                        echo 'Rp ' . number_format($d['total'] ?? 0, 0, ',', '.');
                        ?>
                    </div>
                    <div class="admin-stat-label">Total Pendapatan</div>
                </div>
                <div class="admin-stat-card">
                    <div class="admin-stat-number">
                        <?php
                        $q = mysqli_query($con, "SELECT COUNT(*) total FROM pengunjung");
                        $d = mysqli_fetch_assoc($q);
                        echo $d['total'];
                        ?>
                    </div>
                    <div class="admin-stat-label">ARMY Members</div>
                </div>
                <div class="admin-stat-card">
                    <div class="admin-stat-number">
                        <?php
                        $q = mysqli_query($con, "SELECT COUNT(*) total FROM transaksi WHERE DATE(tanggal_transaksi)=CURDATE() AND terhapus='0'");
                        $d = mysqli_fetch_assoc($q);
                        echo $d['total'];
                        ?>
                    </div>
                    <div class="admin-stat-label">Penjualan Hari Ini</div>
                </div>
            </div>
        </section>
        
        <section id="users" class="admin-section">
            <div class="admin-card">
                <div class="admin-card-header">
                    <h3 class="admin-card-title">Manajemen User</h3>
                    <button class="btn-admin" onclick="openModal('addUserModal')">
                        <i class="icon icon-plus"></i> Tambah User
                    </button>
                </div>
                <div class="table-responsive">
                    <table class="data-table">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>ARMY ID</th>
                                <th>Nama Lengkap</th>
                                <th>Email</th>
                                <th>Telepon</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                        <?php
                        $q_users = mysqli_query($con, "SELECT * FROM pengunjung ORDER BY created_at DESC");
                        $no = 1;
                        if (mysqli_num_rows($q_users) > 0) {
                            while ($r = mysqli_fetch_assoc($q_users)) {
                                echo "<tr>
                                  <td>$no</td>
                                  <td>ARMY" . str_pad($r['id'], 5, '0', STR_PAD_LEFT) . "</td>
                                  <td>{$r['nama_lengkap']}</td>
                                  <td>{$r['email']}</td>
                                  <td>{$r['nomor_telp']}</td>
                                  <td>
                                    <div class='admin-actions'>
                                      <button class='btn-admin btn-edit btn-admin-sm' onclick='editUser(".json_encode($r).")'>
                                        <i class='icon icon-edit'></i> Edit
                                      </button>
                                      <a href='admin.php?delete_user={$r['id']}' 
                                         class='btn-admin btn-delete btn-admin-sm'
                                         onclick=\"return confirm('Hapus user ini?')\">
                                         <i class='icon icon-trash'></i> Hapus
                                      </a>
                                    </div>
                                  </td>
                                </tr>";
                                $no++;
                            }
                        } else {
                            echo "<tr><td colspan='6' class='text-center'>Tidak ada data user</td></tr>";
                        }
                        ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </section>
        
        <section id="tickets" class="admin-section">
            <div class="admin-card">
                <div class="admin-card-header">
                    <h3 class="admin-card-title">Manajemen Tiket</h3>
                    <button class="btn-admin" onclick="openModal('addTicketModal')">
                        <i class="icon icon-plus"></i> Tambah Tiket
                    </button>
                </div>
                <div class="table-responsive">
                    <table class="data-table">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Tipe Tiket</th>
                                <th>Harga</th>
                                <th>Kapasitas</th>
                                <th>Deskripsi</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                        <?php
                        $q = mysqli_query($con, "SELECT * FROM kelas_tiket");
                        $no = 1;
                        if (mysqli_num_rows($q) > 0) {
                            while ($r = mysqli_fetch_assoc($q)) {
                                echo "<tr>
                                  <td>$no</td>
                                  <td><strong>{$r['nama_kelas']}</strong></td>
                                  <td>Rp " . number_format($r['harga'], 0, ',', '.') . "</td>
                                  <td>{$r['kapasitas']}</td>
                                  <td>{$r['deskripsi']}</td>
                                  <td>
                                    <div class='admin-actions'>
                                      <button class='btn-admin btn-edit btn-admin-sm' onclick='editTicket(".json_encode($r).")'>
                                        <i class='icon icon-edit'></i> Edit
                                      </button>
                                      <a href='?delete_ticket={$r['id_kelas']}' 
                                         class='btn-admin btn-delete btn-admin-sm'
                                         onclick=\"return confirm('Hapus tiket ini?')\">
                                         <i class='icon icon-trash'></i> Hapus
                                      </a>
                                    </div>
                                  </td>
                                </tr>";
                                $no++;
                            }
                        } else {
                            echo "<tr><td colspan='6' class='text-center'>Belum ada tiket</td></tr>";
                        }
                        ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </section>
        
        <section id="transactions" class="admin-section">
            <div class="admin-card">
                <div class="admin-card-header">
                    <h3 class="admin-card-title">Riwayat Transaksi</h3>
                </div>
                <div class="table-responsive">
                    <table class="data-table">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>ID Transaksi</th>
                                <th>Customer</th>
                                <th>Tiket</th>
                                <th>Jumlah</th>
                                <th>Total</th>
                                <th>Tanggal</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                        <?php
                        $q = mysqli_query($con, "SELECT t.*, p.nama_lengkap 
                                                 FROM transaksi t 
                                                 JOIN pengunjung p ON t.id = p.id 
                                                 WHERE t.terhapus='0' 
                                                 ORDER BY t.tanggal_transaksi DESC");
                        $no = 1;
                        if (mysqli_num_rows($q) > 0) {
                            while ($r = mysqli_fetch_assoc($q)) {
                                echo "<tr>
                                  <td>$no</td>
                                  <td>TRX".str_pad($r['id_transaksi'],6,'0',STR_PAD_LEFT)."</td>
                                  <td>{$r['nama_lengkap']}</td>
                                  <td><span class='badge'>{$r['kelas']}</span></td>
                                  <td>{$r['jml_kursi']}</td>
                                  <td>Rp ".number_format($r['bayar'],0,',','.')."</td>
                                  <td>".date('d M Y',strtotime($r['tanggal_transaksi']))."</td>
                                  <td>
                                    <a href='process/hapusTransaksi.php?id={$r['id_transaksi']}' 
                                       class='btn-admin btn-delete btn-admin-sm'
                                       onclick=\"return confirm('Hapus transaksi?')\">
                                       <i class='icon icon-trash'></i>
                                    </a>
                                  </td>
                                </tr>";
                                $no++;
                            }
                        } else {
                            echo "<tr><td colspan='8' class='text-center'>Belum ada transaksi</td></tr>";
                        }
                        ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </section>
        
        <section id="reports" class="admin-section">
            <div class="admin-card">
                <div class="admin-card-header">
                    <h3 class="admin-card-title">Laporan Penjualan</h3>
                </div>
                <div class="table-responsive">
                    <table class="data-table">
                        <thead>
                            <tr>
                                <th>Tipe Tiket</th>
                                <th>Tiket Terjual</th>
                                <th>Pendapatan</th>
                            </tr>
                        </thead>
                        <tbody>
                        <?php
                        $q = mysqli_query($con, "SELECT kelas, SUM(jml_kursi) as total_sold, SUM(bayar) as revenue 
                                                 FROM transaksi WHERE terhapus='0' 
                                                 GROUP BY kelas");
                        while ($r = mysqli_fetch_assoc($q)) {
                            echo "<tr>
                              <td><strong>{$r['kelas']}</strong></td>
                              <td>{$r['total_sold']}</td>
                              <td>Rp " . number_format($r['revenue'], 0, ',', '.') . "</td>
                            </tr>";
                        }
                        ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </section>
        
    </main>

    <div class="modal" id="addUserModal">
        <div class="modal-content">
            <div class="modal-header">
                <h3><i class="icon icon-user"></i> Tambah User Baru</h3>
                <button class="modal-close" onclick="closeModal('addUserModal')">×</button>
            </div>
            <div class="modal-body">
                <form method="POST">
                    <div class="form-group">
                        <label class="form-label">Nama Lengkap</label>
                        <input type="text" name="nama" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Email</label>
                        <input type="email" name="email" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Telepon</label>
                        <input type="text" name="telepon" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Alamat</label>
                        <textarea name="alamat" class="form-control" rows="3" required></textarea>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Username</label>
                        <input type="text" name="username" class="form-control" required>
                        <small class="text-muted">Password default: 123456</small>
                    </div>
                    <div style="text-align: center; margin-top: 25px;">
                        <button type="submit" name="add_user" class="btn-admin">
                            <i class="icon icon-plus"></i> Tambah User
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="modal" id="editUserModal">
        <div class="modal-content">
            <div class="modal-header">
                <h3><i class="icon icon-edit"></i> Edit User</h3>
                <button class="modal-close" onclick="closeModal('editUserModal')">×</button>
            </div>
            <div class="modal-body">
                <form method="POST">
                    <input type="hidden" name="edit_user_id" id="edit_user_id">
                    <div class="form-group">
                        <label class="form-label">Nama Lengkap</label>
                        <input type="text" name="edit_nama" id="edit_nama" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Email</label>
                        <input type="email" name="edit_email" id="edit_email" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Telepon</label>
                        <input type="text" name="edit_telepon" id="edit_telepon" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Alamat</label>
                        <textarea name="edit_alamat" id="edit_alamat" class="form-control" rows="3" required></textarea>
                    </div>
                    <div style="text-align: center; margin-top: 25px;">
                        <button type="submit" name="edit_user" class="btn-admin">
                            <i class="icon icon-check"></i> Simpan Perubahan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="modal" id="addTicketModal">
        <div class="modal-content">
            <div class="modal-header">
                <h3><i class="icon icon-ticket"></i> Tambah Tipe Tiket</h3>
                <button class="modal-close" onclick="closeModal('addTicketModal')">×</button>
            </div>
            <div class="modal-body">
                <form method="POST">
                    <div class="form-group">
                        <label class="form-label">Nama Tiket</label>
                        <input type="text" name="nama_kelas" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Harga</label>
                        <input type="number" name="harga" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Kapasitas</label>
                        <input type="number" name="kapasitas" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Deskripsi</label>
                        <textarea name="deskripsi" class="form-control" rows="3" required></textarea>
                    </div>
                    <div style="text-align: center; margin-top: 25px;">
                        <button type="submit" name="add_ticket" class="btn-admin">
                            <i class="icon icon-plus"></i> Tambah Tiket
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="modal" id="editTicketModal">
        <div class="modal-content">
            <div class="modal-header">
                <h3><i class="icon icon-edit"></i> Edit Tiket</h3>
                <button class="modal-close" onclick="closeModal('editTicketModal')">×</button>
            </div>
            <div class="modal-body">
                <form method="POST">
                    <input type="hidden" name="edit_ticket_id" id="edit_ticket_id">
                    <div class="form-group">
                        <label class="form-label">Nama Tiket</label>
                        <input type="text" name="edit_nama_kelas" id="edit_nama_kelas" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Harga</label>
                        <input type="number" name="edit_harga" id="edit_harga" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Kapasitas</label>
                        <input type="number" name="edit_kapasitas" id="edit_kapasitas" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Deskripsi</label>
                        <textarea name="edit_deskripsi" id="edit_deskripsi" class="form-control" rows="3" required></textarea>
                    </div>
                    <div style="text-align: center; margin-top: 25px;">
                        <button type="submit" name="edit_ticket" class="btn-admin">
                            <i class="icon icon-check"></i> Simpan Perubahan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script src="assets/js/main.js"></script>
    <script>
    function toggleSidebar() {
        document.getElementById('adminSidebar').classList.toggle('active');
        document.getElementById('admin-overlay').classList.toggle('active');
    }
    
    function editUser(data) {
        document.getElementById('edit_user_id').value = data.id;
        document.getElementById('edit_nama').value = data.nama_lengkap;
        document.getElementById('edit_email').value = data.email;
        document.getElementById('edit_telepon').value = data.nomor_telp;
        document.getElementById('edit_alamat').value = data.alamat;
        openModal('editUserModal');
    }
    
    function editTicket(data) {
        document.getElementById('edit_ticket_id').value = data.id_kelas;
        document.getElementById('edit_nama_kelas').value = data.nama_kelas;
        document.getElementById('edit_harga').value = data.harga;
        document.getElementById('edit_kapasitas').value = data.kapasitas;
        document.getElementById('edit_deskripsi').value = data.deskripsi;
        openModal('editTicketModal');
    }
    
    document.querySelectorAll('.admin-sidebar-menu a').forEach(link => {
        link.addEventListener('click', function() {
            document.querySelectorAll('.admin-sidebar-menu a').forEach(l => l.classList.remove('active'));
            this.classList.add('active');
        });
    });
    </script>
</body>
</html>