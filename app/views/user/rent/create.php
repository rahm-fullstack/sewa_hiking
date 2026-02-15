<h2>Form Sewa</h2>

<form method="POST" action="index.php?page=user-rent-store">

    <input type="hidden" name="tool_id" value="<?= $tool['id'] ?>">

    Jumlah:
    <input type="number" name="quantity" required>

    Tanggal Mulai:
    <input type="date" name="start_date" required>

    Tanggal Selesai:
    <input type="date" name="end_date" required>

    <button type="submit">Ajukan Sewa</button>
</form>

<br><br>
<a href="index.php?page=user-tools">
    <button type="button">Kembali</button>
</a>

