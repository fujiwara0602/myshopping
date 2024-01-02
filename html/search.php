<?php
    session_start();
    require_once("common/db.php");

    // フォームからのデータをセッションに保存
    $_SESSION['search'] = isset($_GET['search']) ? $_GET['search'] : '';

    // 後の画面でセッションからデータを取得
    $searchTerm = isset($_SESSION['search']) ? $_SESSION['search'] : '';

    try {
        $pdo = new PDO($dsn, $user, $dbPassword);
        if (!$pdo) {
            die('接続失敗です。');
        }

        // プリペアドステートメントのエミュレーションを無効にする
        $pdo->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
        // 例外がスローされる設定にする
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $pdo->beginTransaction();

        // 商品を検索（部分一致）
        $stmt = $pdo->prepare('SELECT * FROM products WHERE name LIKE :searchTerm');
        $stmt->bindValue(':searchTerm', '%' . $searchTerm . '%', PDO::PARAM_STR);
        $stmt->execute();
        $foundProducts = $stmt->fetchAll(PDO::FETCH_OBJ);

        $pdo->commit();
    } catch (PDOException $e) {
        echo "データベースエラー: " . $e->getMessage();
        $pdo->rollBack();
    }
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>My shopping 検索結果</title>
    <link rel="stylesheet" type="text/css" href="css/common.css">
    <link rel="stylesheet" type="text/css" href="css/index.css">
    <link rel="stylesheet" type="text/css" href="css/search.css">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="js/headerFixed.js"></script>
    <script src="js/footerFixed.js"></script>
    <script src="js/scroll.js"></script>
</head>
<body>
    <div id="pagetop"><a href="#">Jump To Top</a></div>
    <header>
        <?php include ( dirname(__FILE__) . '/common/header.php' ); ?>
    </header>

    <h2 class="result">Search Results "<?= $searchTerm ?>"</h2>

    <div class="image">
        <?php foreach ($foundProducts as $index => $product): ?>
            <a href="product.php?id=<?= $product->id ?>"><img src="<?php echo 'images/' . $product->images; ?>" alt="商品画像"></a>
        <?php endforeach; ?>
    </div>

    <footer id="footer">
        <?php include ( dirname(__FILE__) . '/common/footer.php' ); ?>
    </footer>
</body>
</html>
