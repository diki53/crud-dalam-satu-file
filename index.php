<?php
function koneksi()
{
  return mysqli_connect('localhost', 'root', '', 'crud');
}

function detail($query)
{
  $koneksi = koneksi();
  $qry = mysqli_query($koneksi, $query);
  return mysqli_fetch_assoc($qry);
}


function query($query)
{
  $koneksi = koneksi();
  $qry = mysqli_query($koneksi, $query);

  $rows = [];
  while ($row = mysqli_fetch_assoc($qry)) {
    $rows[] = $row;
  }

  return $rows;
}

function tambah($data)
{
  $nama = htmlspecialchars($data['nama']);
  $nim = htmlspecialchars($data['nim']);
  $telp = htmlspecialchars($data['telp']);

  $koneksi = koneksi();

  $query = "INSERT INTO mahasiswa values('','$nama','$nim','$telp')";

  mysqli_query($koneksi, $query);
  echo mysqli_error($koneksi);

  return mysqli_affected_rows($koneksi);
}

function ubah($data)
{
  $id = $_POST['id'];
  $nama = htmlspecialchars($data['nama']);
  $nim = htmlspecialchars($data['nim']);
  $telp = htmlspecialchars($data['telp']);

  $koneksi = koneksi();

  $query = "UPDATE mahasiswa SET nama='$nama', nim='$nim', telepon='$telp' WHERE id_mahasiswa=$id";

  mysqli_query($koneksi, $query);
  echo mysqli_error($koneksi);
  return mysqli_affected_rows($koneksi);
}

if (isset($_POST['tambah'])) {
  if ($tambah = tambah($_POST) > 0) {
    echo "
    <script>
    alert('Data Berhasil Ditambahkan');
    document.location.href='./';
    </script>";
  } else {
    echo "
    <script>
    alert('Data Gagal Ditambahkan !');
    document.location.href='./';
    </script>";
  }
}

if (isset($_POST['ubah'])) {
  if ($ubah = ubah($_POST) > 0) {
    echo "
    <script>
    alert('Data Berhasil Diubah');
    document.location.href='./';
    </script>";
  } else {
    echo "
    <script>
    alert('Data Gagal Diubah !');
    document.location.href='./';
    </script>";
  }
}
function hapus($id)
{
  $koneksi = koneksi();
  $hapus =   mysqli_query($koneksi, "DELETE FROM mahasiswa WHERE id_mahasiswa=$id");

  echo mysqli_error($koneksi);
  return mysqli_affected_rows($koneksi);
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Membuat Crud Dalam Satu File</title>
</head>

<body>
  <h3>Membuat Crud Dalam Satu File php</h3>

  <?php
  $page = @$_GET['page'];
  switch ($page) {
    case "hapus":
      $id = $_GET['id'];
      if ($hapus = hapus($id) > 0) {
        echo "
    <script>
    alert('Data Berhasil Dihapus');
    document.location.href='./';
    </script>";
      } else {
        echo "
    <script>
    alert('Data Gagal Dihapus !');
    document.location.href='./';
    </script>";
      }

      break;
    case "edit":
      $id = $_GET['id'];
      $mahasiswa = detail("SELECT * FROM mahasiswa WHERE id_mahasiswa=$id");
  ?>
      <h3>Ubah Data</h3>
      <form action="" method="POST">

        <ul>
          <li>
            Nama :
            <input type="text" name="nama" value="<?= $mahasiswa['nama']; ?>">
          </li>
          <li>
            Nim :
            <input type="text" name="nim" value="<?= $mahasiswa['nim']; ?>">
          </li>
          <li>
            Telepon :
            <input type="text" name="telp" value="<?= $mahasiswa['telepon']; ?>">
          </li>
          <li>
            <input type="hidden" name="id" value="<?= $mahasiswa['id_mahasiswa']; ?>">
            <button name="ubah" type="submit">Tambah</button>
          </li>
        </ul>

        <a href="./"> Kembali </a>

      </form>
    <?php
      break;
    case "tambah":
    ?>
      <h3>Tambah Data</h3>

      <form action="" method="POST">

        <ul>
          <li>
            Nama :
            <input type="text" name="nama">
          </li>
          <li>
            Nim :
            <input type="text" name="nim">
          </li>
          <li>
            Telepon :
            <input type="text" name="telp">
          </li>
          <li>
            <button name="tambah" type="submit">Tambah</button>
          </li>
        </ul>

        <a href="./"> Kembali </a>

      </form>

    <?php
      break;
    default:
    ?>
      <a href="?page=tambah">Tambah Data</a>
      <br>
      <br>
      <table border="1" cellspacing="0" cellpadding="10">
        <tr>
          <th>Nama</th>
          <th>Nim</th>
          <th>Telepon</th>
          <th>Aksi</th>
        </tr>
        <?php
        $mahasiswa = query("SELECT * FROM mahasiswa");
        ?>
        <?php foreach ($mahasiswa as $mhs) : ?>
          <tr>
            <td><?= $mhs['nama']; ?></td>
            <td><?= $mhs['nim']; ?></td>
            <td><?= $mhs['telepon']; ?></td>
            <td><a href="?page=edit&id=<?= $mhs['id_mahasiswa']; ?>">Edit Data</a> |
              <a href="?page=hapus&id=<?= $mhs['id_mahasiswa']; ?>" onclick="return confirm('Apakah Anda Yakin ?');">Hapus</a></td>
          </tr>
        <?php endforeach; ?>
      </table>

  <?php
      break;
  }

  ?>

</body>

</html>