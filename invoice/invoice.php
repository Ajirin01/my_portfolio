<?php
require 'db.php';

if (!isset($_GET['invoice_number'])) {
    die('Invoice number not specified.');
}

$invoiceNumber = $_GET['invoice_number'];
$stmt = $mysqli->prepare('SELECT * FROM invoices WHERE invoice_number = ?');
$stmt->bind_param('s', $invoiceNumber);
$stmt->execute();
$result = $stmt->get_result();
$invoice = $result->fetch_assoc();

if (!$invoice) {
    die('Invoice not found.');
}

$items = json_decode($invoice['items'], true);

function formatDate($date) {
    return date('F j, Y', strtotime($date));
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invoice #<?php echo $invoice['invoice_number']; ?></title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <div class="invoice-container">
        <?php if ($invoice['logo_path']): ?>
            <img src="<?php echo $invoice['logo_path']; ?>" alt="Company Logo" class="invoice-logo">
        <?php endif; ?>
        <div class="invoice-header">
            <h1><?php echo $invoice['company_name']; ?></h1>
        </div>
        <div class="invoice">
            <h2>Invoice</h2>
            <p><strong>Invoice Number:</strong> <?php echo $invoice['invoice_number']; ?></p>
            <p><strong>Customer Name:</strong> <?php echo $invoice['customer_name']; ?></p>
            <p><strong>Invoice Date:</strong> <?php echo formatDate($invoice['invoice_date']); ?></p>
            <p><strong>Additional Information:</strong> <?php echo nl2br($invoice['additional_info'] ?? ''); ?></p>
            <table>
                <thead>
                    <tr>
                        <th>Item Name</th>
                        <th>Quantity</th>
                        <th>Price (₦)</th>
                        <th>Amount (₦)</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($items as $item): ?>
                        <tr>
                            <td><?php echo $item['name']; ?></td>
                            <td><?php echo $item['quantity']; ?></td>
                            <td>₦<?php echo number_format($item['price'], 2); ?></td>
                            <td>₦<?php echo number_format($item['amount'], 2); ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
            <p><strong>Total Amount:</strong> ₦<?php echo number_format($invoice['total_amount'], 2); ?></p>
            <button onclick="window.print()">Print Invoice</button>
        </div>
    </div>
</body>
</html>
