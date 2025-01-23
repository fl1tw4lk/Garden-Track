<?php echo $this->extend('_partials/template')?>
<?php echo $this->section('isi')?>

<style>
html {
    scroll-behavior: smooth;
}
.carousel-bg {
    height: 92vh; /* Menyesuaikan tinggi layar perangkat */
    object-fit: cover; /* Memastikan gambar tidak terdistorsi */
}

.service-section {
    background-color: #e8f5e9; /* Warna hijau muda */
    width: 100%;
    padding: 150px 0;
    margin: 0;
}

.service-item {
    margin-bottom: 30px;
    background-color: #ffffff;
    box-shadow: 0 0 15px rgba(0, 0, 0, 0.1);
    border-radius: 10px;
    overflow: hidden;
}

.service-item:hover {
    transform: scale(1.05);
    transition: transform 0.3s ease;
}
.text-justify {
    text-align: justify;
    display: block;
}
@media (max-width: 1200px) {
    .carousel-bg {
        height: 80vh; /* Untuk layar lebih kecil dari 1200px */
    }
}
@media (max-width: 992px) {
    .service-item {
        margin-bottom: 20px; /* Penyesuaian jarak pada layar lebih kecil */
    }
}


</style>
<script>
    // Sesuaikan tinggi navbar di bawah ini
    const navbarHeight = 100; // Misal tinggi navbar Anda adalah 70px

    // Ambil semua tautan yang memiliki kelas "scroll-link"
    document.querySelectorAll('.scroll-link').forEach(link => {
        link.addEventListener('click', function(e) {
        e.preventDefault(); // Mencegah aksi default

        // Ambil target dari atribut href
        const targetId = this.getAttribute('href').substring(1);
        const targetElement = document.getElementById(targetId);

        if (targetElement) {
            // Hitung posisi scroll dengan mengurangi tinggi navbar
            const offsetTop = targetElement.offsetTop - navbarHeight;

            // Scroll ke posisi tersebut
            window.scrollTo({
                top: offsetTop,
                behavior: 'smooth'
            });
        }
    });
});
</script>
    <!-- Carousel Start -->
    <div class="container-fluid p-0 wow fadeIn" data-wow-delay="0.1s">
        <div id="header-carousel" class="carousel slide" data-bs-ride="carousel">
            <div class="carousel-inner">
                <div class="carousel-item active">
                    <img class="w-100 carousel-bg" src="<?php echo base_url('tanaman/'); ?>img/carousel-1.jpg" alt="Image">
                    <div class="carousel-caption">
                        <div class="container">
                            <div class="row justify-content-center">
                                <div class="col-lg-8">
                                    <h1 class="display-1 text-white mb-4 animated slideInDown">Tamanmu Dimulai dari Rumah</h1>
                                    <form action="/search" method="get" class="d-flex animated fadeInUp" style="max-width: 600px; margin: auto;">
                                        <input type="text" name="query" class="form-control form-control-lg" placeholder="Cari tanaman..." style="border-radius: 30px 0 0 30px;">
                                        <button type="submit" class="btn btn-primary btn-lg" style="border-radius: 0 30px 30px 0;">Cari</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="carousel-item">
                    <img class="w-100 carousel-bg" src="<?php echo base_url('tanaman/'); ?>img/carousel-2.jpg" alt="Image">
                    <div class="carousel-caption">
                        <div class="container">
                            <div class="row justify-content-center">
                                <div class="col-lg-7">
                                    <h1 class="display-1 text-white mb-4 animated slideInDown">Taman Kecil, Kebahagiaan Besar</h1>
                                    <form action="/search" method="get" class="d-flex animated fadeInUp" style="max-width: 600px; margin: auto;">
                                        <input type="text" name="query" class="form-control form-control-lg" placeholder="Cari tanaman..." style="border-radius: 30px 0 0 30px;">
                                        <button type="submit" class="btn btn-primary btn-lg" style="border-radius: 0 30px 30px 0;">Cari</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <button class="carousel-control-prev" type="button" data-bs-target="#header-carousel" data-bs-slide="prev">
                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                <span class="visually-hidden">Previous</span>
            </button>
            <button class="carousel-control-next" type="button" data-bs-target="#header-carousel" data-bs-slide="next">
                <span class="carousel-control-next-icon" aria-hidden="true"></span>
                <span class="visually-hidden">Next</span>
            </button>
        </div>
    <!-- Carousel End -->


    <!-- Top Feature Start -->
    <!-- <div class="container-fluid top-feature py-5 pt-lg-0">
        <div class="container py-5 pt-lg-0">
            <div class="row gx-0">
                <div class="col-lg-4 wow fadeIn" data-wow-delay="0.1s">
                    <div class="bg-white shadow d-flex align-items-center h-100 px-5" style="min-height: 160px;">
                        <div class="d-flex">
                            <div class="flex-shrink-0 btn-lg-square rounded-circle bg-light">
                                <i class="fa fa-times text-primary"></i>
                            </div>
                            <div class="ps-3">
                                <h4>No Hidden Cost</h4>
                                <span>Clita erat ipsum lorem sit sed stet duo justo</span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4 wow fadeIn" data-wow-delay="0.3s">
                    <div class="bg-white shadow d-flex align-items-center h-100 px-5" style="min-height: 160px;">
                        <div class="d-flex">
                            <div class="flex-shrink-0 btn-lg-square rounded-circle bg-light">
                                <i class="fa fa-users text-primary"></i>
                            </div>
                            <div class="ps-3">
                                <h4>Dedicated Team</h4>
                                <span>Clita erat ipsum lorem sit sed stet duo justo</span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4 wow fadeIn" data-wow-delay="0.5s">
                    <div class="bg-white shadow d-flex align-items-center h-100 px-5" style="min-height: 160px;">
                        <div class="d-flex">
                            <div class="flex-shrink-0 btn-lg-square rounded-circle bg-light">
                                <i class="fa fa-phone text-primary"></i>
                            </div>
                            <div class="ps-3">
                                <h4>24/7 Available</h4>
                                <span>Clita erat ipsum lorem sit sed stet duo justo</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div> -->
    <!-- Top Feature End -->

