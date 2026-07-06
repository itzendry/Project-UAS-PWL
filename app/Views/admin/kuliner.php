<?= view('admin/_layout_start', ['title' => 'Moderasi Kuliner']) ?>

<h3 class="fw-bold mb-4">Moderasi Tempat Kuliner</h3>

<div class="card p-4">
    <div class="table-responsive">
        <table class="table align-middle">
            <thead>
            <tr><th>Tempat</th><th>Kategori</th><th>Pengirim</th><th>Rating</th><th>Status</th><th>Aksi</th></tr>
            </thead>
            <tbody>
            <?php foreach ($kuliner as $item): ?>
                <tr>
                    <td>
                        <strong><?= esc($item['nama_tempat']) ?></strong><br>
                        <small class="text-muted"><?= esc($item['alamat']) ?></small>
                    </td>
                    <td><?= esc($item['nama_kategori']) ?></td>
                    <td><?= esc($item['nama_user'] ?? '-') ?></td>
                    <td><?= esc($item['rating']) ?>/5</td>
                    <td><span class="badge bg-<?= $item['status'] === 'approved' ? 'success' : ($item['status'] === 'rejected' ? 'danger' : 'warning text-dark') ?>"><?= esc($item['status']) ?></span></td>
                    <td class="text-nowrap">
                        <a class="btn btn-sm btn-success" href="/admin/approve/<?= $item['id'] ?>">Approve</a>
                        <a class="btn btn-sm btn-warning" href="/admin/reject/<?= $item['id'] ?>">Reject</a>
                        <a class="btn btn-sm btn-outline-primary" href="/kuliner/edit/<?= $item['id'] ?>">Edit</a>
                    </td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<?= view('admin/_layout_end') ?>
