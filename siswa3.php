<?php
include 'koneksi.php';

$nisn='';

if(isset($_GET['op'])){
    $op =$_GET['op'];
}else{
    $op='';
}

if($op=='delete'){
    $nisn=$_GET['nisn'];
    $sql1= "delete from siswa where nisn='$nisn";
    $q1= mysqli_query($koneksi,$sql1);
    if($q1){
        $sukses="Berhasil hapus data";
    }else{
        $error="Gagal hapus data";
    }
}

if($op=='edit'){
    $nisn=$_GET['nisn'];
    $sqll1="select * from siswa where nisn='$nisn'";
    $q1= mysqli_query($koneksi,$sql1);
    $r1=mysqli_fetch_array($q1);
    $nisn=$r1['nisn'];
}

if($op=='simpan'){
    $nisn=$_POST['nisn'];
    $nis=$_POST['nis'];

    if($nisn && $nis){
        if($op=='edit'){
            $sql1= "update siswa set nisn='$nisn',nis='$nis' where nisn='$nisn'";
            $q1=musqli_query($koneksi,$sql1);
            if($q1){
                $sukses="Berhasil edit data";
            }else{
                $error="Gagal edit data";
            }
        }else{
            $sql1= "insert into siswa (nisn,nis) values('$nisn','$nis')";
            $q1= mysqli_query($koneksi,$sql1);
            if($q1){
                $sukses="Berhasil simpan data";
            }else{  
                $error="Gagal simpan data";
            }
        }
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
            <table class="table">
                <thead>
                    <tr>
                        <th scope="col">#</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    include 'koneksi.php';
                    $sql2= "select * from siswa order by nisn desc";
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
                    <td scope= "row">
                        <a href="siswa3.php?op=edit&nisn=<?php echo $nisn?>"><button></button></a>
                        <a href="siswa3.php?op=delete&nisn=<?php $nisn?>"  onclick="return confirm('yakin ingin menghapus')"><button></button></a>
                    </td>
                  </tr>


                  <? }  ?>
                </tbody>
            </table>
        </div>
    </div>
   </div>
</body>
</html>