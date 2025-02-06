<?php echo $this->extend('_partials/template')?>
<?php echo $this->section('isi')?>
<link href="<?php echo base_url('tanaman/'); ?>css/kebun.css" rel="stylesheet">
<style>
  body {
    background-color: #ffffff;
  }
  .card-container {
    display: flex;
    flex-wrap: wrap;
    gap: 20px;
    justify-content: center;
    padding: 20px;
  }
  /* .card {
    background-color: #fff;
    border-radius: 12px;
    box-shadow: 0 8px 20px rgba(0, 0, 0, 0.1);
    overflow: hidden;
    width: 300px; 
    text-align: center;
    transition: transform 0.3s ease, box-shadow 0.3s ease;
    border: 0.2px solid rgb(211, 177, 177); 
    text-decoration: none; 
  } */
  .card:hover {
    transform: scale(1.05); /* Sedikit memperbesar kartu */
    box-shadow: 0 10px 20px rgba(100, 89, 89, 0.2); /* Memberikan bayangan */
  }
  .card:hover .card-title {
    color: #00b383; /* Warna hijau untuk judul */
    transition: color 0.3s ease;
  }
  .card img {
    width: 100%;
    height: 200px; /* Tinggi gambar tetap */
    object-fit: cover; /* Menjaga proporsi gambar */
  }
  .card h5 {
    margin: 15px 0;
    font-size: 20px;
    color: #333;
  }
  .card p {
    font-size: 14px;
    color: #555;
    margin-bottom: 15px;
  }
  .card-container .card-body {
    padding: 10px;
  }
  .catalog-text {
    font-family: 'Poppins', sans-serif;
    font-weight: 700;
    font-size: 2rem;
    color: #000;
    text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.5);
    animation: slideInFromBottom 1s ease-out;
  }
  @keyframes slideInFromBottom {
    0% {
      transform: translateY(100%);
      opacity: 0;
    }
    100% {
      transform: translateY(0);
      opacity: 1;
    }
  }

  .btn-hover-animate {
    transition: all 0.3s ease-in-out;
  }
  .btn-hover-animate:hover {
    transform: scale(1.1); 
    background-color: #28a745; 
    box-shadow: 0 8px 20px rgba(0, 123, 45, 0.3); 
    color: white; 
    text-decoration: none; 
  }
  .crop-chip {
    background-color: #76736c;
  }
  .member-chip {
    background-color: #a4c98c;
  }
  .member-chip a {
    color: #000000;
  }
  .chip {
      background-color: #a4c98c;
      border-radius: 25px;
      color: #fff;
      display: inline-block;
      height: 30px;
      line-height: 30px;
      margin: 0.2em;
      padding: 0 25px;
  }
  .chip img {
    border-radius: 50%;
    float: left;
    height: 30px;
    margin: 0 10px 0 -25px;
    width: 30px;
  }
  img {
    border-style: none;
    vertical-align: middle;
  
  }

  .img-cover, .card .img-card {
    height: 200px;
    -o-object-fit: cover;
    object-fit: cover;
    width: 100%;
  }
  .card {
    border-radius: 2px;
    background-color: #fff;
    box-shadow: 0 0 4px 0 rgba(0, 0, 0, 0.14), 0 3px 4px 0 rgba(0, 0, 0, 0.12), 0 1px 5px 0 rgba(0, 0, 0, 0.2);
    display: flex;
    flex-direction: column;
    min-width: 0;
    position: relative;
    word-wrap: break-word;
  }
  .card-header:last-child {
    border-bottom-right-radius: 2px;
    border-bottom-left-radius: 2px;
    border-bottom: 0;
  }
  .card-header:first-child {
      border-top-left-radius: 2px;
      border-top-right-radius: 2px;
  }
  .card-header {
      border-bottom: 1px solid rgba(0, 0, 0, 0.12);
      margin-bottom: 0;
      padding: 1rem 1rem;
  }
  .float-right {
    float: right !important;
  }

  /* .tanaman-item {
      margin-bottom: 15px;
  }

  .tanaman-progress-wrapper {
      margin-top: 5px;  
      padding-top: 5px; 
      border-top: 1px solid #ddd; 
  }

  .tanaman-item .progress {
      margin-top: 5px;
  }

  .tanaman-item .progress-bar {
      height: 20px;
      text-align: center;
      line-height: 20px;
  }

  .tanaman-item .float-right {
      font-size: 0.9rem;
      color: #6c757d;
  } */
</style>
<!-- Breadcrumb -->
<nav aria-label="breadcrumb" class="px-4 px-lg-5" style="--bs-breadcrumb-divider: url(&#34;data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='8' height='8'%3E%3Cpath d='M2.5 0L1 1.5 3.5 4 1 6.5 2.5 8l4-4-4-4z' fill='%236c757d'/%3E%3C/svg%3E&#34;);">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="/">Home</a></li>
        <li class="breadcrumb-item">Semua Kebun</li>
    </ol>
