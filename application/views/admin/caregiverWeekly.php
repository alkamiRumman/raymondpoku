<div class="row">
	<div class="col-md-12 grid-margin stretch-card">
		<div class="card">
			<h4 class="card-header d-flex justify-content-between align-items-center">
				Caregiver Payroll Hours
			</h4>
			<div class="card-body">
				<div class="row">
					<div class="col-md-3 mb-3"></div>
					<div class="col-md-3 mb-3">
						<label for="startDate" class="form-label">Start Date <sup class="text-danger">*</sup></label>
						<div class="input-group flatpickr">
							<input type="text" id="startDate" name="startDate" class="form-control"
								   placeholder="Select Date" data-input required>
							<span class="input-group-text input-group-addon" data-toggle>&#128198;</span>
						</div>
					</div>
					<div class="col-md-3 mb-3">
						<label for="endDate" class="form-label">End Date <sup class="text-danger">*</sup></label>
						<div class="input-group flatpickr">
							<input type="text" id="endDate" name="endDate" class="form-control"
								   placeholder="Select Date" data-input required>
							<span class="input-group-text input-group-addon" data-toggle>&#128198;</span>
						</div>
					</div>
				</div>
				<div class="row">
					<div class="col-md-12 text-center mb-3">
						<button type="button" id="search" class="btn btn-sm btn-info">Search</button>
					</div>
				</div>
				<div class="table-responsive">
					<table id="reportTable" class="table table-bordered serverSide-table"
						   style="width: 100% !important;">
						<thead class="bg-info">
						<tr id="headerRow">
							<th class="text-dark">Client</th>
						</tr>
						</thead>
						<tbody id="tableBody"></tbody>
						<tfoot id="tableFooter">
						</tfoot>
					</table>
				</div>
			</div>
		</div>
	</div>
</div>

<script>
	$(function () {
		$("#startDate, #endDate").flatpickr({
			dateFormat: "d M Y"
		});

		$('#search').on('click', function () {
			var startDate = $('#startDate').val();
			var endDate = $('#endDate').val();
			if (startDate == '' || endDate == '') {
				Toast.fire({
					icon: "warning",
					title: 'Select Date Range First!'
				});
			} else {
				$.ajax({
					type: 'POST',
					url: "<?= admin_url('getCaregiverWeekly') ?>",
					data: {startDate: startDate, endDate: endDate},
					success: function (result) {
						var result = JSON.parse(result);

						var headerRow = $('#headerRow');
						var tableBody = $('#tableBody');

						// Clear existing table content
						headerRow.find('th:gt(0)').remove(); // Remove all headers except the first
						tableBody.empty(); // Clear all rows

						// Create a map for caregivers and their corresponding clients
						var caregiverMap = {};
						result.forEach(function (item) {
							var caregiverName = item.firstName + ' ' + (item.lastName ? item.lastName : '');
							if (!caregiverMap[caregiverName]) {
								caregiverMap[caregiverName] = [];
							}
							caregiverMap[caregiverName].push(item);
						});

						// Add headers for each caregiver
						Object.keys(caregiverMap).forEach(function (caregiverName) {
							var header = '<th class="text-dark">' + caregiverName + '</th>';
							headerRow.append(header);
						});

						// Create unique client list
						var clients = {};
						result.forEach(function (item) {
							if (!clients[item.name]) {
								clients[item.name] = [];
							}
							clients[item.name].push(item);
						});

						// Add rows for each client and calculate total hours
						var totalPayable = {};
						var totalCancelled = {};
						var totalLateCancel = {};
						Object.keys(clients).forEach(function (clientName) {
							var row = '<tr>';
							row += '<td>' + clientName + '</td>';
							Object.keys(caregiverMap).forEach(function (caregiverName, index) {
								var d = clients[clientName].find(function(c) {
									return (c.firstName + ' ' + (c.lastName || '')) === caregiverName;
								});
								var payable    = d ? parseFloat(d.payableHours)    : 0;
								var cancelled  = d ? parseFloat(d.cancelledHours)  : 0;
								var lateCancel = d ? parseFloat(d.lateCancelHours) : 0;
								if (!totalPayable[index])    totalPayable[index]    = 0;
								if (!totalCancelled[index])  totalCancelled[index]  = 0;
								if (!totalLateCancel[index]) totalLateCancel[index] = 0;
								totalPayable[index]    += payable;
								totalCancelled[index]  += cancelled;
								totalLateCancel[index] += lateCancel;
								var cell = payable ? '<strong>' + payable + '</strong>' : '-';
								if (lateCancel > 0) cell += '<br><span class="text-warning" style="font-size:11px">Late Cancel: ' + lateCancel + ' hrs</span>';
								if (cancelled  > 0) cell += '<br><span class="text-muted"  style="font-size:11px">Cancelled: '    + cancelled  + ' hrs</span>';
								row += '<td>' + cell + '</td>';
							});
							row += '</tr>';
							tableBody.append(row);
						});

						// Add total rows
						var totalRow = '<tr class="table-light"><th class="text-end">Total Payable Hrs</th>';
						Object.keys(totalPayable).forEach(function (index) {
							totalRow += '<th>' + (totalPayable[index] || 0) + '</th>';
						});
						totalRow += '</tr>';
						tableBody.append(totalRow);

						if (Object.values(totalLateCancel).some(function(v){ return v > 0; })) {
							var lateRow = '<tr class="table-warning"><th class="text-end" style="font-size:12px">Late Cancellations</th>';
							Object.keys(totalLateCancel).forEach(function (index) {
								lateRow += '<th style="font-size:12px">' + (totalLateCancel[index] || 0) + '</th>';
							});
							lateRow += '</tr>';
							tableBody.append(lateRow);
						}

						if (Object.values(totalCancelled).some(function(v){ return v > 0; })) {
							var cancelRow = '<tr class="table-secondary"><th class="text-end" style="font-size:12px">Cancelled (not paid)</th>';
							Object.keys(totalCancelled).forEach(function (index) {
								cancelRow += '<th style="font-size:12px">' + (totalCancelled[index] || 0) + '</th>';
							});
							cancelRow += '</tr>';
							tableBody.append(cancelRow);
						}
					},
					error: function (result) {
						console.log(result);
					}
				});
			}
		});
	});
</script>
