<h2>Approve Pengembalian</h2>

<form method="post" action="index.php?page=save-return">
    <input type="hidden" name="rental_id" value="<?= $_GET['id'] ?>">

    <label>Kondisi Barang</label>
    <select name="condition_return" required>
        <option value="baik">Baik</option>
        <option value="rusak">Rusak</option>
        <option value="hilang">Hilang</option>
    </select>

    <label>Denda</label>
    <input type="number" name="fine" value="0">

    <button type="submit">Simpan</button>
</form>
