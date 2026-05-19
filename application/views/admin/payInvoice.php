<div class="modal" id="modal-default" style="display: block; overflow: auto;">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<div class="modal-header">
				<h4 class="modal-title"><b>Pay Invoice</b></h4>
				<button type="button" class="btn btn-danger close">Close</button>
			</div>
			<form class="forms-sample" action="<?= admin_url('savePay/') . $data->id ?>" method="post">
				<div class="modal-body">

					<!-- Bill To + Invoice Meta -->
					<div class="row mb-3">
						<div class="col-md-6">
							<h5 class="fw-semibold mb-1">BILL TO</h5>
							<h6 class="mb-0"><?= $data->companyName ?></h6>
							<p class="mb-1"><?= $data->name ?><br><?= $data->phone ?><br><?= $data->billingAddress ?></p>
						</div>
						<div class="col-md-6">
							<table class="table table-borderless table-sm mb-0" style="font-size:14px">
								<tbody>
								<tr>
									<th class="text-end pe-2 fw-semibold" style="width:55%">Invoice #:</th>
									<td><?= $data->invoiceNumber ?></td>
								</tr>
								<tr>
									<th class="text-end pe-2 fw-semibold">P.O / S.O #:</th>
									<td><?= $data->poNumber ?></td>
								</tr>
								<tr>
									<th class="text-end pe-2 fw-semibold">Invoice Date:</th>
									<td><?= date('d M Y', strtotime($data->invoiceDate)) ?></td>
								</tr>
								<tr>
									<th class="text-end pe-2 fw-semibold">Payment Due:</th>
									<td><?= date('d M Y', strtotime($data->dueDate)) ?></td>
								</tr>
								<tr>
									<th class="text-end pe-2 fw-semibold">Amount Due (CAD):</th>
									<td class="fw-bold fs-5">$<?= number_format($data->total, 2) ?></td>
								</tr>
								<tr>
									<th class="text-end pe-2 fw-semibold">Already Paid (CAD):</th>
									<td class="text-success fw-semibold">$<?= number_format($data->paidAmount, 2) ?></td>
								</tr>
								<tr class="table-warning">
									<th class="text-end pe-2 fw-semibold">Balance Remaining (CAD):</th>
									<td class="fw-bold">$<?= number_format($data->total - $data->paidAmount, 2) ?></td>
								</tr>
								</tbody>
							</table>
						</div>
					</div>

					<hr>

					<!-- Payment History -->
					<h6 class="fw-semibold mb-2">Payment History</h6>
					<?php if (!empty($payments)): ?>
					<div class="table-responsive mb-3">
						<table class="table table-sm table-bordered" style="font-size:13px">
							<thead class="table-light">
							<tr>
								<th>#</th>
								<th>Date &amp; Time</th>
								<th>Amount (CAD)</th>
								<th>Note</th>
							</tr>
							</thead>
							<tbody>
							<?php foreach ($payments as $i => $p): ?>
							<tr>
								<td><?= $i + 1 ?></td>
								<td><?= date('d M Y, g:i A', strtotime($p->paidAt)) ?></td>
								<td class="text-success fw-semibold">$<?= number_format($p->amount, 2) ?></td>
								<td><?= htmlspecialchars($p->note ?: '—') ?></td>
							</tr>
							<?php endforeach; ?>
							<tr class="table-light fw-bold">
								<td colspan="2" class="text-end">Total Paid</td>
								<td class="text-success">$<?= number_format($data->paidAmount, 2) ?></td>
								<td></td>
							</tr>
							</tbody>
						</table>
					</div>
					<?php else: ?>
					<p class="text-muted small mb-3">No payments recorded yet.</p>
					<?php endif; ?>

					<!-- New Payment Input -->
					<?php if ($data->total > $data->paidAmount): ?>
					<div class="row g-3 align-items-end">
						<div class="col-md-4">
							<label for="payAmount" class="form-label fw-semibold">Pay Amount (CAD)</label>
							<input type="number" id="payAmount" step="0.01" min="0.01"
								   max="<?= $data->total - $data->paidAmount ?>"
								   name="paidAmount" class="form-control" placeholder="0.00" required>
						</div>
						<div class="col-md-8">
							<label for="payNote" class="form-label fw-semibold">Note <span class="text-muted fw-normal">(optional)</span></label>
							<input type="text" id="payNote" name="payNote" class="form-control" placeholder="e.g. Cheque #1234, e-transfer, etc.">
						</div>
					</div>
					<?php else: ?>
					<div class="alert alert-success mb-0">This invoice is fully paid.</div>
					<?php endif; ?>

					<input type="hidden" name="total"    value="<?= $data->total ?>">
					<input type="hidden" name="clientId" value="<?= $data->clientId ?>">

				</div>
				<div class="modal-footer">
					<?php if ($data->total > $data->paidAmount): ?>
					<button type="submit" class="btn btn-primary">Record Payment</button>
					<?php endif; ?>
					<button type="button" class="btn btn-secondary close">Close</button>
				</div>
			</form>
		</div>
	</div>
</div>
<script>
	$('.close').on('click', function () {
		$("#remoteModal1").modal('hide');
	});
</script>
