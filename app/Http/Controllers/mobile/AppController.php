<?php

namespace App\Http\Controllers\mobile;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\PaymentHistory;
use App\Models\Product;

class AppController extends Controller
{
    public function products(){
        $data = Product::get();
        return $this->respondSuccess($data);
    }

    public function transaction(){

        $data = Order::select('id', 'status', 'total', 'created_at')
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return $this->respondSuccess($data);
    }

    public function transaction_detail($id){
        if (!$id){
            return $this->respondError(204, 'field is empty');
        }

        $order = Order::where('id', $id)
            ->first();

        $product = Product::where('id', $order->product_id)
            ->select('id', 'title', 'cover')
            ->first();

        $payment = null;

        if ($order->status == 'done' && $order->payment_id){
            $payment = PaymentHistory::where('id', $order->payment_id)
                ->first();
        }

        $data = [
            'product' => $product,
            'payment_history' => $payment
        ];

        return $this->respondSuccess($data);
    }
}
