<?php

include 'koneksi.php';

$nisn='';
$nis='';
$nama_siswa='';
$id_kelas='';
$alamat_siswa='';   
$no_telp='';
$id_spp='';
$sukses = '';
$error = '';

if(isset($_GET['op'])){
    $op = $_GET['op'];
}else{
    $op = '';
}

if($op =='delete'){
    $nisn=$_GET['nisn'];
    $sql1="delete *from siswa where nisn='$nisn'";
    $q1= mysqli_query($koneksi,$sql1);
    if($q1){
        $sukses="Berhasil hapus data";
    }else{
        $error="Gagal hapus data";
    }
}

if($op =='edit'){
    $nisn = $_GET['nisn'];
    $sql1= "select * from siswa where nisn='$nisn";
    $q1=mysqli_query($koneksi,$sql1);
    $r1=mysqli_fetch_array($q1);
    $nisn=$r1['nisn'];
    $nis=$r1['nis'];
    $nama_siswa=$r1['nama'];    
    $id_kelas=$r1['id_kelas'];
    $alamat_siswa=$r1['alamat'];
    $no_telp=$r1['no_telp'];
    $id_spp=$r1['id_spp'];

    if($nama_siswa==''){
        $error="Data tidak ditemukan";
    }
}

if($op =='simpan'){
    $nisn= $_POST['nisn'];
    $nis= $_POST['nis'];
    $nama_siswa= $_POST['nama'];
    $id_kelas= $_POST['id_kelas'];
    $alamat_siswa= $_POST['alamat'];
    $no_telp= $_POST['no_telp'];
    $id_spp= $_POST['id_spp'];

    if($nisn && $nis && $nama_siswa && $id_kelas && $alamat_siswa && $no_telp && $id_spp){
       if($op=='edit'){
        $sql1="UPDATE siswa SET  nis='$nis', nama='$nama_siswa', id_kelas='$id_kelas', alamat='$alamat_siswa', no_telp='$no_telp', id_spp='$id_spp' WHERE nisn='$nisn'";
        $q1-mysqli_query($koneksi,$sql1);
        if($q1){
            $sukses="Berhasil edit data";
        }else{
            $error="Gagal edit data";
        }
       }else{
        $sql1="INSERT INTO siswa (nisn, nis, nama, id_kelas, alamat, no_telp, id_spp) VALUES ('$nisn', '$nis', '$nama_siswa', '$id_kelas', '$alamat_siswa', '$no_telp', '$id_spp')";
        $q1=mysqli_query($koneksi,$sql1);
        if($q1){
            $sukses="Berhasil simpan data";
        }else{
            $error="Gagal simpan data";
        }
       }
    
}else{
        $error="Silahkan isi semua data";
    }
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <div class="card">
        <div class="card-header">
            <div class="card-body">
                <table class="tabel">
                    <thead>
                        <tr>
                            <th scope="col">#</th>
                            <th scope="col">NISN</th>
                            <th scope="col">NIS</th>
                            <th scope="col">NAMA</th>
                            <th scope="col">ALAMAT</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php

                        include 'koneksi.php';
                        $sql2 = "select * from siswa order by nisn desc";
                        $q2= mysqli_query($koneksi,$sql2);
                        $urut=1;
                        while($r=mysqli_fetch_array($q2)){
                            $nisn=$r['nisn'];
                            $nis=$r['nis'];
                            $nama_siswa=$r['nama'];
                            $alamat_siswa=$r['alamat'];
                            $no_telp=$r['no_telp'];
                            $id_kelas=$r['id_kelas'];
                            $id_spp=$r['id_spp'];
                            ?>
                            <tr>
                                <td scope="row"><?php echo $urut++?></td>
                                <td scope="row"><?php echo $nisn?></td>
                                <td scope="row">
                                    <a href="siswa2.php?op=edit&nisn=<?php echo $nisn;?>"><button type="button" class="btn btn-warning">EDIT</button></a>
                                    <a href="siswa2.php?op=delete&nisn=<?php  $nisn?>"  onclick="return confirm('Yakin ingin hapus?')"><button type="button" class="btn btn-danger">Delete</button></a>
                                </td>
                                
                            </tr>
                        <?php }?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</body>
</html>
