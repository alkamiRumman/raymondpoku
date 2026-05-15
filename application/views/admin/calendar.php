<div class="row">
	<div class="col-md-4 mb-3">
		<label class="form-label">Select Client <sup class="text-danger">*</sup></label>
		<select class="form-select" name="clientId" id="clientIdCalendar" data-width="100%" required>
			<option value="">Select Client</option>
			<?php foreach ($clients as $client) { ?>
				<option data-billrate="<?= $client->billRate ?>"
						data-budgetedhours="<?= $client->budgetedHours ?>"
						value="<?= $client->id ?>"><?= $client->name ?></option>
			<?php } ?>
		</select>
	</div>
	<div class="col-md-5 mb-3">
		<table id="calendarTable" class="table table-bordered table-responsive-sm table-sm">
			<tbody>
			<tr>
				<th>Budgeted Hours</th>
				<td>
					<div id="budgetedHoursCalendar"></div>
				</td>
			</tr>
			<tr>
				<th>Scheduled Hours for <span id="monthName"></span></th>
				<td>
					<div id="scheduledHoursCalendar"></div>
				</td>
			</tr>
			</tbody>
		</table>
	</div>
	<div class="col-md-3 text-center mb-3 copydata" style="display: none">
		<br>
		<button id="copy" class="btn btn-lg btn-primary me-3">Copy data to next month</button>
	</div>
</div>
<div class="row">
	<div class="col-12 col-xl-12">
		<table class="table table-sm">
			<tr class="text-center">
				<th>Service Type</th>
				<td style="background-color: #F38938">Attendant Care</td>
				<td style="background-color: #98FB98">Housekeeping</td>
				<td style="background-color: #E6E6FA">Wound Care</td>
				<td style="background-color: #d3d357">Travel/Transport</td>
				<td style="background-color: #F333FF">Respite</td>
			</tr>
		</table>
	</div>
</div>
<div class="row">
	<div class="col-12 col-xl-12 stretch-card">
		<div class="card-body" style="margin-top: 10px">
			<div id='fullcalendar'></div>
		</div>
	</div>
</div>
<style>
	.fc-event {
		margin: 0 !important;
		padding: 0 !important;
		border: none;
	}
