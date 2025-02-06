<?php echo $this->extend('_partials/template')?>
<?php echo $this->section('isi')?>

<body>
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
  .card {
    background-color: #fff;
    border-radius: 12px;
    box-shadow: 0 8px 20px rgba(0, 0, 0, 0.1);
    overflow: hidden;
    width: 300px; /* Ukuran konsisten */
    text-align: center;
    transition: transform 0.3s ease, box-shadow 0.3s ease;
    border: 0.2px solid rgb(211, 177, 177); /* Garis 1px di seluruh card */
    text-decoration: none; /* Menghapus garis bawah pada link */
  }
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
  /* Animasi tombol tambah kebun */
  .btn-hover-animate {
    transition: all 0.3s ease-in-out;
  }
  .btn-hover-animate:hover {
    transform: scale(1.1); /* Memperbesar tombol saat hover */
    background-color: #28a745; /* Warna hijau lebih terang saat hover */
    box-shadow: 0 8px 20px rgba(0, 123, 45, 0.3); /* Bayangan lebih besar */
    color: white; /* Warna teks putih */
    text-decoration: none; /* Menghapus garis bawah pada link */
  }
</style>
<!-- Hero Section -->
<section class="hero-section" style="background-size: cover; background-position: center; padding-top: 40px;">
    <div class="container d-flex align-items-center justify-content-center">
        <div class="text-center">
            <h1 class="catalog-text">Kebun <?= $kebun[0]['nama_users']?></h1>
        </div>
    </div>
</section>
<?php if (session()->get('id_user') == $kebun[0]['id_user']): ?>
  <!-- Tombol Tambah Kebun -->
  <div class="container mt-4 text-center">
  <a href="/buat_kebun" class="btn btn-success btn-lg rounded-pill shadow btn-hover-animate">
      <i class="bi bi-plus-circle"></i> Tambah Kebun
  </a>
  </div>
<?php endif; ?>
<!-- Catalog Section -->
<section class="catalog-section my-5">
  <div class="container">
    <div class="card-container">
      <?php if (isset($kebun) && !empty($kebun)): ?>
        <?php foreach ($kebun as $item): ?>
          <!-- <php dd($kebun); die; ?>   -->
          <a href="/kebun/detail/<?= $item['id_kebun']; ?>" class="card">
          <img src="/uploads/kebun/<?= $item['poto_kebun'] ?>" class="card-img-top" alt="<?= $item['nama_kebun']; ?>">
            <div class="card-body">
              <h5 class="card-title">Nama Kebun : <?= htmlspecialchars($item['nama_kebun']); ?></h5>
            </div>
          </a>
        <?php endforeach; ?>
      <?php else: ?>
        <p class="text-center">Data Kebun Tidak Tersedia</p>
      <?php endif; ?>
    </div>
  </div>
</section>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
<script>
// Cek apakah ada session flashdata 
const successMessage = "<?= session()->getFlashdata('success') ?>";
const errorMessage = "<?= session()->getFlashdata('error') ?>";
if (successMessage) {
    const Toast = Swal.mixin({
        toast: true,
        position: "top",  // Pastikan posisinya top
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
        title: successMessage // Tampilkan pesan dari session flashdata
    });
}
if (errorMessage) {
    const Toast = Swal.mixin({
        toast: true,
        position: "top",  // Pastikan posisinya top
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
        title: errorMessage // Tampilkan pesan error
    });
}
</script>
<?php echo $this->endSection()?>