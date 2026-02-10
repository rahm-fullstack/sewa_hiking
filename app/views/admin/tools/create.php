<h1>Tambah Alat Hiking</h1>

<form method="POST" action="">
    <div>
        <label>Kategori</label><br>
        <select name="category_id" required>
            <option value="">-- Pilih Kategori --</option>
            <?php foreach ($categories as $cat): ?>
                <option value="<?= $cat['id']; ?>">
                    <?= htmlspecialchars($cat['category_name']); ?>
                </option>
            <?php endforeach; ?>
        </select>
    </div>

    <br>

    <div>
        <label>Nama Alat</label><br>
        <input type="text" name="tool_name" required>
    </div>

    <br>

    <div>
        <label>Stok</label><br>
        <input type="number" name="stock" min="0" required>
    </div>

    <br>

    <br>

    <div>
        <label>Harga Sewa / Hari</label><br>
        <input type="number" name="price_per_day" min="0" required>
    </div>

    <br>

    <button type="submit">Simpan</button>
    <a href="index.php?page=alat">Batal</a>
</form>
<div style="margin-top: 16px;">
    <a href="index.php?page=tools" class="btn btn-secondary">
        ← Kembali ke daftar alat
    </a>
</div>