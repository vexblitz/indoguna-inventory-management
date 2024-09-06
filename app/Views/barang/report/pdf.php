<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>Laporan Data Barang</title>
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
    <h1>Laporan Data Barang</h1>
    <table>
        <tr>
            <th>No</th>
            <th>Kode Barang</th>
            <th>Nama Barang</th>
            <th>Harga Beli</th>
            <th>Harga Jual</th>
            <th>Jenis Satuan</th>
            <th>Kategori Barang</th>
            <th>Stok Awal</th>
            <th>Stok Akhir</th>
        </tr>
        <?php if (!empty($barang) && is_array($barang)) : ?>
            <?php $no = 1; ?>
            <?php foreach ($barang as $item) : ?>
                <tr>
                    <td><?= esc($no++) ?></td>
                    <td><?= esc($item['kode_barang']) ?></td>
                    <td><?= esc($item['nama_barang']) ?></td>
                    <td>Rp. <?= number_format($item['harga_beli'], 0, ',', '.') ?></td>
                    <td>Rp. <?= number_format($item['harga_jual'], 0, ',', '.') ?></td>
                    <td><?= esc($item['nama_satuan']) ?></td>
                    <td><?= esc($item['nama_kategori']) ?></td>
                    <td><?= esc($item['stok_awal']) ?></td>
                    <td><?= esc($item['stok_akhir']) ?></td>
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