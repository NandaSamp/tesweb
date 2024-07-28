<?php
// Menyertakan file CSS
echo '<link rel="stylesheet" type="text/css" href="css/proses.css">';
?>

<!DOCTYPE html>
<html lang="ID">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Proses Buat Akun | Projek Anda</title>
</head>
<body>
<header>
    <h1>verifycode</h1>
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
    <?php
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        // Memeriksa apakah kunci 'ucp', 'DiscordID', dan 'verifycode' ada di $_POST
        if (!isset($_POST['ucp'], $_POST['DiscordID'], $_POST['verifycode'])) {
            echo "<h1>Gagal Membuat Akun</h1>";
            echo "<p>Data formulir tidak lengkap.</p>";
            exit();
        }

        // Mengambil data dari formulir dan membersihkannya
        $ucp = htmlspecialchars($_POST['ucp']);
        $DiscordID = htmlspecialchars($_POST['DiscordID']);
        $verifycode = htmlspecialchars($_POST['verifycode']);
        
        // Validasi sederhana
        if (empty($ucp) || empty($DiscordID) || empty($verifycode)) {
            echo "<h1>Gagal Membuat Akun</h1>";
            echo "<p>Semua bidang harus diisi.</p>";
            exit();
        }
        
        // Validasi verifycode
        if (!preg_match('/^[0-9]+$/', $verifycode)) {
            echo "<h1>Gagal Membuat Akun</h1>";
            echo "<p>Verifycode hanya boleh mengandung angka.</p>";
            exit();
        }
        
        // Validasi ID Discord: angka panjang
        if (!preg_match('/^\d{17,19}$/', $DiscordID)) {
            echo "<h1>Gagal Membuat Akun</h1>";
            echo "<p>ID Discord tidak valid. Harus berupa angka panjang.</p>";
            exit();
        }

        // Verifikasi ID Discord menggunakan API Discord
        $token = 'MTE3OTM5NzYwNjc2NjU0Njk5NA.GPmO6G.hxDs2GNak_Hr5tHcFVJ5-RJyUgk-1MSdM4Sis4'; // Ganti dengan token bot Anda
        $url = "https://discord.com/api/v10/users/$DiscordID";
        $headers = [
            "Authorization: Bot $token"
        ];

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $response = curl_exec($ch);
        $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        if ($http_code != 200) {
            echo "<h1>Gagal Membuat Akun</h1>";
            echo "<p>ID Discord tidak ditemukan atau tidak valid.</p>";
            exit();
        }

        // Koneksi ke database
        $servername = "128.199.218.134"; // Ganti dengan server database Anda
        $username = "u8_bqNVvaGtJE"; // Ganti dengan username database Anda
        $password = "6K!1qRA=ZT2ssqn=zEBUvHL1"; // Ganti dengan password database Anda
        $dbname = "s8_vegas"; // Ganti dengan nama database Anda

        // Membuat koneksi
        $conn = new mysqli($servername, $username, $password, $dbname);

        // Memeriksa koneksi
        if ($conn->connect_error) {
            die("Koneksi gagal: " . $conn->connect_error);
        }

        // Memeriksa duplikasi ucp
        $sql = "SELECT COUNT(*) FROM playerucp WHERE ucp = ?";
        $stmt = $conn->prepare($sql);

        if ($stmt === false) {
            die("Gagal mempersiapkan query: " . $conn->error);
        }

        $stmt->bind_param("s", $ucp);
        $stmt->execute();
        $stmt->bind_result($count_ucp);
        $stmt->fetch();
        $stmt->close();

        if ($count_ucp > 0) {
            echo "<h1>Gagal Membuat Akun</h1>";
            echo "<p>ucp sudah digunakan. Silakan pilih ucp yang lain.</p>";
            $conn->close();
            exit();
        }

        // Memeriksa duplikasi ID Discord
        $sql = "SELECT COUNT(*) FROM playerucp WHERE DiscordID = ?";
        $stmt = $conn->prepare($sql);

        if ($stmt === false) {
            die("Gagal mempersiapkan query: " . $conn->error);
        }

        $stmt->bind_param("s", $DiscordID);
        $stmt->execute();
        $stmt->bind_result($count_DiscordID);
        $stmt->fetch();
        $stmt->close();

        if ($count_DiscordID > 0) {
            echo "<h1>Gagal Membuat Akun</h1>";
            echo "<p>ID DISCORD sudah digunakan. Silakan gunakan ID DISCORD yang lain.</p>";
            $conn->close();
            exit();
        }

        // Menyiapkan SQL
        $sql = "INSERT INTO playerucp (ucp, DiscordID, verifycode) VALUES (?, ?, ?)";
        $stmt = $conn->prepare($sql);

        if ($stmt === false) {
            die("Gagal mempersiapkan query: " . $conn->error);
        }

        // Mengikat parameter
        $stmt->bind_param("sss", $ucp, $DiscordID, $verifycode);

        // Menjalankan statement
        if ($stmt->execute()) {
            echo "<h1>Terima kasih, $ucp!</h1>";
            echo "<p>Akun Anda telah dibuat dengan ID DISCORD: $DiscordID.</p>";
            echo "<p>Silahkan login dengan verifycode: $verifycode.</p>";
        } else {
            echo "<h1>Gagal Membuat Akun</h1>";
            echo "<p>Terjadi kesalahan: " . $stmt->error . "</p>";
        }

        // Menutup statement dan koneksi
        $stmt->close();
        $conn->close();
    } else {
        echo "<h1>Metode Tidak Valid</h1>";
        echo "<p>Formulir harus dikirim menggunakan metode POST.</p>";
    }
    ?>
    
    <!-- Tombol Kembali ke Halaman Buat Akun -->
    <a href="buat-akun.php" class="button">Kembali ke Buat Akun</a>
    <!-- Tombol Kembali ke Halaman Utama -->
    <a href="index.php" class="button">Kembali ke Beranda</a>
</div>

<footer>
    <p>&copy; 2024 Projek Anda. Semua hak cipta dilindungi.</p>
</footer>

</body>
</html>
