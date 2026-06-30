<!DOCTYPE html>
<html lang="id">

<head>
    <title>Edit Data - Kuliner Premium</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <style>
        body { 
            background-color: #F4F7FE; 
            font-family: 'Poppins', sans-serif; 
            color: #2B3674; 
            overflow: hidden; 
        }

        .sidebar { 
            width: 260px; 
            background-color: #111C43; 
            color: white; 
            border-radius: 20px; 
            margin: 20px; 
            box-shadow: 0 10px 30px rgba(17, 28, 67, 0.15); 
            z-index: 100; 
        }

        .sidebar .nav-link { 
            color: #A3AED0; 
            border-radius: 12px; 
            padding: 12px 18px; 
            margin-bottom: 8px; 
            transition: all 0.3s ease; 
            font-weight: 500; 
        }

        .sidebar .nav-link:hover, 
        .sidebar .nav-link.active { 
            background-color: #3A57E8; 
            color: #ffffff; 
            box-shadow: 0 5px 15px rgba(58, 87, 232, 0.25); 
            transform: translateX(5px); 
        }

        .main-content { 
            height: 100vh; 
            overflow-y: auto; 
            padding: 30px 40px !important; 
        }

        .card-custom { 
            background-color: #ffffff; 
            border: none; 
            border-radius: 20px; 
            box-shadow: 0px 10px 40px rgba(112, 144, 176, 0.08); 
            padding: 20px; 
        }

        .form-control, 
        .form-select { 
            border-radius: 12px; 
            padding: 12px 18px; 
            border: 1px solid #E0E5F2; 
            color: #2B3674; 
            font-size: 0.95rem; 
            background-color: #F4F7FE; 
        }

        .form-control:focus, 
        .form-select:focus { 
            border-color: #3A57E8; 
            background-color: #ffffff; 
            box-shadow: 0 0 0 4px rgba(58, 87, 232, 0.1); 
        }

        .form-label { 
            color: #A3AED0; 
            font-weight: 500; 
            font-size: 0.85rem; 
            margin-bottom: 8px; 
        }

        .btn-primary-custom { 
            background-color: #3A57E8; 
            color: #ffffff; 
            border: none; 
            border-radius: 12px; 
            padding: 12px 24px; 
            font-weight: 600; 
            transition: all 0.3s ease; 
        }

        .btn-primary-custom:hover { 
            background-color: #2B44C4; 
            color: #ffffff; 
            transform: translateY(-2px); 
            box-shadow: 0 8px 20px rgba(58, 87, 232, 0.25); 
        }

        .btn-light-custom { 
            background-color: #E9EDF7; 
            color: #2B3674; 
            border: none; 
            border-radius: 12px; 
            padding: 12px 24px; 
            font-weight: 600; 
            transition: all 0.3s ease; 
        }

        .btn-light-custom:hover { 
            background-color: #D1D9E8; 
            transform: translateY(-2px); 
        }
    </style>
</head>

