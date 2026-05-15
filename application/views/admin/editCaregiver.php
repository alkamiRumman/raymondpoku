<div class="modal" id="modal-default" style="display: block;overflow: auto;">
	<div class="modal-dialog modal-xl">
		<div class="modal-content">
			<div class="modal-header">
				<h4 class="modal-title"><b>Update Caregiver Details</b></h4>
				<button type="button" class="btn btn-danger float-end close">Close
				</button>
			</div>
			<form class="forms-sample" action="<?= admin_url('updateCaregiver/') . $data->id ?>" method="post"
				  enctype="multipart/form-data">
				<div class="modal-body">
					<div class="row">
						<div class="col-md-2 mb-3">
							<label for="firstName" class="form-label">First Name <sup
										class="text-danger">*</sup></label>
							<input type="text" class="form-control" id="firstName" name="firstName"
								   value="<?= $data->firstName ?>" required>
						</div>
						<div class="col-md-2 mb-3">
							<label for="lastName" class="form-label">Last Name </label>
							<input type="text" class="form-control" id="lastName" name="lastName"
								   value="<?= $data->lastName ?>">
						</div>
						<div class="col-md-3 mb-3">
							<label for="address" class="form-label">Address <sup
										class="text-danger">*</sup></label>
							<input type="text" class="form-control" id="address" name="address"
								   value="<?= $data->address ?>" required>
						</div>
						<div class="col-md-2 mb-3">
							<label for="phone" class="form-label">Phone Number <sup
										class="text-danger">*</sup></label>
							<input type="text" class="form-control" id="phone" name="phone"
								   value="<?= $data->phone ?>" required>
						</div>
						<div class="col-md-3 mb-3">
							<label for="email" class="form-label">Email <sup
										class="text-danger">*</sup></label>
							<input type="email" class="form-control" id="email" name="email"
								   value="<?= $data->email ?>" required>
						</div>
						<div class="col-md-2 mb-3">
							<label for="sin" class="form-label">SIN# <sup
										class="text-danger">*</sup></label>
							<input type="text" class="form-control" id="sin" name="sin"
								   value="<?= $data->sin ?>" required>
						</div>
						<div class="col-md-2 mb-3">
							<label for="dateOfBirth" class="form-label">Date of Birth<sup
										class="text-danger">*</sup></label>
							<div class="input-group flatpickr">
								<input type="text" id="dateOfBirth" name="dateOfBirth" class="form-control"
									   value="<?= date('d M Y', strtotime($data->dateOfBirth)) ?>" data-input required>
								<span class="input-group-text input-group-addon" data-toggle>&#128198;</span>
							</div>
						</div>
						<div class="col-md-2 mb-3">
							<label for="hiringDate" class="form-label">Hiring Date<sup
										class="text-danger">*</sup></label>
							<div class="input-group flatpickr">
								<input type="text" id="hiringDate" name="hiringDate" class="form-control"
									   value="<?= date('d M Y', strtotime($data->hiringDate)) ?>" data-input required>
								<span class="input-group-text input-group-addon" data-toggle>&#128198;</span>
							</div>
						</div>
						<div class="col-md-2 mb-3">
							<label for="baseRate" class="form-label">Employee Rate<sup class="text-danger">*</sup></label>
							<div class="input-group">
								<span class="input-group-text">$</span>
								<input type="number" step="any" class="form-control" id="baseRate"
									   name="baseRate"
									   value="<?= $data->baseRate ?>" required>
							</div>
						</div>
						<div class="col-md-4 mb-3">
							<label for="position" class="form-label">Position <sup class="text-danger">*</sup></label>
							<input type="text" class="form-control" id="position" name="position"
								   value="<?= $data->position ?>" required>
						</div>
					</div>
					<div class="row">
						<div class="col-md-6 mb-3">
							<label for="notes" class="form-label"> Notes/Comments </label>
							<div class="input-group">
								<textarea rows="4" class="form-control" id="notes"
										  name="notes" placeholder="Enter your note here"><?= $data->notes ?></textarea>
							</div>
						</div>
					</div>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-danger close">Close</button>
					<button type="submit" class="btn btn-info me-2">Update</button>
				</div>
			</form>
		</div>
	</div>
</div>
<script>
	$("#hiringDate").flatpickr({
		defaultDate: "today",
		dateFormat: "d M Y",
	});
	$("#dateOfBirth").flatpickr({
		dateFormat: "d M Y",
		maxDate: "today",
	});

	$('.close').on('click', function () {
		$("#remoteModal1").modal('hide');
	});
</script>
