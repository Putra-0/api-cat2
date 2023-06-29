<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Hewan;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class TransactionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        try {
            $transactions = Transaction::with('user', 'hewan')->get();
            if (count($transactions) > 0) {
                return response([
                    'status' => true,
                    'message' => 'Retrieve All Success',
                    'data' => $transactions,
                ], 200);
            } else {
                return response([
                    'status' => false,
                    'message' => 'Empty',
                ], 404);
            }
        } catch (\Exception $e) {
            return response([
                'status' => false,
                'message' => 'Retrieve All Failed',
                'data' => $e->getMessage(),
            ], 500);
        } catch (\Errror $e) {
            return response([
                'status' => false,
                'message' => 'Retrieve All Failed',
                'data' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'user_id' => 'required|exists:users,id',
            'hewan_id' => 'required|exists:hewans,id',
            'tanggal_pengambilan' => 'required',
        ]);
        if ($validator->fails()) {
            return response([
                'status' => false,
                'message' => 'Data tidak valid',
                'data' => $validator->errors(),
            ], 400);
        }

        try {
            $transaction = Transaction::create([
                'user_id' => $request->user_id,
                'hewan_id' => $request->hewan_id,
                'tanggal_pengambilan' => $request->tanggal_pengambilan,
                'status' => 'Menunggu Konfirmasi',
                'status_penerimaan' => 'Belum Diterima',
            ]);

            Hewan::where('id', $request->hewan_id)->update([
                'status' => 'Sudah Dibooking',
            ]);
            return response([
                'status' => true,
                'message' => 'Create Transaction Success',
                'data' => $transaction,
            ], 200);
        } catch (\Exception $e) {
            return response([
                'status' => false,
                'message' => 'Create Transaction Failed',
                'data' => $e->getMessage(),
            ], 500);
        } catch (\Errror $e) {
            return response([
                'status' => false,
                'message' => 'Create Transaction Failed',
                'data' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($user_id, $transaction_id)
{
    try {
        $transaction = Transaction::with('hewan', 'user')->where('id', $transaction_id)->where('user_id', $user_id)->first();
        if (!empty($transaction)) {
            return response([
                'status' => true,
                'message' => 'Retrieve Transaction Success',
                'data' => $transaction,
            ], 200);
        } else {
            return response([
                'status' => false,
                'message' => 'Transaction Not Found',
            ], 404);
        }
    } catch (\Exception $e) {
        return response([
            'status' => false,
            'message' => 'Retrieve Transaction Failed',
            'data' => $e->getMessage(),
        ], 500);
    } catch (\Errror $e) {
        return response([
            'status' => false,
            'message' => 'Retrieve Transaction Failed',
            'data' => $e->getMessage(),
        ], 500);
    }
}

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {

        try {
            $transaction = Transaction::find($id);
            if (!$transaction) {
                return response([
                    'status' => false,
                    'message' => 'Transaction Not Found',
                ], 404);
            }

            $transaction->status = $request->status;
            $transaction->status_penerimaan = $request->status_penerimaan;
            $transaction->save();

            if ($request->status == 'Dibatalkan') {
                Hewan::where('id', $transaction->hewan_id)->update([
                    'status' => 'Tersedia',
                ]);
            }

            if ($request->status == 'Di Konfirmasi') {
                Hewan::where('id', $transaction->hewan_id)->update([
                    'status' => 'Sudah Dibooking',
                ]);
            }

            if ($request->status_penerimaan == 'Diterima') {
                Hewan::where('id', $transaction->hewan_id)->update([
                    'status' => 'Tidak Tersedia',
                ]);
            }

            return response([
                'status' => true,
                'message' => 'Update Transaction Success',
                'data' => $transaction,
            ], 200);
        } catch (\Exception $e) {
            return response([
                'status' => false,
                'message' => 'Update Transaction Failed',
                'data' => $e->getMessage(),
            ], 500);
        } catch (\Errror $e) {
            return response([
                'status' => false,
                'message' => 'Update Transaction Failed',
                'data' => $e->getMessage(),
            ], 500);
        }

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {

    }
}
