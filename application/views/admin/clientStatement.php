<?php
$totalInvoiced  = 0;
$totalPaid      = 0;
$totalRemaining = 0;
if ($invoices) {
    foreach ($invoices as $inv) {
        $totalInvoiced  += $inv->total;
        $totalPaid      += $inv->paidAmount;
        $totalRemaining += $inv->dueAmount;
    }
}
?>

<div class="d-flex align-items-center justify-content-between mb-4 no-print">
    <div>
        <a href="<?= admin_url('clients') ?>" class="btn btn-sm btn-outline-secondary me-2">
            <i data-feather="arrow-left" style="width:14px;height:14px"></i> Back to Clients
        </a>
    </div>
    <button onclick="printStatement()" class="btn btn-primary btn-sm">
        <i data-feather="printer" style="width:14px;height:14px" class="me-1"></i> Print Statement
    </button>
</div>

<div id="statementContent">
    <!-- Header -->
    <div class="card mb-4 border-0" style="background:linear-gradient(135deg,#1e3a5f 0%,#2563EB 100%);color:#fff;border-radius:14px;">
        <div class="card-body p-4">
            <div class="row align-items-center">
                <div class="col-sm-7">
                    <p class="mb-1 opacity-75" style="font-size:12px;letter-spacing:1px;text-transform:uppercase">Statement of Account</p>
                    <h3 class="mb-1 fw-bold"><?= htmlspecialchars($client->name) ?></h3>
                    <?php if ($client->companyName): ?>
                        <p class="mb-1 opacity-90"><?= htmlspecialchars($client->companyName) ?></p>
                    <?php endif; ?>
                    <p class="mb-0 opacity-75" style="font-size:13px">
                        <?= htmlspecialchars($client->billingAddress ?: $client->address) ?><br>
                        <?= htmlspecialchars($client->phone) ?>
                    </p>
                </div>
                <div class="col-sm-5 text-md-end mt-3 mt-md-0">
                    <img src="<?= base_url('assets/images/logo.png') ?>" height="75" alt="<?= COMPANY ?>" class="mb-2" style="border-radius: 10px">
                    <p class="mb-0 opacity-75" style="font-size:12px">Generated: <?= date('d F Y') ?></p>
                </div>
            </div>
        </div>
    </div>

    <!-- Summary KPI row -->
    <div class="row g-3 mb-4">
        <div class="col-sm-4">
            <div class="card border-0 h-100" style="background:#EFF6FF;border-radius:12px;">
                <div class="card-body p-3">
                    <p class="mb-1" style="font-size:11px;font-weight:600;letter-spacing:.6px;text-transform:uppercase;color:#2563EB">Total Invoiced</p>
                    <h3 class="mb-0 fw-bold" style="color:#1e3a5f">$<?= number_format($totalInvoiced, 2) ?></h3>
                    <p class="mb-0 mt-1" style="font-size:12px;color:#64748B"><?= count($invoices) ?> invoice<?= count($invoices) != 1 ? 's' : '' ?></p>
                </div>
            </div>
        </div>
        <div class="col-sm-4">
            <div class="card border-0 h-100" style="background:#ECFDF5;border-radius:12px;">
                <div class="card-body p-3">
                    <p class="mb-1" style="font-size:11px;font-weight:600;letter-spacing:.6px;text-transform:uppercase;color:#059669">Total Paid</p>
                    <h3 class="mb-0 fw-bold" style="color:#064e3b">$<?= number_format($totalPaid, 2) ?></h3>
                    <?php $pct = $totalInvoiced > 0 ? round(($totalPaid / $totalInvoiced) * 100) : 0; ?>
                    <p class="mb-0 mt-1" style="font-size:12px;color:#64748B"><?= $pct ?>% of total</p>
                </div>
            </div>
        </div>
        <div class="col-sm-4">
            <div class="card border-0 h-100" style="background:<?= $totalRemaining > 0 ? '#FEF2F2' : '#ECFDF5' ?>;border-radius:12px;">
                <div class="card-body p-3">
                    <p class="mb-1" style="font-size:11px;font-weight:600;letter-spacing:.6px;text-transform:uppercase;color:<?= $totalRemaining > 0 ? '#DC2626' : '#059669' ?>">
                        Balance Owing
                    </p>
                    <h3 class="mb-0 fw-bold" style="color:<?= $totalRemaining > 0 ? '#7f1d1d' : '#064e3b' ?>">
                        $<?= number_format($totalRemaining, 2) ?>
                    </h3>
                    <p class="mb-0 mt-1" style="font-size:12px;color:#64748B">
                        <?= $totalRemaining <= 0 ? 'Account clear' : 'Outstanding balance' ?>
                    </p>
                </div>
            </div>
        </div>
    </div>

    <!-- Invoice table -->
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0 fw-semibold">Invoice History</h5>
        </div>
        <div class="card-body p-0">
            <?php if ($invoices): ?>
            <div class="table-responsive">
                <table class="table mb-0" id="statementTable">
                    <thead>
                    <tr>
                        <th>Invoice #</th>
                        <th>Date</th>
                        <th>Due Date</th>
                        <th>PO Number</th>
                        <th class="text-end">Total Due</th>
                        <th class="text-end">Paid</th>
                        <th class="text-end">Balance</th>
                        <th class="text-center">Status</th>
                        <th class="text-center no-print">Print</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php foreach ($invoices as $inv): ?>
                    <tr>
                        <td class="fw-semibold"><?= htmlspecialchars($inv->invoiceNumber) ?></td>
                        <td><?= date('d M Y', strtotime($inv->invoiceDate)) ?></td>
                        <td><?= $inv->dueDate ? date('d M Y', strtotime($inv->dueDate)) : '—' ?></td>
                        <td><?= htmlspecialchars($inv->poNumber ?: '—') ?></td>
                        <td class="text-end">$<?= number_format($inv->total, 2) ?></td>
                        <td class="text-end" style="color:#059669">$<?= number_format($inv->paidAmount, 2) ?></td>
                        <td class="text-end fw-semibold" style="color:<?= $inv->dueAmount > 0 ? '#DC2626' : '#059669' ?>">
                            $<?= number_format($inv->dueAmount, 2) ?>
                        </td>
                        <td class="text-center">
                            <?php
                            $s = $inv->status;
                            if ($s === 'Fully Paid'):
                            ?><span class="badge" style="background:#ECFDF5;color:#059669;font-size:11px">Fully Paid</span>
                            <?php elseif ($s === 'Partial Paid'): ?>
                            <span class="badge" style="background:#FFFBEB;color:#D97706;font-size:11px">Partial Paid</span>
                            <?php else: ?>
                            <span class="badge" style="background:#EFF6FF;color:#2563EB;font-size:11px">Sent</span>
                            <?php endif; ?>
                        </td>
                        <td class="text-center no-print">
                            <a href="javascript:void(0);" onclick="loadPopup('<?= admin_url('printInvoice/') . $inv->id ?>')" class="btn btn-xs btn-outline-secondary">
                                <i data-feather="printer" style="width:12px;height:12px"></i>
                            </a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                    </tbody>
                    <tfoot>
                    <tr style="background:#F8FAFC;font-weight:700">
                        <td colspan="4" class="text-end">Totals</td>
                        <td class="text-end">$<?= number_format($totalInvoiced, 2) ?></td>
                        <td class="text-end" style="color:#059669">$<?= number_format($totalPaid, 2) ?></td>
                        <td class="text-end" style="color:<?= $totalRemaining > 0 ? '#DC2626' : '#059669' ?>">
                            $<?= number_format($totalRemaining, 2) ?>
                        </td>
                        <td colspan="2"></td>
                    </tr>
                    </tfoot>
                </table>
            </div>
            <?php else: ?>
            <div class="text-center py-5">
                <i data-feather="file-text" style="width:48px;height:48px;color:#CBD5E1" class="mb-3"></i>
                <p class="text-muted mb-0">No invoices found for this client.</p>
            </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<script>
function printStatement() {
    var content = document.getElementById('statementContent').innerHTML;
    var win = window.open('', '_blank');
    win.document.write(`<!DOCTYPE html>
<html>
<head>
<title>Statement — <?= htmlspecialchars($client->name) ?></title>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
<style>
  body{font-family:'Roboto',sans-serif;font-size:13px;color:#0f172a;padding:24px}
  .no-print{display:none!important}
  table{width:100%;border-collapse:collapse}
  th,td{padding:8px 12px;border-bottom:1px solid #e2e8f0;vertical-align:middle}
  thead th{background:#f1f5f9;font-size:11px;font-weight:700;text-transform:uppercase;letter-spacing:.5px;color:#64748b}
  tfoot tr{background:#f8fafc;font-weight:700}
  .text-end{text-align:right}
  .text-center{text-align:center}
  .fw-bold{font-weight:700}
  .fw-semibold{font-weight:600}
  .badge{padding:3px 8px;border-radius:4px;font-size:11px;font-weight:600}
  @media print{
    @page{margin:1.5cm}
    tr{page-break-inside:avoid}
  }
</style>
</head>
<body onload="window.print();setTimeout(function(){window.close();},200)">
${content}
</body>
</html>`);
    win.document.close();
}
</script>
