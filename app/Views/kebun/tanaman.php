<?php echo $this->extend('_partials/template') ?>
<?php echo $this->section('isi') ?>
<style>
    body {
        background-color: #ffffff;
    }
    .card {
        background-color: #fff;
        border-radius: 12px;
        box-shadow: 0 8px 20px rgba(0, 0, 0, 0.1);
        overflow: hidden;
        margin: 20px auto;
        max-width: 600px;
        text-align: center;
    }
    .card img {
        width: 100%;
        height: auto;
    }
    .card h5 {
        margin: 15px 0;
        font-size: 20px;
        color: #333;
    }
    .alert {
        margin-top: 20px;
    }
    .btn {
        margin: 10px;
    }
    .container {
        padding: 15px;
    }

    .button {
        width: 50px;
        height: 50px;
        border-radius: 50%;
        background-color: rgb(20, 20, 20);
        border: none;
        font-weight: 600;
        display: flex;
        align-items: center;
        justify-content: center;
        box-shadow: 0px 0px 20px rgba(0, 0, 0, 0.164);
        cursor: pointer;
        transition-duration: .3s;
        overflow: hidden;
        position: relative;
    }
    .svgIcon {
        width: 12px;
        transition-duration: .3s;
    }
    .svgIcon path {
        fill: white;
    }
    .button:hover {
        width: 140px;
        border-radius: 50px;
        transition-duration: .3s;
        background-color: rgb(255, 69, 69);
        align-items: center;
    }
    .button:hover .svgIcon {
        width: 50px;
        transition-duration: .3s;
        transform: translateY(60%);
    }
    .button::before {
        position: absolute;
        top: -20px;
        content: "Delete";
        color: white;
        transition-duration: .3s;
        font-size: 2px;
    }
    .button:hover::before {
        font-size: 13px;
        opacity: 1;
        transform: translateY(30px);
        transition-duration: .3s;
    }
    .edit-button {
        width: 50px;
        height: 50px;
        border-radius: 50%;
        background-color: rgb(20, 20, 20);
        border: none;
        font-weight: 600;
        display: flex;
        align-items: center;
        justify-content: center;
        box-shadow: 0px 0px 20px rgba(0, 0, 0, 0.164);
        cursor: pointer;
        transition-duration: 0.3s;
        overflow: hidden;
        position: relative;
        text-decoration: none !important;
    }
    .edit-svgIcon {
        width: 17px;
        transition-duration: 0.3s;
    }
    .edit-svgIcon path {
        fill: white;
    }
    .edit-button:hover {
        width: 120px;
        border-radius: 50px;
        transition-duration: 0.3s;
        background-color: rgb(255, 69, 69);
        align-items: center;
    }
    .edit-button:hover .edit-svgIcon {
        width: 20px;
        transition-duration: 0.3s;
        transform: translateY(60%);
        -webkit-transform: rotate(360deg);
        -moz-transform: rotate(360deg);
        -o-transform: rotate(360deg);
        -ms-transform: rotate(360deg);
        transform: rotate(360deg);
    }
    .edit-button::before {
        display: none;
        content: "Edit";
        color: white;
        transition-duration: 0.3s;
        font-size: 2px;
    }
    .edit-button:hover::before {
        display: block;
        padding-right: 10px;
        font-size: 13px;
        opacity: 1;
        transform: translateY(0px);
        transition-duration: 0.3s;
    }
    .breadcrumb {
        padding: 10px 0;
        margin-bottom: 20px;
    }
    .breadcrumb a {
        color: #4a7140;
        text-decoration: none;
    }
    .breadcrumb span {
        color: #666;
        margin: 0 5px;
    }

    /* nambah sikit */
    section h2 {
        background-color: #57803c;
        box-shadow: 1px 1px 1px 1px #cac1b3;
        color: #fff;
        font-weight: normal;
        padding: 0.2em;
    }
    .index-cards {
        display: flex;
        flex: none;
        flex-wrap: wrap;
    }
</style>
<!-- Breadcrumb -->
<nav aria-label="breadcrumb" class="px-4 px-lg-5" style="--bs-breadcrumb-divider: url(&#34;data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='8' height='8'%3E%3Cpath d='M2.5 0L1 1.5 3.5 4 1 6.5 2.5 8l4-4-4-4z' fill='%236c757d'/%3E%3C/svg%3E&#34;);">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="/user_page">Home</a></li>
        <li class="breadcrumb-item"><a href="/kebun/semua-kebun">Kebun</a></li>
        <li class="breadcrumb-item active"><?= esc($kebun['nama_kebun']) ?></li>
    </ol>
</nav>
<div class="row">
    <div class="col-md-9">
        <section class="container mt-4">
            <?php if (session()->get('id_user') == $kebun['id_user']): ?>
                <!-- Tombol Tambah Tanaman -->
                <a href="/tanaman/tambah/<?= $kebun['id_kebun'] ?>" class="btn btn-success">Tambah Tanaman</a>
            <?php endif; ?>
            <!-- Daftar Tanaman -->
            <?php if (empty($tanaman)): ?>
                <div class="alert alert-info text-center">
                    Belum memiliki tanaman di kebun ini.
                    <br>
                </div>
            <?php else:?>
                <section>
                    <h2>Tanaman Pada Kebun <?= $kebun['nama_kebun'] ?></h2>
                    <div class="row row-cols-1 row-cols-md-3 g-4">
                        <?php foreach ($tanaman as $item) : 
                            // Ambil image_url untuk setiap tanaman, atau set ke 'default.png' jika tidak ada
                            $image = $item['image_url'] ?? 'default.png'; 

                            // Mengecek apakah $image adalah URL yang valid
                            if (!empty($image) && filter_var($image, FILTER_VALIDATE_URL)) {
                                // Jika $image adalah URL yang valid, gunakan langsung URL tersebut
                                $poto = $image; 
                            } else {
                                // Jika $image bukan URL atau kosong, cek apakah file tersebut ada di folder uploads
                                $filePath = 'public/uploads/tanaman/' . $image;

                                // Cek apakah file ada di server (upload folder)
                                if (!empty($image) && file_exists($filePath)) {
                                    // Jika file ada di folder uploads, gunakan path ke file tersebut
                                    $poto = base_url('uploads/tanaman/' . $image);
                                } else {
                                    // Jika tidak ada file, gunakan gambar default
                                    $poto = base_url('uploads/tanaman/default.png');
                                }
                            }
                        ?>
                        
                            <div class="col">
                                <div class="card h-100">
                                    <a href="/tanaman/detail/<?= $item['id'] ?>" >
                                        <img src="<?= $poto; ?>" class="card-img-top" alt="...">
                                    </a>
                                    <div class="card-body">
                                        <h5 class="card-title"><?= esc($item['common_name']);?></h5>
                                        <p class="card-text">This is a wider card with supporting text below as a natural lead-in to additional content. This content is a little bit longer.</p>
                                    </div>
                                    <div class="card-footer">
                                        <small class="text-body-secondary">DiTanam Pada <?= esc($item['tanggal_mulai']); ?></small>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </section>
            <?php endif; ?>
        </section>
    </div>
    <div class="col-md-3">
        <section class="container mt-4">
            <!-- Kartu Detail Kebun -->
            <div class="card">
                <img src="<?= base_url('uploads/kebun/' . $kebun['poto_kebun']) ?>" alt="<?= htmlspecialchars($kebun['nama_kebun']) ?>">
                <div class="card-body">
                    <h5>Nama Kebun: <?= htmlspecialchars($kebun['nama_kebun']) ?></h5>
                        <!-- Tombol Edit dan Hapus -->
                    <div style="display: flex; justify-content: center; gap: 10px;">
                        <?php if (session()->get('id_user') == $kebun['id_user']): ?>
                            <!-- Tombol Edit -->
                            <a href="/kebun/edit/<?= $kebun['id_kebun']; ?>" style="text-decoration: none;">
                                <button class="edit-button">
                                    <svg class="edit-svgIcon" viewBox="0 0 512 512">
                                        <path d="M410.3 231l11.3-11.3-33.9-33.9-62.1-62.1L291.7 89.8l-11.3 11.3-22.6 22.6L58.6 322.9c-10.4 10.4-18 23.3-22.2 37.4L1 480.7c-2.5 8.4-.2 17.5 6.1 23.7s15.3 8.5 23.7 6.1l120.3-35.4c14.1-4.2 27-11.8 37.4-22.2L387.7 253.7 410.3 231zM160 399.4l-9.1 22.7c-4 3.1-8.5 5.4-13.3 6.9L59.4 452l23-78.1c1.4-4.9 3.8-9.4 6.9-13.3l22.7-9.1v32c0 8.8 7.2 16 16 16h32zM362.7 18.7L348.3 33.2 325.7 55.8 314.3 67.1l33.9 33.9 62.1 62.1 33.9 33.9 11.3-11.3 22.6-22.6 14.5-14.5c25-25 25-65.5 0-90.5L453.3 18.7c-25-25-65.5-25-90.5 0zm-47.4 168l-144 144c-6.2 6.2-16.4 6.2-22.6 0s-6.2-16.4 0-22.6l144-144c6.2-6.2 16.4-6.2 22.6 0s6.2 16.4 0 22.6z"></path>
                                    </svg>
                                </button>
                            </a>
                            <!-- Tombol Hapus -->
                            <a href="/kebun/delete/<?= $kebun['id_kebun']; ?>" id="deleteButton" style="text-decoration: none;">
                                <button class="button">
                                    <svg viewBox="0 0 448 512" class="svgIcon">
                                        <path d="M135.2 17.7L128 32H32C14.3 32 0 46.3 0 64S14.3 96 32 96H416c17.7 0 32-14.3 32-32s-14.3-32-32-32H320l-7.2-14.3C307.4 6.8 296.3 0 284.2 0H163.8c-12.1 0-23.2 6.8-28.6 17.7zM416 128H32L53.2 467c1.6 25.3 22.6 45 47.9 45H346.9c25.3 0 46.3-19.7 47.9-45L416 128z"></path>
                                    </svg>
                                </button>
                            </a>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </section>
    </div>
