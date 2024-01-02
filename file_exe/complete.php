<?php
session_start();
$userId = $_SESSION['id'];
if(!isset($_SESSION['admin']) && $_SESSION['admin'] == 0) {
    // リダイレクト
    header('Location: ../index.php');
    exit();
}

try {
    require_once("../common/db.php");
    $pdo = new PDO($dsn, $user, $dbPassword);

    // カートの buy_check フラグを1に設定
    $stmt = $pdo->prepare("UPDATE cart SET buy_check = 1 WHERE user_id = :user_id");
    $stmt->bindParam(':user_id', $userId, PDO::PARAM_INT);
    $stmt->execute();

    // リダイレクト
    header('Location: ../index.php');
    exit();
} catch (PDOException $e) {
    echo '<span class="error">エラーがありました。</span><br>';
    echo "データベースエラー: " . $e->getMessage();
    $pdo->rollBack();
}
?>
