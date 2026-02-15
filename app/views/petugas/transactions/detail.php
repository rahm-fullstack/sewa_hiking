<h2>Detail Transaksi</h2>

<a href="index.php?page=petugas-transactions">⬅ Kembali</a>
<br><br>

<p><strong>Nama Penyewa:</strong> <?= htmlspecialchars($rental['user_name']) ?></p>
<p><strong>Tanggal Sewa:</strong> <?= $rental['start_date'] ?></p>
<p><strong>Tanggal Kembali:</strong> <?= $rental['end_date'] ?></p>
<p><strong>Status:</strong> <?= $rental['status'] ?></p>

<h3>Daftar Alat</h3>

<table border="1" cellpadding="8">
<tr>
    <th>Nama Alat</th>
    <th>Jumlah</th>
</tr>

<?php foreach ($details as $d): ?>
<tr>
    <td><?= htmlspecialchars($d['tool_name']) ?></td>
    <td><?= $d['quantity'] ?></td>
</tr>
<?php endforeach; ?>
</table>
