<?php
    session_start();
    require_once("common/db.php");
?>
<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<title>My shopping 新規登録</title>
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
        <?php include ( dirname(__FILE__) . '/common/header.php' ); ?>
    </header>
    <form action="/file_exe/signup-exe.php" method="post">
        <div class="error1"><?= $_SESSION['error_message'] ?></div>
        <span class="flex input1">
            <label>名前 :　</label><br>
            <input class="input0" id="name" name="name" placeholder="name"><br><br>
        </span>

        <div class="error1"><?= $_SESSION['error_message3'] ?></div>
        <span class="flex input1">
            <label>メールアドレス : </label><br>
            <input class="input0" id="email" name="email" placeholder="mailaddress"><br><br>
        </span>

        <div class="error1"><?= $_SESSION['error_message1'] ?></div>
        <div class="error1"><?= $_SESSION['error_message2'] ?></div>
        <span class="flex input1">
            <label>パスワード ： </label><br>
            <input class="input0" type="password"  id="password" name="password" placeholder="password"><br><br>
        </span>
        
        <div class="error1"><?= $_SESSION['error_message1'] ?></div>
        <div class="error1"><?= $_SESSION['error_message4'] ?></div>
        <span class="flex input1">
            <label>パスワード確認　：　</label><br>
            <input class="input0" type="password"  id="password_confirmation" name="password_confirmation" placeholder="password_confirmation"><br><br>
        </span>
        <div class="error1"><?= $_SESSION['error_message0'] ?></div>
        <div class="button0">
            <input type="submit" value="登録">
        </div>
    </form><br>
    <footer id="footer">
        <?php include ( dirname(__FILE__) . '/common/footer.php' ); ?>
    </footer>
</body>
</html>




