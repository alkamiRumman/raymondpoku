<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta http-equiv="X-UA-Compatible" content="ie=edge">
	<meta name="description" content="<?= COMPANY ?>">
	<meta name="author" content="Raymondpoku">
	<meta name="keywords" content="<?= COMPANY ?>">
	<title><?= SHORTNAME ?> | Login</title>

	<!-- Fonts -->
	<link rel="preconnect" href="https://fonts.googleapis.com">
	<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
	<link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500;700;900&display=swap" rel="stylesheet">
	<!-- End fonts -->

	<!-- core:css -->
	<link rel="stylesheet" href="<?= base_url('assets/vendors/core/core.css') ?>">
	<!-- endinject -->

	<!-- inject:css -->
	<link rel="stylesheet" href="<?= base_url('assets/fonts/feather-font/css/iconfont.css') ?>">
	<link rel="stylesheet" href="<?= base_url('assets/vendors/flag-icon-css/css/flag-icon.min.css') ?>">
	<!-- endinject -->

	<!-- Layout styles -->
	<link rel="stylesheet" href="<?= base_url('assets/css/demo1/style.css') ?>">
	<!-- End layout styles -->

	<link rel="shortcut icon" href="<?= base_url('assets/images/favicon.png') ?>"/>
</head>
<body>
<div class="container-fluid min-vh-100 d-flex align-items-center justify-content-center">
    <div class="row w-100 justify-content-center">
        <div class="col-sm-10 col-md-8 col-lg-6 col-xl-5">
            <div class="card shadow-sm">
                <div class="card-body p-4 p-md-5">

                    <a class="noble-ui-logo d-block mb-3 fs-3 fw-bold text-center">
                        <?= COMPANY ?>
                    </a>

                    <h5 class="text-muted fw-normal mb-4 text-center">
                        Welcome back! Log in to your account.
                    </h5>

                    <form method="post" action="<?= login_url('verify') ?>">
                        <div class="mb-3">
                            <label for="username" class="form-label">Email address</label>
                            <input type="email" class="form-control" id="username" name="username" placeholder="Email">
                        </div>

                        <div class="mb-3">
                            <label for="password" class="form-label">Password</label>
                            <input type="password" name="password" id="password" autocomplete="current-password"
                                   class="form-control" placeholder="Password">
                        </div>

                        <button type="submit" class="btn btn-primary w-100 text-white">Login</button>
                    </form>

                    <div class="table-responsive mt-4">
                        <table class="table table-bordered mb-0">
                            <tr id="admin">
                                <td>admin@admin.com</td>
                                <td>123</td>
                                <td>Admin</td>
                            </tr>
                        </table>
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>


<!-- core:js -->
<script src="<?= base_url('assets/vendors/core/core.js') ?>"></script>
<!-- endinject -->

<!-- Plugin js for this page -->
<!-- End plugin js for this page -->

<!-- inject:js -->
<script src="<?= base_url('assets/vendors/feather-icons/feather.min.js') ?>"></script>
<script src="<?= base_url('assets/js/template.js') ?>"></script>
<!-- endinject -->
<script>
	$('#admin').on('click', function () {
		$('#username').val('admin@admin.com');
		$('#password').val('123');
	})
	$('#auditor').on('click', function () {
		$('#username').val('rumman@admin.com');
		$('#password').val('123');
	})

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
<!-- Custom js for this page -->
<!-- End custom js for this page -->
</body>
</html>
