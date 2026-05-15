<div class="row align-items-end mb-3">
	<div class="col-md-4">
		<label class="form-label fw-semibold">Select Client <sup class="text-danger">*</sup></label>
		<select class="form-select" name="clientId" id="clientIdCalendar" data-width="100%" required>
			<option value="">— Choose a client —</option>
			<?php foreach ($clients as $client) { ?>
				<option data-billrate="<?= $client->billRate ?>"
						data-budgetedhours="<?= $client->budgetedHours ?>"
						value="<?= $client->id ?>"><?= $client->name ?></option>
			<?php } ?>
		</select>
	</div>
	<div class="col-md-5">
		<table id="calendarTable" class="table table-bordered table-sm mb-0">
			<tbody>
			<tr>
				<th class="ps-3">Budgeted Hours</th>
				<td><span id="budgetedHoursCalendar">—</span></td>
			</tr>
			<tr>
				<th class="ps-3">Scheduled Hours — <span id="monthName"></span></th>
				<td><span id="scheduledHoursCalendar">—</span></td>
			</tr>
			</tbody>
		</table>
	</div>
	<div class="col-md-3 copydata" style="display:none">
		<button id="copy" class="btn btn-primary w-100">
			<i data-feather="copy" class="me-1" style="width:15px;height:15px"></i>
			<span id="copyBtnLabel">Copy to next month</span>
		</button>
	</div>
</div>

<div class="row mb-3">
	<div class="col-12">
		<table class="table table-sm mb-0">
			<tr class="text-center">
				<th class="text-start ps-0">Service Type</th>
				<td style="background-color:#F38938;color:#fff;border-radius:4px">Attendant Care</td>
				<td style="background-color:#4ade80;color:#166534;border-radius:4px">Housekeeping</td>
				<td style="background-color:#c4b5fd;color:#4c1d95;border-radius:4px">Wound Care</td>
				<td style="background-color:#fde047;color:#713f12;border-radius:4px">Travel/Transport</td>
				<td style="background-color:#f0abfc;color:#701a75;border-radius:4px">Respite</td>
			</tr>
		</table>
	</div>
</div>

<div class="row">
	<div class="col-12">
		<div id="fullcalendar"></div>
	</div>
</div>

<style>
.fc-event { margin:0 !important; padding:0 !important; border:none; }
</style>

<script>
var calendar;
var currentViewMonth = new Date().getMonth();   // 0-based
var currentViewYear  = new Date().getFullYear();

