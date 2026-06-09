<?php
$greeting = 'Good morning';
$hour = (int) date('G');
if ($hour >= 12 && $hour < 17) $greeting = 'Good afternoon';
if ($hour >= 17) $greeting = 'Good evening';
?>

<!-- ═══════════════════════════════════════════════════
     WELCOME HERO
════════════════════════════════════════════════════ -->
<div class="dash-hero mb-4">
	<div class="dash-hero-inner">
		<div class="dash-hero-left">
			<p class="dash-greeting"><?= $greeting ?></p>
			<h2 class="dash-name"><?= htmlspecialchars(getSession()->name) ?></h2>
			<p class="dash-sub"><?= COMPANY ?> &nbsp;·&nbsp; <?= date('l, F j, Y') ?></p>
		</div>
		<div class="dash-hero-right">
			<img src="<?= base_url('assets/images/logo.png') ?>" alt="<?= COMPANY ?>" class="dash-logo">
		</div>
	</div>
</div>

<!-- ═══════════════════════════════════════════════════
     QUICK ACTIONS
════════════════════════════════════════════════════ -->
<div class="dash-actions mb-4">
	<button onclick="loadPopup('<?= admin_url('addCaregiver') ?>')" class="dash-action-btn">
		<span class="dab-icon" style="background:#EFF6FF"><i data-feather="heart" style="color:#2563EB"></i></span>
		<span class="dab-label">Add Caregiver</span>
	</button>
	<button onclick="loadPopup('<?= admin_url('addClient') ?>')" class="dash-action-btn">
		<span class="dab-icon" style="background:#F0FDF4"><i data-feather="user-plus" style="color:#16A34A"></i></span>
		<span class="dab-label">Add Client</span>
	</button>
	<button onclick="loadPopup('<?= admin_url('addService') ?>')" class="dash-action-btn">
		<span class="dab-icon" style="background:#FFFBEB"><i data-feather="clipboard" style="color:#D97706"></i></span>
		<span class="dab-label">Add Service</span>
	</button>
	<button onclick="window.location.href='<?= admin_url('generateInvoice') ?>'" class="dash-action-btn">
		<span class="dab-icon" style="background:#F5F3FF"><i data-feather="file-plus" style="color:#7C3AED"></i></span>
		<span class="dab-label">Generate Invoice</span>
	</button>
	<a href="<?= admin_url('calendar') ?>" class="dash-action-btn">
		<span class="dab-icon" style="background:#FFF1F2"><i data-feather="calendar" style="color:#E11D48"></i></span>
		<span class="dab-label">Calendar</span>
	</a>
	<a href="<?= admin_url('invoice') ?>" class="dash-action-btn">
		<span class="dab-icon" style="background:#ECFEFF"><i data-feather="dollar-sign" style="color:#0891B2"></i></span>
		<span class="dab-label">Invoices</span>
	</a>
</div>

<!-- ═══════════════════════════════════════════════════
     KPI GRID — ROW 1: TEAM + HOURS
