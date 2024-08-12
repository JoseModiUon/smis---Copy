<?php

/** @var yii\web\View $this */

?>
<section>
    <table class="table table-striped table-bordered">
        <thead>
        <tr>
            <th scope="col"></th>
            <th scope="col">#</th>
            <th scope="col">Academic Year</th>
        </tr>
        </thead>
        <tbody>
        <tr>
            <th scope="row" class="fs-bolder text-danger" style="width: 15px;"><i class="bi bi-dash-square-dotted"></i>
            </th>
            <th scope="row">1</th>
            <td>2022/2023</td>
        </tr>
        <tr>
            <td colspan="4">
                <table class="table mb-0">
                    <thead>
                    <tr class="text-center">
                        <th scope="col">Txn/Receipt No.</th>
                        <th scope="col">Date</th>
                        <th scope="col">Description</th>
                        <th scope="col">Debit</th>
                        <th scope="col">Credit</th>
                        <th scope="col">Balance</th>
                    </tr>
                    </thead>
                    <tbody class="text-center">
                    <tr>
                        <th scope="row">A-XXXX</th>
                        <td>02-Jul-2022</td>
                        <td>Fee Payment Sem 1</td>
                        <td class="text-center">-</td>
                        <td class="text-end">KES 120,500.00</td>
                        <td class="text-end">KES -120,500.00</td>
                    </tr>
                    <tr>
                        <th scope="row">NDU/SEM1/2022</th>
                        <td>06-Jul-2022</td>
                        <td>Fee Payable Sem 1 2022</td>
                        <td class="text-end">KES 119,500.00</td>
                        <td class="text-center">-</td>
                        <td class="text-end">KES -1,000.00</td>
                    </tr>
                    <tr>
                        <th scope="row">B-TXN2</th>
                        <td>13-Jul-2022</td>
                        <td>Insurance</td>
                        <td class="text-center">-</td>
                        <td class="text-end">KES 25,700.00</td>
                        <td class="text-end">KES -26,700.00</td>
                    </tr>
                    <tr>
                        <th scope="row">NDU/INS/2022-2023</th>
                        <td>13-Jul-2022</td>
                        <td>Student Insurance</td>
                        <td class="text-end">KES 26,000.00</td>
                        <td class="text-center">-</td>
                        <td class="text-end">KES -700.00</td>
                    </tr>
                    </tbody>
                    <tfoot>
                    <tr>
                        <th colspan="3">Totals</th>
                        <th class="text-end">KES 145,500.00</th>
                        <th class="text-end">KES 146,200.00</th>
                        <th class="text-end">KES -700.00</th>
                    </tr>
                    <tr>
                        <th colspan="2"></th>
                        <th class="fw-bold fs-5 text-success">Balance: KES -700</th>
                        <th colspan="4"></th>
                    </tr>

                    </tfoot>
                </table>
            </td>
        </tr>
        <tr>
            <th scope="row"><i class="bi bi-plus-square-dotted"></i></th>
            <th scope="row">2</th>
            <td>2021/2022</td>
        </tr>
        </tbody>
    </table>
</section>