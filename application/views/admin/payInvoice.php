<div class="modal" id="modal-default" style="display:block;overflow:auto;">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">

			<!-- Header -->
			<div class="modal-header" style="background:#1a3c5e;">
				<div>
					<h5 class="modal-title text-white fw-bold mb-0">Pay Invoice</h5>
					<small class="text-white-50"><?= htmlspecialchars($data->invoiceNumber) ?>
						<?php
							$statusColors = ['Sent' => '#f59e0b', 'Partial Paid' => '#3b82f6', 'Fully Paid' => '#22c55e'];
							$sc = $statusColors[$data->status] ?? '#6b7280';
						?>
						&nbsp;<span class="badge rounded-pill" style="background:<?= $sc ?>;font-size:11px;"><?= htmlspecialchars($data->status) ?></span>
					</small>
				</div>
				<button type="button" class="btn btn-sm btn-outline-light close">Close</button>
			</div>

			<form action="<?= admin_url('savePay/') . $data->id ?>" method="post">
				<input type="hidden" name="total"    value="<?= $data->total ?>">
				<input type="hidden" name="clientId" value="<?= $data->clientId ?>">

				<div class="modal-body p-4">

					<!-- Bill To + Invoice Meta -->
					<div class="row g-4 mb-4">
						<div class="col-md-6">
							<div class="p-3 rounded-3 h-100" style="background:#f8fafc;border-left:4px solid #1a3c5e;">
								<div class="fw-semibold mb-2" style="font-size:11px;letter-spacing:.08em;text-transform:uppercase;color:#64748b;">Bill To</div>
								<?php if (!empty($data->companyName)): ?>
								<div class="fw-bold fs-6"><?= htmlspecialchars($data->companyName) ?></div>
								<?php endif; ?>
								<div class="fw-semibold"><?= htmlspecialchars($data->name) ?></div>
								<?php if (!empty($data->phone)): ?>
								<div class="text-muted small"><?= htmlspecialchars($data->phone) ?></div>
								<?php endif; ?>
								<?php if (!empty($data->billingAddress)): ?>
								<div class="text-muted small"><?= nl2br(htmlspecialchars($data->billingAddress)) ?></div>
								<?php endif; ?>
							</div>
						</div>
						<div class="col-md-6">
							<div class="card border shadow-sm h-100">
								<div class="card-body p-3">
									<div class="row g-2" style="font-size:13px;">
										<div class="col-6">
											<div class="text-muted" style="font-size:11px;text-transform:uppercase;font-weight:600;">Invoice #</div>
											<div class="fw-bold"><?= htmlspecialchars($data->invoiceNumber) ?></div>
										</div>
										<?php if (!empty($data->poNumber)): ?>
										<div class="col-6">
											<div class="text-muted" style="font-size:11px;text-transform:uppercase;font-weight:600;">P.O / S.O #</div>
											<div><?= htmlspecialchars($data->poNumber) ?></div>
										</div>
										<?php endif; ?>
										<div class="col-6">
											<div class="text-muted" style="font-size:11px;text-transform:uppercase;font-weight:600;">Invoice Date</div>
											<div><?= date('d M Y', strtotime($data->invoiceDate)) ?></div>
										</div>
										<div class="col-6">
											<div class="text-muted" style="font-size:11px;text-transform:uppercase;font-weight:600;">Payment Due</div>
											<div><?= date('d M Y', strtotime($data->dueDate)) ?></div>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>

					<!-- Financial Summary Bar -->
					<div class="row g-3 mb-4">
						<div class="col-4">
							<div class="rounded-3 p-3 text-center" style="background:#f8fafc;border:1px solid #e2e8f0;">
								<div class="text-muted" style="font-size:11px;text-transform:uppercase;font-weight:600;">Amount Due</div>
								<div class="fw-bold fs-5">$<?= number_format($data->total, 2) ?></div>
							</div>
						</div>
						<div class="col-4">
							<div class="rounded-3 p-3 text-center" style="background:#f0fdf4;border:1px solid #bbf7d0;">
								<div class="text-muted" style="font-size:11px;text-transform:uppercase;font-weight:600;">Paid to Date</div>
								<div class="fw-bold fs-5 text-success">$<?= number_format($data->paidAmount, 2) ?></div>
							</div>
						</div>
						<div class="col-4">
							<div class="rounded-3 p-3 text-center" style="background:#fff7ed;border:1px solid #fed7aa;">
								<div style="font-size:11px;text-transform:uppercase;font-weight:600;color:#92400e;">Balance Owing</div>
								<div class="fw-bold fs-5 text-danger">$<?= number_format($data->total - $data->paidAmount, 2) ?></div>
							</div>
						</div>
					</div>

					<!-- Payment History -->
					<div class="mb-4">
						<div class="fw-semibold mb-2" style="font-size:11px;letter-spacing:.08em;text-transform:uppercase;color:#64748b;">Payment History</div>
						<?php if (!empty($payments)): ?>
						<div class="table-responsive rounded-3 border">
							<table class="table table-sm align-middle mb-0" style="font-size:13px;">
								<thead class="table-light">
								<tr>
									<th class="ps-3">#</th>
									<th>Date &amp; Time</th>
									<th class="text-end">Amount (CAD)</th>
									<th class="pe-3">Note</th>
								</tr>
								</thead>
								<tbody>
								<?php foreach ($payments as $i => $p): ?>
								<tr>
									<td class="ps-3"><?= $i + 1 ?></td>
									<td><?= date('d M Y, g:i A', strtotime($p->paidAt)) ?></td>
									<td class="text-end text-success fw-semibold">$<?= number_format($p->amount, 2) ?></td>
									<td class="pe-3 text-muted"><?= htmlspecialchars($p->note ?: '—') ?></td>
								</tr>
								<?php endforeach; ?>
								</tbody>
								<tfoot>
								<tr style="background:#f8fafc;">
									<td colspan="2" class="text-end fw-bold ps-3">Total Paid</td>
									<td class="text-end text-success fw-bold">$<?= number_format($data->paidAmount, 2) ?></td>
									<td class="pe-3"></td>
								</tr>
								</tfoot>
							</table>
						</div>
						<?php else: ?>
						<div class="rounded-3 border text-center py-4 text-muted" style="background:#fafafa;">
							<i data-feather="credit-card" style="width:28px;height:28px;opacity:.25;" class="d-block mx-auto mb-2"></i>
							<span style="font-size:13px;">No payments recorded yet.</span>
						</div>
						<?php endif; ?>
					</div>

					<!-- New Payment -->
					<?php if ($data->total > $data->paidAmount): ?>
					<div class="rounded-3 p-3" style="background:#f0fdf4;border:1px solid #bbf7d0;">
						<div class="fw-semibold small mb-3" style="color:#166534;text-transform:uppercase;letter-spacing:.06em;">
							<i data-feather="dollar-sign" style="width:13px;height:13px;vertical-align:middle;margin-right:4px;"></i>
							Record New Payment
						</div>
						<div class="row g-3">
							<div class="col-md-4">
								<label class="form-label small fw-semibold mb-1">Payment Date</label>
								<input type="text" id="payInvoiceDate" name="payDate"
								       class="form-control form-control-sm" placeholder="Select date">
							</div>
							<div class="col-md-4">
								<label class="form-label small fw-semibold mb-1">Amount (CAD)</label>
								<div class="input-group input-group-sm">
									<span class="input-group-text">$</span>
									<input type="number" id="payAmount" step="0.01" min="0.01"
									       max="<?= $data->total - $data->paidAmount ?>"
									       name="paidAmount" class="form-control" placeholder="0.00" required>
								</div>
							</div>
							<div class="col-md-4">
								<label class="form-label small fw-semibold mb-1">Note <span class="text-muted fw-normal">(optional)</span></label>
								<input type="text" id="payNote" name="payNote" class="form-control form-control-sm"
								       placeholder="e.g. Cheque #1234…">
							</div>
						</div>
					</div>
					<?php else: ?>
					<div class="rounded-3 p-3 text-center" style="background:#f0fdf4;border:1px solid #bbf7d0;">
						<i data-feather="check-circle" style="width:28px;height:28px;color:#16a34a;" class="d-block mx-auto mb-2"></i>
						<span class="fw-semibold text-success">This invoice is fully paid.</span>
					</div>
					<?php endif; ?>

				</div>

				<div class="modal-footer d-flex justify-content-end gap-2">
					<button type="button" class="btn btn-secondary close">Close</button>
					<?php if ($data->total > $data->paidAmount): ?>
					<button type="submit" class="btn btn-primary">
						<i data-feather="save" style="width:13px;height:13px;vertical-align:middle;margin-right:4px;"></i>
						Record Payment
					</button>
					<?php endif; ?>
				</div>
			</form>
		</div>
	</div>
</div>

<script>
$('.close').on('click', function () { $('#remoteModal1').modal('hide'); });
$(document).ready(function () {
	feather.replace();
	$('#payInvoiceDate').flatpickr({ defaultDate: 'today', dateFormat: 'd M Y' });
});
</script>
