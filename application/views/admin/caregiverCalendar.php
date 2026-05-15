<div class="card mb-4">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="mb-0 fw-semibold">Caregiver Monthly Calendar</h5>
        <button id="printCalBtn" class="btn btn-primary btn-sm no-print" style="display:none!important">
            <i data-feather="printer" style="width:14px;height:14px" class="me-1"></i> Print Calendar
        </button>
    </div>
    <div class="card-body">
        <div class="row g-3 align-items-end">
            <div class="col-md-4">
                <label class="form-label fw-semibold">Caregiver <sup class="text-danger">*</sup></label>
                <select id="caregiverId" class="form-select">
                    <option value="">— Select caregiver —</option>
                    <?php foreach ($caregivers as $cg): ?>
                        <option value="<?= $cg->id ?>"><?= htmlspecialchars($cg->firstName . ' ' . $cg->lastName) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="col-md-3">
                <label class="form-label fw-semibold">Month</label>
                <select id="calMonth" class="form-select">
                    <?php
                    $months = ["January","February","March","April","May","June",
                               "July","August","September","October","November","December"];
                    $curM = (int)date('n');
                    foreach ($months as $i => $m):
                    ?>
                    <option value="<?= $i+1 ?>" <?= ($i+1 === $curM) ? 'selected' : '' ?>><?= $m ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="col-md-2">
                <label class="form-label fw-semibold">Year</label>
                <select id="calYear" class="form-select">
                    <?php for ($y = (int)date('Y') - 2; $y <= (int)date('Y') + 1; $y++): ?>
                    <option value="<?= $y ?>" <?= ($y === (int)date('Y')) ? 'selected' : '' ?>><?= $y ?></option>
                    <?php endfor; ?>
                </select>
            </div>
            <div class="col-md-3">
                <button id="loadCalBtn" class="btn btn-primary w-100">
                    <i data-feather="search" style="width:14px;height:14px" class="me-1"></i> Load Calendar
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Output area -->
<div id="calendarOutput" style="display:none">
    <div class="d-flex justify-content-between align-items-center mb-3 no-print">
        <h5 class="mb-0 fw-semibold" id="calendarHeading"></h5>
        <button onclick="doPrint()" class="btn btn-primary btn-sm">
            <i data-feather="printer" style="width:14px;height:14px" class="me-1"></i> Print
        </button>
    </div>

    <!-- Service type legend -->
    <div class="d-flex flex-wrap gap-2 mb-3 no-print">
        <span style="background:#F38938;color:#fff;padding:3px 10px;border-radius:20px;font-size:12px;font-weight:600">Attendant Care</span>
        <span style="background:#4ade80;color:#166534;padding:3px 10px;border-radius:20px;font-size:12px;font-weight:600">Housekeeping</span>
        <span style="background:#c4b5fd;color:#4c1d95;padding:3px 10px;border-radius:20px;font-size:12px;font-weight:600">Wound Care</span>
        <span style="background:#fde047;color:#713f12;padding:3px 10px;border-radius:20px;font-size:12px;font-weight:600">Travel/Transport</span>
        <span style="background:#f0abfc;color:#701a75;padding:3px 10px;border-radius:20px;font-size:12px;font-weight:600">Respite</span>
    </div>

    <div id="printContent">
        <!-- Print header (hidden on screen) -->
        <div class="print-only mb-4" style="display:none">
            <div class="d-flex justify-content-between align-items-start">
                <div>
                    <img src="<?= base_url('assets/images/logo.png') ?>" height="60" alt="<?= COMPANY ?>">
                </div>
                <div class="text-end">
                    <h4 class="mb-0 fw-bold"><?= COMPANY ?></h4>
                    <p class="mb-0" style="font-size:12px;color:#64748B">Caregiver Monthly Calendar</p>
                </div>
            </div>
            <hr style="border-color:#e2e8f0;margin:12px 0">
            <h5 id="printHeading" class="fw-bold mb-1"></h5>
            <p id="printSubHeading" style="font-size:13px;color:#64748B;margin:0"></p>
            <div style="margin-top:8px" id="printLegend"></div>
            <hr style="border-color:#e2e8f0;margin:12px 0 16px">
        </div>

        <div class="table-responsive">
            <table class="table table-bordered" id="caregiverCalTable" style="font-size:13px">
                <thead id="cgCalThead"></thead>
                <tbody id="cgCalBody"></tbody>
            </table>
        </div>
    </div>
