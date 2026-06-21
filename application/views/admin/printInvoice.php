<div class="modal" id="modal-default" style="display:block;overflow:auto;">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">

			<!-- Header -->
			<div class="modal-header" style="background:#1a3c5e;">
				<div>
					<h5 class="modal-title text-white fw-bold mb-0">Invoice</h5>
					<small class="text-white-50"><?= htmlspecialchars($data->invoiceNumber) ?>
						<?php
							$statusColors = ['Sent' => '#f59e0b', 'Partial Paid' => '#3b82f6', 'Fully Paid' => '#22c55e'];
							$sc = $statusColors[$data->status] ?? '#6b7280';
						?>
						&nbsp;<span class="badge rounded-pill" style="background:<?= $sc ?>;font-size:11px;"><?= htmlspecialchars($data->status) ?></span>
					</small>
				</div>
				<div class="d-flex gap-2">
					<button type="button" class="btn btn-sm btn-outline-light printButton">
						<i data-feather="printer" style="width:13px;height:13px;vertical-align:middle;margin-right:4px;"></i>Print
					</button>
					<button type="button" class="btn btn-sm btn-outline-light close">Close</button>
				</div>
			</div>

			<div class="modal-body p-4" id="printThis">

				<!-- ── Company + Invoice Title ─────────────────────────────── -->
				<table style="width:100%;border-collapse:collapse;margin-bottom:24px;">
					<tr>
						<td style="vertical-align:top;width:50%;">
							<img src="<?= base_url('assets/images/logo.png') ?>" alt="Mayer Health Services" height="55" style="display:block;margin-bottom:10px;">
							<div style="font-size:14px;color:#475569;line-height:1.7;">
								400 Applewood Crescent, Unit 100<br>
								Vaughan, Ontario L4K 0C3 &nbsp;·&nbsp; Canada<br>
								www.mayerhealth.ca
							</div>
						</td>
						<td style="vertical-align:top;text-align:right;">
							<div style="font-size:36px;font-weight:800;color:#1a3c5e;letter-spacing:3px;line-height:1;">INVOICE</div>
							<?php if (!empty($data->title)): ?>
							<div style="font-size:12px;color:#64748b;margin-top:4px;"><?= htmlspecialchars($data->title) ?></div>
							<?php endif; ?>
							<div style="margin-top:8px;">
								<span style="display:inline-block;padding:3px 10px;border-radius:20px;font-size:11px;font-weight:600;color:#fff;background:<?= $sc ?>;"><?= htmlspecialchars($data->status) ?></span>
							</div>
						</td>
					</tr>
				</table>

				<!-- Divider -->
				<div style="border-top:3px solid #1a3c5e;margin-bottom:20px;"></div>

				<!-- ── Bill To + Invoice Meta ───────────────────────────────── -->
				<table style="width:100%;border-collapse:collapse;margin-bottom:24px;">
					<tr>
						<td style="vertical-align:top;width:52%;">
							<div style="font-size:11px;font-weight:700;letter-spacing:.1em;text-transform:uppercase;color:#64748b;margin-bottom:8px;">Bill To</div>
							<?php if (!empty($data->companyName)): ?>
							<div style="font-size:17px;font-weight:700;color:#0f172a;"><?= htmlspecialchars($data->companyName) ?></div>
							<?php endif; ?>
							<div style="font-size:16px;font-weight:600;color:#1e293b;"><?= htmlspecialchars($data->name) ?></div>
							<?php if (!empty($data->phone)): ?>
							<div style="font-size:14px;color:#475569;margin-top:2px;"><?= htmlspecialchars($data->phone) ?></div>
							<?php endif; ?>
							<?php if (!empty($data->billingAddress)): ?>
							<div style="font-size:14px;color:#475569;line-height:1.6;margin-top:2px;"><?= nl2br(htmlspecialchars($data->billingAddress)) ?></div>
							<?php endif; ?>
						</td>
						<td style="vertical-align:top;">
							<table style="width:100%;border-collapse:collapse;font-size:13px;">
								<tr>
									<td style="padding:4px 12px 4px 0;color:#64748b;font-weight:600;white-space:nowrap;">Invoice #</td>
									<td style="padding:4px 0;font-weight:700;color:#0f172a;"><?= htmlspecialchars($data->invoiceNumber) ?></td>
								</tr>
								<?php if (!empty($data->poNumber)): ?>
								<tr>
									<td style="padding:4px 12px 4px 0;color:#64748b;font-weight:600;">P.O / S.O #</td>
									<td style="padding:4px 0;color:#1e293b;"><?= htmlspecialchars($data->poNumber) ?></td>
								</tr>
								<?php endif; ?>
								<tr>
									<td style="padding:4px 12px 4px 0;color:#64748b;font-weight:600;">Invoice Date</td>
									<td style="padding:4px 0;color:#1e293b;"><?= date('d M Y', strtotime($data->invoiceDate)) ?></td>
								</tr>
								<tr>
									<td style="padding:4px 12px 4px 0;color:#64748b;font-weight:600;">Payment Due</td>
									<td style="padding:4px 0;color:#1e293b;"><?= date('d M Y', strtotime($data->dueDate)) ?></td>
								</tr>
							</table>
						</td>
					</tr>
				</table>

				<!-- ── Services Table ─────────────────────────────────────── -->
				<table style="width:100%;border-collapse:collapse;font-size:13px;margin-bottom:20px;">
					<thead>
					<tr style="background:#1a3c5e;">
						<th style="padding:9px 12px;color:#fff;text-align:left;font-weight:600;">Service / Date</th>
						<th style="padding:9px 12px;color:#fff;text-align:left;font-weight:600;">Caregiver</th>
						<th style="padding:9px 12px;color:#fff;text-align:left;font-weight:600;white-space:nowrap;">Times</th>
						<th style="padding:9px 12px;color:#fff;text-align:center;font-weight:600;">Hrs</th>
						<th style="padding:9px 12px;color:#fff;text-align:right;font-weight:600;">Rate</th>
						<th style="padding:9px 12px;color:#fff;text-align:right;font-weight:600;">Amount</th>
					</tr>
					</thead>
					<tbody>
					<?php
						$totalAmount = 0;
						foreach ($services as $i => $row):
							$totalAmount += $row->billAmount;
							$rowBg = ($i % 2 === 0) ? '#ffffff' : '#f8fafc';
					?>
					<tr style="background:<?= $rowBg ?>;">
						<td style="padding:8px 12px;border-bottom:1px solid #e2e8f0;">
							<div style="font-weight:600;"><?= htmlspecialchars($row->serviceType) ?></div>
							<div style="font-size:11px;color:#64748b;"><?= date('d M Y', strtotime($row->date)) ?></div>
						</td>
						<td style="padding:8px 12px;border-bottom:1px solid #e2e8f0;"><?= htmlspecialchars($row->firstName . ' ' . $row->lastName) ?></td>
						<td style="padding:8px 12px;border-bottom:1px solid #e2e8f0;white-space:nowrap;"><?= date('h:i A', strtotime($row->startTime)) ?> – <?= date('h:i A', strtotime($row->endTime)) ?></td>
						<td style="padding:8px 12px;border-bottom:1px solid #e2e8f0;text-align:center;"><?= $row->hours ?></td>
						<td style="padding:8px 12px;border-bottom:1px solid #e2e8f0;text-align:right;">$<?= number_format($row->billRate, 2) ?></td>
						<td style="padding:8px 12px;border-bottom:1px solid #e2e8f0;text-align:right;font-weight:600;">$<?= number_format($row->billAmount, 2) ?></td>
					</tr>
					<?php endforeach; ?>
					</tbody>
				</table>

				<!-- ── Totals (right-aligned) ─────────────────────────────── -->
				<table style="width:100%;border-collapse:collapse;margin-bottom:24px;">
					<tr>
						<td style="width:55%;vertical-align:top;"></td>
						<td style="vertical-align:top;">
							<table style="width:100%;border-collapse:collapse;font-size:13px;">
								<tr>
									<td style="padding:5px 16px 5px 0;color:#475569;">Subtotal</td>
									<td style="padding:5px 0;text-align:right;font-weight:600;">$<?= number_format($totalAmount, 2) ?></td>
								</tr>
								<tr>
									<td style="padding:5px 16px 5px 0;color:#475569;">
										Tax (HST<?= $data->taxPercentage > 0 ? ' ' . $data->taxPercentage . '%' : '' ?>)
									</td>
									<td style="padding:5px 0;text-align:right;">$<?= number_format($data->taxAmount, 2) ?></td>
								</tr>
								<tr>
									<td colspan="2" style="padding:2px 0;">
										<div style="border-top:1px solid #cbd5e1;margin:4px 0;"></div>
									</td>
								</tr>
								<tr>
									<td style="padding:6px 16px 6px 0;font-weight:700;font-size:14px;color:#0f172a;">Amount Due (CAD)</td>
									<td style="padding:6px 0;text-align:right;font-weight:700;font-size:14px;color:#0f172a;">$<?= number_format($data->total, 2) ?></td>
								</tr>
								<?php if ($data->paidAmount > 0): ?>
								<tr>
									<td style="padding:5px 16px 5px 0;color:#16a34a;font-weight:600;">Payment Received</td>
									<td style="padding:5px 0;text-align:right;color:#16a34a;font-weight:600;">–$<?= number_format($data->paidAmount, 2) ?></td>
								</tr>
								<tr>
									<td colspan="2" style="padding:2px 0;">
										<div style="border-top:2px solid #f59e0b;margin:4px 0;"></div>
									</td>
								</tr>
								<tr style="background:#fff7ed;">
									<td style="padding:7px 16px 7px 8px;font-weight:700;color:#92400e;font-size:13px;">Balance Owing (CAD)</td>
									<td style="padding:7px 8px 7px 0;text-align:right;font-weight:700;color:#dc2626;font-size:14px;">$<?= number_format($data->total - $data->paidAmount, 2) ?></td>
								</tr>
								<?php endif; ?>
							</table>
						</td>
					</tr>
				</table>

				<!-- ── Payment History ────────────────────────────────────── -->
				<?php if (!empty($payments)): ?>
				<div style="border-top:1px solid #e2e8f0;padding-top:16px;">
					<div style="font-size:10px;font-weight:700;letter-spacing:.1em;text-transform:uppercase;color:#64748b;margin-bottom:10px;">Payment History</div>
					<table style="width:100%;border-collapse:collapse;font-size:12px;">
						<thead>
						<tr style="background:#f1f5f9;">
							<th style="padding:6px 10px;text-align:left;border-bottom:2px solid #e2e8f0;font-weight:600;color:#475569;">#</th>
							<th style="padding:6px 10px;text-align:left;border-bottom:2px solid #e2e8f0;font-weight:600;color:#475569;">Date</th>
							<th style="padding:6px 10px;text-align:right;border-bottom:2px solid #e2e8f0;font-weight:600;color:#475569;">Amount (CAD)</th>
							<th style="padding:6px 10px;text-align:left;border-bottom:2px solid #e2e8f0;font-weight:600;color:#475569;">Note</th>
						</tr>
						</thead>
						<tbody>
						<?php foreach ($payments as $i => $p): ?>
						<tr>
							<td style="padding:5px 10px;border-bottom:1px solid #f1f5f9;"><?= $i + 1 ?></td>
							<td style="padding:5px 10px;border-bottom:1px solid #f1f5f9;"><?= date('d M Y', strtotime($p->paidAt)) ?></td>
							<td style="padding:5px 10px;border-bottom:1px solid #f1f5f9;text-align:right;color:#16a34a;font-weight:600;">$<?= number_format($p->amount, 2) ?></td>
							<td style="padding:5px 10px;border-bottom:1px solid #f1f5f9;color:#64748b;"><?= htmlspecialchars($p->note ?: '—') ?></td>
						</tr>
						<?php endforeach; ?>
						</tbody>
					</table>
				</div>
				<?php endif; ?>

			</div><!-- /modal-body -->

			<div class="modal-footer d-flex justify-content-end gap-2">
				<button type="button" class="btn btn-secondary close">Close</button>
				<button type="button" class="btn btn-primary printButton">
					<i data-feather="printer" style="width:13px;height:13px;vertical-align:middle;margin-right:4px;"></i>Print
				</button>
			</div>

		</div>
	</div>
</div>

<script>
$('.close').on('click', function () { $('#remoteModal1').modal('hide'); });

$(document).ready(function () { feather.replace(); });

$('.printButton').on('click', function () {
	var content = document.getElementById('printThis').innerHTML;
	var win = window.open('', '_blank');
	win.document.write(`<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<title>Invoice <?= htmlspecialchars($data->invoiceNumber) ?></title>
	<style>
		* { box-sizing: border-box; margin: 0; padding: 0; }
		body { font-family: 'Segoe UI', Arial, sans-serif; color: #1e293b; background: #fff; padding: 32px 40px; font-size: 13px; }
		img { max-width: 60%; height: auto; margin-top: 0; }
		table { border-collapse: collapse; }
		-webkit-print-color-adjust: exact;
		print-color-adjust: exact;
		@page { margin: 15mm 15mm; size: A4; }
	</style>
</head>
<body onload="window.print(); setTimeout(function(){ window.close(); }, 500);">
${content}
</body>
</html>`);
	win.document.close();
});
</script>
