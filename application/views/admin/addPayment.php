<div class="modal" id="modal-default" style="display:block;overflow:auto;">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<div class="modal-header">
				<h4 class="modal-title"><b>Record Payment</b></h4>
				<button type="button" class="btn btn-danger close">Close</button>
			</div>
			<form id="addPaymentForm" action="<?= admin_url('savePayment') ?>" method="post">
				<div class="modal-body">

					<!-- Row 1: client + date + note -->
					<div class="row g-3 mb-3">
						<div class="col-md-4">
							<label class="form-label fw-semibold">Client <sup class="text-danger">*</sup></label>
							<select class="form-select" name="clientId" id="clientId" required>
								<option value="">— Select Client —</option>
								<?php foreach ($clients as $c): ?>
									<option value="<?= $c->id ?>"><?= htmlspecialchars($c->name) ?></option>
								<?php endforeach; ?>
							</select>
						</div>
						<div class="col-md-3">
							<label class="form-label fw-semibold">Payment Date <sup class="text-danger">*</sup></label>
							<input type="text" id="paymentDate" name="paymentDate" class="form-control" placeholder="Select date" required>
						</div>
						<div class="col-md-5">
							<label class="form-label fw-semibold">Note <span class="text-muted fw-normal">(optional)</span></label>
							<input type="text" name="note" class="form-control" placeholder="e.g. Cheque #1234, e-transfer…">
						</div>
					</div>

					<!-- Row 2: tax -->
					<div class="row g-3 mb-3">
						<div class="col-md-3">
							<label class="form-label fw-semibold">Tax %</label>
							<div class="input-group">
								<input type="number" step="any" min="0" name="taxPercentage" id="taxPercentage"
								       class="form-control" placeholder="0">
								<span class="input-group-text">%</span>
							</div>
						</div>
						<div class="col-md-3">
							<label class="form-label fw-semibold">Tax Amount (CAD)</label>
							<div class="input-group">
								<span class="input-group-text">$</span>
								<input type="number" step="any" min="0" name="taxAmount" id="taxAmount"
								       class="form-control" readonly placeholder="0.00">
							</div>
						</div>
						<div class="col-md-3">
							<label class="form-label fw-semibold">Total Payment (CAD)</label>
							<div class="input-group">
								<span class="input-group-text">$</span>
								<input type="number" step="any" min="0" name="totalAmount" id="totalAmount"
								       class="form-control" readonly placeholder="0.00">
							</div>
						</div>
					</div>

					<hr>

					<!-- Invoice allocation table (populated via AJAX) -->
					<div id="invoiceSection" style="display:none;">
						<div class="d-flex justify-content-between align-items-center mb-2">
							<h6 class="fw-semibold mb-0">Apply to Invoices</h6>
							<button type="button" id="selectAllInvoices" class="btn btn-xs btn-outline-secondary">Select All</button>
						</div>
						<p class="text-muted small mb-2">Check each invoice you want to apply this payment to and enter the amount.</p>
						<div class="table-responsive">
							<table class="table table-sm table-bordered" id="invoiceTable">
								<thead class="table-light">
								<tr>
									<th style="width:40px"></th>
									<th>Invoice #</th>
									<th>Invoice Date</th>
									<th>Status</th>
									<th>Balance Due (CAD)</th>
									<th>Amount to Apply (CAD)</th>
								</tr>
								</thead>
								<tbody id="invoiceRows"></tbody>
							</table>
						</div>
						<div id="allocationSummary" class="d-none mt-2 px-3 py-2 rounded" style="background:#EFF6FF;border:1px solid #BFDBFE;font-size:13px;">
							<span class="text-muted me-3">Selected: <strong id="sumCount">0</strong> invoice(s)</span>
							<span class="text-muted me-3">Subtotal: <strong id="sumSubtotal">$0.00</strong></span>
							<span class="text-muted me-3">Tax: <strong id="sumTax">$0.00</strong></span>
							<span style="color:#2563EB;font-weight:600;">Total: <strong id="sumTotal">$0.00</strong></span>
						</div>
					</div>

					<div id="noInvoicesMsg" style="display:none;" class="alert alert-info">
						This client has no outstanding invoices.
					</div>

					<div id="selectClientMsg" class="text-muted small">
						Select a client above to see their outstanding invoices.
					</div>

				</div>
				<div class="modal-footer">
					<button type="submit" id="savePaymentBtn" class="btn btn-primary" disabled>Save Payment</button>
					<button type="button" class="btn btn-secondary close">Close</button>
				</div>
			</form>
		</div>
	</div>
