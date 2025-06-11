<div class="template4 p-6 font-sans">
    <div class="mb-6">
        <?php if ($invoice['logo_path']): ?>
            <img src="<?php echo $invoice['logo_path']; ?>" class="h-16 mb-2" />
        <?php endif; ?>
        <h2 class="text-2xl font-bold">Invoice</h2>
        <p>Invoice #: <?php echo $invoice['invoice_number']; ?></p>
        <p>Date: <?php echo formatDate($invoice['invoice_date']); ?></p>
    </div>
    <div class="mb-4">
        <strong>To:</strong> <?php echo $invoice['customer_name']; ?><br>
        <strong>Company:</strong> <?php echo $invoice['company_name']; ?>
    </div>
    <ul class="divide-y border-t border-b text-sm">
        <?php foreach ($items as $item): ?>
            <li class="py-2 flex justify-between">
                <span><?php echo $item['name']; ?> (x<?php echo $item['quantity']; ?>)</span>
                <span>₦<?php echo number_format($item['amount'], 2); ?></span>
            </li>
        <?php endforeach; ?>
    </ul>
    <div class="mt-4 text-right">
        <strong>Total:</strong> ₦<?php echo number_format($invoice['total_amount'], 2); ?>
    </div>
</div>
