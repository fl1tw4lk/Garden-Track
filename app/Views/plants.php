<?php echo $this->extend('_partials/template')?>
<?php echo $this->section('isi')?>

<div class="container mt-5">
        <h1 class="text-center mb-3">Data Tanaman</h1>
        <div class="container my-4">
    <form action="<?= base_url('plants/search') ?>" method="get" class="d-flex">
        <input type="text" name="search" class="form-control me-2" placeholder="Cari tanaman..." aria-label="Search">
        <button class="btn btn-outline-success" type="submit">Cari</button>
    </form>
</div>
<br><br>
    <!-- Plants Data -->
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
                            </p>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
        <?php else: ?>
            <p class="text-danger">Tidak ada data tanaman yang sesuai dengan pencarian.</p>
        <?php endif; ?>
    </div>
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

<?php echo $this->endSection()?>