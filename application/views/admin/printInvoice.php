<div class="modal" id="modal-default" style="display: block; overflow: auto;">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<div class="modal-header">
				<h4 class="modal-title"><b>Client Invoice</b></h4>
				<button type="button" class="btn btn-danger close">Close</button>
			</div>
			<div class="modal-body" id="printThis">
				<div class="row">
					<div class="col-md-6 mb-3">
						<img src="<?= base_url('assets/images/logo.png') ?>" height="120">
					</div>
					<div class="col-md-6 mb-3 text-end">
						<h2>INVOICE</h2>
						<?php if ($data->title) { ?>
							<small>(<?= $data->title ?>)</small>
						<?php } ?>
						<h4>Mayer Health Services Inc.</h4>
						<p>400 Applewood Crescent, Unit 100<br>
							Vaughan, Ontario L4K 0C3<br>Canada<br>www.mayerhealth.ca</p>
					</div>
				</div>
				<hr>
				<div class="row">
					<div class="col-md-7 mb-3">
						<h5>BILL TO</h5>
						<h5><?= $data->companyName . '<br>' . $data->name ?></h5>
						<p><?= $data->phone ?><br><?= $data->billingAddress ?></p>
					</div>
					<div class="col-md-5 mb-3">
						<table class="table table-responsive table-borderless table-sm">
							<tbody>
							<tr>
								<th style="padding-top: 0;padding-bottom: 0" class="text-end">Invoice Number:</th>
								<td style="padding-top: 0;padding-bottom: 0"
									class="text-start pl-2"><?= $data->invoiceNumber ?>
								</td>
							</tr>
							<tr>
								<th style="padding-top: 0;padding-bottom: 0" class="text-end">P.O/S.O Number:</th>
								<td style="padding-top: 0;padding-bottom: 0"
									class="text-start pl-2"><?= $data->poNumber ?></td>
							</tr>
							<tr>
								<th style="padding-top: 0;padding-bottom: 0" class="text-end">Invoice Date:</th>
								<td style="padding-top: 0;padding-bottom: 0"
									class="text-start pl-2"><?= date('d M Y', strtotime($data->invoiceDate)) ?></td>
							</tr>
							<tr>
								<th style="padding-top: 0;padding-bottom: 0" class="text-end">Payment Due:</th>
								<td style="padding-top: 0;padding-bottom: 0"
									class="text-start pl-2"><?= date('d M Y', strtotime($data->dueDate)) ?></td>
							</tr>
							</tbody>
						</table>
					</div>
				</div>
				<div class="row">
					<div class="col-md-12 mb-3">
						<div class="table-responsive">
							<table class="table"
								   style="width: 100% !important;">
								<thead>
								<tr class="bg-info">
									<th class="text-dark">Services</th>
									<th class="text-dark">Caregiver</th>
									<th class="text-dark">Hours</th>
									<th class="text-dark">Rate</th>
									<th class="text-dark">Amount</th>
								</tr>
								</thead>
								<tbody>
								<?php if ($services) {
									foreach ($services as $datum) { ?>
										<tr>
											<td><?= $datum->serviceType . '<br>' . date('d M Y', strtotime($datum->date)) . ' (' . date('h:i A', strtotime($datum->startTime)) . ' - ' . date('h:i A', strtotime($datum->endTime)) . ')' ?></td>
											<td><?= $datum->firstName . ' ' . $datum->lastName ?></td>
											<td><?= $datum->hours ?></td>
											<td>$<?= $datum->billRate ?></td>
											<td>$<?= $datum->billAmount ?></td>
										</tr>
									<?php }
								} else { ?>
									<tr>
										<td class="text-center text-danger" colspan="5">No record found!</td>
									</tr>
								<?php } ?>
								</tbody>
								<tfoot class="table-footer">
								<tr>
									<th colspan="4" class="text-end">Total (CAD)</th>
									<th>$<?php
										$totalAmount = 0;
										foreach ($services as $item) {
											$totalAmount += $item->billAmount;
										}
										echo $totalAmount ?></th>
								</tr>
								<tr>
									<th colspan="4" class="text-end">Tax (HST)</th>
									<th>$<?= $data->taxAmount ?></th>
								</tr>
								<tr>
									<th colspan="4" class="text-end">Amount Due (CAD)</th>
									<th>$<?= $data->total ?></th>
								</tr>
								<?php if ($data->paidAmount > 0) { ?>
									<tr>
										<th colspan="4" class="text-end">Payment Received (CAD)</th>
										<th>$<?= $data->paidAmount ?></th>
									</tr>
									<tr>
										<th colspan="4" class="text-end">Balance Remaining (CAD)</th>
										<th>$<?= $data->total - $data->paidAmount ?></th>
									</tr>
								<?php } ?>
								</tfoot>
							</table>
						</div>
				</div>
			</div>
			<?php if (!empty($payments)): ?>
			<div class="row mt-3">
				<div class="col-md-12">
					<h6 class="fw-semibold mb-2">Payment History</h6>
					<div class="table-responsive">
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
				</div>
			</div>
			<?php endif; ?>
		</div>
		<div class="modal-footer no-print">
			<button type="button" class="btn btn-danger close">Close</button>
			<button type="button" class="btn btn-primary printButton">Print</button>
		</div>
	</div>
