<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\EmployeeController;
use App\Http\Requests\Admin\Payments\StorePayment;
use App\Models\Payments;
use App\Models\TransactionHeader;
use Illuminate\Http\Request;

class PaymentController extends EmployeeController
{
    //
    public function getTransactionData(Request $request, $transactionHeaderId) {
        if ($request->ajax()){
            $transactionHeader = TransactionHeader::where('id', $transactionHeaderId)->first();
            return ['transactionData' => $transactionHeader];
        }
    }

    public function validatePayment(StorePayment $request) {
        if ($request->ajax()){

            $sanitized = $request->getSanitized();
            $payment = Payments::create($sanitized);
            return ['message' => $sanitized];
        }
    }
}
