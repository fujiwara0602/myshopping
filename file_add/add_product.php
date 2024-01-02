<?php
session_start();
if(!isset($_SESSION['admin']) && $_SESSION['admin'] == 1) {
    // リダイレクト
    header('Location: ../admin_login.php');
    exit();
}
$genre_id = $_POST['genre'];
$name = $_POST['name'];
$cost = $_POST['cost'];
$content = $_POST['content'];

// 画像の処理
if(isset($_FILES['image']) && $_FILES['image']['error'] == UPLOAD_ERR_OK) {
    $uploadDir = 'images/';  // 画像を保存するディレクトリへのパス
    $uploadFile = $uploadDir . basename($_FILES['image']['name']);
    $image_name = basename($_FILES['image']['name']);

    // 画像を移動する
    if(move_uploaded_file($_FILES['image']['tmp_name'], $uploadFile)) {
        // 画像の保存が成功した場合
        $images = $image_name;

        // データベースへの挿入処理を行う
        try {
            require_once("../common/db.php");

            $pdo = new PDO($dsn, $user, $dbPassword);
            if (!$pdo) {
                die('接続失敗です。');
            }
            $pdo->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $pdo->beginTransaction();

            $stmt = $pdo->prepare("INSERT INTO products (genre_id, name, cost, content, images) VALUES (:genre_id, :name, :cost, :content, :images)");
            $stmt->bindParam(':genre_id', $genre_id, PDO::PARAM_INT);
            $stmt->bindParam(':name', $name, PDO::PARAM_STR);
            $stmt->bindParam(':cost', $cost, PDO::PARAM_INT);
            $stmt->bindParam(':content', $content, PDO::PARAM_STR);
            $stmt->bindParam(':images', $images, PDO::PARAM_STR);
            
            $res = $stmt->execute();

            // トランザクションを確定
            $pdo->commit();
            
            if ($res) {
                header('Location: ../admin_product.php');
                exit();
            } else {
                echo "データベースエラー: レコードの挿入に失敗しました。";
                exit();
            }
        } catch (PDOException $e) {
            // ロールバック
            $pdo->rollBack();
            echo "データベースエラー: " . $e->getMessage();
            exit();
        }
    } else {
        echo "ファイルのアップロードに失敗しました。";
        exit();
    }
} else {
    echo "画像がアップロードされていません。";
    exit();
}
?>