</div>

<script>
$('.close').on('click', function () { $('#remoteModal1').modal('hide'); });

$(document).ready(function () {
	$('#paymentDate').flatpickr({ defaultDate: 'today', dateFormat: 'd M Y' });

	$('#clientId').on('change', function () {
		var clientId = $(this).val();
		$('#invoiceRows').empty();
		$('#invoiceSection, #noInvoicesMsg').hide();
		$('#selectClientMsg').hide();
		recalcTotals();

		if (!clientId) { $('#selectClientMsg').show(); return; }

		$.ajax({
			url:  '<?= admin_url('getClientInvoices/') ?>' + clientId,
			type: 'GET',
			dataType: 'json',
			success: function (invoices) {
				if (!invoices.length) { $('#noInvoicesMsg').show(); return; }

				invoices.forEach(function (inv) {
					var balance = parseFloat(inv.dueAmount).toFixed(2);
					var row = '<tr>'
						+ '<td class="text-center"><input type="checkbox" class="inv-check" data-balance="' + balance + '" data-inv="' + inv.id + '"></td>'
						+ '<td>' + inv.invoiceNumber + '</td>'
						+ '<td>' + moment(inv.invoiceDate).format('DD MMM YYYY') + '</td>'
						+ '<td>' + inv.status + '</td>'
						+ '<td>$' + balance + '</td>'
						+ '<td><div class="input-group input-group-sm">'
						+ '<span class="input-group-text">$</span>'
						+ '<input type="number" step="0.01" min="0" max="' + balance + '" '
						+ 'class="form-control inv-amount" name="invoiceIds[' + inv.id + ']" '
						+ 'value="' + balance + '" disabled>'
						+ '</div></td>'
						+ '</tr>';
					$('#invoiceRows').append(row);
				});
				$('#invoiceSection').show();
				recalcTotals();
			}
		});
	});

	// Toggle amount field when checkbox changes
	$(document).on('change', '.inv-check', function () {
		var amtInput = $(this).closest('tr').find('.inv-amount');
		if ($(this).is(':checked')) {
			amtInput.prop('disabled', false);
		} else {
			amtInput.prop('disabled', true).val($(this).data('balance'));
		}
		recalcTotals();
	});

	$(document).on('input', '.inv-amount', function () { recalcTotals(); });

	$('#taxPercentage').on('input', function () { recalcTotals(); });

	$('#selectAllInvoices').on('click', function () {
		var allChecked = $('.inv-check:not(:checked)').length === 0;
		$('.inv-check').each(function () {
			$(this).prop('checked', !allChecked);
			$(this).closest('tr').find('.inv-amount').prop('disabled', allChecked)
				.val($(this).data('balance'));
		});
		$(this).text(allChecked ? 'Select All' : 'Deselect All');
		recalcTotals();
	});

	function preAppliedTotal() {
		var sum = 0;
		$('.inv-check:checked').each(function () {
			var amt = parseFloat($(this).closest('tr').find('.inv-amount').val()) || 0;
			sum += amt;
		});
		return sum;
	}

	function recalcTotals() {
		var pre   = preAppliedTotal();
		var pct   = parseFloat($('#taxPercentage').val()) || 0;
		var tax   = pre * pct / 100;
		var total = pre + tax;
		$('#taxAmount').val(tax > 0 ? tax.toFixed(2) : '');
		$('#totalAmount').val(pre > 0 ? total.toFixed(2) : '');
		$('#savePaymentBtn').prop('disabled', pre <= 0);

		var count = $('.inv-check:checked').length;
		if (count > 0) {
			$('#sumCount').text(count);
			$('#sumSubtotal').text('$' + pre.toFixed(2));
			$('#sumTax').text('$' + tax.toFixed(2));
			$('#sumTotal').text('$' + total.toFixed(2));
			$('#allocationSummary').removeClass('d-none');
		} else {
			$('#allocationSummary').addClass('d-none');
		}
	}
});
</script>
