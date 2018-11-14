<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Subscription;
use App\Models\Store;
use App\Models\Payment;
// untuk menggunakan resize
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image;
// unutk menggunakan auth
use Auth;
// unutk share ke semua view
use View;

class SubscriptionController extends Controller
{
  /**
   * Create a new controller instance.
   *
   * @return void
   */
  public function __construct()
  {
      // middleware
      // $this->middleware('auth');
      // rekening
      $accountNumber = '093482';
      $accountHolderName = 'PT.Zuragan Indonesia';

      // Sharing is caring
      View::share(
        [
          'accountNumber' => $accountNumber,
          'accountHolderName' => $accountHolderName
        ]);
  }

  /**
   * Show the application dashboard.
   *
   * @return \Illuminate\Http\Response
   */

  public function index()
  {

    $data = subscription::all();

      if(count($data) > 0){ //mengecek apakah data kosong atau tidak
          $res['message'] = "Success!";
          $res['values'] = $data;
          return response($res);
      }
      else{
          $res['message'] = "Empty!";
          return response($res);
      }
  }

  // fungsi untuk melihat detail Package subscription
  public function show($id)
  {

    $data = $subscription = subscription::findOrFail($id);

      if($data){ //mengecek apakah data kosong atau tidak
          $res['message'] = "Success!";
          $res['values'] = $data;
          return response($res);
      }
      else{
          $res['message'] = "Empty!";
          return response($res);
      }

  }

  public function buy(Request $request, $id)
  {
    $user = Auth::user();
    $store = store::where('user_id', $user->id)->first();
    $subscription = subscription::findOrFail($id);

    $uniq = rand(1,999);
    $amount = $subscription->price * $request->period - $uniq;
    $oriAmount = $subscription->price * $request->period;

    // mengecek apakah ini sedang extend atau ingin membeli
    if ($store->subscription_id == $id && $store->status == 1 ) {
      // extend
    } else {
      $store->subscription_id = $subscription->id;
      $store->status = 0;
      $store->expire_date = null;
      $store->save();
    }

    $payment = payment::where('store_id', $store->id)
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
        $payment->store_id = $store->id;
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
