<?php
session_start();
if (!isset($_SESSION['username'])) {
    header("Location: ../index.php");
    exit();
}
?>
<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
<link rel="stylesheet" href="../../plugins/fontawesome-free/css/all.min.css">
<link rel="stylesheet" href="../../dist/css/adminlte.min.css">

<?php include 'header.php'; ?>
<?php include 'sidebar.php'; ?>

<div class="content-wrapper">
    <!-- Content Header -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Dashboard</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="#">Home</a></li>
                        <li class="breadcrumb-item active">Dashboard</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <?php
    include 'config.php';

    $jumlahIphoneQuery = mysqli_query($conn, "SELECT COUNT(*) FROM produk WHERE kategori = 'Iphone'");
    $jumlahAndroidQuery = mysqli_query($conn, "SELECT COUNT(*) FROM produk WHERE kategori = 'Android'");

    $jumlahIphone = mysqli_fetch_array($jumlahIphoneQuery)[0];
    $jumlahAndroid = mysqli_fetch_array($jumlahAndroidQuery)[0];
    $totalProduk = $jumlahIphone + $jumlahAndroid;


    $produkLabels = [];
    $produkHarga = [];

    $query = mysqli_query($conn, "SELECT nama_produk, harga FROM produk ORDER BY id_produk ASC");

    while ($row = mysqli_fetch_assoc($query)) {
        $produkLabels[] = $row['nama_produk'];
        $produkHarga[] = $row['harga'];
    }
    ?>

    <!-- Main content -->
    <section class="content">
        <div class="container-fluid">
            <!-- Info Boxes -->
            <div class="row">
                <!-- Semua Produk -->
                <div class="col-lg-3 col-6">
                    <div class="small-box bg-info">
                        <div class="inner">
                            <h3><?php echo $totalProduk; ?></h3>
                            <p>Semua Produk</p>
                        </div>
                        <div class="icon">
                            <i class="ion ion-bag"></i>
                        </div>
                        <a href="produk.php" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
                    </div>
                </div>

                <!-- Bounce Rate -->
                <div class="col-lg-3 col-6">
                    <div class="small-box bg-success">
                        <div class="inner">
                            <h3>53<sup style="font-size: 20px">%</sup></h3>
                            <p>Bounce Rate</p>
                        </div>
                        <div class="icon">
                            <i class="ion ion-stats-bars"></i>
                        </div>
                        <a href="#" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
                    </div>
                </div>

                <!-- User Registrations -->
                <div class="col-lg-3 col-6">
                    <div class="small-box bg-warning">
                        <div class="inner">
                            <h3>44</h3>
                            <p>User Registrations</p>
                        </div>
                        <div class="icon">
                            <i class="ion ion-person-add"></i>
                        </div>
                        <a href="#" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
                    </div>
                </div>

                <!-- Unique Visitors -->
                <div class="col-lg-3 col-6">
                    <div class="small-box bg-danger">
                        <div class="inner">
                            <h3>65</h3>
                            <p>Unique Visitors</p>
                        </div>
                        <div class="icon">
                            <i class="ion ion-pie-graph"></i>
                        </div>
                        <a href="#" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
                    </div>
                </div>
            </div>

            <!-- Chart Section -->
            <div class="row">
                <!-- Line Chart -->
                <div class="col-md-6">
                    <div class="card card-success">
                        <div class="card-header">
                            <h3 class="card-title">Tren Lonjakan Harga Produk</h3>
                        </div>
                        <div class="card-body">
                            <canvas id="lineChart" style="min-height: 250px; height: 250px;"></canvas>
                        </div>
                    </div>
                </div>


                <!-- Donut Chart -->
                <div class="col-md-6">
                    <div class="card card-danger">
                        <div class="card-header">
                            <h3 class="card-title">Perbandingan Jumlah Produk</h3>
                        </div>
                        <div class="card-body">
                            <canvas id="donutChart" style="min-height: 250px; height: 250px; max-height: 250px; max-width: 100%;"></canvas>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

<aside class="control-sidebar control-sidebar-dark"></aside>

<?php include 'footer.php'; ?>

<!-- Scripts -->
<script src="plugins/jquery/jquery.min.js"></script>
<script src="plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<script src="plugins/chart.js/Chart.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>


<script>
    // Donut Chart Script
    var donutChartCanvas = $('#donutChart').get(0).getContext('2d');
    var donutData = {
        labels: ['Iphone', 'Android'],
        datasets: [{
            data: [<?= $jumlahIphone; ?>, <?= $jumlahAndroid; ?>],
            backgroundColor: ['#f56954', '#00a65a'],
        }]
    };
    var donutOptions = {
        maintainAspectRatio: false,
        responsive: true,
    };
    new Chart(donutChartCanvas, {
        type: 'doughnut',
        data: donutData,
        options: donutOptions
    });

    // Line Chart Script
    const labels = <?= json_encode($produkLabels); ?>;
    const dataHarga = <?= json_encode($produkHarga); ?>;

    const ctx = document.getElementById('lineChart').getContext('2d');
    const lineChart = new Chart(ctx, {
        type: 'line',
        data: {
            labels: labels,
            datasets: [{
                label: 'Harga Produk (Rp)',
                data: dataHarga,
                borderColor: 'rgba(54, 162, 235, 1)',
                backgroundColor: 'rgba(54, 162, 235, 0.2)',
                fill: true,
                tension: 0.3,
                pointBackgroundColor: 'rgba(54, 162, 235, 1)',
                pointRadius: 4
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: true
                },
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            let value = context.parsed.y;
                            return 'Rp ' + new Intl.NumberFormat('id-ID').format(value);
                        }
                    }
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        callback: function(value) {
                            return 'Rp ' + value.toLocaleString('id-ID');
                        }
                    }
                }
            }
        }
    });
</script>
</body>

</html>
