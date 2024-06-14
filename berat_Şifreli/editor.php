
<?php
session_start();
$conn = mysqli_connect("localhost", "root", "abc123", "user_management");

if ($_SESSION['user_role'] == 'viewer' || $_SESSION['user_role'] == 'admin') {
    echo "<script>alert('Bu sayfaya erişim izniniz yok.'); window.location.href = 'anasayfa.php';</script>";
    exit;
}

// Kullanıcıları listeleme işlemi
$userResult = mysqli_query($conn, "SELECT * FROM users");
$users = mysqli_fetch_all($userResult, MYSQLI_ASSOC);

// Ürünleri listeleme işlemi
$productResult = mysqli_query($conn, "SELECT * FROM products");
$products = mysqli_fetch_all($productResult, MYSQLI_ASSOC);

// Kullanıcı silme işlemi
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_user'])) {
    $user_id = $_POST['user_id'];
    mysqli_query($conn, "DELETE FROM users WHERE id = $user_id");
    header("Location: editor.php");
    exit;
}

// Kullanıcı güncelleme işlemi
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_user'])) {
    $user_id = $_POST['user_id'];
    $role = $_POST['role'];
    mysqli_query($conn, "UPDATE users SET role = '$role' WHERE id = $user_id");
    header("Location: editor.php");
    exit;
}

// Ürün silme işlemi
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_product'])) {
    $product_id = $_POST['product_id'];
    mysqli_query($conn, "DELETE FROM products WHERE id = $product_id");
    header("Location: editor.php");
    exit;
}

// Ürün güncelleme işlemi
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_product'])) {
    $product_id = $_POST['id'];
    $urun_adi = $_POST['urun_adi'];
    $fiyat = $_POST['fiyat'];
    $stok = $_POST['stok'];
    mysqli_query($conn, "UPDATE products SET urun_adi = '$urun_adi', fiyat = '$fiyat', stok = '$stok' WHERE id = $product_id");
    header("Location: editor.php");
    exit;
}
?>
<!--php
session_start();
require 'config.php';

if ($_SESSION['user_role'] == 'viewer') {
    echo "<script>alert('Bu sayfaya erişim izniniz yok.'); window.location.href = 'anasayfa.php';</script>";
    exit;
  
}
if ($_SESSION['user_role'] == 'admin') {
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
    $stmt = $pdo->prepare("DELETE FROM users WHERE id = ?");
    $stmt->execute([$user_id]);
    header("Location: editor.php");
    exit;
}

// Kullanıcı güncelleme işlemi
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_user'])) {
    $user_id = $_POST['user_id'];
    $role = $_POST['role'];
    $stmt = $pdo->prepare("UPDATE users SET role = ? WHERE id = ?");
    $stmt->execute([$role, $user_id]);
    header("Location: editor.php");
    exit;
}


// Ürün silme işlemi
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_product'])) {
    $product_id = $_POST['product_id'];
    $stmt = $pdo->prepare("DELETE FROM products WHERE id = ?");
    $stmt->execute([$product_id]);
    header("Location: editor.php");
    exit;
}

// Ürün güncelleme işlemi
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_product'])) {
    $product_id = $_POST['product_id'];
    $product_name = $_POST['product_name'];
    $price = $_POST['price'];
    $stock = $_POST['stock'];
    $stmt = $pdo->prepare("UPDATE products SET urun_adi = ?, fiyat = ?, stok = ? WHERE id = ?");
    $stmt->execute([$product_name, $price, $stock, $product_id]);
    header("Location: editor.php");
    exit;
}
-->

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Editör Paneli</title>
    <style>
    /* Genel stil */
    body {
        font-family: Arial, sans-serif;
        margin: 20px;
    }

    table {
        width: 100%;
        border-collapse: collapse;
        margin-bottom: 20px;
    }

    th, td {
        padding: 12px 15px;
        border-bottom: 1px solid #ddd;
        text-align: left;
    }

    th {
        background-color: #00509e;
        color: #fff;
        border-radius: 10px 10px 0 0;
        border: none;
    }

    td {
        background-color: #f1f1f1;
    }

    tr:last-child td {
        border-bottom: none;
    }

    button, input[type="submit"] {
        background-color: #00509e;
        color: #fff;
        border: none;
        padding: 10px 20px;
        border-radius: 25px;
        cursor: pointer;
        transition: background-color 0.3s ease, transform 0.3s ease, box-shadow 0.3s ease;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        font-size: 16px;
    }

    button:hover, input[type="submit"]:hover {
        background-color: #003f7f;
        transform: translateY(-3px);
        box-shadow: 0 6px 12px rgba(0, 0, 0, 0.2);
    }

    button:active, input[type="submit"]:active {
        transform: translateY(1px);
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
    }

    button:focus, input[type="submit"]:focus {
        outline: none;
        box-shadow: 0 0 0 3px rgba(0, 80, 158, 0.4);
    }

    input[type="text"], input[type="email"], input[type="password"] {
        width: calc(100% - 20px);
        padding: 10px;
        margin: 10px 0 20px 0;
        border: 1px solid #ccc;
        border-radius: 25px;
        box-sizing: border-box;
        transition: border-color 0.3s ease;
    }

    input[type="text"]:focus, input[type="email"]:focus, input[type="password"]:focus {
        border-color: #00509e;
        outline: none;
    }
</style>

</head>
<body>
    <h1>Editör Paneli</h1>
    <p>Hoş geldiniz, <?php echo htmlspecialchars($_SESSION['username']); ?>!</p>
    <a href="cikis.php">Çıkış Yap</a>

    <h2>Kullanıcılar</h2>
    <table border="1">
        <tr>
            <th>ID</th>
            <th>Kullanıcı Adı</th>
            <th>Yetki Rolü</th>
            <th>İşlem</th>
        </tr>
        <?php foreach ($users as $user): ?>
            <tr>
                <td><?php echo $user['id']; ?></td>
                <td><?php echo $user['username']; ?></td>
                <td>
                    <form action="" method="post">
                        <input type="hidden" name="user_id" value="<?php echo $user['id']; ?>">
                        <select name="role">
                            <option value="viewer" <?php if ($user['role'] === 'viewer') echo 'selected'; ?>>Viewer</option>
                            <option value="editor" <?php if ($user['role'] === 'editor') echo 'selected'; ?>>Editor</option>
                        </select>
                        <button type="submit" name="update_user">Güncelle</button>
                    </form>
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
            <th>Stok</th>
            <th>İşlem</th>
        </tr>
        <?php foreach ($products as $product): ?>
            <tr>
                <td><?php echo $product['id']; ?></td>
                <td><?php echo $product['urun_adi']; ?></td>
                <td><?php echo $product['fiyat']; ?></td>
                <td><?php echo $product['stok']; ?></td>
                <td>
                    <form action="" method="post">
                        <input type="hidden" name="product_id" value="<?php echo $product['id']; ?>">
                        <button type="submit" name="update_product">Güncelle</button>
                    </form>
                </td>
                <td>
                    <form action="" method="post">
                        <input type="hidden" name="product_id" value="<?php echo $product['id']; ?>">
                        <button type="submit" name="delete_product">Sil</button>
                    </form>
                </td>
            </tr>
        <?php endforeach; ?>
    </table>

</body>
</html>
