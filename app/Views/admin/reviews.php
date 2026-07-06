<?= view('admin/_layout_start', ['title' => 'Moderasi Review']) ?>

<h3 class="fw-bold mb-4">Moderasi Review</h3>

<div class="card p-4">
    <div class="table-responsive">
        <table class="table align-middle">
            <thead><tr><th>Tempat</th><th>User</th><th>Review</th><th>Rating</th><th>Aksi</th></tr></thead>
            <tbody>
            <?php foreach ($reviews as $review): ?>
                <tr>
                    <td><?= esc($review['nama_tempat']) ?></td>
                    <td><?= esc($review['nama_user'] ?? '-') ?></td>
                    <td><?= esc($review['review']) ?></td>
                    <td><?= esc($review['rating']) ?>/5</td>
                    <td><a class="btn btn-sm btn-danger" href="/admin/review/delete/<?= $review['id'] ?>" onclick="return confirm('Hapus review ini?')">Hapus</a></td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<?= view('admin/_layout_end') ?>
