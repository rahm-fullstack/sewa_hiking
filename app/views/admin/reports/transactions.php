<h2>Laporan Transaksi</h2>
<a href="index.php?page=reports">⬅ Kembali</a>
<form method="GET">
    <input type="hidden" name="page" value="report-transactions">
    <input type="date" name="start" required>
    <input type="date" name="end" required>
    <button type="submit">Filter</button>
</form>

<table border="1" cellpadding="5">
    <tr>
        <th>No</th>
        <th>Peminjam</th>
        <th>Alat</th>
        <th>Kategori</th>
        <th>Tanggal</th>
        <th>Total</th>
        <th>Status</th>
    </tr>

    <?php foreach ($reports as $i => $r): ?>
    <tr>
        <td><?= $i+1 ?></td>
        <td><?= htmlspecialchars($r['user_name']) ?></td>
        <td><?= htmlspecialchars($r['tool_name']) ?></td>
        <td><?= htmlspecialchars($r['category_name']) ?></td>
        <td><?= $r['start_date'] ?> → <?= $r['end_date'] ?></td>
        <td>Rp <?= number_format($r['total_price']) ?></td>
        <td><?= $r['status'] ?></td>
    </tr>
    <?php endforeach; ?>
</table>