</div>
</div>

<style>
	@media print {
		.modal-header, .modal-footer, .no-print { display: none !important; }
	}
</style>
<script>
	$('.close').on('click', function () {
		$("#remoteModal1").modal('hide');
	});

	$('.printButton').on('click', function () {
		var printContents = document.getElementById('printThis').innerHTML;
		var printWindow = window.open('', '_blank');
		printWindow.document.write(`
            <html>
                <head>
                    <title>Print Invoice</title>
                    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/5.0.0/css/bootstrap.min.css">
                    <style>
                        body { padding: 20px; }
                        h1, h2, h3, h4, h5, h6 { margin: 0; padding: 0; }
                        .table { width: 100%; max-width: 100%; margin-bottom: 1rem; background-color: transparent; }
                        .table th, .table td { padding-top: 0; padding-bottom: 0; vertical-align: top; border-top: 1px solid #dee2e6; }
                        .table thead th { vertical-align: bottom; text-align: left; background-color: #66D1D1; }
                        .table tbody + tbody { border-top: 2px solid #dee2e6; }
                        .table-sm th, .table-sm td { padding: 0.3rem; }
                        .table-bordered { border: 1px solid #dee2e6; }
                        .table-bordered th, .table-bordered td { border: 1px solid #dee2e6; }
                        .table-bordered thead th, .table-bordered thead td { border-bottom-width: 2px; }
                        .table-borderless th, .table-borderless td, .table-borderless thead th, .table-borderless tbody + tbody { border: 0; }
                        .table-light th, .table-light td { background-color: #f8f9fa !important; -webkit-print-color-adjust: exact; print-color-adjust: exact; }
                        .text-success { color: #198754 !important; }
                        .text-end { text-align: right; }
                        .text-start { text-align: left; }
                        .fw-semibold { font-weight: 600; }
                        .fw-bold { font-weight: 700; }
                        .pl-2 { padding-left: 0.5rem; }
                        .mb-3 { margin-bottom: 1rem; }
                        .mt-3 { margin-top: 1rem; }
                        .bg-info { background-color: #d1ecf1; -webkit-print-color-adjust: exact; print-color-adjust: exact; }
                        .text-dark { color: #343a40; }
                        tfoot { display: table-row-group; }
                        tfoot tr { page-break-inside: avoid; }
                        @media print {
                            .row { display: flex; flex-wrap: nowrap; }
                            .col-md-5  { flex: 0 0 auto; width: 41.6666666667%; }
                            .col-md-6  { flex: 0 0 auto; width: 50%; }
                            .col-md-7  { flex: 0 0 auto; width: 58.3333333333%; }
                            .col-md-12 { flex: 0 0 auto; width: 100%; }
                            tr { page-break-inside: avoid; }
                            .table-footer { position: relative; bottom: 0; page-break-inside: avoid; }
                            .table-footer::after { content: ''; display: block; height: 1px; page-break-after: auto; }
                        }
                    </style>
                </head>
                <body onload="window.print(); setTimeout(function(){window.close();}, 1);">
                    ${printContents}
                </body>
            </html>
        `);
		printWindow.document.close();
	});
</script>
