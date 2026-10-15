<!DOCTYPE html>
<html>
<head>
    <title>Catatan Keuangan</title>
</head>
<body>

    <h1>Catatan Keuangan</h1>

    <table border="1" cellpadding="8">
        <tr>
            <th>Judul</th>
            <th>Jenis</th>
            <th>Jumlah</th>
            <th>Tanggal</th>
        </tr>

        @foreach ($transactions as $transaction)
            <tr>
                <td>{{ $transaction->title }}</td>
                <td>{{ $transaction->type }}</td>
                <td>{{ $transaction->amount }}</td>
                <td>{{ $transaction->date }}</td>
            </tr>
        @endforeach
    </table>

    @if ($transactions->count() == 0)
        <p>Belum ada data transaksi.</p>
    @endif

</body>
</html>