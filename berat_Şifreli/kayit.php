<?php
// Veritabanı bağlantısı
$conn = mysqli_connect("localhost", "root", "abc123", "user_management");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];
    $role = $_POST['role'];
    $email = $_POST['email'];
    $yas = $_POST['yas'];
    $meslek = $_POST['meslek'];
    $hobiler = $_POST['hobiler'];

    // Aynı rolde başka kullanıcı olup olmadığını kontrol et
    $result = mysqli_query($conn, "SELECT COUNT(*) FROM users WHERE role = '$role'");
    $count = mysqli_fetch_array($result)[0];

    // Eğer admin veya editor ise ve o rolde zaten bir kullanıcı varsa hata mesajı göster
    if (($role == 'admin' || $role == 'editor') && $count > 0) {
        echo "Sadece bir $role kullanıcısı olabilir.";
    } else {
        // Kullanıcıyı ekle
        $query = "INSERT INTO users (username, password, role, email, yas, meslek, hobiler) VALUES ('$username', '$password', '$role', '$email', '$yas', '$meslek', '$hobiler')";
        if (mysqli_query($conn, $query)) {
            echo "Kullanıcı başarıyla eklendi.";
        } else {
            echo "Kullanıcı ekleme hatası.";
        }
    }
}
?>

<!--php
 $conn = mysqli_connect("localhost", "root", "abc123", "user_management");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];
    $role = $_POST['role'];
    $email = $_POST['email'];
    $yas = $_POST['yas'];
    $meslek = $_POST['meslek'];
    $hobiler = $_POST['hobiler'];
    // Veritabanında aynı rolde kaç kullanıcı olduğunu kontrol et
    $sql="SELECT COUNT(*) FROM users WHERE role = ?";
  

    // Eğer admin veya editor ise ve o rolde zaten bir kullanıcı varsa hata mesajı göster
    if (($role == 'admin' || $role == 'editor') && $count > 0) {
        echo "Sadece bir $role kullanıcısı olabilir.";
    } else {

        $sql = "INSERT INTO products (username, password, role, email, yas, meslek, hobiler) VALUES(?, ?, ?, ?, ?, ?, ?) ";
        if (mysqli_query($conn, $sql)) {
            echo "Ürün başarıyla eklendi!";
        } else {
            echo "Hata: " . mysqli_error($conn);
        }
        // Kullanıcıyı ekle
        //$conn="INSERT INTO users (username, password, role, email, yas, meslek, hobiler) VALUES (?, ?, ?, ?, ?, ?, ?)";
        //$stmt->bind_param("sssssss", $username, $password, $role, $email, $yas, $meslek, $hobiler);
        //if ($stmt->execute()) {
        //    echo "Kullanıcı başarıyla eklendi.";
        //} else {
         //   echo "Kullanıcı ekleme hatası.";
        //}
        //$stmt->close();
    }
 // Rol kontrolü yap
 //$stmt = $pdo->prepare("SELECT COUNT(*) FROM users WHERE role = ?");
 //$stmt->execute([$role]);
 //$count = $stmt->fetchColumn();
 //if (($role == 'admin' || $role == 'editor') && $count > 0) {
   // echo "Sadece bir $role kullanıcısı olabilir.";
//} else {
    // Kullanıcıyı ekle
  //  $stmt = $pdo->prepare("INSERT INTO users (username, password, role, email, yas, meslek, hobiler) VALUES (?, ?, ?, ?, ?, ?, ?)");
    //if ($stmt->execute([$username, $password, $role, $email, $yas, $meslek, $hobiler])) {
      //  echo "Kullanıcı başarıyla eklendi.";
    //} else {
      //  echo "Kullanıcı ekleme hatası.";
    //}
}

-->

<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <title>Hemen Kayıt Ol!</title>
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
    background-color: rgba(255, 255, 255, 0.8);
    padding: 30px;
    border-radius: 15px;
    box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
    text-align: center;
    max-width: 400px;
    width: 100%;
}

h2 {
    margin-bottom: 20px;
    color: #ff4081;
}

label {
    display: block;
    margin: 15px 0 5px;
    text-align: left;
    color: #6a11cb;
}

input {
    width: 100%;
    padding: 10px;
    border: 2px solid #6a11cb;
    border-radius: 5px;
    margin-bottom: 15px;
    box-sizing: border-box;
}

button {
    width: 100%;
    padding: 10px;
    background-color: #6a11cb;
    border: none;
    border-radius: 5px;
    color: white;
    font-size: 16px;
    cursor: pointer;
    transition: background-color 0.3s, transform 0.3s;
}

button:hover {
    background-color: #8e44ad;
    transform: scale(1.05);
}

nav ul {
    list-style-type: none;
    padding: 0;
    margin-top: 20px;
}

nav ul li {
    display: inline;
    margin: 0 10px;
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
    margin-top: 10px;
}

    </style>
</head>
<body>

<section id="content" class="container">
    <h2>Hesap Oluştur</h2>
    <form action="kayit.php" method="post">
        <div class="form-group">
            <input name='username' type="text" placeholder="Kullanıcı Adı" required class="form-control">
        </div>
        <div class="form-group">
            <input name='password' type="password" placeholder="Şifre" required class="form-control">
        </div>
        <div class="form-group">
            <input name='email' type="email" placeholder="E-posta" required class="form-control">
        </div>
        <div class="form-group">
            <input name='yas' type="text" placeholder="Yaş" class="form-control">
        </div>
        <div class="form-group">
            <input name='meslek' type="text" placeholder="Meslek" class="form-control">
        </div>
        <div class="form-group">
            <textarea name='hobiler' placeholder="Hobiler" class="form-control"></textarea>
        </div>
        <div class="form-group">
            <label for="role">Rol:</label>
            <select name="role" id="role">
                <option value="admin">Admin</option>
                <option value="editor">Editor</option>
                <option value="viewer">Viewer</option>
            </select>
        </div>
        <button type="submit" class="btn btn-lg btn-primary btn-block">Kayıt ol</button>
    </form>
    <div class="line line-dashed"></div>
    <p class="text-muted text-center"><small>Zaten hesabınız var mı?</small></p>
    <a href="giris.php" class="btn btn-lg btn-default btn-block">Giriş yap</a>
</section>


</body>
</html>