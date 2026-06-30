<!DOCTYPE html>
<html>
<head>
    <title>Data API Kuliner</title>
</head>
<body>

<h2>Data Kuliner Dari API</h2>

<table border="1" cellpadding="10">
    <tr>
        <th>ID</th>
        <th>Nama Tempat</th>
        <th>Kategori</th>
        <th>Alamat</th>
        <th>Rating</th>
    </tr>

    <?php foreach($kuliner as $k): ?>
    <tr>
        <td><?= $k['id'] ?></td>
        <td><?= esc($k['nama_tempat']) ?></td>
        <td><?= esc($k['nama_kategori']) ?></td>
        <td><?= esc($k['alamat']) ?></td>
        <td><?= esc($k['rating']) ?></td>
    </tr>
    <?php endforeach; ?>

</table>

</body>
</html>