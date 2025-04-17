<?php
include 'config.php';

if ($_SERVER['REQUEST_METHOD'] == 'GET' && isset($_GET['id'])) {
  $id = mysqli_real_escape_string($conn, $_GET['id']); // Ambil id pengguna dari URL

  // Ambil informasi file gambar dari database sebelum menghapus data
  $query = "SELECT gambar FROM produk WHERE id_produk='$id'";
  $result = mysqli_query($conn, $query);

  if ($result && mysqli_num_rows($result) > 0) {
    $row = mysqli_fetch_assoc($result);
    $gambarPath = "Assets/Img/" . basename($row['gambar']); // Pastikan path lengkap

    // Hapus data dari database
    $queryDelete = "DELETE FROM produk WHERE id_produk='$id'";
    if (mysqli_query($conn, $queryDelete)) {
      // Hapus file gambar dari folder jika data berhasil dihapus dari database
      if (file_exists($gambarPath)) {
        unlink($gambarPath);
      }
      // Redirect setelah berhasil menghapus
      header('Location: produk.php?successDelete=1');
      exit;
    }
  }
}

mysqli_close($conn);