</div>

<div id="calendarEmpty" class="text-center py-5" style="display:none">
    <i data-feather="calendar" style="width:48px;height:48px;color:#CBD5E1" class="mb-3 d-block mx-auto"></i>
    <p class="text-muted mb-0">No services found for this caregiver in the selected month.</p>
</div>

<script>
var serviceTypeColors = {
    'Attendant Care':   { bg:'#FFF4EB', border:'#F38938', text:'#9a4500' },
    'Housekeeping':     { bg:'#F0FFF4', border:'#4ade80', text:'#166534' },
    'Wound Care':       { bg:'#F5F3FF', border:'#c4b5fd', text:'#4c1d95' },
    'Travel/Transport': { bg:'#FEFCE8', border:'#fde047', text:'#713f12' },
    'Respite':          { bg:'#FDF4FF', border:'#f0abfc', text:'#701a75' },
};

function formatTime(t) {
    if (!t) return '';
    var p = t.split(':');
    var h = parseInt(p[0], 10);
    var m = p[1];
    var s = h >= 12 ? 'pm' : 'am';
    h = h % 12 || 12;
    return h + ':' + m + s;
}

var monthNames = ["January","February","March","April","May","June",
                  "July","August","September","October","November","December"];

function getDaysInMonth(year, month) {
    return new Date(year, month, 0).getDate(); // month is 1-based here
}
function getDayName(year, month, day) {
    var d = new Date(year, month-1, day);
    return ['Sun','Mon','Tue','Wed','Thu','Fri','Sat'][d.getDay()];
}

$('#loadCalBtn').on('click', function () {
    var caregiverId = $('#caregiverId').val();
    var month       = $('#calMonth').val();
    var year        = $('#calYear').val();
    if (!caregiverId) {
        Toast.fire({ icon:'warning', title:'Please select a caregiver.' });
        return;
    }
    var btn = $(this);
    btn.prop('disabled', true).html('<span class="spinner-border spinner-border-sm me-1"></span> Loading…');

    $.ajax({
        url:      '<?= admin_url('getCaregiverCalendarServices/') ?>' + caregiverId + '/' + month + '/' + year,
        type:     'GET',
        dataType: 'json',
        success:  function (data) {
            renderCalendar(data, month, year, caregiverId);
        },
        error:    function () { Toast.fire({ icon:'error', title:'Failed to load services.' }); },
        complete: function () { btn.prop('disabled', false).html('<i data-feather="search" style="width:14px;height:14px" class="me-1"></i> Load Calendar'); feather.replace(); }
    });
});