</style>
<script>
	var calendar;
	document.addEventListener('DOMContentLoaded', function () {
		var calendarEl = document.getElementById('fullcalendar');
		var userChangedView = false;

		function initFullCalendar(viewType, height, fontSize) {
			calendar = new FullCalendar.Calendar(calendarEl, {
				headerToolbar: {
					left: "prev,next",
					center: 'title',
					right: 'dayGridMonth,listMonth'
				},
				fixedWeekCount: true,
				initialView: viewType,
				eventOverlap: true,
				timeZone: 'UTC',
				hiddenDays: [],
				navLinks: true,
				dayMaxEvents: 5,
				contentHeight: 'auto',
				aspectRatio: 1.35,
				height: height,
				events: [],
				eventContent: function (arg) {
					return {
						html: '<p onclick="loadPopup(\'<?= base_url("admin/editServiceCalendar/") ?>' + arg.event.id + '\')" class="text-center" style="cursor: default; font-weight: bold; font-size: ' + fontSize + 'px">' + arg.event.title + '<br>' + arg.event.extendedProps.description + '</p>'
					};
				},
				viewDidMount: function () {
					userChangedView = false; // Reset flag after view is rendered
				},
				dateClick: function (info) {
					var clientId = $('#clientIdCalendar').find(":selected").val();
					if (clientId) {
						loadPopup('<?= admin_url('addServiceFromCalendar/') ?>' + clientId + '/' + info.dateStr)
					} else {
						Toast.fire({
							icon: "warning",
							title: 'Select a Client First!'
						});
					}
				},
				datesSet: function (info) {
					var clientId = $('#clientIdCalendar').find(":selected").val();
					var newDate = new Date(info.view.activeStart);
					var newDate1 = newDate.setDate(newDate.getDate() + 7);
					var monthName = new Date(newDate1).getMonth();
					var monthNames = ["January", "February", "March", "April", "May", "June",
						"July", "August", "September", "October", "November", "December"];
					$('#monthName').text(monthNames[monthName]);
					var year = new Date(newDate1).getFullYear();
					if (new Date().getMonth() === monthName && new Date().getFullYear() === year && clientId) {
						$('.copydata').show();
					} else {
						$('.copydata').hide();
					}
					if (clientId) {
						$.ajax({
							type: "GET",
							url: "<?= admin_url('getScheduledHoursOtherMonth/') ?>" + clientId + '/' + (monthName + 1) + '/' + year,
							success: function (response) {
								console.log(response);
								$('#scheduledHoursCalendar').text(response)
							}
						});
					}
				}
			});

			calendar.on('viewDidMount', function () {
				userChangedView = false; // Reset flag after the calendar view changes
			});
			calendar.render();
		}

		function determineViewAndHeight() {
			if (userChangedView) return; // Exit if user changed the view manually

			var viewType = window.innerWidth >= 768 ? 'dayGridMonth' : 'listMonth';
			var height = window.innerWidth >= 768 ? null : window.innerHeight;
			var fontSize = window.innerWidth >= 768 ? 12 : 9;

			if (calendar) {
				var currentView = calendar.view.type;
				if (currentView !== viewType) {
					calendar.destroy();
					initFullCalendar(viewType, height, fontSize);
				} else if (height !== null && calendar.el.offsetHeight !== height) {
					calendar.setOption('height', height, fontSize);
					calendar.render(); // Render the calendar after adjusting the height
				}
			} else {
				initFullCalendar(viewType, height, fontSize);
			}
		}

		determineViewAndHeight();
		window.addEventListener('resize', function () {
			determineViewAndHeight();
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

		$("#clientIdCalendar").on('change', function () {
			var clientId = $('#clientIdCalendar').find(":selected").val();
			var budgetedHours = $(this).find(':selected').data('budgetedhours');
			$('#budgetedHoursCalendar').text(budgetedHours);

			$.ajax({
				url: '<?= admin_url('getAllService/') ?>' + clientId,
				type: 'GET',
				dataType: 'json',
				success: function (data) {
					var calendarEvents = [];
					console.log(data);
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

					// Calculate monthName and year after updating the calendar
					var newDate = new Date(calendar.view.currentStart);
					var newDate1 = newDate.setDate(newDate.getDate() + 7);
					var monthName = new Date(newDate1).getMonth();
					var year = new Date(newDate1).getFullYear();

					console.log(monthName);
					var monthNames = ["January", "February", "March", "April", "May", "June",
						"July", "August", "September", "October", "November", "December"];
					$('#monthName').text(monthNames[monthName]);

					// Show or hide the copydata button based on the current month and year
					if (new Date().getMonth() === monthName && new Date().getFullYear() === year && clientId) {
						$('.copydata').show();
					} else {
						$('.copydata').hide();
					}

					// Now that monthName is defined, you can make the second AJAX call
					$.ajax({
						type: "GET",
						url: "<?= admin_url('getScheduledHoursOtherMonth/') ?>" + clientId + '/' + (monthName + 1) + '/' + year,
						success: function (response) {
							$('#scheduledHoursCalendar').text(response);
						},
						error: function (xhr, status, error) {
							console.error('Error fetching scheduled hours:', error);
						}
					});
				},
				error: function (xhr, status, error) {
					console.error('Error fetching data from server:', error);
				}
			});
		});

		// Detect user view change and set flag
		calendarEl.addEventListener('click', function () {
			if (!userChangedView) {
				userChangedView = true;
			}
		});

		$('#copy').on('click', function () {
			var clientId = $('#clientIdCalendar').find(":selected").val();
			if (!clientId) {
				Toast.fire({
					icon: "warning",
					title: 'Select a Client First!'
				});
				return;
			}

			var submitButton = $(this);
			submitButton.prop('disabled', true).text('Copying, please wait...');
			$.ajax({
				url: '<?= admin_url('copyServiceToNextMonth') ?>',
				type: 'POST',
				data: {clientId: clientId},
				success: function (response) {
					var data = JSON.parse(response);
					if (data.status == 'success') {
						Toast.fire({
							icon: "success",
							title: data.message
						});
					} else {
						Toast.fire({
							icon: "error",
							title: data.message
						});
					}
					submitButton.prop('disabled', false).text('Copy data to next month');
				},
				error: function (xhr, status, error) {
					Toast.fire({
						icon: "error",
						title: error
					});
					console.error('Error copying data:', error);
					submitButton.prop('disabled', false).text('Copy data to next month');
				}
			});

			$.ajax({
				url: "<?= admin_url('getAllService/') ?>" + clientId,
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
				},
				error: function (xhr, status, error) {
					Toast.fire({
						icon: "error",
						title: 'Error fetching current month data.'
					});
					console.error('Error fetching data from server:', error);
					submitButton.prop('disabled', false).text('Copy data to next month');
				}
			});
		});
	});
</script>
