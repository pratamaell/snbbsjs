<?php
include 'koneksi.php';

$bulan = $_GET['bulan'] ?? date('F');
$tahun = $_GET['tahun'] ?? date('Y');
$bulan_list = ['Januari','Februari','Maret','April','Mei','Juni','Juli','Agustus','September','Oktober','November','Desember'];
?>

<!DOCTYPE html>
<html>
<head>
    <title>Laporan Pembayaran</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        @media print {
            button, form { display: none; }
            table { font-size: 12pt; }
        }
    </style>
</head>
<body class="container mt-4">
    <h2 class="mb-4">Laporan Pembayaran SPP - <?= $bulan ?> <?= $tahun ?></h2>

    <form method="GET" class="row g-3 align-items-center mb-3">
        <div class="col-auto">
            <select name="bulan" class="form-select">
                <?php foreach($bulan_list as $b): ?>
                    <option value="<?= $b ?>" <?= ($b == $bulan) ? 'selected' : '' ?>><?= $b ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="col-auto">
            <input type="number" name="tahun" value="<?= $tahun ?>" class="form-control" min="2020" max="<?= date('Y') ?>">
        </div>
        <div class="col-auto">
            <button type="submit" class="btn btn-primary">Tampilkan</button>
            <button type="button" class="btn btn-success" onclick="window.print()">Cetak</button>
        </div>
    </form>

    <table class="table table-bordered table-striped">
        <thead class="table-dark">
            <tr>
                <th>No</th>
                <th>Tanggal Bayar</th>
                <th>NISN</th>
                <th>Nama</th>
                <th>Bulan</th>
                <th>Tahun</th>
                <th>Jumlah</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $no = 1;
            $total = 0;
            $q = mysqli_query($koneksi, "SELECT pembayaran.*, siswa.nama AS nama_siswa 
                                        FROM pembayaran 
                                        JOIN siswa ON pembayaran.nisn = siswa.nisn 
                                        WHERE bulan_dibayar='$bulan' AND tahun_dibayar='$tahun' 
                                        ORDER BY tgl_bayar DESC");

            if ($q && mysqli_num_rows($q) > 0):
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
                <td>Rp. <?= number_format($d['jumlah_bayar'], 0, ',', '.') ?></td>
            </tr>
            <?php endwhile; else: ?>
            <tr><td colspan="7" class="text-center">Tidak ada data untuk bulan ini.</td></tr>
            <?php endif; ?>
        </tbody>
        <tfoot>
            <tr>
                <th colspan="6" class="text-end">Total</th>
                <th>Rp. <?= number_format($total, 0, ',', '.') ?></th>
            </tr>
        </tfoot>
    </table>
</body>
</html>
