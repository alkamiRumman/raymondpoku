<div class="row">
	<div class="col-md-12">
		<button type="button" id="ssbutton"
				class="btn btn-sm btn-outline-primary float-end btn-icon-text me-2 mb-2 mb-md-0">
			<i class="btn-icon-prepend" data-feather="image"></i>
			Screenshot
		</button>
	</div>
</div>
<div class="specific">
	<div class="row">
		<div class="col-12 col-xl-12 stretch-card">
			<div class="card">
				<div class="card-header d-flex align-items-center">
					<img src="<?= base_url('assets/images/Logo.png') ?>" height="100" alt="<?= COMPANY ?>"
						 class="me-3">
					<h3 class="mb-0"><?= COMPANY ?></h3>
				</div>
			</div>
		</div>
	</div>
	<div class="card-body" style="margin-top: 10px">
		<div id='fullcalendar'></div>
	</div>
</div>
<style>
	.specific {
		background-color: #ffffff;
	}

	.fc-event {
		margin: 0 !important;
		padding: 0 !important;
		border: none;
	}

	.green, .fc-day[data-date].green {
		background-color: #4CAF50;
		color: #fff;
		border-radius: 5px;
	}

	.red, .fc-day[data-date].red {
		background-color: #F44336;
		color: #fff;
		border-radius: 5px;
	}
</style>
<script>
	document.getElementById('ssbutton').addEventListener('click', function () {
		var element = document.getElementsByClassName('specific')[0];
		html2canvas(element, {
			useCORS: true,
			backgroundColor: null,
			onrendered: function (canvas) {
				// Convert canvas to an image and download it
				var imgData = canvas.toDataURL('image/png');
				var a = document.createElement('a');
				a.href = imgData;
				a.download = '<?= COMPANY ?>' + '_calendar_' +
					new Date().toLocaleString('en-us', {
						day: 'numeric',
						month: 'short',
						year: 'numeric'
					}).split('T')[0] + '.png';
				a.click();
			}
		});
	});
	document.addEventListener('DOMContentLoaded', function () {
		var calendarEl = document.getElementById('fullcalendar');
		var calendar;
		var userChangedView = false; // Flag to track user view change

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
				dayMaxEvents: 1,
				contentHeight: 'auto',
				aspectRatio: 1.35,
				height: height,
				events: [],
				eventContent: function (arg) {
					return {
						html: '<p class="text-center" style="font-weight:bold;font-size:' + fontSize + 'px">$' + arg.event.title + '<br>' + arg.event.extendedProps.description + ' Trades</p>'
					};
				},
				eventDidMount: function (arg) {
					applyDayColor(arg.event.startStr, arg.event.extendedProps.totalTradeAmount);
				},
				viewDidMount: function () {
					userChangedView = false; // Reset flag after view is rendered
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
			var fontSize = window.innerWidth >= 768 ? 17 : 9;

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

		$.ajax({
			url: '<?= admin_url('getAllTrade') ?>',
			type: 'GET',
			dataType: 'json',
			success: function (data) {
				var calendarEvents = [];
				data.forEach(function (eventData) {
					var title = Math.abs(eventData.totalTradeAmount);
					var color = eventData.totalTradeAmount < 0 ? '#F44336' : '#4CAF50';
					calendarEvents.push({
						id: eventData.id,
						start: eventData.entryDate,
						end: eventData.entryDate,
						allDay: true,
						title: title,
						description: eventData.totalTrade,
						backgroundColor: color,
						totalTradeAmount: eventData.totalTradeAmount, // Store totalTradeAmount in event data
					});
				});
				calendar.setOption('eventSources', [calendarEvents]);
			},
			error: function (xhr, status, error) {
				console.error('Error fetching data from server:', error);
			}
		});

		// Detect user view change and set flag
		calendarEl.addEventListener('click', function () {
			if (!userChangedView) {
				userChangedView = true;
			}
		});
	});
</script>
