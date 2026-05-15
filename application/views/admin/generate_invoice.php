<!--<div class="row">-->
<!--	<div class="col-md-6 mb-3">-->
<!--		<img src="--><? //= base_url('assets/images/logo.png') ?><!--" height="120">-->
<!--	</div>-->
<!--	<div class="col-md-6 mb-3 text-end">-->
<!--		<h2>INVOICE</h2>-->
<!--		<small>(--><? //= date("F , Y") ?><!--)</small>-->
<!--		<h4>Mayer Health Servicces Inc.</h4>-->
<!--		<p>400 Applewood Crescent, Unit 100<br>-->
<!--			Vaughan, Ontario L4K 0C3<br>Canada</p>-->
<!--		<p>www.mayerhealth.ca</p>-->
<!--	</div>-->
<!--</div>-->
<!--<hr>-->
<div class="row">
	<div class="col-md-7 mb-3">
		<input type="text" name="title" class="form-control" placeholder="Invoice Title" required><br>
		<h5>BILL TO</h5>
		<h5><?= $client->companyName ?></h5>
		<h5><?= $client->name ?></h5>
		<p><?= $client->phone ?><br><?= $client->billingAddress ?></p>
	</div>
	<div class="col-md-3 mb-3">
		<table class="table table-responsive table-borderless table-sm">
			<tbody>
			<tr>
				<th style="padding-top: 0;padding-bottom: 0" class="text-end">Invoice Number:</th>
				<td style="padding-top: 0;padding-bottom: 0" class="text-start pl-2">
					<input type="hidden" name="clientId" value="<?= $client->id ?>">
					<input type="hidden" name="invoiceNumber"
						   value="<?= $invouceNumber = date('Ymd') . '-' . rand(1000, 9999) ?>">
					<?= $invouceNumber ?>
				</td>
			</tr>
			<tr>
				<th style="padding-top: 0;padding-bottom: 0" class="text-end">P.O/S.O Number:</th>
				<td style="padding-top: 0;padding-bottom: 0" class="text-start pl-2"><input type="text" name="poNumber">
				</td>
			</tr>
			<tr>
				<th style="padding-top: 0;padding-bottom: 0" class="text-end">Invoice Date:</th>
				<td style="padding-top: 0;padding-bottom: 0" class="text-start pl-2">
					<input name="invoiceDate" id="invoiceDate">
				</td>
			</tr>
			<tr>
				<th style="padding-top: 0;padding-bottom: 0" class="text-end">Payment Due:</th>
				<td style="padding-top: 0;padding-bottom: 0" class="text-start pl-2">
					<input type="date" id="dueDate" name="dueDate">
				</td>
			</tr>
			<tr>
				<th style="padding-top: 0;padding-bottom: 0;" class="text-end">Total (CAD):</th>
				<th style="padding-top: 0;padding-bottom: 0;"
					class="text-start pl-2">
					<input name="totalAmount" readonly type="number" id="totalAmount" value="<?= $totalAmount ?>">
					<input name="totalHours" type="hidden" id="totalHours" value="<?= $totalHours ?>">
				</th>
			</tr>
			<tr>
				<th style="padding-top: 0;padding-bottom: 0;" class="text-end">Tax (%):</th>
				<th style="padding-top: 0;padding-bottom: 0;" class="text-start pl-2">
					<input type="number" step="any" min="0" name="taxPercentage" placeholder="(%)" id="taxPercentage"></th>
			</tr>
			<tr>
				<th style="padding-top: 0;padding-bottom: 0;" class="text-end">Tax (HST):</th>
				<th style="padding-top: 0;padding-bottom: 0;" class="text-start pl-2">
					<input type="number" step="any" readonly name="taxAmount" id="taxAmount"></th>
			</tr>
			<tr>
				<th style="padding-top: 0;padding-bottom: 0;" class="text-end">Amount Due (CAD):</th>
				<th style="padding-top: 0;padding-bottom: 0;" class="text-start pl-2">
					<input name="total" type="number" readonly id="total" value="<?= $totalAmount ?>">
				</th>
			</tr>
			<tr>
				<th style="padding-top: 0;padding-bottom: 0;" class="text-end">Pay
					Amount (CAD):
				</th>
				<th style="padding-top: 0;padding-bottom: 0;"
					class="text-start pl-2">
					<input type="number" step="any" min="0" id="paidAmount"
						   max="<?= $totalAmount ?>" name="paidAmount">
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
				<?php foreach ($selectedRows as $row): ?>
					<tr>
						<td><input type="hidden" value="<?php echo $row['id'] ?>" name="serviceId[]">
							<?php echo $row['serviceType'] . '<br>' . date('d F Y', strtotime($row['date'])) ?></td>
						<td><?php echo $row['firstName'] . ' ' . $row['lastName'] ?></td>
						<td><?php echo $row['startTime'] . ' - ' . $row['endTime']; ?></td>
						<td><?php echo $row['hours']; ?></td>
						<td>$<?php echo $row['billRate']; ?></td>
						<td>$<?php echo $row['billAmount']; ?></td>
					</tr>
				<?php endforeach; ?>
				</tbody>
			</table>
		</div>
	</div>
</div>
<script>
	$(document).ready(function () {
		$("#dueDate, #invoiceDate").flatpickr({
			static: true,
			defaultDate: "today",
			dateFormat: "d M Y",
		});
	});

	$('#taxPercentage').on('input', function () {
		var percentage = parseFloat($(this).val());
		var totalAmount = parseFloat($('#totalAmount').val());
		var taxAmount = totalAmount * percentage / 100;
		$('#taxAmount').val(taxAmount.toFixed(2));
		var total = totalAmount + taxAmount;
		$('#total').val(total.toFixed(2));
		$('#paidAmount').attr('max', total.toFixed(2));
	})
</script>
