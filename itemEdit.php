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

// Ürün ID'sini al
$item_id = $_GET['id'] ?? null;

if (!$item_id) {
    die('Geçersiz ürün ID.');
}

// Mevcut ürün bilgilerini al
$stmt = $db->prepare('SELECT * FROM menu_items WHERE id = :item_id');
$stmt->execute([':item_id' => $item_id]);
$item = $stmt->fetch();

if (!$item) {
    die('Ürün bulunamadı.');
}

// Güncelleme işlemi
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'];
    $price = $_POST['price'];
    $description = $_POST['description'];
    $image_path = $item['image']; // Varsayılan olarak eski resim yolu

    // Yeni bir resim yüklendi mi?
    if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
        $uploadDir = 'uploads/';
        $fileName = basename($_FILES['image']['name']);
        $targetPath = $uploadDir . $fileName;

        if (move_uploaded_file($_FILES['image']['tmp_name'], $targetPath)) {
            $image_path = $targetPath; // Yeni resim yolunu güncelle
        }
    }

    // Ürünü güncelle
    $stmt = $db->prepare('UPDATE menu_items SET name = :name, price = :price, description = :description, image = :image WHERE id = :item_id');
    $stmt->execute([
        ':name' => $name,
        ':price' => $price,
        ':description' => $description,
        ':image' => $image_path,
        ':item_id' => $item_id
    ]);

    // Başarıyla güncellendikten sonra yönlendirme
    header("Location: itemAdd.php?id={$item['category_id']}");
    exit;
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ürün Güncelleme Paneli</title>
    <?php include_once('bs.php'); ?>
    <style>
        <?php include_once('edit.css'); ?>
    </style>
</head>

<body>
    <h1 style="text-align: center;">Ürün Güncelleme</h1>
    <form action="" method="POST" enctype="multipart/form-data">
        <label for="name">Ürün adı:</label>
        <input type="text" name="name" id="name" value="<?php echo htmlspecialchars($item['name']); ?>" required>
        <br>
        <label for="price">Fiyat:</label>
        <input type="text" name="price" id="price" value="<?php echo htmlspecialchars($item['price']); ?>" required>
        <br>
        <label for="description">Açıklama:</label>
        <textarea name="description" id="description" required><?php echo htmlspecialchars($item['description']); ?></textarea>
        <br>
        <label for="image">Resim:</label>
        <img src="<?php echo htmlspecialchars($item['image']); ?>" alt="Mevcut Resim">
        <input type="file" name="image" id="image">
        <br>
        <button type="submit">Güncelle</button>
        <button onclick="return confirm('Bu ürünü silmek istediğinize emin misiniz?');"><a type="submit" href="itemDelete.php?id=<?php echo htmlspecialchars($item_id); ?>">Sil</a></button>
    </form>
    <div style="text-align: center; margin-top: 20px;">
        <button><a href="itemList.php?id=<?php echo htmlspecialchars($item['category_id']); ?>">Geri Dön</a></button>

    </div>
</body>

</html>