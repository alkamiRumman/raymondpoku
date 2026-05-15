<div class="modal" id="modal-default" style="display: block;overflow: auto;">
	<div class="modal-dialog modal-xl">
		<div class="modal-content">
			<div class="modal-header">
				<h4 class="modal-title"><b>Archived Clients</b></h4>
				<button type="button" class="btn btn-danger float-end close">Close
				</button>
			</div>
			<div class="modal-body">
				<div class="table-responsive">
					<table id="reportTable" class="table serverSide-table1"
						   style="width: 100% !important;">
						<thead class="bg-info">
						<tr>
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
</div>
<script>
	$('.close').on('click', function () {
		$("#remoteModal1").modal('hide');
	});
	var Table = [];
	$(document).ready(function () {
		Table = $('.serverSide-table1').DataTable({
			serverSide: true,
			order: [[0, "ASC"]],
			// destroy: true,
			stateSave: true,
			"columnDefs": [
				{
					"render": function (data, type, row) {
						if (data > 0) {
							return '$' + data;
						} else {
							return data;
						}

					}, "targets": [12, 13, 17, 15, 16]
				},
				{
					"render": function (data, type, row) {
						if (data) {
							return moment(data).format('DD MMM YYYY');
						} else {
							return '-';
						}

					}, "targets": [5, 18, 19], type: 'date'
				},
				{
					"render": function (data, type, row) {
						return '<a onclick="showSwal(\'passing-parameter-execute-active\', \'' + '<?php echo base_url('admin/changeClientStatusActive/'); ?>' + row['id'] + '\')">' +
							'<button class="btn btn-xs btn-success text-white">Active</button></a>';
					},
					"targets": 20
				}
			],
			'aoColumns': [{mData: "name"}, {mData: "address"}, {mData: "phone"}, {mData: "dol"}, {mData: "referralSource"},
				{mData: "referralDate"}, {mData: "billingAddress"}, {mData: "companyName"}, {mData: "adjustorName"}, {mData: "adjustorEmail"},
				{mData: "adjustorPhone"}, {mData: "adjustorFax"}, {mData: "budget"}, {mData: "billRate"}, {mData: "budgetedHours"},
				{mData: "totalBilled"}, {mData: "totalPaid"}, {mData: "outstanding"}, {mData: "createAt"}, {mData: "updateAt"},
				{mData: "status"}],
			"aLengthMenu": [[25, 50, 100, 200, 500, -1], [25, 50, 100, 200, 500, 'All']],
			"iDisplayLength": 25,
			'bProcessing': true,
			"language": {
				processing: '<div><i class="fa fa-spinner fa-spin fa-3x fa-fw"></i><span class="sr-only">Loading Please Wait...</span></div>'
			},
			'bServerSide': true,
			'sAjaxSource': '<?= admin_url('getArchivedClients') ?>',
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

			}
		});
	});
</script>
