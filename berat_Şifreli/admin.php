
<?php
session_start();
$conn = mysqli_connect("localhost", "root", "abc123", "user_management");

if ($_SESSION['user_role'] == 'viewer' || $_SESSION['user_role'] == 'editor') {
    echo "<script>alert('Bu sayfaya erişim izniniz yok.'); window.location.href = 'anasayfa.php';</script>";
    exit;
}

// Oturum süresini hesaplama fonksiyonu
function calculateSessionDuration($login_time, $logout_time) {
    $session_duration = $logout_time - $login_time;
    $hours = floor($session_duration / 3600);
    $minutes = floor(($session_duration % 3600) / 60);
    $seconds = $session_duration % 60;

    return array($hours, $minutes, $seconds);
}


// Kullanıcıları listeleme işlemi
$userResult = mysqli_query($conn, "SELECT * FROM users");
$users = mysqli_fetch_all($userResult, MYSQLI_ASSOC);

// Ürünleri listeleme işlemi
$ciceklerResult = mysqli_query($conn, "SELECT * FROM cicekler");
$cicekler = mysqli_fetch_all($ciceklerResult, MYSQLI_ASSOC);

// Kullanıcı silme işlemi
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_user'])) {
    $user_id = $_POST['user_id'];
    mysqli_query($conn, "DELETE FROM users WHERE id = $user_id");
    header("Location: admin.php");
    exit;
}

// Kullanıcı güncelleme işlemi
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_user'])) {
    $user_id = $_POST['user_id'];
    $role = $_POST['role'];
    mysqli_query($conn, "UPDATE users SET role = '$role' WHERE id = $user_id");
    header("Location: admin.php");
    exit;
}

// Ürün silme işlemi
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_cicekler'])) {
    $cicekler_id = $_POST['cicekler_id'];
    mysqli_query($conn, "DELETE FROM cicekler WHERE id = $cicekler_id");
    header("Location: admin.php");
    exit;
}

// Ürün güncelleme işlemi
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_cicekler'])) {
    $cicekler_id = $_POST['id'];
    $isim = $_POST['isim'];
    $fiyat = $_POST['fiyat'];
    
    mysqli_query($conn, "UPDATE cicekler SET isim = '$isim', fiyat = '$fiyat',  WHERE id = $cicekler_id");
    header("Location: admin.php");
    exit;
}


?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Yönetici Paneli</title>
    <style>
    /* Genel Stiller */
    body {
        font-family: 'Arial', sans-serif;
        background-color: #f1f9ff;
        margin: 0;
        padding: 20px;
    }

    table {
        width: 100%;
        border-collapse: separate;
        border-spacing: 0;
        border-radius: 12px;
        overflow: hidden;
        box-shadow: 0 4px 8px rgba(0, 123, 255, 0.2);
        margin-bottom: 20px;
    }

    th, td {
        padding: 12px 15px;
        border-bottom: 1px solid #ddd;
    }

    th {
        background-color: #007bff;
        color: #fff;
        text-align: left;
        border-bottom: 0;
    }

    td {
        background-color: #ffffff;
    }

    td:last-child {
        border-bottom: none;
    }

    button, input[type="submit"], button[type="submit"] {
        background-color: #007bff;
        color: #fff;
        border: none;
        padding: 10px 20px;
        border-radius: 25px;
        cursor: pointer;
        transition: background-color 0.3s ease, transform 0.3s ease;
        box-shadow: 0 4px 6px rgba(0, 123, 255, 0.2);
    }

    button:hover, input[type="submit"]:hover, button[type="submit"]:hover {
        background-color: #0056b3;
        transform: translateY(-3px);
    }

    button:active, input[type="submit"]:active, button[type="submit"]:active {
        transform: translateY(1px);
    }

    /* Ekstra Stiller */
    table, th, td {
        border-radius: 12px;
    }

    th:first-child {
        border-top-left-radius: 12px;
    }

    th:last-child {
        border-top-right-radius: 12px;
    }

    td:first-child {
        border-bottom-left-radius: 12px;
    }

    td:last-child {
        border-bottom-right-radius: 12px;
    }
</style>

</head>
<body>
    <h1>Yönetici Paneli</h1>
    <p>Hoş geldiniz, <?php echo $_SESSION['username']; ?>!</p>
    <a href="anasayfa.php">Çıkış Yap</a>

    <h2>Kullanıcılar</h2>
    <table border="1">
        <tr>
            <th>ID</th>
            <th>Kullanıcı Adı</th>
            <th>Şifre</th>
            <th>Yaş</th>
            <th>E-posta</th>
            <th>Meslek</th>
            <th>Hobiler</th>
            <th>Yetki Rolü</th>
            <th>Oturum Süresi</th>
            <th>İşlem</th>
            </tr>
       
       
        <?php foreach ($users as $user): ?>
            <tr>
           
                <td><?php echo $user['id']; ?></td>
                <td><?php echo $user['username']; ?></td>
                <td><?php echo $user['password']; ?></td>
                <td><?php echo $user['yas']; ?></td>
                <td><?php echo $user['email']; ?></td>
                <td><?php echo $user['meslek']; ?></td>
                <td><?php echo $user['hobiler']; ?></td>
              
                <td>
                    <form action="" method="post">
                        <input type="hidden" name="user_id" value="<?php echo $user['id']; ?>">
                        <select name="role">
                            <option value="admin" <?php if ($user['role'] === 'admin') echo 'selected'; ?>>Admin</option>
                            <option value="editor" <?php if ($user['role'] === 'editor') echo 'selected'; ?>>Editor</option>
                        </select>
                        <button type="submit" name="update_user">Güncelle</button>
                    </form>
                </td>
                <td>
                <?php
                   // Oturumu kapalı olan kullanıcıların oturum süresini hesapla ve yazdır
