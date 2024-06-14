
<?php
$conn = mysqli_connect("localhost", "root", "abc123", "user_management");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $term = $_POST['term'] ?? '';
    $sql = "SELECT * FROM cicekler WHERE isim LIKE '%$term%'";
    $result = mysqli_query($conn, $sql);
    $products = mysqli_fetch_all($result, MYSQLI_ASSOC);
    echo json_encode($products);
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Ürün Ara</title>
    <script>
    function searchProducts() {
        const term = document.getElementById('search').value;
        const xhr = new XMLHttpRequest();
        xhr.open('POST', '', true);
        xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
        xhr.onload = function() {
            if (this.status === 200) {
                const results = JSON.parse(this.responseText);
                let output = '<ul>';
                if (results.length > 0) {
                    results.forEach(product => {
                        output += `<li>${product.isim} - ${product.fiyat}</li>`;
                    });
                } else {
                    output += '<li>Ürün bulunamadı.</li>'; // Hata mesajı
                }
                output += '</ul>';
                document.getElementById('results').innerHTML = output;
            } else {
                document.getElementById('results').innerHTML = '<p>Bir hata oluştu. Lütfen tekrar deneyin.</p>'; // Diğer hata mesajı
            }
        };
        xhr.onerror = function() {
            document.getElementById('results').innerHTML = '<p>Bir hata oluştu. Sunucuya erişilemiyor.</p>'; // Bağlantı hatası mesajı
        };
        xhr.send('term=' + term);
    }
</script>

</head>
<body>
<style>
    body {
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        background-color: #eef2f7;
        margin: 0;
        padding: 0;
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
    input[type="text"], input[type="submit"] {
        width: calc(100% - 24px);
        padding: 12px 10px;
        margin: 15px 0;
        border: 1px solid #ccd6e3;
        border-radius: 10px;
        box-sizing: border-box;
        font-size: 16px;
        transition: border-color 0.3s ease, box-shadow 0.3s ease;
    }
    input[type="text"]:focus {
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
</style>

    <!--<h1>Search Results for "<php echo $_GET['query']; ?>"</h1>-->
    <div class="container">
        <h2>Ürün Ara</h2>
        <form onsubmit="searchProducts(); return false;">
            <input type="text" id="search" placeholder="Ürün adı...">
            <input type="submit" value="Ara">
        </form>
        <div id="results"></div>
    </div>
</body>
</html>

<!--php
// config.php dosyası içeriği (harici bağımlılıklar)
//$pdo = new PDO('mysql:host=localhost;dbname=user_management', 'root', 'abc123');
 $conn = mysqli_connect("localhost", "root", "abc123", "user_management");

// İstemci Tarafı (Kullanıcı Tarayıcısı)
// Kullanıcı Tarayıcısı, HTTP GET/POST isteklerini gönderir.

// Sunucu Tarafı (PHP Sunucusu)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Gelen isteği işler
    $term = $_POST['term'] ?? '';

    // Veritabanına erişir
    $sql="SELECT * FROM products WHERE urun_adi LIKE ?";
    $result = mysqli_query($conn, $sql);
$urunler = mysqli_fetch_all($result, MYSQLI_ASSOC);
   // $stmt = $pdo->prepare("SELECT * FROM products WHERE urun_adi LIKE ?");
    //$stmt->execute(["%$term%"]);
    //$results = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Sonuçları kullanıcıya geri döner
    echo json_encode($results);
    exit; // İşlem tamamlandıktan sonra çıkış yapılır
}

// Veritabanı Tarafı (Veritabanı Sunucusu)
// Veritabanı sunucusu, SQL sorgularını yürütür ve sonuçları PHP sunucusuna döndürür.

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Ürün Ara</title>
    <script>
        function searchProducts() {
            const term = document.getElementById('search').value;
            const xhr = new XMLHttpRequest();
            xhr.open('POST', 'ajax_search.php', true);
            xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
            xhr.onload = function() {
                if (this.status === 200) {
                    const results = JSON.parse(this.responseText);
                    let output = '<ul>';
                    results.forEach(product => {
                        output += `<li>${product.urun_adi} - ${product.fiyat}</li>`;
                    });
                    output += '</ul>';
                    document.getElementById('results').innerHTML = output;
                }
            };
            xhr.send('term=' + term);
        }
    </script>
</head>
<body>
    <style>
        body {
    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    background-color: #f9f9f9;
    margin: 0;
    padding: 0;
}

.container {
    width: 80%;
    max-width: 1200px;
    margin: auto;
    background: #fff;
    padding: 20px;
    border-radius: 10px;
    box-shadow: 0 0 20px rgba(0, 0, 0, 0.1);
}

h2 {
    color: #333;
    margin-top: 0;
}

nav ul {
    list-style: none;
    padding: 0;
    text-align: center;
}

nav ul li {
    display: inline;
    margin-right: 10px;
}

nav ul li a {
    text-decoration: none;
    color: #555;
    padding: 8px 15px;
    border-radius: 5px;
    background-color: #f5f5f5;
    transition: background-color 0.3s;
}

nav ul li a:hover {
    background-color: #e0e0e0;
}

table {
    width: 100%;
    margin-bottom: 20px;
    border-collapse: collapse;
}

table, th, td {
    border: 1px solid #ddd;
    padding: 12px;
}

th {
    background: #f5f5f5;
}

input[type="text"], input[type="password"], input[type="email"], textarea, select {
    width: calc(100% - 24px);
    padding: 10px;
    margin: 10px 0;
    border: 1px solid #ddd;
    border-radius: 5px;
}

input[type="submit"] {
    padding: 12px 20px;
    background: #4caf50;
    border: none;
    color: white;
    cursor: pointer;
    border-radius: 5px;
    transition: background-color 0.3s;
}

input[type="submit"]:hover {
    background: #45a049;
}

    </style>
    <div class="container">
        <h2>Ürün Ara</h2>
        <form onsubmit="searchProducts(); return false;">
            <input type="text" id="search" style="width: 70%;" placeholder="Ürün adı...">
            <input type="submit" value="Ara" style="width: 20%; cursor: pointer;">
        </form>
        <div id="results"></div>  Arama sonuçlarının gösterildiği alan 
    </div>
</body>
</html>
-->