<div class="modal" id="modal-default" style="display: block;overflow: auto;">
	<div class="modal-dialog modal-md">
		<div class="modal-content">
			<div class="modal-header">
				<h4 class="modal-title"><b>Update Service</b></h4>
				<button type="button" class="btn btn-danger float-end close">Close
				</button>
			</div>
			<form class="forms-sample" action="<?= admin_url('updateService/') . $data->id ?>" method="post"
				  enctype="multipart/form-data">
				<div class="modal-body">
					<div class="row">
						<div class="col-md-12 mb-3">
							<label for="copyDate" class="form-label"> Copy Data to multiple date </label>
							<div class="input-group flatpickr">
								<input type="text" id="copyDate" name="copyDate" class="form-control"
									   placeholder="Select Date(s)" data-input>
								<button type="button" class="btn btn-sm btn-danger" id="clearButton">Clear</button>
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-md-6 mb-3">
							<label for="date" class="form-label"> Date <sup
										class="text-danger">*</sup></label>
							<div class="input-group flatpickr">
								<input type="text" id="date" name="date" class="form-control"
									   value="<?= date('d M Y', strtotime($data->date)) ?>" data-input required>
							</div>
						</div>
						<div class="col-md-6 mb-3">
							<label class="form-label">Service Type <sup
										class="text-danger">*</sup></label>
							<select class="form-select" name="serviceType" data-width="100%" required>
								<option <?= $data->serviceType == 'Attendant Care' ? 'selected' : '' ?>
										value="Attendant Care">Attendant Care
								</option>
								<option <?= $data->serviceType == 'Housekeeping' ? 'selected' : '' ?>
										value="Housekeeping">Housekeeping
								</option>
								<option <?= $data->serviceType == 'Wound Care' ? 'selected' : '' ?> value="Wound Care">
									Wound Care
								</option>
								<option <?= $data->serviceType == 'Travel/Transport' ? 'selected' : '' ?>
										value="Travel/Transport">Travel/Transport
								</option>
								<option <?= $data->serviceType == 'Respite' ? 'selected' : '' ?> value="Respite">
									Respite
								</option>
							</select>
						</div>
					</div>
					<div class="row">
						<div class="col-md-6 mb-3">
							<label for="startTime" class="form-label"> Start Time <sup
										class="text-danger">*</sup></label>
							<input type="text" id="startTime" name="startTime" class="form-control"
								   value="<?= $data->startTime ?>" data-input required>
						</div>
						<div class="col-md-6 mb-3">
							<label for="endTime" class="form-label"> End Time <sup
										class="text-danger">*</sup></label>
							<input type="text" id="endTime" name="endTime" class="form-control"
								   value="<?= $data->endTime ?>" data-input required>
						</div>
					</div>
					<div class="row">
						<div class="col-md-6 mb-3">
							<label class="form-label">Caregiver <sup
										class="text-danger">*</sup></label>
							<select class="form-select" name="caregiverId" id="caregiverId" data-width="100%" required>
								<?php foreach ($caregivers as $caregiver) { ?>
									<option <?= $data->caregiverId == $caregiver->id ? 'selected' : '' ?>
											data-rate="<?= $caregiver->baseRate ?>"
											value="<?= $caregiver->id ?>"><?= $caregiver->firstName . ' ' . $caregiver->lastName ?></option>
								<?php } ?>
							</select>
						</div>
						<div class="col-md-6 mb-3">
							<label class="form-label">Client <sup
										class="text-danger">*</sup></label>
							<select class="form-select" name="clientId" id="clientId" data-width="100%" required>
								<?php foreach ($clients as $client) { ?>
									<option <?= $data->clientId == $client->id ? 'selected' : '' ?>
											data-billrate="<?= $client->billRate ?>"
											data-budgetedhours="<?= $client->budgetedHours ?>"
											value="<?= $client->id ?>"><?= $client->name ?></option>
								<?php } ?>
							</select>
						</div>
					</div>
					<div class="row">
						<div class="mb-3">
							<table class="table table-bordered table-responsive-sm">
								<tbody>
								<tr>
									<th>Budgeted Hours</th>
									<td>
										<div id="budgetedHours"></div>
									</td>
								</tr>
								<tr>
									<th>Scheduled Hours for <?= date('F') ?></th>
									<td>
										<div id="scheduledHours"></div>
									</td>
								</tr>
								</tbody>
							</table>
						</div>
						<div class="row">
							<div class="col-md-4 mb-3">
								<label for="rate" class="form-label">Employee Rate <sup
											class="text-danger">*</sup></label>
								<div class="input-group">
									<span class="input-group-text">$</span>
									<input type="number" step="any" min="0" class="form-control numeric-input" id="rate"
										   name="rate"
										   value="<?= $data->rate ?>" required>
								</div>
							</div>
							<div class="col-md-4 mb-3">
								<label for="billRate" class="form-label">Bill Rate <sup
											class="text-danger">*</sup></label>
								<div class="input-group">
									<span class="input-group-text">$</span>
									<input type="number" step="any" min="0" class="form-control numeric-input"
										   id="billRate"
										   name="billRate"
										   value="<?= $data->billRate ?>" required>
								</div>
							</div>
							<div class="col-md-4 mb-3">
								<label for="hours" class="form-label">Hours </label>
								<input type="number" step="any" min="0" class="form-control" name="hours"
									   id="hours" value="<?= $data->hours ?>" required>
							</div>
						</div>
						<div class="row">
							<div class="col-md-4 mb-3">
								<label for="amount" class="form-label">Total Amount <sup
											class="text-danger">*</sup></label>
								<div class="input-group">
									<span class="input-group-text">$</span>
									<input type="number" step="any" min="0" class="form-control numeric-input"
										   id="amount"
										   name="amount" value="<?= $data->amount ?>" readonly>
								</div>
							</div>
							<div class="col-md-4 mb-3">
								<label for="billAmount" class="form-label">Bill Amount <sup
											class="text-danger">*</sup></label>
								<div class="input-group">
									<span class="input-group-text">$</span>
									<input type="hidden" name="oldBillAmount" value="<?= $data->billAmount ?>">
									<input type="number" step="any" min="0" class="form-control numeric-input"
										   id="billAmount" value="<?= $data->billAmount ?>"
										   name="billAmount" readonly>
								</div>
							</div>
						</div>
						<div class="row">
							<div class="col-md-12 mb-3">
								<label for="comments" class="form-label">Comments </label>
								<textarea class="form-control" name="comments"
										  id="comments"><?= $data->comments ?></textarea>
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
		var budgetedHours = $('#clientId').find(':selected').data('budgetedhours');
		var clientId = $('#clientId').find(':selected').val();
		console.log(budgetedHours);
		console.log(clientId);
		$('#budgetedHours').text(budgetedHours);
		$.ajax({
			type: "GET",
			url: "<?= admin_url('getScheduledHoursThisMonth/') ?>" + clientId,
			success: function (response) {
				console.log(response);
				$('#scheduledHours').text(response)
			}
		});
		var fp = $("#copyDate").flatpickr({
			// static: true,
			mode: "multiple",
			dateFormat: "d M Y",
		});
		$('#clearButton').on('click', function () {
			fp.clear();
		})
		$("#date").flatpickr({
			static: true,
			dateFormat: "d M Y",
		});
		$("#startTime, #endTime").flatpickr({
			static: true,
			enableTime: true,
			noCalendar: true,
			dateFormat: "h:i K",
			time_24hr: false,
			onChange: calculateTotalHours
		});
	});

	function calculateTotalHours() {
		const startTime = $("#startTime").val();
		const endTime = $("#endTime").val();

		if (startTime && endTime) {
			const start = parseTime(startTime);
			const end = parseTime(endTime);

			let totalHours = (end - start) / 3600;
			if (totalHours < 0) {
				totalHours += 24;
			}
			$("#hours").val(totalHours.toFixed(2));
			var rate = parseFloat($('#rate').val());
			var billRate = parseFloat($('#billRate').val());
			var hour = parseFloat($('#hours').val());
			var total = rate * hour;
			var billAmount = billRate * hour;
			$('#amount').val(total.toFixed(2));
			$('#billAmount').val(billAmount.toFixed(2));
		} else {
			$("#hours").val('');
		}
	}

	function parseTime(timeString) {
		const [time, period] = timeString.split(' ');
		let [hours, minutes] = time.split(':').map(Number);
		if (period === 'PM' && hours < 12) {
			hours += 12;
		} else if (period === 'AM' && hours === 12) {
			hours = 0;
		}
		return hours * 3600 + minutes * 60;
	}

	$('#caregiverId').on('change', function () {
		var rate = $(this).find(':selected').data('rate');
		$('#rate').val(rate);
		var rate = parseFloat($('#rate').val());
		var hour = parseFloat($('#hours').val());
		var total = rate * hour;
		$('#amount').val(total.toFixed(2));
	});

	$('#clientId').on('change', function () {
		var billRate = $(this).find(':selected').data('billrate');
		var budgetedHours = $(this).find(':selected').data('budgetedhours');
		var clientId = $(this).find(':selected').val();
		$('#billRate').val(billRate);
		$('#budgetedHours').text(budgetedHours);
		var rate = parseFloat($('#billRate').val());
		var hour = parseFloat($('#hours').val());
		var total = rate * hour;
		$('#billAmount').val(total.toFixed(2));
		$.ajax({
			type: "GET",
			url: "<?= admin_url('getScheduledHoursThisMonth/') ?>" + clientId,
			success: function (response) {
				console.log(response);
				$('#scheduledHours').text(response)
			}
		});
	});

	$('#hours, #rate, #billRate').on('input', function () {
		if ($('#caregiverId :selected').val() == '') {
			$('#hours').val('')
			Toast.fire({
				icon: "warning",
				title: 'Select Caregiver first!'
			});
		} else if ($('#clientId :selected').val() == '') {
			$('#hours').val('')
			Toast.fire({
				icon: "warning",
				title: 'Select Client first!'
			});
		} else {
			var rate = parseFloat($('#rate').val());
			var billRate = parseFloat($('#billRate').val());
			var hour = parseFloat($('#hours').val());
			var total = rate * hour;
			var totalBill = billRate * hour;
			$('#amount').val(total.toFixed(2));
			$('#billAmount').val(totalBill.toFixed(2));
		}
	})
</script>
