<div class="row">
	<div class="col-md-12 grid-margin stretch-card">
		<div class="card">
			<h4 class="card-header d-flex justify-content-between align-items-center">
				Payments
				<a href="javascript:void(0);" onclick="loadPopup('<?= admin_url('addPayment') ?>')"
				   class="btn btn-xs btn-outline-info me-2">Record Payment</a>
			</h4>
			<div class="card-body">
				<div class="table-responsive">
					<table id="paymentsTable" class="table table-bordered" style="width:100%!important">
						<thead class="bg-info">
						<tr>
							<th class="text-dark">Actions</th>
							<th class="text-dark">Payment Date</th>
							<th class="text-dark">Client</th>
							<th class="text-dark">Total Amount</th>
							<th class="text-dark">Tax Amount</th>
							<th class="text-dark">Invoices Applied To</th>
							<th class="text-dark">Note</th>
						</tr>
						</thead>
						<tbody></tbody>
					</table>
				</div>
			</div>
		</div>
	</div>
</div>

<script>
function confirmDeletePayment(url) {
	if (confirm('Delete this payment? Invoice balances will be reversed.')) {
		window.location.href = url;
	}
}

$(document).ready(function () {
	$('#paymentsTable').DataTable({
		processing: true,
		serverSide: false,
		order: [[1, 'DESC']],
		scrollX: true,
		ajax: {
			url:  '<?= admin_url('getPaymentsData') ?>',
			type: 'POST',
			dataSrc: ''
		},
		columns: [
			{
				data: null, orderable: false,
				render: function (data, type, row) {
					return '<a href="javascript:void(0);" onclick="confirmDeletePayment(\'<?= admin_url('deletePayment/') ?>' + row.id + '\')" '
						+ 'class="btn btn-xs btn-danger px-2 py-1" title="Delete"><i class="feather icon-trash-2"></i></a>';
				}
			},
			{
				data: 'paymentDate',
				render: function (data) { return moment(data).format('DD MMM YYYY'); }
			},
			{ data: 'clientName' },
			{
				data: 'totalAmount',
				render: function (data) { return '$' + parseFloat(data).toFixed(2); }
			},
			{
				data: 'taxAmount',
				render: function (data) { return data > 0 ? '$' + parseFloat(data).toFixed(2) : '—'; }
			},
			{ data: 'invoiceList', orderable: false },
			{ data: 'note', render: function(d){ return d || '—'; } }
		],
		language: {
			processing: '<div><i class="fa fa-spinner fa-spin fa-3x fa-fw"></i></div>',
			emptyTable: 'No payments recorded yet.'
		}
	});
});
</script>
