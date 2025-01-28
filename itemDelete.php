<?php
session_start();

// Kullanıcı admin değilse giriş sayfasına yönlendir
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

$item_id = (int)$_GET['id'] ?? null;

if (!$item_id) {
    echo "Ürün Bulunmamaktadır!";
    return;
}

// Ürün bilgilerini al
$stmt = $db->prepare('SELECT * FROM menu_items WHERE id = :item_id');
$stmt->execute([':item_id' => $item_id]);
$item = $stmt->fetch();

if ($item) {
    // Silme işlemi
    $stmt = $db->prepare('DELETE FROM menu_items WHERE id = :item_id');
    if ($stmt->execute([':item_id' => $item_id])) {
        // Ürün kategorisini al
        $category_id = $item['category_id'] ?? null; // 'category_id' sütunu kontrol ediliyor

        if ($category_id) {
            header('Location: itemList.php?id=' . urlencode($category_id));
            exit;
        } else {
            echo "Kategori bilgisi bulunamadı!";
        }
    } else {
        echo "Silme işlemi başarısız!";
    }
} else {
    echo "Ürün bulunmamakta!";
}


