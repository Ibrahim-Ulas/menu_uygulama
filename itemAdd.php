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
}
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'];
    $category_id = $_POST['category_id'];
    $price = $_POST['price'];
    $description = $_POST['description'];



    if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
        $uploadDir = 'uploads/'; // Resimlerin yükleneceği klasör
        $fileName = basename($_FILES['image']['name']);
        $targetPath = $uploadDir . $fileName;

        // Dosyayı belirtilen klasöre taşı
        if (move_uploaded_file($_FILES['image']['tmp_name'], $targetPath)) {

            try {
                $stmt = $db->prepare('INSERT INTO menu_items (category_id, name, image, price, description) 
                                 VALUES (:category_id, :name, :image, :price, :description)');
                $stmt->execute([
                    ':category_id' => $category_id,
                    ':name' => $name,
                    ':image' => $targetPath,
                    ':price' => $price,
                    ':description' => $description,

                ]);
                $success = true;
            } catch (Exception $e) {
                $success = false;
                $error = $e->getMessage();
            }
            if ($success) {
                header("Location:itemAdd.php?id=$category_id");
                exit;
            } else {
                $error = 'Resim eklenirken bir hata oluştu.';
            }
        }
    }
}

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ürün Ekleme Paneli</title>
    <?php include_once('bs.php'); ?>
    <style>
        <?php include_once('edit.css'); ?>
    </style>
</head>

<body>
    <div>
        <h1 class="baslik"><?php echo htmlspecialchars($category['name']) ?></h1>
        <ul>
            <?php foreach ($menuItems as $items): ?>
                <li><a href="itemEdit.php?id=<?php echo $items['id'] ?>"><?php echo $items["name"] ?>
                        <img src=" <?php echo htmlspecialchars($items['image']) ?>" alt="<?= htmlspecialchars($items['name']) ?>">
                    </a>
                    <p><?php echo $items['price'] ?> TL</p>
                </li>
            <?php endforeach ?>

        </ul>



    </div>
    <div style="text-align: center;">
        <form action="" method="POST" enctype="multipart/form-data">
            <input type="hidden" name="category_id" value="<?php echo htmlspecialchars($category_id); ?>">
            <label for="name">Ürün adı:</label>
            <input type="text" name="name" id="name" required>
            <br>
            <label for="image">Resim:</label>
            <input type="file" name="image" id="image" required>
            <br>
            <label for="price">Fiyat:</label>
            <input type="text" name="price" id="price" required>
            <br>
            <label for="description">İçerik:</label>
            <input type="text" name="description" id="description" required>
            <button style="color:white;"type="submit">Ekle</button>
        </form>
    </div>
</body>
<div style="text-align: center;">
    <button><a href="admin.php">Kategori Ekleme Paneline Geri Dön</a></button>
    <button><a href="itemList.php?id=<?php echo htmlspecialchars($category['id']); ?>">Ürün Görüntüleme Paneline Geri Dön</a></button>

</div>

</html>