<div class="modal" id="modal-default" style="display: block;overflow: auto;">
	<div class="modal-dialog modal-md">
		<div class="modal-content">
			<div class="modal-header">
				<h4 class="modal-title"><b>Add New User</b></h4>
				<button type="button" class="btn btn-danger float-end close">Close
				</button>
			</div>
			<form class="forms-sample" action="<?= admin_url('saveUser') ?>" method="post"
				  enctype="multipart/form-data">
				<div class="modal-body">
					<div class="mb-3">
						<label for="fullName" class="form-label">Full Name <sup
									class="text-danger">*</sup></label>
						<input autocomplete='off' type="text" name="fullName" class="form-control" id="fullName"
							   placeholder="Enter Full Name" required>
					</div>
					<div class="mb-3" id="credentials">
						<label for="username" class="form-label">Email address <sup
									class="text-danger">*</sup></label>
						<input autocomplete='off' type="email" name="username" class="form-control" id="username"
							   placeholder="Enter Username" required>
					</div>
					<div class="row mb-3" id="passwordFields">
						<div class="col-md-6">
							<label for="userPassword" class="form-label">Password <sup
										class="text-danger">*</sup></label>
							<input autocomplete='off' type="password" class="form-control" name="password"
								   id="userPassword" autocomplete="current-password" placeholder="Password" required>
						</div>
						<div class="col-md-6">
							<label for="password1" class="form-label">Confirm Password <sup
										class="text-danger">*</sup></label>
							<input autocomplete='off' type="password" class="form-control" name="password1"
								   id="password1" autocomplete="current-password"
								   placeholder="Confirm Password" required>
						</div>
					</div>
					<div class="row mb-3">
						<div class="col-md-6">
							<label for="package" class="form-label">Select Package</label>
							<select class="form-select" name="package" id="package">
								<option value="">Select Package</option>
								<option value="1">$19.99 /month</option>
								<option value="6">$99.00 /6 months</option>
								<option value="12">$199.00 /12 months
								</option>
							</select>
						</div>
						<div class="col-md-6">
							<label for="expireDate" class="form-label">Expire Date <sup
										class="text-danger">*</sup></label>
							<div class="input-group flatpickr">
								<input type="text" id="expireDate" name="expireDate" class="form-control"
									   placeholder="Select Date" data-input required>
								<span class="input-group-text input-group-addon" data-toggle>&#128198;</span>
							</div>
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

	$("#expireDate").flatpickr({
		dateFormat: "d M Y",
	});

	$(document).on('change', "#package", function () {
		var monthsToAdd = parseInt($(this).val());
		var expireDateString = new Date().toLocaleDateString('en-GB', {
			day: '2-digit',
			month: 'short',
			year: 'numeric'
		});
		if (!isNaN(monthsToAdd)) {
			var expireDate = new Date(expireDateString);
			expireDate.setMonth(expireDate.getMonth() + monthsToAdd);
			$('#expireDate').val(expireDate.toLocaleDateString('en-GB', {
				day: '2-digit',
				month: 'short',
				year: 'numeric'
			}));
		} else {
			$('#expireDate').val(expireDateString);
		}
	});

	var status = 0;
	var isDuplicate = false;
	$("#username").on("input", function () {
		let txt = $(this).val();
		console.log(txt);
		$.ajax({
			url: '<?=base_url('site/checkEmail')?>',
			data: {text: txt},
			type: 'get'
		}).done(function (data) {
			isDuplicate = (Number(data.count) > 0);
		}).fail(function (sr) {
			console.log(sr);
		});
	});
	$('#password1, #userPassword').on('input', function () {
		var password = $('#userPassword').val();
		var password1 = $('#password1').val();
		console.log(password);
		console.log(password1);
		if (password == password1) {
			status = 0;
		} else {
			status = 1;
		}
	});

	$(".forms-sample").on('submit', function (e) {
		if (status == 1) {
			e.preventDefault();
			Toast.fire({
				icon: "warning",
				title: "Password doesn't match!"
			});
		}
		if (isDuplicate === true) {
			e.preventDefault();
			Toast.fire({
				icon: "warning",
				title: "Email already exist!"
			});
		}
	});
</script>
