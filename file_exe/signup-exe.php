<?php
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $password_confirmation = $_POST['password_confirmation'];

    // バリデーション
    if (empty($name) || empty($email) || empty($password) || empty($password_confirmation)) {
        $_SESSION['error_message0'] = "全てのフィールドを入力してください。";
        header('Location: ../signup.php');
        exit();
    }

    // パスワード確認
    if ($password != $password_confirmation) {
        $_SESSION['error_message1'] = "パスワードが一致しません。";
        header('Location: ../signup.php');
        exit();
    }

    if (strlen($password) < 8) {
        $_SESSION['error_message2'] = "パスワードは少なくとも8文字以上である必要があります。";
        header('Location: ../signup.php');
        exit();
    }

    if (strlen($password_confirmation) < 8) {
        $_SESSION['error_message4'] = "パスワードは少なくとも8文字以上である必要があります。";
        header('Location: ../signup.php');
        exit();
    }

    // メールアドレスのバリデーション
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $_SESSION['error_message3'] = "有効なメールアドレスを入力してください。";
        header('Location: ../signup.php');
        exit();
    }

    try {
        require_once("../common/db.php");
        $pdo = new PDO($dsn, $user, $dbPassword); 

        // 既存のメールアドレスが存在するか確認
        $stmt = $pdo->prepare("SELECT * FROM users WHERE email = :email");
        $stmt->bindParam(':email', $email, PDO::PARAM_STR);
        $stmt->execute();
        $existingUser = $stmt->fetch();

        if ($existingUser) {
            $_SESSION['error_message'] = "このメールアドレスは既に使用されています。";
            header('Location: ../signup.php');
            exit();
        }

        // 新しいユーザーを追加
        $pdo->beginTransaction();
        $stmt = $pdo->prepare("INSERT INTO users (name, email, password, admin) VALUES (:name, :email, :password, :admin)");
        $stmt->bindParam(':name', $name, PDO::PARAM_STR);
        $stmt->bindParam(':email', $email, PDO::PARAM_STR);
        $stmt->bindParam(':password', password_hash($password, PASSWORD_DEFAULT), PDO::PARAM_STR);
        $stmt->bindValue(':admin', 0, PDO::PARAM_INT);
        $res = $stmt->execute();

        // トランザクションを確定
        $pdo->commit();

        if ($res) {
            // ユーザー登録が成功した場合
            header('Location: ../login.php');
            exit();
        } else {
            $_SESSION['error_message'] = "ユーザー登録に失敗しました。";
            header('Location: ../signup.php');
            exit();
        }
    } catch (PDOException $e) {
        // ロールバック
        $pdo->rollBack();
        $_SESSION['error_message'] = "データベースエラー: " . $e->getMessage();
        header('Location: ../signup.php');
        exit();
    }
}
?>
