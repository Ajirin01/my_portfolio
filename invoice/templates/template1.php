<div class="template1 p-8 font-sans">
    <div class="flex justify-between items-center">
        <?php if ($invoice['logo_path']): ?>
            <img src="<?php echo $invoice['logo_path']; ?>" class="w-24 h-24 object-contain"/>
        <?php endif; ?>
        <div class="text-right">
            <h1 class="text-3xl font-bold">Invoice</h1>
            <p><strong>#:</strong> <?php echo $invoice['invoice_number']; ?></p>
            <p><strong>Date:</strong> <?php echo formatDate($invoice['invoice_date']); ?></p>
        </div>
    </div>
    <hr class="my-4">
    <div>
        <p><strong>Customer:</strong> <?php echo $invoice['customer_name']; ?></p>
        <p><strong>Company:</strong> <?php echo $invoice['company_name']; ?></p>
    </div>
    <table class="w-full mt-6 border text-sm">
        <thead class="bg-gray-100">
            <tr>
                <th class="border p-2">Item</th>
                <th class="border p-2">Qty</th>
                <th class="border p-2">Price</th>
                <th class="border p-2">Amount</th>
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
    <div class="mt-4 text-right">
        <p class="text-lg font-semibold">Total: ₦<?php echo number_format($invoice['total_amount'], 2); ?></p>
    </div>
    <p class="mt-4 text-sm">Note: <?php echo nl2br($invoice['additional_info'] ?? ''); ?></p>
</div>