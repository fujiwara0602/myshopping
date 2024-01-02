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
    <title>My shopping ユーザ編集</title>
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
        <form action="/file_exe/edit_user_exe.php" class="edit_user" method="post">
                <input type="hidden" name="id" value="<?= $user_id ?>">
                <div class="error"><?= $_SESSION['error_message'] ?></div>
                <div class="error"><?= $_SESSION['error_message0'] ?></div>
                <label>氏名<span class="red">*</span></label>
                <input type="text" class="input" id="name" value="<?= $name ?>" name="name" placeholder="name">

                <label>メールアドレス<span class="red">*</span></label>
                <div class="error"><?= $_SESSION['error_message3'] ?></div>
                <input type="text" class="input" id="email" value="<?= $email ?>" name="email" placeholder="email">
            
                <p class="red">パスワードを忘れた場合はこのまま入力だけでお願いします。</p>
                <label>パスワード<span class="red">*</span></label>
                <div class="error"><?= $_SESSION['error_message1'] ?></div>
                <div class="error"><?= $_SESSION['error_message2'] ?></div>
                <input type="password" class="input" id="password" name="password" placeholder="password"><br><br>

            
                <label>パスワード（確認）<span class="red">*</span></label>
                <div class="error"><?= $_SESSION['error_message1'] ?></div>
                <div class="error"><?= $_SESSION['error_message4'] ?></div>
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