document.addEventListener('DOMContentLoaded', function () {
	var calendarEl = document.getElementById('fullcalendar');
	var userChangedView = false;

	var serviceTypeColors = {
		'Attendant Care': '#F38938',
		'Housekeeping':   '#4ade80',
		'Wound Care':     '#c4b5fd',
		'Travel/Transport': '#fde047',
		'Respite':        '#f0abfc',
	};

	function formatTime(time) {
		var parts = time.split(':');
		var h = parseInt(parts[0], 10);
		var m = parts[1];
		var suffix = h >= 12 ? 'pm' : 'am';
		h = h % 12 || 12;
		return h + ':' + m + ' ' + suffix;
	}

	var monthNames = ["January","February","March","April","May","June",
					  "July","August","September","October","November","December"];

	function updateCopyButton(clientId) {
		if (clientId) {
			$('.copydata').show();
			var nextMonth = currentViewMonth === 11 ? 0 : currentViewMonth + 1;
			var nextYear  = currentViewMonth === 11 ? currentViewYear + 1 : currentViewYear;
			$('#copyBtnLabel').text('Copy ' + monthNames[currentViewMonth] + ' → ' + monthNames[nextMonth]);
			if (window.feather) feather.replace();
		} else {
			$('.copydata').hide();
		}
	}

	function loadScheduledHours(clientId) {
		if (!clientId) return;
		$.ajax({
			type: 'GET',
			url: '<?= admin_url('getScheduledHoursOtherMonth/') ?>' + clientId + '/' + (currentViewMonth + 1) + '/' + currentViewYear,
			success: function (response) { $('#scheduledHoursCalendar').text(response || '0'); }
		});
	}

	function initFullCalendar(viewType, height, fontSize) {
		calendar = new FullCalendar.Calendar(calendarEl, {
			headerToolbar: {
				left:   'prev,next',
				center: 'title',
				right:  'dayGridMonth,listMonth'
			},
			fixedWeekCount: true,
			initialView:    viewType,
			eventOverlap:   true,
			timeZone:       'UTC',
			navLinks:       true,
			dayMaxEvents:   5,
			contentHeight:  'auto',
			aspectRatio:    1.35,
			height:         height,
			events:         [],
			eventContent: function (arg) {
				return {
					html: '<p onclick="loadPopup(\'<?= base_url("admin/editServiceCalendar/") ?>' + arg.event.id + '\')" '
						+ 'class="text-center" style="cursor:pointer;font-weight:600;font-size:' + fontSize + 'px;line-height:1.3;padding:1px 2px">'
						+ arg.event.title + '<br>' + arg.event.extendedProps.description + '</p>'
				};
			},
			viewDidMount: function () { userChangedView = false; },
			dateClick: function (info) {
				var clientId = $('#clientIdCalendar').val();
				if (clientId) {
					loadPopup('<?= admin_url('addServiceFromCalendar/') ?>' + clientId + '/' + info.dateStr);
				} else {
					Toast.fire({ icon: 'warning', title: 'Select a client first!' });
				}
			},
			datesSet: function (info) {
				var d = new Date(info.view.activeStart);
				d.setDate(d.getDate() + 7);
				currentViewMonth = d.getMonth();
				currentViewYear  = d.getFullYear();
				$('#monthName').text(monthNames[currentViewMonth] + ' ' + currentViewYear);
				var clientId = $('#clientIdCalendar').val();
				updateCopyButton(clientId);
				loadScheduledHours(clientId);
			}
		});
		calendar.on('viewDidMount', function () { userChangedView = false; });
		calendar.render();
	}

	function determineViewAndHeight() {
		if (userChangedView) return;
		var viewType  = window.innerWidth >= 768 ? 'dayGridMonth' : 'listMonth';
		var height    = window.innerWidth >= 768 ? null : window.innerHeight;
		var fontSize  = window.innerWidth >= 768 ? 12 : 9;
		if (calendar) {
			if (calendar.view.type !== viewType) {
				calendar.destroy();
				initFullCalendar(viewType, height, fontSize);
			} else if (height !== null) {
				calendar.setOption('height', height);
				calendar.render();
			}
		} else {
			initFullCalendar(viewType, height, fontSize);
		}
	}

	determineViewAndHeight();
	window.addEventListener('resize', function () { determineViewAndHeight(); });

	$("#clientIdCalendar").on('change', function () {
		var clientId      = $(this).val();
		var budgetedHours = $(this).find(':selected').data('budgetedhours');
		$('#budgetedHoursCalendar').text(budgetedHours || '—');
		updateCopyButton(clientId);

		if (!clientId) { calendar.setOption('eventSources', [[]]); return; }

		$.ajax({
			url:      '<?= admin_url('getAllService/') ?>' + clientId,
			type:     'GET',
			dataType: 'json',
			success:  function (data) {
				var events = data.map(function (e) {
					return {
						id:              e.id,
						start:           e.date,
						end:             e.date,
						allDay:          true,
						title:           formatTime(e.startTime) + ' - ' + formatTime(e.endTime),
						backgroundColor: serviceTypeColors[e.serviceType] || '#94a3b8',
						description:     e.firstName + ' ' + e.lastName
					};
				});
				calendar.setOption('eventSources', [events]);
				loadScheduledHours(clientId);
				updateCopyButton(clientId);
			},
			error: function (x, s, e) { console.error('Events load error:', e); }
		});
	});

	calendarEl.addEventListener('click', function () { userChangedView = true; });

	$('#copy').on('click', function () {
		var clientId = $('#clientIdCalendar').val();
		if (!clientId) {
			Toast.fire({ icon: 'warning', title: 'Select a client first!' });
			return;
		}
		var btn = $(this);
		btn.prop('disabled', true).find('#copyBtnLabel').text('Copying…');

		$.ajax({
			url:     '<?= admin_url('copyServiceToNextMonth') ?>',
			type:    'POST',
			data:    { clientId: clientId, month: currentViewMonth + 1, year: currentViewYear },
			success: function (response) {
				var data = JSON.parse(response);
				Toast.fire({ icon: data.status === 'success' ? 'success' : 'error', title: data.message });
				// Reload calendar events
				$.ajax({
					url: '<?= admin_url('getAllService/') ?>' + clientId,
					type: 'GET', dataType: 'json',
					success: function (d) {
						var events = d.map(function (e) {
							return {
								id:              e.id, start: e.date, end: e.date, allDay: true,
								title:           formatTime(e.startTime) + ' - ' + formatTime(e.endTime),
								backgroundColor: serviceTypeColors[e.serviceType] || '#94a3b8',
								description:     e.firstName + ' ' + e.lastName
							};
						});
						calendar.setOption('eventSources', [events]);
					}
				});
			},
			error: function (x, s, e) { Toast.fire({ icon: 'error', title: 'Request failed.' }); },
			complete: function () {
				btn.prop('disabled', false);
				updateCopyButton(clientId);
			}
		});
	});
});
</script>
