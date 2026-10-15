<!DOCTYPE html>
<html>
<head>
    <title>Tambah Transaksi</title>
</head>
<body>

    <h1>Tambah Transaksi</h1>

    <form action="/transactions" method="POST">
        @csrf

        <label>Judul</label><br>
        <input type="text" name="title"><br><br>

        <label>Jenis</label><br>
        <select name="type">
            <option value="income">Pemasukan</option>
            <option value="expense">Pengeluaran</option>
        </select><br><br>

        <label>Jumlah</label><br>
        <input type="number" name="amount"><br><br>

        <label>Tanggal</label><br>
        <input type="date" name="date"><br><br>

        <button type="submit">Simpan</button>
    </form>

</body>
</html>