<?php
include '../dbconnect.php';

if (!isset($_GET['order_id']) || empty($_GET['order_id'])) {
    die('Invalid order ID.');
}

$order_id = (int) $_GET['order_id'];

try {
    $stmt_order = $pdo->prepare("
        SELECT o.*
        FROM orders o
        WHERE o.order_id = :order_id
        LIMIT 1
    ");
    $stmt_order->execute(['order_id' => $order_id]);
    $order = $stmt_order->fetch(PDO::FETCH_ASSOC);

    if (!$order) {
        die('Order not found.');
    }

    $stmt_items = $pdo->prepare("
        SELECT *
        FROM order_items
        WHERE order_id = :order_id
        ORDER BY order_item_id ASC
    ");
    $stmt_items->execute(['order_id' => $order_id]);
    $order_items = $stmt_items->fetchAll(PDO::FETCH_ASSOC);
} catch (Exception $e) {
    die('Error: ' . $e->getMessage());
}

function pdf_escape($text)
{
    $text = (string) $text;
    $text = str_replace("\\", "\\\\", $text);
    $text = str_replace("(", "\\(", $text);
    $text = str_replace(")", "\\)", $text);
    $text = str_replace("\r", "", $text);
    return $text;
}

function to_pdf_text($text)
{
    $text = (string) $text;
    $converted = @iconv('UTF-8', 'Windows-1252//TRANSLIT', $text);
    if ($converted === false) {
        $converted = $text;
    }
    return pdf_escape($converted);
}

function wrap_pdf_line($text, $maxChars = 90)
{
    return wordwrap($text, $maxChars, "\n", true);
}

function build_page_stream(array $lines)
{
    $y = 800;
    $lineHeight = 16;
    $stream = "BT\n/F1 12 Tf\n";

    foreach ($lines as $line) {
        $safe = to_pdf_text($line);
        $stream .= "1 0 0 1 50 {$y} Tm ({$safe}) Tj\n";
        $y -= $lineHeight;
    }

    $stream .= "ET";
    return $stream;
}

$lines = [];
$lines[] = 'Elegance Salon - Order Invoice';
$lines[] = '';
$lines[] = 'Order Details';
$lines[] = 'Order ID: ' . $order['order_id'];
$lines[] = 'Customer Name: ' . $order['first_name'] . ' ' . $order['last_name'];
$lines[] = 'Email: ' . $order['email'];
$lines[] = 'Telephone: ' . $order['telephone'];
$lines[] = 'Address: ' . $order['address'];
$lines[] = 'City: ' . $order['city'];
$lines[] = 'Postal Code: ' . $order['postal_code'];
$lines[] = 'Country: ' . $order['country'];
$lines[] = 'Payment Method: ' . ucfirst(str_replace('_', ' ', $order['payment_method']));
$lines[] = 'Status: ' . ucfirst($order['status']);
$lines[] = 'Total: PKR ' . number_format($order['total'], 2);
$lines[] = '';
$lines[] = 'Order Items';

foreach ($order_items as $item) {
    $itemLine = 'Product: ' . $item['product_name']
        . ' | Qty: ' . $item['quantity']
        . ' | Price: PKR ' . number_format($item['price'], 2)
        . ' | Total: PKR ' . number_format($item['total'], 2);

    $wrapped = explode("\n", wrap_pdf_line($itemLine, 90));
    foreach ($wrapped as $wline) {
        $lines[] = $wline;
    }
}

$lines[] = '';
$lines[] = 'Thank you for shopping with Elegance Salon!';

$pages = [];
$perPage = 40;
for ($i = 0; $i < count($lines); $i += $perPage) {
    $pages[] = array_slice($lines, $i, $perPage);
}

$pdfObjects = [];
$offsets = [];
$pdf = "%PDF-1.4\n";
$pdf .= "%\xE2\xE3\xCF\xD3\n";

$objectNumber = 1;

// 1: Catalog
$pdfObjects[$objectNumber] = "<< /Type /Catalog /Pages 2 0 R >>";
$objectNumber++;

// 2: Pages placeholder
$pdfObjects[$objectNumber] = "";
$objectNumber++;

// 3: Font
$pdfObjects[$objectNumber] = "<< /Type /Font /Subtype /Type1 /BaseFont /Helvetica >>";
$objectNumber++;

$pageObjectNumbers = [];
$contentObjectNumbers = [];

foreach ($pages as $index => $pageLines) {
    $contentObj = $objectNumber++;
    $pageObj = $objectNumber++;

    $contentObjectNumbers[] = $contentObj;
    $pageObjectNumbers[] = $pageObj;

    $stream = build_page_stream($pageLines);
    $pdfObjects[$contentObj] = "<< /Length " . strlen($stream) . " >>\nstream\n{$stream}\nendstream";
    $pdfObjects[$pageObj] = "<< /Type /Page /Parent 2 0 R /MediaBox [0 0 595 842] /Resources << /Font << /F1 3 0 R >> >> /Contents {$contentObj} 0 R >>";
}

$kids = [];
foreach ($pageObjectNumbers as $pageObjNum) {
    $kids[] = $pageObjNum . " 0 R";
}
$pdfObjects[2] = "<< /Type /Pages /Kids [ " . implode(' ', $kids) . " ] /Count " . count($pageObjectNumbers) . " >>";

// Build final PDF with xref
$objectOffsets = [];
foreach ($pdfObjects as $objNum => $objContent) {
    $objectOffsets[$objNum] = strlen($pdf);
    $pdf .= $objNum . " 0 obj\n";
    $pdf .= $objContent . "\n";
    $pdf .= "endobj\n";
}

$xrefPosition = strlen($pdf);
$maxObjectNum = max(array_keys($pdfObjects));
$pdf .= "xref\n";
$pdf .= "0 " . ($maxObjectNum + 1) . "\n";
$pdf .= "0000000000 65535 f \n";

for ($i = 1; $i <= $maxObjectNum; $i++) {
    $offset = isset($objectOffsets[$i]) ? $objectOffsets[$i] : 0;
    $pdf .= sprintf("%010d 00000 n \n", $offset);
}

$pdf .= "trailer\n";
$pdf .= "<< /Size " . ($maxObjectNum + 1) . " /Root 1 0 R >>\n";
$pdf .= "startxref\n";
$pdf .= $xrefPosition . "\n";
$pdf .= "%%EOF";

while (ob_get_level()) {
    ob_end_clean();
}

header('Content-Type: application/pdf');
header('Content-Disposition: attachment; filename="order_' . $order['order_id'] . '.pdf"');
header('Content-Length: ' . strlen($pdf));

echo $pdf;
exit;
