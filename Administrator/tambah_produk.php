<?php
include 'config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $nama_produk = mysqli_real_escape_string($conn, $_POST['nama_produk']);
  $kategori = mysqli_real_escape_string($conn, $_POST['kategori']);
  $harga = mysqli_real_escape_string($conn, $_POST['harga']);

  // Upload gambar
  $gambar = $_FILES['Gambar']['name'];
  $tmp_name = $_FILES['Gambar']['tmp_name'];
  $folder_tujuan = 'Assets/Img/';

  // Validasi ekstensi file
  $allowed_extensions = ['jpg', 'jpeg', 'png', 'gif'];
  $file_extension = strtolower(pathinfo($gambar, PATHINFO_EXTENSION));

  if (!in_array($file_extension, $allowed_extensions)) {
    echo "<script>alert('Format gambar tidak valid! Hanya jpg, jpeg, png, dan gif.'); window.location.href='produk.php';</script>";
    exit;
  }

  // Gunakan nama asli file, hindari overwrite
  $file_name_no_ext = pathinfo($gambar, PATHINFO_FILENAME);
  $nama_file_baru = basename($gambar);
  $target_file = $folder_tujuan . $nama_file_baru;
  $counter = 1;

  while (file_exists($target_file)) {
    $nama_file_baru = $file_name_no_ext . '_' . $counter . '.' . $file_extension;
    $target_file = $folder_tujuan . $nama_file_baru;
    $counter++;
  }

  // Upload dan simpan ke database
  if (move_uploaded_file($tmp_name, $target_file)) {
    $query = "INSERT INTO produk (gambar, nama_produk, kategori, harga)
                  VALUES ('$target_file', '$nama_produk', '$kategori', '$harga')";

    if (mysqli_query($conn, $query)) {
      echo "<script>alert('Produk berhasil ditambahkan!'); window.location.href='produk.php';</script>";
    } else {
      echo "<script>alert('Gagal menyimpan data ke database!'); window.location.href='produk.php';</script>";
    }
  } else {
    echo "<script>alert('Gagal mengupload gambar!'); window.location.href='produk.php';</script>";
  }
}
