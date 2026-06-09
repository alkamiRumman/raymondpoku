<div class="row">
	<div class="col-md-12 grid-margin stretch-card">
		<div class="card">
			<h4 class="card-header d-flex justify-content-between align-items-center">
				Invoices
			</h4>
			<div class="card-body">
				<div class="row">
					<div class="col-md-4 mb-3"></div>
					<div class="col-md-2 mb-3">
						<label for="yearSearch">Select Year</label><br>
						<select id="yearSearch" name="yearSearch" class="form-select">
							<?php foreach ($years as $year) { ?>
								<option <?= $year->year == date('Y') ? 'selected' : '' ?>
										value="<?= $year->year ?>"><?= $year->year ?></option>
							<?php } ?>
						</select>
					</div>
					<div class="col-md-2 mb-3">
						<label for="monthSearch">Select Month</label><br>
						<select id="monthSearch" name="monthSearch" class="form-select">
							<option value="All">All</option>
							<option value="1">January</option>
							<option value="2">February</option>
							<option value="3">March</option>
							<option value="4">April</option>
							<option value="5">May</option>
							<option value="6">June</option>
							<option value="7">July</option>
							<option value="8">August</option>
							<option value="9">September</option>
							<option value="10">October</option>
							<option value="11">November</option>
							<option value="12">December</option>
						</select>
					</div>
				</div>
				<table id="reportTable" class="table table-bordered serverSide-table"
					   style="width: 100% !important;">
					<thead class="bg-info">
					<tr id="headerRow">
						<th class="text-dark">Actions</th>
						<th class="text-dark">Invoice Date</th>
						<th class="text-dark">Client Name</th>
						<th class="text-dark">Billing Address</th>
						<th class="text-dark">Phone</th>
						<th class="text-dark">Total Hours</th>
						<th class="text-dark">Total Amount</th>
						<th class="text-dark">Paid Amount</th>
						<th class="text-dark">Due Amount</th>
						<th class="text-dark">Status</th>
					</tr>
					</thead>
					<tbody id="tableBody"></tbody>
				</table>
			</div>
		</div>
	</div>
</div>
<script>
	var Table = [];

	function makeInvoiceColumnDefs() {
		return [
			{
				"render": function (data, type, row) {
					return moment(data).format('DD MMM YYYY');
				}, "targets": 1, type: 'date'
			},
			{
				"render": function (data, type, row) {
					if (data > 0) {
						return '$' + data;
					} else {
						return data;
					}
				}, "targets": [6, 7, 8]
			},
			{
				"render": function (data, type, row) {
					var map = {
						'Sent':         '<span class="badge-status badge-sent">Sent</span>',
						'Partial Paid': '<span class="badge-status badge-partial">Partial Paid</span>',
						'Fully Paid':   '<span class="badge-status badge-paid">Fully Paid</span>'
					};
					return map[data] || '<span class="badge-status badge-archived">' + data + '</span>';
				}, "targets": 9
			}
		];
	}

	function makeInvoiceAoColumns() {
		return [
			{mData: "actions", bSortable: false},
			{mData: "invoiceDate"}, {mData: "name", bSortable: false}, {mData: "billingAddress"}, {mData: "phone"}, {mData: "totalHours"},
			{mData: "total"}, {mData: "paidAmount"}, {mData: "dueAmount"}, {mData: "status"}
		];
	}

	function makeInvoiceTableOptions(ajaxSource) {
		return {
			serverSide: true,
			order: [[1, "DESC"]],
			stateSave: true,
			scrollX: true,
			fixedColumns: { leftColumns: 2 },
			initComplete: function () {
				var state = Table.state.loaded();
				Table.columns().every(function (index) {
					var col = this;
					var title = $('#reportTable thead th').eq(index).text();
					var header = $(col.header());

					if (index === 2) {
						header.html(title + '<br><input type="text" placeholder="Search here..." style="font-weight:normal;" />');
						var input = $('input', header);

						input.on('keyup change', function () {
							col.search(this.value).draw();
						});

						if (state) {
							var colSearch = state.columns[index].search.search;
							if (colSearch) input.val(colSearch);
						}
					} else {
						header.text(title);
					}
				});
			},
			"columnDefs": makeInvoiceColumnDefs(),
			'aoColumns': makeInvoiceAoColumns(),
			"aLengthMenu": [[25, 50, 100, 200, 500, -1], [25, 50, 100, 200, 500, 'All']],
			"iDisplayLength": 25,
			'bProcessing': true,
			"language": {
				processing: '<div><i class="fa fa-spinner fa-spin fa-3x fa-fw"></i><span class="sr-only">Loading Please Wait...</span></div>',
				emptyTable: 'No invoices found.'
			},
			'bServerSide': true,
			'sAjaxSource': ajaxSource,
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
			"fnFooterCallback": function (nRow, aaData, iStart, iEnd, aiDisplay) {},
			"fnRowCallback": function (nRow, aData, iDisplayIndex, iDisplayIndexFull) {},
			buttons: [
				{
					extend: 'colvis',
					text: 'Column Visibility',
					collectionLayout: 'three-column'
				}, {
					text: 'Export',
					className: 'btn btn-success',
					action: function (e, dt, node, config) {
						var headers = [];
						dt.columns().every(function (index) {
							if (index < dt.columns().count() - 1) {
								headers.push(this.header().textContent.trim());
							}
						});

						var data = [];
						dt.rows({search: 'applied'}).every(function (rowIdx, tableLoop, rowLoop) {
							var rowData = [];
							dt.columns().every(function (colIdx) {
								if (colIdx < dt.columns().count() - 1) {
									rowData.push(this.data()[rowIdx]);
								}
							});
							data.push(rowData);
						});
						var nowDate = new Date();
						var nowDay = ((nowDate.getDate().toString().length) == 1) ? '0' + (nowDate.getDate()) : (nowDate.getDate());
						var nowMonth = ((nowDate.getMonth().toString().length) == 1) ? '0' + (nowDate.getMonth() + 1) : (nowDate.getMonth() + 1);
						var nowYear = nowDate.getFullYear();
						var formatDate = nowYear + nowMonth + nowDay;
						var exportData = [headers].concat(data);
						var ws = XLSX.utils.aoa_to_sheet(exportData);
						var wb = XLSX.utils.book_new();
						XLSX.utils.book_append_sheet(wb, ws, 'Sheet1');
						XLSX.writeFile(wb, 'Invoices_' + formatDate + '.xlsx');
					}
				}
			]
		};
	}

	function confirmDeleteInvoice(url) {
		if (confirm('Delete this invoice? This cannot be undone. Services linked to it will be unlinked so they can be re-invoiced.')) {
			window.location.href = url;
		}
	}

	$(document).ready(function () {
		$("#monthSearch, #yearSearch").on('change', function () {
			var month = $('#monthSearch').find(":selected").val();
			var year = $('#yearSearch').find(":selected").val();
			Table.destroy();
			$('#reportTable tr td').remove();
			if (month) {
				Table = $('.serverSide-table').DataTable(
					makeInvoiceTableOptions('<?= admin_url('getCustomInvoice/') ?>' + month + '/' + year)
				);
			}
		});

		Table = $('.serverSide-table').DataTable(
			makeInvoiceTableOptions('<?= admin_url('getInvoice') ?>')
		);
	});
</script>
