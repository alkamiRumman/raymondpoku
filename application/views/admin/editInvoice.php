<div class="modal" id="modal-default" style="display:block;overflow:auto;">
	<div class="modal-dialog modal-xl">
		<div class="modal-content">

			<!-- Header -->
			<div class="modal-header" style="background:#1a3c5e;">
				<div>
					<h5 class="modal-title text-white mb-0 fw-bold">Update Invoice</h5>
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

			<form action="<?= admin_url('savePay/') . $data->id ?>" method="post" enctype="multipart/form-data">
				<input type="hidden" name="clientId"     value="<?= $data->clientId ?>">
				<input type="hidden" name="invoiceNumber" value="<?= $data->invoiceNumber ?>">
				<input type="hidden" name="totalHours"   id="totalHours" value="<?= $data->totalHours ?>">

				<div class="modal-body p-4">

					<!-- ── Section 1: Invoice Info ─────────────────────────────── -->
					<div class="row g-4 mb-4">

						<!-- Bill To -->
						<div class="col-md-7">
							<label class="form-label fw-semibold text-muted" style="font-size:11px;letter-spacing:.05em;text-transform:uppercase;">Invoice Title</label>
							<input type="text" name="title" class="form-control fw-semibold mb-3"
							       value="<?= htmlspecialchars($data->title) ?>" placeholder="Invoice Title" required>

							<div class="p-3 rounded-3" style="background:#f8fafc;border-left:4px solid #1a3c5e;">
								<div class="fw-semibold mb-1" style="font-size:11px;letter-spacing:.08em;text-transform:uppercase;color:#64748b;">Bill To</div>
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

						<!-- Invoice Meta -->
						<div class="col-md-5">
							<div class="card border shadow-sm h-100">
								<div class="card-body p-3">
									<div class="row g-3">
										<div class="col-6">
											<label class="form-label fw-semibold text-muted" style="font-size:11px;text-transform:uppercase;">Invoice #</label>
											<div class="fw-bold"><?= htmlspecialchars($data->invoiceNumber) ?></div>
										</div>
										<div class="col-6">
											<label class="form-label fw-semibold text-muted" style="font-size:11px;text-transform:uppercase;">P.O / S.O #</label>
											<input type="text" class="form-control form-control-sm" name="poNumber"
											       value="<?= htmlspecialchars($data->poNumber) ?>">
										</div>
										<div class="col-6">
											<label class="form-label fw-semibold text-muted" style="font-size:11px;text-transform:uppercase;">Invoice Date</label>
											<input class="form-control form-control-sm" name="invoiceDate" id="invoiceDate"
											       value="<?= date('d M Y', strtotime($data->invoiceDate)) ?>">
										</div>
										<div class="col-6">
											<label class="form-label fw-semibold text-muted" style="font-size:11px;text-transform:uppercase;">Payment Due</label>
											<input class="form-control form-control-sm" name="dueDate" id="dueDate"
											       value="<?= date('d M Y', strtotime($data->dueDate)) ?>">
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>

					<!-- ── Section 2: Services ─────────────────────────────────── -->
					<div class="mb-4">
						<div class="fw-semibold mb-2" style="font-size:11px;letter-spacing:.08em;text-transform:uppercase;color:#64748b;">Services</div>
						<div class="table-responsive rounded-3 border">
							<table class="table table-sm table-hover align-middle mb-0">
								<thead style="background:#e8f0fe;">
								<tr>
									<th class="ps-3">Service / Date</th>
									<th>Caregiver</th>
									<th>Times</th>
									<th class="text-center">Hours</th>
									<th class="text-end">Rate</th>
									<th class="text-end pe-3">Amount</th>
								</tr>
								</thead>
								<tbody>
								<?php foreach ($services as $row): ?>
								<tr>
									<td class="ps-3">
										<input type="hidden" value="<?= $row->id ?>" name="serviceId[]">
										<div class="fw-semibold small"><?= htmlspecialchars($row->serviceType) ?></div>
										<div class="text-muted" style="font-size:11px;"><?= date('d M Y', strtotime($row->date)) ?></div>
									</td>
									<td class="small"><?= htmlspecialchars($row->firstName . ' ' . $row->lastName) ?></td>
									<td class="small text-nowrap"><?= $row->startTime ?> – <?= $row->endTime ?></td>
									<td class="text-center small"><?= $row->hours ?></td>
									<td class="text-end small">$<?= number_format($row->billRate, 2) ?></td>
									<td class="text-end pe-3 small fw-semibold">$<?= number_format($row->billAmount, 2) ?></td>
								</tr>
								<?php endforeach; ?>
								</tbody>
							</table>
						</div>
					</div>

					<!-- ── Section 3: Payment History  +  Financial Summary ──── -->
					<div class="row g-4">

						<!-- Payment History -->
						<div class="col-md-7">
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

						<!-- Financial Summary + New Payment -->
						<div class="col-md-5">

							<!-- Summary card -->
							<div class="card border shadow-sm mb-3">
								<div class="card-body p-3">
									<div class="fw-semibold mb-3" style="font-size:11px;letter-spacing:.08em;text-transform:uppercase;color:#64748b;">Financial Summary</div>

									<div class="d-flex justify-content-between align-items-center mb-2">
										<span class="text-muted small">Subtotal</span>
										<span class="fw-semibold">$<span id="totalAmountDisplay"><?= number_format($data->totalAmount, 2) ?></span></span>
									</div>

									<div class="d-flex justify-content-between align-items-center mb-2">
										<div class="d-flex align-items-center gap-2">
											<span class="text-muted small">Tax</span>
											<div class="input-group input-group-sm" style="width:110px;">
												<input type="number" step="any" min="0" name="taxPercentage" id="taxPercentage"
												       class="form-control form-control-sm" value="<?= $data->taxPercentage ?>" placeholder="0">
												<span class="input-group-text">%</span>
											</div>
										</div>
										<span class="text-muted small">$<span id="taxAmountDisplay"><?= number_format($data->taxAmount, 2) ?></span></span>
									</div>

									<hr class="my-2">

									<div class="d-flex justify-content-between align-items-center mb-2">
										<span class="fw-semibold">Amount Due</span>
										<span class="fw-bold">$<span id="totalDisplay"><?= number_format($data->total, 2) ?></span></span>
									</div>

									<div class="d-flex justify-content-between align-items-center mb-2">
										<span class="text-muted small">Paid to Date</span>
										<span class="text-success fw-semibold">$<?= number_format($data->paidAmount, 2) ?></span>
									</div>

									<div class="d-flex justify-content-between align-items-center px-2 py-2 rounded-2" style="background:#fff7ed;border:1px solid #fed7aa;">
										<span class="fw-semibold" style="color:#92400e;">Balance Owing</span>
										<span class="fw-bold fs-6 text-danger" id="balanceOwingDisplay">$<span id="currentDueDisplay"><?= number_format($data->total - $data->paidAmount, 2) ?></span></span>
									</div>
								</div>
							</div>

							<!-- Record New Payment -->
							<div class="card border-success shadow-sm">
								<div class="card-header bg-success-subtle border-success">
									<div class="fw-semibold text-success">
										<i data-feather="dollar-sign" class="me-1"></i>
										Record New Payment
									</div>
								</div>

								<div class="card-body">
									<!-- Row 1 -->
									<div class="row g-3">
										<div class="col-md-6">
											<label for="newPaymentDate" class="form-label fw-semibold">
												Payment Date
											</label>
											<input
												type="text"
												id="newPaymentDate"
												name="payDate"
												class="form-control"
												placeholder="Select date">
										</div>

										<div class="col-md-6">
											<label for="newPaymentAmount" class="form-label fw-semibold">
												Amount (CAD)
											</label>
											<div class="input-group">
												<span class="input-group-text">$</span>
												<input
													type="number"
													step="0.01"
													min="0"
													id="newPaymentAmount"
													name="paidAmount"
													class="form-control"
													placeholder="0.00"
													max="<?= $data->total - $data->paidAmount ?>">
											</div>
										</div>
									</div>

									<!-- Row 2 -->
									<div class="row mt-3">
										<div class="col-12">
											<label for="payNote" class="form-label fw-semibold">
												Payment Note
												<span class="text-muted fw-normal">(Optional)</span>
											</label>
											<input
												type="text"
												name="payNote"
												id="payNote"
												class="form-control"
												placeholder="Cheque #123, E-transfer, Bank Deposit, etc.">
										</div>
									</div>

								</div>
							</div>

							<!-- Hidden inputs required for form POST -->
							<input type="hidden" name="totalAmount" id="totalAmount" value="<?= $data->totalAmount ?>">
							<input type="hidden" name="taxAmount"   id="taxAmount"   value="<?= $data->taxAmount ?>">
							<input type="hidden" name="total"       id="total"       value="<?= $data->total ?>">
							<input type="hidden" id="pay"           value="<?= $data->paidAmount ?>">
						</div>
					</div>

				</div><!-- /modal-body -->

				<div class="modal-footer d-flex justify-content-between align-items-center">
					<a href="javascript:void(0);"
					   onclick="$('#remoteModal1').modal('hide'); setTimeout(function(){ loadPopup('<?= admin_url('addPayment') ?>'); }, 300);"
					   class="btn btn-outline-secondary btn-sm">
						<i data-feather="plus-circle" style="width:13px;height:13px;vertical-align:middle;margin-right:4px;"></i>
						Record Separate Payment
					</a>
					<div class="d-flex gap-2">
						<button type="button" class="btn btn-secondary close">Close</button>
						<button type="submit" class="btn btn-primary">
							<i data-feather="save" style="width:13px;height:13px;vertical-align:middle;margin-right:4px;"></i>
							Update Invoice
						</button>
					</div>
				</div>
			</form>
		</div>
	</div>
