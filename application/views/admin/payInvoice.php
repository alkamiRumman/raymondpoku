<div class="modal" id="modal-default" style="display: block; overflow: auto;">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<div class="modal-header">
				<h4 class="modal-title"><b>Pay Invoice</b></h4>
				<button type="button" class="btn btn-danger close">Close</button>
			</div>
			<form class="forms-sample" action="<?= admin_url('savePay/') . $data->id ?>" method="post">
				<div class="modal-body">
					<div class="row">
						<div class="col-md-6 mb-3">
							<h5>BILL TO</h5>
							<h5><?= $data->companyName . '<br>' . $data->name ?></h5>
							<p><?= $data->phone ?><br><?= $data->billingAddress ?></p>
							<?php if ($data->updateAt) { ?>
								<pre>Last Payment: <?= date('Y-m-d H:i:s', strtotime($data->updateAt)) ?></pre>
							<?php } ?>
						</div>
						<div class="col-md-5 mb-3">
							<table class="table table-responsive table-borderless table-sm">
								<tbody>
								<tr>
									<th style="padding-top: 0;padding-bottom: 0" class="text-end">Invoice Number:</th>
									<td style="padding-top: 0;padding-bottom: 0"
										class="text-start pl-2"><?= $data->invoiceNumber ?>
									</td>
								</tr>
								<tr>
									<th style="padding-top: 0;padding-bottom: 0" class="text-end">P.O/S.O Number:</th>
									<td style="padding-top: 0;padding-bottom: 0"
										class="text-start pl-2"><?= $data->poNumber ?></td>
								</tr>
								<tr>
									<th style="padding-top: 0;padding-bottom: 0" class="text-end">Invoice Date:</th>
									<td style="padding-top: 0;padding-bottom: 0"
										class="text-start pl-2"><?= date('d M Y', strtotime($data->invoiceDate)) ?></td>
								</tr>
								<tr>
									<th style="padding-top: 0;padding-bottom: 0" class="text-end">Payment Due:</th>
									<td style="padding-top: 0;padding-bottom: 0"
										class="text-start pl-2"><?= date('d M Y', strtotime($data->dueDate)) ?></td>
								</tr>
								<tr>
									<th style="padding-top: 0;padding-bottom: 0;" class="text-end">Total Amount (CAD):
									</th>
									<th style="padding-top: 0;padding-bottom: 0;" class="text-start pl-2">
										$<?= $data->totalAmount ?></th>
								</tr>
								<tr>
									<th style="padding-top: 0;padding-bottom: 0;" class="text-end">Tax Amount (CAD):
									</th>
									<th style="padding-top: 0;padding-bottom: 0;"
										class="text-start pl-2">$<?= $data->taxAmount ?></th>
								</tr>
								<tr class="bg-gray-200">
									<th style="padding-top: 0;padding-bottom: 0;font-size: 18px" class="text-end">Amount
										Due
										(CAD):
									</th>
									<th style="padding-top: 0;padding-bottom: 0;font-size: 18px"
										class="text-start pl-2">$<?= $data->total ?></th>
								</tr>
								<tr>
									<th style="padding-top: 0;padding-bottom: 0;" class="text-end">Paid
										Amount (CAD):
									</th>
									<th style="padding-top: 0;padding-bottom: 0;"
										class="text-start pl-2">$<?= $data->paidAmount ?></th>
								</tr>
								<tr>
									<th style="padding-top: 0;padding-bottom: 0;" class="text-end">Pay
										Amount (CAD):
									</th>
									<th style="padding-top: 0;padding-bottom: 0;"
										class="text-start pl-2">
										<input type="hidden" name="total" value="<?= $data->total ?>">
										<input type="hidden" name="clientId" value="<?= $data->clientId ?>">
										<input type="number" step="any" min="0"
											   max="<?= $data->total - $data->paidAmount ?>" name="paidAmount">
									</th>
								</tr>
								</tbody>
							</table>
						</div>
					</div>
				</div>
				<div class="modal-footer">
					<button type="submit" class="btn btn-primary">Pay</button>
				</div>
			</form>
		</div>
	</div>
</div>
<script>
	$('.close').on('click', function () {
		$("#remoteModal1").modal('hide');
	});
</script>
