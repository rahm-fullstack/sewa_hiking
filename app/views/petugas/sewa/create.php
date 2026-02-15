<h2>Form Transaksi Sewa</h2>

<form method="POST" action="index.php?page=petugas-sewa-store">

    <label>Peminjam</label><br>
    <select name="user_id" required>
        <?php foreach ($users as $u): ?>
            <option value="<?= $u['id'] ?>">
                <?= htmlspecialchars($u['name']) ?>
            </option>
        <?php endforeach; ?>
    </select>
    <br><br>

    <label>Alat</label><br>
    <select name="tool_id" required>
        <?php foreach ($tools as $t): ?>
            <option value="<?= $t['id'] ?>">
                <?= htmlspecialchars($t['tool_name']) ?> (stok: <?= $t['stock'] ?>)
            </option>
        <?php endforeach; ?>
    </select>
    <br><br>

    <label>Jumlah</label><br>
    <input type="number" name="quantity" min="1" required>
    <br><br>

    <label>Harga / Hari</label><br>
    <input type="number" name="price_per_day" required>
    <br><br>

    <label>Tanggal Sewa</label><br>
    <input type="date" name="start_date" required>
    <br><br>

    <label>Tanggal Kembali</label><br>
    <input type="date" name="end_date" required>
    <br><br>

    <button type="submit">Simpan Transaksi</button>
</form>

<br>
<a href="index.php?page=petugas-dashboard">⬅ Kembali</a>
