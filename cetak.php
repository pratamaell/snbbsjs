<?php
include 'koneksi.php';

$bulan = $_GET['bulan'] ?? date('F');
$tahun = $_GET['tahun'] ?? date('Y');

$bulan_list = ['Januari','Februari','Maret','April','Mei','Juni','Juli','Agustus','September','Oktober','November','Desember'];
?>

<h2>Laporan Pembayaran - <?= $bulan ?> <?= $tahun ?></h2>

<form method="GET">
    <select name="bulan">
        <?php foreach($bulan_list as $b): ?>
            <option value="<?= $b ?>" <?= ($b == $bulan) ? 'selected' : '' ?>><?= $b ?></option>
        <?php endforeach; ?>
    </select>
    <input type="number" name="tahun" value="<?= $tahun ?>">
    <button type="submit">Tampilkan</button>
    <button type="button" onclick="window.print()">Cetak</button>
</form>

<table border="1" cellpadding="5" cellspacing="0">
    <tr>
        <th>No</th>
        <th>Tgl Bayar</th>
        <th>NISN</th>
        <th>Nama</th>
        <th>Bulan</th>
        <th>Tahun</th>
        <th>Jumlah</th>
    </tr>
    <?php
    $no = 1;
    $total = 0;
    $q = mysqli_query($koneksi, "SELECT pembayaran.*, siswa.nama_siswa 
                                 FROM pembayaran 
                                 JOIN siswa ON pembayaran.nisn = siswa.nisn 
                                 WHERE bulan_dibayar='$bulan' AND tahun_dibayar='$tahun' 
                                 ORDER BY tgl_bayar DESC");
    while($d = mysqli_fetch_assoc($q)):
        $total += $d['jumlah_bayar'];
    ?>
    <tr>
        <td><?= $no++ ?></td>
        <td><?= $d['tgl_bayar'] ?></td>
        <td><?= $d['nisn'] ?></td>
        <td><?= $d['nama_siswa'] ?></td>
        <td><?= $d['bulan_dibayar'] ?></td>
        <td><?= $d['tahun_dibayar'] ?></td>
        <td>Rp. <?= number_format($d['jumlah_bayar']) ?></td>
    </tr>
    <?php endwhile; ?>
    <tr>
        <td colspan="6"><strong>Total</strong></td>
        <td><strong>Rp. <?= number_format($total) ?></strong></td>
    </tr>
</table>
