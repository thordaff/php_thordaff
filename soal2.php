<?php
    session_start();

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        if (isset($_POST['nama'])) {
            $_SESSION['nama'] = $_POST['nama'];
            header('Location: soal2.php?step=2');
            exit;
        } elseif (isset($_POST['umur'])) {
            $_SESSION['umur'] = $_POST['umur'];
            header('Location: soal2.php?step=3');
            exit;
        } elseif (isset($_POST['hobi'])) {
            $_SESSION['hobi'] = $_POST['hobi'];
            header('Location: soal2.php?step=4');
            exit;
        }
    }

    $step = isset($_GET['step']) ? (int)$_GET['step'] : 1;

?>
<!DOCTYPE html>
<html>
    <head>
        <title>Soal 2 - Technical Test</title>
        <style>
            .container {
                display: flex;
                justify-content: center;
                align-items: center;

                width: 100%;
                height: 100vh;
            }
            .card{
                border: 2px solid #000;
                border-radius: 10px;

                padding: 20px;
                width: 350px;
            }
            .card form {
                display: flex;
                flex-direction: column;
                align-items: flex-end;
            }
            .card form label,
            .card form input[type="text"],
            .card form input[type="number"] {
                align-self: flex-start;
                width: 100%;
            }
            .card form input[type="submit"] {
                margin-top: 20px;
            }

        </style>
    </head>
    <body>
        <div class="container">
            <div class="card">
                <?php if ($step == 1): ?>
                    <form method="POST">
                        <label>Nama Anda :</label>
                        <input type="text" name="nama" required>
                        <br>
                        <input type="submit" value="SUBMIT">
                    </form>
                    
                <?php elseif ($step == 2): ?>
                    <form method="POST">
                        <label>Umur Anda :</label>
                        <input type="number" name="umur" min="1" max="150" required>
                        <br>
                        <input type="submit" value="SUBMIT">
                    </form>
                    
                <?php elseif ($step == 3): ?>
                    <form method="POST">
                        <label>Hobi Anda :</label>
                        <input type="text" name="hobi" required>
                        <br>
                        <input type="submit" value="SUBMIT">
                    </form>
                    
                <?php elseif ($step == 4): ?>
                    <strong>Nama:</strong> <?php echo htmlspecialchars($_SESSION['nama'] ?? ''); ?><br>
                    <strong>Umur:</strong> <?php echo htmlspecialchars($_SESSION['umur'] ?? ''); ?><br>
                    <strong>Hobi:</strong> <?php echo htmlspecialchars($_SESSION['hobi'] ?? ''); ?>
                    <br><br>
                    <a href="soal2.php">Mulai Lagi</a>
                <?php endif; ?>
            </div>
        </div>
    </body>
</html>