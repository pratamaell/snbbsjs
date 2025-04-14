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

// ambil semua data siswa buat dropdown
$list_siswa = mysqli_query($conn, "SELECT nisn, nama FROM siswa");

// ambil semua spp buat dropdown
$list_spp = mysqli_query($conn, "SELECT id_spp, nominal FROM spp");
?>

<h2>Form Pembayaran SPP</h2>
<form method="POST">
  <div>
    <label>NISN:</label>
    <select name="nisn" onchange="this.form.submit()" required>
      <option value="">-- Pilih Siswa --</option>
      <?php while ($row = mysqli_fetch_assoc($list_siswa)) { ?>
        <option value="<?= $row['nisn'] ?>" <?= ($row['nisn'] == @$siswa['nisn']) ? 'selected' : '' ?>>
          <?= $row['nisn'] ?> - <?= $row['nama'] ?>
        </option>
      <?php } ?>
    </select>
  </div>

  <?php if (!empty($siswa)) { ?>
    <div>
      <label>Nama Siswa:</label>
      <input type="text" value="<?= $siswa['nama'] ?>" readonly>
    </div>

    <div>
      <label>Nominal SPP:</label>
      <input type="text" value="Rp. <?= number_format($siswa['nominal']) ?>" readonly>
    </div>

    <div>
      <label>Bulan Bayar:</label>
      <select name="bulan_bayar" required>
        <option value="">-- Pilih Bulan --</option>
        <?php
        $bulan = ['Januari','Februari','Maret','April','Mei','Juni','Juli','Agustus','September','Oktober','November','Desember'];
        foreach ($bulan as $b) {
          echo "<option value='$b'>$b</option>";
        }
        ?>
      </select>
    </div>

    <div>
      <label>Tahun Bayar:</label>
      <input type="text" name="tahun_bayar" placeholder="Contoh: 2025" required>
    </div>

    <div>
      <label>ID SPP:</label>
      <select name="id_spp" required>
        <option value="">-- Pilih ID SPP --</option>
        <?php while ($spp = mysqli_fetch_assoc($list_spp)) { ?>
          <option value="<?= $spp['id_spp'] ?>" <?= ($spp['id_spp'] == $siswa['id_spp']) ? 'selected' : '' ?>>
            <?= $spp['id_spp'] ?> - Rp. <?= number_format($spp['nominal']) ?>
          </option>
        <?php } ?>
      </select>
    </div>

    <button type="submit" name="simpan">Bayar</button>
  <?php } ?>
</form>

<?php
if (isset($_POST['simpan'])) {
  $nisn         = $_POST['nisn'];
  $bulan_bayar  = $_POST['bulan_bayar'];
  $tahun_bayar  = $_POST['tahun_bayar'];
  $tgl_bayar    = date('Y-m-d');
  $id_user      = $_SESSION['id_user'];
  $id_spp       = $_POST['id_spp'];

  // ambil nominal dari spp
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
    echo "<script>alert('Gagal menyimpan pembayaran');</script>";
  }
}
?>
