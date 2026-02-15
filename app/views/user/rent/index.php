<h2>Riwayat Pengajuan Sewa</h2>

<a href="index.php?page=user-tools">⬅ Lihat Barang Tersedia</a>
<br><br>

<table border="1" cellpadding="8">
    <tr>
        <th>ID</th>
        <th>Barang</th>
        <th>Tanggal Sewa</th>
        <th>Tanggal Kembali</th>
        <th>Status</th>
    </tr>

    <?php if (empty($rentals)): ?>
        <tr>
            <td colspan="5">Belum ada pengajuan</td>
        </tr>
    <?php else: ?>
        <?php foreach ($rentals as $r): ?>
        <tr>
            <td><?= $r['id'] ?></td>
            <td><?= htmlspecialchars($r['tool_name']) ?></td>
            <td><?= $r['start_date'] ?></td>
            <td><?= $r['end_date'] ?></td>
            <td>
                <?php if ($r['status'] == 'pending'): ?>
                    ⏳ Pending
                <?php elseif ($r['status'] == 'approved'): ?>
                    ✅ Approved
                <?php elseif ($r['status'] == 'returned'): ?>
                    🔁 Returned
                <?php else: ?>
                    <?= $r['status'] ?>
                <?php endif; ?>
            </td>
        </tr>
        <?php endforeach; ?>
    <?php endif; ?>
</table>
<a href="index.php?page=login">⬅ Keluar</a>
<?php if ($r['status'] == 'pending'): ?>
    <a href="index.php?page=cancel-rental&id=<?= $r['id'] ?>">
        Batalkan
    </a>
<?php endif; ?>

<?php if ($r['status'] == 'approved'): ?>
    <a href="index.php?page=request-return&id=<?= $r['id'] ?>">
        Ajukan Pengembalian
    </a>
    <?php if ($r['status'] == 'pending'): ?>
    <a href="index.php?page=user-cancel&id=<?= $r['detail_id'] ?>">
        Batalkan
    </a>
<?php endif; ?>

<?php if ($r['status'] == 'approved'): ?>
    <a href="index.php?page=request-return&id=<?= $r['detail_id'] ?>">
        Ajukan Pengembalian
    </a>
<?php endif; ?>

<?php endif; ?>

