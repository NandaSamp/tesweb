<?php
// Menyertakan file CSS
echo '<link rel="stylesheet" type="text/css" href="css/style.css">';
?>

<!DOCTYPE html>
<html lang="ID">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="wIDth=device-wIDth, initial-scale=1.0">
    <title>Buat Akun | Projek Anda</title>
</head>
<body>
    <header>
        <h1>NandaSamp Roleplay</h1>
        <nav>
            <ul>
                <li><a href="index.php">Beranda</a></li>
                <li><a href="buat-akun.php">Buat Akun</a></li>
                <li><a href="#">Discord</a></li>
                <li><a href="daftar-player.php">Daftar Player</a></li>
            </ul>
        </nav>
    </header>

    <div class="container">
        <h2>Buat Akun</h2>
        <form action="proses-buat-akun.php" method="post">
            <label for="ucp">ucp:</label>
            <input type="text" ID="ucp" name="ucp" required>
            <br><br>
            <label for="DiscordID">ID DISCORD:</label>
            <input type="tel" ID="DiscordID" name="DiscordID" required>
            <br><br>
            <label for="verifycode">verifycode:</label>
            <input type="text" ID="verifycode" name="verifycode" required>
            <br><br>
            <input type="submit" value="Buat Akun">
        </form>
    </div>

    <footer>
        <p>&copy; 2024 Projek Anda. Semua hak cipta dilindungi.</p>
    </footer>
</body>
</html>