<!-- About Start -->
<div class="container-xxl py-5">
    <div class="container">
        <div class="row g-5 align-items-end">
            <div class="col-lg-3 col-md-5 wow fadeInUp" data-wow-delay="0.1s">
                <img class="img-fluid rounded" data-wow-delay="0.1s" src="<?php echo base_url('tanaman/'); ?>img/about.jpg">
            </div>
            <div class="col-lg-6 col-md-7 wow fadeInUp" data-wow-delay="0.3s">
                <h1 class="display-1 text-primary mb-0">Garden Track</h1>
                <p class="text-primary mb-4"></p>
                <h1 class="display-5 mb-4">Hidup Hijau, Dimulai dari Rumah.</h1>
                <p class="mb-4">Buat dan kelola kebun digital Anda sendiri! Tambahkan tanaman, catat perkembangan mereka, dan atur jadwal perawatan semua dalam satu aplikasi yang mudah digunakan. Bawa kebun Anda ke level berikutnya, tanpa kesulitan!</p>
                <a class="btn btn-primary py-3 px-4" href="/services">Jelajahi</a>
                <section id="services">
            </div>
            <div class="col-lg-3 col-md-12 wow fadeInUp" data-wow-delay="0.5s">
                <div class="row g-5">
                    <div class="col-12 col-sm-6 col-lg-12">
                        <div class="border-start ps-4">
                            <i class="fa fa-award fa-3x text-primary mb-3"></i>
                            <h4 class="mb-3">Kualitas Terjamin</h4>
                            <span style="text-align: justify; display: block;">Kami memberikan layanan berkualitas yang dirancang untuk memenuhi kebutuhan kebun Anda, memastikan pertumbuhan tanaman yang sehat dan optimal</span>
                        </div>
                    </div>
                    <div class="col-12 col-sm-6 col-lg-12">
                        <div class="border-start ps-4">
                            <i class="fa fa-users fa-3x text-primary mb-3"></i>
                            <h4 class="mb-3">Dukungan Penuh</h4>
                            <span style="text-align: justify; display: block;">Tim kami selalu siap membantu Anda dengan panduan yang mudah dan praktis untuk mengelola kebun Anda secara efektif</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Service Start-->

