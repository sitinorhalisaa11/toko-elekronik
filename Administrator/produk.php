<?php
session_start();
if (!isset($_SESSION['username'])) {
  header("Location: ../index.php");
  exit();
}
?>

<?php include 'header.php'; ?>
<style media="print">
  .no-print,
  .no-print * {
    display: none !important;
  }

  th:nth-child(6),
  td:nth-child(6) {
    display: none !important;
  }
</style>


<?php include 'sidebar.php'; ?>
<div class="content-wrapper">
  <div class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-6">
          <h1 class="m-0">Halaman Produk</h1>
        </div>
        <div class="col-sm-6">
          <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="#">Home</a></li>
            <li class="breadcrumb-item active">Produk</li>
          </ol>
        </div>
      </div>
    </div>
  </div>

  <section class="content">
    <div class="container-fluid">
      <div class="row">
        <div class="col-12">
          <div class="card">
            <div class="card-body">
              <div class="row mb-3">
                <div class="col-sm-6 text-left no-print">
                  <a href="#" class="btn btn-primary" data-toggle="modal" data-target="#modalAdd">Tambah Data</a>
                </div>
                <div class="col-sm-6 text-right">
                  <button onclick="window.print()" class="btn btn-secondary no-print">
                    <i class="fas fa-print"></i> Print Laporan
                  </button>
                </div>
              </div>

              <table class="table table-bordered table-hover">
                <thead>
                  <tr class="text-center">
                    <th style="width: 3%;">No</th>
                    <th>Gambar</th>
                    <th>Produk</th>
                    <th>Kategori</th>
                    <th>Harga</th>
                    <th style="width: 10%;">Aksi</th> <!-- Kolom ke-6 -->
                  </tr>
                </thead>
                <tbody>
                  <?php
                  include 'config.php';
                  $query = mysqli_query($conn, "SELECT * FROM produk");
                  $no = 1;
                  while ($view = mysqli_fetch_array($query)) :
                  ?>
                    <tr>
                      <td class="text-center"><?= $no++; ?></td>
                      <td class="text-center"><img src="<?= $view['gambar'] ?>" alt="gambar" width="100"></td>
                      <td class="text-center"><?= $view['nama_produk']; ?></td>
                      <td class="text-center"><?= $view['kategori']; ?></td>
                      <td class="text-center">
                        <strong style="color:green;"><?= 'Rp ' . number_format($view['harga'], 0, ',', '.'); ?></strong>
                      </td>
                      <td class="text-center">
                        <!-- Ini juga akan hilang saat print -->
                        <a href="#" class="btn btn-success btn-sm" data-toggle="modal" data-target="#modalEdit<?= $view['id_produk']; ?>">
                          <i class="fas fa-pencil-alt"></i>
                        </a>
                        <a href="delete_produk.php?id=<?= $view['id_produk']; ?>" onclick="return confirm('Apakah Anda yakin ingin menghapus data ini?')" class="btn btn-danger btn-sm">
                          <i class="fas fa-trash-alt"></i>
                        </a>
                      </td>
                    </tr>

                    <!-- Edit Modal -->
                    <div class="modal fade" id="modalEdit<?= $view['id_produk']; ?>">
                      <div class="modal-dialog modal-lg">
                        <div class="modal-content">
                          <div class="modal-header">
                            <h4 class="modal-title">Edit Data Produk</h4>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                              <span aria-hidden="true">&times;</span>
                            </button>
                          </div>
                          <form class="form-horizontal" action="edit_produk.php" method="post" enctype="multipart/form-data">
                            <input type="hidden" name="id_produk" value="<?= $view['id_produk']; ?>">
                            <div class="modal-body">
                              <div class="card-body">
                                <div class="form-group row">
                                  <label class="col-sm-2 col-form-label">Gambar</label>
                                  <div class="col-sm-10">
                                    <input type="file" class="form-control" name="Gambar">
                                    <img src="<?= $view['gambar']; ?>" width="100">
                                  </div>
                                </div>
                                <div class="form-group row">
                                  <label class="col-sm-2 col-form-label">Produk</label>
                                  <div class="col-sm-10">
                                    <input type="text" class="form-control" name="nama_produk" value="<?= $view['nama_produk']; ?>" required>
                                  </div>
                                </div>
                                <div class="form-group row">
                                  <label class="col-sm-2 col-form-label">Kategori</label>
                                  <div class="col-sm-10">
                                    <select class="form-control" name="kategori" required>
                                      <option value="">Pilih Kategori</option>
                                      <option value="Iphone" <?= ($view['kategori'] == 'Iphone') ? 'selected' : ''; ?>>Iphone</option>
                                      <option value="Android" <?= ($view['kategori'] == 'Android') ? 'selected' : ''; ?>>Android</option>
                                    </select>
                                  </div>
                                </div>
                                <div class="form-group row">
                                  <label class="col-sm-2 col-form-label">Harga</label>
                                  <div class="col-sm-10">
                                    <input type="text" class="form-control" name="harga" value="<?= $view['harga']; ?>" required>
                                  </div>
                                </div>
                              </div>
                            </div>
                            <div class="modal-footer justify-content-between">
                              <button type="button" class="btn btn-default" data-dismiss="modal">Tutup</button>
                              <button type="submit" class="btn btn-primary">Simpan Data</button>
                            </div>
                          </form>
                        </div>
                      </div>
                    </div>
                  <?php endwhile; ?>
                </tbody>
              </table>
              <br>
              <?php
              $jumlahIphoneQuery = mysqli_query($conn, "SELECT COUNT(*) FROM produk WHERE kategori = 'Iphone'");
              $jumlahAndroidQuery = mysqli_query($conn, "SELECT COUNT(*) FROM produk WHERE kategori = 'Android'");
              $jumlahIphone = mysqli_fetch_array($jumlahIphoneQuery)[0];
              $jumlahAndroid = mysqli_fetch_array($jumlahAndroidQuery)[0];
              ?>
              <p>Total Produk Iphone: <?= $jumlahIphone; ?> Data</p>
              <p>Total Produk Android: <?= $jumlahAndroid; ?> Data</p>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>
