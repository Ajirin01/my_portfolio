<?php
require 'db.php';

// Handle status update
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['invoice_id'])) {
    $invoiceId = intval($_POST['invoice_id']);
    $newStatus = htmlspecialchars(trim($_POST['status']));

    $stmt = $mysqli->prepare('UPDATE invoices SET status = ? WHERE id = ?');
    $stmt->bind_param('si', $newStatus, $invoiceId);
    $stmt->execute();
    $stmt->close();
}

// Fetch all invoices
$result = $mysqli->query('SELECT * FROM invoices ORDER BY invoice_date DESC');
$invoices = $result->fetch_all(MYSQLI_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invoice Manager</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <div class="container">
        <h1>Invoice Manager</h1>
        <table>
            <thead>
                <tr>
                    <th>Invoice Number</th>
                    <th>Customer Name</th>
                    <th>Invoice Date</th>
                    <th>Total Amount (₦)</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($invoices as $invoice): ?>
                    <tr>
                        <td><?php echo $invoice['invoice_number']; ?></td>
                        <td><?php echo $invoice['customer_name']; ?></td>
                        <td><?php echo date('F j, Y', strtotime($invoice['invoice_date'])); ?></td>
                        <td>₦<?php echo number_format($invoice['total_amount'], 2); ?></td>
                        <td>
                            <form action="" method="POST">
                                <input type="hidden" name="invoice_id" value="<?php echo $invoice['id']; ?>">
                                <select name="status" onchange="this.form.submit()">
                                    <option value="Pending" <?php echo $invoice['status'] == 'Pending' ? 'selected' : ''; ?>>Pending</option>
                                    <option value="Paid" <?php echo $invoice['status'] == 'Paid' ? 'selected' : ''; ?>>Paid</option>
                                    <option value="Cancelled" <?php echo $invoice['status'] == 'Cancelled' ? 'selected' : ''; ?>>Cancelled</option>
                                </select>
                            </form>
                        </td>
                        <td><a href="invoice.php?invoice_number=<?php echo $invoice['invoice_number']; ?>">View</a></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</body>
</html>
