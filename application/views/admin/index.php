<div class="d-flex justify-content-between align-items-center flex-wrap grid-margin">
	<div>
		<h4 class="mb-3 mb-md-0">Welcome again <?= getSession()->name ?></h4>
	</div>
	<div class="d-flex align-items-center flex-wrap text-nowrap">
		<div class="input-group flatpickr wd-200 me-2 mb-2 mb-md-0" id="dashboardDate">
			<span class="input-group-text input-group-addon bg-transparent border-primary" data-toggle><i
						data-feather="calendar" class="text-primary"></i></span>
			<input type="text" class="form-control bg-transparent border-primary" id="date" readonly
				   value="<?= date('d F Y') ?>"
				   data-input>
		</div>
	</div>
</div>
<div class="specific" id="printThis">
	<div class="row">
		<div class="col-12 col-xl-12 stretch-card">
			<div class="card">
				<div class="card-header d-flex align-items-center">
					<img src="<?= base_url('assets/images/logo.png') ?>" height="100" alt="<?= COMPANY ?>" class="me-3">
					<h3 class="mb-0"><?= COMPANY ?></h3>
				</div>
			</div>
		</div>
	</div>
	<div class="row mt-2">
		<div class="col-md-3 mb-2 grid-margin stretch-card">
			<div class="card">
				<div class="card-body">
					<div class="d-flex justify-content-between align-items-baseline">
						<h6 class="card-title mb-0">Total Caregivers</h6>
					</div>
					<div class="row">
						<div class="col-12 col-md-12 col-xl-8">
							<h3 class="mt-3"><?= $totalCaregiver ?></h3>
						</div>
					</div>
				</div>
			</div>
		</div>
		<div class="col-md-3 mb-2 grid-margin stretch-card">
			<div class="card">
				<div class="card-body">
					<div class="d-flex justify-content-between align-items-baseline">
						<h6 class="card-title mb-0">Total Clients</h6>
					</div>
					<div class="row">
						<div class="col-12 col-md-12 col-xl-8">
							<h3 class="mt-3"><?= $totalClient ?></h3>
						</div>
					</div>
				</div>
			</div>
		</div>
		<div class="col-md-3 mb-2 grid-margin stretch-card">
			<div class="card">
				<div class="card-body">
					<div class="d-flex justify-content-between align-items-baseline">
						<h6 class="card-title mb-0">Service Hours Today</h6>
					</div>
					<div class="row">
						<div class="col-12 col-md-12 col-xl-8">
							<h3 class="mt-3"><?= $serviceHoursToday ?></h3>
						</div>
					</div>
				</div>
			</div>
		</div>
		<div class="col-md-3 mb-2 grid-margin stretch-card">
			<div class="card">
				<div class="card-body">
					<div class="d-flex justify-content-between align-items-baseline">
						<h6 class="card-title mb-0">Service Hours this week</h6>
					</div>
					<div class="row">
						<div class="col-12 col-md-12 col-xl-8">
							<h3 class="mt-3"><?= $serviceHoursWeek ?></h3>
						</div>
					</div>
				</div>
			</div>
		</div>
		<div class="col-md-3 mb-2 grid-margin stretch-card">
			<div class="card">
				<div class="card-body">
					<div class="d-flex justify-content-between align-items-baseline">
						<h6 class="card-title mb-0">Total Amount today</h6>
					</div>
					<div class="row">
						<div class="col-12 col-md-12 col-xl-8">
							<h3 class="mt-3">$<?= $totalAmountToday ?></h3>
						</div>
					</div>
				</div>
			</div>
		</div>
		<div class="col-md-3 mb-2 grid-margin stretch-card">
			<div class="card">
				<div class="card-body">
					<div class="d-flex justify-content-between align-items-baseline">
						<h6 class="card-title mb-0">Total Amount this week</h6>
					</div>
					<div class="row">
						<div class="col-12 col-md-12 col-xl-8">
							<h3 class="mt-3">$<?= $totalAmountWeek ?></h3>
						</div>
					</div>
				</div>
			</div>
		</div>
		<div class="col-md-3 mb-2 grid-margin stretch-card">
			<div class="card">
				<div class="card-body">
					<div class="d-flex justify-content-between align-items-baseline">
						<h6 class="card-title mb-0">Billed Amount today</h6>
					</div>
					<div class="row">
						<div class="col-12 col-md-12 col-xl-8">
							<h3 class="mt-3">$<?= $billedAmountToday ?></h3>
						</div>
					</div>
				</div>
			</div>
		</div>
		<div class="col-md-3 mb-2 grid-margin stretch-card">
			<div class="card">
				<div class="card-body">
					<div class="d-flex justify-content-between align-items-baseline">
						<h6 class="card-title mb-0">Billed Amount this week</h6>
					</div>
					<div class="row">
						<div class="col-12 col-md-12 col-xl-8">
							<h3 class="mt-3">$<?= $billedAmountWeek ?></h3>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	<div class="row g-2 mt-1 mb-3 justify-content-center">
		<div class="col-12 col-md-6 col-lg-3">
			<button type="button" onclick="loadPopup('<?= admin_url('addCaregiver') ?>')"
					class="btn btn-primary w-100 dashboard-btn">
				<i class="link-icon" data-feather="shield"></i>
				Add Caregiver
			</button>
		</div>
		<div class="col-12 col-md-6 col-lg-3">
			<button type="button" onclick="loadPopup('<?= admin_url('addClient') ?>')"
					class="btn btn-primary w-100 dashboard-btn">
				<i class="link-icon" data-feather="users"></i>
				Add Client
			</button>
		</div>
		<div class="col-12 col-md-6 col-lg-3">
			<button type="button" onclick="loadPopup('<?= admin_url('addService') ?>')"
					class="btn btn-primary w-100 dashboard-btn">
				<i class="link-icon" data-feather="settings"></i>
				Add Service
			</button>
		</div>
		<div class="col-12 col-md-6 col-lg-3">
			<button type="button" onclick="window.location.href='<?= admin_url('generateInvoice') ?>'"
					class="btn btn-primary w-100 dashboard-btn">
				<i class="link-icon" data-feather="pen-tool"></i>
				Generate Invoice
			</button>
		</div>
	</div>
