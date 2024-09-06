<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>Laporan Kategori Barang</title>
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
    <h1>Laporan Kategori Barang</h1>
    <table>
        <tr>
            <th>No</th>
            <th>Nama Kategori</th>
        </tr>
        <?php if (!empty($kategori_barang) && is_array($kategori_barang)): ?>
            <?php foreach ($kategori_barang as $index => $kb): ?>
                <tr>
                    <td><?= $index + 1; ?></td>
                    <td><?= $kb['nama_kategori']; ?></td>
                </tr>
            <?php endforeach; ?>
        <?php else: ?>
            <tr>
                <td colspan="2">Data kategori barang tidak tersedia.</td>
            </tr>
        <?php endif; ?>
    </table>
</body>

</html>