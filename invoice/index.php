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
    $template = $_POST['template'];

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
    $stmt = $mysqli->prepare('INSERT INTO invoices (invoice_number, customer_name, invoice_date, items, total_amount, status, additional_info, company_name, logo_path, template) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)');
    $stmt->bind_param('ssssdsssss', $invoiceNumber, $customerName, $invoiceDate, $itemsJson, $totalAmount, $status, $additionalInfo, $companyName, $logoPath, $template);

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
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Invoice Creation App</title>
  <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 min-h-screen flex items-center justify-center px-4 py-8">
  <div class="w-full max-w-3xl bg-white shadow-xl rounded-xl p-8">
    <h1 class="text-3xl font-bold mb-6 text-center text-gray-800"><a href="manage_invoices.php" class="cursor-default">Create New Invoice</a></h1>
    <form action="" method="POST" enctype="multipart/form-data" class="space-y-6">
      <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        <div>
          <label for="customerName" class="block text-sm font-medium text-gray-700">Customer Name</label>
          <input type="text" id="customerName" name="customerName" required class="mt-1 block w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-500 focus:outline-none"/>
        </div>
        <div>
          <label for="invoiceDate" class="block text-sm font-medium text-gray-700">Invoice Date</label>
          <input type="date" id="invoiceDate" name="invoiceDate" required class="mt-1 block w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-500 focus:outline-none"/>
        </div>
        <div>
          <label for="companyName" class="block text-sm font-medium text-gray-700">Company Name</label>
          <input type="text" id="companyName" name="companyName" required class="mt-1 block w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-500 focus:outline-none"/>
        </div>
        <div>
          <label for="logo" class="block text-sm font-medium text-gray-700">Company Logo</label>
          <input type="file" id="logo" name="logo" accept="image/*" class="mt-1 block w-full border border-gray-300 rounded-lg px-2 py-2 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100"/>
        </div>
      </div>
      <div>
        <label for="additionalInfo" class="block text-sm font-medium text-gray-700">Additional Info</label>
        <textarea id="additionalInfo" name="additionalInfo" rows="4" class="mt-1 block w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-500 focus:outline-none"></textarea>
      </div>

      <div x-data="{ selected: 'template1', scroll: 0 }" class="w-full">
            <h2 class="text-lg font-semibold mb-3 text-gray-700">Choose Invoice Template</h2>
            <input type="hidden" name="template" id="selectedTemplate" x-model="selected" />

            <!-- Carousel Container -->
            <div class="relative">
                <!-- Scroll Buttons -->
                <div @click="scroll -= 300" class="absolute left-0 top-1/2 transform -translate-y-1/2 z-10 bg-white border rounded-full shadow p-2">
                    &#10094;
                </div>
                <div @click="scroll += 300" class="absolute right-0 top-1/2 transform -translate-y-1/2 z-10 bg-white border rounded-full shadow p-2">
                    &#10095;
                </div>

                <!-- Carousel -->
                <div class="overflow-x-auto scrollbar-hide">
                    <div 
                        class="flex gap-4 transition-transform duration-300 ease-in-out"
                        :style="'transform: translateX(-' + scroll + 'px)'"
                    >
                        <!-- Template Cards -->
                        <template x-for="(template, index) in [
                            { id: 'template1', title: 'Modern' },
                            { id: 'template2', title: 'Minimalist' },
                            { id: 'template3', title: 'Classic' },
                            { id: 'template4', title: 'Left Align' },
                            { id: 'template5', title: 'Dark Mode' },
                            { id: 'template6', title: 'Accent Columns' }
                        ]" :key="template.id">
                            <div 
                                class="template-card border rounded-xl p-4 cursor-pointer min-w-[200px] hover:ring-2"
                                :class="selected === template.id ? 'ring-2 ring-blue-500' : 'hover:ring-blue-400'"
                                @click="selected = template.id"
                            >
                                <h3 class="font-bold text-gray-800 mb-2" x-text="template.title"></h3>
                                <div class="h-24 rounded-md overflow-hidden">
                                    <img 
                                        :src="'template-images/' + template.id + '.png'" 
                                        :alt="template.title + ' Preview'" 
                                        :class="selected === template.id ? 'grayscale-0' : 'grayscale hover:grayscale-0'"

                                        class="object-cover w-full h-full rounded-md border border-gray-200"
                                    />
                                </div>
                            </div>
                        </template>
                    </div>
                </div>

            </div>
        </div>

      <div id="items" class="space-y-4">
        <h2 class="text-xl font-semibold text-gray-800">Items</h2>
        <div class="item grid grid-cols-1 md:grid-cols-3 gap-4 bg-gray-50 p-4 rounded-lg border relative">
          <div>
            <label class="block text-sm text-gray-600">Item Name</label>
            <input type="text" name="itemName[]" required class="mt-1 w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-400 focus:outline-none"/>
          </div>
          <div>
            <label class="block text-sm text-gray-600">Quantity</label>
            <input type="number" name="quantity[]" required class="mt-1 w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-400 focus:outline-none"/>
          </div>
          <div>
            <label class="block text-sm text-gray-600">Price</label>
            <input type="number" name="price[]" step="0.01" required class="mt-1 w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-400 focus:outline-none"/>
          </div>
          <button type="button" class="remove-item absolute top-2 right-2 text-red-500 hover:text-red-700 text-sm">Remove</button>
        </div>
      </div>

      <div class="flex justify-between mt-4">
        <button type="button" id="addItem" class="text-sm bg-white text-blue-600 border border-blue-500 px-4 py-2 rounded-lg hover:bg-blue-50 transition-all">+ Add Item</button>
        <button type="submit" class="bg-blue-600 text-white px-6 py-2 rounded-lg hover:bg-blue-700 transition-all">Generate Invoice</button>
      </div>


      <!-- <div class="text-center mt-6">
        <a href="manage_invoices.php" class="text-blue-600 hover:underline text-sm">Go to Manage Invoices</a>
      </div> -->
    </form>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>


  <script>
    const addItemBtn = document.getElementById('addItem');
    const itemsContainer = document.getElementById('items');

    function bindRemoveButtons() {
      document.querySelectorAll('.remove-item').forEach(btn => {
        btn.onclick = () => {
          const itemBlocks = document.querySelectorAll('.item');
          if (itemBlocks.length > 1) {
            btn.parentElement.remove();
          } else {
            alert("At least one item is required.");
          }
        };
      });
    }

    addItemBtn.addEventListener('click', () => {
      const firstItem = document.querySelector('.item');
      const clone = firstItem.cloneNode(true);
      clone.querySelectorAll('input').forEach(input => input.value = '');
      itemsContainer.appendChild(clone);
      bindRemoveButtons();
    });

    // Initial binding
    bindRemoveButtons();
  </script>

  <script>
    const templateCards = document.querySelectorAll('.template-card');
    const selectedInput = document.getElementById('selectedTemplate');

    templateCards.forEach(card => {
        card.addEventListener('click', () => {
        templateCards.forEach(c => c.classList.remove('ring-2', 'ring-blue-500'));
        card.classList.add('ring-2', 'ring-blue-500');
        selectedInput.value = card.dataset.template;
        });
    });

    // Set the first template as default visually
    templateCards[0].classList.add('ring-2', 'ring-blue-500');
    </script>

</body>
</html>
