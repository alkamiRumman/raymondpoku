<div class="row">
	<div class="col-md-12 grid-margin stretch-card">
		<div class="card">
			<h4 class="card-header d-flex justify-content-between align-items-center">
				Trades
				<a href="javascript:void(0);"
				   onclick="loadPopup('<?= admin_url('add') ?>')"
				   class="btn btn-sm btn-outline-info me-2">Add Trade</a>
			</h4>
			<div class="card-body">
				<div class="table-responsive">
					<table id="dataTable" class="table">
						<thead>
						<tr>
							<th>Date</th>
							<th>Ticker</th>
							<th>Trade Side</th>
							<th>Contracts</th>
							<th>Entry Contract Price</th>
							<th>Trade Entry Cost</th>
							<th>Exit Contract Price</th>
							<th>Trade Exit Cost</th>
							<th>Total Profit/Loss</th>
							<th>Total Profit/Loss (%)</th>
							<th>Last Update</th>
							<th>Actions</th>
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
		var Table = [];
		Table = $('#dataTable').DataTable({
			serverSide: true,
			order: [[0, "DESC"]],
			// destroy: true,
			stateSave: true,
			"columnDefs": [
				{
					"render": function (data, type, row) {
						return moment(data).format('DD MMM YYYY');
					}, "targets": 0, type: 'date'
				},
				{
					"render": function (data, type, row) {
						if (data >= 0)
							return '$' + data;
						else
							return '<span class="text-danger fw-bold">$' + data + '</span>';
					}, "targets": [4, 5, 6, 7, 8]
				},
				{
					"render": function (data, type, row) {
						if (data >= 0)
							return data + '%';
						else
							return '<span class="text-danger fw-bold">' + data + '%</span>';
					}, "targets": 9
				},
				{
					"render": function (data, type, row) {
						if (data) {
							return moment(data).format('DD MMM YYYY hh:mm:ss A');
						} else {
							return '--';
						}
					}, "targets": 10, type: 'date'
				}
			],
			'aoColumns': [{mData: "entryDate"}, {mData: "ticker"}, {mData: "side"}, {mData: "contracts"}, {mData: "price"},
				{mData: "subTotal"}, {mData: "sold"}, {mData: "amount"}, {mData: "total"}, {mData: "percentage"},
				{mData: "updateAt"}, {mData: "actions", bSortable: false}],
			"aLengthMenu": [[15, 25, 50, 100, 200, -1], [15, 25, 50, 100, 200, 'All']],
			"iDisplayLength": 15,
			'bProcessing': true,
			"language": {
				processing: '<div><i class="fa fa-spinner fa-spin fa-3x fa-fw"></i><span class="sr-only">Loading Please Wait...</span></div>'
			},
			'bServerSide': true,
			'sAjaxSource': '<?= admin_url('getTrades') ?>',
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
				'copy', {
					extend: 'csv',
					title: 'Trade List - ' + moment(new Date()).format("DD MMM YYYY"),
					exportOptions: {
						columns: ':visible:not(:last-child)'
					}
				}, {
					extend: 'excel',
					title: 'Trade List - ' + moment(new Date()).format("DD MMM YYYY"),
					exportOptions: {
						columns: ':visible:not(:last-child)'
					}
				}, {
					extend: 'colvis',
					text: 'Column Visibility',
					collectionLayout: 'two-column'
				}
			]
		});
	});
	$('#dataTable tbody').on('click', 'tr td:not(:last-child)', function () {
		var data = $('#dataTable').DataTable().row(this).data();
		console.log(data);
		loadPopup('<?= admin_url('view/')?>' + data.id);
	});
</script>