</div>

<!-- <div id="wpdcom" class="wpdiscuz_unauth wpd-dark wpd-layout-3 wpd-comments-open"> 
<div class="wc_social_plugin_wrapper"></div>
<div class="wpd-form-wrap"> 
<div class="wpd-form-head"> 
<div class="wpd-sbs-toggle"> 
<i class="far fa-envelope"></i>
 <span class="wpd-sbs-title">Subscribe</span> 
 <i class="fas fa-caret-down"></i></div>
 <div class="wpd-auth"> 
 <div class="wpd-login"></div>
 </div>
 </div>
 <div class="wpdiscuz-subscribe-bar wpdiscuz-hidden">
  <form action="https://tv7.idlix.asia/wp-admin/admin-ajax.php?action=wpdAddSubscription" method="post" id="wpdiscuz-subscribe-form"> 
  <div class="wpdiscuz-subscribe-form-intro">Notify of</div>
  <div class="wpdiscuz-subscribe-form-option" style="width:40%;"> 
  <select class="wpdiscuz_select" name="wpdiscuzSubscriptionType">
   <option value="post">new follow-up comments</option> 
   <option value="all_comment">new replies to my comments</option> 
   </select></div>
   <div class="wpdiscuz-item wpdiscuz-subscribe-form-email"> 
   <input class="email" type="email" name="wpdiscuzSubscriptionEmail" required="required" value="" placeholder="Email" style="background-image: url(&quot;data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAADAAAAAwCAYAAABXAvmHAAAAGXRFWHRTb2Z0d2FyZQBBZG9iZSBJbWFnZVJlYWR5ccllPAAAA3ZpVFh0WE1MOmNvbS5hZG9iZS54bXAAAAAAADw/eHBhY2tldCBiZWdpbj0i77u/IiBpZD0iVzVNME1wQ2VoaUh6cmVTek5UY3prYzlkIj8+IDx4OnhtcG1ldGEgeG1sbnM6eD0iYWRvYmU6bnM6bWV0YS8iIHg6eG1wdGs9IkFkb2JlIFhNUCBDb3JlIDUuMy1jMDExIDY2LjE0NTY2MSwgMjAxMi8wMi8wNi0xNDo1NjoyNyAgICAgICAgIj4gPHJkZjpSREYgeG1sbnM6cmRmPSJodHRwOi8vd3d3LnczLm9yZy8xOTk5LzAyLzIyLXJkZi1zeW50YXgtbnMjIj4gPHJkZjpEZXNjcmlwdGlvbiByZGY6YWJvdXQ9IiIgeG1sbnM6eG1wTU09Imh0dHA6Ly9ucy5hZG9iZS5jb20veGFwLzEuMC9tbS8iIHhtbG5zOnN0UmVmPSJodHRwOi8vbnMuYWRvYmUuY29tL3hhcC8xLjAvc1R5cGUvUmVzb3VyY2VSZWYjIiB4bWxuczp4bXA9Imh0dHA6Ly9ucy5hZG9iZS5jb20veGFwLzEuMC8iIHhtcE1NOk9yaWdpbmFsRG9jdW1lbnRJRD0ieG1wLmRpZDpiYmZkZTQxOS00ZGRkLWU5NDYtOWQ2MC05OGExNGJiMTA3N2YiIHhtcE1NOkRvY3VtZW50SUQ9InhtcC5kaWQ6RDAyNDkwMkRDOTIyMTFFNkI0MzFGRTk2RjM1OTdENTciIHhtcE1NOkluc3RhbmNlSUQ9InhtcC5paWQ6RDAyNDkwMkNDOTIyMTFFNkI0MzFGRTk2RjM1OTdENTciIHhtcDpDcmVhdG9yVG9vbD0iQWRvYmUgUGhvdG9zaG9wIENDIDIwMTUgKFdpbmRvd3MpIj4gPHhtcE1NOkRlcml2ZWRGcm9tIHN0UmVmOmluc3RhbmNlSUQ9InhtcC5paWQ6OTU2NTE1NDItMmIzOC1kZjRkLTk0N2UtN2NjOTlmMjQ5ZGFjIiBzdFJlZjpkb2N1bWVudElEPSJ4bXAuZGlkOmJiZmRlNDE5LTRkZGQtZTk0Ni05ZDYwLTk4YTE0YmIxMDc3ZiIv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&quot;) !important; background-repeat: no-repeat; background-size: 20px; background-position: 97% center; cursor: auto;" data-temp-mail-org="0"></div><div class="wpdiscuz-subscribe-form-button"> <input id="wpdiscuz_subscription_button" class="wpd-prim-button wpd_not_clicked" type="submit" value="›" name="wpdiscuz_subscription_button"></div><input type="hidden" id="wpdiscuz_subscribe_form_nonce" name="wpdiscuz_subscribe_form_nonce" value="7b98d91d7e"><input type="hidden" name="_wp_http_referer" value="/tvseries/study-group-2025/"> </form></div><div class="wpd-form wpd-form-wrapper wpd-main-form-wrapper" id="wpd-main-form-wrapper-0_0"> <form method="post" enctype="multipart/form-data" data-uploading="false" class="wpd_comm_form wpd_main_comm_form"> <div class="wpd-field-comment"> <div class="wpdiscuz-item wc-field-textarea"> <div class="wpdiscuz-textarea-wrap"> <div class="wpd-avatar"> <img alt="guest" src="https://secure.gravatar.com/avatar/f391101c63404eeea2fad2ab2821ecea?s=56&amp;d=mm&amp;r=r" srcset="https://secure.gravatar.com/avatar/f391101c63404eeea2fad2ab2821ecea?s=112&amp;d=mm&amp;r=r 2x" class="avatar avatar-56 photo" height="56" width="56" decoding="async"></div><div id="wpd-editor-wraper-0_0" style=""> <div id="wpd-editor-char-counter-0_0" class="wpd-editor-char-counter">1500</div><label style="display: none;" for="wc-textarea-0_0">Label</label>  <div id="wpd-editor-0_0" class="ql-container ql-snow"><div class="ql-editor ql-blank" data-gramm="false" contenteditable="true" data-placeholder="Join the discussion"><p><br></p></div><div class="ql-clipboard" contenteditable="true" tabindex="-1"></div><div class="ql-tooltip ql-hidden"><a class="ql-preview" target="_blank" href="about:blank"></a><input type="text" data-formula="e=mc^2" data-link="https://example.com" data-video="Embed URL"><a class="ql-action"></a><a class="ql-remove"></a></div><div class="ql-texteditor"><textarea id="wc-textarea-0_0" name="wc_comment" class="wc_comment wpd-field" style="display: none;"></textarea></div></div><div id="wpd-editor-toolbar-0_0" class="ql-toolbar ql-snow"> <button title="Bold" class="ql-bold" type="button"><svg viewBox="0 0 18 18"> <path class="ql-stroke" d="M5,4H9.5A2.5,2.5,0,0,1,12,6.5v0A2.5,2.5,0,0,1,9.5,9H5A0,0,0,0,1,5,9V4A0,0,0,0,1,5,4Z"></path> <path class="ql-stroke" d="M5,9h5.5A2.5,2.5,0,0,1,13,11.5v0A2.5,2.5,0,0,1,10.5,14H5a0,0,0,0,1,0,0V9A0,0,0,0,1,5,9Z"></path> </svg></button> <button title="Italic" class="ql-italic" type="button"><svg viewBox="0 0 18 18"> <line class="ql-stroke" x1="7" x2="13" y1="4" y2="4"></line> <line class="ql-stroke" x1="5" x2="11" y1="14" y2="14"></line> <line class="ql-stroke" x1="8" x2="10" y1="14" y2="4"></line> </svg></button> <button title="Underline" class="ql-underline" type="button"><svg viewBox="0 0 18 18"> <path class="ql-stroke" d="M5,3V9a4.012,4.012,0,0,0,4,4H9a4.012,4.012,0,0,0,4-4V3"></path> <rect class="ql-fill" height="1" rx="0.5" ry="0.5" width="12" x="3" y="15"></rect> </svg></button> <button title="Strike" class="ql-strike" type="button"><svg viewBox="0 0 18 18"> <line class="ql-stroke ql-thin" x1="15.5" x2="2.5" y1="8.5" y2="9.5"></line> <path class="ql-fill" d="M9.007,8C6.542,7.791,6,7.519,6,6.5,6,5.792,7.283,5,9,5c1.571,0,2.765.679,2.969,1.309a1,1,0,0,0,1.9-.617C13.356,4.106,11.354,3,9,3,6.2,3,4,4.538,4,6.5a3.2,3.2,0,0,0,.5,1.843Z"></path> <path class="ql-fill" d="M8.984,10C11.457,10.208,12,10.479,12,11.5c0,0.708-1.283,1.5-3,1.5-1.571,0-2.765-.679-2.969-1.309a1,1,0,1,0-1.9.617C4.644,13.894,6.646,15,9,15c2.8,0,5-1.538,5-3.5a3.2,3.2,0,0,0-.5-1.843Z"></path> </svg></button> <button title="Ordered List" class="ql-list" value="ordered" type="button"><svg viewBox="0 0 18 18"> <line class="ql-stroke" x1="7" x2="15" y1="4" y2="4"></line> <line class="ql-stroke" x1="7" x2="15" y1="9" y2="9"></line> <line class="ql-stroke" x1="7" x2="15" y1="14" y2="14"></line> <line class="ql-stroke ql-thin" x1="2.5" x2="4.5" y1="5.5" y2="5.5"></line> <path class="ql-fill" d="M3.5,6A0.5,0.5,0,0,1,3,5.5V3.085l-0.276.138A0.5,0.5,0,0,1,2.053,3c-0.124-.247-0.023-0.324.224-0.447l1-.5A0.5,0.5,0,0,1,4,2.5v3A0.5,0.5,0,0,1,3.5,6Z"></path> <path class="ql-stroke ql-thin" d="M4.5,10.5h-2c0-.234,1.85-1.076,1.85-2.234A0.959,0.959,0,0,0,2.5,8.156"></path> <path class="ql-stroke ql-thin" d="M2.5,14.846a0.959,0.959,0,0,0,1.85-.109A0.7,0.7,0,0,0,3.75,14a0.688,0.688,0,0,0,.6-0.736,0.959,0.959,0,0,0-1.85-.109"></path> </svg></button> <button title="Unordered List" class="ql-list" value="bullet" type="button"><svg viewBox="0 0 18 18"> <line class="ql-stroke" x1="6" x2="15" y1="4" y2="4"></line> <line class="ql-stroke" x1="6" x2="15" y1="9" y2="9"></line> <line class="ql-stroke" x1="6" x2="15" y1="14" y2="14"></line> <line class="ql-stroke" x1="3" x2="3" y1="4" y2="4"></line> <line class="ql-stroke" x1="3" x2="3" y1="9" y2="9"></line> <line class="ql-stroke" x1="3" x2="3" y1="14" y2="14"></line> </svg></button> <button title="Blockquote" class="ql-blockquote" type="button"><svg viewBox="0 0 18 18"> <rect class="ql-fill ql-stroke" height="3" width="3" x="4" y="5"></rect> <rect class="ql-fill ql-stroke" height="3" width="3" x="11" y="5"></rect> <path class="ql-even ql-fill ql-stroke" d="M7,8c0,4.031-3,5-3,5"></path> <path class="ql-even ql-fill ql-stroke" d="M14,8c0,4.031-3,5-3,5"></path> </svg></button> <button title="Code Block" class="ql-code-block" type="button"><svg viewBox="0 0 18 18"> <polyline class="ql-even ql-stroke" points="5 7 3 9 5 11"></polyline> <polyline class="ql-even ql-stroke" points="13 7 15 9 13 11"></polyline> <line class="ql-stroke" x1="10" x2="8" y1="5" y2="13"></line> </svg></button> <button title="Source Code" class="ql-sourcecode" data-wpde_button_name="sourcecode" type="button">{}</button> <button title="Spoiler" class="ql-spoiler" data-wpde_button_name="spoiler" type="button">[+]</button> <div class="wpd-editor-buttons-right"></div></div></div></div></div></div><div class="wpd-form-foot" style="display:none;"> <div class="wpdiscuz-textarea-foot"> <div class="wpdiscuz-button-actions"></div></div><div class="wpd-form-row"> <div class="wpd-form-col-left"> <div class="wpdiscuz-item wc_name-wrapper wpd-has-icon"> <div class="wpd-field-icon"><i class="fas fa-user"></i></div><input id="wc_name-0_0" value="" required="required" aria-required="true" class="wc_name wpd-field" type="text" name="wc_name" placeholder="Name*" maxlength="25" pattern=".{6,25}" title=""> <label for="wc_name-0_0" class="wpdlb">Name*</label></div><div class="wpdiscuz-item wc_email-wrapper wpd-has-icon"> <div class="wpd-field-icon">
   <i class="fas fa-at"></i>
   </div>
   <input id="wc_email-0_0" value="" required="required" aria-required="true" class="wc_email wpd-field" type="email" name="wc_email" placeholder="Email*" style="background-image: url(&quot;data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAADAAAAAwCAYAAABXAvmHAAAAGXRFWHRTb2Z0d2FyZQBBZG9iZSBJbWFnZVJlYWR5ccllPAAAA3ZpVFh0WE1MOmNvbS5hZG9iZS54bXAAAAAAADw/eHBhY2tldCBiZWdpbj0i77u/IiBpZD0iVzVNME1wQ2VoaUh6cmVTek5UY3prYzlkIj8+IDx4OnhtcG1ldGEgeG1sbnM6eD0iYWRvYmU6bnM6bWV0YS8iIHg6eG1wdGs9IkFkb2JlIFhNUCBDb3JlIDUuMy1jMDExIDY2LjE0NTY2MSwgMjAxMi8wMi8wNi0xNDo1NjoyNyAgICAgICAgIj4gPHJkZjpSREYgeG1sbnM6cmRmPSJodHRwOi8vd3d3LnczLm9yZy8xOTk5LzAyLzIyLXJkZi1zeW50YXgtbnMjIj4gPHJkZjpEZXNjcmlwdGlvbiByZGY6YWJvdXQ9IiIgeG1sbnM6eG1wTU09Imh0dHA6Ly9ucy5hZG9iZS5jb20veGFwLzEuMC9tbS8iIHhtbG5zOnN0UmVmPSJodHRwOi8vbnMuYWRvYmUuY29tL3hhcC8xLjAvc1R5cGUvUmVzb3VyY2VSZWYjIiB4bWxuczp4bXA9Imh0dHA6Ly9ucy5hZG9iZS5jb20veGFwLzEuMC8iIHhtcE1NOk9yaWdpbmFsRG9jdW1lbnRJRD0ieG1wLmRpZDpiYmZkZTQxOS00ZGRkLWU5NDYtOWQ2MC05OGExNGJiMTA3N2YiIHhtcE1NOkRvY3VtZW50SUQ9InhtcC5kaWQ6RDAyNDkwMkRDOTIyMTFFNkI0MzFGRTk2RjM1OTdENTciIHhtcE1NOkluc3RhbmNlSUQ9InhtcC5paWQ6RDAyNDkwMkNDOTIyMTFFNkI0MzFGRTk2RjM1OTdENTciIHhtcDpDcmVhdG9yVG9vbD0iQWRvYmUgUGhvdG9zaG9wIENDIDIwMTUgKFdpbmRvd3MpIj4gPHhtcE1NOkRlcml2ZWRGcm9tIHN0UmVmOmluc3RhbmNlSUQ9InhtcC5paWQ6OTU2NTE1NDItMmIzOC1kZjRkLTk0N2UtN2NjOTlmMjQ5ZGFjIiBzdFJlZjpkb2N1bWVudElEPSJ4bXAuZGlkOmJiZmRlNDE5LTRkZGQtZTk0Ni05ZDYwLTk4YTE0YmIxMDc3ZiIv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&quot;) !important; background-repeat: no-repeat; background-size: 20px; background-position: 97% center; cursor: auto;" data-temp-mail-org="1"> <label for="wc_email-0_0" class="wpdlb">Email*</label></div></div><div class="wpd-form-col-right"> <div class="wc-field-submit"> <label class="wpd_label" wpd-tooltip="Notify of new replies to this comment"> <input id="wc_notification_new_comment-0_0" class="wc_notification_new_comment-0_0 wpd_label__checkbox" value="comment" type="checkbox" name="wpdiscuz_notification_type"> <span class="wpd_label__text"> <span class="wpd_label__check"> <i class="fas fa-bell wpdicon wpdicon-on"></i> <i class="fas fa-bell-slash wpdicon wpdicon-off"></i> </span> </span> </label> <input id="wpd-field-submit-0_0" class="wc_comm_submit wpd_not_clicked wpd-prim-button" type="submit" name="submit" value="Post Comment" aria-label="Post Comment"></div></div><div class="clearfix"></div></div></div><input type="hidden" class="wpdiscuz_unique_id" value="0_0" name="wpdiscuz_unique_id"> <p style="display: none;"><input type="hidden" id="akismet_comment_nonce" name="akismet_comment_nonce" value="aaf3563119"></p><p style="display: none !important;" class="akismet-fields-container" data-prefix="ak_"><label>Δ<textarea name="ak_hp_textarea" cols="45" rows="8" maxlength="100"></textarea></label><input type="hidden" id="ak_js_1" name="ak_js" value="1738702656647"></p> </form></div><div id="wpdiscuz_hidden_secondary_form" style="display: none;"> <div class="wpd-form wpd-form-wrapper wpd-secondary-form-wrapper" id="wpd-secondary-form-wrapper-wpdiscuzuniqueid" style="display: none;"> <div class="wpd-secondary-forms-social-content"></div><div class="clearfix"></div><form method="post" enctype="multipart/form-data" data-uploading="false" class="wpd_comm_form wpd-secondary-form-wrapper"> <div class="wpd-field-comment"> <div class="wpdiscuz-item wc-field-textarea"> <div class="wpdiscuz-textarea-wrap"> <div class="wpd-avatar"> <img alt="guest" src="https://secure.gravatar.com/avatar/a61231df71ed8f1eb5320b312717dfbd?s=56&amp;d=mm&amp;r=r" srcset="https://secure.gravatar.com/avatar/a61231df71ed8f1eb5320b312717dfbd?s=112&amp;d=mm&amp;r=r 2x" class="avatar avatar-56 photo" height="56" width="56" decoding="async"></div><div id="wpd-editor-wraper-wpdiscuzuniqueid" style="display: none;"> <div id="wpd-editor-char-counter-wpdiscuzuniqueid" class="wpd-editor-char-counter"></div><label style="display: none;" for="wc-textarea-wpdiscuzuniqueid">Label</label> <textarea id="wc-textarea-wpdiscuzuniqueid" name="wc_comment" class="wc_comment wpd-field"></textarea> <div id="wpd-editor-wpdiscuzuniqueid"></div>
   <div id="wpd-editor-toolbar-wpdiscuzuniqueid"> <button title="Bold" class="ql-bold"></button> <button title="Italic" class="ql-italic"></button> <button title="Underline" class="ql-underline"></button> <button title="Strike" class="ql-strike"></button> <button title="Ordered List" class="ql-list" value="ordered"></button> <button title="Unordered List" class="ql-list" value="bullet"></button> <button title="Blockquote" class="ql-blockquote"></button> <button title="Code Block" class="ql-code-block"></button> <button title="Source Code" class="ql-sourcecode" data-wpde_button_name="sourcecode">{}</button> <button title="Spoiler" class="ql-spoiler" data-wpde_button_name="spoiler">[+]</button> <div class="wpd-editor-buttons-right"></div></div></div></div></div></div><div class="wpd-form-foot" style="display:none;"> <div class="wpdiscuz-textarea-foot"> <div class="wpdiscuz-button-actions"></div></div><div class="wpd-form-row"> <div class="wpd-form-col-left"> <div class="wpdiscuz-item wc_name-wrapper wpd-has-icon"> <div class="wpd-field-icon"><i class="fas fa-user"></i></div><input id="wc_name-wpdiscuzuniqueid" value="" required="required" aria-required="true" class="wc_name wpd-field" type="text" name="wc_name" placeholder="Name*" maxlength="25" pattern=".{6,25}" title=""> <label for="wc_name-wpdiscuzuniqueid" class="wpdlb">Name*</label></div><div class="wpdiscuz-item wc_email-wrapper wpd-has-icon"> <div class="wpd-field-icon"><i class="fas fa-at"></i></div><input id="wc_email-wpdiscuzuniqueid" value="" required="required" aria-required="true" class="wc_email wpd-field" type="email" name="wc_email" placeholder="Email*" style="background-image: url(&quot;data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAADAAAAAwCAYAAABXAvmHAAAAGXRFWHRTb2Z0d2FyZQBBZG9iZSBJbWFnZVJlYWR5ccllPAAAA3ZpVFh0WE1MOmNvbS5hZG9iZS54bXAAAAAAADw/eHBhY2tldCBiZWdpbj0i77u/IiBpZD0iVzVNME1wQ2VoaUh6cmVTek5UY3prYzlkIj8+IDx4OnhtcG1ldGEgeG1sbnM6eD0iYWRvYmU6bnM6bWV0YS8iIHg6eG1wdGs9IkFkb2JlIFhNUCBDb3JlIDUuMy1jMDExIDY2LjE0NTY2MSwgMjAxMi8wMi8wNi0xNDo1NjoyNyAgICAgICAgIj4gPHJkZjpSREYgeG1sbnM6cmRmPSJodHRwOi8vd3d3LnczLm9yZy8xOTk5LzAyLzIyLXJkZi1zeW50YXgtbnMjIj4gPHJkZjpEZXNjcmlwdGlvbiByZGY6YWJvdXQ9IiIgeG1sbnM6eG1wTU09Imh0dHA6Ly9ucy5hZG9iZS5jb20veGFwLzEuMC9tbS8iIHhtbG5zOnN0UmVmPSJodHRwOi8vbnMuYWRvYmUuY29tL3hhcC8xLjAvc1R5cGUvUmVzb3VyY2VSZWYjIiB4bWxuczp4bXA9Imh0dHA6Ly9ucy5hZG9iZS5jb20veGFwLzEuMC8iIHhtcE1NOk9yaWdpbmFsRG9jdW1lbnRJRD0ieG1wLmRpZDpiYmZkZTQxOS00ZGRkLWU5NDYtOWQ2MC05OGExNGJiMTA3N2YiIHhtcE1NOkRvY3VtZW50SUQ9InhtcC5kaWQ6RDAyNDkwMkRDOTIyMTFFNkI0MzFGRTk2RjM1OTdENTciIHhtcE1NOkluc3RhbmNlSUQ9InhtcC5paWQ6RDAyNDkwMkNDOTIyMTFFNkI0MzFGRTk2RjM1OTdENTciIHhtcDpDcmVhdG9yVG9vbD0iQWRvYmUgUGhvdG9zaG9wIENDIDIwMTUgKFdpbmRvd3MpIj4gPHhtcE1NOkRlcml2ZWRGcm9tIHN0UmVmOmluc3RhbmNlSUQ9InhtcC5paWQ6OTU2NTE1NDItMmIzOC1kZjRkLTk0N2UtN2NjOTlmMjQ5ZGFjIiBzdFJlZjpkb2N1bWVudElEPSJ4bXAuZGlkOmJiZmRlNDE5LTRkZGQtZTk0Ni05ZDYwLTk4YTE0YmIxMDc3ZiIv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&quot;) !important; background-repeat: no-repeat; background-size: 20px; background-position: 97% center; cursor: auto;" data-temp-mail-org="2"> <label for="wc_email-wpdiscuzuniqueid" class="wpdlb">Email*</label></div></div><div class="wpd-form-col-right"> <div class="wc-field-submit"> <label class="wpd_label" wpd-tooltip="Notify of new replies to this comment"> <input id="wc_notification_new_comment-wpdiscuzuniqueid" class="wc_notification_new_comment-wpdiscuzuniqueid wpd_label__checkbox" value="comment" type="checkbox" name="wpdiscuz_notification_type"> <span class="wpd_label__text"> <span class="wpd_label__check"> <i class="fas fa-bell wpdicon wpdicon-on"></i> <i class="fas fa-bell-slash wpdicon wpdicon-off"></i> </span> </span> </label> <input id="wpd-field-submit-wpdiscuzuniqueid" class="wc_comm_submit wpd_not_clicked wpd-prim-button" type="submit" name="submit" value="Post Comment" aria-label="Post Comment"></div></div><div class="clearfix"></div></div></div><input type="hidden" class="wpdiscuz_unique_id" value="wpdiscuzuniqueid" name="wpdiscuz_unique_id"> <p style="display: none;">
   <input type="hidden" id="akismet_comment_nonce" name="akismet_comment_nonce" value="aaf3563119"></p><p style="display: none !important;" class="akismet-fields-container" data-prefix="ak_"><label>Δ<textarea name="ak_hp_textarea" cols="45" rows="8" maxlength="100"></textarea></label><input type="hidden" id="ak_js_2" name="ak_js" value="1738702656647"></p> </form></div></div></div><div id="wpd-threads" class="wpd-thread-wrapper"> <div class="wpd-thread-head"> <div class="wpd-thread-info" data-comments-count="25"> <span class="wpdtc" title="25">25</span> Comments</div><div class="wpd-space"></div><div class="wpd-thread-filter"> <div class="wpd-filter wpdf-reacted wpd_not_clicked" wpd-tooltip="Most reacted comment"> <i class="fas fa-bolt"></i></div><div class="wpd-filter wpdf-hottest wpd_not_clicked" wpd-tooltip="Hottest comment thread"> <i class="fas fa-fire"></i></div><div class="wpd-filter wpdf-sorting"> <span class="wpdiscuz-sort-button wpdiscuz-vote-sort-up wpdiscuz-sort-button-active" data-sorting="by_vote">Most Voted</span> <i class="fas fa-sort-down"></i> <div class="wpdiscuz-sort-buttons"> <span class="wpdiscuz-sort-button wpdiscuz-date-sort-desc" data-sorting="newest">Newest</span> <span class="wpdiscuz-sort-button wpdiscuz-date-sort-asc" data-sorting="oldest">Oldest</span></div></div></div></div><div class="wpd-comment-info-bar"> <div class="wpd-current-view"><i class="fas fa-quote-left"></i> Inline Feedbacks</div><div class="wpd-filter-view-all">View all comments</div></div><div class="wpd-thread-list"> <div id="wpd-comm-62044_0" class="comment even thread-even depth-1 wpd-comment wpd_comment_level-1"><div class="wpd-comment-wrap wpd-blog-guest"> <div class="wpd-comment-left"> <div class="wpd-avatar"> <img alt="Rendy" src="https://secure.gravatar.com/avatar/cab91f7e10f8b21a55070776e903d66c?s=64&amp;d=mm&amp;r=r" srcset="https://secure.gravatar.com/avatar/cab91f7e10f8b21a55070776e903d66c?s=128&amp;d=mm&amp;r=r 2x" class="avatar avatar-64 photo" height="64" width="64" decoding="async"></div></div><div id="comment-62044" class="wpd-comment-right"> <div class="wpd-comment-header"> <div class="wpd-comment-author"> Rendy</div><div class="wpd-space"></div><div class="wpd-comment-link wpd-hidden"> <span wpd-tooltip="Comment Link" wpd-tooltip-position="left"><i class="fas fa-link" aria-hidden="true" data-wpd-clipboard="https://tv7.idlix.asia/tvseries/study-group-2025/#comment-62044"></i></span></div></div><div class="wpd-comment-subheader"> <div class="wpd-comment-label" wpd-tooltip="Guest" wpd-tooltip-position="top"> <span>Guest</span></div><div class="wpd-comment-date" title="30 January, 2025 7:55 pm"> <i class="far fa-clock" aria-hidden="true"></i> 30 January, 2025 7:55 pm</div></div><div class="wpd-comment-text"> <p>Kapan ini episode 3-4</p></div><div class="wpd-comment-footer"> <div class="wpd-vote"> <div class="wpd-vote-up wpd_not_clicked"> <svg xmlns="https://www.w3.org/2000/svg" viewBox="0 0 24 24"><path fill="none" d="M0 0h24v24H0V0z"></path><path d="M1 21h4V9H1v12zm22-11c0-1.1-.9-2-2-2h-6.31l.95-4.57.03-.32c0-.41-.17-.79-.44-1.06L14.17 1 7.59 7.59C7.22 7.95 7 8.45 7 9v10c0 1.1.9 2 2 2h9c.83 0 1.54-.5 1.84-1.22l3.02-7.05c.09-.23.14-.47.14-.73v-2z"></path></svg></div><div class="wpd-vote-result wpd-up" title="11">11</div><div class="wpd-vote-down wpd_not_clicked"> <svg xmlns="https://www.w3.org/2000/svg" viewBox="0 0 24 24"><path fill="none" d="M0 0h24v24H0z"></path><path d="M15 3H6c-.83 0-1.54.5-1.84 1.22l-3.02 7.05c-.09.23-.14.47-.14.73v2c0 1.1.9 2 2 2h6.31l-.95 4.57-.03.32c0 .41.17.79.44 1.06L9.83 23l6.59-6.59c.36-.36.58-.86.58-1.41V5c0-1.1-.9-2-2-2zm4 0v12h4V3h-4z"></path></svg></div></div><div class="wpd-reply-button"> <svg xmlns="https://www.w3.org/2000/svg" viewBox="0 0 24 24"><path d="M10 9V5l-7 7 7 7v-4.1c5 0 8.5 1.6 11 5.1-1-5-4-10-11-11z"></path><path d="M0 0h24v24H0z" fill="none"></path></svg> <span>Reply</span></div><div class="wpd-wpanel"></div><div class="wpd-space"></div><div class="wpd-tool-wrap"> <div class="wpd-toggle wpd-hidden wpd_not_clicked" wpd-tooltip="Hide Replies"> <i class="fas fa-chevron-up"></i></div></div></div></div></div><div id="wpdiscuz_form_anchor-62044_0"></div><div id="wpd-comm-62173_62044" class="comment odd alt depth-2 wpd-comment wpd-reply wpd_comment_level-2"><div class="wpd-comment-wrap wpd-blog-guest"> <div class="wpd-comment-left"> <div class="wpd-avatar"> <img alt="FRIEREN" src="https://secure.gravatar.com/avatar/d3639ab3b3c231c485c87b620d9adcc6?s=64&amp;d=mm&amp;r=r" srcset="https://secure.gravatar.com/avatar/d3639ab3b3c231c485c87b620d9adcc6?s=128&amp;d=mm&amp;r=r 2x" class="avatar avatar-64 photo" height="64" width="64" loading="lazy" decoding="async"></div></div><div id="comment-62173" class="wpd-comment-right"> <div class="wpd-comment-header"> <div class="wpd-comment-author"> FRIEREN</div><div class="wpd-comment-label" wpd-tooltip="Guest" wpd-tooltip-position="top"> <span>Guest</span></div><div class="wpd-space"></div><div class="wpd-comment-link wpd-hidden"> <span wpd-tooltip="Comment Link" wpd-tooltip-position="left"><i class="fas fa-link" aria-hidden="true" data-wpd-clipboard="https://tv7.idlix.asia/tvseries/study-group-2025/#comment-62173"></i></span></div></div><div class="wpd-reply-to"> <i class="far fa-comments"></i> Reply to&nbsp; <a href="#comment-62044"> Rendy </a> <div class="wpd-comment-date" title="31 January, 2025 7:56 pm"> <i class="far fa-clock" aria-hidden="true"></i> 31 January, 2025 7:56 pm</div></div><div class="wpd-comment-text"> <p>Aslii ini manaaa</p></div><div class="wpd-comment-footer"> <div class="wpd-vote"> <div class="wpd-vote-up wpd_not_clicked"> <svg xmlns="https://www.w3.org/2000/svg" viewBox="0 0 24 24"><path fill="none" d="M0 0h24v24H0V0z"></path><path d="M1 21h4V9H1v12zm22-11c0-1.1-.9-2-2-2h-6.31l.95-4.57.03-.32c0-.41-.17-.79-.44-1.06L14.17 1 7.59 7.59C7.22 7.95 7 8.45 7 9v10c0 1.1.9 2 2 2h9c.83 0 1.54-.5 1.84-1.22l3.02-7.05c.09-.23.14-.47.14-.73v-2z"></path></svg></div><div class="wpd-vote-result wpd-up" title="5">5</div><div class="wpd-vote-down wpd_not_clicked"> <svg xmlns="https://www.w3.org/2000/svg" viewBox="0 0 24 24"><path fill="none" d="M0 0h24v24H0z"></path><path d="M15 3H6c-.83 0-1.54.5-1.84 1.22l-3.02 7.05c-.09.23-.14.47-.14.73v2c0 1.1.9 2 2 2h6.31l-.95 4.57-.03.32c0 .41.17.79.44 1.06L9.83 23l6.59-6.59c.36-.36.58-.86.58-1.41V5c0-1.1-.9-2-2-2zm4 0v12h4V3h-4z"></path></svg></div></div><div class="wpd-reply-button"> <svg xmlns="https://www.w3.org/2000/svg" viewBox="0 0 24 24"><path d="M10 9V5l-7 7 7 7v-4.1c5 0 8.5 1.6 11 5.1-1-5-4-10-11-11z"></path><path d="M0 0h24v24H0z" fill="none"></path></svg> <span>Reply</span></div><div class="wpd-space"></div></div></div></div><div id="wpdiscuz_form_anchor-62173_62044"></div></div></div><div id="wpd-comm-61417_0" class="comment even thread-odd thread-alt depth-1 wpd-comment wpd_comment_level-1"><div class="wpd-comment-wrap wpd-blog-guest"> <div class="wpd-comment-left"> <div class="wpd-avatar"> <img alt="suka hyunwook" src="https://secure.gravatar.com/avatar/839717eb4a0b4b36828451910bdca9f0?s=64&amp;d=mm&amp;r=r" srcset="https://secure.gravatar.com/avatar/839717eb4a0b4b36828451910bdca9f0?s=128&amp;d=mm&amp;r=r 2x" class="avatar avatar-64 photo" height="64" width="64" loading="lazy" decoding="async"></div></div><div id="comment-61417" class="wpd-comment-right"> <div class="wpd-comment-header"> <div class="wpd-comment-author"> suka hyunwook</div><div class="wpd-space"></div><div class="wpd-comment-link wpd-hidden"> <span wpd-tooltip="Comment Link" wpd-tooltip-position="left"><i class="fas fa-link" aria-hidden="true" data-wpd-clipboard="https://tv7.idlix.asia/tvseries/study-group-2025/#comment-61417"></i></span></div></div><div class="wpd-comment-subheader"> <div class="wpd-comment-label" wpd-tooltip="Guest" wpd-tooltip-position="top"> <span>Guest</span></div><div class="wpd-comment-date" title="26 January, 2025 11:05 am"> <i class="far fa-clock" aria-hidden="true"></i> 26 January, 2025 11:05 am</div></div><div class="wpd-comment-text"> <p>belom aplot kah</p></div><div class="wpd-comment-footer"> <div class="wpd-vote"> <div class="wpd-vote-up wpd_not_clicked"> <svg xmlns="https://www.w3.org/2000/svg" viewBox="0 0 24 24"><path fill="none" d="M0 0h24v24H0V0z"></path><path d="M1 21h4V9H1v12zm22-11c0-1.1-.9-2-2-2h-6.31l.95-4.57.03-.32c0-.41-.17-.79-.44-1.06L14.17 1 7.59 7.59C7.22 7.95 7 8.45 7 9v10c0 1.1.9 2 2 2h9c.83 0 1.54-.5 1.84-1.22l3.02-7.05c.09-.23.14-.47.14-.73v-2z"></path></svg></div><div class="wpd-vote-result wpd-up" title="3">3</div><div class="wpd-vote-down wpd_not_clicked"> <svg xmlns="https://www.w3.org/2000/svg" viewBox="0 0 24 24"><path fill="none" d="M0 0h24v24H0z"></path><path d="M15 3H6c-.83 0-1.54.5-1.84 1.22l-3.02 7.05c-.09.23-.14.47-.14.73v2c0 1.1.9 2 2 2h6.31l-.95 4.57-.03.32c0 .41.17.79.44 1.06L9.83 23l6.59-6.59c.36-.36.58-.86.58-1.41V5c0-1.1-.9-2-2-2zm4 0v12h4V3h-4z"></path></svg></div></div><div class="wpd-reply-button"> 
   <svg xmlns="https://www.w3.org/2000/svg" viewBox="0 0 24 24">
   <path d="M10 9V5l-7 7 7 7v-4.1c5 0 8.5 1.6 11 5.1-1-5-4-10-11-11z"></path>
   <path d="M0 0h24v24H0z" fill="none"></path></svg> 
   <span>Reply</span></div>
   <div class="wpd-wpanel"></div><div class="wpd-space"></div></div></div></div>
   <div id="wpdiscuz_form_anchor-61417_0"></div></div>
   <div id="wpd-comm-61585_0" class="comment odd alt thread-even depth-1 wpd-comment wpd_comment_level-1">
   <div class="wpd-comment-wrap wpd-blog-guest"> <div class="wpd-comment-left"> 
   <div class="wpd-avatar"> 
   <img alt="hahahan" src="https://secure.gravatar.com/avatar/d4e8835961f8177bf08895edd912f559?s=64&amp;d=mm&amp;r=r" srcset="https://secure.gravatar.com/avatar/d4e8835961f8177bf08895edd912f559?s=128&amp;d=mm&amp;r=r 2x" class="avatar avatar-64 photo" height="64" width="64" loading="lazy" decoding="async"></div></div>
   <div id="comment-61585" class="wpd-comment-right"> <div class="wpd-comment-header"> <div class="wpd-comment-author"> hahahan</div>
   <span wpd-tooltip="Comment Link" wpd-tooltip-position="left">
    <i class="fas fa-link" aria-hidden="true" data-wpd-clipboard="https://tv7.idlix.asia/tvseries/study-group-2025/#comment-62708"></i></span></div></div>
    <div class="wpd-comment-subheader"> 
   <div class="wpd-comment-label" wpd-tooltip="Guest" wpd-tooltip-position="top"> <span>Guest</span></div>
   <div class="wpd-comment-date" title="4 February, 2025 8:18 pm">
    <i class="far fa-clock" aria-hidden="true"></i> 4 February, 2025 8:18 pm</div></div>
   <div class="wpd-comment-text"> 
   <p>Kenapa pada bilang Minhyun mirip Cha eun-woo, bukannya lebih mirip Park Seo-joon ya?</p></div>
   <div class="wpd-comment-footer"> 
    <div class="wpd-vote"> 
        <div class="wpd-vote-up wpd_not_clicked"> 
   <svg xmlns="https://www.w3.org/2000/svg" viewBox="0 0 24 24">
   <path fill="none" d="M0 0h24v24H0V0z"></path>
   <path d="M1 21h4V9H1v12zm22-11c0-1.1-.9-2-2-2h-6.31l.95-4.57.03-.32c0-.41-.17-.79-.44-1.06L14.17 1 7.59 7.59C7.22 7.95 7 8.45 7 9v10c0 1.1.9 2 2 2h9c.83 0 1.54-.5 1.84-1.22l3.02-7.05c.09-.23.14-.47.14-.73v-2z"></path></svg></div>
   <div class="wpd-vote-result" title="0">0</div>
   <div class="wpd-vote-down wpd_not_clicked"> 
   <svg xmlns="https://www.w3.org/2000/svg" viewBox="0 0 24 24">
   <path fill="none" d="M0 0h24v24H0z"></path>
   <path d="M15 3H6c-.83 0-1.54.5-1.84 1.22l-3.02 7.05c-.09.23-.14.47-.14.73v2c0 1.1.9 2 2 2h6.31l-.95 4.57-.03.32c0 .41.17.79.44 1.06L9.83 23l6.59-6.59c.36-.36.58-.86.58-1.41V5c0-1.1-.9-2-2-2zm4 0v12h4V3h-4z">
   </path>
