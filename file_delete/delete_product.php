<?php
  session_start();
  if(!isset($_SESSION['admin']) && $_SESSION['admin'] == 1) {
      // リダイレクト
      header('Location: ../admin.php');
      exit();
  }
  $product_id = $_POST['id'];
  try {
    require_once("../common/db.php");
    $pdo = new PDO($dsn, $user, $dbPassword);
    
    $stmt = $pdo->prepare("DELETE FROM products WHERE id = :product_id");
    
    $stmt->bindParam(':product_id', $product_id, PDO::PARAM_INT);
    
    $stmt->execute();
    
    header('Location: ../admin_product.php');
    exit();
  } catch (PDOException $e) {
    header('Location: ../index.php');
    exit();
  }
?>
