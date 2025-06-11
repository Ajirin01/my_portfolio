<div class="template5 bg-gray-900 text-white p-8 rounded-lg font-mono shadow-lg">
    <!-- Header with logo -->
    <div class="flex justify-between items-center">
        <h2 class="text-3xl font-bold">INVOICE</h2>
        <div class="text-right text-sm">
            <?php if ($invoice['logo_path']): ?>
                <div class="mb-2 flex justify-end">
                    <img src="<?php echo $invoice['logo_path']; ?>" alt="Logo"
                         class="h-16 w-16 rounded-full object-cover border-2 border-gray-700" />
                </div>
            <?php endif; ?>
            <p class="font-semibold">Invoice #: <?php echo $invoice['invoice_number']; ?></p>
            <p>Date: <?php echo formatDate($invoice['invoice_date']); ?></p>
        </div>
    </div>

    <!-- Divider -->
    <hr class="my-4 border-gray-700">

    <!-- Parties Info -->
    <div class="mb-4 text-sm">
        <p><strong>From:</strong> <?php echo $invoice['company_name']; ?></p>
        <p><strong>To:</strong> <?php echo $invoice['customer_name']; ?></p>
        <p><strong>Additional Information:</strong> <?php echo $invoice['additional_info']; ?></p>
    </div>

    <!-- Item Table -->
    <table class="w-full mt-4 text-sm">
        <thead>
            <tr class="bg-gray-800 text-gray-600 uppercase text-xs tracking-wider">
                <th class="text-left p-2">Item</th>
                <th class="text-right p-2">Qty</th>
                <th class="text-right p-2">Price</th>
                <th class="text-right p-2">Amount</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($items as $item): ?>
                <tr class="border-t border-gray-800 hover:bg-gray-800">
                    <td class="p-2"><?php echo $item['name']; ?></td>
                    <td class="p-2 text-right"><?php echo $item['quantity']; ?></td>
                    <td class="p-2 text-right">₦<?php echo number_format($item['price'], 2); ?></td>
                    <td class="p-2 text-right">₦<?php echo number_format($item['amount'], 2); ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    <!-- Total -->
    <div class="mt-6 text-right text-lg font-bold text-white">
        TOTAL: ₦<?php echo number_format($invoice['total_amount'], 2); ?>
    </div>
</div>
