<!DOCTYPE html>
<html lang="id">

<head>
    <title>Data Kuliner - Premium</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css" />

    <style>
        body {
            background-color: #F4F7FE;
            font-family: 'Poppins', sans-serif;
            color: #2B3674;
            overflow-x: hidden;
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
            transition: 0.3s;
            font-weight: 500;
        }

        .sidebar .nav-link:hover,
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
        }

        .form-control,
        .form-select {
            border-radius: 12px;
            padding: 12px 18px;
            border: 1px solid #E0E5F2;
            background: #F4F7FE;
        }

        .btn-primary-custom {
            background-color: #3A57E8;
            color: white;
            border: none;
            border-radius: 12px;
            padding: 12px 20px;
            font-weight: 600;
            transition: all 0.3s ease;
        }
        .btn-primary-custom:hover {
            background-color: #2B44C4;
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(58, 87, 232, 0.35);
        }

        /* --- DESAIN GRID CARD SIMETRIS MUTLAK --- */
        .data-card {
            background: #ffffff;
            border: 1px solid #E0E5F2;
            border-radius: 16px;
            transition: all 0.3s ease;
            overflow: hidden; 
        }

        .data-card:hover {
            box-shadow: 0 15px 35px rgba(58, 87, 232, 0.1);
            border-color: #3A57E8;
            transform: translateY(-5px); 
        }

        .card-img-custom, .card-no-pic {
            height: 180px;
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

        /* Tombol Aksi */
        .btn-action {
            border-radius: 8px;
            padding: 6px 12px;
            font-size: 0.75rem;
            font-weight: 600;
            text-decoration: none;
            transition: all 0.2s ease;
            display: inline-flex;
            align-items: center;
        }

        /* Tombol Selengkapnya (Pengganti Lencana Status) */
        .btn-selengkapnya {
            background-color: #111C43;
            color: white;
            border: none;
            border-radius: 8px;
            padding: 6px 14px;
            font-size: 0.8rem;
            font-weight: 600;
            text-decoration: none;
            transition: all 0.3s ease;
            display: inline-flex;
            align-items: center;
        }
        .btn-selengkapnya:hover {
            background-color: #3A57E8;
            color: white;
            transform: translateY(-2px);
            box-shadow: 0 4px 10px rgba(58, 87, 232, 0.2);
        }

        .btn-fav { color: #707EAE; background-color: #F4F7FE; border: 1px solid transparent; }
        .btn-fav:hover { background-color: #FEECEE; color: #D81B60; }

        .btn-edit { background-color: #E9EDF7; color: #3A57E8; }
        .btn-edit:hover { background-color: #3A57E8; color: white; }
        
        .btn-delete { background-color: #FEECEE; color: #EE5D50; }
        .btn-delete:hover { background-color: #EE5D50; color: white; }

        .btn-logout { background: rgba(255, 255, 255, 0.08); color: white; border-radius: 12px; transition: all 0.3s ease; border: 1px solid transparent; }
        .btn-logout:hover { background: #EE5D50; color: white; transform: translateY(-3px); }

        .star-rating { color: #FFB547; font-size: 0.95rem; }
        
        .kategori-badge { 
            background-color: #F4F7FE; 
            color: #47548C; 
            padding: 4px 8px; 
            border-radius: 6px; 
            font-size: 0.7rem; 
            font-weight: 600; 
        }
    </style>
</head>

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

            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
        <h3 class="fw-bold" style="color: #111C43;">Data Kuliner</h3>
        <p class="text-muted mb-0">Sistem informasi lokasi kuliner</p>
    </div>

    <div class="d-flex align-items-center gap-3 bg-white px-3 py-2 rounded-pill shadow-sm border" style="border-color: #E0E5F2 !important;">
        <div class="text-end d-none d-md-block">
            <p class="mb-0 fw-bold" style="color: #2B3674; font-size: 0.85rem; line-height: 1.2;">
                <?= session()->get('nama') ?? 'Pengguna'; ?>
            </p>
            <small class="text-muted fw-medium" style="font-size: 0.7rem;">
                <?= ucfirst(session()->get('role') ?? 'User'); ?>
            </small>
        </div>
        
        <div class="d-flex align-items-center justify-content-center rounded-circle text-white shadow-sm" 
             style="width: 40px; height: 40px; background-color: #3A57E8; font-weight: 600; font-size: 1.1rem;">
             <?= strtoupper(substr(session()->get('nama') ?? 'U', 0, 1)); ?>
        </div>
    </div>
            </div>

            <?php if (session()->getFlashdata('success')): ?>
                <div class="alert alert-success alert-dismissible fade show" role="alert" style="border-radius: 12px;">
                    <?= session()->getFlashdata('success'); ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            <?php endif; ?>

            <?php if (session()->getFlashdata('error')): ?>
                <div class="alert alert-danger alert-dismissible fade show" role="alert" style="border-radius: 12px;">
                    <?= session()->getFlashdata('error'); ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            <?php endif; ?>

            <div class="card card-custom mb-5">
                <div class="card-body p-4">
                    <h5 class="fw-bold mb-4">Tambah Tempat Kuliner</h5>
                    <form action="/kuliner/save" method="post" enctype="multipart/form-data">
                        <?= csrf_field(); ?>
                        <input type="hidden" name="latitude" id="latitude">
<input type="hidden" name="longitude" id="longitude">
                        <div class="row mb-3">
                            <div class="col-lg-6 mb-3">
                                <label class="form-label">Nama Tempat</label>
                                <input type="text" name="nama_tempat" class="form-control" value="<?= old('nama_tempat'); ?>" required>
                            </div>
                            <div class="col-lg-6 mb-3">
                                <label class="form-label">Kategori</label>
                                <select name="category_id" class="form-select" required>
                                    <option value="">-- Pilih Kategori --</option>
                                    <?php foreach ($categories as $c): ?>
                                        <option value="<?= $c['id']; ?>" <?= (old('category_id') == $c['id']) ? 'selected' : ''; ?>><?= $c['nama_kategori']; ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Alamat</label>

<textarea
    name="alamat"
    id="alamat"
    class="form-control"
    rows="2"
    required><?= old('alamat'); ?></textarea>

<button
    type="button"
    id="btnCariKoordinat"
    class="btn btn-primary mt-2">
    Cari Koordinat
</button>

<div class="mt-2">
    Latitude :
    <span id="showLat">-</span>
    <br>

    Longitude :
    <span id="showLng">-</span>
    
</div>
<div id="map" style="height: 300px; margin-top: 15px; border-radius: 12px;"></div>
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label">Deskripsi</label>
                            <textarea name="deskripsi" class="form-control" rows="3"><?= old('deskripsi'); ?></textarea>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Review</label>
                            <textarea name="review" class="form-control" rows="2" required><?= old('review'); ?></textarea>
                        </div>
                        <div class="row mb-4">
                            <div class="col-lg-8 mb-3">
                                <label class="form-label">Upload Foto Tempat</label>
                                <input type="file" name="foto" class="form-control" accept="image/png, image/jpeg, image/jpg">
                                <small class="text-muted d-block mt-1">Opsional. Maksimal 2MB (JPG/PNG).</small>
                            </div>
                            <div class="col-lg-4 mb-3">
                                <label class="form-label">Rating</label>
                                <select name="rating" class="form-select" required>
                                    <option value="5" <?= (old('rating') == 5) ? 'selected' : ''; ?>>⭐⭐⭐⭐⭐</option>
                                    <option value="4" <?= (old('rating') == 4) ? 'selected' : ''; ?>>⭐⭐⭐⭐</option>
                                    <option value="3" <?= (old('rating') == 3) ? 'selected' : ''; ?>>⭐⭐⭐</option>
                                    <option value="2" <?= (old('rating') == 2) ? 'selected' : ''; ?>>⭐⭐</option>
                                    <option value="1" <?= (old('rating') == 1) ? 'selected' : ''; ?>>⭐</option>
                                </select>
                            </div>
                        </div>
                        <button class="btn-primary-custom w-100">Simpan Data</button>
                    </form>
                </div>
            </div>

            <div class="mb-5">
                <h5 class="fw-bold mb-4 px-2">Daftar Kuliner</h5>

                <?php if (empty($kuliner)): ?>
                    <div class="text-center py-5">
                        <p class="text-muted">Belum ada data kuliner.</p>
                    </div>
                <?php else: ?>
                    
                    <div class="row g-4">
                        <?php foreach ($kuliner as $k): ?>
                            
                            <div class="col-xl-4 col-lg-6 col-md-12">
                                
                                <div class="data-card h-100 d-flex flex-column p-0">
                                    
                                    <?php if (!empty($k['foto']) && $k['foto'] != 'default.png'): ?>
                                        <img src="<?= base_url('uploads/kuliner/' . $k['foto']); ?>" class="card-img-custom" alt="Foto">
                                    <?php else: ?>
                                        <div class="card-no-pic">
                                            <small class="text-muted fw-bold">No Pic</small>
                                        </div>
                                    <?php endif; ?>

                                    <div class="p-4 flex-grow-1 d-flex flex-column">
                                        
                                        <div class="d-flex justify-content-between align-items-start gap-2 mb-2">
                                            <h5 class="fw-bold title-locked m-0" title="<?= $k['nama_tempat']; ?>">
                                                <?= $k['nama_tempat']; ?>
                                            </h5>
                                        </div>
                                        
                                        <div class="mb-3">
                                            <span class="kategori-badge"><?= $k['nama_kategori']; ?></span>
                                        </div>

                                        <p class="text-limit-1 text-muted mb-0" style="font-size: 0.8rem;">
                                            📍 <?= $k['alamat']; ?>
                                        </p>

                                        <div class="star-rating mt-auto pt-3">
                                            <?= str_repeat('⭐', $k['rating']); ?>
                                        </div>

                                    </div>

                                    <div class="p-3 border-top d-flex justify-content-between align-items-center" style="background-color: #F8FAFC;">
                                        
                                        <a href="/kuliner/detail/<?= $k['id']; ?>" class="btn-selengkapnya">
                                            Selengkapnya
                                        </a>

                                        <div class="d-flex gap-1 flex-wrap justify-content-end">
                                            <a href="/kuliner/favorite/<?= $k['id']; ?>" class="btn-action btn-fav" title="Favorit">♡</a>
                                            
                                            <?php if (session()->get('role') == 'admin' || $k['user_id'] == session()->get('user_id')): ?>
                                                <a href="/kuliner/edit/<?= $k['id']; ?>" class="btn-action btn-edit">Edit</a>
                                                <a href="/kuliner/delete/<?= $k['id']; ?>" class="btn-action btn-delete" onclick="return confirm('Hapus?')">Hapus</a>
                                            <?php endif; ?>
                                        </div>

                                    </div>

                                </div>
                            </div>

                        <?php endforeach; ?>
                    </div>

                <?php endif; ?>
            </div>

        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>

<script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>

<script>
document.getElementById("btnCariKoordinat").addEventListener("click", function () {

    let alamat = document.getElementById("alamat").value;

    if (!alamat) {
        alert("Alamat masih kosong!");
        return;
    }

    fetch("<?= base_url('kuliner/get-coordinate') ?>", {
        method: "POST",
        headers: {
            "Content-Type": "application/x-www-form-urlencoded"
        },
        body: "alamat=" + encodeURIComponent(alamat)
    })
    .then(res => res.json())
    .then(data => {

        if (data.status) {

            // isi hidden input (untuk database)
            document.getElementById("latitude").value = data.lat;
            document.getElementById("longitude").value = data.lng;

            // tampilkan di UI
            document.getElementById("showLat").innerText = data.lat;
            document.getElementById("showLng").innerText = data.lng;

            alert("Koordinat berhasil ditemukan!");

        } else {
            alert("Gagal: " + data.message);
        }

    })
    .catch(err => {
        console.log(err);
        alert("Terjadi error saat mengambil koordinat");
    });

});



</script>

<script>
    // INIT MAP
    var map = L.map('map').setView([-2.5, 118], 5);

    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        maxZoom: 19,
    }).addTo(map);

    var marker;

    document.getElementById("btnCariKoordinat").addEventListener("click", function () {

        let alamat = document.getElementById("alamat").value;

        if (!alamat) {
            alert("Alamat masih kosong!");
            return;
        }

        fetch("https://nominatim.openstreetmap.org/search?format=json&q=" + alamat)
            .then(res => res.json())
            .then(data => {

                if (data.length > 0) {

                    let lat = data[0].lat;
                    let lng = data[0].lon;

                    // set input hidden
                    document.getElementById("latitude").value = lat;
                    document.getElementById("longitude").value = lng;

                    // tampil UI
                    document.getElementById("showLat").innerText = lat;
                    document.getElementById("showLng").innerText = lng;

                    // update map
                    map.setView([lat, lng], 15);

                    if (marker) {
                        map.removeLayer(marker);
                    }

                    marker = L.marker([lat, lng]).addTo(map)
                        .bindPopup("Lokasi ditemukan")
                        .openPopup();

                } else {
                    alert("Koordinat tidak ditemukan");
                }
            });
    });
</script>