<div class="row">
	<div class="col-md-12 grid-margin stretch-card">
		<div class="card">
			<h4 class="card-header d-flex justify-content-between align-items-center">
				User List
				<a href="javascript:void(0);"
				   onclick="loadPopup('<?= admin_url('addUser') ?>')"
				   class="btn btn-sm btn-outline-info me-2">Add User</a>
			</h4>
			<div class="card-body">
				<div class="table-responsive">
					<table id="reportTable" class="table serverSide-table"
						   style="width: 100% !important;">
						<thead>
						<tr>
							<th>Name</th>
							<th>Username</th>
							<th>Package</th>
							<th>Create At</th>
							<th>Expire At</th>
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
	var Table, selectedIDs = [];
	$(document).ready(function () {
		Table = $('.serverSide-table').DataTable({
			serverSide: true,
			order: [[4, "ASC"]],
			// destroy: true,
			stateSave: true,
			"columnDefs": [
				{
					"render": function (data, type, row) {
						switch (data) {
							case '1':
								return '$19.99 /month';
								break;
							case '6':
								return '$99.00 /month';
								break;
							case '12':
								return '$199.00 /month';
								break;
							default:
								return '--';
						}
					}, "targets": 2
				},
				{
					"render": function (data, type, row) {
						if (data) {
							return moment(data).format('D MMM YYYY');
						} else {
							return '-';
						}

					}, "targets": [3, 4], type: 'date'
				}
			],
			'aoColumns': [{mData: "name"}, {mData: "username"}, {mData: "package"}, {mData: "createAt"}, {mData: "expireDate"}, {
				mData: "actions",
				bSortable: false
			}],
			"aLengthMenu": [[25, 50, 100, 200, 500, -1], [25, 50, 100, 200, 500, 'All']],
			"iDisplayLength": 25,
			'bProcessing': true,
			"language": {
				processing: '<div><i class="fa fa-spinner fa-spin fa-3x fa-fw"></i><span class="sr-only">Loading Please Wait...</span></div>'
			},
			'bServerSide': true,
			'sAjaxSource': '<?= admin_url('getUsers') ?>',
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
				var expireDate = new Date(aData['expireDate']);
				var currentDate = new Date();
				if (new Date() >= new Date(aData['expireDate'])) {
					$('td', nRow).css('color', '#cc3300');
					$('td', nRow).css('font-weight', 'bold');
				} else if (expireDate.getMonth() <= (currentDate.getMonth() + 1) && expireDate.getFullYear() <= currentDate.getFullYear()) {
					$('td', nRow).css('color', '#ffcc00');
					$('td', nRow).css('font-weight', 'bold');
				} else {
					$('td', nRow).css('color', '#339900');
					$('td', nRow).css('font-weight', 'bold');
				}
			}
			// dom: '<"top"B<"pull-right"l>>irtp',
			// dom: 'fi<"pull-right"l>rtp',
		});
	});

	$('#reportTable tbody').on('click', 'tr td:not(:last-child)', function () {
		var data = $('#reportTable').DataTable().row(this).data();
		console.log(data);
		loadPopup('<?= admin_url('viewPackage/')?>' + data.id);
	});
</script>
