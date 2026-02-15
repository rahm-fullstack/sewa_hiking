<h2>Daftar Alat</h2>

<?php foreach ($tools as $t): ?>
    <div>
        <b><?= $t['tool_name'] ?></b>
        (stok: <?= $t['stock'] ?>)
        <a href="index.php?page=user-rent&id=<?= $t['id'] ?>">
            Sewa
        </a>
    </div>
<?php endforeach ?>
<a href="index.php?page=login">⬅ Keluar</a>
<a href="index.php?page=user-rentals">riwayat sewa</a>