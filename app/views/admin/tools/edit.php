<h1>Edit Alat Hiking</h1>

<form method="POST" action="">
    <div>
        <label>Kategori</label><br>
        <select name="category_id" required>
            <?php foreach ($categories as $cat): ?>
                <option value="<?= $cat['id']; ?>"
                    <?= $cat['id'] == $tool['category_id'] ? 'selected' : ''; ?>>
                    <?= htmlspecialchars($cat['category_name']); ?>
                </option>
            <?php endforeach; ?>
        </select>
    </div>

    <br>

    <div>
        <label>Nama Alat</label><br>
        <input type="text" name="tool_name"
               value="<?= htmlspecialchars($tool['tool_name']); ?>" required>
    </div>

    <br>

    <div>
        <label>Stok</label><br>
        <input type="number" name="stock"
               value="<?= $tool['stock']; ?>" min="0" required>
    </div>

    <br>
    <br>

    <div>
        <label>Harga Sewa / Hari</label><br>
        <input type="number" name="price_per_day"
               value="<?= $tool['price_per_day']; ?>" min="0" required>
    </div>

    <br>

    <button type="submit">Update</button>
    <a href="index.php?page=alat">Batal</a>
</form>
<div style="margin-top: 16px;">
    <a href="index.php?page=tools" class="btn btn-secondary">
        ← Kembali ke daftar alat
    </a>
</div>