</nav>
<!-- Hero Section -->
<section class="hero-section" style="background-size: cover; background-position: center; padding-top: 40px;">
    <div class="container d-flex align-items-center justify-content-center">
        <div class="text-center">
            <h1 class="catalog-text">Semua Kebun </h1>
        </div>
    </div>
</section>
<!-- Catalog Section -->
<section class="catalog-section my-5">
  <div class="container">
    <div class="card-container">
      <?php if (isset($kebun) && !empty($kebun)): ?>
          <?php foreach ($kebun as $item):?>
            <?php
                // Foto Profile
                $profilePath = $item['profile'] ?? 'default.png';
                if (filter_var($profilePath, FILTER_VALIDATE_URL)) {
                    $profile = $profilePath;
                } else {
                    $profile = base_url('uploads/profile/' . $profilePath);
                }
            ?>
          <div class="col-md-10">
            <div class="card">
              <div class="card-header">
                <div class="row">
                  <div class="col-12 col-md-3">
                    <h2><a name="#" href="/kebun/detail/<?= $item['id_kebun']; ?>"><?= esc($item['nama_kebun']); ?></a></h2>
                  </div>
                </div>
                <div class="row">
                  <div class="col-md-4">
                    owner: 
                    <span class="chip member-chip">
                      <a href="/kebun/kebun-<?= esc($item['id_user']); ?>">
                        <img alt="<?= esc($item['nama_users']); ?>" height="50" width="50" src="<?= esc($profile); ?>">
                        <?= esc($item['nama_users']); ?>
                      </a>
                    </span>
                    <img alt="<?= esc($item['nama_kebun']); ?>" class="img-card" src="/uploads/kebun/<?= $item['poto_kebun']; ?>">
                  </div>
                  <div class="col-md-8">
                    <section>
                      <strong>Tanaman:</strong>
                      <?php if (!empty($item['tanaman'])): ?>
                        <ul>
                          <?php foreach ($item['tanaman'] as $tanaman): ?>
                              <li class="tanaman-item">
                                  <div class="row">
                                      <div class="col-10 col-md-3 progress-row--crop">
                                          <?= esc($tanaman['nama']); ?>
                                      </div>
                                      <div class="col-15 col-md-8 progress-row--crop">
                                          <!-- Progress Bar -->
                                          <div class="progress" role="progressbar" aria-valuenow="<?= $tanaman['progress']; ?>" aria-valuemin="0" aria-valuemax="100">
                                              <div class="progress-bar bg-success" style="width: <?= $tanaman['progress']; ?>%">
                                                  <?= $tanaman['progress']; ?>%
                                              </div>
                                          </div>
                                          <div class="float-right mt-2"><?= esc($tanaman['tanggal_selesai']) ?></div>
                                      </div>
                                  </div>
                                  <hr>
                              </li>
                          <?php endforeach; ?>
                      </ul>
                      <?php else: ?>
                          <p>Belum ada tanaman</p>
                      <?php endif; ?>
                    </section>
                    <!-- <section>
                    <hr>
                      <p>No annual plantings</p>
                    </section> -->
                  </div>
                </div>
              </div>
            </div>
          </div>
        <?php endforeach; ?>
      <?php else: ?>
          <p class="text-center">Data Kebun Tidak Tersedia</p>
      <?php endif; ?>
    </div>
  </div>
</section>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
<script>
const successMessage = "<?= session()->getFlashdata('success') ?>";
const errorMessage = "<?= session()->getFlashdata('error') ?>";
if (successMessage) {
    const Toast = Swal.mixin({
        toast: true,
        position: "top", 
        showConfirmButton: false,
        timer: 3000,
        timerProgressBar: true,
        didOpen: (toast) => {
            toast.onmouseenter = Swal.stopTimer;
            toast.onmouseleave = Swal.resumeTimer;
        },
        customClass: {
            popup: 'toast-popup'
        }
    });
    Toast.fire({
        icon: "success",
        title: successMessage 
    });
}
if (errorMessage) {
    const Toast = Swal.mixin({
        toast: true,
        position: "top",  
        showConfirmButton: false,
        timer: 3000,
        timerProgressBar: true,
        didOpen: (toast) => {
            toast.onmouseenter = Swal.stopTimer;
            toast.onmouseleave = Swal.resumeTimer;
        },
        customClass: {
            popup: 'toast-popup'
        }
    });
    Toast.fire({
        icon: "error",
        title: errorMessage 
    });
}
</script>
<?php echo $this->endSection()?>