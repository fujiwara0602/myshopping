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
<title>My shopping ユーザ一覧(管理者)</title>
    <link rel="stylesheet" type="text/css" href="css/common.css">
    <link rel="stylesheet" type="text/css" href="css/admin.css">
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
        <?php include ( dirname(__FILE__) . '/common/header.php' ); ?>
    </header>
    
        <table class="user">
            <tr>
                <th>ID</th>
                <th>名前</th>
                <th>メールアドレス</th>
                <th colspan="2"></th>
            </tr>
            <?php

                    try {
                        $pdo = new PDO($dsn, $user, $dbPassword);
                        if (!$pdo) {
                            die('接続失敗です。');
                        }

                        $pdo->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
                        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                        $pdo->beginTransaction();

                        $stmt = $pdo->prepare('SELECT * FROM users WHERE admin = 0');
                        $stmt->execute();
                        $users = $stmt->fetchAll(PDO::FETCH_ASSOC);

                        // カート内の商品を一覧表示
                        foreach ($users as $user) {
            ?>
                            <tr>
                                <td class="id3"><?= $user['id'] ?></td>
                                <td class="name3"><?= $user['name'] ?></td>
                                <td class="email3"><?= $user['email'] ?></td>
                                <td class="button3">
                                    <form method="POST" action="ad_edit_user.php">
                                        <input type="hidden" name="id" value="<?= $user['id'] ?>">
                                        <input type="hidden" name="name" value="<?= $user['name'] ?>">
                                        <input type="hidden" name="email" value="<?= $user['email'] ?>">
                                        <button class="button2 ad-button" type="submit">編集</button>
                                    </form>
                                </td>
                                <td class="button3">
                                    <form method="POST" action="/file_delete/delete_user.php" onSubmit="return check()">
                                        <input type="hidden" name="id" value="<?= $user['id'] ?>">
                                        <button class="button2 ad-button" type="submit">削除</button>
                                    </form>
                                </td>
                            </tr>
            <?php
                        }
                        $pdo->commit();
                    } catch (Exception $e) {
                        echo '<span class="error">エラーがありました。</span><br>';
                        echo "データベースエラー: " . $e->getMessage();
                        $pdo->rollBack();
                    }
            ?>
        </table>

    <footer id="footer">
        <?php include ( dirname(__FILE__) . '/common/footer.php' ); ?>
    </footer>
</body>
</html>
