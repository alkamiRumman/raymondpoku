<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8"/>
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1, minimum-scale=1.0, shrink-to-fit=no">
	<meta name="description" content="<?= COMPANY ?>">
	<meta name="author" content="Wraprotale">
	<meta name="keywords"
		  content="<?= COMPANY ?>">
	<title><?= SHORTNAME . ' - ' . 'Login' ?></title>
	<!-- Web Fonts
	========================= -->
	<link rel='stylesheet' href='https://fonts.googleapis.com/css?family=Poppins:100,200,300,400,500,600,700,800,900'
		  type='text/css'>
	<link rel="shortcut icon" href="<?= base_url('assets/images/Logo.png') ?>"/>
	<!-- Stylesheet
	========================= -->
	<link rel="stylesheet" type="text/css"
		  href="<?= base_url('assets/login/vendor/bootstrap/css/bootstrap.min.css') ?>"/>
	<link rel="stylesheet" type="text/css" href="<?= base_url('assets/login/vendor/font-awesome/css/all.min.css') ?>"/>
	<link rel="stylesheet" type="text/css" href="<?= base_url('assets/login/css/stylesheet.css') ?>"/>
	<link id="color-switcher" type="text/css" rel="stylesheet"
		  href="<?= base_url('assets/login/css/color-pink.css') ?>"/>
	<link rel="stylesheet" href="<?= base_url('assets/vendors/sweetalert2/sweetalert2.min.css') ?>">
</head>
<body>

<!-- Preloader -->
<div class="preloader">
	<div class="lds-ellipsis">
		<div></div>
		<div></div>
		<div></div>
		<div></div>
	</div>
</div>
<!-- Preloader End -->

<div id="main-wrapper" class="oxyy-login-register">
	<div class="hero-wrap min-vh-100">
		<div class="hero-mask opacity-4 bg-dark"></div>
		<div class="hero-bg hero-bg-scroll"
			 style="background-image:url(<?= base_url('assets/images/background.png'); ?>)"></div>
		<div class="hero-content d-flex min-vh-100">
			<div class="container my-auto">
				<div class="row">
					<div class="col-md-9 col-lg-7 col-xl-5 mx-auto">
						<div class="hero-wrap rounded shadow-lg p-4 py-sm-5 px-sm-5 my-4">
							<div class="hero-mask opacity-9 bg-dark"></div>
							<div class="hero-content">
								<div class="logo mb-4"><a class="d-flex justify-content-center" href="<?= base_url() ?>"
														  title="<?= COMPANY ?>"><img height="150"
																					  src="<?= base_url('assets/images/Logo.png') ?>"
																					  alt="<?= COMPANY ?>"></a></div>
								<form class="forms-sample" id="payment-form"
									  action="<?= login_url('getPaymentMethodId') ?>" method="post">
									<div class="mb-3 icon-group">
										<input type="hidden" name="stripeToken" id="stripeToken">
										<input type="text" class="form-control" id="fullName" name="fullName"
											   required
											   placeholder="Full Name">
										<span class="icon-inside text-primary"><i class="fas fa-user"></i></span>
									</div>
									<div class="mb-3 icon-group">
										<input type="email" class="form-control" id="username" name="username"
											   required
											   placeholder="Email Address">
										<span class="icon-inside text-primary"><i
													class="fas fa-envelope"></i></span></div>
									<div class="mb-3 icon-group">
										<input type="password" class="form-control" id="loginPassword"
											   name="password" required
											   placeholder="Password">
										<span class="icon-inside text-primary"><i class="fas fa-lock"></i></span>
									</div>
									<div class="mb-3">
										<select class="form-select" id="package" name="package" required>
											<option value="1">$19.99 /month</option>
											<option value="6">$99.00 /6 months</option>
											<option value="12">$199.00 /12 months</option>
										</select>
									</div>
									<div class="mb-3">
										<div class="form-control" id="card-element"></div>
									</div>
									<div class="d-grid mt-4 mb-3">
										<button class="btn btn-primary text-uppercase" type="submit">Sign Up
										</button>
									</div>
								</form>
								<div class="d-flex align-items-center mt-2 mb-3">
									<hr class="flex-grow-1 border-light">
									<span class="mx-2 text-white-50 text-2">Or Sign up with</span>
									<hr class="flex-grow-1 border-light">
								</div>
								<div class="d-flex flex-column align-items-center mb-3">
									<ul class="social-icons social-icons-rounded">
										<li class="social-icons-facebook"><a href="#" data-bs-toggle="tooltip"
																			 data-bs-original-title="Sign Up with Facebook"><i
														class="fab fa-facebook-f"></i></a></li>
										<li class="social-icons-twitter"><a href="#" data-bs-toggle="tooltip"
																			data-bs-original-title="Sign Up with Twitter"><i
														class="fab fa-twitter"></i></a></li>
										<li class="social-icons-google"><a href="#" data-bs-toggle="tooltip"
																		   data-bs-original-title="Sign Up with Google"><i
														class="fab fa-google"></i></a></li>
										<li class="social-icons-linkedin"><a href="#" data-bs-toggle="tooltip"
																			 data-bs-original-title="Sign Up with Linkedin"><i
														class="fab fa-linkedin-in"></i></a></li>
									</ul>
								</div>
								<p class="text-2 text-white-50 text-center mb-0">Already a member <a
											class="link-light text-3" href="<?= login_url('index') ?>">Login now</a></p>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