</div>
<div class="row">
	<div class="col-xl-6 grid-margin stretch-card">
		<div class="card">
			<div class="card-body">
				<h6 class="card-title">Scheduled Hours This Month</h6>
				<div id="apexBar"></div>
			</div>
		</div>
	</div>
	<div class="col-xl-6 grid-margin stretch-card">
		<div class="card">
			<div class="card-body">
				<h6 class="card-title">Invoices Data this month</h6>
				<div id="invoiceBar"></div>
			</div>
		</div>
	</div>
</div>
<div class="row">
	<div class="col-xl-12 grid-margin stretch-card">
		<div class="card">
			<div class="card-body">
				<canvas id="invoicePaid" width="400" height="200"></canvas>
			</div>
		</div>
	</div>
</div>

<div class="row">
	<div class="col-xl-12 grid-margin stretch-card">
		<div class="card">
			<div class="card-body">
				<canvas id="totalHoursInvoiceHours" width="400" height="200"></canvas>
			</div>
		</div>
	</div>
</div>
<style>
	.dashboard-btn {
		font-size: 1.2rem;
		padding: 15px 30px;
		border-radius: 10px;
		transition: transform 0.3s ease;
	}

	.dashboard-btn i {
		margin-right: 10px;
	}

	.dashboard-btn:hover {
		transform: scale(1.05);
	}

	#invoicePaid {
		max-height: 400px; /* Set the maximum height */
		height: 400px; /* Set a fixed height */
		width: 100%; /* You can set a percentage width or fixed value */
	}
