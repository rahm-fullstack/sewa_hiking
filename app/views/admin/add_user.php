<h2>Tambah User</h2>

<form method="POST">
    <input name="name" placeholder="Nama" required><br><br>
    <input name="username" placeholder="Username" required><br><br>
    <input name="password" type="password" placeholder="Password" required><br><br>

    <select name="role_id" required>
        <option value="1">Admin</option>
        <option value="2">Petugas</option>
        <option value="3">Peminjam</option>
    </select><br><br>

    <button type="submit">Simpan</button>
</form>
<a href="index.php?page=users">⬅ Kembali</a>