<div class="container-fluid py-5 service-section">
    <div class="container">
        <div class="text-center mx-auto wow fadeInUp" data-wow-delay="0.1s" style="max-width: 500px;">
            <h1 class="display-7 mb-7">Layanan Garden Track</h1>
            <p class="fs-5 fw-bold text-primary">Cari Tanaman Dan Buat Kebun Disini</p><br>
        </div>
        <div class="row g-4">
            <div class="col-lg-4 col-md-6 wow fadeInUp" data-wow-delay="0.1s">
                <div class="service-item d-flex h-100">
                    <div class="service-img rounded">
                        <img class="img-fluid" src="<?php echo base_url('tanaman/'); ?>img/service-1.jpg" alt="">
                    </div>
                    <div class="service-text rounded p-5">
                        <div class="btn-square rounded-circle mx-auto mb-3">
                            <img class="img-fluid" src="<?php echo base_url('tanaman/'); ?>img/icon/icon-2.png" alt="Icon">
                        </div>
                        <h4 class="mb-3">Cari Tanaman</h4>
                        <p class="mb-4">Lihat Berbagai Jenis Tanaman Disini, Dan cari Informasinya</p>
                        <a class="btn btn-sm" href="plants.php"><i class="fa fa-plus text-primary me-2"></i>Cari Tanaman</a>
                    </div>
                </div>
            </div>
                <div class="col-lg-4 col-md-6 wow fadeInUp" data-wow-delay="0.3s">
                    <div class="service-item rounded d-flex h-100">
                        <div class="service-img rounded">
                            <img class="img-fluid" src="<?php echo base_url('tanaman/'); ?>img/service-2.jpg" alt="">
                        </div>
                        <div class="service-text rounded p-5">
                            <div class="btn-square rounded-circle mx-auto mb-3">
                                <img class="img-fluid" src="<?php echo base_url('tanaman/'); ?>img/icon/icon-4.png" alt="Icon">
                            </div>
                            <h4 class="mb-3">Buat Kebun Anda</h4>
                            <p class="mb-4">Erat ipsum justo amet duo et elitr dolor, est duo duo eos lorem sed diam stet diam sed stet.</p>
                            <a class="btn btn-sm" href=""><i class="fa fa-plus text-primary me-2"></i>Buat Kebun</a>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4 col-md-6 wow fadeInUp" data-wow-delay="0.5s">
                    <div class="service-item rounded d-flex h-100">
                        <div class="service-img rounded">
                            <img class="img-fluid" src="<?php echo base_url('tanaman/'); ?>img/service-3.jpg" alt="">
                        </div>
                        <div class="service-text rounded p-5">
                            <div class="btn-square rounded-circle mx-auto mb-3">
                                <img class="img-fluid" src="<?php echo base_url('tanaman/'); ?>img/icon/icon-8.png" alt="Icon">
                            </div>
                            <h4 class="mb-3">Kelola Kebun Anda</h4>
                            <p class="mb-4">Erat ipsum justo amet duo et elitr dolor, est duo duo eos lorem sed diam stet diam sed stet.</p>
                            <a class="btn btn-sm" href=""><i class="fa fa-plus text-primary me-2"></i>Kelola Kebun</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


 <!-- Features Start -->
 <div class="container-xxl py-5">
        <div class="container">
            <div class="row g-5 align-items-center">
                <div class="col-lg-6 wow fadeInUp" data-wow-delay="0.1s">
                    <p class="fs-5 fw-bold text-primary">Mengapa Memilih Kami!</p>
                    <h1 class="display-5 mb-4">Beberapa Alasan Mengapa Banyak Orang Memilih Kami!</h1>
                    <p class="mb-4">Garden Track memberikan akses mudah ke berbagai informasi tentang tanaman, mulai dari cara perawatan, hingga manfaat kesehatan yang bisa Anda dapatkan. Temukan semua yang perlu Anda ketahui untuk merawat tanaman kesayangan dengan lebih baik!</p>
                </div>
                <div class="col-lg-6">
                    <div class="row g-4 align-items-center">
                        <div class="col-md-6">
                            <div class="row g-4">
                                <div class="col-12 wow fadeIn" data-wow-delay="0.3s">
                                    <div class="text-center rounded py-5 px-4" style="box-shadow: 0 0 45px rgba(0,0,0,.08);">
                                        <div class="btn-square bg-light rounded-circle mx-auto mb-4" style="width: 90px; height: 90px;">
                                            <i class="fa fa-check fa-3x text-primary"></i>
                                        </div>
                                        <h4 class="mb-0">100% Satisfaction</h4>
                                    </div>
                                </div>
                                <div class="col-12 wow fadeIn" data-wow-delay="0.5s">
                                    <div class="text-center rounded py-5 px-4" style="box-shadow: 0 0 45px rgba(0,0,0,.08);">
                                        <div class="btn-square bg-light rounded-circle mx-auto mb-4" style="width: 90px; height: 90px;">
                                            <i class="fa fa-users fa-3x text-primary"></i>
                                        </div>
                                        <h4 class="mb-0">Dedicated Team</h4>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6 wow fadeIn" data-wow-delay="0.7s">
                            <div class="text-center rounded py-5 px-4" style="box-shadow: 0 0 45px rgba(0,0,0,.08);">
                                <div class="btn-square bg-light rounded-circle mx-auto mb-4" style="width: 90px; height: 90px;">
                                    <i class="fa fa-tools fa-3x text-primary"></i>
                                </div>
                                <h4 class="mb-0">Modern Equipment</h4>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Features End -->

    <!-- Quote Start -->
    <div class="container-fluid quote my-5 py-5" data-parallax="scroll" data-image-src="img/carousel-2.jpg">
        <div class="container py-5">
            <div class="row justify-content-center">
                <div class="col-lg-7">
                    <div class="bg-white rounded p-4 p-sm-5 wow fadeIn" data-wow-delay="0.5s">
                        <h1 class="display-5 text-center mb-5">Get A Free Quote</h1>
                        <div class="row g-3">
                            <div class="col-sm-6">
                                <div class="form-floating">
                                    <input type="text" class="form-control bg-light border-0" id="gname" placeholder="Gurdian Name">
                                    <label for="gname">Your Name</label>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="form-floating">
                                    <input type="email" class="form-control bg-light border-0" id="gmail" placeholder="Gurdian Email">
                                    <label for="gmail">Your Email</label>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="form-floating">
                                    <input type="text" class="form-control bg-light border-0" id="cname" placeholder="Child Name">
                                    <label for="cname">Your Mobile</label>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="form-floating">
                                    <input type="text" class="form-control bg-light border-0" id="cage" placeholder="Child Age">
                                    <label for="cage">Service Type</label>
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="form-floating">
                                    <textarea class="form-control bg-light border-0" placeholder="Leave a message here" id="message" style="height: 100px"></textarea>
                                    <label for="message">Message</label>
                                </div>
                            </div>
                            <div class="col-12 text-center">
                                <button class="btn btn-primary py-3 px-4" type="submit">Submit Now</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Quote End -->

<?php echo $this->endSection()?>
