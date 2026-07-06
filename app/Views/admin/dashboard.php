<?= view('admin/_layout_start', ['title' => 'Dashboard Admin']) ?>

<h3 class="fw-bold mb-4">Dashboard</h3>

<div class="row g-3 mb-4">
    <div class="col-md-3"><div class="card p-3"><span class="text-muted">Tempat</span><strong class="fs-2"><?= $totalKuliner ?></strong></div></div>
    <div class="col-md-3"><div class="card p-3"><span class="text-muted">Menunggu Moderasi</span><strong class="fs-2"><?= $pendingKuliner ?></strong></div></div>
    <div class="col-md-3"><div class="card p-3"><span class="text-muted">Review</span><strong class="fs-2"><?= $totalReview ?></strong></div></div>
    <div class="col-md-3"><div class="card p-3"><span class="text-muted">User Aktif</span><strong class="fs-2"><?= $totalUser ?></strong></div></div>
</div>

<div class="card p-4">
    <h5 class="fw-bold mb-3">Rating Tertinggi</h5>
    <div class="table-responsive">
        <table class="table align-middle">
            <thead><tr><th>Tempat</th><th>Kategori</th><th>Rating</th><th>Status</th></tr></thead>
            <tbody>
            <?php foreach ($topKuliner as $item): ?>
                <tr>
                    <td><?= esc($item['nama_tempat']) ?></td>
                    <td><?= esc($item['nama_kategori']) ?></td>
                    <td><?= esc($item['rating']) ?>/5</td>
                    <td><span class="badge bg-success"><?= esc($item['status']) ?></span></td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<?= view('admin/_layout_end') ?>
