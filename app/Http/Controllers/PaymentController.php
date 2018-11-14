<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Organization;
use App\Models\Subscription;
use App\Models\Payment;

use Auth;

class PaymentController extends Controller
{
  public function buy(Request $request, $id)
  {
    $user = Auth::user();
    $organization = organization::where('user_id', $user->id)->first();
    $subscription = subscription::findOrFail($id);

    $uniq = rand(1,999);
    $amount = $subscription->price * $request->period - $uniq;
    $oriAmount = $subscription->price * $request->period;

    // mengecek apakah ini sedang extend atau ingin membeli
    if ($organization->subscription_id == $id && $organization->status == 1 ) {
      // extend
    } else {
      $organization->subscription_id = $subscription->id;
      $organization->status = 0;
      $organization->expire_date = null;
      $organization->save();
    }

    $payment = payment::where('organization_id', $organization->id)
    ->where('paid', 0)->first();

    if ($payment == null) {
        // pengecekan apakah uniq code sudah ada
        $payment = payment::where('uniq_code', $uniq)
          ->where('paid', 0)->first();
        // perulangan sampai tidak ada yang sama
        while ($payment != null) {
          $uniq = rand(1,999);
          $amount = $subscription->price * $request->period - $uniq;
          $payment = payment::where('uniq_code', $uniq)
            ->where('paid', 0)->first();
        }
        // end batas perulangn pengecekan yang sama
        $payment = new payment;
        $payment->organization_id = $organization->id;
      } else {
        // pengecekan apakah uniq code sudah ada
        $cariPayment = payment::where('uniq_code', $uniq)
          ->where('paid', 0)->first();
        // perulangan sampai tidak ada yang sama
        while ($cariPayment != null) {
          $uniq = rand(1,999);
          $amount = $subscription->price * $request->period - $uniq;
          $cariPayment = payment::where('uniq_code', $uniq)
            ->where('paid', 0)->first();
        }
      }

      $payment->proof = null;
      $payment->uniq_code = $uniq;
      $payment->amount = $amount;
      $payment->subscription_id = $id;
      $payment->period = $request->period;
      $success = $payment->save();

      $data = $payment;

        if($success){ //mengecek apakah data kosong atau tidak
            $res['message'] = "Success!";
            $res['values'] = $data;
            return response($res);
        }
        else{
            $res['message'] = "Failed!";
            return response($res);
        }

  }
}