</div>

<script>
$('.close').on('click', function () { $('#remoteModal1').modal('hide'); });

$(document).ready(function () {
	$('#invoiceDate, #dueDate').flatpickr({ static: true, dateFormat: 'd M Y' });
	$('#newPaymentDate').flatpickr({ static: true, dateFormat: 'd M Y', defaultDate: 'today' });
	feather.replace();
});

$('#taxPercentage').on('input', function () {
	var pct         = parseFloat($(this).val()) || 0;
	var subtotal    = parseFloat($('#totalAmount').val()) || 0;
	var paid        = parseFloat($('#pay').val()) || 0;
	var tax         = subtotal * pct / 100;
	var total       = subtotal + tax;
	var newPayment  = parseFloat($('#newPaymentAmount').val()) || 0;
	var balance     = total - paid - newPayment;

	$('#taxAmount').val(tax.toFixed(2));
	$('#taxAmountDisplay').text(tax.toFixed(2));
	$('#total').val(total.toFixed(2));
	$('#totalDisplay').text(total.toFixed(2));
	$('#currentDueDisplay').text(balance.toFixed(2));
});

$('#newPaymentAmount').on('input', function () {
	var total      = parseFloat($('#total').val()) || 0;
	var paid       = parseFloat($('#pay').val()) || 0;
	var newPayment = parseFloat($(this).val()) || 0;
	$('#currentDueDisplay').text((total - paid - newPayment).toFixed(2));
});
</script>
