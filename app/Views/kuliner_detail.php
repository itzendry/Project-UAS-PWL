<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= esc($k['nama_tempat']) ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css">
    <style>
        body { background: #f5f7fb; color: #1f2a44; }
        .navbar { background: #111c43; }
        .card { border: 0; border-radius: 8px; box-shadow: 0 10px 28px rgba(17, 28, 67, .08); }
        .hero-img { width: 100%; max-height: 420px; object-fit: cover; background: #e8edf6; }
        #map { height: 320px; border-radius: 8px; }
    </style>
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-dark">
    <div class="container">
        <a class="navbar-brand fw-bold" href="/">KulinerZone</a>
        <div class="navbar-nav ms-auto">
            <a class="nav-link" href="<?= session()->get('login') ? '/kuliner' : '/browse' ?>">Daftar Kuliner</a>
            <?php if (session()->get('login')): ?>
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

    <div class="card overflow-hidden mb-4">
        <?php if (! empty($k['foto']) && $k['foto'] !== 'default.png'): ?>
            <img class="hero-img" src="<?= base_url('uploads/kuliner/' . $k['foto']) ?>" alt="<?= esc($k['nama_tempat']) ?>">
        <?php else: ?>
            <div class="hero-img d-flex align-items-center justify-content-center text-muted">Tidak ada foto tempat</div>
        <?php endif; ?>
        <div class="card-body p-4">
            <div class="d-flex justify-content-between flex-wrap gap-3 mb-3">
                <div>
                    <h2 class="fw-bold mb-2"><?= esc($k['nama_tempat']) ?></h2>
                    <span class="badge bg-primary-subtle text-primary"><?= esc($k['nama_kategori']) ?></span>
                    <span class="badge bg-light text-dark"><?= esc($k['status']) ?></span>
                </div>
                <div class="text-md-end">
                    <strong class="fs-4"><?= (int) $k['rating'] ?>/5</strong><br>
                    <small class="text-muted">Rating rata-rata</small>
                </div>
            </div>
            <p class="text-muted mb-3"><?= esc($k['alamat']) ?></p>
            <p style="white-space: pre-line"><?= esc($k['deskripsi'] ?: 'Belum ada deskripsi untuk tempat ini.') ?></p>
            <?php if (session()->get('login')): ?>
                <div class="d-flex gap-2 flex-wrap">
                    <a class="btn btn-outline-danger btn-sm" href="/kuliner/favorite/<?= $k['id'] ?>">Simpan Favorit</a>
                    <a class="btn btn-outline-secondary btn-sm" href="/kuliner/mark-closed/<?= $k['id'] ?>">Tandai Tutup Permanen</a>
                    <?php if (session()->get('role') === 'admin' || (int) $k['user_id'] === (int) session()->get('user_id')): ?>
                        <a class="btn btn-outline-primary btn-sm" href="/kuliner/edit/<?= $k['id'] ?>">Edit Tempat</a>
                    <?php endif; ?>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <?php if (! empty($photos)): ?>
        <div class="card p-4 mb-4">
            <h5 class="fw-bold mb-3">Galeri Foto</h5>
            <div class="row g-3">
                <?php foreach ($photos as $photo): ?>
                    <div class="col-md-4">
                        <img class="w-100 rounded" style="height: 180px; object-fit: cover;" src="<?= base_url('uploads/kuliner/thumbs/' . $photo['foto']) ?>" onerror="this.src='<?= base_url('uploads/kuliner/' . $photo['foto']) ?>'" alt="Foto <?= esc($k['nama_tempat']) ?>">
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    <?php endif; ?>

    <div class="row g-4">
        <div class="col-lg-7">
            <div class="card p-4">
                <h5 class="fw-bold mb-3">Lokasi</h5>
                <div id="map"></div>
            </div>
        </div>
        <div class="col-lg-5">
            <div class="card p-4">
                <h5 class="fw-bold mb-3">Tulis Review</h5>
                <?php if (session()->get('login')): ?>
                    <form action="/kuliner/review/<?= $k['id'] ?>" method="post">
                        <?= csrf_field() ?>
                        <label class="form-label">Rating</label>
                        <select class="form-select mb-3" name="rating" required>
                            <?php for ($i = 5; $i >= 1; $i--): ?>
                                <option value="<?= $i ?>"><?= $i ?> bintang</option>
                            <?php endfor; ?>
                        </select>
                        <label class="form-label">Review</label>
                        <textarea class="form-control mb-3" name="review" rows="4" required></textarea>
                        <button class="btn btn-primary w-100">Kirim Review</button>
                    </form>
                <?php else: ?>
                    <p class="text-muted mb-3">Login untuk menulis review dan menyimpan tempat favorit.</p>
                    <a class="btn btn-primary" href="/login">Login</a>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <div class="card p-4 mt-4">
        <h5 class="fw-bold mb-3">Review Pengunjung</h5>
        <?php foreach ($reviews as $review): ?>
            <div class="border-bottom py-3">
                <div class="d-flex justify-content-between gap-3">
                    <div>
                        <strong><?= esc($review['nama_user'] ?? 'Pengguna') ?></strong>
                        <span class="badge bg-warning text-dark"><?= (int) $review['rating'] ?>/5</span>
                    </div>
                    <small class="text-muted"><?= esc($review['created_at']) ?></small>
                </div>
                <p class="mb-2 mt-2"><?= esc($review['review']) ?></p>
                <?php $canEditReview = session()->get('login') && (int) $review['user_id'] === (int) session()->get('user_id') && strtotime($review['created_at']) >= strtotime('-24 hours'); ?>
                <?php if ($canEditReview): ?>
                    <form action="/kuliner/review/update/<?= $review['id'] ?>" method="post" class="row g-2 mt-2">
                        <?= csrf_field() ?>
                        <div class="col-md-3">
                            <select class="form-select form-select-sm" name="rating">
                                <?php for ($i = 5; $i >= 1; $i--): ?>
                                    <option value="<?= $i ?>" <?= (int) $review['rating'] === $i ? 'selected' : '' ?>><?= $i ?></option>
                                <?php endfor; ?>
                            </select>
                        </div>
                        <div class="col-md-7">
                            <input class="form-control form-control-sm" name="review" value="<?= esc($review['review']) ?>">
                        </div>
                        <div class="col-md-2">
                            <button class="btn btn-sm btn-outline-primary w-100">Update</button>
                        </div>
                    </form>
                <?php endif; ?>
            </div>
        <?php endforeach; ?>
        <?php if (empty($reviews)): ?>
            <p class="text-muted mb-0">Belum ada review.</p>
        <?php endif; ?>
    </div>
</main>

<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
const lat = Number(<?= json_encode($k['latitude']) ?>);
const lng = Number(<?= json_encode($k['longitude']) ?>);

if (lat && lng) {
    const map = L.map('map').setView([lat, lng], 16);
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '&copy; OpenStreetMap contributors'
    }).addTo(map);
    L.marker([lat, lng]).addTo(map).bindPopup(<?= json_encode($k['nama_tempat']) ?>).openPopup();
} else {
    document.getElementById('map').innerHTML = '<div class="h-100 d-flex align-items-center justify-content-center text-muted">Lokasi belum tersedia.</div>';
}
</script>
</body>
</html>
