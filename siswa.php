
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
<form method="POST" enctype="multipart/form-data">
  <div class="mb-3">
    <label for="exampleInputEmail1" class="form-label">NISN</label>
    <input type="text" class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp" name="nisn" value="<?php echo $nisn ?>">
  </div>
  <div class="mb-3">
    <label for="exampleInputEmail1" class="form-label">NIS</label>
    <input type="text" class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp" name="nis" value="<?php echo $nis ?>">
  </div>
  <div class="mb-3">
    <label for="exampleInputEmail1" class="form-label">NAMA</label>
    <input type="text" class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp" name="nama" value="<?php echo $nama_siswa ?>">
  </div>
  <div class="mb-3">
    <label for="exampleInputEmail1" class="form-label">IDKELAS</label>
    <select name="id_kelas" class="form-control">
        <option value="">--Pilih Kelas--</option>
        <?php
        $kelas=mysqli_query($koneksi,"select *from kelas");
        while($row =mysqli_fetch_arrayO($kelas)){
        echo "<option value='$row[id_kelas]'
         ".($row['id_kelas']==$id_kelas ? 'selected' : '')."> $row[nama_kelas] - $row[kompetensi_keahlian] </option>";
        }
        ?>
    </select>
  </div>
  <div class="mb-3">
    <label for="exampleInputEmail1" class="form-label">ALAMAT</label>
    <input type="text" class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp" name="alamat" value="<?php echo $alamat_siswa ?>">
  </div>
  <div class="mb-3">
    <label for="exampleInputEmail1" class="form-label">NOTELP</label>
    <input type="text" class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp" name="no_telp" value="<?php echo $no_telp ?>">
  </div>
  <div class="mb-3">
    <label for="exampleInputEmail1" class="form-label">IDSPP</label>
    <select name="id_spp" class="form-control">
    <option value="">-- Pilih SPP --</option>
    <?php
      $spp = mysqli_query($koneksi, "SELECT * FROM spp");
      while($r = mysqli_fetch_array($spp)){
        echo "<option value='$r[id_spp]' ".($r['id_spp']==$id_spp ? 'selected' : '')."> Tahun: $r[tahun] - Rp ".number_format($r['nominal'],0,',','.') ." </option>";
      }
    ?>
  </select>
  </div>
  
  <button type="submit" class="btn btn-primary">Submit</button>
</form>
</body>
</html>