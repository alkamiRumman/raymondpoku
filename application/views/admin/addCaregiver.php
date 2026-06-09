<div class="modal" id="modal-default" style="display:block;overflow:auto;">
	<div class="modal-dialog modal-xl">
		<div class="modal-content">
			<div class="modal-header">
				<div>
					<h5 class="modal-title">Add New Caregiver</h5>
					<small class="text-white-50">Fill in the details below to register a new caregiver</small>
				</div>
				<button type="button" class="btn btn-sm btn-outline-light close">Close</button>
			</div>

			<form action="<?= admin_url('saveCaregiver') ?>" method="post" enctype="multipart/form-data">
				<div class="modal-body p-4">

					<!-- Personal Information -->
					<div class="mb-4">
						<div class="form-section-label">Personal Information</div>
						<div class="row g-3">
							<div class="col-md-3">
								<label for="firstName" class="form-label">First Name <span class="text-danger">*</span></label>
								<input type="text" class="form-control" name="firstName" id="firstName" placeholder="First name" required>
							</div>
							<div class="col-md-3">
								<label for="lastName" class="form-label">Last Name</label>
								<input type="text" class="form-control" name="lastName" id="lastName" placeholder="Last name">
							</div>
							<div class="col-md-3">
								<label for="dateOfBirth" class="form-label">Date of Birth <span class="text-danger">*</span></label>
								<input type="text" class="form-control" name="dateOfBirth" id="dateOfBirth" placeholder="dd MMM YYYY" required>
							</div>
							<div class="col-md-3">
								<label for="email" class="form-label">Email <span class="text-danger">*</span></label>
								<input type="email" class="form-control" name="email" id="email" placeholder="email@example.com" required>
							</div>
						</div>
					</div>

					<!-- Contact Information -->
					<div class="mb-4">
						<div class="form-section-label">Contact Information</div>
						<div class="row g-3">
							<div class="col-md-6">
								<label for="address" class="form-label">Address <span class="text-danger">*</span></label>
								<input type="text" class="form-control" name="address" id="address" placeholder="Full address" required>
							</div>
							<div class="col-md-3">
								<label for="phone" class="form-label">Phone Number <span class="text-danger">*</span></label>
								<input type="text" class="form-control" name="phone" id="phone" placeholder="e.g. 416-555-0100" required>
							</div>
							<div class="col-md-3">
								<label for="sin" class="form-label">SIN# <span class="text-danger">*</span></label>
								<input type="text" class="form-control" name="sin" id="sin" placeholder="e.g. 123-456-789" required>
							</div>
						</div>
					</div>

					<!-- Employment Details -->
					<div class="mb-4">
						<div class="form-section-label">Employment Details</div>
						<div class="row g-3">
							<div class="col-md-3">
								<label for="hiringDate" class="form-label">Hiring Date <span class="text-danger">*</span></label>
								<input type="text" class="form-control" name="hiringDate" id="hiringDate" placeholder="dd MMM YYYY" required>
							</div>
							<div class="col-md-5">
								<label for="position" class="form-label">Position <span class="text-danger">*</span></label>
								<input type="text" class="form-control" name="position" id="position" placeholder="e.g. Personal Support Worker" required>
							</div>
							<div class="col-md-4">
								<label for="baseRate" class="form-label">Employee Rate <span class="text-danger">*</span></label>
								<div class="input-group">
									<span class="input-group-text">$</span>
									<input type="number" step="any" min="0" class="form-control" name="baseRate" id="baseRate" placeholder="0.00" required>
									<span class="input-group-text">/ hr</span>
								</div>
							</div>
						</div>
					</div>

					<!-- Notes -->
					<div>
						<div class="form-section-label">Notes &amp; Comments</div>
						<div class="row g-3">
							<div class="col-md-8">
								<label for="notes" class="form-label">Additional Notes</label>
								<textarea rows="3" class="form-control" name="notes" id="notes" placeholder="Any additional notes about this caregiver…"></textarea>
							</div>
						</div>
					</div>

				</div>

				<div class="modal-footer d-flex justify-content-end gap-2">
					<button type="button" class="btn btn-secondary close">Close</button>
					<button type="submit" class="btn btn-primary">
						<i data-feather="save" style="width:13px;height:13px;vertical-align:middle;margin-right:4px;"></i>Save Caregiver
					</button>
				</div>
			</form>
		</div>
	</div>
</div>

<script>
$('.close').on('click', function () { $('#remoteModal1').modal('hide'); });
$(document).ready(function () { feather.replace(); });
$('#hiringDate').flatpickr({ defaultDate: 'today', dateFormat: 'd M Y' });
$('#dateOfBirth').flatpickr({ dateFormat: 'd M Y', maxDate: 'today' });
</script>
