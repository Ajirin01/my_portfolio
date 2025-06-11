<div class="template3 p-6 bg-gray-50 border rounded-lg font-mono">
    <div class="flex justify-between mb-4">
        <div>
            <h1 class="text-2xl font-bold text-blue-700">Invoice</h1>
            <p>#<?php echo $invoice['invoice_number']; ?></p>
            <p><?php echo formatDate($invoice['invoice_date']); ?></p>
        </div>
        <?php if ($invoice['logo_path']): ?>
            <img src="<?php echo $invoice['logo_path']; ?>" class="h-20" />
        <?php endif; ?>
    </div>
    <div class="mb-4">
        <p><strong>To:</strong> <?php echo $invoice['customer_name']; ?> — <?php echo $invoice['company_name']; ?></p>
    </div>
    <table class="w-full text-sm border">
        <thead class="bg-gray-200">
            <tr>
                <th class="p-2 border">Description</th>
                <th class="p-2 border">Qty</th>
                <th class="p-2 border">Rate</th>
                <th class="p-2 border">Total</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($items as $item): ?>
                <tr>
                    <td class="p-2 border"><?php echo $item['name']; ?></td>
                    <td class="p-2 border"><?php echo $item['quantity']; ?></td>
                    <td class="p-2 border">₦<?php echo number_format($item['price'], 2); ?></td>
                    <td class="p-2 border">₦<?php echo number_format($item['amount'], 2); ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
    <div class="text-right mt-4">
        <p class="text-lg font-bold">Total: ₦<?php echo number_format($invoice['total_amount'], 2); ?></p>
    </div>
    <p class="mt-4 italic text-xs text-gray-500"><?php echo nl2br($invoice['additional_info'] ?? ''); ?></p>
</div>