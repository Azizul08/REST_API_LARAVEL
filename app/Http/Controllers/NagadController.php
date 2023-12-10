<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Karim007\LaravelNagad\Facade\NagadPayment;
use Karim007\LaravelNagad\Facade\NagadRefund;

class NagadController extends Controller
{
    public function pay()
	{
		$amount = 1000;
		$trx_id = uniqid();
		//if you have multipule/dynamic callback url then uncomment bellow line and use dynamic callbackurl
		//otherwise don't do anything
		//config(['nagad.callback_url' => env('NAGAD_CALLBACK_URL')]);
		
		$response = NagadPayment::create($amount, $trx_id); // 1st parameter is amount and 2nd is unique invoice number

		//$response = NagadPayment::create($amount, $trx_id,1); // additional last parameter for manage difference account

		if (isset($response) && $response->status == "Success"){
			return redirect()->away($response->callBackUrl);
		}
		return redirect()->back()->with("error-alert", "Invalid request try again after few time later");
	}
	
	public function callback(Request $request)
	{
		if (!$request->status && !$request->order_id) {
			return response()->json([
				"error" => "Not found any status"
			], 500);
		}

		if (config("nagad.response_type") == "json") {
			return response()->json($request->all());
		}

		$verify = NagadPayment::verify($request->payment_ref_id); // $paymentRefId which you will find callback URL request parameter

		if (isset($verify->status) && $verify->status == "Success") {
			return $this->success($verify->orderId);
		} else {
			return $this->fail($verify->orderId);
		}

	}
	
	public function refund($paymentRefId)
	{
		$refundAmount=1000;
		$verify = NagadRefund::refund($paymentRefId,$refundAmount);
		//$verify = NagadRefund::refund($paymentRefId,$refundAmount,'','sss',1); last parameter for manage account

		if (isset($verify->status) && $verify->status == "Success") {
			return $this->success($verify->orderId);
		} else {
			return $this->fail($verify->orderId);
		}
	}
	
	public function success($transId)
	{
		return view("nagad::success", compact('transId'));
	}
	
	public function fail($transId)
	{
		return view("nagad::failed", compact('transId'));
	}
}
