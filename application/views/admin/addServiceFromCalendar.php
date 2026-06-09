<div class="modal" id="modal-default" style="display: block; overflow: auto;">
	<div class="modal-dialog modal-md">
		<div class="modal-content">
			<div class="modal-header">
				<h4 class="modal-title"><b>Add New Service</b></h4>
				<button type="button" class="btn btn-danger float-end close">Close</button>
			</div>
			<form class="forms-sample" action="<?= admin_url('saveServiceFromCalendar') ?>" method="post"
				  onsubmit="return handleSubmit();">
				<div class="modal-body">
					<div class="row">
						<div class="col-md-4 mb-3">
							<label for="date" class="form-label"> Date <sup class="text-danger">*</sup></label>
							<input type="text" name="date" class="form-control"
								   value="<?= date('d M Y', strtotime($date)) ?>" data-input readonly>
						</div>
						<div class="col-md-4 mb-3">
							<label for="startTime" class="form-label"> Start Time <sup
										class="text-danger">*</sup></label>
							<input type="text" id="startTime" name="startTime" class="form-control"
								   placeholder="Select Time" data-input required>
						</div>
						<div class="col-md-4 mb-3">
							<label for="endTime" class="form-label"> End Time <sup class="text-danger">*</sup></label>
							<input type="text" id="endTime" name="endTime" class="form-control"
								   placeholder="Select Time" data-input required>
						</div>
					</div>
					<div class="row">
						<div class="col-md-12 mb-3">
							<label class="form-label">Service Type <sup class="text-danger">*</sup></label>
							<select class="form-select" name="serviceType" data-width="100%" required>
								<option value="">Select Service Type</option>
								<option value="Attendant Care">Attendant Care</option>
								<option value="Housekeeping">Housekeeping</option>
								<option value="Wound Care">Wound Care</option>
								<option value="Travel/Transport">Travel/Transport</option>
								<option value="Respite">Respite</option>
							</select>
						</div>
						<div class="col-md-12 mb-3">
							<label class="form-label">Status <sup class="text-danger">*</sup></label>
							<select class="form-select" name="status" required>
								<option value="scheduled" selected>Scheduled</option>
								<option value="complete">Complete</option>
								<option value="cancelled">Cancelled (not billed)</option>
								<option value="late_cancellation">Late Cancellation (billed)</option>
							</select>
						</div>
					</div>
					<div class="row">
						<div class="col-md-6 mb-3">
							<label class="form-label">Caregiver <sup class="text-danger">*</sup></label>
							<select class="form-select" name="caregiverId" id="caregiverId" data-width="100%" required>
								<option value="">Select Caregiver</option>
								<?php foreach ($caregivers as $caregiver) { ?>
									<option data-rate="<?= $caregiver->baseRate ?>"
											value="<?= $caregiver->id ?>"><?= $caregiver->firstName . ' ' . $caregiver->lastName ?></option>
								<?php } ?>
							</select>
						</div>
						<div class="col-md-6 mb-3">
							<label for="clientName" class="form-label"> Client <sup class="text-danger">*</sup></label>
							<input type="text" id="clientName" class="form-control" value="<?= $client->name ?>"
								   data-input readonly>
							<input type="hidden" id="clientId" name="clientId" value="<?= $client->id ?>">
						</div>
					</div>
					<div class="row">
						<div class="col-md-4 mb-3">
							<label for="rate" class="form-label">Employee Rate <sup class="text-danger">*</sup></label>
							<div class="input-group">
								<span class="input-group-text">$</span>
								<input type="number" step="any" min="0" class="form-control numeric-input" id="rate"
									   name="rate" placeholder="Ex: 15" required>
							</div>
						</div>
						<div class="col-md-4 mb-3">
							<label for="billRate" class="form-label">Bill Rate <sup
										class="text-danger">*</sup></label>
							<div class="input-group">
								<span class="input-group-text">$</span>
								<input type="number" step="any" min="0" class="form-control numeric-input"
									   id="billRate"
									   name="billRate" value="<?= $client->billRate ?>" required>
							</div>
						</div>
						<div class="col-md-4 mb-3">
							<label for="hours" class="form-label">Hours </label>
							<input type="number" step="any" min="0" class="form-control" name="hours" id="hours"
								   placeholder="Total Hours" required>
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
									   name="amount" readonly>
							</div>
						</div>
						<div class="col-md-4 mb-3">
							<label for="billAmount" class="form-label">Bill Amount <sup
										class="text-danger">*</sup></label>
							<div class="input-group">
								<span class="input-group-text">$</span>
								<input type="number" step="any" min="0" class="form-control numeric-input"
									   id="billAmount"
									   name="billAmount" readonly>
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-md-12 mb-3">
							<label for="comments" class="form-label">Comments </label>
							<textarea class="form-control" name="comments"
									  id="comments" placeholder="Enter Your Comments"></textarea>
						</div>
					</div>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-danger close">Close</button>
					<button type="submit" id="submit" class="btn btn-success me-2">Save</button>
				</div>
			</form>
		</div>
	</div>