════════════════════════════════════════════════════ -->
<div class="kpi-grid mb-3">

	<div class="kpi-card-v2">
		<div class="kpi-icon-wrap" style="background:#EFF6FF">
			<i data-feather="heart" style="color:#2563EB;width:20px;height:20px"></i>
		</div>
		<div class="kpi-body">
			<span class="kpi-label-v2">Total Caregivers</span>
			<span class="kpi-value-v2"><?= $totalCaregiver ?></span>
		</div>
		<div class="kpi-bar" style="background:#2563EB"></div>
	</div>

	<div class="kpi-card-v2">
		<div class="kpi-icon-wrap" style="background:#F0FDF4">
			<i data-feather="users" style="color:#16A34A;width:20px;height:20px"></i>
		</div>
		<div class="kpi-body">
			<span class="kpi-label-v2">Total Clients</span>
			<span class="kpi-value-v2"><?= $totalClient ?></span>
		</div>
		<div class="kpi-bar" style="background:#16A34A"></div>
	</div>

	<div class="kpi-card-v2">
		<div class="kpi-icon-wrap" style="background:#FFFBEB">
			<i data-feather="clock" style="color:#D97706;width:20px;height:20px"></i>
		</div>
		<div class="kpi-body">
			<span class="kpi-label-v2">Service Hours Today</span>
			<span class="kpi-value-v2"><?= $serviceHoursToday ?: '0' ?></span>
			<span class="kpi-sub-v2">hrs</span>
		</div>
		<div class="kpi-bar" style="background:#D97706"></div>
	</div>

	<div class="kpi-card-v2">
		<div class="kpi-icon-wrap" style="background:#FFF1F2">
			<i data-feather="activity" style="color:#E11D48;width:20px;height:20px"></i>
		</div>
		<div class="kpi-body">
			<span class="kpi-label-v2">Service Hours This Week</span>
			<span class="kpi-value-v2"><?= $serviceHoursWeek ?: '0' ?></span>
			<span class="kpi-sub-v2">hrs</span>
		</div>
		<div class="kpi-bar" style="background:#E11D48"></div>
	</div>

</div>

<!-- ═══════════════════════════════════════════════════
     KPI GRID — ROW 2: FINANCIALS
════════════════════════════════════════════════════ -->
<div class="kpi-grid kpi-grid-5 mb-4">

	<div class="kpi-card-v2">
		<div class="kpi-icon-wrap" style="background:#FEF2F2">
			<i data-feather="alert-circle" style="color:#DC2626;width:20px;height:20px"></i>
		</div>
		<div class="kpi-body">
			<span class="kpi-label-v2">Outstanding Balance</span>
			<span class="kpi-value-v2" style="color:#DC2626">$<?= number_format((float)($totalOutstanding ?: 0), 0) ?></span>
		</div>
		<div class="kpi-bar" style="background:#DC2626"></div>
	</div>

	<div class="kpi-card-v2">
		<div class="kpi-icon-wrap" style="background:#F5F3FF">
			<i data-feather="trending-up" style="color:#7C3AED;width:20px;height:20px"></i>
		</div>
		<div class="kpi-body">
			<span class="kpi-label-v2">Total Amount Today</span>
			<span class="kpi-value-v2">$<?= number_format((float)($totalAmountToday ?: 0), 0) ?></span>
		</div>
		<div class="kpi-bar" style="background:#7C3AED"></div>
	</div>

	<div class="kpi-card-v2">
		<div class="kpi-icon-wrap" style="background:#ECFEFF">
			<i data-feather="bar-chart-2" style="color:#0891B2;width:20px;height:20px"></i>
		</div>
		<div class="kpi-body">
			<span class="kpi-label-v2">Total Amount This Week</span>
			<span class="kpi-value-v2">$<?= number_format((float)($totalAmountWeek ?: 0), 0) ?></span>
		</div>
		<div class="kpi-bar" style="background:#0891B2"></div>
	</div>

	<div class="kpi-card-v2">
		<div class="kpi-icon-wrap" style="background:#FFF7ED">
			<i data-feather="file-text" style="color:#EA580C;width:20px;height:20px"></i>
		</div>
		<div class="kpi-body">
			<span class="kpi-label-v2">Billed Amount Today</span>
			<span class="kpi-value-v2">$<?= number_format((float)($billedAmountToday ?: 0), 0) ?></span>
		</div>
		<div class="kpi-bar" style="background:#EA580C"></div>
	</div>

	<div class="kpi-card-v2">
		<div class="kpi-icon-wrap" style="background:#F0FDF4">
			<i data-feather="dollar-sign" style="color:#16A34A;width:20px;height:20px"></i>
		</div>
		<div class="kpi-body">
			<span class="kpi-label-v2">Billed Amount This Week</span>
			<span class="kpi-value-v2">$<?= number_format((float)($billedAmountWeek ?: 0), 0) ?></span>
		</div>
		<div class="kpi-bar" style="background:#16A34A"></div>
	</div>

</div>

