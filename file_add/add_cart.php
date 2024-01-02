<?php
session_start();

$userId = $_SESSION['id'];
$productId = $_POST['product_id'];
$quantity = $_POST['number'];
if(!$quantity) {
    header('Location: ../cart.php');
    exit();
}
if(!isset($_SESSION['admin']) && $_SESSION['admin'] == 0) {
    // リダイレクト
    header('Location: ../index.php');
    exit();
}
// データベース接続の詳細を含むファイルをインクルード
        require_once("../common/db.php");

try {
    $pdo = new PDO($dsn, $user, $dbPassword);

    // PDOの属性を設定
    $pdo->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $pdo->beginTransaction();

    // カートが存在し、buy_checkが0であるか確認
    $stmt = $pdo->prepare('SELECT * FROM cart WHERE user_id = :user_id AND buy_check = 0 LIMIT 1');
    $stmt->bindParam(':user_id', $userId, PDO::PARAM_INT);
    $stmt->execute();
    $cart = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$cart) {
        // カートが存在しないか、buy_checkが0でない場合、新しいカートを作成
        $stmt = $pdo->prepare('INSERT INTO cart (user_id, buy_check) VALUES (:user_id, 0)');
        $stmt->bindParam(':user_id', $userId, PDO::PARAM_INT);
        $stmt->execute();

        // 新しく挿入されたカートのIDを取得
        $cartId = $pdo->lastInsertId();
    } else {
        // カートが存在する場合、そのIDを使用
        $cartId = $cart['id'];
    }

    // カート内に商品が既に存在するか確認
    $stmt = $pdo->prepare('SELECT * FROM cart_product WHERE cart_id = :cart_id AND product_id = :product_id LIMIT 1');
    $stmt->bindParam(':cart_id', $cartId, PDO::PARAM_INT);
    $stmt->bindParam(':product_id', $productId, PDO::PARAM_INT);
    $stmt->execute();
    $existingCartItem = $stmt->fetch(PDO::FETCH_ASSOC);

    // 商品がすでにカート内に存在する場合、数量を更新
    $stmt = $pdo->prepare('UPDATE cart_product SET number = :quantity WHERE id = :id');
    $stmt->bindParam(':quantity', $quantity, PDO::PARAM_INT);
    $stmt->bindParam(':id', $existingCartItem['id'], PDO::PARAM_INT);
    $stmt->execute();

    $pdo->commit();
    header('Location: ../cart.php');
    exit();
} catch (Exception $e) {
    echo '<span class="error">エラーがありました。</span><br>';
    echo "データベースエラー: " . $e->getMessage();
    $pdo->rollBack();
}
?>