</style>
<script>
	$(function () {
		'use strict';

		var colors = {
			primary: "#6571ff",
			secondary: "#7987a1",
			success: "#05a34a",
			info: "#66d1d1",
			warning: "#fbbc06",
			danger: "#ff3366",
			light: "#e9ecef",
			dark: "#060c17",
			muted: "#7987a1",
			gridBorder: "rgba(77, 138, 240, .15)",
			bodyColor: "#000",
			cardBg: "#fff"
		}

		var fontFamily = "'Roboto', Helvetica, sans-serif"
		var totalHours = [];
		var names = [];

		var cData = JSON.parse(`<?php echo $scheduledHours; ?>`);

		cData.forEach(function (item) {
			totalHours.push(parseFloat(item.totalHours));
			names.push(item.name);
		});
		if ($('#apexBar').length) {
			var options = {
				chart: {
					type: 'bar',
					height: '320',
					parentHeightOffset: 0,
					foreColor: colors.bodyColor,
					background: colors.cardBg,
					toolbar: {
						show: false
					},
				},
				theme: {
					mode: 'light'
				},
				tooltip: {
					theme: 'light'
				},
				colors: [colors.primary],
				grid: {
					padding: {
						bottom: -4
					},
					borderColor: colors.gridBorder,
					xaxis: {
						lines: {
							show: true
						}
					}
				},
				series: [{
					name: 'Scheduled Hours',
					data: totalHours
				}],
				xaxis: {
					// type: 'datetime',
					categories: names,
					axisBorder: {
						color: colors.gridBorder,
					},
					axisTicks: {
						color: colors.gridBorder,
					},
				},
				yaxis: {
					title: {
						text: 'Total Hours'
					}
				},
				legend: {
					show: true,
					position: "top",
					horizontalAlign: 'center',
					fontFamily: fontFamily,
					itemMargin: {
						horizontal: 8,
						vertical: 0
					},
				},
				stroke: {
					width: 0
				},
				plotOptions: {
					bar: {
						borderRadius: 4
					}
				}
			}
			var apexBarChart = new ApexCharts(document.querySelector("#apexBar"), options);
			apexBarChart.render();
		}

		var total = [];
		var paid = [];
		var due = [];
		var names = [];

		var cDataInvoice = JSON.parse(`<?php echo $invoiceData; ?>`);

		cDataInvoice.forEach(function (item) {
			total.push(parseFloat(item.total));
			paid.push(parseFloat(item.paidAmount));
			due.push(parseFloat(item.dueAmount));
			names.push(item.name);
		});

		console.log(cDataInvoice)
		if ($('#invoiceBar').length) {
			var options = {
				series: [{
					name: 'Total',
					data: total
				}, {
					name: 'Paid',
					data: paid
				}, {
					name: 'Due',
					data: due
				}],
				chart: {
					type: 'bar',
					height: 350,
					toolbar: {
						show: false
					},
				},
				plotOptions: {
					bar: {
						horizontal: false,
						columnWidth: '35%',
						endingShape: 'rounded'
					},
				},
				dataLabels: {
					enabled: false
				},
				stroke: {
					show: true,
					width: 2,
					colors: ['transparent']
				},
				xaxis: {
					categories: names,
				},
				yaxis: {
					title: {
						text: 'Total Amount ($)'
					}
				},
				fill: {
					opacity: 1
				},
				tooltip: {
					y: {
						formatter: function (val) {
							return "$ " + val
						}
					}
				}
			};

			var chart = new ApexCharts(document.querySelector("#invoiceBar"), options);
			chart.render();
		}
	});
	$(document).ready(function () {
		if ($('#invoicePaid').length) {
			var cDataI = JSON.parse(`<?php echo $data1; ?>`);
			console.log("Total Paid Data:", cDataI.paidAmount);
			var ctxI = document.getElementById("invoicePaid").getContext('2d');
			var chart = new Chart(ctxI, {
				type: "bar",
				data: {
					labels: cDataI.label,
					datasets: [
						{
							type: "bar",
							backgroundColor: 'rgba(75, 192, 192, 0.7)',
							borderColor: 'rgba(75, 192, 192, 1)',
							borderWidth: 1,
							barThickness: 30,
							label: "Total Amount",
							data: cDataI.totalAmount,
							order: 1
						},
						{
							type: "bar",
							backgroundColor: 'rgba(255, 99, 132, 0.7)',
							borderColor: 'rgba(255, 99, 132, 1)',
							borderWidth: 1,
							barThickness: 30,
							label: "Total Paid",
							data: cDataI.paidAmount,
							order: 2
						},
						{
							type: "line",
							label: "Due Amount",
							data: cDataI.totalDue,
							borderColor: 'rgb(40,67,137)',
							tension: 0.1,
							pointRadius: 3,
							borderWidth: 3,
							fill: false
						}
					]
				},
				options: {
					responsive: true,
					maintainAspectRatio: false,
					scales: {
						x: {
							barPercentage: 1.0,
							grid: {
								display: true
							}
						},
						y: {
							beginAtZero: true,
							ticks: {
								callback: function (value) {
									return '$' + value;
								}
							}
						}
					},
					plugins: {
						legend: {
							display: true
						},
						title: {
							display: true,
							position: "top",
							text: "Invoice Amount vs Paid Amount",
							font: {
								size: 18
							},
							color: "#111"
						}
					}
				}
			});
		}
	});
	$(document).ready(function () {
		if ($('#totalHoursInvoiceHours').length) {
			var cDataI = JSON.parse(`<?php echo $data11; ?>`);
			var ctxH = document.getElementById("totalHoursInvoiceHours").getContext('2d');
			var chart = new Chart(ctxH, {
				type: "bar",
				data: {
					labels: cDataI.label,
					datasets: [
						{
							type: "bar",
							backgroundColor: 'rgba(75, 192, 192, 0.7)',
							borderColor: 'rgba(75, 192, 192, 1)',
							borderWidth: 1,
							barThickness: 30,
							label: "Total Payroll Hours",
							data: cDataI.totalHours,
							order: 1
						},
						{
							type: "bar",
							backgroundColor: 'rgba(255, 99, 132, 0.7)',
							borderColor: 'rgba(255, 99, 132, 1)',
							borderWidth: 1,
							barThickness: 30,
							label: "Total Invoice Hours",
							data: cDataI.totalInvoiceHours,
							order: 2
						}
					]
				},
				options: {
					responsive: true,
					maintainAspectRatio: false,
					scales: {
						x: {
							barPercentage: 1.0,
							grid: {
								display: true
							}
						},
						y: {
							beginAtZero: true,
							ticks: {
								callback: function (value) {
									return value;
								}
							}
						}
					},
					plugins: {
						legend: {
							display: true
						},
						title: {
							display: true,
							position: "top",
							text: "Payroll Hours vs Invoice Hours",
							font: {
								size: 18
							},
							color: "#111"
						}
					}
				}
			});
		}
	});
</script>
