<?php
    session_start();
    require_once("common/db.php");
    unset($_SESSION['error']);
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>My shopping ホーム</title>
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
    <form method="GET" action="/search.php" class="head mat20">
        <input type="text" name="search" class="search" placeholder="商品を検索">
        <button class="search1" type="submit">検索</button>
    </form>

    <?php
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

            // ジャンルを取得
            $stmt = $pdo->prepare("SELECT * FROM genres");
            $stmt->execute();
            $genres = $stmt->fetchAll(PDO::FETCH_OBJ);

            // 各ジャンルに対して関連する製品を取得
            foreach ($genres as $genre) {
                $stmt = $pdo->prepare("SELECT * FROM products WHERE genre_id = ? LIMIT 4");
                $stmt->execute([$genre->id]);
                $products = $stmt->fetchAll(PDO::FETCH_OBJ);
                $genre->products = $products;
    ?>
        <div class="genre">
            <div class="flex head">
                <h1>ジャンル: <?= $genre->name ?></h1>
                <a href="/genre.php?genre_id=<?= $genre->id ?>" class="button">
                    View All
                </a>
            </div>
            <div class="images">
                <?php
                    foreach ($genre->products as $product) {
                ?>
                        <a href="/product.php?id=<?= $product->id ?>">
                            <img src="images/<?php echo $product->images; ?>" alt="商品画像">
                        </a>
                <?php
                    }
                ?>
            </div>
        </div>
    <?php
            }
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
