<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lokasi Kuliner</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css">
    <style>
        body { background: #f5f7fb; color: #1f2a44; }
        .navbar { background: #111c43; }
        .card { border: 0; border-radius: 8px; box-shadow: 0 10px 28px rgba(17, 28, 67, .08); }
        .place-img { width: 100%; height: 190px; object-fit: cover; background: #e8edf6; }
        .badge-soft { background: #eef2ff; color: #3447a7; }
        #map { height: 280px; border-radius: 8px; }
    </style>
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-dark">
    <div class="container">
        <a class="navbar-brand fw-bold" href="/">KulinerZone</a>
        <div class="navbar-nav ms-auto">
            <a class="nav-link" href="/browse">Browse</a>
            <?php if (session()->get('login')): ?>
                <?php if (session()->get('role') === 'admin'): ?>
                    <a class="nav-link" href="/admin/dashboard">Admin</a>
                <?php endif; ?>
                <a class="nav-link" href="/kuliner/my-favorites">Favorit</a>
                <a class="nav-link" href="/logout">Logout</a>
            <?php else: ?>
                <a class="nav-link" href="/login">Login</a>
            <?php endif; ?>
        </div>
    </div>
</nav>

<main class="container py-4">
    <?php if (session()->getFlashdata('success')): ?>
        <div class="alert alert-success"><?= session()->getFlashdata('success') ?></div>
    <?php endif; ?>
    <?php if (session()->getFlashdata('error')): ?>
        <div class="alert alert-danger"><?= session()->getFlashdata('error') ?></div>
    <?php endif; ?>

    <div class="d-flex justify-content-between align-items-end flex-wrap gap-3 mb-4">
        <div>
            <h2 class="fw-bold mb-1"><?= $requireLogin ? 'Dashboard Kuliner' : 'Browse Kuliner' ?></h2>
            <p class="text-muted mb-0">Temukan, tambahkan, dan ulas lokasi jajanan favorit.</p>
        </div>
        <?php if (! session()->get('login')): ?>
            <a class="btn btn-primary" href="/login">Login untuk Submit</a>
        <?php endif; ?>
    </div>

    <form class="card p-3 mb-4" method="get" action="<?= $requireLogin ? '/kuliner' : '/browse' ?>">
        <div class="row g-3 align-items-end">
            <div class="col-md-4">
                <label class="form-label">Cari nama atau alamat</label>
                <input class="form-control" name="q" value="<?= esc(service('request')->getGet('q') ?? '') ?>">
            </div>
            <div class="col-md-3">
                <label class="form-label">Kategori</label>
                <select class="form-select" name="category_id">
                    <option value="">Semua kategori</option>
                    <?php foreach ($categories as $category): ?>
                        <option value="<?= $category['id'] ?>" <?= service('request')->getGet('category_id') == $category['id'] ? 'selected' : '' ?>>
                            <?= esc($category['nama_kategori']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="col-md-3">
                <label class="form-label">Rating minimum</label>
                <select class="form-select" name="rating">
                    <option value="">Semua rating</option>
                    <?php for ($i = 5; $i >= 1; $i--): ?>
                        <option value="<?= $i ?>" <?= service('request')->getGet('rating') == $i ? 'selected' : '' ?>><?= $i ?>+</option>
                    <?php endfor; ?>
                </select>
            </div>
            <div class="col-md-2">
                <button class="btn btn-dark w-100">Filter</button>
            </div>
        </div>
    </form>

    <?php if (session()->get('login')): ?>
        <div class="card p-4 mb-4">
            <h5 class="fw-bold mb-3">Submit Tempat Kuliner</h5>
            <form action="/kuliner/save" method="post" enctype="multipart/form-data">
                <?= csrf_field() ?>
                <input type="hidden" name="latitude" id="latitude">
                <input type="hidden" name="longitude" id="longitude">
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label">Nama Tempat</label>
                        <input class="form-control" name="nama_tempat" value="<?= old('nama_tempat') ?>" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Kategori</label>
                        <select class="form-select" name="category_id" required>
                            <option value="">Pilih kategori</option>
                            <?php foreach ($categories as $category): ?>
                                <option value="<?= $category['id'] ?>" <?= old('category_id') == $category['id'] ? 'selected' : '' ?>><?= esc($category['nama_kategori']) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="col-12">
                        <label class="form-label">Alamat</label>
                        <textarea class="form-control" name="alamat" id="alamat" rows="2" required><?= old('alamat') ?></textarea>
                        <button type="button" id="btnCariKoordinat" class="btn btn-outline-primary btn-sm mt-2">Cari Koordinat</button>
                        <span class="small text-muted ms-2">Lat: <span id="showLat">-</span>, Lng: <span id="showLng">-</span></span>
                        <div id="map" class="mt-3"></div>
                    </div>
                    <div class="col-12">
                        <label class="form-label">Deskripsi</label>
                        <textarea class="form-control" name="deskripsi" rows="3"><?= old('deskripsi') ?></textarea>
                    </div>
                    <div class="col-md-8">
                        <label class="form-label">Review Awal</label>
                        <textarea class="form-control" name="review" rows="2"><?= old('review') ?></textarea>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Rating</label>
                        <select class="form-select" name="rating">
                            <?php for ($i = 5; $i >= 1; $i--): ?>
                                <option value="<?= $i ?>" <?= old('rating', 5) == $i ? 'selected' : '' ?>><?= $i ?> bintang</option>
                            <?php endfor; ?>
                        </select>
                    </div>
                    <div class="col-md-8">
                        <label class="form-label">Foto Tempat</label>
                        <input class="form-control" type="file" name="fotos[]" accept="image/png,image/jpeg,image/jpg" multiple>
                        <small class="text-muted">Maksimal 3 foto, tiap file JPG/PNG maksimal 2MB. Sistem membuat resize 800px dan thumbnail.</small>
                    </div>
                    <div class="col-md-4 d-flex align-items-end">
                        <button class="btn btn-primary w-100">Kirim Tempat</button>
                    </div>
                </div>
            </form>
        </div>
    <?php endif; ?>

    <div class="row g-4">
        <?php foreach ($kuliner as $item): ?>
            <div class="col-md-6 col-lg-4">
                <div class="card h-100 overflow-hidden">
                    <?php if (! empty($item['foto']) && $item['foto'] !== 'default.png'): ?>
                        <img class="place-img" src="<?= base_url('uploads/kuliner/thumbs/' . $item['foto']) ?>" onerror="this.src='<?= base_url('uploads/kuliner/' . $item['foto']) ?>'" alt="<?= esc($item['nama_tempat']) ?>">
                    <?php else: ?>
                        <div class="place-img d-flex align-items-center justify-content-center text-muted">Tidak ada foto</div>
                    <?php endif; ?>
                    <div class="card-body d-flex flex-column">
                        <div class="d-flex justify-content-between gap-2 mb-2">
                            <h5 class="fw-bold mb-0"><?= esc($item['nama_tempat']) ?></h5>
                            <span class="badge badge-soft align-self-start"><?= esc($item['nama_kategori']) ?></span>
                        </div>
                        <p class="text-muted small mb-2"><?= esc($item['alamat']) ?></p>
                        <?php $shortDesc = trim((string) ($item['deskripsi'] ?? '')); ?>
                        <p class="mb-3"><?= esc(strlen($shortDesc) > 110 ? substr($shortDesc, 0, 110) . '...' : $shortDesc) ?></p>
                        <div class="mt-auto d-flex justify-content-between align-items-center">
                            <strong><?= (int) $item['rating'] ?>/5</strong>
                            <div class="d-flex gap-2">
                                <a class="btn btn-sm btn-dark" href="<?= session()->get('login') ? '/kuliner/detail/' . $item['id'] : '/detail/' . $item['id'] ?>">Detail</a>
                                <?php if (session()->get('login')): ?>
                                    <a class="btn btn-sm btn-outline-danger" href="/kuliner/favorite/<?= $item['id'] ?>">Favorit</a>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
        <?php if (empty($kuliner)): ?>
            <div class="col-12"><div class="card p-5 text-center text-muted">Belum ada data kuliner.</div></div>
        <?php endif; ?>
    </div>

    <div class="mt-4">
        <?= isset($pager) ? $pager->links() : '' ?>
    </div>
</main>

<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<?php if (session()->get('login')): ?>
<script>
const map = L.map('map').setView([-6.982, 110.409], 13);
L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', { maxZoom: 19, attribution: '&copy; OpenStreetMap' }).addTo(map);
let marker;

document.getElementById('btnCariKoordinat').addEventListener('click', async function () {
    const alamat = document.getElementById('alamat').value.trim();
    if (!alamat) {
        alert('Alamat masih kosong.');
        return;
    }

    const response = await fetch('<?= base_url('kuliner/get-coordinate') ?>', {
        method: 'POST',
        headers: {'Content-Type': 'application/x-www-form-urlencoded'},
        body: 'alamat=' + encodeURIComponent(alamat)
    });
    const data = await response.json();

    if (!data.status) {
        alert(data.message || 'Koordinat tidak ditemukan.');
        return;
    }

    document.getElementById('latitude').value = data.lat;
    document.getElementById('longitude').value = data.lng;
    document.getElementById('showLat').innerText = data.lat;
    document.getElementById('showLng').innerText = data.lng;

    map.setView([data.lat, data.lng], 16);
    if (marker) marker.remove();
    marker = L.marker([data.lat, data.lng]).addTo(map).bindPopup('Lokasi ditemukan').openPopup();
});
</script>
<?php endif; ?>
</body>
</html>
