<?php
  session_start();
  if(!isset($_SESSION['admin']) && $_SESSION['admin'] == 1) {
      // リダイレクト
      header('Location: ../admin_login.php');
      exit();
  }
  $id = $_POST['id'];

  try {
    require_once("../common/db.php");
    $pdo = new PDO($dsn, $user, $dbPassword);
    
    $stmt = $pdo->prepare("DELETE FROM cart WHERE id = :id");
    
    $stmt->bindParam(':id', $id, PDO::PARAM_INT);
    
    $stmt->execute();
    
    header('Location: ../admin_order.php');
    exit();
  } catch (PDOException $e) {
    header('Location: ../index.php');
    exit();
  }
?>
