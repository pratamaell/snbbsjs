<?php
include 'koneksi.php';
session_start();

$id_barang="";
$nama_barang="";
$harga_barang="";
$jumlah_barang="";
$foto_barang="";
$sukses="";
$error="";

if(isset($_GET['op'])){
    $op = $_GET['op'];
}else{
    $op='';
}

if($op == 'delete'){
    $id_barang= $_GET['id'];
    $sql1= "delete from barang where id_barang='$id_barang'";
    $q1=mysqli_query($koneksi,$sql1);
    if($q1){
        $sukses="Berhasil Menghapus";
    }else{
        $error="Gagal Menghapus";
    }
}

if($op == 'edit'){
    $id_barang=$_GET['id'];
    $sql1="select * from barang where id_barang='$id_barang'";
    $q1=mysqli_query($koneksi,$sql1);
    $r1=mysqli_fetch_array($q1);
    $nama_barang=$r1['nama_barang'];
    $harga_barang=$r1['harga_barang'];
    $jumlah_barang=$r1['jumlah_barang'];

    if($nama_barang == ''){
        $error="Nama Barang Tidak Ada";
    }
}

if(isset($_POST['simpan'])){
    $nama_barang=$_POST['nama_barang'];
    $harga_barang=$_POST['harga_barang'];
    $jumlah_barang=$_POST['jumlah_barang'];
    $foto_barang=$_FILES['foto_barang']['name'];
    $file_tmp=$_FILES['foto_barang']['tmp_name'];
    move_uploaded_file($file_tmp,'images/'.$foto_barang);

    if($nama_barang && $harga_barang && $jumlah_barang && $foto_barang){
        if($op=='edit'){
            $sql1="UPDATE barang SET nama_barang='$nama_barang',harga_barang='$harga_barang',jumlah_barang='$jumlah_barang',foto_barang='$foto_barang' where id_barang='$id_barang'";
            $q1=mysqli_query($koneksi,$sql1);
            if($q1){
                $sukses="Berhasil Mengupdate Barang";
            }else{
                $error="gagal update";
            }
        }else{
            $sql1="INSERT INTO barang (nama_barang,harga_barang,jumlah_barang,foto_barang) values('$nama_barang','$harga_barang','$jumlah_barang','$foto_barang')";
            $q1=mysqli_query($koneksi,$sql1);
            if($q1){
                $sukses="Berhasil masuk barang";
            }else{
                $error="gagal masuk";
            }
        }
    }else{
        $error="gagalllllllll";
    }
}




?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">nk
</head>
<body>
    <?php 
    if($sukses){
        ?>
        <div class="alert alert-success" role="alert"></div>
        <?php echo $sukses?>
    
    <?php header("refresh:5;url.barang.php")?>

    <?php }?>
    
    
<form method="POST" enctype="multipart/form-data">
  <div class="mb-3">
    <label for="exampleInputEmail1" class="form-label">NAMA BARANG</label>
    <input type="text" class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp" name="nama_barang" value="<?php echo $nama_barang ?>">
  </div>
  <div class="mb-3">
    <label for="exampleInputEmail1" class="form-label">HARGA BARANG</label>
    <input type="text" class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp" name="harga_barang" value="<?php echo $harga_barang ?>">
  </div>
  <div class="mb-3">
    <label for="exampleInputEmail1" class="form-label">JUMLAH BARANG</label>
    <input type="text" class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp" name="jumlah_barang" value="<?php echo $jumlah_barang ?>">
  </div>
  <div class="mb-3">
    <label for="exampleInputEmail1" class="form-label">FOTO BARANG</label>
    <input type="file" class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp" name="foto_barang" value="<?php echo $foto_barang ?>">
  </div>
  
  <button type="submit" class="btn btn-primary">Submit</button>
</form>

<div class="card">
    <div class="card-header">
        <div class="card-body">
            <table class="table">
                <thead>
                    <tr>
                        <th scope="col">#</th>
                        <th scope="col">NAMa</th>
                        <th scope="col">HARGA</th>
                        <th scope="col">JUMLAH</th>
                        <th scope="col">FOTO</th>
                        <th scope="col">AKSI</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    include 'koneksi.php';
                    $sql2="select * from barang order by id_barang desc";
                    $q2= mysqli_query($koneksi,$sql2);
                    $urut=1;
                    while($data=mysqli_fetch_array($q2)){
                         $id_barang=$data['id_barang'];
                         $nama_barang=$data['nama_barang'];
                         $harga_barang=$data['harga_barang'];
                         $jumlah_barang=$data['jumlah_barang'];
                         $foto_barang=$data['foto_barang'];
                        ?>
                        <tr>
                            <td scope="row"><?php echo $urut++ ?></td>
                            <td scope="row"><?php echo $nama_barang ?></td>
                            <td scope="row"><?php echo $harga_barang ?></td>
                            <td scope="row"><?php echo $jumlah_barang ?></td>
                            <td scope="row"><img src="images/<?php echo $foto_barang ?>" alt=""></td>
                            <td scope="row">
                                <a href="barang.php?op=edit&id=<?php echo $id_barang?>"><button tyoe="button" class="btn btn-warning">Edit</button></a>
                                <a href="barang.php?op=delete&id=<?= $id_barang?>" onclick="return confirm('Yakin di hapus')"><button tyoe="button" class="btn btn-danger">Delete</button></a>
                            </td>
                        </tr>


                  <?php  }?>
                    
                   
                </tbody>
            </table>
        </div>
    </div>
</div>
    
</body>
</html>