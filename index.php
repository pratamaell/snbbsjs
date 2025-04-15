<?php
include 'koneksi.php';

$nisn = $_GET['nisn'] ?? '';
$data_siswa = null;
$data_pembayaran = [];
$error = '';

// Jika ada input NISN
if (!empty($nisn)) {
    // Ambil data siswa + info kelas
    $q_siswa = mysqli_query($koneksi, "
        SELECT siswa.*, kelas.nama_kelas, kelas.kompetensi_keahlian 
        FROM siswa 
        LEFT JOIN kelas ON siswa.id_kelas = kelas.id_kelas 
        WHERE nisn = '$nisn'
    ");

    if (mysqli_num_rows($q_siswa) > 0) {
        $data_siswa = mysqli_fetch_assoc($q_siswa);

        // Ambil data pembayaran siswa
        $q_pembayaran = mysqli_query($koneksi, "SELECT * FROM pembayaran WHERE nisn='$nisn' ORDER BY tgl_bayar ASC");
        while ($row = mysqli_fetch_assoc($q_pembayaran)) {
            $data_pembayaran[] = $row;
        }
    } else {
        $error = "Siswa dengan NISN <strong>$nisn</strong> tidak ditemukan.";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Riwayat Pembayaran Siswa</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        @media print {
            form, button { display: none; }
        }
    </style>
</head>
<body class="container mt-5">
    <h2 class="mb-4">Cari Riwayat Pembayaran Siswa</h2>

    <form method="GET" class="row g-3 mb-4">
        <div class="col-auto">
            <input type="text" name="nisn" class="form-control" placeholder="Masukkan NISN" value="<?= htmlspecialchars($nisn) ?>" required>
        </div>
        <div class="col-auto">
            <button type="submit" class="btn btn-primary">Cari</button>
            <?php if ($data_siswa): ?>
                <button type="button" class="btn btn-success" onclick="window.print()">Cetak</button>
            <?php endif; ?>
        </div>
    </form>

    <?php if ($error): ?>
        <div class="alert alert-danger"><?= $error ?></div>
    <?php elseif ($data_siswa): ?>
        <div class="mb-4">
            <h4>Data Siswa</h4>
            <ul class="list-group">
                <li class="list-group-item"><strong>Nama:</strong> <?= $data_siswa['nama'] ?? $data_siswa['nama_siswa'] ?></li>
                <li class="list-group-item"><strong>NIS:</strong> <?= $data_siswa['nis'] ?></li>
                <li class="list-group-item"><strong>Kelas:</strong> <?= $data_siswa['nama_kelas'] ?> - <?= $data_siswa['kompetensi_keahlian'] ?></li>
            </ul>
        </div>

        <h4>Riwayat Pembayaran SPP</h4>
        <?php if (count($data_pembayaran) > 0): ?>
            <table class="table table-bordered table-striped mt-3">
                <thead class="table-dark">
                    <tr>
                        <th>No</th>
                        <th>Tanggal Bayar</th>
                        <th>Bulan Dibayar</th>
                        <th>Tahun</th>
                        <th>Jumlah Bayar</th>
                    </tr>
                </thead>
                <tbody>
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
                        <td>Rp. <?= number_format($bayar['jumlah_bayar'], 0, ',', '.') ?></td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
                <tfoot>
                    <tr>
                        <td colspan="4"><strong>Total Dibayar</strong></td>
                        <td><strong>Rp. <?= number_format($total, 0, ',', '.') ?></strong></td>
                    </tr>
                </tfoot>
            </table>
        <?php else: ?>
            <div class="alert alert-warning">Siswa belum melakukan pembayaran.</div>
        <?php endif; ?>
    <?php endif; ?>
</body>
</html>
