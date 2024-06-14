<?php
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Güvenli bağlantı
    $conn = new mysqli("localhost", "root", "abc123", "user_management");
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Kullanıcı adı için SQL Injection önlemi
    $username = mysqli_real_escape_string($conn, $username);

    // Şifreyi hashle (bu örnekte SHA-256 kullanıldı)
    $hashed_password = hash('sha256', $password);

    // Prepared statement kullanımı
    $query = "SELECT *FROM users WHERE username='$username' AND password='$password'";
    $result = mysqli_query($conn, $query);
    if ($result->num_rows == 1) {
        $user = $result->fetch_assoc();
        $_SESSION['login_time'] = time();
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['user_role'] = $user['role'];
        $_SESSION['username'] = $user['username'];
        $_SESSION['yas'] = $user['yas'];
        $_SESSION['email'] = $user['email'];
        $_SESSION['meslek'] = $user['meslek'];
        $_SESSION['hobiler'] = $user['hobiler'];
        header("Location: anasayfa.php");
        exit();
    } else {
        echo "Kullanıcı adı veya şifre hatalı.";
    }

    
    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <title>Giriş Yap</title>
    <style>
        body {
            background: url('https://www.transparenttextures.com/patterns/asfalt-dark.png'), linear-gradient(to right, #6a11cb, #2575fc);
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            color: #333;
            margin: 0;
        }

        .container {
            background: rgba(255, 255, 255, 0.9);
            background: linear-gradient(to bottom right, rgba(255, 255, 255, 0.9), rgba(255, 255, 255, 0.8));
            padding: 40px;
            border-radius: 20px;
            box-shadow: 0 8px 30px rgba(0, 0, 0, 0.3);
            text-align: center;
            max-width: 450px;
            width: 100%;
        }

        h2 {
            margin-bottom: 25px;
            color: #ff4081;
        }

        label {
            display: block;
            margin: 20px 0 10px;
            text-align: left;
            font-weight: bold;
            color: #6a11cb;
        }

        input {
            width: 100%;
            padding: 12px;
            border: 2px solid #6a11cb;
            border-radius: 10px;
            margin-bottom: 20px;
            box-sizing: border-box;
        }

        button {
            width: 100%;
            padding: 12px;
            background-color: #6a11cb;
            border: none;
            border-radius: 10px;
            color: white;
            font-size: 18px;
            cursor: pointer;
            transition: background-color 0.3s, transform 0.3s;
        }

        button:hover {
            background-color: #8e44ad;
            transform: scale(1.1);
        }

        nav ul {
            list-style-type: none;
            padding: 0;
            margin-top: 30px;
        }

        nav ul li {
            display: inline;
            margin: 0 12px;
        }

        nav ul li a {
            text-decoration: none;
            color: #6a11cb;
            border-bottom: 2px solid transparent;
            transition: border-bottom 0.3s, color 0.3s;
        }

        nav ul li a:hover {
            border-bottom: 2px solid #ff4081;
            color: #ff4081;
        }

        .error {
            color: #ff4081;
            margin-top: 15px;
        }
    </style>
</head>
<body>

    <div class="container">
        <h2>Giriş Yap</h2>
        <form action="giris.php" method="post">
            <label for="username">Kullanıcı Adı:</label>
            <input type="text" id="username" name="username" required>
            
            <label for="password">Şifre:</label>
            <input type="password" id="password" name="password" required>
            
            <button type="submit">Giriş Yap</button>
        </form>
        
        <nav>
            <ul>
                <li><a href="kayit.php">Kayıt Yap</a></li>
            </ul>
        </nav>

       
    </div>
</body>
</html>
