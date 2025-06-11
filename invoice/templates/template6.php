<div class="template6 border p-6 font-sans">
    <!-- Header -->
    <div class="grid grid-cols-2 gap-4 mb-6 items-start">
        <div>
            <h2 class="text-2xl font-bold text-indigo-700">Invoice</h2>
            <p class="text-gray-600">#<?php echo $invoice['invoice_number']; ?></p>
            <p class="text-gray-500 text-sm"><?php echo formatDate($invoice['invoice_date']); ?></p>
        </div>
        <?php if ($invoice['logo_path']): ?>
            <div class="flex justify-center items-center">
                <img src="<?php echo $invoice['logo_path']; ?>" 
                     class="rounded-full max-w-[80px] h-auto object-cover shadow-md" 
                     alt="Company Logo" />
            </div>
        <?php endif; ?>
    </div>

    <!-- Customer Info -->
    <div class="mb-6 bg-indigo-50 p-4 rounded-md text-sm">
        <p><span class="font-semibold text-gray-700">Customer:</span> <?php echo $invoice['customer_name']; ?></p>
        <p><span class="font-semibold text-gray-700">Company:</span> <?php echo $invoice['company_name']; ?></p>
    </div>

    <!-- Items Table -->
    <div class="overflow-x-auto">
        <table class="w-full text-sm text-left">
            <thead class="bg-indigo-100 text-indigo-800 uppercase text-xs tracking-wide">
                <tr>
                    <th class="px-4 py-2">Item</th>
                    <th class="px-4 py-2">Qty</th>
                    <th class="px-4 py-2">Price</th>
                    <th class="px-4 py-2">Total</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-indigo-100">
                <?php foreach ($items as $item): ?>
                    <tr class="hover:bg-indigo-50 transition">
                        <td class="px-4 py-3 font-medium text-gray-700"><?php echo $item['name']; ?></td>
                        <td class="px-4 py-3 text-gray-600"><?php echo $item['quantity']; ?></td>
                        <td class="px-4 py-3 text-gray-600">₦<?php echo number_format($item['price'], 2); ?></td>
                        <td class="px-4 py-3 font-semibold text-indigo-700">₦<?php echo number_format($item['amount'], 2); ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

    <!-- Total -->
    <div class="mt-6 text-right text-lg font-bold text-indigo-800">
        Total: ₦<?php echo number_format($invoice['total_amount'], 2); ?>
    </div>
</div>
