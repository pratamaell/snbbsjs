<?php
include 'koneksi.php';

$nisn='';
$data_siswa=null;
$data_pembayaran=[];
$error='';


if(isset($_GET['nisn'])){
    $nisn=$_GET['nisn'];

   $siswa= mysqli_query($koneksi,"SELECT * from siswa where nisn='$nisn' ORDER BY tgl_bayar asc");
   if(mysqli_num_rows($siswa)){
    $data_siswa= mysqli_fetch_assoc($siswa);

    $pembayaran= mysqli_query($koneksi,"SELECT * from pembayaran where nisn='$nisn' ORDER BY tgl_bayar asc");
    while($r= mysqli_fetch_assoc($pembayaran)){
        $data_pembayaran[]= $r;
    }
   }else{
    $error ="data siswa berdasarkan $nisn tersebut tidak ditemukan ";
   }
}
?>

<h2>Data Pembayaran Siswa</h2>

<form method="GET">
<label for="">Masukkan NISN</label>
<input type="text" name="nisn" value='<?php htmlspecialchars($nisn)?> ' required>
<button type="submit">Cari</button>
</form>