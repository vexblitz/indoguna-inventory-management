<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Laporan Barang Masuk</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
        }

        h1 {
            text-align: center;
            color: #333;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        table,
        th,
        td {
            border: 1px solid #ddd;
        }

        th,
        td {
            padding: 10px;
            text-align: left;
        }

        th {
            background-color: #4CAF50;
            color: white;
        }

        tr:nth-child(even) {
            background-color: #f2f2f2;
        }

        tr:hover {
            background-color: #ddd;
        }
    </style>
</head>

<body>
    <h1><?= $title ?></h1>
    <table>
        <tr>
            <th>No</th>
            <th>Kode Transaksi</th>
            <th>Nama Barang</th>
            <th>Jumlah Keluar</th>
            <th>Tanggal Keluar</th>
        </tr>
        <?php if (!empty($barangKeluar) && is_array($barangKeluar)) : ?>
            <?php $no = 1; ?>
            <?php foreach ($barangKeluar as $item) : ?>
                <tr>
                    <td><?= esc($no++) ?></td>
                    <td><?= esc($item['kode_transaksi']) ?></td>
                    <td><?= esc($item['nama_barang']) ?></td>
                    <td><?= esc($item['jumlah_keluar']) ?></td>
                    <td><?= esc($item['tanggal_keluar']) ?></td>
                </tr>
            <?php endforeach; ?>
        <?php else : ?>
            <tr>
                <td colspan="9" class="text-center">Tidak ada data</td>
            </tr>
        <?php endif; ?>
    </table>
</body>

</html>