<!-- ═══════════════════════════════════════════════════
     CHARTS ROW 1 — SCHEDULED HOURS + INVOICE BREAKDOWN
════════════════════════════════════════════════════ -->
<div class="row g-3 mb-3">
	<div class="col-xl-6">
		<div class="card h-100">
			<div class="card-header d-flex align-items-center justify-content-between flex-wrap gap-2">
				<div>
					<h6 class="chart-title mb-0">Scheduled Hours</h6>
					<p class="chart-sub mb-0">By client</p>
				</div>
				<div class="d-flex align-items-center gap-1">
					<select id="scheduledMonth" class="form-select form-select-sm" style="width:auto">
						<?php $months = ['January','February','March','April','May','June','July','August','September','October','November','December'];
						foreach ($months as $mi => $mn): ?>
						<option value="<?= $mi+1 ?>" <?= ($mi+1 == (int)date('n')) ? 'selected' : '' ?>><?= $mn ?></option>
						<?php endforeach; ?>
					</select>
					<select id="scheduledYear" class="form-select form-select-sm" style="width:auto">
						<?php for ($y = (int)date('Y')-3; $y <= (int)date('Y')+1; $y++): ?>
						<option value="<?= $y ?>" <?= ($y == (int)date('Y')) ? 'selected' : '' ?>><?= $y ?></option>
						<?php endfor; ?>
					</select>
				</div>
			</div>
			<div class="card-body pt-2">
				<div id="apexBar"></div>
			</div>
		</div>
	</div>
	<div class="col-xl-6">
		<div class="card h-100">
			<div class="card-header d-flex align-items-center justify-content-between flex-wrap gap-2">
				<div>
					<h6 class="chart-title mb-0">Invoice Breakdown</h6>
					<p class="chart-sub mb-0">Total · Paid · Due</p>
				</div>
				<div class="d-flex align-items-center gap-1">
					<select id="invoiceBreakMonth" class="form-select form-select-sm" style="width:auto">
						<?php foreach ($months as $mi => $mn): ?>
						<option value="<?= $mi+1 ?>" <?= ($mi+1 == (int)date('n')) ? 'selected' : '' ?>><?= $mn ?></option>
						<?php endforeach; ?>
					</select>
					<select id="invoiceBreakYear" class="form-select form-select-sm" style="width:auto">
						<?php for ($y = (int)date('Y')-3; $y <= (int)date('Y')+1; $y++): ?>
						<option value="<?= $y ?>" <?= ($y == (int)date('Y')) ? 'selected' : '' ?>><?= $y ?></option>
						<?php endfor; ?>
					</select>
				</div>
			</div>
			<div class="card-body pt-2">
				<div id="invoiceBar"></div>
			</div>
		</div>
	</div>
</div>

<!-- ═══════════════════════════════════════════════════
     CHARTS ROW 2 — ANNUAL INVOICE OVERVIEW
════════════════════════════════════════════════════ -->
<div class="row g-3 mb-3">
	<div class="col-12">
		<div class="card">
			<div class="card-header d-flex align-items-center justify-content-between flex-wrap gap-2">
				<div>
					<h6 class="chart-title mb-0">Invoice Amount vs Paid Amount</h6>
					<p class="chart-sub mb-0">Monthly comparison</p>
				</div>
				<select id="invoicePaidYear" class="form-select form-select-sm" style="width:auto">
					<?php for ($y = (int)date('Y')-3; $y <= (int)date('Y')+1; $y++): ?>
					<option value="<?= $y ?>" <?= ($y == (int)date('Y')) ? 'selected' : '' ?>><?= $y ?></option>
					<?php endfor; ?>
				</select>
			</div>
			<div class="card-body pt-2">
				<div style="position:relative;height:300px">
					<canvas id="invoicePaid"></canvas>
				</div>
			</div>
		</div>
	</div>
</div>

<!-- ═══════════════════════════════════════════════════
     CHARTS ROW 3 — PAYROLL vs INVOICE HOURS
