<!DOCTYPE html>
<html lang="id">
<head>
    <title>Daftar Akun - Kuliner App</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <style>
        body {
            margin: 0;
            font-family: 'Poppins', sans-serif;
            color: #2B3674;
            
            background: linear-gradient(rgba(17, 28, 67, 0.75), rgba(17, 28, 67, 0.75)), 
                        url('<?= base_url('assets/gambar1.jpeg'); ?>');
            background-size: cover;
            background-position: center;
            background-attachment: fixed;
            background-repeat: no-repeat;
            
            height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        /* KARTU REGISTER*/
        .register-card {
            background-color: #ffffff;
            border: none;
            border-radius: 20px;
            box-shadow: 0px 20px 50px rgba(0, 0, 0, 0.3); 
            padding: 40px;
            width: 100%;
            max-width: 420px;
            margin: 20px;
        }

        .register-card h3 {
            color: #2B3674;
            font-weight: 700;
        }

        .register-card p {
            color: #A3AED0;
            font-size: 0.95rem;
        }

        /* INPUT*/
        .form-control {
            border-radius: 12px;
            padding: 12px 18px;
            border: 1px solid #E0E5F2;
            color: #2B3674;
            font-size: 0.95rem;
            background-color: #F8FAFC;
        }

        .form-control:focus {
            border-color: #3A57E8;
            background-color: #ffffff;
            box-shadow: 0 0 0 4px rgba(58, 87, 232, 0.1);
        }

        /* TOMBOL DAFTAR */
        .btn-primary-custom {
            background-color: #3A57E8;
            color: #ffffff;
            border: none;
            border-radius: 12px;
            padding: 12px 20px;
            font-weight: 600;
            transition: all 0.3s ease;
        }

        .btn-primary-custom:hover {
            background-color: #2B44C4;
            color: #ffffff;
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(58, 87, 232, 0.25);
        }

        /*KEMBALI KE LOGIN*/
        .btn-outline-custom {
            background-color: transparent;
            color: #3A57E8;
            border: 2px solid #3A57E8;
            border-radius: 12px;
            padding: 12px 20px;
            font-weight: 600;
            transition: all 0.3s ease;
        }

        .btn-outline-custom:hover {
            background-color: #3A57E8;
            color: #ffffff;
            box-shadow: 0 8px 20px rgba(58, 87, 232, 0.25);
        }
    </style>
</head>

<body>

    <div class="register-card">

        <h3 class="mb-1 text-center">Daftar Akun</h3>
        <p class="mb-4 text-center">Buat akun untuk mulai berbagi ulasan</p>

        <?php if(session()->getFlashdata('error')): ?>
            <div class="alert alert-danger" role="alert">
                <?= session()->getFlashdata('error'); ?>
            </div>
        <?php endif; ?>

        <form action="<?= base_url('register/proses'); ?>" method="post">
            <?= csrf_field(); ?>
            
            <div class="mb-3">
                <label class="form-label" style="font-size: 0.85rem; font-weight: 500; color: #707EAE;">Nama Pengguna</label>
                <input type="text" name="nama" class="form-control" placeholder="nama" value="<?= old('nama'); ?>" required>
            </div>

            <div class="mb-3">
                <label class="form-label" style="font-size: 0.85rem; font-weight: 500; color: #707EAE;">Email</label>
                <input type="email" name="email" class="form-control" placeholder="nama@email.com" value="<?= old('email'); ?>" required>
            </div>

            <div class="mb-4">
                <label class="form-label" style="font-size: 0.85rem; font-weight: 500; color: #707EAE;">Password</label>
                <input type="password" name="password" class="form-control" placeholder="minimal 8 karakter" required>
            </div>

            <button class="btn btn-primary-custom w-100 py-2">Daftar Akun</button>

            <div class="mt-3">
                <a href="/login" class="btn btn-outline-custom w-100 py-2 text-decoration-none text-center d-block">Kembali ke Login</a>
            </div>

        </form>

    </div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>