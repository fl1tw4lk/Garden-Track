<?php echo $this->extend('_partials/template')?>
<?php echo $this->section('isi')?>

<!-- <p class="fs-5 fw-bold text-primary">Daftar Sayuran</p> -->
<div class="container-fluid copyright py-4">
    <div class="container">
        <div class="row">
            <div class="col-md-6 text-center text-md-start mb-3 mb-md-0">
                <span class="fs-5 fw-semi-bold text-light">All Plants</span>
            </div>
        </div>
    </div>
</div>
<div class="container-xxl py-5">
    <div class="row g-4">
        <?php if (!empty($plants)): ?>
        <div class="row">
            <?php foreach ($plants as $plant): ?>
                <div class="col-md-4">
                    <div class="card mb-4">
                        <img src="<?= $plant['image_url'] ?>" class="card-img-top" alt="<?= $plant['common_name'] ?>" style="height: 200px; object-fit: cover;">
                        <div class="card-body">
                            <h5 class="card-title"><?= $plant['common_name'] ?? 'Nama Tidak Tersedia' ?></h5>
                            <p class="card-text">
                                <strong>Nama Ilmiah:</strong> <?= $plant['scientific_name'] ?><br>
                                <strong>Kategori:</strong> <?= $plant['family'] ?? 'Tidak Tersedia' ?><br>
                                <!-- <strong>Author:</strong> <?= $plant['author'] ?? 'Tidak Tersedia' ?><br>
                                <strong>Deskripsi:</strong> <?= $plant['description'] ?? 'Deskripsi tidak tersedia.' ?> -->
                            </p>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>

        <!-- Pagination Buttons -->
        <div class="d-flex justify-content-between">
            <?php if ($currentPage > 1): ?>
                <a href="?page=<?= $currentPage - 1 ?>" class="btn btn-primary">Previous</a>
            <?php else: ?>
                <button class="btn btn-primary" disabled>Previous</button>
            <?php endif; ?>

            <?php if ($currentPage < $totalPages): ?>
                <a href="?page=<?= $currentPage + 1 ?>" class="btn btn-primary">Next</a>
            <?php else: ?>
                <button class="btn btn-primary" disabled>Next</button>
            <?php endif; ?>
        </div>
        <?php else: ?>
            <p class="text-danger">Tidak ada data sayuran yang tersedia.</p>
        <?php endif; ?>
    </div>
</div>
<?php echo $this->endSection()?>