<div class="modal" id="modal-default" style="display: block;overflow: auto;">
	<div class="modal-dialog modal-md">
		<div class="modal-content">
			<div class="modal-header">
				<h4 class="modal-title"><b>Update User</b></h4>
				<button type="button" class="btn btn-danger float-end close">Close
				</button>
			</div>
			<form class="forms-sample" action="<?= login_url('update/') . getSession()->id ?>" method="post"
				  enctype="multipart/form-data">
				<div class="modal-body">
					<div class="mb-3">
						<label for="fullName" class="form-label">Full Name <sup
									class="text-danger">*</sup></label>
						<input autocomplete='off' type="text" name="fullName" class="form-control" id="fullName"
							   value="<?= getSession()->name ?>" required>
					</div>
					<div class="mb-3">
						<input type="checkbox" id="toggleCredentials" name="logindetails"
							   value="1">
						<label for="toggleCredentials">Change Login Details</label>
					</div>
					<div class="mb-3" id="credentials" style="display:none;"> <!-- Initially hidden -->
						<label for="username" class="form-label">Email address <sup
									class="text-danger">*</sup></label>
						<input autocomplete='off' type="email" name="username" class="form-control" id="username"
							   value="<?= getSession()->username ?>">
					</div>
					<div class="row mb-3" id="passwordFields" style="display:none;"> <!-- Initially hidden -->
						<div class="col-md-6">
							<label for="userPassword" class="form-label">Password <sup
										class="text-danger">*</sup></label>
							<input autocomplete='off' type="password" class="form-control" name="password"
								   id="userPassword" autocomplete="current-password" placeholder="Password">
						</div>
						<div class="col-md-6">
							<label for="password1" class="form-label">Confirm Password <sup
										class="text-danger">*</sup></label>
							<input autocomplete='off' type="password" class="form-control" name="password1"
								   id="password1" autocomplete="current-password"
								   placeholder="Confirm Password">
						</div>
					</div>
					<div class="row mb-3">
						<div class="col-md-12">
							<label for="profilePicture" class="form-label">Profile Picture</label>
							<input type="file" class="form-control" name="profilePicture" id="profilePicture">
						</div>
					</div>
					<div class="row mb-3">
						<div class="col-md-12 aligns-items-center justify-content-center text-center">
							<div class="card-body">
								<img width="250" class="img-fluid mx-auto" id="preview"
									 src="<?= getSession()->profilePicture != '' ? base_url('images/' . getSession()->id . '/' .
											 getSession()->profilePicture) : base_url('assets/images/others/noImage.png') ?>">
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
	// Toggle visibility and required attribute of email and password fields
	$('#toggleCredentials').on('change', function () {
		if ($(this).is(':checked')) {
			$('#credentials, #passwordFields').show();
			$('#username, #userPassword, #password1').prop('required', true);
		} else {
			$('#credentials, #passwordFields').hide();
			$('#username, #userPassword, #password1').prop('required', false);
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
		if (password == password1) {
			status = 0;
		} else {
			status = 1;
		}
	});

	$(document).ready(function () {
		$(".forms-sample").submit(function (submitEvent) {
			var filename = $("#profilePicture").val();
			var extension = filename.replace(/^.*\./, '');

			if (extension == filename) {
				extension = '';
			} else {
				extension = extension.toLowerCase();
			}
			switch (extension) {
				case 'gif':
				case 'jpg':
				case 'jpeg':
				case 'png':
				case '':
					break;

				default:
					Toast.fire({
						icon: "warning",
						title: "Image format does not match!"
					});
					submitEvent.preventDefault();
			}

		});
	});

	function readURL(input) {
		if (input.files && input.files[0]) {
			var reader = new FileReader();
			reader.onload = function (e) {
				$('#preview').attr('src', e.target.result);
			}
			reader.readAsDataURL(input.files[0]); // convert to base64 string
		}
	}

	$("#profilePicture").change(function () {
		readURL(this);
	});


	$(".forms-sample").on('submit', function (e) {
		if ($('#toggleCredentials').is(':checked')) {
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
		}
	});

	// Close modal
	$('.close').on('click', function () {
		$("#remoteModal1").modal('hide');
	});
</script>
