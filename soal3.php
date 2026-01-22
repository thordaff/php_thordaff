<?php
    require_once __DIR__ . '/vendor/autoload.php';
    
    $dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
    $dotenv->load();

    $host = $_ENV['DB_HOST'];
    $dbname = $_ENV['DB_DATABASE'];
    $username = $_ENV['DB_USERNAME'];
    $password = $_ENV['DB_PASSWORD'];
    $port = $_ENV['DB_PORT'];

    try {
        $pdo = new PDO("mysql:host=$host;port=$port;dbname=$dbname", $username, $password);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    } catch (PDOException $e) {
        die("Could not connect to the database $dbname :" . $e->getMessage());
    }

    $searchResults = [];
    $searched = false;

    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['search'])) {
        $searched = true;
        $nama = $_POST['nama'] ?? '';
        $alamat = $_POST['alamat'] ?? '';
        
        $sql = "SELECT p.id, p.nama, p.alamat, h.hobi 
                FROM person p 
                LEFT JOIN hobi h ON p.id = h.person_id 
                WHERE 1=1";
        
        $params = [];
        
        if (!empty($nama)) {
            $sql .= " AND p.nama LIKE :nama";
            $params[':nama'] = "%$nama%";
        }
        
        if (!empty($alamat)) {
            $sql .= " AND p.alamat LIKE :alamat";
            $params[':alamat'] = "%$alamat%";
        }
        
        $stmt = $pdo->prepare($sql);
        $stmt->execute($params);
        $searchResults = $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    $stmt = $pdo->query("SELECT p.id, p.nama, p.alamat, h.hobi 
                        FROM person p 
                        LEFT JOIN hobi h ON p.id = h.person_id");
    $allData = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Soal 3 - Person & Hobi</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
            background-color: #f5f5f5;
        }
        .container {
            max-width: 800px;
            margin: 0 auto;
            background: white;
            padding: 20px;
            border-radius: 5px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
        }
        h2 {
            color: #333;
            border-bottom: 2px solid #4CAF50;
            padding-bottom: 10px;
        }
        .form-group {
            margin-bottom: 15px;
        }
        label {
            display: inline-block;
            width: 80px;
            font-weight: bold;
        }
        input[type="text"] {
            padding: 8px;
            width: 200px;
            border: 1px solid #ddd;
            border-radius: 4px;
        }
        button {
            background-color: #4CAF50;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 14px;
        }
        button:hover {
            background-color: #45a049;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 12px;
            text-align: left;
        }
        th {
            background-color: #4CAF50;
            color: white;
        }
        tr:nth-child(even) {
            background-color: #f2f2f2;
        }
        .section {
            margin-bottom: 30px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Data Person dan Hobi</h2>
        
        <!-- Section 1: All Data -->
        <div class="section">
            <h3>Semua Data</h3>
            <table>
                <thead>
                    <tr>
                        <th>Nama</th>
                        <th>Alamat</th>
                        <th>Hobi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (count($allData) > 0): ?>
                        <?php foreach ($allData as $row): ?>
                            <tr>
                                <td><?= htmlspecialchars($row['nama']) ?></td>
                                <td><?= htmlspecialchars($row['alamat']) ?></td>
                                <td><?= htmlspecialchars($row['hobi'] ?? '-') ?></td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="3" style="text-align: center;">Tidak ada data</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>

        <!-- Section 2: Search Form -->
        <div class="section" id="search-form">
            <h3>Pencarian</h3>
            <form method="POST" action="#hasil-pencarian">
                <div class="form-group">
                    <label>Nama:</label>
                    <input type="text" name="nama" value="<?= htmlspecialchars($_POST['nama'] ?? '') ?>">
                </div>
                <div class="form-group">
                    <label>Alamat:</label>
                    <input type="text" name="alamat" value="<?= htmlspecialchars($_POST['alamat'] ?? '') ?>">
                </div>
                <div class="form-group">
                    <button type="submit" name="search">SEARCH</button>
                </div>
            </form>
        </div>

        <!-- Section 3: Search Results -->
        <?php if ($searched): ?>
        <div class="section" id="hasil-pencarian">
            <h3>Hasil Pencarian</h3>
            <table>
                <thead>
                    <tr>
                        <th>Nama</th>
                        <th>Alamat</th>
                        <th>Hobi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (count($searchResults) > 0): ?>
                        <?php foreach ($searchResults as $row): ?>
                            <tr>
                                <td><?= htmlspecialchars($row['nama']) ?></td>
                                <td><?= htmlspecialchars($row['alamat']) ?></td>
                                <td><?= htmlspecialchars($row['hobi'] ?? '-') ?></td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="3" style="text-align: center;">Tidak ada hasil yang ditemukan</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
        <?php endif; ?>
    </div>
</body>
</html>