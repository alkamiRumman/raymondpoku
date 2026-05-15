<div class="row">
	<div class="col-md-12 grid-margin stretch-card">
		<div class="card">
			<h4 class="card-header d-flex justify-content-between align-items-center">
				Services
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
							<th></th>
							<th class="text-dark">Invoice Number</th>
							<th class="text-dark">Date</th>
							<th class="text-dark">Time</th>
							<th class="text-dark">Caregiver</th>
							<th class="text-dark">Client</th>
							<th class="text-dark">Service Type</th>
							<th class="text-dark">Base Rate</th>
							<th class="text-dark">Bill Rate</th>
							<th class="text-dark">Hours</th>
							<th class="text-dark">Amount</th>
							<th class="text-dark">Bill Amount</th>
							<th class="text-dark">Last Update</th>
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
<div class="modal fade" id="remoteModal1" tabindex="-1" role="dialog" aria-labelledby="remoteModalLabel"
	 aria-hidden="true">
	<div class="modal-dialog modal-lg" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="remoteModalLabel">Generate Invoice</h5>
				<button type="button" class="btn btn-sm btn-danger close" data-dismiss="modal" aria-label="Close">
					Close
				</button>
			</div>
			<form class="forms-sample" action="<?= admin_url('saveGenerateInvoice') ?>" method="post"
				  enctype="multipart/form-data">
				<div class="modal-body">
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-danger close">Close</button>
					<button type="submit" class="btn btn-success me-2">Generate</button>
				</div>
			</form>
		</div>
	</div>
