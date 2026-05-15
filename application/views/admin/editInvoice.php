<div class="modal" id="modal-default" style="display: block;overflow: auto;">
	<div class="modal-dialog modal-xl">
		<div class="modal-content">
			<div class="modal-header">
				<h4 class="modal-title"><b>Update Invoice</b></h4>
				<button type="button" class="btn btn-danger float-end close">Close
				</button>
			</div>
			<form class="forms-sample" action="<?= admin_url('savePay/') . $data->id ?>" method="post"
				  enctype="multipart/form-data">
				<div class="modal-body">
					<div class="row">
						<div class="col-md-7 mb-3">
							<input type="text" name="title" class="form-control" placeholder="Invoice Title"
								   value="<?= $data->title ?>" required><br>
							<h5>BILL TO</h5>
							<h5><?= $data->companyName ?></h5>
							<h5><?= $data->name ?></h5>
							<p><?= $data->phone ?><br><?= $data->billingAddress ?></p>
						</div>
						<div class="col-md-3 mb-3">
							<table class="table table-responsive table-borderless table-sm">
								<tbody>
								<tr>
									<th style="padding-top: 0;padding-bottom: 0" class="text-end">Invoice Number:</th>
									<td style="padding-top: 0;padding-bottom: 0" class="text-start pl-2">
										<input type="hidden" name="clientId" value="<?= $data->clientId ?>">
										<input type="hidden" name="invoiceNumber"
											   value="<?= $data->invoiceNumber ?>">
										<?= $data->invoiceNumber ?>
									</td>
								</tr>
								<tr>
									<th style="padding-top: 0;padding-bottom: 0" class="text-end">P.O/S.O Number:</th>
									<td style="padding-top: 0;padding-bottom: 0" class="text-start pl-2"><input
												type="text" value="<?= $data->poNumber ?>" name="poNumber">
									</td>
								</tr>
								<tr>
									<th style="padding-top: 0;padding-bottom: 0" class="text-end">Invoice Date:</th>
									<td style="padding-top: 0;padding-bottom: 0" class="text-start pl-2">
										<input name="invoiceDate"
											   value="<?= date('d M Y', strtotime($data->invoiceDate)) ?>"
											   id="invoiceDate">
									</td>
								</tr>
								<tr>
									<th style="padding-top: 0;padding-bottom: 0" class="text-end">Payment Due:</th>
									<td style="padding-top: 0;padding-bottom: 0" class="text-start pl-2">
										<input id="dueDate" name="dueDate"
											   value="<?= date('d M Y', strtotime($data->dueDate)) ?>">
									</td>
								</tr>
								<tr>
									<th style="padding-top: 0;padding-bottom: 0;" class="text-end">Total (CAD):
									</th>
									<th style="padding-top: 0;padding-bottom: 0;"
										class="text-start pl-2">
										<input name="totalAmount" readonly type="number" id="totalAmount"
											   value="<?= $data->totalAmount ?>">
										<input name="totalHours" type="hidden" id="totalHours"
											   value="<?= $data->totalHours ?>">
									</th>
								</tr>
								<tr>
									<th style="padding-top: 0;padding-bottom: 0;" class="text-end">Tax (%):</th>
									<th style="padding-top: 0;padding-bottom: 0;" class="text-start pl-2">
										<input type="number" step="any" value="<?= $data->taxPercentage ?>" min="0"
											   placeholder="(%)" name="taxPercentage" id="taxPercentage">
									</th>
								</tr>
								<tr>
									<th style="padding-top: 0;padding-bottom: 0;" class="text-end">Tax (HST):
									</th>
									<th style="padding-top: 0;padding-bottom: 0;" class="text-start pl-2">
										<input type="number" step="any" readonly value="<?= $data->taxAmount ?>"
											   name="taxAmount" id="taxAmount"></th>
								</tr>
								<tr>
									<th style="padding-top: 0;padding-bottom: 0;" class="text-end">Amount Due (CAD):
									</th>
									<th style="padding-top: 0;padding-bottom: 0;" class="text-start pl-2">
										<input name="total" type="number" readonly id="total"
											   value="<?= $data->total ?>">
									</th>
								</tr>
								<tr>
									<th style="padding-top: 0;padding-bottom: 0;" class="text-end">Paid
										Amount (CAD):
									</th>
									<th style="padding-top: 0;padding-bottom: 0;"
										class="text-start pl-2">
										<input type="number" id="pay" value="<?= $data->paidAmount ?>" readonly>
									</th>
								</tr>
								<tr>
									<th style="padding-top: 0;padding-bottom: 0;" class="text-end">Current Due (CAD):
									</th>
									<th style="padding-top: 0;padding-bottom: 0;"
										class="text-start pl-2">
										<input type="number" id="currentDue"
											   value="<?= $data->total - $data->paidAmount ?>" readonly>
									</th>
								</tr>
								<tr>
									<th style="padding-top: 0;padding-bottom: 0;" class="text-end">Pay
										Amount (CAD):
									</th>
									<th style="padding-top: 0;padding-bottom: 0;"
										class="text-start pl-2">
										<input type="number" step="any" min="0"
											   max="<?= $data->total - $data->paidAmount ?>" name="paidAmount">
									</th>
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
									<thead class="bg-info">
									<tr class="text-white">
										<th class="text-dark">Services</th>
										<th class="text-dark">Caregiver</th>
										<th class="text-dark">Times</th>
										<th class="text-dark">Hours</th>
										<th class="text-dark">Rate</th>
										<th class="text-dark">Amount</th>
									</tr>
									</thead>
									<tbody>
									<?php foreach ($services as $row): ?>
										<tr>
											<td><input type="hidden" value="<?php echo $row->id ?>"
													   name="serviceId[]">
												<?php echo $row->serviceType . '<br>' . date('d F Y', strtotime($row->date)) ?>
											</td>
											<td><?php echo $row->firstName . ' ' . $row->lastName ?></td>
											<td><?php echo $row->startTime . ' - ' . $row->endTime; ?></td>
											<td><?php echo $row->hours; ?></td>
											<td>$<?php echo $row->billRate; ?></td>
											<td>$<?php echo $row->billAmount; ?></td>
										</tr>
									<?php endforeach; ?>
									</tbody>
								</table>
							</div>
						</div>
					</div>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-danger close">Close</button>
					<button type="submit" class="btn btn-info me-2">Update</button>
				</div>
			</form>
		</div>
	</div>
</div>

<script>
	$('.close').on('click', function () {
		$("#remoteModal1").modal('hide');
	});
	$(document).ready(function () {
		$("#dueDate, #invoiceDate").flatpickr({
			static: true,
			dateFormat: "d M Y",
		});
	});

	$('#taxPercentage').on('input', function () {
		var percentage = parseFloat($(this).val());
		var totalAmount = parseFloat($('#totalAmount').val());
		var pay = parseFloat($('#pay').val());
		var taxAmount = totalAmount * percentage / 100;
		$('#taxAmount').val(taxAmount.toFixed(2));
		var total = totalAmount + taxAmount;
		var currentDue = total - pay;
		$('#total').val(total.toFixed(2));
		$('#currentDue').val(currentDue.toFixed(2));
	})
</script>
