<body>
<div class="main-wrapper">
	<nav class="sidebar">
		<div class="sidebar-header">
			<a href="<?= admin_url('index') ?>" class="sidebar-brand">
				<?= SHORTNAME ?>
			</a>
			<div class="sidebar-toggler not-active">
				<span></span>
				<span></span>
				<span></span>
			</div>
		</div>
		<div class="sidebar-body">
			<ul class="nav">
				<?php if (isAdmin()) { ?>
					<li class="nav-item nav-category">Main</li>
					<li class="nav-item">
						<a href="<?= admin_url('index') ?>" class="nav-link">
							<i class="link-icon" data-feather="grid"></i>
							<span class="link-title">Dashboard</span>
						</a>
					</li>

					<li class="nav-item nav-category">People</li>
					<li class="nav-item">
						<a href="<?= admin_url('caregivers') ?>" class="nav-link">
							<i class="link-icon" data-feather="heart"></i>
							<span class="link-title">Caregivers</span>
						</a>
					</li>
					<li class="nav-item">
						<a href="<?= admin_url('clients') ?>" class="nav-link">
							<i class="link-icon" data-feather="users"></i>
							<span class="link-title">Clients</span>
						</a>
					</li>

					<li class="nav-item nav-category">Scheduling</li>
					<li class="nav-item">
						<a href="<?= admin_url('services') ?>" class="nav-link">
							<i class="link-icon" data-feather="clipboard"></i>
							<span class="link-title">Services</span>
						</a>
					</li>
					<li class="nav-item">
						<a href="<?= admin_url('calendar') ?>" class="nav-link">
							<i class="link-icon" data-feather="calendar"></i>
							<span class="link-title">Client Calendar</span>
						</a>
					</li>
					<li class="nav-item">
						<a href="<?= admin_url('caregiverCalendar') ?>" class="nav-link">
							<i class="link-icon" data-feather="printer"></i>
							<span class="link-title">Caregiver Calendar</span>
						</a>
					</li>
					<li class="nav-item">
						<a href="<?= admin_url('caregiverWeekly') ?>" class="nav-link">
							<i class="link-icon" data-feather="clock"></i>
							<span class="link-title">Payroll Hours</span>
						</a>
					</li>

					<li class="nav-item nav-category">Billing</li>
					<li class="nav-item">
						<a href="<?= admin_url('generateInvoice') ?>" class="nav-link">
							<i class="link-icon" data-feather="file-plus"></i>
							<span class="link-title">Generate Invoice</span>
						</a>
					</li>
					<li class="nav-item">
						<a href="<?= admin_url('invoice') ?>" class="nav-link">
							<i class="link-icon" data-feather="dollar-sign"></i>
							<span class="link-title">Invoices</span>
						</a>
					</li>
				<?php } ?>

				<li class="nav-item nav-category">Account</li>
				<li class="nav-item">
					<a href="<?= login_url('logout') ?>" class="nav-link">
						<i class="link-icon" data-feather="log-out"></i>
						<span class="link-title">Logout</span>
					</a>
				</li>
			</ul>
		</div>
	</nav>

	<div class="page-wrapper">
		<nav class="navbar">
			<a href="#" class="sidebar-toggler">
				<i data-feather="menu"></i>
			</a>
			<div class="navbar-content">
				<form class="search-form">
					<div class="input-group">
						<div class="input-group-text">
							<i data-feather="search" style="width:15px;height:15px"></i>
						</div>
						<input type="text" class="form-control" id="navbarForm" placeholder="Search…">
					</div>
				</form>
				<ul class="navbar-nav">
					<li class="nav-item dropdown">
						<a class="nav-link dropdown-toggle" href="#" id="profileDropdown" role="button"
						   data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
							<img class="wd-30 ht-30 rounded-circle"
								 src="<?= getSession()->profilePicture
									? base_url('images/' . getSession()->id . '/' . getSession()->profilePicture)
									: base_url('assets/images/others/noImage.png') ?>"
								 alt="profile">
						</a>
						<div class="dropdown-menu p-0" aria-labelledby="profileDropdown" style="min-width:220px">
							<div class="d-flex flex-column align-items-center border-bottom px-4 py-3">
								<div class="mb-2">
									<img class="wd-60 ht-60 rounded-circle"
										 src="<?= getSession()->profilePicture
											? base_url('images/' . getSession()->id . '/' . getSession()->profilePicture)
											: base_url('assets/images/others/noImage.png') ?>"
										 alt="">
								</div>
								<div class="text-center">
									<p class="tx-15 fw-bolder mb-0"><?= getSession()->name ?></p>
									<p class="tx-12 text-muted mb-0"><?= getSession()->username ?></p>
								</div>
							</div>
							<ul class="list-unstyled p-1 mb-0">
								<li class="dropdown-item py-2">
									<a href="javascript:void(0);" class="text-body ms-0"
									   onclick="loadPopup('<?= login_url('profile') ?>')">
										<i class="me-2 icon-sm" data-feather="edit-2"></i>
										<span>Edit Profile</span>
									</a>
								</li>
								<li class="dropdown-item py-2">
									<a href="<?= login_url('logout') ?>" class="text-body ms-0">
										<i class="me-2 icon-sm" data-feather="log-out"></i>
										<span>Log Out</span>
									</a>
								</li>
							</ul>
						</div>
					</li>
				</ul>
			</div>
		</nav>
		<div class="page-content">