</div>

<script>
	function handleSubmit() {
		var submitButton = document.getElementById('submit');
		submitButton.disabled = true;
		submitButton.innerHTML = 'Please wait...';
		return true;
	}

	$('.close').on('click', function () {
		$("#remoteModal1").modal('hide');
	});

	$(document).ready(function () {
		$("#startTime, #endTime").flatpickr({
			static: true,
			enableTime: true,
			noCalendar: true,
			dateFormat: "h:i K",
			time_24hr: false,
			onChange: calculateTotalHours
		});

		function formatTime(time) {
			var [hours, minutes] = time.split(':');
			hours = parseInt(hours, 10);
			var suffix = hours >= 12 ? 'pm' : 'am';
			hours = hours % 12 || 12;
			return hours + ':' + minutes + ' ' + suffix;
		}
		var serviceTypeColors = {
			'Attendant Care': '#F38938',
			'Housekeeping': '#98FB98',
			'Wound Care': '#E6E6FA',
			'Travel/Transport': '#d3d357',
			'Respite': '#F333FF',
		};

		// AJAX form submission for saving service
		$('form').on('submit', function (e) {
			e.preventDefault(); // Prevent the form from submitting normally
			var formData = $(this).serialize();
			var clientId = $('#clientId').val();
			$.ajax({
				url: '<?= admin_url('saveServiceFromCalendar') ?>',
				type: 'POST',
				data: formData,
				dataType: 'json',
				success: function (data) {
					if (data) {
						$('#remoteModal1').modal('hide');
						$.ajax({
							url: '<?= admin_url('getAllService/') ?>' + data.clientId,
							type: 'GET',
							dataType: 'json',
							success: function (data) {
								var calendarEvents = [];
								data.forEach(function (eventData) {
									var title = formatTime(eventData.startTime) + ' - ' + formatTime(eventData.endTime);
									var serviceType = eventData.serviceType;
									var color = serviceTypeColors[serviceType] || '';
									calendarEvents.push({
										id: eventData.id,
										start: eventData.date,
										end: eventData.date,
										allDay: true,
										title: title,
										backgroundColor: color,
										description: eventData.firstName + ' ' + eventData.lastName,
									});
								});
								calendar.setOption('eventSources', [calendarEvents]);
								$.ajax({
									type: "GET",
									url: "<?= admin_url('getScheduledHoursThisMonth/') ?>" + clientId,
									success: function (response) {
										console.log(response);
										$('#scheduledHoursCalendar').text(response)
									}
								});
							},
							error: function (xhr, status, error) {
								console.error('Error fetching data from server:', error);
							}
						});
						// Display success message
						Toast.fire({
							icon: "success",
							title: 'Service Added Successfully'
						});
					}
				},
				error: function (xhr, status, error) {
					console.error('Error saving service:', error);
				}
			});
		});

		// Calculate total hours function
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
				updateAmounts();
			} else {
				$("#hours").val('');
				$("#amount").val('');
				$("#billAmount").val('');
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

		function updateAmounts() {
			const rate = parseFloat($('#rate').val());
			const billRate = parseFloat($('#billRate').val());
			const hours = parseFloat($('#hours').val());

			if (!isNaN(rate) && !isNaN(billRate) && !isNaN(hours)) {
				const total = rate * hours;
				const billAmount = billRate * hours;
				$('#amount').val(total.toFixed(2));
				$('#billAmount').val(billAmount.toFixed(2));
			}
		}

		// Set rate and calculate amount when caregiver is selected
		$('#caregiverId').on('change', function () {
			const rate = parseFloat($(this).find(':selected').data('rate'));
			if (!isNaN(rate)) {
				$('#rate').val(rate);
				updateAmounts();
			}
		});

		// Calculate total amount when rate or hours change
		$('#hours, #rate, #billRate').on('input', function () {
			if ($('#caregiverId :selected').val() == '') {
				$('#hours').val('');
				Toast.fire({
					icon: "warning",
					title: 'Select Caregiver first!'
				});
			} else {
				updateAmounts();
			}
		});
	});
</script>
