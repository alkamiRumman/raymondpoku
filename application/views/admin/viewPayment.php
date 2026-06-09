<div class="modal" id="modal-default" style="display:block;overflow:auto;">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<div class="modal-header">
				<h4 class="modal-title"><b>Payment Details</b></h4>
				<button type="button" class="btn btn-danger close">Close</button>
			</div>
			<div class="modal-body">
				<div class="row mb-3">
					<div class="col-md-6">
						<table class="table table-sm table-borderless">
							<tbody>
							<tr>
								<th class="text-muted" style="width:40%">Client</th>
								<td><?= htmlspecialchars($payment->clientName) ?></td>
							</tr>
							<tr>
								<th class="text-muted">Payment Date</th>
								<td><?= date('d M Y', strtotime($payment->paymentDate)) ?></td>
							</tr>
							<tr>
								<th class="text-muted">Note</th>
								<td><?= htmlspecialchars($payment->note ?: '—') ?></td>
							</tr>
							</tbody>
						</table>
					</div>
					<div class="col-md-6">
						<table class="table table-sm table-borderless">
							<tbody>
							<tr>
								<th class="text-muted" style="width:50%">Tax Amount</th>
								<td><?= $payment->taxAmount > 0 ? '$' . number_format($payment->taxAmount, 2) : '—' ?></td>
							</tr>
							<tr>
								<th class="text-muted">Total Amount</th>
								<td class="fw-semibold text-success">$<?= number_format($payment->totalAmount, 2) ?></td>
							</tr>
							</tbody>
						</table>
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
				<button type="button" class="btn btn-secondary close">Close</button>
			</div>
		</div>
	</div>
</div>

<script>
$('.close').on('click', function () { $('#remoteModal1').modal('hide'); });
</script>
