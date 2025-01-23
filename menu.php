
<?php
// Veritabanı bağlantısı
$dsn = 'mysql:host=localhost;dbname=menu_uygulama;charset=utf8mb4';
$username = 'root';
$password = '';
$options = [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
];
$db = new PDO($dsn, $username, $password, $options);

// Menü öğelerini çek
$menuItems = $db->query('SELECT * FROM menu')->fetchAll();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Menü</title>
    <style>
        .list{
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
            align-items: center;            
        }
        .list li{
            margin: 10px 15px;
            
        }
        img{ 
            max-width: 100px;
            height: 100px;
            display: block;
        }
    </style>
</head>
<body>
   <div>
        <h1 style="text-align: center;">Menü</h1>
        <ul class="list">
            <?php foreach($menuItems as $category): ?>
            <li>
                <a href="MenuItems.php?id=<?= htmlspecialchars($category['id'])?>">
                <?=htmlspecialchars($category["name"])?>
                </a>

            <a href="MenuItems.php?id=<?= htmlspecialchars($category['id'])?>">
                <img src=" <?=htmlspecialchars($category['image'])?>"alt="<?= htmlspecialchars($category['name']) ?>">
            </a>
        </li>
            <?php endforeach ?>

        </ul>



   </div>
</body>
</html>