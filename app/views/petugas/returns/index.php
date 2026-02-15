<h2>Pengembalian Alat</h2>

<table border="1">
<tr>
    <th>User</th>
    <th>Periode</th>
    <th>Aksi</th>
</tr>

<?php foreach ($rentals as $r): ?>
<tr>
    <td><?= $r['user_name'] ?></td>
    <td><?= $r['start_date'] ?> - <?= $r['end_date'] ?></td>
    <td>
        <a href="index.php?page=approve-return&id=<?= $r['id'] ?>">
            Proses
        </a>
    </td>
</tr>
<?php endforeach ?>
</table>
