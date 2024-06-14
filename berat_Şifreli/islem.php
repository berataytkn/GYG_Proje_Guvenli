<?php
session_start(); // Session başlatma

// CSRF token oluşturma
if (!isset($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32)); // Rastgele token oluştur
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // CSRF token'ı doğrula
    if (!isset($_SERVER['HTTP_REFERER']) || strpos($_SERVER['HTTP_REFERER'], 'http://localhost') !== 0) {
        die('Invalid referer');
    }
    // Referer kontrolü
    if (!isset($_SERVER['HTTP_REFERER']) || strpos($_SERVER['HTTP_REFERER'], 'http://localhost') !== 0) {
        die('Invalid referer');
    }

    $amount = $_POST['amount'];
    // İsteği işleyin (örneğin, veri tabanına kaydetme veya başka bir işlem yapma)
    echo "İşlem tamamlandı: $amount";
}
?>


