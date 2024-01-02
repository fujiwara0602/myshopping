<?php
    session_start();
    $_SESSION['genre_id']   = $_GET["genre_id"];
?>
<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<title>My shopping ジャンル別商品一覧</title>
<link rel="stylesheet" type="text/css" href="css/common.css">
<link rel="stylesheet" type="text/css" href="css/index.css">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.2.4/jquery.min.js"></script>
<script src="https://code.jquery.com/jquery-3.3.1.js"></script>
<script src="js/headerFixed.js"></script>
<script src="js/footerFixed.js"></script>
    <script src="js/scroll.js"></script>
</head>
<body>
    <div id="pagetop"><a href="#">Jump To Top</a></div>
    <header>
        <?php include ( dirname(__FILE__) . '/common/header.php' ); ?>
    </header>
    <?php
        try {
            require_once("common/db.php");
            $pdo = new PDO($dsn, $user, $dbPassword);
            if (!$pdo) {
                die('接続失敗です。');
            }

            // プリペアドステートメントのエミュレーションを無効にする
            $pdo->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
            // 例外がスローされる設定にする
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $pdo->beginTransaction();

            // ジャンルを取得
            $stmt = $pdo->prepare('SELECT * FROM genres WHERE id = :id');
            $stmt->bindParam(':id', $_SESSION['genre_id'], PDO::PARAM_INT);
            $stmt->execute();
            $genre = $stmt->fetch(PDO::FETCH_OBJ);

            // ジャンルに対する関連する製品を取得
            $stmt = $pdo->prepare('SELECT * FROM products WHERE genre_id = :genre_id');
            $stmt->bindParam(':genre_id', $_SESSION['genre_id'], PDO::PARAM_INT);
            $stmt->execute();
            $products = $stmt->fetchAll(PDO::FETCH_OBJ);

            // ジャンルと関連する製品を表示
    ?>
        
        <div class="genre">
            <h1 class="head">Genre: <?php echo $genre->name; ?></h1>
            <div class="image">
                <?php foreach ($products as $index => $product): ?>
                    <a href="product.php?id=<?= $product->id ?>">
                        <img src="<?php echo 'images/' . $product->images; ?>" alt="商品画像">
                    </a>
                <?php endforeach; ?>
            </div>
        </div>

    <?php
            $pdo->commit();
        } catch (PDOException $e) {
            echo "データベースエラー: " . $e->getMessage();
            $pdo->rollBack();
        }
    ?>
    <footer id="footer">
        <?php include ( dirname(__FILE__) . '/common/footer.php' ); ?>
    </footer>
</body>
</html>