if ($user['logout_time'] != null) {
    $login_time = strtotime($user['login_time']);
    $logout_time = strtotime($user['logout_time']);
    $session_duration = calculateSessionDuration($login_time, $logout_time);
    printf("%02d:%02d:%02d", $session_duration[0], $session_duration[1], $session_duration[2]);
} else {
    echo "Oturum süresi dolmuş.";
}

                    ?>


                </td>
                <td>
                    <form action="" method="post">
                        <input type="hidden" name="user_id" value="<?php echo $user['id']; ?>">
                        <button type="submit" name="delete_user">Sil</button>
                    </form>
                </td>
            </tr>
        <?php endforeach; ?>
    </table>

    <h2>Ürünler</h2>
    <table border="1">
        <tr>
            <th>ID</th>
            <th>Ürün Adı</th>
            <th>Fiyat</th>
            <th>renk</th>
            <th>işlem</th>
            
        </tr>
        <?php foreach ($cicekler as $cicekler): ?>
            <tr>
                <td><?php echo $cicekler['id']; ?></td>
                <td><?php echo $cicekler['isim']; ?></td>
                <td><?php echo $cicekler['fiyat']; ?></td>
                <td><?php echo $cicekler['renk']; ?></td>
                
                
                
                <td>
                    <form action="" method="post">
                        <input type="hidden" name="cicekler_id" value="<?php echo $cicekler['id']; ?>">
                        <button type="submit" name="delete_cicekler">Sil</button>
                    </form>
                </td>
            </tr>
        <?php endforeach; ?>
    </table>
    <script>
        setInterval(function() {
            window.location.reload();
        }, 6000); // Sayfayı her saniyede bir yenile
    </script>
</body>
</html>

<!--php
session_start();
$conn = mysqli_connect("localhost", "root", "abc123", "user_management");
//require 'config.php';

if ($_SESSION['user_role'] == 'viewer') {
    echo "<script>alert('Bu sayfaya erişim izniniz yok.'); window.location.href = 'anasayfa.php';</script>";
    exit;
  
}
if ($_SESSION['user_role'] == 'editor') {
    echo "<script>alert('Bu sayfaya erişim izniniz yok.'); window.location.href = 'anasayfa.php';</script>";
    exit;
}  

// Kullanıcıları listeleme işlemi
$stmt = $pdo->query('SELECT * FROM users');
$users = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Ürünleri listeleme işlemi
$stmt = $pdo->query('SELECT * FROM products');
$products = $stmt->fetchAll(PDO::FETCH_ASSOC);


// Kullanıcı silme işlemi
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_user'])) {
    $user_id = $_POST['user_id'];
    // Kullanıcı silme sorgusu
    $stmt = $pdo->prepare("DELETE FROM users WHERE id = ?");
    $stmt->execute([$user_id]);
    header("Location: admin.php");
    exit;
}

// Kullanıcı güncelleme işlemi
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_user'])) {
    $user_id = $_POST['user_id'];
    $role = $_POST['role'];
    // Kullanıcı güncelleme sorgusu
    $stmt = $pdo->prepare("UPDATE users SET role = ? WHERE id = ?");
    $stmt->execute([$role, $user_id]);
    header("Location: admin.php");
    exit;
}


// Ürün silme işlemi
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_product'])) {
    $product_id = $_POST['product_id'];
    // Ürün silme sorgusu
    $stmt = $pdo->prepare("DELETE FROM products WHERE id = ?");
    $stmt->execute([$product_id]);
    header("Location: admin.php");
    exit;
}

// Ürün güncelleme işlemi
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_product'])) {
    $product_id = $_POST['id'];
    $urun_adi = $_POST['urun_adi'];
    $fiyat = $_POST['fiyat'];
    $stok = $_POST['stok'];
    // Ürün güncelleme sorgusu
    $stmt = $pdo->prepare("UPDATE products SET urun_adi = ?, fiyat = ?, stok = ? WHERE id = ?");
    $stmt->execute([$urun_adi, $fiyat, $stok, $product_id]);
    header("Location: admin.php");
    exit;
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Yönetici Paneli</title>
    <style>
  th, td {
            padding: 8px 10px;
            border-bottom: 1px solid #ddd;
        }

        th {
            background-color: #ff6f61;
            color: #fff;
        }

        td {
            background-color: #f9f9f9;
        }

        button {
            background-color: #ff6f61;
            color: #fff;
            border: none;
            padding: 6px 12px;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s ease, transform 0.3s ease;
        }

        button:hover {
            background-color: #ff5444;
            transform: translateY(-3px);
        }

        input[type="submit"], button[type="submit"] {
            background-color: #ff6f61;
            color: #fff;
            border: none;
            padding: 8px 16px;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s ease, transform 0.3s ease;
        }

        input[type="submit"]:hover, button[type="submit"]:hover {
            background-color: #ff5444;
            transform: translateY(-3px);
        }
        </style>
