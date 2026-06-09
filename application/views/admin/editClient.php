<div class="modal" id="modal-default" style="display:block;overflow:auto;">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <div>
                    <h5 class="modal-title">Update Client Details</h5>
                    <small class="text-white-50"><?= htmlspecialchars($data->name) ?></small>
                </div>
                <button type="button" class="btn btn-sm btn-outline-light close">Close</button>
            </div>

            <form action="<?= admin_url('updateClient/') . $data->id ?>" method="post" enctype="multipart/form-data">
                <div class="modal-body p-4">

                    <!-- Client Information -->
                    <div class="mb-4">
                        <div class="form-section-label">Client Information</div>
                        <div class="row g-3">
                            <div class="col-md-4">
                                <label for="name" class="form-label">Client Name <span class="text-danger">*</span></label>
                                <input autocomplete="off" type="text" class="form-control" name="name" id="name" value="<?= htmlspecialchars($data->name) ?>" required>
                            </div>
                            <div class="col-md-3">
                                <label for="phone" class="form-label">Phone Number <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" name="phone" id="phone" value="<?= htmlspecialchars($data->phone) ?>" required>
                            </div>
                            <div class="col-md-5">
                                <label for="address" class="form-label">Address <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" name="address" id="address" value="<?= htmlspecialchars($data->address) ?>" required>
                            </div>
                        </div>
                    </div>

                    <!-- Referral & Billing -->
                    <div class="mb-4">
                        <div class="form-section-label">Referral &amp; Billing</div>
                        <div class="row g-3">
                            <div class="col-md-5">
                                <label for="referralSource" class="form-label">Referral Source <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" name="referralSource" id="referralSource" value="<?= htmlspecialchars($data->referralSource) ?>" required>
                            </div>
                            <div class="col-md-3">
                                <label for="referralDate" class="form-label">Referral Date <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" name="referralDate" id="referralDate" value="<?= $data->referralDate ? date('d M Y', strtotime($data->referralDate)) : '' ?>" required>
                            </div>
                            <div class="col-md-4">
                                <label for="dol" class="form-label">DOL</label>
                                <input type="text" class="form-control" name="dol" id="dol" value="<?= htmlspecialchars($data->dol) ?>">
                            </div>
                        </div>
                        <div class="row g-3 mt-1">
                            <div class="col-md-7">
                                <label for="billingAddress" class="form-label">Billing Address <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" name="billingAddress" id="billingAddress" value="<?= htmlspecialchars($data->billingAddress) ?>" required>
                            </div>
                            <div class="col-md-5">
                                <label for="companyName" class="form-label">Company Name <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" name="companyName" id="companyName" value="<?= htmlspecialchars($data->companyName) ?>" required>
                            </div>
                        </div>
                    </div>

                    <!-- Adjustor Information -->
                    <div class="mb-4">
                        <div class="form-section-label">Adjustor Information</div>
                        <div class="row g-3">
                            <div class="col-md-3">
                                <label for="adjustorName" class="form-label">Adjustor Name <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" name="adjustorName" id="adjustorName" value="<?= htmlspecialchars($data->adjustorName) ?>" required>
                            </div>
                            <div class="col-md-3">
                                <label for="adjustorEmail" class="form-label">Adjustor Email</label>
                                <input type="email" class="form-control" name="adjustorEmail" id="adjustorEmail" value="<?= htmlspecialchars($data->adjustorEmail) ?>">
                            </div>
                            <div class="col-md-3">
                                <label for="adjustorPhone" class="form-label">Adjustor Phone <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" name="adjustorPhone" id="adjustorPhone" value="<?= htmlspecialchars($data->adjustorPhone) ?>" required>
                            </div>
                            <div class="col-md-3">
                                <label for="adjustorFax" class="form-label">Adjustor Fax</label>
                                <input type="text" class="form-control" name="adjustorFax" id="adjustorFax" value="<?= htmlspecialchars($data->adjustorFax) ?>">
                            </div>
                        </div>
                    </div>

                    <!-- Financial Details -->
                    <div>
                        <div class="form-section-label">Financial Details</div>
                        <div class="row g-3">
                            <div class="col-md-4">
                                <label for="budget" class="form-label">Budget / Form 1 <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <span class="input-group-text">$</span>
                                    <input type="number" step="any" min="0" class="form-control" name="budget" id="budget" value="<?= $data->budget ?>" required>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <label for="billRate" class="form-label">Bill Rate <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <span class="input-group-text">$</span>
                                    <input type="number" step="any" min="0" class="form-control" name="billRate" id="billRate" value="<?= $data->billRate ?>" required>
                                    <span class="input-group-text">/ hr</span>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <label for="budgetedHours" class="form-label">Budgeted Hours</label>
                                <div class="input-group">
                                    <input type="number" step="any" min="0" class="form-control" name="budgetedHours" id="budgetedHours" value="<?= $data->budgetedHours ?>" readonly>
                                    <span class="input-group-text">hrs</span>
                                </div>
                                <div class="form-text text-muted" style="font-size:11px;">Calculated from Budget ÷ Bill Rate</div>
                            </div>
                        </div>
                    </div>

                </div>

                <div class="modal-footer d-flex justify-content-end gap-2">
                    <button type="button" class="btn btn-secondary close">Close</button>
                    <button type="submit" class="btn btn-primary">
                        <i data-feather="save" style="width:13px;height:13px;vertical-align:middle;margin-right:4px;"></i>Update Client
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
$('.close').on('click', function () { $('#remoteModal1').modal('hide'); });
$(document).ready(function () { feather.replace(); });
$('#referralDate').flatpickr({ dateFormat: 'd M Y' });
$('#budget, #billRate').on('input', function () {
    var budget = parseFloat($('#budget').val());
    var billRate = parseFloat($('#billRate').val());
    if (budget > 0 && billRate > 0) {
        $('#budgetedHours').val((budget / billRate).toFixed(2));
    }
});
</script>
