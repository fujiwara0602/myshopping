<?php
    session_start();
    $user_id = $_POST['id'];
    $name = $_POST['name'];
    $email = $_POST['email'];
    require_once("common/db.php");
    if(!isset($_SESSION['admin']) && $_SESSION['admin'] == 1) {
        // リダイレクト
        header('Location: admin_login.php');
        exit();
    }
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>My shopping ユーザ編集(管理者)</title>
    <link rel="stylesheet" type="text/css" href="css/common.css">
    <link rel="stylesheet" type="text/css" href="css/admin.css">
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
        <form action="/file_exe/ad_edit_user_exe.php" class="edit_user" method="post">
                <input type="hidden" name="id" value="<?= $user_id ?>">
                <label>氏名<span class="red">*</span></label>
                <div class="error"></div>
                <input type="text" class="input" id="name" name="name" value="<?= $name ?>" placeholder="name">

                <label>メールアドレス<span class="red">*</span></label>
                <div class="error"></div>
                <input type="text" class="input" id="email" name="email" value="<?= $email ?>"placeholder="email">
            
                <label>パスワード<span class="red">*</span></label>
                <div class="error"></div>
                <input type="password" class="input" id="password" name="password" placeholder="password"><br><br>

            
                <label>パスワード（確認）<span class="red">*</span></label>
                <div class="error"></div>
                <input type="password" class="input" id="password_confirmation" name="password_confirmation" placeholder="password"><br><br>

            <div class="button_right">
                <button type="submit" class="button4">送信</button>
            </div>
        </form>
    <!--フッター-->
    <footer id="footer">
        <?php include ( dirname(__FILE__) . '/common/footer.php' ); ?>
    </footer>
    <script src="js/error.js"></script>
</body>
</html>