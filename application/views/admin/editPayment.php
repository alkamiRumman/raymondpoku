<div class="modal" id="modal-default" style="display:block;overflow:auto;">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<div class="modal-header">
				<h4 class="modal-title"><b>Edit Payment</b></h4>
				<button type="button" class="btn btn-danger close">Close</button>
			</div>
			<form action="<?= admin_url('updatePayment/') . $payment->id ?>" method="post">
				<div class="modal-body">

					<div class="row g-3 mb-3">
						<div class="col-md-4">
							<label class="form-label fw-semibold">Client</label>
							<input type="text" class="form-control" value="<?= htmlspecialchars($payment->clientName) ?>" readonly>
						</div>
						<div class="col-md-3">
							<label class="form-label fw-semibold">Payment Date <sup class="text-danger">*</sup></label>
							<input type="text" id="editPaymentDate" name="paymentDate" class="form-control"
							       value="<?= date('d M Y', strtotime($payment->paymentDate)) ?>" required>
						</div>
						<div class="col-md-5">
							<label class="form-label fw-semibold">Note <span class="text-muted fw-normal">(optional)</span></label>
							<input type="text" name="note" class="form-control"
							       value="<?= htmlspecialchars($payment->note ?: '') ?>"
							       placeholder="e.g. Cheque #1234, e-transfer…">
						</div>
					</div>

					<div class="row g-3 mb-3">
						<div class="col-md-3">
							<label class="form-label fw-semibold">Tax Amount (CAD)</label>
							<div class="input-group">
								<span class="input-group-text">$</span>
								<input type="text" class="form-control" value="<?= number_format($payment->taxAmount, 2) ?>" readonly>
							</div>
						</div>
						<div class="col-md-3">
							<label class="form-label fw-semibold">Total Amount (CAD)</label>
							<div class="input-group">
								<span class="input-group-text">$</span>
								<input type="text" class="form-control fw-semibold" value="<?= number_format($payment->totalAmount, 2) ?>" readonly>
							</div>
						</div>
					</div>

					<hr>

					<h6 class="fw-semibold mb-2">Invoices Applied To</h6>
					<?php if (!empty($items)): ?>
					<div class="table-responsive">
						<table class="table table-sm table-bordered" style="font-size:13px">
							<thead class="table-light">
							<tr>
								<th>#</th>
								<th>Invoice #</th>
								<th>Invoice Total</th>
								<th>Amount Applied</th>
							</tr>
							</thead>
							<tbody>
							<?php foreach ($items as $i => $item): ?>
							<tr>
								<td><?= $i + 1 ?></td>
								<td><?= htmlspecialchars($item->invoiceNumber) ?></td>
								<td>$<?= number_format($item->invoiceTotal, 2) ?></td>
								<td class="text-success fw-semibold">$<?= number_format($item->amountApplied, 2) ?></td>
							</tr>
							<?php endforeach; ?>
							</tbody>
						</table>
					</div>
					<?php else: ?>
					<p class="text-muted small">No invoice allocations found.</p>
					<?php endif; ?>

				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-secondary close me-2">Close</button>
					<button type="submit" class="btn btn-primary">Save Changes</button>
				</div>
			</form>
		</div>
	</div>
</div>

<script>
$('.close').on('click', function () { $('#remoteModal1').modal('hide'); });
$(document).ready(function () {
	$('#editPaymentDate').flatpickr({ dateFormat: 'd M Y' });
});
</script>