</div>

<!-- Add Modal -->
<div class="modal fade" id="modalAdd">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title">Tambah Produk</h4>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <form class="form-horizontal" action="tambah_produk.php" method="post" enctype="multipart/form-data">
        <div class="modal-body">
          <div class="card-body">
            <div class="form-group row">
              <label class="col-sm-3 col-form-label">Gambar</label>
              <div class="col-sm-9">
                <input type="file" class="form-control" name="Gambar" required>
              </div>
            </div>
            <div class="form-group row">
              <label class="col-sm-3 col-form-label">Produk</label>
              <div class="col-sm-9">
                <input type="text" class="form-control" name="nama_produk" required>
              </div>
            </div>
            <div class="form-group row">
              <label class="col-sm-3 col-form-label">Kategori</label>
              <div class="col-sm-9">
                <select class="form-control" name="kategori" required>
                  <option value="">Pilih Kategori</option>
                  <option value="Iphone">Iphone</option>
                  <option value="Android">Android</option>
                </select>
              </div>
            </div>
            <div class="form-group row">
              <label class="col-sm-3 col-form-label">Harga</label>
              <div class="col-sm-9">
                <input type="text" class="form-control" name="harga" required>
              </div>
            </div>
          </div>
        </div>
        <div class="modal-footer justify-content-between">
          <button type="button" class="btn btn-default" data-dismiss="modal">Tutup</button>
          <button type="submit" class="btn btn-primary">Simpan</button>
        </div>
      </form>
    </div>
  </div>
</div><br><br>

<?php include 'footer.php'; ?>

<!-- Scripts -->
<script>
  function printLaporan() {
    var printContents = document.getElementById("laporan").innerHTML;
    var originalContents = document.body.innerHTML;
    document.body.innerHTML = printContents;
    window.print();
    document.body.innerHTML = originalContents;
    location.reload(); // reload halaman setelah cetak agar kembali normal
  }
</script>

</script>
<script src="plugins/jquery/jquery.min.js"></script>
<script src="plugins/jquery-ui/jquery-ui.min.js"></script>
<script>
  $.widget.bridge('uibutton', $.ui.button)
</script>
<script src="plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<script src="plugins/chart.js/Chart.min.js"></script>
<script src="plugins/sparklines/sparkline.js"></script>
<script src="plugins/jqvmap/jquery.vmap.min.js"></script>
<script src="plugins/jqvmap/maps/jquery.vmap.usa.js"></script>
<script src="plugins/jquery-knob/jquery.knob.min.js"></script>
<script src="plugins/moment/moment.min.js"></script>
<script src="plugins/daterangepicker/daterangepicker.js"></script>
<script src="plugins/tempusdominus-bootstrap-4/js/tempusdominus-bootstrap-4.min.js"></script>
<script src="plugins/summernote/summernote-bs4.min.js"></script>
<script src="plugins/overlayScrollbars/js/jquery.overlayScrollbars.min.js"></script>
<script src="dist/js/adminlte.js"></script>
<script src="dist/js/demo.js"></script>
<script src="dist/js/pages/dashboard.js"></script>
