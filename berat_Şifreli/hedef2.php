<?php
session_start();

// CSRF token oluşturma veya mevcut token'ı alma
if (!isset($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32)); // Rastgele token oluştur
}
$csrf_token = $_SESSION['csrf_token'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Hedef Sayfa</title>
</head>
<body>
    <h1>Hedef Sayfa</h1>
    <form action="islem.php" method="POST">
        <label for="amount">Amount:</label>
        <input type="text" id="amount" name="amount">
        <!-- CSRF token'ı gizli bir alan olarak form içine ekleme -->
        <input type="hidden" name="csrf_token" value="<?php echo $csrf_token; ?>">
        <input type="submit" value="Submit">
    </form>
</body>
</html>