<body>

    <div class="d-flex vh-100">

        <div class="sidebar d-none d-md-flex flex-column p-4">
            <div class="text-center mb-5 mt-3">
                <img src="<?= base_url('assets/chef.png'); ?>" alt="Logo Kuliner" style="height: 100px; width: auto;" class="mb-2">
                <h4 class="fw-bold" style="color: #ffffff;">Kuliner</h4>
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
            
            <div class="mt-auto pt-4">
                <a href="/logout" class="btn w-100 py-2 fw-bold" 
                   style="background: rgba(255,255,255,0.08); color: #fff; border-radius: 12px; transition: 0.3s;" 
                   onmouseover="this.style.background='#EE5D50'" 
                   onmouseout="this.style.background='rgba(255,255,255,0.08)'">
                   Keluar
                </a>
            </div>
        </div>

        <div class="main-content flex-grow-1">
            <div class="row">
                <div class="col-lg-10">

                    <div class="d-flex justify-content-between align-items-center mb-4 mt-3">
                        <div>
                            <h3 class="fw-bold mb-1" style="color: #2B3674;">Edit Data Kuliner</h3>
                            <p class="mb-0" style="color: #A3AED0;">Perbarui informasi tempat kuliner yang dipilih.</p>
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

                    <?php if (session()->getFlashdata('error')): ?>
                        <div class="alert alert-danger alert-dismissible fade show" role="alert" style="border-radius: 12px;">
                            <?= session()->getFlashdata('error'); ?>
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    <?php endif; ?>

                    <div class="card card-custom">
                        <div class="card-body p-4 p-md-5">

                            <form action="/kuliner/update/<?= $kuliner['id']; ?>" method="post" enctype="multipart/form-data">
                                <?= csrf_field(); ?>

                                <div class="mb-4">
                                    <label class="form-label">Nama Tempat</label>
                                    <input type="text" name="nama_tempat" class="form-control" value="<?= old('nama_tempat', $kuliner['nama_tempat']); ?>" required>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">Kategori</label>
                                    <select name="category_id" class="form-select" required>
                                        <?php foreach ($categories as $c): ?>
                                            <option value="<?= $c['id']; ?>" <?= ($c['id'] == old('category_id', $kuliner['category_id'])) ? 'selected' : ''; ?>>
                                                <?= $c['nama_kategori']; ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>

                                <div class="mb-4">
                                    <label class="form-label">Alamat Lengkap</label>
                                    <input type="text" name="alamat" class="form-control" value="<?= old('alamat', $kuliner['alamat']); ?>" required>
                                </div>

                                <div class="mb-4">
                                    <label class="form-label">Deskripsi Fasilitas</label>
                                    <textarea name="deskripsi" class="form-control" rows="4"><?= old('deskripsi', $kuliner['deskripsi']); ?></textarea>
                                    <small class="text-muted mt-1 d-block">Tuliskan deskripsi lengkap tempat ini.</small>
                                </div>

                                <div class="mb-4">
                                    <label class="form-label">Ulasan Singkat</label>
                                    <input type="text" name="review" class="form-control" value="<?= old('review', $kuliner['review'] ?? ''); ?>" required>
                                </div>

                                <div class="mb-4">
                                    <label class="form-label">Upload Foto Tempat</label>
                                    <?php if (!empty($kuliner['foto']) && $kuliner['foto'] != 'default.png'): ?>
                                        <div class="mb-3">
                                            <img src="<?= base_url('uploads/kuliner/' . $kuliner['foto']); ?>" alt="Foto Tempat" class="img-thumbnail" style="max-height: 120px; border-radius: 12px; object-fit: cover;">
                                        </div>
                                    <?php endif; ?>
                                    
                                    <input type="file" name="foto" class="form-control" accept="image/png, image/jpeg, image/jpg">
                                    <small class="text-muted d-block mt-1">Biarkan kosong jika tidak ingin mengubah foto. Maksimal 2MB (JPG/PNG).</small>
                                </div>

                                <div class="mb-5">
                                    <label class="form-label">Rating</label>
                                    <select name="rating" class="form-select" style="cursor: pointer;" required>
                                        <?php $rating = old('rating', $kuliner['rating'] ?? 5); ?>
                                        <option value="5" <?= ($rating == 5) ? 'selected' : ''; ?>>⭐⭐⭐⭐⭐ (Sangat Bagus)</option>
                                        <option value="4" <?= ($rating == 4) ? 'selected' : ''; ?>>⭐⭐⭐⭐ (Bagus)</option>
                                        <option value="3" <?= ($rating == 3) ? 'selected' : ''; ?>>⭐⭐⭐ (Lumayan)</option>
                                        <option value="2" <?= ($rating == 2) ? 'selected' : ''; ?>>⭐⭐ (Kurang)</option>
                                        <option value="1" <?= ($rating == 1) ? 'selected' : ''; ?>>⭐ (Buruk)</option>
                                    </select>
                                </div>

                                <div class="d-flex justify-content-end gap-3 pt-3 border-top" style="border-color: #E9EDF7 !important;">
                                    <a href="/kuliner" class="btn btn-light-custom text-decoration-none">Batal</a>
                                    <button type="submit" class="btn btn-primary-custom">Simpan Perubahan</button>
                                </div>

                            </form>

                        </div>
                    </div>

                </div>
            </div>
        </div>

    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>