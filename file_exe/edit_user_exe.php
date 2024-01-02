<?php
session_start();
if(!isset($_SESSION['admin']) && $_SESSION['admin'] == 0) {
  // リダイレクト
  header('Location: ../admin_login.php');
  exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $user_id = $_POST['id'];
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
        $stmt = $pdo->prepare("SELECT * FROM users WHERE email = :email AND id != :user_id");
        $stmt->bindParam(':email', $email, PDO::PARAM_STR);
        $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
        $stmt->execute();
        $existingUser = $stmt->fetch();

        if ($existingUser) {
            $_SESSION['error_message'] = "このメールアドレスは既に使用されています。";
            header('Location: ../edit_user.php');
            exit();
        }

        $pdo->beginTransaction();
        $stmt = $pdo->prepare("UPDATE users SET name = :name, email = :email, password = :password WHERE id = :user_id");
        $stmt->bindParam(':name', $name, PDO::PARAM_STR);
        $stmt->bindParam(':email', $email, PDO::PARAM_STR);
        $stmt->bindParam(':password', password_hash($password, PASSWORD_DEFAULT), PDO::PARAM_STR);
        $stmt->bindValue(':user_id', $user_id, PDO::PARAM_INT);
        $res = $stmt->execute();

        // トランザクションを確定
        $pdo->commit();

        if ($res) {
            // ユーザー登録が成功した場合
            
            $stmt = $pdo->prepare("SELECT * FROM users WHERE id = :user_id");
            $stmt->bindParam(':user_id', $user_id, PDO::PARAM_STR);
            $stmt->execute();
            $result = $stmt->fetch();
            $_SESSION['id']   = $result['id'];
            $_SESSION['name'] = $result['name'];
            $_SESSION['email'] = $result['email'];
            $_SESSION['admin'] = $result['admin'];
            header('Location: ../mypage.php');
            exit();
        } else {
            $_SESSION['error_message'] = "ユーザー登録に失敗しました。";
            header('Location: ../mypage.php');
            exit();
        }
    } catch (PDOException $e) {
        // ロールバック
        $pdo->rollBack();
        $_SESSION['error_message'] = "データベースエラー: " . $e->getMessage();
        header('Location: ../mypage.php');
        exit();
    }
}
?>
