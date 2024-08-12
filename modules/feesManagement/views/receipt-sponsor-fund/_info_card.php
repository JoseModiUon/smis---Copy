<div class="row ml-3">
    <div class="col-md-12">
        <div>
            <span style="display: block;">

                <span class="fw-bold"> Fund Amount:</span> <span class="fst-italic"><?= number_format($receiptSponsorFund->amount, 2) ?></span>
                <span class="fw-bold"> Total Allocated:</span> <span class="fst-italic"><?= number_format($receiptSponsorFund->findAllocated(), 2) ?></span>
            </span>
            <span style="display: block;">
                <span class="fw-bold"> Balance:</span> <span class="fst-italic"><?= number_format($receiptSponsorFund->findBalance(), 2) ?></span>

            </span>
            <span style="display: block;">
                <span class="fw-bold"> No. of Beneficiaries:</span> <span class="fst-italic"><?= $receiptSponsorFund->no_of_beneficiaries ?></span>
                <span class="fw-bold"> Allocated Beneficiaries:</span> <span class="fst-italic"><?= $receiptSponsorFund->getTotalBeneficiaries() ?> </span>
        </div>
    </div>
</div>