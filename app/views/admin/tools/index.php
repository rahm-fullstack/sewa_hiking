<h2>Data Alat Hiking</h2>
<a href="index.php?page=add-tool">+ Tambah Alat</a>

<table border="1">
<tr>
  <th>Nama</th>
  <th>Kategori</th>
  <th>Stok</th>
  <th>Harga/Hari</th>
  <th>Aksi</th>
</tr>

<?php foreach ($tools as $t): ?>
<tr>
  <td><?= $t['tool_name'] ?></td>
  <td><?= $t['category_name'] ?></td>
  <td><?= $t['stock'] ?></td>
  <td><?= $t['price_per_day'] ?></td>
  <td>
    <a href="index.php?page=edit-tool&id=<?= $t['id'] ?>">Edit</a> |
    <a href="index.php?page=delete-tool&id=<?= $t['id'] ?>" onclick="return confirm('Hapus data?')">Hapus</a>
  </td>
</tr>
<?php endforeach; ?>
</table>
<div style="margin-top: 16px;">
    <a href="index.php?page=admin-dashboard" class="btn btn-secondary">
        ← Kembali ke halaman utama
    </a>
</div>