</div>
<script>
	var Table = [];
	$(function () {
		$("#startDate, #endDate").flatpickr({
			dateFormat: "d M Y"
		});

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
					order: [[2, "DESC"]],
					// destroy: true,
					// stateSave: true,
					initComplete: function () {
						var state = Table.state.loaded();
						// Add "Select All" checkbox in the first header cell
						$('#dataTable thead th').eq(0).html('<input type="checkbox" class="form-check-input" id="selectAll">');

						// Restore input values from state and handle other column headers
						Table.columns().every(function (index) {
							var col = this;
							var title = $('#dataTable thead th').eq(index).text();
							var header = $(col.header());

							if ([4, 5, 6].includes(index)) {
								header.html(title + '<br><input type="text" placeholder="Search here..." />');
								var input = $('input', header);

								input.on('keyup change', function () {
									$('#selectAll').prop('checked', false);
									col.search(this.value).draw();
								});

								if (state) {
									var colSearch = state.columns[index].search.search;
									if (colSearch) {
										input.val(colSearch);
									}
								}
							} else if (![0].includes(index)) {
								header.text(title);
							}
						});
					},
					"columnDefs": [
						{orderable: false, targets: [4, 5, 6]},
						{
							"render": function (data, type, row) {
								return '';
							}, "targets": 0, orderable: false, searchable: false
						},
						{
							"render": function (data, type, row) {
								return moment(data).format('DD MMM YYYY');
							}, "targets": 2, type: 'date'
						},
						{
							"render": function (data, type, row) {
								return data + ' - ' + row['endTime'];
							}, "targets": 3,
						},
						{
							"render": function (data, type, row) {
								return data + ' ' + row['lastName'];
							}, "targets": 4,
						},
						{
							"render": function (data, type, row) {
								if (data == 0)
									return data;
								else
									return '$' + data + '</span>';
							}, "targets": [7, 8, 10, 11]
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
					'aoColumns': [{
						mData: "id",
						className: 'select-checkbox',
						searchable: false
					}, {mData: "invoiceNumber"}, {mData: "date"}, {mData: "startTime"}, {mData: "firstName"}, {mData: "name"}, {mData: "serviceType"}, {mData: "rate"}, {mData: "billRate"}, {mData: "hours"},
						{mData: "amount"}, {mData: "billAmount"}, {mData: "updateAt"}],
					"aLengthMenu": [[15, 25, 50, 100, 200, -1], [15, 25, 50, 100, 200, 'All']],
					"iDisplayLength": 25,
					'bProcessing': true,
					"language": {
						processing: '<div><i class="fa fa-spinner fa-spin fa-3x fa-fw"></i><span class="sr-only">Loading Please Wait...</span></div>'
					},
					'bServerSide': true,
					'sAjaxSource': '<?= admin_url('getServicesCustomInvoice/') ?>' + convertDate(startDate) + '/' + convertDate(endDate),
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
					select: {
						style: 'multi'
					},
					// dom: '<"top"B<"pull-right"f>>irtlp',
					// dom: 'lfrtip',
					buttons: [
						{
							extend: 'colvis',
							text: 'Column Visibility',
							collectionLayout: 'two-column'
						},
						{
							text: 'Generate',
							className: 'btn btn-success',
							attr: {
								title: 'Generate Invoice',
								id: 'generateInvoice'
							},
							action: function (e, dt, node, config) {
								var selectedRows = Table.rows({selected: true}).data().toArray();

								function checkNamesSimilar(data) {
									var name = data[0].name;
									for (var i = 1; i < data.length; i++) {
										if (data[i].name !== name) {
											return true;
										}
									}
									return false;
								}

								if (checkNamesSimilar(selectedRows)) {
									Toast.fire({
										icon: "warning",
										title: 'Please select same client to generate an invoice.'
									});
									return;
								}
								if (selectedRows.length === 0) {
									Toast.fire({
										icon: "warning",
										title: 'Please select at least one row to generate an invoice.'
									});
									return;
								}
								var url = '<?= admin_url('generate_invoice') ?>';
								$.ajax({
									type: 'POST',
									url: url,
									data: {selectedRows: selectedRows},
									// dataType: 'json',
									dataType: 'html',
									success: function (response) {
										console.log(response);
										$('#remoteModal1 .modal-body').html(response);
										$('#remoteModal1').modal('show');
									},
									error: function (xhr, status, error) {
										Toast.fire({
											icon: "warning",
											title: error
										});
									}
								});
							}
						}
					]
				});
			}
		});

		Table = $('#dataTable').DataTable({
			serverSide: true,
			order: [[2, "DESC"]],
			// destroy: true,
			// stateSave: true,
			initComplete: function () {
				var state = Table.state.loaded();
				// Add "Select All" checkbox in the first header cell
				$('#dataTable thead th').eq(0).html('<input type="checkbox" class="form-check-input" id="selectAll">');

				// Restore input values from state and handle other column headers
				Table.columns().every(function (index) {
					var col = this;
					var title = $('#dataTable thead th').eq(index).text();
					var header = $(col.header());

					if ([4, 5, 6].includes(index)) {
						header.html(title + '<br><input type="text" placeholder="Search here..." />');
						var input = $('input', header);

						input.on('keyup change', function () {
							$('#selectAll').prop('checked', false);
							col.search(this.value).draw();
						});

						if (state) {
							var colSearch = state.columns[index].search.search;
							if (colSearch) {
								input.val(colSearch);
							}
						}
					} else if (![0].includes(index)) {
						header.text(title);
					}
				});
			},
			"columnDefs": [
				{orderable: false, targets: [4, 5, 6]},
				{
					"render": function (data, type, row) {
						return '';
					}, "targets": 0, orderable: false, searchable: false
				},
				{
					"render": function (data, type, row) {
						return moment(data).format('DD MMM YYYY');
					}, "targets": 2, type: 'date'
				},
				{
					"render": function (data, type, row) {
						return data + ' - ' + row['endTime'];
					}, "targets": 3,
				},
				{
					"render": function (data, type, row) {
						return data + ' ' + row['lastName'];
					}, "targets": 4,
				},
				{
					"render": function (data, type, row) {
						if (data == 0)
							return data;
						else
							return '$' + data + '</span>';
					}, "targets": [7, 8, 10, 11]
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
			'aoColumns': [{
				mData: "id",
				className: 'select-checkbox',
				searchable: false
			}, {mData: "invoiceNumber"}, {mData: "date"}, {mData: "startTime"}, {mData: "firstName"}, {mData: "name"}, {mData: "serviceType"}, {mData: "rate"}, {mData: "billRate"}, {mData: "hours"},
				{mData: "amount"}, {mData: "billAmount"}, {mData: "updateAt"}],
			"aLengthMenu": [[15, 25, 50, 100, 200, -1], [15, 25, 50, 100, 200, 'All']],
			"iDisplayLength": 25,
			'bProcessing': true,
			"language": {
				processing: '<div><i class="fa fa-spinner fa-spin fa-3x fa-fw"></i><span class="sr-only">Loading Please Wait...</span></div>'
			},
			'bServerSide': true,
			'sAjaxSource': '<?= admin_url('getServicesInvoice') ?>',
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
			select: {
				style: 'multi'
			},
			// dom: '<"top"B<"pull-right"f>>irtlp',
			// dom: 'lfrtip',
			buttons: [
				{
					extend: 'colvis',
					text: 'Column Visibility',
					collectionLayout: 'two-column'
				},
				{
					text: 'Generate',
					className: 'btn btn-success',
					attr: {
						title: 'Generate Invoice',
						id: 'generateInvoice'
					},
					action: function (e, dt, node, config) {
						var selectedRows = Table.rows({selected: true}).data().toArray();
						console.log(selectedRows);

						function checkNamesSimilar(data) {
							var name = data[0].name;
							for (var i = 1; i < data.length; i++) {
								if (data[i].name !== name) {
									return true;
								}
							}
							return false;
						}

						if (checkNamesSimilar(selectedRows)) {
							Toast.fire({
								icon: "warning",
								title: 'Please select same client to generate an invoice.'
							});
							return;
						}
						if (selectedRows.length === 0) {
							Toast.fire({
								icon: "warning",
								title: 'Please select at least one row to generate an invoice.'
							});
							return;
						}
						var url = '<?= admin_url('generate_invoice') ?>';
						$.ajax({
							type: 'POST',
							url: url,
							data: {selectedRows: selectedRows},
							// dataType: 'json',
							dataType: 'html',
							success: function (response) {
								console.log(response);
								$('#remoteModal1 .modal-body').html(response);
								$('#remoteModal1').modal('show');
							},
							error: function (xhr, status, error) {
								Toast.fire({
									icon: "warning",
									title: error
								});
							}
						});
					}
				}
			]
		});
		Table.on('search.dt', function () {
			$('#selectAll').prop('checked', false);
		});

		Table.on('page.dt', function () {
			$('#selectAll').prop('checked', false);
		});

		Table.on('select', function (e, dt, type, indexes) {
			if (type === 'row') {
				var rows = Table.rows(indexes).nodes().to$();
				$.each(rows, function () {
					var rowData = Table.row($(this)).data();
					if (rowData.invoiceNumber != null)
						Table.row($(this)).deselect();
				})
			}
		});
	});

	$('#dataTable thead').on('click', '#selectAll', function () {
		if ($(this).is(":checked")) {
			Table.rows({
				page: 'current'
			}).select();
		} else {
			Table.rows({
				page: 'current'
			}).deselect();
		}
	});
</script>