</head>
<body>
    <h1>Yönetici Paneli</h1>
    <p>Hoş geldiniz, <php echo $_SESSION['username']; ?>!</p>
    <a href="anasayfa.php">Çıkış Yap</a>

    <h2>Kullanıcılar</h2>
    <table border="1">
        <tr>
            <th>ID</th>
            <th>Kullanıcı Adı</th>
            <th>Şifre</th>
            <th>Yaş</th>
            <th>E-posta</th>
            <th>Meslek</th>
            <th>Hobiler</th>
            <th>Yetki Rolü</th>
            <th>İşlem</th>
        </tr>
        <php foreach ($users as $user): ?>
            <tr>
    
                <td><php echo $user['id']; ?></td>
                <td><php echo $user['username']; ?></td>
                <td><php echo $user['password']; ?></td>
                <td><php echo $user['yas']; ?></td>
                <td><php echo $user['email']; ?></td>
                <td><php echo $user['meslek']; ?></td>
                <td><php echo $user['hobiler']; ?></td>
                
                <td>
                    <form action="" method="post">
                        <input type="hidden" name="user_id" value="<php echo $user['id']; ?>">
                        <select name="role">
                            <option value="admin" <php if ($user['role'] === 'admin') echo 'selected'; ?>>Admin</option>
                            <option value="editor" <php if ($user['role'] === 'editor') echo 'selected'; ?>>Editor</option>
                        </select>
                        <button type="submit" name="update_user">Güncelle</button>
                    </form>
                </td>
                <td>
                    <form action="" method="post">
                        <input type="hidden" name="user_id" value="<php echo $user['id']; ?>">
                        <button type="submit" name="delete_user">Sil</button>
                    </form>
                </td>
            </tr>
        <php endforeach; ?>
    </table>

    <h2>Ürünler</h2>
    <table border="1">
        <tr>
            <th>ID</th>
            <th>Ürün Adı</th>
            <th>Fiyat</th>
            <th>Kategori</th>
            <th>Stok</th>
            <th>Açıklama</th>
            <th>Eklenme tarihi</th>
            <th>İşlem</th>
        </tr>
        <php foreach ($products as $product): ?>
            <tr>
                <td><php echo htmlspecialchars($product['id']); ?></td>
                <td><php echo htmlspecialchars($product['urun_adi']); ?></td>
                <td><php echo htmlspecialchars($product['fiyat']); ?></td>
                <td><php echo htmlspecialchars($product['kategori']); ?></td>
                <td><php echo htmlspecialchars($product['stok']); ?></td>
                <td><php echo htmlspecialchars($product['aciklama']); ?></td>
                <td><php echo htmlspecialchars($product['eklenme_tarihi']); ?></td>

                <td>
                    <form action="" method="post">
                        <input type="hidden" name="product_id" value="<php echo $product['id']; ?>">
                        <select name="fiyat">
                            
                    
                        </select>
                        <button type="submit" name="update_product">Güncelle</button>
                    </form>
                </td>
                <td>
                    <button onclick="showUpdateForm(<php echo htmlspecialchars(json_encode($product)); ?>)">Güncelle</button>
                </td>
                <td>
                    <form action="" method="post">
                        <input type="hidden" name="product_id" value="<php echo htmlspecialchars($product['id']); ?>">
                        <button type="submit" name="delete_product">Sil</button>
                    </form>
                </td>
            </tr>
        <php endforeach; ?>
    </table>

    <div id="updateForm" style="display:none;">
        <h2>Ürün Güncelle</h2>
        <form action="" method="post">
            <input type="hidden" name="product_id" id="update_product_id">
            <label for="urun_adi">Ürün Adı:</label>
            <input type="text" name="urun_adi" id="update_urun_adi"><br>
            <label for="fiyat">Fiyat:</label>
            <input type="text" name="fiyat" id="update_fiyat"><br>
            <label for="stok">Stok:</label>
            <input type="text" name="stok" id="update_stok"><br>
    
            <button type="submit" name="update_product">Güncelle</button>
        </form>
    </div>

    <script>
        function showUpdateForm(products) {
            document.getElementById('update_product_id').value = products.id;
            document.getElementById('update_urun_adi').value = products.urun_adi;
            document.getElementById('update_fiyat').value = products.fiyat;
            
            document.getElementById('update_stok').value = products.stok;
            
           
            document.getElementById('updateForm').style.display = 'block';
        }
    </script>
</body>
</html>
    -->


  
       