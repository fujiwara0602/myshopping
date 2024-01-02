<?php
    session_start();
    require_once("common/db.php");
?>

<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>My shopping 商品詳細</title>
    <link rel="stylesheet" type="text/css" href="css/common.css">
    <link rel="stylesheet" type="text/css" href="css/product.css">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.2.4/jquery.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.3.1.js"></script>
    <script src="js/headerFixed.js"></script>
    <script src="js/footerFixed.js"></script>
    <script src="js/scroll.js"></script>
    <script>
        function confirm_test() {
            var select = confirm("カートに入れるにはログインが必要です");
            return select;
        }
    </script>
    <script>
        function confirm_cart() {
            var select = confirm("カートに追加しますか？");
            return select;
        }
    </script>
</head>
<body>
    <div id="pagetop"><a href="#">Jump To Top</a></div>
    <header>
        <?php include ( dirname(__FILE__) . '/common/header.php' ); ?>
    </header>

    <?php
        $product_id = $_GET["id"];

        try {
            $pdo = new PDO($dsn, $user, $dbPassword);
            $pdo->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $pdo->beginTransaction();

            $stmt = $pdo->prepare('SELECT * FROM products WHERE id = :id');
            $stmt->bindParam(':id', $product_id, PDO::PARAM_INT);
            $stmt->execute();

            $res = $stmt->fetch(PDO::FETCH_ASSOC); // Fetch the data

    ?>
    <div class="flex product">
        <img src="<?php echo 'images/' . $res['images']; ?>" alt="商品画像">
        <div class="p_right">
            <h1><?= $res['name'] ?></h1>
            <span class="flex">
                <p class="cost"><?= $res['cost'] ?>円</p>
                <?php
                    if (empty($_SESSION['name']) || empty($_SESSION['email'])) {
                ?>
                        <form action="login.php" onsubmit="return confirm_test()">
                            <button type="submit" class="cart_button">カートに入れる</button>
                        </form>
                <?php
                    } else {
                ?>
                        <form action="/file_exe/cart-insert.php" method="post" onsubmit="return confirm_cart()">
                            <input type="hidden" name="product_id" value="<?= $res['id'] ?>">
                            <button type="submit" class="cart_button">カートに入れる</button>
                        </form>
                <?php
                    }
                ?>
            </span>
            <p><?= $res['content'] ?></p> 
        </div>
    </div>
    <?php
        $pdo->commit();
        } catch (Exception $e) {
            echo '<span class="error">エラーがありました。</span><br>';
            $pdo->rollBack();
        }
    ?>

    <footer id="footer">
        <?php include ( dirname(__FILE__) . '/common/footer.php' ); ?>
    </footer>
</body>
</html>
