<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= esc($title ?? 'Admin Kuliner') ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background: #f5f7fb; }
        .nav-admin { background: #111c43; }
        .card { border: 0; border-radius: 8px; box-shadow: 0 10px 30px rgba(17, 28, 67, .08); }
    </style>
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-dark nav-admin">
    <div class="container">
        <a class="navbar-brand fw-bold" href="/admin/dashboard">Admin Kuliner</a>
        <div class="navbar-nav ms-auto">
            <a class="nav-link" href="/admin/dashboard">Dashboard</a>
            <a class="nav-link" href="/admin/kuliner">Moderasi Tempat</a>
            <a class="nav-link" href="/admin/reviews">Review</a>
            <a class="nav-link" href="/admin/categories">Kategori</a>
            <a class="nav-link" href="/kuliner">Aplikasi</a>
            <a class="nav-link" href="/logout">Logout</a>
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