function renderCalendar(data, month, year, caregiverId) {
    var cgName = $('#caregiverId option:selected').text();
    var heading = cgName + ' — ' + monthNames[month-1] + ' ' + year;

    $('#calendarHeading').text(heading);
    $('#printHeading').text(heading);
    $('#printSubHeading').text('Generated: ' + new Date().toLocaleDateString('en-CA', {year:'numeric',month:'long',day:'numeric'}));

    // Build legend for print
    var legendHtml = '';
    Object.keys(serviceTypeColors).forEach(function(k){
        var c = serviceTypeColors[k];
        legendHtml += '<span style="background:'+c.bg+';border:1.5px solid '+c.border+';color:'+c.text+';padding:2px 8px;border-radius:10px;font-size:11px;font-weight:600;margin-right:6px">'+k+'</span>';
    });
    $('#printLegend').html(legendHtml);

    if (!data || data.length === 0) {
        $('#calendarOutput').hide();
        $('#calendarEmpty').show();
        return;
    }

    $('#calendarEmpty').hide();
    $('#calendarOutput').show();

    // Group services by date
    var byDate = {};
    data.forEach(function (s) {
        if (!byDate[s.date]) byDate[s.date] = [];
        byDate[s.date].push(s);
    });

    var days = getDaysInMonth(year, month);
    var thead = '<tr><th style="width:110px">Date</th><th>Client</th><th>Service Type</th><th>Time</th><th>Hours</th></tr>';
    var tbody = '';
    var totalHours = 0;
    var rowCount = 0;

    for (var d = 1; d <= days; d++) {
        var dateStr = year + '-' + String(month).padStart(2,'0') + '-' + String(d).padStart(2,'0');
        var dayName = getDayName(year, month, d);
        var isWeekend = (dayName === 'Sat' || dayName === 'Sun');

        if (byDate[dateStr] && byDate[dateStr].length > 0) {
            byDate[dateStr].forEach(function (s, idx) {
                var c = serviceTypeColors[s.serviceType] || {bg:'#f8fafc',border:'#cbd5e1',text:'#334155'};
                totalHours += parseFloat(s.hours) || 0;
                rowCount++;
                tbody += '<tr style="'+(isWeekend ? 'background:#fafafa' : '')+'">';
                if (idx === 0) {
                    tbody += '<td rowspan="'+byDate[dateStr].length+'" style="font-weight:600;white-space:nowrap;vertical-align:middle;'+(isWeekend?'color:#94a3b8':'')+'">'
                           + dayName + ', ' + monthNames[month-1].substring(0,3) + ' ' + d + '</td>';
                }
                tbody += '<td style="vertical-align:middle">' + s.clientName + '</td>';
                tbody += '<td style="vertical-align:middle"><span style="background:'+c.bg+';border:1.5px solid '+c.border+';color:'+c.text+';padding:2px 8px;border-radius:10px;font-size:11.5px;font-weight:600">' + s.serviceType + '</span></td>';
                tbody += '<td style="vertical-align:middle;white-space:nowrap">' + formatTime(s.startTime) + ' – ' + formatTime(s.endTime) + '</td>';
                tbody += '<td style="vertical-align:middle">' + s.hours + '</td>';
                tbody += '</tr>';
            });
        }
    }

    // Summary footer
    tbody += '<tr style="background:#f1f5f9;font-weight:700">'
           + '<td colspan="4" class="text-end">Total Hours</td>'
           + '<td>' + totalHours.toFixed(2) + '</td></tr>';

    tbody += '<tr style="background:#f1f5f9;font-weight:700">'
           + '<td colspan="4" class="text-end">Total Service Days</td>'
           + '<td>' + Object.keys(byDate).length + '</td></tr>';

    $('#cgCalThead').html(thead);
    $('#cgCalBody').html(tbody);

    if (window.feather) feather.replace();
}

function doPrint() {
    var content = document.getElementById('printContent').innerHTML;
    var caregiverName = $('#caregiverId option:selected').text();
    var monthLabel    = $('#calMonth option:selected').text() + ' ' + $('#calYear').val();

    var win = window.open('', '_blank');
    win.document.write(`<!DOCTYPE html>
<html>
<head>
<title>Calendar — ` + caregiverName + ` ` + monthLabel + `</title>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
<style>
  body{font-family:'Roboto',sans-serif;font-size:12px;color:#0f172a;padding:20px}
  .no-print{display:none!important}
  .print-only{display:block!important}
  table{width:100%;border-collapse:collapse}
  th,td{padding:6px 10px;border:1px solid #e2e8f0;vertical-align:middle}
  thead th{background:#f1f5f9;font-size:11px;font-weight:700;text-transform:uppercase;letter-spacing:.4px;color:#64748b}
  .text-end{text-align:right}
  @media print{
    @page{margin:1.5cm;size:landscape}
    tr{page-break-inside:avoid}
    body{padding:0}
  }
</style>
</head>
<body onload="window.print();setTimeout(function(){window.close();},200)">
` + content + `
</body>
</html>`);
    win.document.close();
}
</script>
