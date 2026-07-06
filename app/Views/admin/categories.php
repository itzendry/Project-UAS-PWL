<?= view('admin/_layout_start', ['title' => 'Kelola Kategori']) ?>

<h3 class="fw-bold mb-4">Kelola Kategori</h3>

<div class="row g-4">
    <div class="col-md-4">
        <div class="card p-4">
            <h5 class="fw-bold mb-3">Tambah Kategori</h5>
            <form action="/admin/categories/save" method="post">
                <?= csrf_field() ?>
                <label class="form-label">Nama Kategori</label>
                <input class="form-control mb-3" name="nama_kategori" value="<?= old('nama_kategori') ?>" required>
                <button class="btn btn-primary w-100">Simpan</button>
            </form>
        </div>
    </div>
    <div class="col-md-8">
        <div class="card p-4">
            <div class="table-responsive">
                <table class="table align-middle">
                    <thead><tr><th>Kategori</th><th class="text-end">Aksi</th></tr></thead>
                    <tbody>
                    <?php foreach ($categories as $category): ?>
                        <tr>
                            <td><?= esc($category['nama_kategori']) ?></td>
                            <td class="text-end"><a class="btn btn-sm btn-outline-danger" href="/admin/categories/delete/<?= $category['id'] ?>" onclick="return confirm('Hapus kategori ini?')">Hapus</a></td>
                        </tr>
                    <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<?= view('admin/_layout_end') ?>
