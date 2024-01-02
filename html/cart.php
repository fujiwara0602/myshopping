<?php
session_start();
require_once("db.php");
$userId = $_SESSION['id'];
if(!isset($_SESSION['admin']) && $_SESSION['admin'] == 0) {
    header('Location: /www.hogeshop.com/index.php');
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>My shopping カート</title>
    <link rel="stylesheet" type="text/css" href="css/common.css">
    <link rel="stylesheet" type="text/css" href="css/cart.css">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.2.4/jquery.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.3.1.js"></script>
    <script src="js/headerFixed.js"></script>
    <script src="js/footerFixed.js"></script>
    <script src="js/scroll.js"></script>
    <script src="js/delete_check.js"></script>
</head>
<body>
    <div id="pagetop"><a href="#">Jump To Top</a></div>
    <header>
        <?php include ( dirname(__FILE__) . '/header.php' ); ?>
    </header>
    <div class="cart1">
        <?php
        try {
            $pdo = new PDO($dsn, $user, $dbPassword);
            if (!$pdo) {
                die('接続失敗です。');
            }

            $pdo->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $pdo->beginTransaction();

            // カートの取得
            $stmt = $pdo->prepare('SELECT * FROM cart WHERE user_id = :user_id AND buy_check = 0');
            $stmt->bindParam(':user_id', $userId, PDO::PARAM_INT);
            $stmt->execute();
            $cart = $stmt->fetch(PDO::FETCH_ASSOC);

            // カート内の商品を取得
            $stmt = $pdo->prepare('SELECT cp.*, p.name AS product_name, p.cost AS product_cost, g.name AS genre_name
                                FROM cart_product cp
                                JOIN products p ON cp.product_id = p.id
                                JOIN genres g ON p.genre_id = g.id
                                WHERE cp.cart_id = :cart_id');
            $stmt->bindParam(':cart_id', $cart['id'], PDO::PARAM_INT);
            $stmt->execute();
            $cartProducts = $stmt->fetchAll(PDO::FETCH_ASSOC);

            $maxCost = 0;
            $maxNumber = 0;

            // カート内の商品を一覧表示
            foreach ($cartProducts as $cartProduct) {
                ?>
                <table class="cart">
                    <tr>
                        <th>ジャンル</th>
                        <th>商品名</th>
                        <th>値段<br class="none2">(合計)</th>
                        <th>個数</th>
                        <th colspan="2"></th>
                    </tr>
                    <tr>
                        <td class="genre"><?= $cartProduct['genre_name'] ?></td>
                        <td class="name"><?= $cartProduct['product_name'] ?></td>
                        <td class="cost"><?= $cartProduct['product_cost'] * $cartProduct['number'] ?> 円</td>
                        <td class="number"><?= $cartProduct['number'] ?> 点</td>
                        <td class="button">
                            <form method="POST" action="/www.hogeshop.com/file_add/add_cart.php">
                                <input type="hidden" name="product_id" value="<?= $cartProduct['product_id'] ?>">
                                <input type="hidden" name="cart_id" value="<?= $cartProduct['cart_id'] ?>">
                                <input type="number" id="number" name="number" min="1" placeholder="00"><br><br>
                                <button type="submit" class="button1">変更</button>
                            </form>
                        </td>
                        <td class="button">
                            <form method="POST" action="/www.hogeshop.com/file_delete/delete_cart.php" onSubmit="return check()">
                                <input type="hidden" name="product_id" value="<?= $cartProduct['product_id'] ?>">
                                <input type="hidden" name="cart_id" value="<?= $cartProduct['cart_id'] ?>">
                                <button type="submit" class="button1">削除</button>
                            </form>
                        </td>
                    </tr>
                </table>
                <?php
                $maxCost += $cartProduct['product_cost'] * $cartProduct['number'];
                $maxNumber += $cartProduct['number'];
            }

            // カートが空でない場合にのみ表示
            if ($maxNumber > 0) {
                ?>
                <table class="cart">
                    <tr>
                        <th colspan="3"><p>合計金額: <?= $maxCost ?>円</p></th>
                        <th><?= $maxNumber ?> 点</th>
                        <th colspan="2"><a href="/www.hogeshop.com/confirm.php"><button class="cart_button">購入確認<br class="none1">にすすむ</button></a></th>
                    </tr>
                </table>
                <?php
            }

            $pdo->commit();
        } catch (Exception $e) {
            echo '<span class="error">エラーがありました。</span><br>';
            echo "データベースエラー: " . $e->getMessage();
            $pdo->rollBack();
        }
        ?>
    </div>
    <footer id="footer">
        <?php include ( dirname(__FILE__) . '/footer.php' ); ?>
    </footer>
</body>
</html>
