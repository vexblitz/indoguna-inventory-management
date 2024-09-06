<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">
    <title>Data Barang</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
        }

        .container {
            width: 100%;
            margin: 0 auto;
            padding: 20px;
        }

        .header {
            text-align: center;
            margin-bottom: 20px;
        }

        .details {
            margin-bottom: 20px;
        }

        .details table {
            width: 100%;
            border-collapse: collapse;
        }

        .details table,
        .details th,
        .details td {
            border: 1px solid #000;
        }

        .details th,
        .details td {
            padding: 8px;
            text-align: left;
        }

        .details th {
            background-color: #f2f2f2;
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="header">
            <h1>Data Barang</h1>
        </div>
        <div class="details">
            <table>
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Kode Barang</th>
                        <th>Nama Barang</th>
                        <th>Harga Beli</th>
                        <th>Harga Jual</th>
                        <th>Jenis Satuan</th>
                        <th>Stok Awal</th>
                        <th>Stok Akhir</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($barang_control as $kb) : ?>
                        <tr>
                            <td><?php echo $kb['id'] ?></td>
                            <td><?php echo $kb['kode_barang'] ?></td>
                            <td><?php echo $kb['nama_barang'] ?></td>
                            <td>Rp. <?= number_format($kb['harga_beli'], 0, ',', '.') ?></td>
                            <td>Rp. <?= number_format($kb['harga_jual'], 0, ',', '.') ?></td>
                            <td><?php echo isset($kb['nama_satuan']) ? $kb['nama_satuan'] : 'N/A' ?></td>
                            <td><?php echo $kb['stok_awal'] ?></td>
                            <td><?php echo $kb['stok_akhir'] ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>

            </table>
        </div>
    </div>
</body>

</html>