<?php
    session_start();
    if(isset($_SESSION['admin']) && $_SESSION['admin'] == 1) {
        header('Location: admin.php');
        exit();
    }
    require_once("common/db.php");
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>My shopping ログイン(管理者)</title>
    <link rel="stylesheet" type="text/css" href="css/common.css">
    <link rel="stylesheet" type="text/css" href="css/log.css">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.2.4/jquery.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.3.1.js"></script>
    <script src="js/headerFixed.js"></script>
    <script src="js/footerFixed.js"></script>
</head>
<body>
    <header>
        <?php include (dirname(__FILE__) . '/common/header.php'); ?>
    </header>
    <form action="/file_exe/ad_login-exe.php" method="post"><br>
        <div class="error1">
            <?= $_SESSION['error'] ?>
        </div>
        <input type="hidden" name="admin" value="1">
        <span class="flex input1">
            <label>メールアドレス　:　</label><br>
            <input class="input0" id="email" name="email" placeholder="メールアドレス"><br><br>
        </span>
        <span class="flex input1">
            <label>パスワード　：　</label><br>
            <input class="input0" type="password" id="password" name="password" placeholder="パスワード"><br><br>
        </span>
        <div class="button0">
            <input type="submit" value="ログイン">
        </div>
    </form><br>
    <footer id="footer">
        <?php include (dirname(__FILE__) . '/common/footer.php'); ?>
    </footer>
</body>
</html>