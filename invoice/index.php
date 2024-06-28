<?php
require 'db.php';

// Function to generate a unique invoice number
function generateInvoiceNumber() {
    return 'INV-' . strtoupper(uniqid());
}

// Function to format the invoice date
function formatDate($date) {
    return date('F j, Y', strtotime($date));
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $invoiceNumber = generateInvoiceNumber();
    $customerName = htmlspecialchars(trim($_POST['customerName']));
    $invoiceDate = htmlspecialchars(trim($_POST['invoiceDate']));
    $companyName = htmlspecialchars(trim($_POST['companyName']));
    $additionalInfo = htmlspecialchars(trim($_POST['additionalInfo']));
    $itemNames = $_POST['itemName'];
    $quantities = $_POST['quantity'];
    $prices = $_POST['price'];
    $logoPath = '';

    // Handle logo upload
    if (isset($_FILES['logo']) && $_FILES['logo']['error'] == UPLOAD_ERR_OK) {
        $uploadDir = 'uploads/';
        $logoPath = $uploadDir . basename($_FILES['logo']['name']);
        move_uploaded_file($_FILES['logo']['tmp_name'], $logoPath);
    }

    $items = [];
    $totalAmount = 0;
    for ($i = 0; $i < count($itemNames); $i++) {
        $itemName = htmlspecialchars(trim($itemNames[$i]));
        $quantity = (int)$quantities[$i];
        $price = (float)$prices[$i];
        $amount = $quantity * $price;

        if (!empty($itemName) && $quantity > 0 && $price > 0) {
            $items[] = [
                'name' => $itemName,
                'quantity' => $quantity,
                'price' => $price,
                'amount' => $amount
            ];
            $totalAmount += $amount;
        }
    }

    // Convert items array to JSON for storage
    $itemsJson = json_encode($items);

    // Insert invoice data into the database
    $stmt = $mysqli->prepare('INSERT INTO invoices (invoice_number, customer_name, invoice_date, items, total_amount, status, additional_info, company_name, logo_path) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)');
    $stmt->bind_param('ssssdssss', $invoiceNumber, $customerName, $invoiceDate, $itemsJson, $totalAmount, $status, $additionalInfo, $companyName, $logoPath);

    $status = 'Pending';
    $stmt->execute();
    $stmt->close();

    // Redirect to the invoice page
    header("Location: invoice.php?invoice_number=$invoiceNumber");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invoice Creation App</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <div class="container">
        <h1>Invoice Creation</h1>
        <form action="" method="POST" enctype="multipart/form-data">
            <div class="form-group">
                <label for="customerName">Customer Name:</label>
                <input type="text" id="customerName" name="customerName" required>
            </div>
            <div class="form-group">
                <label for="invoiceDate">Invoice Date:</label>
                <input type="date" id="invoiceDate" name="invoiceDate" required>
            </div>
            <div class="form-group">
                <label for="companyName">Company Name:</label>
                <input type="text" id="companyName" name="companyName" required>
            </div>
            <div class="form-group">
                <label for="additionalInfo">Additional Information:</label>
                <textarea id="additionalInfo" name="additionalInfo" rows="4"></textarea>
            </div>
            <div class="form-group">
                <label for="logo">Company Logo:</label>
                <input type="file" id="logo" name="logo" accept="image/*">
            </div>
            <div id="items">
                <h2>Items</h2>
                <div class="item">
                    <label for="itemName[]">Item Name:</label>
                    <input type="text" id="itemName[]" name="itemName[]" required>
                    <label for="quantity[]">Quantity:</label>
                    <input type="number" id="quantity[]" name="quantity[]" required>
                    <label for="price[]">Price:</label>
                    <input type="number" id="price[]" name="price[]" step="0.01" required>
                </div>
            </div>
            <button type="button" id="addItem">Add Item</button>
            <button type="submit">Generate Invoice</button>
        </form>
        <a href="manage_invoices.php">Manage Invoices</a>
    </div>
    <script>
        document.getElementById('addItem').addEventListener('click', function () {
            var newItem = document.querySelector('.item').cloneNode(true);
            newItem.querySelectorAll('input').forEach(function(input) {
                input.value = '';
            });
            document.getElementById('items').appendChild(newItem);
        });
    </script>
</body>
</html>