</svg>
</div>
</div>
   <div class="wpd-reply-button"> 
   <svg xmlns="https://www.w3.org/2000/svg" viewBox="0 0 24 24">
   <path d="M10 9V5l-7 7 7 7v-4.1c5 0 8.5 1.6 11 5.1-1-5-4-10-11-11z"></path><path d="M0 0h24v24H0z" fill="none"></path></svg> <span>Reply</span></div><div class="wpd-wpanel"></div><div class="wpd-space"></div></div></div></div><div id="wpdiscuz_form_anchor-62708_0"></div></div><div id="wpd-comm-61953_0" class="comment even thread-even depth-1 wpd-comment wpd_comment_level-1"><div class="wpd-comment-wrap wpd-blog-guest"> <div class="wpd-comment-left"> <div class="wpd-avatar"> <img alt="Stranger from hell" src="https://secure.gravatar.com/avatar/aaa656cdec1538530293c94954a9603b?s=64&amp;d=mm&amp;r=r" srcset="https://secure.gravatar.com/avatar/aaa656cdec1538530293c94954a9603b?s=128&amp;d=mm&amp;r=r 2x" class="avatar avatar-64 photo" height="64" width="64" loading="lazy" decoding="async"></div></div><div id="comment-61953" class="wpd-comment-right"> <div class="wpd-comment-header"> <div class="wpd-comment-author"> Stranger from hell</div><div class="wpd-space"></div><div class="wpd-comment-link wpd-hidden"> <span wpd-tooltip="Comment Link" wpd-tooltip-position="left"><i class="fas fa-link" aria-hidden="true" data-wpd-clipboard="https://tv7.idlix.asia/tvseries/study-group-2025/#comment-61953"></i></span></div></div><div class="wpd-comment-subheader"> <div class="wpd-comment-label" wpd-tooltip="Guest" wpd-tooltip-position="top"> <span>Guest</span></div><div class="wpd-comment-date" title="30 January, 2025 12:35 am"> <i class="far fa-clock" aria-hidden="true"></i> 30 January, 2025 12:35 am</div></div><div class="wpd-comment-text"> <p>MAJU LO SEMUAAA HAHA</p></div><div class="wpd-comment-footer"> <div class="wpd-vote"> <div class="wpd-vote-up wpd_not_clicked"> <svg xmlns="https://www.w3.org/2000/svg" viewBox="0 0 24 24"><path fill="none" d="M0 0h24v24H0V0z"></path><path d="M1 21h4V9H1v12zm22-11c0-1.1-.9-2-2-2h-6.31l.95-4.57.03-.32c0-.41-.17-.79-.44-1.06L14.17 1 7.59 7.59C7.22 7.95 7 8.45 7 9v10c0 1.1.9 2 2 2h9c.83 0 1.54-.5 1.84-1.22l3.02-7.05c.09-.23.14-.47.14-.73v-2z"></path></svg></div><div class="wpd-vote-result wpd-down" title="-4">-4</div><div class="wpd-vote-down wpd_not_clicked"> <svg xmlns="https://www.w3.org/2000/svg" viewBox="0 0 24 24"><path fill="none" d="M0 0h24v24H0z"></path><path d="M15 3H6c-.83 0-1.54.5-1.84 1.22l-3.02 7.05c-.09.23-.14.47-.14.73v2c0 1.1.9 2 2 2h6.31l-.95 4.57-.03.32c0 .41.17.79.44 1.06L9.83 23l6.59-6.59c.36-.36.58-.86.58-1.41V5c0-1.1-.9-2-2-2zm4 0v12h4V3h-4z"></path></svg></div></div><div class="wpd-reply-button"> <svg xmlns="https://www.w3.org/2000/svg" viewBox="0 0 24 24"><path d="M10 9V5l-7 7 7 7v-4.1c5 0 8.5 1.6 11 5.1-1-5-4-10-11-11z"></path><path d="M0 0h24v24H0z" fill="none"></path></svg> <span>Reply</span></div><div class="wpd-wpanel"></div><div class="wpd-space"></div></div></div></div><div id="wpdiscuz_form_anchor-61953_0"></div></div><div class="wpdiscuz-comment-pagination"></div></div></div></div> -->


