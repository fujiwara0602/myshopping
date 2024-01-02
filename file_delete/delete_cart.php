<?php
  session_start();
  if(!isset($_SESSION['admin']) && $_SESSION['admin'] == 0) {
      // リダイレクト
      header('Location: ../index.php');
      exit();
  }
  $cart_id = filter_input(INPUT_POST, 'cart_id', FILTER_SANITIZE_NUMBER_INT);
  $product_id = filter_input(INPUT_POST, 'product_id', FILTER_SANITIZE_NUMBER_INT);

  try {
    require_once("../common/db.php");
    $pdo = new PDO($dsn, $user, $dbPassword);
    
    $stmt = $pdo->prepare("DELETE FROM cart_product WHERE cart_id = :cart_id AND product_id = :product_id");
    
    $stmt->bindParam(':cart_id', $cart_id, PDO::PARAM_INT);
    $stmt->bindParam(':product_id', $product_id, PDO::PARAM_INT);
    
    $stmt->execute();
    
    header('Location: ../cart.php');
    exit();
  } catch (PDOException $e) {
    header('Location: ../index.php');
    exit();
  }
?>