<!-- Script -->
<script src="<?= base_url('assets/login/vendor/jquery/jquery.min.js') ?>"></script>
<script src="<?= base_url('assets/login/vendor/bootstrap/js/bootstrap.bundle.min.js') ?>"></script>
<script src="<?= base_url('assets/login/js/theme.js') ?>"></script>
<script src="<?= base_url('assets/vendors/sweetalert2/sweetalert2.min.js') ?>"></script>
<script src="<?= base_url('assets/js/sweet-alert.js') ?>"></script>
<script src="https://js.stripe.com/v3/"></script>
<script>
	var status = 0;
	let isDuplicate = false;
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
	var stripe = Stripe('<?php echo $this->config->item('stripe_publishable_key'); ?>');
	var elements = stripe.elements();
	var cardElement = elements.create('card');
	cardElement.mount('#card-element');
	cardElement.on('change', function (event) {
		if (event.error) {
			Toast.fire({
				icon: "warning",
				title: event.error.message
			});
		}
	})
	// var formData = document.getElementById('payment-form');
	$("#payment-form").on('submit', function (event) {
		event.preventDefault();
		if (isDuplicate === true) {
			event.preventDefault();
			Toast.fire({
				icon: "warning",
				title: "Email already exist!"
			});
		}

		stripe.createToken(cardElement).then(function (result) {
			if (result.error) {
				Toast.fire({
					icon: "warning",
					title: result.error.message
				});
			} else {
				// Send the token to your server.
				$('#stripeToken').val(result.token.id)
				stripeTokenHandler(result.token);
			}
		});
	});

	function stripeTokenHandler(token) {
		// Insert the token ID into the form so it gets submitted to the server
		var form = document.getElementById('payment-form');
		var hiddenInput = document.createElement('input');
		hiddenInput.setAttribute('type', 'hidden');
		hiddenInput.setAttribute('name', 'stripeToken');
		hiddenInput.setAttribute('value', token.id);
		form.appendChild(hiddenInput);

		// Submit the form
		form.submit();
	}

	const Toast = Swal.mixin({
		toast: true,
		position: "bottom-end",
		showConfirmButton: false,
		timer: 3000,
		timerProgressBar: true,
		didOpen: (toast) => {
			toast.onmouseenter = Swal.stopTimer;
			toast.onmouseleave = Swal.resumeTimer;
		}
	});
	<?php if($this->session->flashdata('success')){ ?>
	Toast.fire({
		icon: "success",
		title: "<?php echo $this->session->flashdata('success'); ?>"
	});
	<?php }else if($this->session->flashdata('error')){  ?>
	Toast.fire({
		icon: "error",
		title: "<?php echo $this->session->flashdata('error'); ?>"
	});
	<?php }else if($this->session->flashdata('warning')){  ?>
	Toast.fire({
		icon: "warning",
		title: "<?php echo $this->session->flashdata('warning'); ?>"
	});
	<?php }else if($this->session->flashdata('info')){  ?>
	Toast.fire({
		icon: "info",
		title: "<?php echo $this->session->flashdata('info'); ?>"
	});
	<?php } ?>
</script>
</body>
</html>
