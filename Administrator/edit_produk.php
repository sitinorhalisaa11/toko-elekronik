<?php
include '../config.php';

$target_dir = "Assets/Img/";

if (!file_exists($target_dir)) {
  mkdir($target_dir, 0777, true);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
  if (!empty($_POST['id_produk']) && !empty($_POST['nama_produk']) && !empty($_POST['kategori']) && !empty($_POST['harga'])) {
    $id_produk = mysqli_real_escape_string($conn, $_POST['id_produk']);
    $nama_produk = mysqli_real_escape_string($conn, $_POST['nama_produk']);
    $kategori = mysqli_real_escape_string($conn, $_POST['kategori']);
    $harga = mysqli_real_escape_string($conn, $_POST['harga']);

    // Cek apakah ada file gambar baru diupload
    if (isset($_FILES['Gambar']) && $_FILES['Gambar']['error'] === UPLOAD_ERR_OK) {
      $file_name = basename($_FILES['Gambar']['name']);
      $target_file = $target_dir . $file_name;

      // Ambil gambar lama dari database
      $result = mysqli_query($conn, "SELECT gambar FROM produk WHERE id_produk='$id_produk'");
      $row = mysqli_fetch_assoc($result);
      $old_image = $row['gambar'];

      // Hapus gambar lama jika ada
      if (!empty($old_image) && file_exists($target_dir . basename($old_image))) {
        unlink($target_dir . basename($old_image));
      }

      // Upload gambar baru
      if (move_uploaded_file($_FILES['Gambar']['tmp_name'], $target_file)) {
        $query = "UPDATE produk SET nama_produk='$nama_produk', kategori='$kategori', harga='$harga', gambar='$target_file' WHERE id_produk='$id_produk'";
      } else {
        header('Location: produk.php?error=Gagal mengunggah file gambar');
        exit;
      }
    } else {
      // Jika tidak upload gambar baru
      $query = "UPDATE produk SET nama_produk='$nama_produk', kategori='$kategori', harga='$harga' WHERE id_produk='$id_produk'";
    }

    // Eksekusi query
    if (mysqli_query($conn, $query)) {
      header('Location: produk.php?successEdit=1');
      exit;
    } else {
      header('Location: produk.php?error=' . urlencode(mysqli_error($conn)));
      exit;
    }
  } else {
    header('Location: produk.php?error=Harap isi semua field');
    exit;
  }
}
