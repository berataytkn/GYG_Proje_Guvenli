<?php
session_start();

function secureInput($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["complaint"])) {
    $complaint = secureInput($_POST["complaint"]);
    $_SESSION['message'] = "Şikayetiniz başarıyla alındı: " . $complaint;
    header("Location: " . $_SERVER['PHP_SELF']);
    exit();
}
?>
<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <title>Şikayet Formu</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #eef2f7;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
        }
        .container {
            width: 80%;
            max-width: 1200px;
            margin: 40px auto;
            background: #fff;
            padding: 30px;
            border-radius: 15px;
            box-shadow: 0 0 30px rgba(0, 0, 0, 0.1);
        }
        h2 {
            color: #00509e;
            margin-top: 0;
            font-size: 28px;
            border-bottom: 2px solid #00509e;
            padding-bottom: 10px;
        }
        input[type="text"], input[type="submit"], textarea {
            width: calc(100% - 24px);
            padding: 12px 10px;
            margin: 15px 0;
            border: 1px solid #ccd6e3;
            border-radius: 10px;
            box-sizing: border-box;
            font-size: 16px;
            transition: border-color 0.3s ease, box-shadow 0.3s ease;
        }
        input[type="text"]:focus, textarea:focus {
            border-color: #00509e;
            box-shadow: 0 0 5px rgba(0, 80, 158, 0.3);
            outline: none;
        }
        input[type="submit"] {
            background: #007bff;
            color: white;
            border: none;
            cursor: pointer;
            transition: background-color 0.3s, transform 0.3s, box-shadow 0.3s;
            font-size: 16px;
            border-radius: 10px;
            padding: 12px 20px;
        }
        input[type="submit"]:hover {
            background: #00509e;
            transform: translateY(-3px);
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
        }
        input[type="submit"]:active {
            background: #003f7f;
            transform: translateY(1px);
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }
        .message {
            margin-top: 20px;
            padding: 15px;
            background-color: #d4edda;
            border: 1px solid #c3e6cb;
            color: #155724;
            border-radius: 5px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Kullanıcı Şikayet Formu</h2>
        <?php
        if (isset($_SESSION['message'])) {
            echo "<div class='message'>" . $_SESSION['message'] . "</div>";
            unset($_SESSION['message']);
        }
        ?>
        <form method="post" action="">
            <label for="complaint">Şikayetiniz:</label><br>
            <textarea id="complaint" name="complaint" rows="4" cols="50" required></textarea><br>
            <input type="submit" value="Şikayet Gönder">
        </form>
    </div>
</body>
</html>
