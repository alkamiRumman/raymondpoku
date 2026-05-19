<div class="modal" id="modal-default" style="display: block;overflow: auto;">
	<div class="modal-dialog modal-fullscreen">
		<div class="modal-content">
			<div class="modal-header">
				<h4 class="modal-title"><b>Archived Caregivers</b></h4>
				<button type="button" class="btn btn-danger float-end close">Close
				</button>
			</div>
			<div class="modal-body">
				<div class="table-responsive">
					<table id="reportTable1" class="table serverSide-table1"
						   style="width: 100% !important;">
						<thead class="bg-info">
						<tr>
							<th class="text-dark">Name</th>
							<th class="text-dark">address</th>
							<th class="text-dark">Phone</th>
							<th class="text-dark">Email Address</th>
							<th class="text-dark">SIN #</th>
							<th class="text-dark">Date of Birth</th>
							<th class="text-dark">Hiring Date</th>
							<th class="text-dark">Employee Rate</th>
							<th class="text-dark">Position</th>
							<th class="text-dark">Notes/Comments</th>
							<th class="text-dark">Create At</th>
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
						return data + ' ' + row['lastName'];
					}, "targets": 0,
				},
				{
					"render": function (data, type, row) {
						if (data > 0) {
							return '$' + data;
						} else {
							return data;
						}

					}, "targets": 7
				},
				{
					"render": function (data, type, row) {
						if (data) {
							return moment(data).format('DD MMM YYYY');
						} else {
							return '-';
						}

					}, "targets": [5, 6, 10, 11], type: 'date'
				},
				{
					"render": function (data, type, row) {
						return '<a onclick="showSwal(\'passing-parameter-execute-active\', \'' + '<?php echo base_url('admin/changeStatusActive/'); ?>' + row['id'] + '\')">' +
							'<button class="btn btn-xs btn-warning text-white">Archived</button></a>';
					}, "targets": 12
				}
			],
			'aoColumns': [{mData: "firstName"}, {mData: "address"}, {mData: "phone"}, {mData: "email"}, {mData: "sin"},
				{mData: "dateOfBirth"}, {mData: "hiringDate"}, {mData: "baseRate"}, {mData: "position"}, {mData: "notes"},
				{mData: "createAt"},{mData: "updateAt"},{mData: "status"}],
			"aLengthMenu": [[25, 50, 100, 200, 500, -1], [25, 50, 100, 200, 500, 'All']],
			"iDisplayLength": 25,
			'bProcessing': true,
			"language": {
				processing: '<div><i class="fa fa-spinner fa-spin fa-3x fa-fw"></i><span class="sr-only">Loading Please Wait...</span></div>'
			},
			'bServerSide': true,
			'sAjaxSource': '<?= admin_url('getArchivedCaregivers') ?>',
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
			// dom: '<"top"B<"pull-right"l>>irtp',
			// dom: 'fi<"pull-right"l>rtp',
		});
	});
</script>
