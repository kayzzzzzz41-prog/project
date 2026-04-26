<?php

namespace App\Http\Controllers;

use App\Models\Contract;
use App\Models\KaspiPayment;
use Illuminate\Http\Request;

class KaspiController extends Controller
{
    public function handle(Request $request)
    {
        $command = $request->get('command');
        $txnId   = $request->get('txn_id');
        $account = $request->get('account');
        $sum     = $request->get('sum');
        $txnDate = $request->get('txn_date');

        return match ($command) {
            'check' => $this->check($txnId, $account),
            'pay'   => $this->pay($txnId, $account, $sum, $txnDate),
            default => $this->jsonResponse($txnId, null, 0, 5, 'Неизвестная команда'),
        };
    }

    private function check($txnId, $account)
    {
        $contract = Contract::where('contract_number', $account)->first();

        if (!$contract) {
            return $this->jsonResponse($txnId, null, 0, 1, 'Договор не найден');
        }

        if ($contract->is_paid) {
            return $this->jsonResponse($txnId, null, 0, 3, 'Договор уже оплачен');
        }

        return $this->jsonResponse($txnId, null, $contract->amount, 0, 'OK', [
            'name'    => $contract->name,
            'address' => $contract->address,
        ]);
    }

    private function pay($txnId, $account, $sum, $txnDate)
    {
        // Проверка дублирующегося платежа
        $existing = KaspiPayment::where('txn_id', $txnId)->first();
        if ($existing) {
            return $this->jsonResponse(
                $txnId, $existing->prv_txn_id,
                $existing->sum, $existing->result, 'Дубликат'
            );
        }

        $contract = Contract::where('contract_number', $account)->first();

        if (!$contract) {
            return $this->jsonResponse($txnId, null, 0, 1, 'Договор не найден');
        }

        if ($contract->is_paid) {
            return $this->jsonResponse($txnId, null, 0, 3, 'Уже оплачен');
        }

        // Сохраняем платёж
        $payment = KaspiPayment::create([
            'txn_id'     => $txnId,
            'account'    => $account,
            'sum'        => $sum,
            'txn_date'   => $txnDate,
            'result'     => 0,
            'command'    => 'pay',
            'prv_txn_id' => uniqid(),
        ]);

        // Помечаем договор оплаченным
        $contract->update(['is_paid' => true]);

        return $this->jsonResponse($txnId, $payment->prv_txn_id, $sum, 0, 'OK');
    }

    private function jsonResponse($txnId, $prvTxnId, $sum, $result, $comment, $fields = [])
    {
        $response = [
            'txn_id'     => $txnId,
            'prv_txn_id' => $prvTxnId,
            'result'     => $result,
            'sum'        => number_format((float)$sum, 2, '.', ''),
            'comment'    => $comment,
        ];

        if (!empty($fields)) {
            $response['fields'] = $fields;
        }

        return response()->json($response, 200, [], JSON_UNESCAPED_UNICODE);
    }
}