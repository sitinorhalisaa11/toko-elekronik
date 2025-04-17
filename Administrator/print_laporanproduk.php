<?php
include 'config.php';
?>
<!DOCTYPE html>
<html>

<head>
  <title>Print Produk</title>
  <style>
    body {
      font-family: Arial, sans-serif;
    }

    table {
      border-collapse: collapse;
      width: 100%;
    }

    th,
    td {
      border: 1px solid #333;
      padding: 8px;
      text-align: center;
    }

    @media print {
      .no-print {
        display: none;
      }
    }
  </style>
</head>

<body>
  <h2 style="text-align: center;">Laporan Data Produk</h2>
  <table>
    <thead>
      <tr>
        <th>No</th>
        <th>Gambar</th>
        <th>Produk</th>
        <th>Kategori</th>
        <th>Harga</th>
      </tr>
    </thead>
    <tbody>
      <?php
      $query = mysqli_query($conn, "SELECT * FROM produk");
      $no = 1;
      while ($view = mysqli_fetch_array($query)) :
      ?>
        <tr>
          <td><?= $no++; ?></td>
          <td><img src="<?= $view['gambar']; ?>" width="80"></td>
          <td><?= $view['nama_produk']; ?></td>
          <td><?= $view['kategori']; ?></td>
          <td><?= 'Rp ' . number_format($view['harga'], 0, ',', '.'); ?></td>
        </tr>
      <?php endwhile; ?>
    </tbody>
  </table>
  <br>
  <div style="text-align: center;" class="no-print">
    <button onclick="window.print()">Cetak Halaman Ini</button>
    <a href="produk.php">Kembali</a>
  </div>
</body>

</html>