<section class="mt-4">
    <h2>Komentar</h2>
    <!-- Form Komentar (Hanya untuk user selain pemilik kebun) -->
    <?php if (session()->get('id_user') && session()->get('id_user') != $kebun['id_user']): ?>
    <div class="card mb-4">
        <div class="card-body">
            <form action="/kebun/komentar" method="post">
                <?= csrf_field() ?>
                <input type="hidden" name="id_kebun" value="<?= $kebun['id_kebun'] ?>">
                <div class="form-group">
                    <textarea name="komentar" class="form-control" rows="3" placeholder="Tulis komentar..."></textarea>
                </div>
                <button type="submit" class="btn btn-primary">Kirim Komentar</button>
            </form>
        </div>
    </div>
    <?php endif; ?>
    <center><hr width="50%"></center>

    <!-- Menampilkan Komentar -->
    <?php if (empty($komentar)): ?>
    <p class="text-muted">Belum ada komentar.</p>
    <?php else: ?>
        <?php foreach ($komentar as $comment): ?>
            <?php if ($comment['induk_komentar_id'] == NULL): ?>
            <div class="card mb-3">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <span class="font-weight-bold"><?= htmlspecialchars($comment['nama_users']) ?></span>
                        <span class="text-muted"><?= $comment['created_at'] ?></span>
                    </div>
                    <p class="card-text"><?= htmlspecialchars($comment['komentar']) ?></p>

                    <!-- Tombol Balas -->
                    <?php if (session()->get('id_user')): ?>
                    <button class="btn btn-sm btn-outline-primary reply-btn" data-parent-id="<?= $comment['id_komentar'] ?>">Balas</button>

                    <!-- Form Balas Tersembunyi -->
                    <form action="/kebun/komentar" method="post" class="mt-3 reply-form" style="display: none;">
                        <?= csrf_field() ?>
                        <input type="hidden" name="id_kebun" value="<?= $kebun['id_kebun'] ?>">
                        <input type="hidden" name="induk_komentar_id" value="<?= $comment['id_komentar'] ?>">
                        <div class="form-group">
                            <textarea name="komentar" class="form-control" rows="2" placeholder="Tulis balasan..."></textarea>
                        </div>
                        <button type="submit" class="btn btn-primary btn-sm">Kirim Balasan</button>
                    </form>
                    <?php endif; ?>

                    <!-- Balasan Komentar -->
                    <?php foreach ($komentar as $reply): ?>
                        <?php if ($reply['induk_komentar_id'] == $comment['id_komentar']): ?>
                        <div class="card mt-3 ml-5" style="border-left: 3px solid #007bff;"> <!-- Card kecil dengan border kiri -->
                            <div class="card-body p-3"> <!-- Padding lebih kecil -->
                                <div class="d-flex justify-content-between align-items-center mb-2">
                                    <span class="font-weight-bold"><?= htmlspecialchars($reply['nama_users']) ?></span>
                                    <span class="text-muted"><?= $reply['created_at'] ?></span>
                                </div>
                                <p class="card-text mb-0"><?= htmlspecialchars($reply['komentar']) ?></p> <!-- Margin bottom 0 -->
                            </div>
                        </div>
                        <?php endif; ?>
                    <?php endforeach; ?>
                </div>
            </div>
            <?php endif; ?>
        <?php endforeach; ?>
    <?php endif; ?>
