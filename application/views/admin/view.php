<div class="modal" id="modal-default" style="display: block;overflow: auto;">
	<div class="modal-dialog modal-md">
		<div class="modal-content">
			<div class="modal-header">
				<h4 class="modal-title"><b>Trade Details</b></h4>
				<button type="button" class="btn btn-danger float-end close">Close
				</button>
			</div>
			<div class="modal-body">
				<div class="row">
					<div class="col-md-12 text-center">
						<img class="img-fluid rounded mx-auto d-block clickable-image" id="preview"
							 src="<?= $data->photo != '' ? base_url('uploads/' . $data->id . '/' . $data->photo) : base_url('assets/images/others/noImage.png') ?>"
							 alt="Trade Image" width="200" height="180">
					</div>
					<div class="overlay" id="image-overlay">
						<img src="" alt="Maximized Image" id="maximized-image">
					</div>
				</div>
				<div class="row">
					<div class="col-md-12 table-responsive" style="overflow: auto">
						<table class="table">
							<tbody>
							<tr>
								<td>Date</td>
								<td><?= date('d M Y', strtotime($data->entryDate)) ?></td>
							</tr>
							<tr>
								<td>Ticker</td>
								<td><?= $data->ticker ?></td>
							</tr>
							<tr>
								<td>Trade Side</td>
								<td><?= $data->side ?></td>
							</tr>
							<tr>
								<td>Contracts</td>
								<td><?= $data->contracts ?></td>
							</tr>
							<tr>
								<td>Entry Contract Price</td>
								<td>$<?= $data->price ?></td>
							</tr>
							<tr>
								<td>Trade Entry Cost</td>
								<td class="<?= $data->subTotal < 0 ? 'text-danger fw-bold' : '' ?>">
									$<?= abs($data->subTotal) ?></td>
							</tr>
							<tr>
								<td>Exit Contract Price</td>
								<td><?= $data->sold ?></td>
							</tr>
							<tr>
								<td>Trade Exit Cost</td>
								<td class="<?= $data->amount < 0 ? 'text-danger fw-bold' : '' ?>">
									$<?= abs($data->amount) ?></td>
							</tr>
							<tr>
								<td>Total Profit/Loss</td>
								<td class="<?= $data->total < 0 ? 'text-danger fw-bold' : '' ?>">
									$<?= abs($data->total) ?></td>
							</tr>
							<tr>
								<td>Total Profit/Loss (%)</td>
								<td class="<?= $data->percentage < 0 ? 'text-danger fw-bold' : '' ?>"><?= abs($data->percentage) ?>
									%
								</td>
							</tr>
							<tr>
								<td>Trading Notes</td>
								<td>
									<div style="word-wrap: break-word; max-width: 200px;">
										<?= $data->note ?>
									</div>
								</td>
							</tr>
							<tr>
								<td>Trading Notes 2</td>
								<td>
									<div style="word-wrap: break-word; max-width: 200px;">
										<?= $data->note2 ?>
									</div>
								</td>
							</tr>
							</tbody>
						</table>
					</div>
				</div>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-danger close">Close</button>
			</div>
			</form>
		</div>
	</div>
</div>
<style>

	.overlay {
		display: none;
		position: fixed;
		top: 0;
		left: 0;
		width: 100%;
		height: 100%;
		background: rgba(0, 0, 0, 0.8);
		justify-content: center;
		align-items: center;
		overflow: hidden;
		z-index: 1000;
	}

	.overlay.active {
		display: flex;
	}

	.overlay img {
		max-width: 90%;
		max-height: 90%;
		box-shadow: 0 0 10px rgba(0, 0, 0, 0.5);
	}
</style>
<script>
	$('.clickable-image').on('click', function() {
		var src = $(this).attr('src');
		$('#maximized-image').attr('src', src);
		$('#image-overlay').addClass('active');
	});

	$('#image-overlay').on('click', function() {
		$(this).removeClass('active');
	});
	$('.close').on('click', function () {
		$("#remoteModal1").modal('hide');
	});
</script>
