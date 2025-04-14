<?php
include 'koneksi.php';
session_start();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Riwayat Pembayaran SPP</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="container mt-5">

    <h2>Riwayat Pembayaran SPP</h2>
    <table class="table table-bordered table-striped mt-4">
        <thead>
            <tr>
                <th>No</th>
                <th>NISN</th>
                <th>Nama Siswa</th>
                <th>Tanggal Bayar</th>
                <th>Bulan Dibayar</th>
                <th>Tahun Dibayar</th>
                <th>Jumlah Bayar</th>
            </tr>
        </thead>
        <tbody>
        <?php
        $no = 1;
        $sql = "
            SELECT pembayaran.*, siswa.nama AS nama_siswa
            FROM pembayaran 
            JOIN siswa ON pembayaran.nisn = siswa.nisn
            ORDER BY pembayaran.tgl_bayar DESC
        ";
        $query = mysqli_query($koneksi, $sql);
        while($data = mysqli_fetch_array($query)) {
        ?>
            <tr>
                <td><?= $no++ ?></td>
                <td><?= $data['nisn'] ?></td>
                <td><?= $data['nama_siswa'] ?></td>
                <td><?= $data['tgl_bayar'] ?></td>
                <td><?= $data['bulan_bayar'] ?></td>
                <td><?= $data['tahun_bayar'] ?></td>
                <td>Rp. <?= number_format($data['jumlah_bayar']) ?></td>
            </tr>
        <?php } ?>
        </tbody>
    </table>

</body>
</html>
