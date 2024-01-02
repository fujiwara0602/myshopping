<?php
session_start();

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once("common/db.php");
require_once("lib/tcpdf/tcpdf.php");


$cart_id = $_POST["id"]; // Assuming "id" is the correct field name

$maxCost = 0;
$maxNumber = 0;


$pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

$pdf->SetCreator(PDF_CREATOR);
$pdf->SetAuthor('HOgeHoge');
$pdf->SetTitle('レシート');
$pdf->SetSubject('TCPDF Example');
$pdf->SetKeywords('TCPDF, PDF, example, test, guide');
$pdf->SetFont('kozgopromedium', '', 10);
$pdf->AddPage();

try {
    $pdo = new PDO($dsn, $user, $dbPassword);
    if (!$pdo) {
        die('接続失敗です。');
    }
    $pdo->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $pdo->beginTransaction();

    $stmt = $pdo->prepare('SELECT * FROM cart_product WHERE cart_id = :cart_id');
    $stmt->bindParam(':cart_id', $cart_id, PDO::PARAM_INT);
    $stmt->execute();
    $cart_products = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    // HTML contentを構築
    $html = '
        <style>
            .bo20 {
                padding-bottom: 20px;
            }
            .line_bot {
                border-bottom: 1px solid black;
            }
            
            table {
                text-align: left;
                font-size: 8px;
                max-width: 350px;
                min-height: 500px;
                border: 1px solid black;
            }
            .brank {
                height: 100%;
            }
            .title {
                font-size: 24px;
                text-align: center;
                padding-top: 20px;
                padding-bottom: 20px;
            }
            .name1 {
                width: 80%!important;
                font-size: 13px;
                padding-top: 20px;
                padding-bottom: 5px; 
            }
            .cost1,
            .number1 {
                width: 10%!important;
                font-size: 13px;
                padding-top: 20px;
                padding-bottom: 5px; 
                text-align: center;
            }
            .name {
                font-size: 7px;
                padding-top: 5px;
            }
            .cost {
                font-size: 8px;
            }
            .number {
                font-size: 13px;
                padding-top: 5px;
                text-align: end;
            }
            .max {
                font-size: 13px;
                padding-top: 5px;
                text-align: end;
            }
            * {
                box-sizing: border-box!important;
            }
        </style>
        <table>
            <tr>
                <th colspan="3" class="title line_bot">レシート</th>
            </tr>
            <tr>
                <td>YYYY/MM/DD</td>
                <td>番号: ' . $cart_id . '</td>
            </tr>
            <tr>
                <th class="name1">商品名</th>
                <th class="cost1">値段</th>
                <th class="number1">個数</th>
            </tr>
            <tr>
                <th colspan="3" class="line_bot"></th>
            </tr>';

    foreach ($cart_products as $cart_product) {
        $stmt = $pdo->prepare("SELECT * FROM products WHERE id = :product_id");
        $stmt->bindParam(':product_id', $cart_product['product_id'], PDO::PARAM_INT);
        $stmt->execute();
        $product = $stmt->fetch(PDO::FETCH_ASSOC);

        $html .= '
            <tr>
                <td class="name">' . $product['name'] . '</td>
                <td class="cost">' . ($product['cost'] * $cart_product['number']) . ' 円</td>
                <td class="number">' . $cart_product['number'] . ' 点</td>
            </tr>';

        $maxCost += $product['cost'] * $cart_product['number'];
        $maxNumber += $cart_product['number'];
    }

    $html .= '
            <tr>
                <th colspan="2" class="max"><p>合計金額: ' . $maxCost . ' 円</p></th>
                <th class="max">' . $maxNumber . ' 点</th>
            </tr>
            <tr>
                <th colspan="3" class="brank"></th>
            </tr>
        </table>';

    // Output HTML content as PDF
    $pdf->writeHTML($html, true, false, true, false, '');

    $pdo->commit();
    
    // Output PDF to the browser
    $pdf->Output('receipt.pdf', 'I');

} catch (Exception $e) {
    echo '<span class="error">データベース接続エラー: ' . $e->getMessage() . '</span><br>';
    exit(); // エラーが発生したら終了
}
?>
