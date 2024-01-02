<?php
    session_start();
    require_once("common/db.php");
    if(!isset($_SESSION['admin']) && $_SESSION['admin'] == 0) {
        // リダイレクト
        header('Location: index.php');
        exit();
    }
?>

<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<title>My shopping マイページ</title>
<link rel="stylesheet" type="text/css" href="css/common.css">
<link rel="stylesheet" type="text/css" href="css/mypage.css">
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
    <?php include (dirname(__FILE__) . '/common/header.php'); ?>
</header>

<div>
    <table>
        <tr>
            <th>ユーザ名</th>
            <th>メールアドレス</th>
        </tr>
        <tr>
            <th class="font20"><?= $_SESSION['name'] ?></th>
            <th class="font20"><?= $_SESSION['email'] ?></th>
            <td rowspan="2">
                <form method="POST" action="edit_user.php">
                    <input type="hidden" name="id" value="<?= $_SESSION['id'] ?>">
                    <input type="hidden" name="name" value="<?= $_SESSION['name'] ?>">
                    <input type="hidden" name="email" value="<?= $_SESSION['email'] ?>">
                    <button class="button2" type="submit">プロフィールを編集<br>(パスワードを忘れた方はこちら)</button>
                </form>
            </td>
        </tr>
    </table>
    <hr>
    <table>
        <tr>
            <td colspan="4" class="pat_30 font_24">注文履歴</td>
        </tr>
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

                $stmt = $pdo->prepare('SELECT * FROM cart WHERE buy_check = 1 AND user_id = :user_id');
                $stmt->bindParam(':user_id', $_SESSION['id'], PDO::PARAM_INT);
                $stmt->execute();
                $mypages = $stmt->fetchAll(PDO::FETCH_OBJ);

                $counts = 1;
                foreach ($mypages as $mypage) {
                  $max_cost = 0;
        ?>
                    <tr>
                        <th colspan="4" class="pat100"><p><?= $counts ?>件め</p></th>
                    </tr>
                    <tr>
                        <th>商品画像</th>
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
                            <td class="genre">
                                <a href="/product.php?id=<?= $product['id'] ?>">
                                    <img src="<?php echo 'images/' . $product['images']; ?>" alt="商品画像">
                                </a>
                            </td>
                            <td class="name"><?= $product['name'] ?></td>
                            <td class="cost"><?= $product['cost'] * $cartproduct['number'] ?> 円</td>
                            <td class="number"><?= $cartproduct['number'] ?>点</td>
                        </tr>
        <?php
                        $max_cost += $product['cost'] * $cartproduct['number'];
                    }
        ?>
                    <tr>
                        <th class="foreach"></th>
                        <th colspan="2" class="foreach">
                            <form class="form" method="POST" action="receipt.php" target="_blank">
                                <input type="hidden" name="id" value="<?= $mypage->id ?>">
                                <button type="submit" class="receipt">レシート</button>
                            </form>
                        </th>
                        <th colspan="2" class="max pab_30 pab100 foreach">合計金額:<br class="none1"> <?= $max_cost ?> 円</th>
                    </tr>
        <?php
                        $counts += 1;
                }
                $pdo->commit();
            } catch (PDOException $e) {
                echo "データベースエラー: " . $e->getMessage();
                $pdo->rollBack();
            }
        ?>
        </table>
</div>

<footer id="footer">
    <?php include (dirname(__FILE__) . '/common/footer.php'); ?>
</footer>
</body>
</html>