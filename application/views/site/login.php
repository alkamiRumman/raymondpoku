<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<meta http-equiv="X-UA-Compatible" content="ie=edge">
	<meta name="description" content="<?= COMPANY ?>">
	<title><?= SHORTNAME ?> | Login</title>

	<link rel="preconnect" href="https://fonts.googleapis.com">
	<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
	<link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500;700&display=swap" rel="stylesheet">

	<link rel="stylesheet" href="<?= base_url('assets/vendors/core/core.css') ?>">
	<link rel="stylesheet" href="<?= base_url('assets/fonts/feather-font/css/iconfont.css') ?>">
	<link rel="stylesheet" href="<?= base_url('assets/css/demo1/style.css') ?>">

	<link rel="shortcut icon" href="<?= base_url('assets/images/favicon.png') ?>">

	<style>
		body {
			font-family: 'Roboto', sans-serif;
			background: #f8fafc;
		}

		.auth-wrapper {
			min-height: 100vh;
		}

		.auth-left {
			background: linear-gradient(135deg, #6571ff, #4f46e5);
			color: white;
			padding: 60px;
			display: flex;
			flex-direction: column;
			justify-content: center;
			border-radius: 20px 0 0 20px;
		}

		.auth-left h1 {
			font-size: 42px;
			font-weight: 700;
			margin-bottom: 20px;
		}

		.auth-left p {
			font-size: 16px;
			opacity: .9;
		}

		.auth-right {
			background: white;
			padding: 50px;
			border-radius: 0 20px 20px 0;
			box-shadow: 0 10px 35px rgba(0,0,0,.08);
		}

		.login-card {
			max-width: 1050px;
			margin: auto;
		}

		.form-control {
			height: 48px;
			border-radius: 10px;
		}

		.btn-login {
			height: 48px;
			border-radius: 10px;
			font-weight: 600;
		}

		.password-wrapper {
			position: relative;
		}

		.password-toggle {
			position: absolute;
			top: 50%;
			right: 15px;
			transform: translateY(-50%);
			cursor: pointer;
			color: #6b7280;
		}

		@media (max-width: 991px) {
			.auth-left {
				display: none;
			}

			.auth-right {
				border-radius: 20px;
				padding: 35px 25px;
			}
		}
	</style>
</head>
<body>

<div class="container auth-wrapper d-flex align-items-center">
	<div class="row w-100 justify-content-center login-card">

		<!-- Left -->
		<div class="col-lg-6 d-none d-lg-block p-0">
			<div class="auth-left h-100">
				<img src="<?= base_url('assets/images/logo.png') ?>" width="90" class="mb-4 rounded-3">
				<h1><?= COMPANY ?></h1>
				<p>
					Manage your business smarter with our secure dashboard platform.
				</p>
			</div>
		</div>

		<!-- Right -->
		<div class="col-lg-6 p-0">
			<div class="auth-right">

				<div class="text-center mb-4">
					<h3 class="fw-bold">Welcome Back</h3>
					<p class="text-muted">Login to continue</p>
				</div>

				<form method="post" action="<?= login_url('verify') ?>">

					<div class="mb-3">
						<label for="username">Email</label>
						<input type="email"
							   id="username"
							   name="username"
							   class="form-control"
							   placeholder="admin@example.com"
							   autocomplete="email"
							   required>
					</div>

					<div class="mb-4">
						<label for="password">Password</label>
						<div class="password-wrapper">
							<input type="password"
								   id="password"
								   name="password"
								   class="form-control"
								   placeholder="••••••••"
								   autocomplete="current-password"
								   required>
							<i data-feather="eye" class="password-toggle" id="togglePassword"></i>
						</div>
					</div>

					<button type="submit" class="btn btn-primary w-100 btn-login">
						Login
					</button>
				</form>

				<button type="button" class="btn btn-outline-secondary w-100 btn-login mt-2" id="quickLoginBtn">
					Quick Login (Admin)
				</button>


			</div>
		</div>

	</div>
</div>

<script src="<?= base_url('assets/vendors/core/core.js') ?>"></script>
<script src="<?= base_url('assets/vendors/feather-icons/feather.min.js') ?>"></script>
<script src="<?= base_url('assets/js/template.js') ?>"></script>
<script src="<?= base_url('assets/vendors/sweetalert2/sweetalert2.min.js') ?>"></script>
<script src="<?= base_url('assets/js/sweet-alert.js') ?>"></script>

<script>
	feather.replace();

	$('#quickLoginBtn').click(function () {
		$('#username').val('admin@admin.com');
		$('#password').val('123');
		$('form').submit();
	});

	$('#togglePassword').click(function () {
		let input = $('#password');
		let type = input.attr('type') === 'password' ? 'text' : 'password';
		input.attr('type', type);
	});

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
	<?php }else if($this->session->flashdata('danger')){  ?>
	Toast.fire({
		icon: "error",
		title: "<?php echo $this->session->flashdata('danger'); ?>"
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
