<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kuliner App - Temukan Rasa Terbaik</title>
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background-color: #F8FAFC;
        }

        /* Navigasi */
        .navbar-custom {
            background-color: #111C43;
            padding: 15px 0;
        }

        /* Hero Section (Banner Utama) */
        .hero-section {
            background: linear-gradient(rgba(17, 28, 67, 0.85), rgba(17, 28, 67, 0.85)), 
                        url('<?= base_url('assets/gambar1.jpeg'); ?>'); 
            background-size: cover;
            background-position: center;
            padding: 100px 0;
            color: white;
            border-bottom-left-radius: 50px;
            border-bottom-right-radius: 50px;
            margin-bottom: -50px;
        }

        /* --- DESAIN GRID CARD SIMETRIS (SAMA DENGAN DASHBOARD) --- */
        .kuliner-card {
            background: #ffffff;
            border: 1px solid #E0E5F2;
            border-radius: 16px;
            transition: all 0.3s ease;
            overflow: hidden; 
            box-shadow: 0 10px 30px rgba(0,0,0,0.05);
        }

        .kuliner-card:hover {
            box-shadow: 0 15px 35px rgba(58, 87, 232, 0.15);
            border-color: #3A57E8;
            transform: translateY(-5px); 
        }

        .card-img-custom, .card-no-pic {
            height: 200px; /* Sedikit lebih tinggi untuk etalase Home */
            width: 100%;
            border-bottom: 1px solid #E0E5F2;
        }

        .card-img-custom { object-fit: cover; }
        .card-no-pic {
            background-color: #E9EDF7;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        /* Kunci Tinggi Judul agar 1 atau 2 baris tetap simetris */
        .title-locked {
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
            font-size: 1.1rem;
            line-height: 1.4;
            min-height: 3rem; 
            color: #111C43;
        }

        .text-limit-1 {
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .text-limit-2 {
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
            font-size: 0.85rem;
            color: #707EAE;
            line-height: 1.6;
        }

        .badge-kategori {
            background-color: #F4F7FE;
            color: #47548C;
            font-weight: 600;
            padding: 6px 12px;
            border-radius: 8px;
            font-size: 0.75rem;
            border: 1px solid #E0E5F2;
        }

        .star-rating {
            color: #FFB547;
            font-size: 0.95rem;
        }

        /* Tombol Modern */
        .btn-primary-custom {
            background-color: #3A57E8;
            color: white;
            border-radius: 10px;
            padding: 10px 24px;
            font-weight: 600;
            border: none;
            transition: 0.3s;
        }

        .btn-primary-custom:hover {
            background-color: #2B44C4;
            color: white;
        }

        /* Tombol Selengkapnya */
        .btn-selengkapnya {
            background-color: #111C43;
            color: white;
            border: none;
            border-radius: 8px;
            padding: 8px 16px;
            font-size: 0.85rem;
            font-weight: 600;
            text-decoration: none;
            transition: all 0.3s ease;
            display: inline-flex;
            align-items: center;
            width: 100%;
            justify-content: center;
        }
        .btn-selengkapnya:hover {
            background-color: #3A57E8;
            color: white;
            transform: translateY(-2px);
            box-shadow: 0 4px 10px rgba(58, 87, 232, 0.2);
        }
    </style>
</head>

<body>

    <nav class="navbar navbar-expand-lg navbar-dark navbar-custom shadow-sm sticky-top">
        <div class="container">
            <a class="navbar-brand fw-bold d-flex align-items-center gap-2" href="/">
                <img src="<?= base_url('assets/chef.png'); ?>" alt="Logo" height="40">
                KulinerZone
            </a>
            
            <div class="ms-auto">
                <?php if(session()->get('login')): ?>
                    <a href="/kuliner" class="btn btn-primary-custom">Masuk Dashboard</a>
                <?php else: ?>
                    <a href="/login" class="btn btn-outline-light border-0 me-2 fw-medium">Login</a>
                    <a href="/register" class="btn btn-primary-custom">Daftar Akun</a>
                <?php endif; ?>
            </div>
        </div>
    </nav>

    <div class="hero-section text-center">
        <div class="container">
            <h1 class="fw-bold display-4 mb-3">Temukan Rasa Terbaik di Sekitarmu</h1>
            <p class="fs-5 text-light opacity-75 mx-auto mb-4" style="max-width: 600px;">
                Platform komunitas untuk mencari, mereview, dan membagikan rekomendasi tempat kuliner hidden gem pilihan mahasiswa.
            </p>
            <?php if(!session()->get('login')): ?>
                <a href="/register" class="btn btn-warning fw-bold px-4 py-2 rounded-pill shadow" style="color: #111C43;">Mulai Bagikan Review</a>
            <?php endif; ?>
        </div>
    </div>

    <div class="container" style="margin-top: 80px; margin-bottom: 100px;">
        
        <div class="d-flex justify-content-between align-items-end mb-4">
            <div>
                <h3 class="fw-bold" style="color: #111C43;">Ulasan Terbaru</h3>
                <p class="text-muted mb-0">Rekomendasi tempat makan terhangat dari komunitas.</p>
            </div>
        </div>

        <div class="row g-4">
            <?php if(empty($kuliner)): ?>
                <div class="col-12 text-center py-5">
                    <img src="https://cdn-icons-png.flaticon.com/512/3075/3075977.png" width="120" class="mb-3 opacity-50">
                    <h5 class="text-muted">Belum ada ulasan yang dipublikasikan.</h5>
                </div>
            <?php else: ?>
                
                <?php foreach($kuliner as $k): ?>
                    <div class="col-md-6 col-lg-4">
                        
                        <div class="kuliner-card h-100 d-flex flex-column p-0">
                            
                            <?php if (!empty($k['foto']) && $k['foto'] != 'default.png'): ?>
                                <img src="<?= base_url('uploads/kuliner/' . $k['foto']); ?>" class="card-img-custom" alt="Foto Tempat">
                            <?php else: ?>
                                <div class="card-no-pic">
                                    <img src="<?= base_url('assets/chef.png'); ?>" alt="Default Kuliner" style="height: 100px; object-fit: contain;">
                                </div>
                            <?php endif; ?>

                            <div class="p-4 flex-grow-1 d-flex flex-column">
                                
                                <div class="d-flex justify-content-between align-items-start gap-2 mb-3">
                                    <h5 class="fw-bold title-locked m-0" title="<?= $k['nama_tempat']; ?>">
                                        <?= $k['nama_tempat']; ?>
                                    </h5>
                                </div>
                                
                                <div class="mb-3">
                                    <span class="badge-kategori"><?= $k['nama_kategori']; ?></span>
                                </div>

                                <p class="text-limit-1 text-muted mb-3" style="font-size: 0.85rem;">
                                    📍 <?= $k['alamat']; ?>
                                </p>

                                <div class="text-limit-2 mb-3" style="font-style: italic;">
                                    "<?= $k['review']; ?>"
                                </div>

                                <div class="star-rating mt-auto pt-2">
                                    <?= str_repeat('⭐', $k['rating']); ?>
                                </div>

                            </div>

                            <div class="p-3 border-top mt-auto bg-light">
                                <a href="/kuliner/detail/<?= $k['id']; ?>" class="btn-selengkapnya">
                                    Baca Selengkapnya ➔
                                </a>
                            </div>

                        </div>
                    </div>
                <?php endforeach; ?>

            <?php endif; ?>
        </div>

    </div>

    <footer class="bg-white text-center py-4 mt-auto border-top">
        <p class="text-muted mb-0 fw-medium">© <?= date('Y'); ?> Kuliner App. Dibangun untuk Tugas Mahasiswa.</p>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>