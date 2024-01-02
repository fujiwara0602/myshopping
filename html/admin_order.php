<?php
    session_start();
    require_once("common/db.php");
    if(!isset($_SESSION['admin']) && $_SESSION['admin'] == 1) {
        // リダイレクト
        header('Location: admin.php');
        exit();
    }
?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <title>My shopping 注文履歴(管理者)</title>
    <link rel="stylesheet" type="text/css" href="css/common.css">
    <link rel="stylesheet" type="text/css" href="css/admin.css">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <script src="https://code.jquery.com/jquery-3.3.1.js"></script>
    <script src="js/headerFixed.js"></script>
    <script src="js/footerFixed.js"></script>
    <script src="js/scroll.js"></script>
    <script src="js/delete_check.js"></script>
</head>
<body>
    <div id="pagetop"><a href="#">Jump To Top</a></div>
        <header>
        <?php include ( dirname(__FILE__) . '/common/header.php' ); ?>
        </header>
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

                $stmt = $pdo->prepare('SELECT * FROM cart WHERE buy_check = 1');
                $stmt->execute();
                $mypages = $stmt->fetchAll(PDO::FETCH_OBJ);

                $counts = 1;
                foreach ($mypages as $mypage) {
                  $max_cost = 0;
        ?>
            <table class="order_table">
                <tr>
                    <th><p><?= $counts ?>件め</p></th>
                    <th>
                        <form method="POST" action="/file_delete/delete_order.php" onSubmit="return check()">
                            <input type="hidden" name="id" value="<?= $mypage->id ?>">
                            <button type="submit">削除</button>
                        </form>
                    </th>
                </tr>
                <tr>
                    <th>ジャンル</th>
                    <th>商品名</th>
                    <th>値段</th>
                    <th>個数</th>
                </tr>
        <?php
                    $stmt = $pdo->prepare('SELECT * FROM cart_product WHERE cart_id = :id');
                    $stmt->bindParam(':id', $mypage->id, PDO::PARAM_INT);
                    $stmt->execute();
                    $cart_products = $stmt->fetchAll(PDO::FETCH_ASSOC);
                    foreach ($cart_products as $cartproduct) {

                        $stmt = $pdo->prepare("SELECT * FROM products WHERE id = :product_id");
                        $stmt->bindParam(':product_id', $cartproduct['product_id'], PDO::PARAM_INT);
                        $stmt->execute();
                        $product = $stmt->fetch(PDO::FETCH_ASSOC);

                        $stmt = $pdo->prepare("SELECT * FROM genres WHERE id = :id");
                        $stmt->bindParam(':id', $product['genre_id'], PDO::PARAM_INT);
                        $stmt->execute();
                        $genre = $stmt->fetch(PDO::FETCH_ASSOC);
        ?>
                    <tr>
                    <td class="genre"><?= $genre['name'] ?></td>
                            <td class="name"><?= $product['name'] ?></td>
                            <td class="cost"><?= $product['cost'] * $cartproduct['number'] ?> 円</td>
                            <td class="number"><?= $cartproduct['number'] ?>点</td>
                        </td>
                    </tr>
        <?php
                        $max_cost += $product['cost'] * $cartproduct['number'];
                    }
        ?>
                    <tr>
                        <td colspan="5" class="max">合計金額: <?= $max_cost ?> 円</td>
                    </tr>
    </table>
        <?php
                        $counts += 1;
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