<?php

namespace App\Http\Controllers\API\V1;

use App\Http\Resources\BaseResource;
use App\Http\Resources\TransactionResource;
use Illuminate\Http\Request;
use App\Models\Transaction;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class TransactionController extends Controller
{
    public function index()
    {
        try {
            $transactions = Transaction::with('user', 'product')->get();
            return new BaseResource(true, $transactions, 'Transactions retrieved successfully', null);
        } catch (\Exception $e) {
            return new BaseResource(false, null, 'Failed to retrieve transactions', $e->getMessage());
        }
    }

    public function show($id)
    {
        try {
            $transaction = Transaction::with('user', 'product')->find($id);

            if (!$transaction) {
                return new BaseResource(false, null, 'Transaction not found', null);
            }

            return new BaseResource(true, new TransactionResource($transaction), 'Transaction retrieved successfully', null);
        } catch (\Exception $e) {
            return new BaseResource(false, null, 'Failed to retrieve transaction', $e->getMessage());
        }
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'user_id' => 'required|exists:users,id',
            'product_id' => 'required|exists:products,id',
            'transaction_type' => 'required|string|max:255',
            'payment_method' => 'required|string|max:255',
            'payment_id' => 'required|string|max:255',
            'transaction_date' => 'required|date',
            'amount' => 'required|integer',
            'quantity' => 'required|integer',
            'product_price' => 'required|numeric',
        ]);

        if ($validator->fails()) {
            return new BaseResource(false, null, 'Validation Failed', $validator->errors());
        }

        try {
            $transaction = Transaction::create([
                'user_id' => Auth::Id(),
                'product_id' => $request->product_id,
                'transaction_type' => $request->transaction_type,
                'payment_method' => $request->payment_method,
                'payment_id' => $request->payment_id,
                'transaction_date' => $request->transaction_date,
                'amount' => $request->amount,
                'quantity' => $request->quantity,
                'product_price' => $request->product_price,
            ]);
            return new BaseResource(true, new TransactionResource($transaction), 'Transaction created successfully', null);
        } catch (\Exception $e) {
            return new BaseResource(false, null, 'Failed to create transaction', $e->getMessage());
        }
    }

    public function update(Request $request, $id)
    {
        $transaction = Transaction::find($id);

        if (!$transaction) {
            return new BaseResource(false, null, 'Transaction not found', null);
        }

        $validator = Validator::make($request->all(), [
            'transaction_type' => 'required|string|max:255',
            'payment_method' => 'required|string|max:255',
            'payment_id' => 'required|string|max:255',
            'transaction_date' => 'required|date',
            'amount' => 'required|integer',
            'quantity' => 'required|integer',
            'product_price' => 'required|numeric',
        ]);

        if ($validator->fails()) {
            return new BaseResource(false, null, 'Validation Failed', $validator->errors());
        }

        try {
            $transaction->update([
                'transaction_type' => $request->transaction_type,
                'payment_method' => $request->payment_method,
                'payment_id' => $request->payment_id,
                'transaction_date' => $request->transaction_date,
                'amount' => $request->amount,
                'quantity' => $request->quantity,
                'product_price' => $request->product_price,
            ]);
            return new BaseResource(true, new TransactionResource($transaction), 'Transaction updated successfully', null);
        } catch (\Exception $e) {
            return new BaseResource(false, null, 'Failed to update transaction', $e->getMessage());
        }
    }

    public function destroy($id)
    {
        $transaction = Transaction::find($id);

        if (!$transaction) {
            return new BaseResource(false, null, 'Transaction not found', null);
        }

        try {
            $transaction->delete();
            return new BaseResource(true, null, 'Transaction deleted successfully', null);
        } catch (\Exception $e) {
            return new BaseResource(false, null, 'Failed to delete transaction', $e->getMessage());
        }
    }
}
