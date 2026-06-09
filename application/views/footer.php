<div class="modal fade" id="remoteModal1" role="dialog" aria-hidden="true"></div>
<footer class="footer d-flex flex-column flex-md-row align-items-center justify-content-between px-4 py-3 border-top small">
	<p class="text-muted mb-1 mb-md-0">Copyright © 2024 <a href="https://thetradingjournal.io"><?= COMPANY ?></a>.
	</p>
	<p class="text-muted">Handcrafted With <i class="mb-1 text-primary ms-1 icon-sm" data-feather="heart"></i></p>
</footer>
<!-- partial -->
</div>
</div>

<!-- core:js -->
<script src="<?= base_url('assets/vendors/core/core.js') ?>"></script>
<!-- endinject -->

<!-- Plugin js for this page -->
<script src="<?= base_url('assets/vendors/flatpickr/flatpickr.min.js') ?>"></script>
<script src="<?= base_url('assets/vendors/apexcharts/apexcharts.min.js') ?>"></script>
<!-- End plugin js for this page -->
<script src="<?= base_url('assets/vendors/sweetalert2/sweetalert2.min.js') ?>"></script>
<script src="<?= base_url('assets/js/sweet-alert.js') ?>"></script>
<script src="<?= base_url('assets/vendors/datatables.net/jquery.dataTables.js') ?>"></script>
<script src="<?= base_url('assets/vendors/datatables.net-bs5/dataTables.bootstrap5.js') ?>"></script>
<script src="<?= base_url('assets/vendors/datatables.net-bs5/dataTables.buttons.js') ?>"></script>
<script src="<?= base_url('assets/vendors/datatables.net-bs5/buttons.bootstrap5.min.js') ?>"></script>
<script src="<?= base_url('assets/vendors/datatables.net-bs5/buttons.colVis.js') ?>"></script>
<script src="https://cdn.datatables.net/fixedheader/3.4.0/js/dataTables.fixedHeader.min.js"></script>
<script src="https://cdn.datatables.net/fixedcolumns/3.4.0/js/dataTables.fixedColumns.min.js"></script>
<script src="<?= base_url('assets/js/data-table.js') ?>"></script>

<!-- inject:js -->
<script src="<?= base_url('assets/vendors/feather-icons/feather.min.js') ?>"></script>
<script src="<?= base_url('assets/js/template.js') ?>"></script>
<!-- endinject -->
<script src="<?= base_url('assets/vendors/moment/moment.min.js') ?>"></script>
<script src="<?= base_url('assets/vendors/fullcalendar/main.min.js') ?>"></script>
<!-- Custom js for this page -->
<script src="<?= base_url('assets/vendors/chartjs/Chart.min.js') ?>"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.18.5/xlsx.full.min.js"></script>
<!--<script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/0.4.1/html2canvas.js">-->
</script>
<script src="<?= base_url('assets/vendors/select2/select2.min.js') ?>"></script>
<script src="<?= base_url('assets/js/select2.js') ?>"></script>
<script type="text/javascript" src="https://cdn.datatables.net/select/1.1.2/js/dataTables.select.min.js"></script>
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/select/1.1.2/css/select.dataTables.min.css">

<!-- End custom js for this page -->

</body>
</html>
<script>
	$('.close').on('click', function () {
		console.log('he');
		$("#remoteModal1").modal('hide');
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
