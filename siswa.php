<?php
include 'koneksi.php';

$nisn = '';
$nis = '';
$nama = '';
$alamat = '';
$no_telp = '';
$id_kelas = '';
$id_spp = '';
$sukses = '';
$error = '';

if (isset($_GET['op'])) {
    $op = $_GET['op'];
} else {
    $op = '';
}

// Delete
if ($op == 'delete') {
    $nisn = $_GET['nisn'];
    $sql = "DELETE FROM siswa WHERE nisn='$nisn'";
    $query = mysqli_query($koneksi, $sql);
    if ($query) $sukses = "Berhasil hapus data";
    else $error = "Gagal hapus data";
}

// Edit
if ($op == 'edit') {
    $nisn = $_GET['nisn'];
    $sql = "SELECT * FROM siswa WHERE nisn='$nisn'";
    $query = mysqli_query($koneksi, $sql);
    $data = mysqli_fetch_array($query);
    $nisn = $data['nisn'];
    $nis = $data['nis'];
    $nama = $data['nama'];
    $alamat = $data['alamat'];
    $no_telp = $data['no_telp'];
    $id_kelas = $data['id_kelas'];
    $id_spp = $data['id_spp'];
}

// Simpan (Tambah/Edit)
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nisn = $_POST['nisn'];
    $nis = $_POST['nis'];
    $nama = $_POST['nama'];
    $alamat = $_POST['alamat'];
    $no_telp = $_POST['no_telp'];
    $id_kelas = $_POST['id_kelas'];
    $id_spp = $_POST['id_spp'];

    if ($nisn && $nis && $nama && $alamat && $no_telp && $id_kelas && $id_spp) {
        if ($op == 'edit') {
            $sql = "UPDATE siswa SET nis='$nis', nama='$nama', alamat='$alamat', no_telp='$no_telp', id_kelas='$id_kelas', id_spp='$id_spp' WHERE nisn='$nisn'";
        } else {
            $sql = "INSERT INTO siswa (nisn, nis, nama, alamat, no_telp, id_kelas, id_spp) VALUES ('$nisn', '$nis', '$nama', '$alamat', '$no_telp', '$id_kelas', '$id_spp')";
        }

        $query = mysqli_query($koneksi, $sql);
        if ($query) $sukses = "Berhasil menyimpan data";
        else $error = "Gagal menyimpan data";
    } else {
        $error = "Silakan isi semua data!";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Data Siswa</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="container mt-4">
    <h2>Form Siswa</h2>
    <?php if ($sukses) echo "<div class='alert alert-success'>$sukses</div>"; ?>
    <?php if ($error) echo "<div class='alert alert-danger'>$error</div>"; ?>

    <form method="POST">
        <input type="text" name="nisn" class="form-control mb-2" placeholder="NISN" value="<?= $nisn ?>" <?= ($op == 'edit') ? 'readonly' : '' ?>>
        <input type="text" name="nis" class="form-control mb-2" placeholder="NIS" value="<?= $nis ?>">
        <input type="text" name="nama" class="form-control mb-2" placeholder="Nama" value="<?= $nama ?>">
        <input type="text" name="alamat" class="form-control mb-2" placeholder="Alamat" value="<?= $alamat ?>">
        <input type="text" name="no_telp" class="form-control mb-2" placeholder="No. Telp" value="<?= $no_telp ?>">

        <select name="id_kelas" class="form-control mb-2">
            <option value="">Pilih Kelas</option>
            <?php
            $kelas = mysqli_query($koneksi, "SELECT * FROM kelas");
            while ($k = mysqli_fetch_array($kelas)) {
                echo "<option value='$k[id_kelas]' ".($k['id_kelas'] == $id_kelas ? 'selected' : '')."> $k[nama_kelas] - $k[kompetensi_keahlian] </option>";
            }
            ?>
        </select>

        <select name="id_spp" class="form-control mb-2">
            <option value="">Pilih SPP</option>
            <?php
            $spp = mysqli_query($koneksi, "SELECT * FROM spp");
            while ($s = mysqli_fetch_array($spp)) {
                echo "<option value='$s[id_spp]' ".($s['id_spp'] == $id_spp ? 'selected' : '')."> Tahun: $s[tahun] - Rp ".number_format($s['nominal']) ." </option>";
            }
            ?>
        </select>

        <button type="submit" class="btn btn-primary">Simpan</button>
        <a href="siswa3.php" class="btn btn-secondary">Reset</a>
    </form>

    <h3 class="mt-4">Data Siswa</h3>
    <table class="table table-bordered">
        <tr>
            <th>No</th>
            <th>NISN</th>
            <th>NIS</th>
            <th>Nama</th>
            <th>Alamat</th>
            <th>No Telp</th>
            <th>Kelas</th>
            <th>SPP</th>
            <th>Aksi</th>
        </tr>
        <?php
        $no = 1;
        $siswa = mysqli_query($koneksi, "SELECT * FROM siswa ORDER BY nisn DESC");
        while ($r = mysqli_fetch_array($siswa)) {
            echo "<tr>
                <td>$no</td>
                <td>$r[nisn]</td>
                <td>$r[nis]</td>
                <td>$r[nama]</td>
                <td>$r[alamat]</td>
                <td>$r[no_telp]</td>
                <td>$r[id_kelas]</td>
                <td>$r[id_spp]</td>
                <td>
                    <a href='siswa3.php?op=edit&nisn=$r[nisn]' class='btn btn-warning btn-sm'>Edit</a>
                    <a href='siswa3.php?op=delete&nisn=$r[nisn]' onclick=\"return confirm('Hapus data?')\" class='btn btn-danger btn-sm'>Delete</a>
                </td>
            </tr>";
            $no++;
        }
        ?>
    </table>
</body>
</html>
