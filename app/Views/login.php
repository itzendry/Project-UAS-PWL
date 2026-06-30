<!DOCTYPE html>
<html lang="id">
<head>
    <title>Login - Kuliner App</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <style>
        body {
            margin: 0;
            font-family: 'Poppins', sans-serif;
            color: #2B3674;
            
            background: linear-gradient(rgba(17, 28, 67, 0.75), rgba(17, 28, 67, 0.75)), 
                        url('<?= base_url('assets/gambar1.jpeg'); ?>');
            /* DIPERBAIKI: Menggunakan cover agar gambar tidak distorsi/penyet */
            background-size: cover; 
            background-position: center;
            background-attachment: fixed;
            background-repeat: no-repeat;
            
            height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        /*LOGIN */
        .login-card {
            background-color: #ffffff;
            border: none;
            border-radius: 20px;
            box-shadow: 0px 20px 50px rgba(0, 0, 0, 0.3); 
            padding: 40px;
            width: 100%;
            max-width: 420px;
            margin: 20px; 
        }

        .login-card h3 { color: #2B3674; font-weight: 700; }
        .login-card p { color: #A3AED0; font-size: 0.95rem; }

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

        /* TOMBOL MASUK*/
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

        /*DAFTAR AKUN*/
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

    <div class="login-card">

        <h3 class="mb-1 text-center">Selamat Datang</h3>
        <p class="mb-4 text-center">Silakan masuk ke akun Anda</p>

        <?php if(session()->getFlashdata('success')): ?>
            <div class="alert alert-success" style="border-radius: 12px; font-size: 0.9rem;">
                <?= session()->getFlashdata('success'); ?>
            </div>
        <?php endif; ?>

        <?php if(session()->getFlashdata('error')): ?>
            <div class="alert alert-danger" style="border-radius: 12px; font-size: 0.9rem;">
                <?= session()->getFlashdata('error'); ?>
            </div>
        <?php endif; ?>

        <form action="<?= base_url('login/proses'); ?>" method="post">
            <?= csrf_field(); ?>
            <div class="mb-3">
                <input type="email" name="email" class="form-control" placeholder="Email Address" required>
            </div>
            <div class="mb-4">
                <input type="password" name="password" class="form-control" placeholder="Password" required>
            </div>
            <button class="btn btn-primary-custom w-100 py-2">Masuk</button>
            <div class="mt-3">
                <a href="/register" class="btn btn-outline-custom w-100 py-2 text-decoration-none text-center d-block">Daftar Akun</a>
            </div>
        </form>

    </div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>