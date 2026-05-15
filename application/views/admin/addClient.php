<div class="modal" id="modal-default" style="display: block;overflow: auto;">
	<div class="modal-dialog modal-xl">
		<div class="modal-content">
			<div class="modal-header">
				<h4 class="modal-title"><b>Add New Client</b></h4>
				<button type="button" class="btn btn-danger float-end close">Close
				</button>
			</div>
			<form class="forms-sample" action="<?= admin_url('saveClient') ?>" method="post"
				  enctype="multipart/form-data">
				<div class="modal-body">
					<div class="row">
						<div class="col-md-3 mb-3">
							<label for="name" class="form-label">Client Name <sup
										class="text-danger">*</sup></label>
							<input autocomplete='off' type="text" name="name" class="form-control" id="name"
								   placeholder="Enter Client Name" required>
						</div>
						<div class="col-md-3 mb-3">
							<label for="phone" class="form-label">Phone Number <sup
										class="text-danger">*</sup></label>
							<input type="text" class="form-control" id="phone" name="phone"
								   placeholder="Phone Number" required>
						</div>
						<div class="col-md-6 mb-3">
							<label for="address" class="form-label">Address <sup
										class="text-danger">*</sup></label>
							<input type="text" class="form-control" id="address" name="address"
								   placeholder="Address" required>
						</div>
					</div>
					<div class="row">
						<div class="col-md-6 mb-3">
							<label for="referralSource" class="form-label">Referral Source <sup
										class="text-danger">*</sup></label>
							<input type="text" class="form-control" id="referralSource" name="referralSource" required>
						</div>
						<div class="col-md-3 mb-3">
							<label for="referralDate" class="form-label">Referral Date<sup
										class="text-danger">*</sup></label>
							<div class="input-group flatpickr">
								<input type="text" id="referralDate" name="referralDate" class="form-control"
									   placeholder="Select date" data-input required>
								<span class="input-group-text input-group-addon" data-toggle>&#128198;</span>
							</div>
						</div>
						<div class="col-md-3 mb-3">
							<label for="dol" class="form-label">DOL </label>
							<input type="text" class="form-control" id="dol" name="dol">
						</div>
					</div>
					<div class="row">
						<div class="col-md-8 mb-3">
							<label for="billingAddress" class="form-label">Billing Address <sup
										class="text-danger">*</sup></label>
							<input type="text" class="form-control" id="billingAddress" name="billingAddress"
								   placeholder="Billing Address" required>
						</div>
						<div class="col-md-4 mb-3">
							<label for="companyName" class="form-label">Company Name <sup
										class="text-danger">*</sup></label>
							<input type="text" class="form-control" id="companyName" name="companyName"
								   placeholder="Company Name" required>
						</div>
					</div>
					<div class="row">
						<div class="col-md-4 mb-3">
							<label for="adjustorName" class="form-label">Adjustor name <sup
										class="text-danger">*</sup></label>
							<input type="text" class="form-control" id="adjustorName" name="adjustorName"
								   placeholder="Adjustor Name" required>
						</div>
						<div class="col-md-4 mb-3">
							<label for="adjustorEmail" class="form-label">Adjustor Email </label>
							<input type="text" class="form-control" id="adjustorEmail" name="adjustorEmail"
								   placeholder="Adjustor Email">
						</div>
						<div class="col-md-4 mb-3">
							<label for="adjustorPhone" class="form-label">Adjustor Phone # <sup
										class="text-danger">*</sup></label>
							<input type="number" class="form-control" id="adjustorPhone" name="adjustorPhone"
								   placeholder="Adjustor Phone" required>
						</div>
						<div class="col-md-4 mb-3">
							<label for="adjustorFax" class="form-label">Adjustor Fax </label>
							<input type="text" class="form-control" id="adjustorFax" name="adjustorFax"
								   placeholder="Adjustor Fax">
						</div>
						<div class="col-md-3 mb-3">
							<label for="budget" class="form-label">Budget/Form 1 <sup
										class="text-danger">*</sup></label>
							<div class="input-group">
								<span class="input-group-text">$</span>
								<input type="number" step="any" min="0" class="form-control" id="budget"
									   name="budget"
									   placeholder="Ex: 3300" required>
							</div>
						</div>
						<div class="col-md-2 mb-2">
							<label for="billRate" class="form-label">Bill Rate <sup
										class="text-danger">*</sup></label>
							<div class="input-group">
								<span class="input-group-text">$</span>
								<input type="number" step="any" min="0" class="form-control" id="billRate"
									   name="billRate"
									   placeholder="Ex: 50" required>
							</div>
						</div>
						<div class="col-md-2 mb-3">
							<label for="budgetedHours" class="form-label">Budgeted Hours</label>
							<input type="number" step="any" min="0" class="form-control" id="budgetedHours"
								   name="budgetedHours" readonly>
						</div>
					</div>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-danger close">Close</button>
					<button type="submit" class="btn btn-success me-2">Save</button>
				</div>
			</form>
		</div>
	</div>
</div>

<script>
	$('.close').on('click', function () {
		$("#remoteModal1").modal('hide');
	});

	$("#referralDate").flatpickr({
		dateFormat: "d M Y",
		defaultDate: "today",
	});
	$('#budget, #billRate').on('input', function () {
		var budget = parseFloat($('#budget').val());
		var billRate = parseFloat($('#billRate').val());
		var total = budget / billRate;
		$('#budgetedHours').val(total.toFixed(2));
	})
</script>
