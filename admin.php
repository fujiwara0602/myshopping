<?php
    session_start();
    require_once("common/db.php");
?>
<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<title>My shopping ホーム(管理者)</title>
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
    <div class="admin">
        <a href="admin_order.php" class="admin_link">注文履歴</a><br>
        <a href="admin_product.php" class="admin_link">商品管理</a><br>
        <a href="admin_user.php" class="admin_link">ユーザ管理</a>
    </div>
    <footer id="footer">
        <?php include ( dirname(__FILE__) . '/common/footer.php' ); ?>
    </footer>
</body>
</html>