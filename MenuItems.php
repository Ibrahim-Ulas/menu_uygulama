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

$category_id=$_GET['id'] ?? null;

if ($category_id) {
    $stmt = $db->prepare('SELECT name FROM menu WHERE id = :category_id');
    $stmt->execute([':category_id' => $category_id]);
    $category = $stmt->fetch();

    // Kategoriye ait menü öğelerini çek
    $menuItems = $db->prepare('SELECT * FROM menu_items WHERE category_id = :category_id');
    $menuItems->execute([':category_id' => $category_id]);
    $menuItems = $menuItems->fetchAll();
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo ($category['name']);?></title>
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
        <h1 style="text-align: center;"><?php echo htmlspecialchars($category['name'])?></h1>
        <ul class="list">
            <?php foreach($menuItems as $items): ?>
            <li><?=$items["name"]?></a>
            <img src=" <?=htmlspecialchars($items['image'])?>"alt="<?= htmlspecialchars($items['name']) ?>">
            <p><?= $items['price'] ?> TL</p>
            <p style="border: outset;"><?= $items['description']?></p>
        </li>
            <?php endforeach ?>

        </ul>



   </div>
</body>
<div style="text-align: center;">
<a href="menu.php">Kategorilere Geri Dön</a>
</div>
</html>