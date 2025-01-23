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

$category_id=$_GET['id'] ?? null;

if (!$category_id) {
    echo 'Kategori Bulunamadı.';
    return;
}

$stmt=$db->prepare('SELECT * FROM menu WHERE id=:category_id');
$stmt->execute([':category_id'=> $category_id]);
$category=$stmt->fetch();

if($category_id){
    $stmt=$db->prepare('DELETE FROM menu WHERE id=:category_id');
    $stmt->execute(['category_id'=> $category_id]);

    header('Location: admin.php');

} else{
    echo 'Kategori silinirken bir hata oluştu.';
    exit;
}
