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

    public function updatePayment(Request $request, $transactionHeaderId) {
        if ($request->ajax()){
            $transactionHeader = TransactionHeader::where('id', $transactionHeaderId)->first();
            $transactionHeader->is_paid = 1;
            $transactionHeader->save();
            return ['transactionData' => $transactionHeader];
        }
    }

    public function validatePayment(StorePayment $request) {
        if ($request->ajax()){

            $sanitized = $request->getSanitized();
            $transactionHeader = TransactionHeader::where('id', $request->get('transaction_header_id'))->first();
            $totalBalance = floatval($transactionHeader->balance_raw) - floatval($sanitized['payment_amount']);
            if ($totalBalance < 0) {
                abort(422, "Payment amount exceeds outstanding balance. " . $totalBalance);
            }
            if ($sanitized['payment_amount'] <= 0) {
                abort(422, "Payment must be above zero.");
            }
            if ($sanitized['type'] == 'Cash') {
                $sanitized['account_name'] = '';
                $sanitized['reference_number'] = '';
            }
            $payment = Payments::create($sanitized);
            return ['message' => $sanitized];
        }
    }

    public function destroy(Request $request, Payments $payment)
    {
        $payment->delete();

        if ($request->ajax()) {
            return response(['message' => trans('brackets/admin-ui::admin.operation.succeeded')]);
        }

        return redirect()->back();
    }
}
