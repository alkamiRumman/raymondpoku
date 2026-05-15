<div class="modal" id="modal-default" style="display: block;overflow: auto;">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<div class="modal-header">
				<h4 class="modal-title"><b>Package Upgrade History</b></h4>
				<button type="button" class="btn btn-danger float-end close">Close
				</button>
			</div>
			<div class="modal-body">
				<div class="row">
					<div class="col-md-12 mb-3">
						<?php if (isset($user)) { ?>
							<p class="text-center"><?= $user->name . '<br>' . $user->username ?></p>
						<?php } ?>
						<div class="table-responsive">
							<table class="table serverSide-tableHistory caption-top"
								   style="width: 100% !important;">
								<thead>
								<tr>
									<th>Date/Time</th>
									<th>Package</th>
									<th>Expire At</th>
									<th>Upgraded By</th>
								</tr>
								</thead>
								<tbody>
								<?php if ($data) {
									foreach ($data as $datum) { ?>
										<tr>
											<td><?= date('d M Y h:i:s A', strtotime($datum->createAt)) ?></td>
											<td><?= $datum->package == 1 ? '$19.99 /month' : ($datum->package == 6 ? '$99.00 /6 months' : ($datum->package == 12 ? '$199.00 /12 months' : '--')) ?></td>
											<td><?= date('d M Y', strtotime($datum->expireDate)) ?></td>
											<td><?= $datum->name == getSession()->name ? 'Self' : $datum->name ?></td>
										</tr>
									<?php }
								} else { ?>
									<tr>
										<td colspan="4" class="text-danger text-center bold">No History Found!!</td>
									</tr>
								<?php } ?>
								</tbody>
							</table>
						</div>
					</div>
				</div>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-danger close">Close</button>
			</div>
		</div>
	</div>
</div>

<script>
	$(document).ready(function () {
		$('.serverSide-tableHistory').DataTable({
			order: [[0, 'desc']],
			"columnDefs": [{type: 'date', "targets": 0}]
		});
	});
	$('.close').on('click', function () {
		$("#remoteModal1").modal('hide');
	});
</script>
