<?php
require 'config.php';

$harga = [
    'silver'  => 10000,
    'gold'    => 30000,
    'diamond' => 60000
];

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nama   = trim($_POST['nama'] ?? '');
    $kelas  = $_POST['tiket'] ?? '';
    $jumlah = (int) ($_POST['jumlah'] ?? 0);

    if ($nama === '') {
        $error = 'Nama pemesan wajib diisi.';
    } elseif (!isset($harga[$kelas])) {
        $error = 'Silakan pilih tiket.';
    } elseif ($jumlah < 1 || $jumlah > 10) {
        $error = 'Jumlah tiket harus 1 sampai 10.';
    } else {
        $hargaSatuan = $harga[$kelas];
        $total       = $hargaSatuan * $jumlah;

        try {
            $stmt = $koneksi->prepare(
                "INSERT INTO tb_pemesanan (nama_pemesan, kelas_tiket, jumlah_tiket, harga_satuan, total_harga)
                 VALUES (:nama, :kelas, :jumlah, :harga_satuan, :total)"
            );

            $stmt->execute([
                ':nama'         => $nama,
                ':kelas'        => $kelas,
                ':jumlah'       => $jumlah,
                ':harga_satuan' => $hargaSatuan,
                ':total'        => $total,
            ]);
        } catch (PDOException $e) {
            $error = 'Gagal menyimpan pesanan. Pastikan file database bisa ditulis '
                   . '(folder database/ harus writable).';
        }

        if ($error === '') {
            header('Location: index.php');
            exit;
        }
    }
}

$data = $koneksi->query("SELECT * FROM tb_pemesanan ORDER BY id DESC")->fetchAll();

$hasil = $koneksi
    ->query("SELECT COUNT(*) AS jml, SUM(total_harga) AS omzet FROM tb_pemesanan")
    ->fetch();
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Pemesanan Tiket Acara</title>
    <link href="bootstrap.min.css" rel="stylesheet">
    <link href="style.css" rel="stylesheet">
</head>
<body>

<header>
    <nav class="navbar navbar-expand navbar-dark bento-navbar">
        <div class="container">
            <span class="navbar-brand fw-bold">Pemesanan Tiket Acara</span>
            <div class="d-flex align-items-center gap-3">
                <span class="navbar-text">David Dwi Wahyudi</span>
                <span class="badge text-bg-light">252410103005</span>
            </div>
        </div>
    </nav>
</header>

<main class="container py-4">

    <div class="card bento bento-header mb-3">
        <div class="card-body p-4">
            <h3 class="fw-bold mb-1">Pemesanan Tiket Acara</h3>
            <p class="mb-0 opacity-75">Pilih kelas tiket, isi data pemesan, dan konfirmasi pesanan Anda.</p>
        </div>
    </div>

    <div class="row g-3">

        <div class="col-lg-7">
            <div class="card bento h-100">
                <div class="card-body p-4">
                    <h5 class="fw-bold mb-4">Form Pemesanan</h5>

                    <?php if ($error !== '') { ?>
                        <div class="alert alert-danger py-2"><?= $error ?></div>
                    <?php } ?>

                    <form id="formTiket" method="post" action="index.php">

                        <div class="mb-3">
                            <label for="nama" class="form-label">Nama Pemesan</label>
                            <input type="text" class="form-control" id="nama" name="nama"
                                   value="<?= htmlspecialchars($_POST['nama'] ?? '') ?>"
                                   placeholder="Masukkan nama">
                        </div>

                        <div class="mb-3">
                            <label for="tiket" class="form-label">Pilih Tiket</label>
                            <select class="form-select" id="tiket" name="tiket">
                                <option value="">-- Pilih --</option>
                                <option value="silver" <?= ($_POST['tiket'] ?? '') === 'silver' ? 'selected' : '' ?>>Silver - Rp 10.000</option>
                                <option value="gold" <?= ($_POST['tiket'] ?? '') === 'gold' ? 'selected' : '' ?>>Gold - Rp 30.000</option>
                                <option value="diamond" <?= ($_POST['tiket'] ?? '') === 'diamond' ? 'selected' : '' ?>>Diamond - Rp 60.000</option>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label for="jumlah" class="form-label">Jumlah Tiket</label>
                            <input type="number" class="form-control" id="jumlah" name="jumlah"
                                   value="<?= htmlspecialchars($_POST['jumlah'] ?? '1') ?>" min="1" max="10">
                        </div>

                        <div class="mb-4">
                            <label class="form-label">Total Harga</label>
                            <div class="total" id="total">Rp 0</div>
                        </div>

                        <button type="submit" class="btn btn-primary w-100 py-2">Pesan Tiket</button>

                    </form>
                </div>
            </div>
        </div>

        <div class="col-lg-5 d-flex flex-column">

            <div class="row g-3 mb-3">

                <div class="col-6">
                    <div class="card bento h-100">
                        <div class="card-body p-3 text-center">
                            <div class="stat-label">Total Pesanan</div>
                            <div class="stat-value"><?= $hasil['jml'] ?></div>
                        </div>
                    </div>
                </div>

                <div class="col-6">
                    <div class="card bento h-100">
                        <div class="card-body p-3 text-center">
                            <div class="stat-label">Total Omzet</div>
                            <div class="stat-value"><?= number_format($hasil['omzet'] ?? 0, 0, ',', '.') ?></div>
                        </div>
                    </div>
                </div>

            </div>

            <div class="card bento flex-grow-1">
                <div class="card-body p-3 d-flex flex-column">
                    <div class="judul-bagian mb-3">Pilihan Tiket</div>
                    <div class="d-flex flex-column gap-2 flex-grow-1">
                        <?php foreach ($harga as $kelas => $hargaSatuan) { ?>
                            <div class="ticket-box ticket-<?= $kelas ?> d-flex align-items-center justify-content-between flex-grow-1">
                                <span class="ticket-nama"><?= ucfirst($kelas) ?></span>
                                <span class="ticket-harga">Rp <?= number_format($hargaSatuan, 0, ',', '.') ?></span>
                            </div>
                        <?php } ?>
                    </div>
                </div>
            </div>

        </div>

        <div class="col-12">
            <div class="card bento">
                <div class="card-body p-4">
                    <h5 class="fw-bold mb-3">Riwayat Pemesanan</h5>

                    <table class="table table-sm mb-0 align-middle">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Nama</th>
                                <th>Tiket</th>
                                <th>Jumlah</th>
                                <th class="text-end">Total</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (count($data) > 0) { ?>
                                <?php foreach ($data as $row) { ?>
                                    <tr>
                                        <td><?= $row['id'] ?></td>
                                        <td><?= htmlspecialchars($row['nama_pemesan']) ?></td>
                                        <td><?= ucfirst($row['kelas_tiket']) ?></td>
                                        <td><?= $row['jumlah_tiket'] ?></td>
                                        <td class="text-end">Rp <?= number_format($row['total_harga'], 0, ',', '.') ?></td>
                                    </tr>
                                <?php } ?>
                            <?php } else { ?>
                                <tr>
                                    <td colspan="5" class="text-center text-muted py-3">Belum ada data</td>
                                </tr>
                            <?php } ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

    </div>
</main>

<footer class="container pb-4">
    <p class="text-center text-secondary small mb-0">
        Tugas Ujian Tengah Semester &middot; Pemrograman Web &middot; Universitas Nijiputera
    </p>
</footer>

<script>
    const HARGA = <?= json_encode($harga) ?>;
</script>
<script src="jquery.min.js"></script>
<script src="script.js"></script>
</body>
</html>
