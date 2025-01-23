<?php
session_start();

if (!isset($_SESSION['is_admin']) || $_SESSION['is_admin'] !== true) {
    header('Location: login.php');
    exit;
}

// Veritabanı bağlantısı
$dsn = 'mysql:host=localhost;dbname=menu_uygulama;charset=utf8mb4';
$username = 'root'; // Veritabanı kullanıcı adı
$password = ''; // Veritabanı şifresi
$options = [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
];
$db = new PDO($dsn, $username, $password, $options);

$category_id = $_GET['id'] ?? null;
if ($category_id) {
    // Kategori ismini almak için sorgu
    $stmt = $db->prepare('SELECT * FROM menu WHERE id = :category_id');
    $stmt->execute([':category_id' => $category_id]);
    $category = $stmt->fetch();

    if ($category) {
        // Seçilen kategoriye ait ürünleri çek
        $stmt = $db->prepare('SELECT * FROM menu_items WHERE category_id = :category_id');
        $stmt->execute([':category_id' => $category_id]);
        $menuItems = $stmt->fetchAll();
    }
} ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ürün Görüntüleme Paneli</title>
   <style> .list{
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
            <li><a href="itemEdit.php?id=<?php echo $items['id']?>"><?php echo $items["name"]?>
            <img src=" <?php echo htmlspecialchars($items['image'])?>"alt="<?= htmlspecialchars($items['name']) ?>">
            </a>
            <p><?php echo $items['price'] ?> TL</p>
        </li>
            <?php endforeach ?>

        </ul>



   </div>
</body>
<div style="text-align: center;">
    <button><a href="itemAdd.php?id=<?php echo htmlspecialchars($category['id']); ?>">Bu Kategoriye Yeni Ürün Ekle</a></button>
    <button onclick="return confirm('Bu Kategoriyi Silmek İstediğinize Emin Misiniz?');"><a href="categoryDelete.php?id=<?php echo htmlspecialchars($category['id']); ?>">Bu Kategoriyi Sil</a></button>
    <button><a href="admin.php">Kategori Ekleme Paneline Geri Dön</a></button>
    
</div>
</html>