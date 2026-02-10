<h2>Laporan Alat Paling Sering Disewa</h2>

<a href="index.php?page=reports">⬅ Kembali</a>
<br><br>

<table border="1" cellpadding="8" cellspacing="0">
    <tr>
        <th>No</th>
        <th>Nama Alat</th>
        <th>Kategori</th>
        <th>Total Disewa</th>
        <th>Total Pendapatan</th>
    </tr>

    <?php if (empty($reports)): ?>
        <tr>
            <td colspan="5" align="center">Belum ada data</td>
        </tr>
    <?php else: ?>
        <?php $no = 1; ?>
        <?php foreach ($reports as $r): ?>
            <tr>
                <td><?= $no++ ?></td>
                <td><?= htmlspecialchars($r['tool_name']) ?></td>
                <td><?= htmlspecialchars($r['category_name']) ?></td>
                <td><?= $r['total_disewa'] ?></td>
                <td><?= number_format($r['total_pendapatan']) ?></td>
            </tr>
        <?php endforeach; ?>
    <?php endif; ?>
</table>
