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

// Yeni menü öğesi ekleme
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'];
    if ($action === 'add_menu_category') {
        $name = $_POST['name'];
    if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
        $uploadDir = 'uploads/'; // Resimlerin yükleneceği klasör
        $fileName = basename($_FILES['image']['name']);
        $targetPath = $uploadDir . $fileName;

        // Dosyayı belirtilen klasöre taşı
        if (move_uploaded_file($_FILES['image']['tmp_name'], $targetPath)) {

    try {$stmt = $db->prepare('INSERT INTO menu (name, image) VALUES (:name, :image)');
    $stmt->execute([':name' => $name, ':image' => $targetPath]);
    $success=true;
    } catch (Exception $e) {
        $success=false;
        $error=$e->getMessage();
    }
    if($success){
        header('Location: admin.php');
        exit;
    
}
else{
    $error='Resim eklenirken bir hata oluştu.';
}
}else{
    $error='Resim yüklenirken bir hata oluştu.';
}
}
}
}

 

// Mevcut menü öğelerini çekme
$categories = $db->query('SELECT * FROM menu')->fetchAll();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kategori Ekleme Paneli</title>
    <?php include_once ('bs.php'); ?>
    <style>
   <?php include_once ('css.css'); ?>
    </style>
</head>
<body>
    <h1 class="baslik">Kategori Ekleme</h1>
    
    <!-- Yeni Menü Öğesi Ekleme Formu -->
    <form action="" method="POST" enctype="multipart/form-data">
        <input type="hidden" name="action" value="add_menu_category">
        <label for="name">Kategori Adı:</label>
        <input type="text" name="name" id="name" required>
        <br>
        <label for="image">Resim:</label>
        <input type="file" name="image" id="image" required>
        <br>
        <button type="submit">Ekle</button>
    </form>

    <!-- Mevcut Menü Öğeleri -->
    <h2 class="baslik">Mevcut Menü</h2>
    <ul class="list" style="display: inline-block;">
        <?php foreach ($categories as $category): ?>
            <button>
                <li>
                <a href="itemList.php?id=<?php echo htmlspecialchars($category['id']); ?>">
                    <?php echo htmlspecialchars($category['name']); ?>
                    <br>
                <img src="<?php echo htmlspecialchars($category['image']); ?>" width="50">
                </a>
            </li>
            </button>

        <?php endforeach; ?>
    </ul>
    

</body>
<br>
<div style="text-align: center;">
<button><a href="logout.php">Yönetim Oturumunu Kapat</a></button>
</div>
</html>
