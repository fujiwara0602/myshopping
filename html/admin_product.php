<?php
    session_start();
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
<title>My shopping 商品一覧(管理者)</title>
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
    <?php
        try {
          require_once("common/db.php");
            $pdo = new PDO($dsn, $user, $dbPassword);
            if (!$pdo) {
                die('接続失敗です。');
            }

            // プリペアドステートメントのエミュレーションを無効にする
            $pdo->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
            // 例外がスローされる設定にする
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $pdo->beginTransaction();
    ?>
    <div class="pro_flex center">
      <form action="/file_add/add_product.php" method="post" enctype="multipart/form-data">
          <div class="error"><br></div>
          <label class="pro_label">ジャンル<span class="red">*</span></label><br>
          <select id="genre" name="genre">
            <?php
              $stmt = $pdo->prepare('SELECT * FROM genres');
              $stmt->execute();
              $genres = $stmt->fetchAll(PDO::FETCH_OBJ);
              foreach ($genres as $genre) {
            ?>
                <option value="<?= $genre->id ?>"><?= $genre->id ?>.<?= $genre->name ?></option>
            <?php
              }
            ?>
          </select>
  
          <div class="error"><br></div>
          <label class="pro_label">商品名<span class="red">*</span></label><br>
          <input type="text" class="input21" id="name" name="name" placeholder="商品名"><br><br>
  
          <div class="error"><br></div>
          <label class="pro_label">値段<span class="red">*</span></label><br>
          <input type="text" class="input21" id="cost" name="cost" placeholder="1000"><br><br>
  
          <div class="error1"></div><br>
          <label class="pro_label">説明<span class="red">*</span></label><br>
          <textarea class="input2" id="content" name="content"></textarea><br><br>
          
          <div class="error"><br></div>
          <label class="pro_label">画像</label>
          <input type="file" class="ad-button" name="image"><br><br>
          
          <input type="submit" class="ad-button" value="登録">
      </form>
      <div>
        <?php
            // ジャンルを取得
            $stmt = $pdo->prepare("SELECT * FROM genres");
            $stmt->execute();
            $genres = $stmt->fetchAll(PDO::FETCH_OBJ);

            // 各ジャンルに対して関連する製品を取得
            foreach ($genres as $genre) {
                $stmt = $pdo->prepare("SELECT * FROM products WHERE genre_id = :genre_id");
                $stmt->bindParam(':genre_id', $genre->id, PDO::PARAM_INT);
                $stmt->execute();
                $products = $stmt->fetchAll(PDO::FETCH_OBJ);
        ?>
            <div class="head">
                <h1>Genre: <?= $genre->name ?></h1>
            </div>
              <div class="rounded-table-container">
                <table class="product">
                  <tr>
                    <th class="genre2">ジャンル</th>
                    <th class="name2">商品名</th>
                    <th class="cost2">値段</th>
                    <th class="content2">説明</th>
                    <th class="images2">画像</th>
                    <th></th>
                  </tr>
                <?php
                    foreach ($products as $product) {
                ?>
                    <tr>
                      <td><?= $genre->name ?></td>
                      <td><?= $product->name ?></td>
                      <td><?= $product->cost ?></td>
                      <td><?= $product->content ?></td>
                      <td><img src="<?php echo 'images/' . $product->images; ?>" alt="商品画像" class="img_size"></td>
                      <th>
                        <form method="POST" action="/file_delete/delete_product.php" onSubmit="return check()">
                          <input type="hidden" name="id" value="<?= $product->id ?>">
                          <button class="button2" type="submit">削除</button>
                        </form>
                      </th>
                    </tr>
                <?php
                    }
                ?>
                </table>
              </div>
          <?php
            }
          ?>
      </div>
    </div>
    <?php
            $pdo->commit();
        } catch (PDOException $e) {
            echo "データベースエラー: " . $e->getMessage();
            $pdo->rollBack();
        }
    ?>
    <footer id="footer">
        <?php include ( dirname(__FILE__) . '/common/footer.php' ); ?>
    </footer>
</body>
</html>
