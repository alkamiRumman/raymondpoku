<div class="modal" id="modal-default" style="display: block;overflow: auto;">
	<div class="modal-dialog modal-xl">
		<div class="modal-content">
			<div class="modal-header">
				<h4 class="modal-title"><b>Add New Trade</b></h4>
				<button type="button" class="btn btn-danger float-end close">Close
				</button>
			</div>
			<form class="forms-sample" action="<?= admin_url('save') ?>" method="post" enctype="multipart/form-data">
				<div class="modal-body">
					<div class="row">
						<div class="col-md-2 mb-3">
							<label for="entryDate" class="form-label">Entry Date <sup
										class="text-danger">*</sup></label>
							<div class="input-group flatpickr">
								<input type="text" id="entryDate" name="entryDate" class="form-control"
									   placeholder="Select date" data-input required>
								<span class="input-group-text input-group-addon" data-toggle>&#128198;</span>
							</div>
						</div>
						<div class="col-md-2 mb-3">
							<label for="ticker" class="form-label">Ticker </label>
							<input type="text" class="form-control" id="ticker" name="ticker">
						</div>
						<div class="col-md-2 mb-3">
							<label for="side" class="form-label">Trade Side </label>
							<select class="form-select" id="side" name="side">
								<option value="Call">Call</option>
								<option value="Put">Put</option>
							</select>
						</div>
						<div class="col-md-2 mb-3">
							<label for="contracts" class="form-label">Contracts <sup class="text-danger">*</sup></label>
							<input type="number" step="any" class="form-control numeric-input" id="contracts"
								   name="contracts"
								   placeholder="Ex: 5" required>
						</div>
						<div class="col-md-2 mb-3">
							<label for="price" class="form-label">Entry Contract Price <sup class="text-danger">*</sup></label>
							<div class="input-group">
								<span class="input-group-text">$</span>
								<input type="number" step="any" class="form-control numeric-input" id="price"
									   name="price"
									   placeholder="Ex: 100" required>
							</div>
						</div>
						<div class="col-md-2 mb-3">
							<label for="sold" class="form-label">Exit Contract Price <sup class="text-danger">*</sup></label>
							<input type="number" step="any" class="form-control numeric-input" id="sold" name="sold"
								   placeholder="Ex: 10" required>
						</div>
						<div class="col-md-2 mb-3">
							<label for="subTotal" class="form-label"> Trade Entry Cost </label>
							<div class="input-group">
								<span class="input-group-text">$</span>
								<input type="number" step="any" class="form-control" id="subTotal"
									   readonly>
								<input type="hidden" id="subTotal_original" name="subTotal">
							</div>
						</div>
						<div class="col-md-2 mb-3">
							<label for="amount" class="form-label">Trade Exit Cost </label>
							<div class="input-group">
								<span class="input-group-text">$</span>
								<input type="number" step="any" class="form-control" id="amount" readonly>
								<input type="hidden" id="amount_original" name="amount">
							</div>
						</div>
						<div class="col-md-2 mb-3">
							<label for="total" class="form-label"> Total Profit/Loss </label>
							<div class="input-group">
								<span class="input-group-text">$</span>
								<input type="number" step="any" class="form-control" id="total" readonly>
								<input type="hidden" id="total_original" name="total">
							</div>
						</div>
						<div class="col-md-2 mb-3">
							<label for="percentage" class="form-label">Total Profit/Loss (%) </label>
							<div class="input-group" id="percentage_group">
								<input type="number" step="any" class="form-control" id="percentage"
									   data-input readonly>
								<span class="input-group-text input-group-addon" data-toggle>%</span>
								<input type="hidden" id="percentage_original" name="percentage">
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-md-6 mb-3">
							<label for="note" class="form-label">Trading Notes </label>
							<div class="input-group">
								<textarea rows="4" class="form-control" id="note"
										  name="note" placeholder="Enter your note for the trade here"></textarea>
							</div>
							<label for="note2" class="form-label">Trading Notes 2</label>
							<div class="input-group">
								<textarea rows="4" class="form-control" id="note2"
										  name="note2" placeholder="Enter your note for the trade here"></textarea>
							</div>
						</div>
						<div class="col-md-6">
							<label for="photo" class="form-label">Upload Trade Photo</label>
							<input type="file" class="form-control" name="photo" id="photo" accept="image/*"><br>
							<div class="card-body">
								<img width="200" style="height: 180px;"
									 class="img-responsive img-thumbnail center-block" id="preview"
									 src="<?= base_url('assets/images/others/noImage.png') ?>">
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
<style>
	.negative {
		font-weight: bold;
		color: red;
	}
</style>
<script>
	$("#entryDate").flatpickr({
		defaultDate: "today",
		dateFormat: "d M Y",
	});
	$("#entryTime, #exitTime").flatpickr({
		enableTime: true,
		noCalendar: true,
		dateFormat: "H:i",
	});
	$(document).on('input', ".numeric-input", function () {
		var contracts = parseFloat($('#contracts').val());
		var price = parseFloat($('#price').val());
		var sold = parseFloat($('#sold').val());

		if (isNaN(contracts) || isNaN(price) || isNaN(sold) || contracts < 0 || price < 0 || sold < 0) {
			return;
		}

		var subtotal = (price * 100) * contracts;
		var amount = sold * contracts * 100;
		var total = amount - subtotal;

		var percentage = subtotal !== 0 ? (total / subtotal) * 100 : 0;

		$('#subTotal').val(subtotal.toFixed(2)).toggleClass('negative', subtotal < 0);
		$('#amount').val(amount.toFixed(2)).toggleClass('negative', amount < 0);
		$('#total').val(total.toFixed(2)).toggleClass('negative', total < 0);
		$('#percentage').val(percentage.toFixed(2)).toggleClass('negative', percentage < 0);

		$('#subTotal_original').val(subtotal.toFixed(2));
		$('#amount_original').val(amount.toFixed(2));
		$('#total_original').val(total.toFixed(2));
		$('#percentage_original').val(percentage.toFixed(2));
	});

	$(document).ready(function () {
		$(".forms-sample").on('submit', function (submitEvent) {
			var filename = $("#photo").val();
			var extension = filename.replace(/^.*\./, '');

			if (extension == filename) {
				extension = '';
			} else {
				extension = extension.toLowerCase();
			}
			switch (extension) {
				case 'jpg':
				case 'jpeg':
				case 'png':
				case '':
					break;
				default:
					Toast.fire({
						icon: "warning",
						title: 'Image format does not match!'
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
			reader.readAsDataURL(input.files[0]);
		}
	}

	$("#photo").change(function () {
		readURL(this);
	});

	$('.close').on('click', function () {
		$("#remoteModal1").modal('hide');
	});
</script>
