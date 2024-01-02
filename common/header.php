<div class="header">
    <a href="../index.php"><h1>HOGE_SITE</h1></a>
    <ul>
        <?php
            if (empty($_SESSION['name']) || empty($_SESSION['email'])) {
        ?>
                <li><a href="../signup.php">新規登録</a></li>
                <li><a href="../login.php">ログイン</a></li>
                <br class="none">
                <li><a href="../cart.php">カート</a></li>
        <?php
            } else {
        ?>
                <li class="head_li"><?= $_SESSION['name'] ?></li>
                <br>
                <li><a href="../file_exe/logout.php">ログアウト</a></li>
                <br class="none">
                <li><a href="../mypage.php">マイページ</a></li>
                <li><a href="../cart.php">カート</a></li>
        <?php
            }
        ?>
    </ul>
</div>