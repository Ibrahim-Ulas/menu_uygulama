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
    <link href='https://fonts.googleapis.com/css?family=Londrina+Shadow' rel='stylesheet' type='text/css'>
    <?php include_once('bs.php'); ?>
    <style>
        <?php include_once('css.css'); ?>
    </style>
</head>

<body>
    <div >
        <h1 style="text-align: center;"><img style="height: 150px;"src="resim/chilis.jpg" alt=""></h1>
        <h2 style="text-align: center;">Menü</h2>
        <ul class="list">
            <?php foreach ($menuItems as $category): ?>
                <li>
                    <button><a href="MenuItems.php?id=<?= htmlspecialchars($category['id']) ?>">
                            <?= htmlspecialchars($category["name"]) ?>
                        </a>
                        <a href="MenuItems.php?id=<?= htmlspecialchars($category['id']) ?>">
                        <img src=" <?= htmlspecialchars($category['image']) ?>" alt="<?= htmlspecialchars($category['name']) ?>">
                    </a>
                    </button>
                    
                </li>
            <?php endforeach ?>

        </ul>



    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-kenU1KFdBIe4zVF0s0G1M5b4hcpxyD9F7jL+jjXkk+Q2h455rYXK/7HAuoJl+0I4" crossorigin="anonymous"></script>

</body>

</html>