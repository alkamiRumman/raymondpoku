<div class="row">
	<div class="col-md-12 grid-margin stretch-card">
		<div class="card">
			<h4 class="card-header d-flex justify-content-between align-items-center">
				Client List
				<div class="btn-group">
					<a href="javascript:void(0);"
					   onclick="loadPopup('<?= admin_url('archivedClient') ?>')"
					   class="btn btn-xs btn-outline-warning me-2">Archived Clients</a>
					<a href="javascript:void(0);"
					   onclick="loadPopup('<?= admin_url('addClient') ?>')"
					   class="btn btn-xs btn-outline-info me-2">Add New</a>
				</div>

			</h4>
			<div class="card-body">
				<table id="reportTable" class="table serverSide-table"
					   style="width: 100% !important;">
					<thead class="bg-info">
					<tr>
						<th class="text-dark">Actions</th>
						<th class="text-dark">Client Name</th>
						<th class="text-dark">Address</th>
						<th class="text-dark">Phone</th>
						<th class="text-dark">DOL</th>
						<th class="text-dark">Referral Source</th>
						<th class="text-dark">Referral Date</th>
						<th class="text-dark">Billing Address</th>
						<th class="text-dark">Company Name</th>
						<th class="text-dark">Adjustor Name</th>
						<th class="text-dark">Adjustor Email</th>
						<th class="text-dark">Adjustor Phone</th>
						<th class="text-dark">Adjustor Fax</th>
						<th class="text-dark">Budget/Form 1</th>
						<th class="text-dark">Bill Rate</th>
						<th class="text-dark">Budgeted Hours</th>
						<th class="text-dark">Total Billed</th>
						<th class="text-dark">Total Paid</th>
						<th class="text-dark">Outstanding Amount</th>
						<th class="text-dark">Create Date</th>
						<th class="text-dark">Last Update</th>
						<th class="text-dark">Status</th>
					</tr>
					</thead>
					<tbody>
					</tbody>
				</table>
			</div>
		</div>
	</div>
</div>
<script>
	var Table = [];
	$(document).ready(function () {
		Table = $('.serverSide-table').DataTable({
			serverSide: true,
			order: [[1, "ASC"]],
			stateSave: true,
			scrollX: true,
			scrollY: '65vh',
			scrollCollapse: true,
			fixedColumns: { leftColumns: 2 },
			"columnDefs": [
				{
					"render": function (data, type, row) {
						if (data > 0) {
							return '$' + data;
						} else {
							return data;
						}
					}, "targets": [13, 14, 16, 17, 18]
				},
				{
					"render": function (data, type, row) {
						if (data) {
							return moment(data).format('DD MMM YYYY');
						} else {
							return '-';
						}
					}, "targets": [6, 19, 20], type: 'date'
				},
				{
					"render": function (data, type, row) {
						return '<a onclick="showSwal(\'passing-parameter-execute-archive\', \'' + '<?php echo base_url('admin/changeClientStatus/'); ?>' + row['id'] + '\')">' +
							'<button class="btn btn-xs btn-success text-white">Active</button></a>';
					},
					"targets": 21
				}
			],
			'aoColumns': [
				{mData: "actions", bSortable: false},
				{mData: "name"}, {mData: "address"}, {mData: "phone"}, {mData: "dol"}, {mData: "referralSource"},
				{mData: "referralDate"}, {mData: "billingAddress"}, {mData: "companyName"}, {mData: "adjustorName"}, {mData: "adjustorEmail"},
				{mData: "adjustorPhone"}, {mData: "adjustorFax"}, {mData: "budget"}, {mData: "billRate"}, {mData: "budgetedHours"},
				{mData: "totalBilled"}, {mData: "totalPaid"}, {mData: "outstanding"}, {mData: "createAt"}, {mData: "updateAt"},
				{mData: "status"}
			],
			"aLengthMenu": [[25, 50, 100, 200, 500, -1], [25, 50, 100, 200, 500, 'All']],
			"iDisplayLength": 25,
			'bProcessing': true,
			"language": {
				processing: '<div><i class="fa fa-spinner fa-spin fa-3x fa-fw"></i><span class="sr-only">Loading Please Wait...</span></div>'
			},
			'bServerSide': true,
			'sAjaxSource': '<?= admin_url('getClients') ?>',
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
			"fnRowCallback": function (nRow, aData, iDisplayIndex, iDisplayIndexFull) {}
		});
	});
</script>