════════════════════════════════════════════════════ -->
<div class="row g-3 mb-3">
	<div class="col-12">
		<div class="card">
			<div class="card-header d-flex align-items-center justify-content-between flex-wrap gap-2">
				<div>
					<h6 class="chart-title mb-0">Payroll Hours vs Invoice Hours</h6>
					<p class="chart-sub mb-0">Monthly comparison</p>
				</div>
				<select id="payrollYear" class="form-select form-select-sm" style="width:auto">
					<?php for ($y = (int)date('Y')-3; $y <= (int)date('Y')+1; $y++): ?>
					<option value="<?= $y ?>" <?= ($y == (int)date('Y')) ? 'selected' : '' ?>><?= $y ?></option>
					<?php endfor; ?>
				</select>
			</div>
			<div class="card-body pt-2">
				<div style="position:relative;height:280px">
					<canvas id="totalHoursInvoiceHours"></canvas>
				</div>
			</div>
		</div>
	</div>
</div>

<!-- ═══════════════════════════════════════════════════
     DASHBOARD STYLES
════════════════════════════════════════════════════ -->
<style>
/* ── Hero ── */
.dash-hero {
	background: linear-gradient(135deg, #0F172A 0%, #1E3A5F 50%, #2563EB 100%);
	border-radius: 14px;
	padding: 28px 32px;
	color: #fff;
	position: relative;
	overflow: hidden;
}
.dash-hero::before {
	content: '';
	position: absolute;
	top: -40px; right: -40px;
	width: 220px; height: 220px;
	border-radius: 50%;
	background: rgba(255,255,255,.05);
}
.dash-hero::after {
	content: '';
	position: absolute;
	bottom: -60px; right: 80px;
	width: 160px; height: 160px;
	border-radius: 50%;
	background: rgba(255,255,255,.04);
}
.dash-hero-inner {
	display: flex;
	align-items: center;
	justify-content: space-between;
	position: relative;
	z-index: 1;
}
.dash-greeting {
	font-size: 13px;
	font-weight: 500;
	opacity: .65;
	margin-bottom: 4px;
	letter-spacing: .3px;
}
.dash-name {
	font-size: 1.65rem;
	font-weight: 700;
	margin-bottom: 6px;
	line-height: 1.2;
}
.dash-sub {
	font-size: 12.5px;
	opacity: .55;
	margin-bottom: 0;
}
.dash-logo {
	height: 75px;
	border-radius: 10px;
}

/* ── Quick Actions ── */
.dash-actions {
	display: flex;
	gap: 10px;
	flex-wrap: wrap;
}
.dash-action-btn {
	display: inline-flex;
	align-items: center;
	gap: 10px;
	background: #fff;
	border: 1.5px solid #E2E8F0;
	border-radius: 10px;
	padding: 10px 16px;
	cursor: pointer;
	font-size: 13.5px;
	font-weight: 500;
	color: #0F172A;
	text-decoration: none;
	transition: border-color .18s, box-shadow .18s, transform .18s;
	white-space: nowrap;
}
.dash-action-btn:hover {
	border-color: #2563EB;
	color: #2563EB;
	box-shadow: 0 4px 14px rgba(37,99,235,.12);
	transform: translateY(-2px);
	text-decoration: none;
}
.dab-icon {
	display: flex;
	align-items: center;
	justify-content: center;
	width: 30px; height: 30px;
	border-radius: 7px;
	flex-shrink: 0;
}
.dab-icon svg { width: 15px; height: 15px; }

/* ── KPI Grid ── */
.kpi-grid {
	display: grid;
	grid-template-columns: repeat(4, 1fr);
	gap: 14px;
}
.kpi-grid-5 { grid-template-columns: repeat(5, 1fr); }
@media (max-width: 1200px) { .kpi-grid-5 { grid-template-columns: repeat(3, 1fr); } }
@media (max-width: 992px)  { .kpi-grid, .kpi-grid-5 { grid-template-columns: repeat(2, 1fr); } }
@media (max-width: 576px)  { .kpi-grid, .kpi-grid-5 { grid-template-columns: repeat(2, 1fr); } }

.kpi-card-v2 {
	background: #fff;
	border: 1px solid #E2E8F0;
	border-radius: 12px;
	padding: 18px 20px 14px;
	box-shadow: 0 1px 3px rgba(0,0,0,.06);
	display: flex;
	flex-direction: column;
	gap: 12px;
	position: relative;
	overflow: hidden;
	transition: box-shadow .2s, transform .2s;
}
.kpi-card-v2:hover {
	box-shadow: 0 6px 20px rgba(0,0,0,.09);
	transform: translateY(-2px);
}
.kpi-icon-wrap {
	width: 42px; height: 42px;
	border-radius: 10px;
	display: flex;
	align-items: center;
	justify-content: center;
	flex-shrink: 0;
}
.kpi-body {
	display: flex;
	flex-direction: column;
	gap: 2px;
}
.kpi-label-v2 {
	font-size: 11.5px;
	font-weight: 600;
	text-transform: uppercase;
	letter-spacing: .6px;
	color: #64748B;
}
.kpi-value-v2 {
	font-size: 2rem;
	font-weight: 700;
	color: #0F172A;
	line-height: 1.15;
}
.kpi-sub-v2 {
	font-size: 12px;
	color: #94A3B8;
	font-weight: 500;
	margin-top: -2px;
}
.kpi-bar {
	position: absolute;
	bottom: 0; left: 0; right: 0;
	height: 3px;
	border-radius: 0 0 12px 12px;
	opacity: .6;
}

/* ── Chart card labels ── */
.chart-title {
	font-size: 14px;
	font-weight: 600;
	color: #0F172A;
}
.chart-sub {
	font-size: 12px;
	color: #94A3B8;
}
.chart-badge {
	display: inline-flex;
	align-items: center;
	gap: 5px;
	padding: 4px 10px;
	border-radius: 20px;
	font-size: 11.5px;
	font-weight: 600;
	white-space: nowrap;
}
.chart-badge svg { flex-shrink: 0; }
</style>

<!-- ═══════════════════════════════════════════════════
     CHART SCRIPTS
════════════════════════════════════════════════════ -->
<script>
$(function () {
	'use strict';

	/* ── palette ── */
	var blue   = '#2563EB';
	var green  = '#16A34A';
	var amber  = '#D97706';
	var red    = '#DC2626';
	var purple = '#7C3AED';
	var teal   = '#0891B2';
	var border = '#E2E8F0';
	var muted  = '#94A3B8';
	var font   = "'Roboto', sans-serif";

	/* ── Scheduled Hours by Client (ApexCharts bar) ── */
	var hoursData  = [];
	var hoursNames = [];
	var cData = JSON.parse(`<?php echo $scheduledHours; ?>`);
	cData.forEach(function (item) {
		hoursData.push(parseFloat(item.totalHours));
		hoursNames.push(item.name);
	});

	var apexBarChart = null;
	if ($('#apexBar').length) {
		apexBarChart = new ApexCharts(document.querySelector('#apexBar'), {
			chart: { type: 'bar', height: 280, toolbar: { show: false }, fontFamily: font },
			series: [{ name: 'Scheduled Hours', data: hoursData }],
			colors: [blue],
			plotOptions: { bar: { horizontal: true, borderRadius: 4 } },
			xaxis: { categories: hoursNames, labels: { show: true, style: { fontSize: '11px' } } },
			yaxis: { labels: { style: { fontSize: '12px', colors: muted }, formatter: function (v) { return v + 'h'; } } },
			grid: { borderColor: border, strokeDashArray: 4 },
			tooltip: { y: { formatter: function (v) { return v + ' hrs'; } }, style: { fontFamily: font } },
			states: { hover: { filter: { type: 'darken', value: .85 } } }
		});
		apexBarChart.render();
	}

	$('#scheduledMonth, #scheduledYear').on('change', function () {
		if (!apexBarChart) return;
		var m = $('#scheduledMonth').val(), y = $('#scheduledYear').val();
		$.getJSON('<?= admin_url('getDashboardScheduledHoursData/') ?>' + m + '/' + y, function (data) {
			var d = [], n = [];
			data.forEach(function (item) { d.push(parseFloat(item.totalHours)); n.push(item.name); });
			apexBarChart.updateOptions({ series: [{ name: 'Scheduled Hours', data: d }], xaxis: { categories: n } });
		});
	});

	/* ── Invoice Breakdown by Client (ApexCharts grouped bar) ── */
	var invTotal = [], invPaid = [], invDue = [], invNames = [];
	var cDataInvoice = JSON.parse(`<?php echo $invoiceData; ?>`);
	cDataInvoice.forEach(function (item) {
		invTotal.push(parseFloat(item.total));
		invPaid.push(parseFloat(item.paidAmount));
		invDue.push(parseFloat(item.dueAmount));
		invNames.push(item.name);
	});

	var invoiceBarChart = null;
	if ($('#invoiceBar').length) {
		invoiceBarChart = new ApexCharts(document.querySelector('#invoiceBar'), {
			chart: { type: 'bar', height: 280, toolbar: { show: false }, fontFamily: font },
			series: [
				{ name: 'Total', data: invTotal },
				{ name: 'Paid',  data: invPaid  },
				{ name: 'Due',   data: invDue   }
			],
			colors: [blue, green, red],
			plotOptions: {
				bar: { horizontal: false, borderRadius: 4, columnWidth: invNames.length <= 2 ? '25%' : '55%', dataLabels: { position: 'top' } }
			},
			dataLabels: { enabled: false },
			stroke: { show: true, width: 2, colors: ['transparent'] },
			xaxis: { categories: invNames, labels: { style: { fontSize: '12px', colors: muted } }, axisBorder: { color: border } },
			yaxis: { labels: { style: { fontSize: '12px', colors: muted }, formatter: function (v) { return '$' + v; } } },
			legend: { position: 'top', horizontalAlign: 'right', fontFamily: font, fontSize: '12px', markers: { radius: 4 } },
			grid: { borderColor: border, strokeDashArray: 4 },
			tooltip: { y: { formatter: function (v) { return '$' + parseFloat(v).toFixed(2); } }, style: { fontFamily: font } }
		});
		invoiceBarChart.render();
	}

	$('#invoiceBreakMonth, #invoiceBreakYear').on('change', function () {
		if (!invoiceBarChart) return;
		var m = $('#invoiceBreakMonth').val(), y = $('#invoiceBreakYear').val();
		$.getJSON('<?= admin_url('getDashboardInvoiceBreakdownData/') ?>' + m + '/' + y, function (data) {
			var t = [], p = [], d = [], n = [];
			data.forEach(function (item) {
				t.push(parseFloat(item.total)); p.push(parseFloat(item.paidAmount));
				d.push(parseFloat(item.dueAmount)); n.push(item.name);
			});
			invoiceBarChart.updateOptions({
				series: [{ name: 'Total', data: t }, { name: 'Paid', data: p }, { name: 'Due', data: d }],
				xaxis: { categories: n }
			});
		});
	});

	/* ── Invoice Amount vs Paid — Annual (Chart.js) ── */
	var invoicePaidChart = null;
	if ($('#invoicePaid').length) {
		var cDataI = JSON.parse(`<?php echo $data1; ?>`);
		invoicePaidChart = new Chart(document.getElementById('invoicePaid').getContext('2d'), {
			type: 'bar',
			data: {
				labels: cDataI.label,
				datasets: [
					{ type: 'bar',  label: 'Total Invoiced', data: cDataI.totalAmount, backgroundColor: 'rgba(37,99,235,.75)', borderColor: 'rgba(37,99,235,1)', borderWidth: 0, borderRadius: 4, barThickness: 22, order: 1 },
					{ type: 'bar',  label: 'Total Paid',     data: cDataI.paidAmount,   backgroundColor: 'rgba(22,163,74,.75)', borderColor: 'rgba(22,163,74,1)',  borderWidth: 0, borderRadius: 4, barThickness: 22, order: 2 },
					{ type: 'line', label: 'Balance Due',    data: cDataI.totalDue,     borderColor: red, backgroundColor: 'rgba(220,38,38,.08)', borderWidth: 2, pointRadius: 4, pointBackgroundColor: red, fill: true, tension: .3, order: 0 }
				]
			},
			options: {
				responsive: true, maintainAspectRatio: false,
				interaction: { mode: 'index', intersect: false },
				plugins: {
					legend: { display: true, position: 'top', align: 'end', labels: { font: { family: font, size: 12 }, usePointStyle: true, pointStyleWidth: 10 } },
					tooltip: { callbacks: { label: function (ctx) { return ' ' + ctx.dataset.label + ': $' + parseFloat(ctx.raw).toFixed(2); } }, bodyFont: { family: font }, titleFont: { family: font } }
				},
				scales: {
					x: { grid: { display: false }, ticks: { font: { family: font, size: 12 }, color: muted } },
					y: { beginAtZero: true, grid: { color: border, drawBorder: false }, ticks: { font: { family: font, size: 12 }, color: muted, callback: function (v) { return '$' + v; } } }
				}
			}
		});
	}

	$('#invoicePaidYear').on('change', function () {
		if (!invoicePaidChart) return;
		$.getJSON('<?= admin_url('getDashboardAnnualInvoiceData/') ?>' + $(this).val(), function (d) {
			invoicePaidChart.data.labels           = d.label;
			invoicePaidChart.data.datasets[0].data = d.totalAmount;
			invoicePaidChart.data.datasets[1].data = d.paidAmount;
			invoicePaidChart.data.datasets[2].data = d.totalDue;
			invoicePaidChart.update();
		});
	});

	/* ── Payroll Hours vs Invoice Hours — Annual (Chart.js) ── */
	var hoursChart = null;
	if ($('#totalHoursInvoiceHours').length) {
		var cDataH = JSON.parse(`<?php echo $data11; ?>`);
		hoursChart = new Chart(document.getElementById('totalHoursInvoiceHours').getContext('2d'), {
			type: 'bar',
			data: {
				labels: cDataH.label,
				datasets: [
					{ type: 'bar', label: 'Payroll Hours',  data: cDataH.totalHours,        backgroundColor: 'rgba(37,99,235,.75)', borderWidth: 0, borderRadius: 4, barThickness: 22, order: 1 },
					{ type: 'bar', label: 'Invoice Hours',  data: cDataH.totalInvoiceHours,  backgroundColor: 'rgba(124,58,237,.75)', borderWidth: 0, borderRadius: 4, barThickness: 22, order: 2 }
				]
			},
			options: {
				responsive: true, maintainAspectRatio: false,
				interaction: { mode: 'index', intersect: false },
				plugins: {
					legend: { display: true, position: 'top', align: 'end', labels: { font: { family: font, size: 12 }, usePointStyle: true, pointStyleWidth: 10 } },
					tooltip: { callbacks: { label: function (ctx) { return ' ' + ctx.dataset.label + ': ' + ctx.raw + ' hrs'; } }, bodyFont: { family: font }, titleFont: { family: font } }
				},
				scales: {
					x: { grid: { display: false }, ticks: { font: { family: font, size: 12 }, color: muted } },
					y: { beginAtZero: true, grid: { color: border, drawBorder: false }, ticks: { font: { family: font, size: 12 }, color: muted, callback: function (v) { return v + 'h'; } } }
				}
			}
		});
	}

	$('#payrollYear').on('change', function () {
		if (!hoursChart) return;
		$.getJSON('<?= admin_url('getDashboardAnnualHoursData/') ?>' + $(this).val(), function (d) {
			hoursChart.data.labels           = d.label;
			hoursChart.data.datasets[0].data = d.totalHours;
			hoursChart.data.datasets[1].data = d.totalInvoiceHours;
			hoursChart.update();
		});
	});

	/* Auto-refresh KPI values every 5 minutes */
	setTimeout(function () { location.reload(); }, 5 * 60 * 1000);
});
</script>
