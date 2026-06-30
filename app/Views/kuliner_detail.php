<!DOCTYPE html>
<html lang="id">

<head>
    <title>Detail Kuliner - Premium</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" 
    href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"/>

    <style>
        body {
            background-color: #F4F7FE;
            font-family: 'Poppins', sans-serif;
            color: #2B3674;
        }

        .sidebar {
            width: 280px;
            background-color: #111C43;
            color: white;
            border-radius: 20px;
            margin: 20px;
            box-shadow: 0 10px 30px rgba(17, 28, 67, 0.15);
        }

        .sidebar .nav-link {
            color: #A3AED0;
            border-radius: 12px;
            padding: 12px 18px;
            margin-bottom: 8px;
            font-weight: 500;
            text-decoration: none;
            display: block;
        }

        .sidebar .nav-link.active {
            background-color: #3A57E8;
            color: white;
        }

        .main-content {
            height: 100vh;
            overflow-y: auto;
            padding: 30px 40px !important;
        }

        .card-custom {
            background: white;
            border: none;
            border-radius: 20px;
            box-shadow: 0px 10px 40px rgba(112, 144, 176, 0.08);
            overflow: hidden;
        }

        .detail-img {
            width: 100%;
            height: 350px;
            object-fit: cover;
        }

        .detail-no-pic {
            width: 100%;
            height: 350px;
            background-color: #E9EDF7;
            display: flex;
            align-items: center;
            justify-content: center;
            border-bottom: 1px solid #E0E5F2;
        }

        .btn-back {
            background-color: #E9EDF7;
            color: #3A57E8;
            border-radius: 10px;
            padding: 10px 20px;
            font-weight: 600;
            text-decoration: none;
            transition: 0.2s;
            display: inline-flex;
            align-items: center;
            gap: 8px;
        }

        .btn-back:hover {
            background-color: #3A57E8;
            color: white;
        }

        .star-rating { color: #FFB547; font-size: 1.2rem; }
        .kategori-badge { background-color: #F4F7FE; color: #47548C; padding: 6px 14px; border-radius: 8px; font-size: 0.8rem; font-weight: 600; border: 1px solid #E0E5F2; }
        .info-box { background-color: #F8FAFC; border-radius: 12px; padding: 15px; border: 1px solid #E0E5F2; }
        .btn-logout { background: rgba(255, 255, 255, 0.08); color: white; border-radius: 12px; transition: all 0.3s ease; border: 1px solid transparent; }
        .btn-logout:hover { background: #EE5D50; color: white; transform: translateY(-3px); }
    </style>
</head>
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<body>

    <div class="d-flex vh-100">

        <div class="sidebar d-none d-md-flex flex-column p-4 flex-shrink-0">
            <div class="text-center mb-5 mt-3">
                <img src="<?= base_url('assets/chef.png'); ?>" style="height:100px;" class="mb-2" alt="Logo">
                <h4 class="fw-bold text-white">Kuliner</h4>
            </div>
            <ul class="nav flex-column mb-auto">
                <li class="nav-item">
                    <a href="/kuliner" class="nav-link active">Data Tempat</a>

                    <li class="nav-item">
                    <a href="/kuliner/my-favorites" class="nav-link <?= url_is('kuliner/my-favorites') ? 'active' : '' ?>">
                        Favorit
                    </a>
                </li>
                </li>
            </ul>
            <div class="mt-auto">
                <a href="/logout" class="btn btn-logout w-100 py-2 fw-bold text-center">Logout</a>
            </div>
        </div>

        <div class="main-content flex-grow-1">

            <div class="mb-4">
                <a href="/kuliner" class="btn-back">
                    ⬅ Kembali ke Daftar
                </a>
            </div>

            <div class="card card-custom mb-5">
                
                <?php if (!empty($k['foto']) && $k['foto'] != 'default.png'): ?>
                    <img src="<?= base_url('uploads/kuliner/' . $k['foto']); ?>" class="detail-img" alt="Foto">
                <?php else: ?>
                    <div class="detail-no-pic">
                        <h5 class="text-muted fw-bold">Tidak Ada Foto Tempat</h5>
                    </div>
                <?php endif; ?>

                <div class="card-body p-5">
                    
                    <div class="d-flex justify-content-between align-items-center flex-wrap gap-3 mb-4">
                        <div>
                            <h2 class="fw-bold mb-2" style="color: #111C43;"><?= $k['nama_tempat']; ?></h2>
                            <span class="kategori-badge"><?= $k['nama_kategori']; ?></span>
                        </div>
                        <div class="text-lg-end">
                            <div class="star-rating mb-1">
                                <?= str_repeat('⭐', $k['rating']); ?>
                            </div>
                            <small class="text-muted">Rating dari Pengguna</small>
                        </div>
                    </div>

                    <hr class="my-4" style="color: #E0E5F2;">

                    <div class="mb-4">
                        <h5 class="fw-bold mb-2" style="color: #111C43;">📍 Alamat</h5>
                        <p class="fs-6 text-secondary"><?= $k['alamat']; ?></p>
                    </div>

                    <div class="mt-3">
    <h6 class="fw-bold">Lokasi</h6>

    <div id="map" style="height: 300px; border-radius: 12px;"></div>
</div>

                    <div class="mb-4">
                        <h5 class="fw-bold mb-2" style="color: #111C43;">📖 Deskripsi</h5>
                        <p class="text-secondary" style="line-height: 1.7; white-space: pre-line;">
                            <?= !empty($k['deskripsi']) ? $k['deskripsi'] : 'Belum ada deskripsi fasilitas untuk tempat ini.'; ?>
                        </p>
                    </div>

                    <div class="p-4 border-start border-4 border-primary bg-light rounded-end">
                        <h5 class="fw-bold mb-2" style="color: #111C43;">💬 Review Pengguna</h5>
                        <p class="text-secondary mb-0" style="font-style: italic; line-height: 1.7; white-space: pre-line;">
                            "<?= $k['review']; ?>"
                        </p>
                    </div>

                </div>
            </div>

        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>

<script>
    const lat = <?= $k['latitude'] ?? 0 ?>;
    const lng = <?= $k['longitude'] ?? 0 ?>;

    if (lat && lng) {

        const map = L.map('map').setView([lat, lng], 15);

        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '&copy; OpenStreetMap contributors'
        }).addTo(map);

        L.marker([lat, lng])
            .addTo(map)
            .bindPopup("<?= $k['nama_tempat'] ?>")
            .openPopup();

    } else {

        document.getElementById('map').innerHTML =
            "<p class='text-muted'>Lokasi tidak tersedia</p>";

    }
</script>