<?php
include 'config.php';
session_start();

if (!isset($_SESSION['id_user'])) {
  header("Location: index.php");
  exit();
}

$siswa = [];
if (isset($_GET['nisn'])) {
  $nisn = $_GET['nisn'];
  $query = mysqli_query($conn, "SELECT siswa.*, spp.nominal, spp.id_spp FROM siswa 
                                JOIN spp ON siswa.id_spp = spp.id_spp 
                                WHERE siswa.nisn='$nisn'");
  $siswa = mysqli_fetch_assoc($query);
}

// Ambil semua siswa
$list_siswa = mysqli_query($conn, "SELECT nisn, nama FROM siswa");

// Ambil semua spp
$list_spp = mysqli_query($conn, "SELECT id_spp, nominal FROM spp");
?>

<!DOCTYPE html>
<html>
<head>
  <title>Pembayaran SPP</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="container py-5">
  <h2 class="mb-4">Form Pembayaran SPP</h2>

  <form method="GET" class="mb-4">
    <div class="row">
      <div class="col-md-6">
        <label for="nisn" class="form-label">Pilih Siswa (NISN - Nama):</label>
        <select name="nisn" id="nisn" class="form-select" onchange="this.form.submit()" required>
          <option value="">-- Pilih Siswa --</option>
          <?php while ($row = mysqli_fetch_assoc($list_siswa)) { ?>
            <option value="<?= $row['nisn'] ?>" <?= ($row['nisn'] == (@$siswa['nisn'] ?? '')) ? 'selected' : '' ?>>
              <?= $row['nisn'] ?> - <?= $row['nama'] ?>
            </option>
          <?php } ?>
        </select>
      </div>
    </div>
  </form>

  <?php if (!empty($siswa)) : ?>
    <form method="POST" class="row g-3">
      <input type="hidden" name="nisn" value="<?= $siswa['nisn'] ?>">

      <div class="col-md-6">
        <label class="form-label">Nama Siswa:</label>
        <input type="text" class="form-control" value="<?= $siswa['nama'] ?>" readonly>
      </div>

      <div class="col-md-6">
        <label class="form-label">Nominal SPP:</label>
        <input type="text" class="form-control" value="Rp. <?= number_format($siswa['nominal'], 0, ',', '.') ?>" readonly>
      </div>

      <div class="col-md-4">
        <label class="form-label">Bulan Bayar:</label>
        <select name="bulan_bayar" class="form-select" required>
          <option value="">-- Pilih Bulan --</option>
          <?php
          $bulan = ['Januari','Februari','Maret','April','Mei','Juni','Juli','Agustus','September','Oktober','November','Desember'];
          foreach ($bulan as $b) {
            echo "<option value='$b'>$b</option>";
          }
          ?>
        </select>
      </div>

      <div class="col-md-4">
        <label class="form-label">Tahun Bayar:</label>
        <input type="text" name="tahun_bayar" class="form-control" placeholder="Contoh: 2025" required>
      </div>

      <div class="col-md-4">
        <label class="form-label">ID SPP:</label>
        <select name="id_spp" class="form-select" required>
          <option value="">-- Pilih ID SPP --</option>
          <?php
          mysqli_data_seek($list_spp, 0); // reset pointer
          while ($spp = mysqli_fetch_assoc($list_spp)) {
            $selected = ($spp['id_spp'] == $siswa['id_spp']) ? 'selected' : '';
            echo "<option value='{$spp['id_spp']}' $selected>{$spp['id_spp']} - Rp. " . number_format($spp['nominal'], 0, ',', '.') . "</option>";
          }
          ?>
        </select>
      </div>

      <div class="col-12">
        <button type="submit" name="simpan" class="btn btn-primary">Bayar</button>
      </div>
    </form>
  <?php endif; ?>

<?php
if (isset($_POST['simpan'])) {
  $nisn         = $_POST['nisn'];
  $bulan_bayar  = $_POST['bulan_bayar'];
  $tahun_bayar  = $_POST['tahun_bayar'];
  $tgl_bayar    = date('Y-m-d');
  $id_user      = $_SESSION['id_user'];
  $id_spp       = $_POST['id_spp'];

  $get_spp = mysqli_query($conn, "SELECT nominal FROM spp WHERE id_spp='$id_spp'");
  $spp = mysqli_fetch_assoc($get_spp);
  $jumlah_bayar = $spp['nominal'];

  $query = mysqli_query($conn, "INSERT INTO pembayaran 
    (id_user, nisn, tgl_bayar, bulan_bayar, tahun_bayar, id_spp, jumlah_bayar)
    VALUES 
    ('$id_user', '$nisn', '$tgl_bayar', '$bulan_bayar', '$tahun_bayar', '$id_spp', '$jumlah_bayar')");

  if ($query) {
    echo "<script>alert('Pembayaran berhasil disimpan!'); window.location='dashboard.php';</script>";
  } else {
    echo "<div class='alert alert-danger mt-3'>Gagal menyimpan pembayaran.</div>";
  }
}
?>
</body>
</html>
