<?php
include 'koneksi.php';

$nisn = '';
$data_siswa = null;
$data_pembayaran = [];
$error = '';

if (isset($_GET['nisn'])) {
    $nisn = $_GET['nisn'];

    // Ambil data siswa
    $q_siswa = mysqli_query($koneksi, "SELECT * FROM siswa WHERE nisn='$nisn'");
    if (mysqli_num_rows($q_siswa) > 0) {
        $data_siswa = mysqli_fetch_assoc($q_siswa);

        // Ambil data pembayaran siswa
        $q_pembayaran = mysqli_query($koneksi, "SELECT * FROM pembayaran WHERE nisn='$nisn' ORDER BY tgl_bayar ASC");
        while ($row = mysqli_fetch_assoc($q_pembayaran)) {
            $data_pembayaran[] = $row;
        }
    } else {
        $error = "Siswa dengan NISN $nisn tidak ditemukan.";
    }
}
?>

<h2>Cari Riwayat Pembayaran Siswa</h2>

<form method="GET">
  <label>Masukkan NISN:</label>
  <input type="text" name="nisn" value="<?= htmlspecialchars($nisn) ?>" required>
  <button type="submit">Cari</button>
</form>

<br>

<?php if ($error): ?>
    <div style="color: red;"><?= $error ?></div>
<?php elseif ($data_siswa): ?>
    <h3>Data Siswa</h3>
    <p><strong>Nama:</strong> <?= $data_siswa['nama'] ?? $data_siswa['nama_siswa'] ?></p>
    <p><strong>NIS:</strong> <?= $data_siswa['nis'] ?></p>
    <p><strong>Kelas:</strong> <?= $data_siswa['id_kelas'] ?></p>

    <h3>Riwayat Pembayaran SPP</h3>
    <?php if (count($data_pembayaran) > 0): ?>
        <table border="1" cellpadding="5">
            <tr>
                <th>No</th>
                <th>Tanggal Bayar</th>
                <th>Bulan Dibayar</th>
                <th>Tahun</th>
                <th>Jumlah Bayar</th>
            </tr>
            <?php
            $no = 1;
            $total = 0;
            foreach ($data_pembayaran as $bayar):
                $total += $bayar['jumlah_bayar'];
            ?>
                <tr>
                    <td><?= $no++ ?></td>
                    <td><?= $bayar['tgl_bayar'] ?></td>
                    <td><?= $bayar['bulan_dibayar'] ?></td>
                    <td><?= $bayar['tahun_dibayar'] ?></td>
                    <td>Rp. <?= number_format($bayar['jumlah_bayar']) ?></td>
                </tr>
            <?php endforeach; ?>
            <tr>
                <td colspan="4"><strong>Total Dibayar:</strong></td>
                <td><strong>Rp. <?= number_format($total) ?></strong></td>
            </tr>
        </table>
    <?php else: ?>
        <p>Siswa belum melakukan pembayaran.</p>
    <?php endif; ?>
<?php endif; ?>
