<h2>Daftar Transaksi Sewa</h2>

<a href="index.php?page=petugas-dashboard">⬅ Kembali</a>
<br><br>

<table border="1" cellpadding="8">
    <tr>
        <th>ID</th>
        <th>Nama Penyewa</th>
        <th>Tanggal Sewa</th>
        <th>Tanggal Kembali</th>
        <th>Status</th>
        <th>Aksi</th>
    </tr>

    <?php if (empty($rentals)): ?>
        <tr>
            <td colspan="6">Belum ada transaksi</td>
        </tr>
    <?php else: ?>
       <?php foreach ($rentals as $r): ?>
<tr>
    <td><?= $r['id'] ?></td>
    <td><?= htmlspecialchars($r['user_name']) ?></td>
    <td><?= $r['start_date'] ?></td>
    <td><?= $r['end_date'] ?></td>
    <td><?= $r['status'] ?></td>
    <td>
        <a href="index.php?page=petugas-transactions-detail&id=<?= $r['id'] ?>">
            Detail
        </a>

    <?php if ($r['status'] === 'pending'): ?>
    <form method="POST" action="index.php?page=approve-rental" style="display:inline;">
        <input type="hidden" name="id" value="<?= $r['id'] ?>">
        <button type="submit" onclick="return confirm('Approve transaksi ini?')">
            Approve
        </button>
    </form>
<?php endif; ?>

    </td>
</tr>
         <?php endforeach; ?>
     <?php endif; ?>    
