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
$template = $invoice['template'];

function formatDate($date) {
    return date('F j, Y', strtotime($date));
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Invoice #<?php echo $invoice['invoice_number']; ?></title>
  <link rel="stylesheet" href="styles.css">
  <script src="https://cdn.tailwindcss.com"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>
</head>
<body>
  <div class="invoice-container">
    <?php
      $templateFile = "templates/{$template}.php";
      if (file_exists($templateFile)) {
          include $templateFile;
      } else {
          echo "<p>Template not found.</p>";
      }
    ?>
  </div>

  <div class="text-center mb-4 px-8">
    <button 
        onclick="downloadInvoice()" 
        class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700"
    >
        Download Invoice as Image
    </button>
    </div>


    <script>
        function downloadInvoice() {
            const invoice = document.querySelector('.invoice-container');
            html2canvas(invoice, {
                scale: 2, // higher resolution
                backgroundColor: null // retain transparency if needed
            }).then(canvas => {
                const link = document.createElement('a');
                link.download = 'invoice_<?php echo $invoice["invoice_number"]; ?>.png';
                link.href = canvas.toDataURL('image/png');
                link.click();
            });
        }
    </script>

</body>
</html>
