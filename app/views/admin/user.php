<h2>Data User</h2>
<a href="index.php?page=add-user">Tambah User</a>

<table border="1">
<tr>
    <th>Nama</th>
    <th>Username</th>
    <th>Role</th>
    <th>Aksi</th>
</tr>

<?php foreach ($users as $user): ?>
<tr>
    <td><?= $user['name']; ?></td>
    <td><?= $user['username']; ?></td>
    <td><?= $user['role_name']; ?></td>
    <td>
        <a href="index.php?page=delete-user&id=<?= $user['id']; ?>"
           onclick="return confirm('Hapus user?')">Hapus</a>
    </td>
</tr>
<?php endforeach; ?>
</table>
<a href="index.php?page=admin-dashboard">⬅ Kembali</a>