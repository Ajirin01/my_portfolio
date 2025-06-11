<div class="template2 bg-white border p-6 shadow-md font-serif">
    <div class="text-center">
        <h1 class="text-4xl font-bold">Invoice</h1>
        <p class="text-sm">Invoice #: <?php echo $invoice['invoice_number']; ?> | Date: <?php echo formatDate($invoice['invoice_date']); ?></p>
    </div>
    <?php if ($invoice['logo_path']): ?>
        <div class="flex justify-center my-4">
            <img src="<?php echo $invoice['logo_path']; ?>" class="h-16"/>
        </div>
    <?php endif; ?>
    <p><strong>Billed To:</strong> <?php echo $invoice['customer_name']; ?> (<?php echo $invoice['company_name']; ?>)</p>
    <table class="w-full mt-4 border text-sm">
        <thead>
            <tr class="bg-blue-100">
                <th class="border p-2">Item</th>
                <th class="border p-2">Qty</th>
                <th class="border p-2">Unit Price</th>
                <th class="border p-2">Subtotal</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($items as $item): ?>
                <tr>
                    <td class="border p-2"><?php echo $item['name']; ?></td>
                    <td class="border p-2"><?php echo $item['quantity']; ?></td>
                    <td class="border p-2">₦<?php echo number_format($item['price'], 2); ?></td>
                    <td class="border p-2">₦<?php echo number_format($item['amount'], 2); ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
    <p class="text-right mt-4 font-bold">Total Due: ₦<?php echo number_format($invoice['total_amount'], 2); ?></p>
    <p class="mt-2 text-xs text-gray-600"><?php echo nl2br($invoice['additional_info'] ?? ''); ?></p>
</div>