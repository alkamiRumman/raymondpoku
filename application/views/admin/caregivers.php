<div class="row">
	<div class="col-md-12 grid-margin stretch-card">
		<div class="card">
			<h4 class="card-header d-flex justify-content-between align-items-center">
				Caregiver List
				<div class="btn-group">
					<a href="javascript:void(0);"
					   onclick="loadPopup('<?= admin_url('archivedCaregiver') ?>')"
					   class="btn btn-xs btn-outline-warning me-2">Archived Caregivers</a>
					<a href="javascript:void(0);"
					   onclick="loadPopup('<?= admin_url('addCaregiver') ?>')"
					   class="btn btn-xs btn-outline-info me-2">Add New</a>
				</div>
			</h4>
			<div class="card-body">
				<table id="reportTable" class="table serverSide-table"
					   style="width: 100% !important;">
					<thead class="bg-info">
					<tr>
						<th class="text-dark">Actions</th>
						<th class="text-dark">Name</th>
						<th class="text-dark">Address</th>
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
						return data + ' ' + row['lastName'];
					}, "targets": 1,
				},
				{
					"render": function (data, type, row) {
						if (data > 0) {
							return '$' + data;
						} else {
							return data;
						}
					}, "targets": 8
				},
				{
					"render": function (data, type, row) {
						if (data) {
							return moment(data).format('DD MMM YYYY');
						} else {
							return '-';
						}
					}, "targets": [6, 7, 11, 12], type: 'date'
				},
				{
					"render": function (data, type, row) {
						return '<a onclick="showSwal(\'passing-parameter-execute-archive\', \'' + '<?php echo base_url('admin/changeStatus/'); ?>' + row['id'] + '\')">' +
							'<button class="btn btn-xs btn-success text-white">Active</button></a>';
					},
					"targets": 13
				}
			],
			'aoColumns': [
				{mData: "actions", bSortable: false},
				{mData: "firstName"}, {mData: "address"}, {mData: "phone"}, {mData: "email"}, {mData: "sin"},
				{mData: "dateOfBirth"}, {mData: "hiringDate"}, {mData: "baseRate"}, {mData: "position"}, {mData: "notes"},
				{mData: "createAt"}, {mData: "updateAt"}, {mData: "status"}
			],
			"aLengthMenu": [[25, 50, 100, 200, 500, -1], [25, 50, 100, 200, 500, 'All']],
			"iDisplayLength": 25,
			'bProcessing': true,
			"language": {
				processing: '<div><i class="fa fa-spinner fa-spin fa-3x fa-fw"></i><span class="sr-only">Loading Please Wait...</span></div>'
			},
			'bServerSide': true,
			'sAjaxSource': '<?= admin_url('getCaregivers') ?>',
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
