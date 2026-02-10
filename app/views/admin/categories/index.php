<h2>Data Kategori Alat Hiking</h2>

<a href="index.php?page=add-category">+ Tambah Kategori</a>

<table border="1" cellpadding="8">
<tr>
    <th>Nama Kategori</th>
    <th>Deskripsi</th>
    <th>Aksi</th>
</tr>

<?php foreach ($category as $cat): ?>
<tr>
    <td><?= htmlspecialchars($cat['category_name']); ?></td>
    <td><?= htmlspecialchars($cat['description']); ?></td>
    <td>
        <a href="index.php?page=edit-category&id=<?= $cat['id']; ?>">Edit</a> |
        <a href="index.php?page=delete-category&id=<?= $cat['id']; ?>"
           onclick="return confirm('Hapus kategori?')">Hapus</a>

    </td>
</tr>
<?php endforeach; ?>
</table>
<div style="margin-top: 16px;">
    <a href="index.php?page=admin-dashboard" class="btn btn-secondary">
        ← Kembali ke halaman utama
    </a>
</div>
