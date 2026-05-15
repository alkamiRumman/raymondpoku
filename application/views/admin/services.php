<div class="row">
	<div class="col-md-12 grid-margin stretch-card">
		<div class="card">
			<h4 class="card-header d-flex justify-content-between align-items-center">
				Services
				<a href="javascript:void(0);"
				   onclick="loadPopup('<?= admin_url('addService') ?>')"
				   class="btn btn-xs btn-outline-info me-2">Add Service</a>
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
					<table id="dataTable" class="table">
						<thead class="bg-info">
						<tr>
							<th class="text-dark">Date</th>
							<th class="text-dark">Invoice Number</th>
							<th class="text-dark">Time</th>
							<th class="text-dark">Caregiver</th>
							<th class="text-dark">Client</th>
							<th class="text-dark">Service Type</th>
							<th class="text-dark">Employee Rate</th>
							<th class="text-dark">Bill Rate</th>
							<th class="text-dark">Hours</th>
							<th class="text-dark">Amount</th>
							<th class="text-dark">Bill Amount</th>
							<th class="text-dark">Comments</th>
							<th class="text-dark">Last Update</th>
							<th class="text-dark">Actions</th>
						</tr>
						</thead>
						<tbody>
						</tbody>
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
		var Table = [];

		function convertDate(inputDate) {
			const monthNames = ["Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"];
			let dateParts = inputDate.split(' ');

			let day = dateParts[0];
			let month = monthNames.indexOf(dateParts[1]);
			let year = dateParts[2];

			month = month < 9 ? '0' + (month + 1) : (month + 1);
			day = day.length < 2 ? '0' + day : day;
			let formattedDate = `${year}-${month}-${day}`;

			return formattedDate;
		}

		$('#search').on('click', function () {
			var startDate = $('#startDate').val();
			var endDate = $('#endDate').val();
			if (startDate == '' || endDate == '') {
				Toast.fire({
					icon: "warning",
					title: 'Select Date Range First!'
				});
			} else if (new Date(startDate) > new Date(endDate)) {
				Toast.fire({
					icon: "warning",
					title: 'End date can not be past date!'
				});
			} else {
				if ($.isEmptyObject(Table) == false) {
					Table.destroy();
					$('#dataTable tr td').remove();
				}
				Table = $('#dataTable').DataTable({
					serverSide: true,
					order: [[0, "DESC"]],
					// destroy: true,
					stateSave: true,
					initComplete: function () {
						var state = Table.state.loaded();
						// Restore input values from state
						Table.columns().every(function (index) {
							var col = this;
							var title = $('#dataTable thead th').eq(index).text();
							var header = $(col.header());

							if ([3, 4].includes(index)) {
								header.html(title + '<br><input type="text" placeholder="Search here..." />');
								var input = $('input', header);

								input.on('keyup change', function () {
									col.search(this.value).draw();
								});

								if (state) {
									var colSearch = state.columns[index].search.search;
									if (colSearch) {
										input.val(colSearch);
									}
								}
							} else {
								header.text(title);
							}
						});
					},
					"columnDefs": [
						{orderable: false, targets: [3, 4]},
						{
							"render": function (data, type, row) {
								return moment(data).format('DD MMM YYYY');
							}, "targets": 0, type: 'date'
						},
						{
							"render": function (data, type, row) {
								return data + ' - ' + row['endTime'];
							}, "targets": 2,
						},
						{
							"render": function (data, type, row) {
								return data + ' ' + row['lastName'];
							}, "targets": 3,
						},
						{
							"render": function (data, type, row) {
								if (data == 0)
									return data;
								else
									return '$' + data + '</span>';
							}, "targets": [6, 7, 9, 10]
						},
						{
							"render": function (data, type, row) {
								if (data) {
									return moment(data).format('DD MMM YYYY hh:mm:ss A');
								} else {
									return '--';
								}
							}, "targets": 12, type: 'date'
						}
					],
					'aoColumns': [{mData: "date"}, {mData: "invoiceNumber"}, {mData: "startTime"}, {mData: "firstName"}, {mData: "name"}, {mData: "serviceType"}, {mData: "rate"}, {mData: "billRate"}, {mData: "hours"},
						{mData: "amount"}, {mData: "billAmount"}, {mData: "comments"}, {mData: "updateAt"}, {
							mData: "actions",
							bSortable: false
						}],
					"aLengthMenu": [[15, 25, 50, 100, 200, -1], [15, 25, 50, 100, 200, 'All']],
					"iDisplayLength": 15,
					'bProcessing': true,
					"language": {
						processing: '<div><i class="fa fa-spinner fa-spin fa-3x fa-fw"></i><span class="sr-only">Loading Please Wait...</span></div>'
					},
					'bServerSide': true,
					'sAjaxSource': '<?= admin_url('getServicesCustom/') ?>' + convertDate(startDate) + '/' + convertDate(endDate),
					'fnServerData': function (sSource, aoData, fnCallback) {
						$.ajax({
							'dataType': 'json',
							'type': 'POST',
							'url': sSource,
							'data': aoData,
							'success': function (d, e, f) {
								console.log(d);
								fnCallback(d, e, f);
							},
							error: function (jqXHR, textStatus, errorThrown) {
								console.log(jqXHR);
								if (jqXHR.jqXHRstatusText)
									alert(jqXHR.jqXHRstatusText);
							}
						});
					},
					"fnFooterCallback": function (nRow, aaData, iStart, iEnd, aiDisplay) {
						// console.log(nRow);
					},
					"fnRowCallback": function (nRow, aData, iDisplayIndex, iDisplayIndexFull) {

					},
					// dom: '<"top"B<"pull-right"f>>irtlp',
					// dom: 'lfrtip',
					buttons: [
						{
							extend: 'colvis',
							text: 'Column Visibility',
							collectionLayout: 'two-column'
						}
					]
				});
			}
		});

		Table = $('#dataTable').DataTable({
			serverSide: true,
			order: [[0, "DESC"]],
			// destroy: true,
			stateSave: true,
			initComplete: function () {
				var state = Table.state.loaded();
				// Restore input values from state
				Table.columns().every(function (index) {
					var col = this;
					var title = $('#dataTable thead th').eq(index).text();
					var header = $(col.header());

					if ([3, 4].includes(index)) {
						header.html(title + '<br><input type="text" placeholder="Search here..." />');
						var input = $('input', header);

						input.on('keyup change', function () {
							col.search(this.value).draw();
						});

						if (state) {
							var colSearch = state.columns[index].search.search;
							if (colSearch) {
								input.val(colSearch);
							}
						}
					} else {
						header.text(title);
					}
				});
			},
			"columnDefs": [
				{orderable: false, targets: [3, 4]},
				{
					"render": function (data, type, row) {
						return moment(data).format('DD MMM YYYY');
					}, "targets": 0, type: 'date'
				},
				{
					"render": function (data, type, row) {
						return data + ' - ' + row['endTime'];
					}, "targets": 2,
				},
				{
					"render": function (data, type, row) {
						return data + ' ' + row['lastName'];
					}, "targets": 3,
				},
				{
					"render": function (data, type, row) {
						if (data == 0)
							return data;
						else
							return '$' + data + '</span>';
					}, "targets": [6, 7, 10, 9]
				},
				{
					"render": function (data, type, row) {
						if (data) {
							return moment(data).format('DD MMM YYYY hh:mm:ss A');
						} else {
							return '--';
						}
					}, "targets": 12, type: 'date'
				}
			],
			'aoColumns': [{mData: "date"}, {mData: "invoiceNumber"}, {mData: "startTime"}, {mData: "firstName"}, {mData: "name"}, {mData: "serviceType"}, {mData: "rate"}, {mData: "billRate"}, {mData: "hours"},
				{mData: "amount"}, {mData: "billAmount"}, {mData: "comments"}, {mData: "updateAt"}, {
					mData: "actions",
					bSortable: false
				}],
			"aLengthMenu": [[15, 25, 50, 100, 200, -1], [15, 25, 50, 100, 200, 'All']],
			"iDisplayLength": 15,
			'bProcessing': true,
			"language": {
				processing: '<div><i class="fa fa-spinner fa-spin fa-3x fa-fw"></i><span class="sr-only">Loading Please Wait...</span></div>'
			},
			'bServerSide': true,
			'sAjaxSource': '<?= admin_url('getServices') ?>',
			'fnServerData': function (sSource, aoData, fnCallback) {
				$.ajax({
					'dataType': 'json',
					'type': 'POST',
					'url': sSource,
					'data': aoData,
					'success': function (d, e, f) {
						console.log(d);
						fnCallback(d, e, f);
					},
					error: function (jqXHR, textStatus, errorThrown) {
						console.log(jqXHR);
						if (jqXHR.jqXHRstatusText)
							alert(jqXHR.jqXHRstatusText);
					}
				});
			},
			"fnFooterCallback": function (nRow, aaData, iStart, iEnd, aiDisplay) {
				// console.log(nRow);
			},
			"fnRowCallback": function (nRow, aData, iDisplayIndex, iDisplayIndexFull) {

			},
			// dom: '<"top"B<"pull-right"f>>irtlp',
			// dom: 'lfrtip',
			buttons: [
				{
					extend: 'colvis',
					text: 'Column Visibility',
					collectionLayout: 'two-column'
				}
			]
		});

		Table.columns().eq(0).each(function (colIdx) {
			$('input', Table.column(colIdx).header()).on('keyup change', function () {
				Table
					.column(colIdx)
					.search(this.value)
					.draw();
			});
		});
	});
</script>
