<?php
session_start();
error_reporting(E_ALL);
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $password = $_POST['password'];

    if (empty($email) || empty($password)) {
        $_SESSION['error'] = 'メールアドレスとパスワードを入力してください。';
        header('Location: ../login.php');
        exit();
    }

    try {
        require_once("../common/db.php");
        $pdo = new PDO($dsn, $user, $dbPassword);
        $stmt = $pdo->prepare("SELECT * FROM users WHERE email = :email AND admin = 0");
        $stmt->bindParam(':email', $email, PDO::PARAM_STR);
        $stmt->execute();

        $result = $stmt->fetch();

        if (password_verify($password, $result['password'])) {
            // ログイン成功
            $_SESSION['id']   = $result['id'];
            $_SESSION['name'] = $result['name'];
            $_SESSION['email'] = $result['email'];
            $_SESSION['admin'] = $result['admin'];
            header('Location: ../index.php');
            exit;
        } else {
            // ログイン失敗
            $_SESSION['error'] = 'メールアドレスまたはパスワードが正しくありません。';
            header('Location: ../login.php');
            exit;
        }
    } catch (PDOException $e) {
        $_SESSION['error'] = 'データベースエラー: ' . $e->getMessage();
        header('Location: ../login.php');
        exit;
    }
}
?>