</section>
<script>
// Tampilkan/hilangkan form balasan
const replyButtons = document.querySelectorAll('.reply-btn');
replyButtons.forEach(button => {
    button.addEventListener('click', () => {
        const form = button.nextElementSibling;
        if (form.style.display === 'none' || !form.style.display) {
            form.style.display = 'block';
        } else {
            form.style.display = 'none';
        }
    });
});
</script>






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
    document.getElementById('deleteButton').addEventListener('click', function(event) {
        event.preventDefault(); // Hentikan tindakan default dari tautan
        confirmDelete(<?= $kebun['id_kebun']; ?>);
    });

    function confirmDelete(idKebun) {
        Swal.fire({
            title: 'Apakah Anda yakin?',
            text: "Data ini tidak dapat dikembalikan setelah dihapus!",
            icon: 'warning',
            showCancelButton: true, // Tampilkan tombol Batal
            confirmButtonColor: '#d33', // Warna tombol Hapus
            cancelButtonColor: '#3085d6', // Warna tombol Batal
            confirmButtonText: 'Ya, Hapus!',
            cancelButtonText: 'Batal'
        }).then((result) => {
            // Hanya hapus jika user menekan "Ya, Hapus!"
            if (result.isConfirmed) {
                // Proses penghapusan
                window.location.href = `/kebun/delete/${idKebun}`;
            }
        });
    }
</script>
<?php echo $this->endSection() ?>