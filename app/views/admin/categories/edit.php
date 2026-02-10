<h2>Edit Kategori</h2>

<form method="POST">
    <input name="category_name"
           value="<?= htmlspecialchars($category['category_name']); ?>"
           required><br><br>

    <textarea name="description"><?= htmlspecialchars($category['description']); ?></textarea><br><br>

    <button type="submit">Update</button>
</form>
<div style="margin-top: 16px;">
    <a href="index.php?page=categories" class="btn btn-secondary">
        ← Kembali ke Daftar Kategori
    </a>
</div>
