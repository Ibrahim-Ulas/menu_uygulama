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
    <?php include_once ('bs.php'); ?>
   <style> 
        <?php include_once ('css.css'); ?>
        </style>
</head>
<body>
    
<div>
       <h1 style="text-align: center;"><?php echo htmlspecialchars($category['name'])?></h1>
        <ul class="list">
            <?php foreach($menuItems as $items): ?>
            <button>
            <li><a href="itemEdit.php?id=<?php echo $items['id']?>"><?php echo $items["name"]?>
            <img src=" <?php echo htmlspecialchars($items['image'])?>"alt="<?= htmlspecialchars($items['name']) ?>">
            </a>
            <p><?php echo $items['price'] ?> TL</p>
            <p style="border: outset;"><?php echo $items['description'] ?></p>
        </li>
        </button>
            <?php endforeach ?>

        </ul>



   </div>
   <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-kenU1KFdBIe4zVF0s0G1M5b4hcpxyD9F7jL+jjXkk+Q2h455rYXK/7HAuoJl+0I4" crossorigin="anonymous"></script>
</body>
<style>
</style>
<div style="text-align: center;">
    <button type="button" class="btn btn-success"><a href="itemAdd.php?id=<?php echo htmlspecialchars($category['id']); ?>">Bu Kategoriye Yeni Ürün Ekle</a></button>
    <button type="button" class="btn btn-danger" onclick="return confirm('Bu Kategoriyi Silmek İstediğinize Emin Misiniz?');"><a href="categoryDelete.php?id=<?php echo htmlspecialchars($category['id']); ?>">Bu Kategoriyi Sil</a></button>
    <button type="button" class="btn btn-warning"><a href="admin.php">Kategori Ekleme Paneline Geri Dön</a></button>
    
</div>
</html>