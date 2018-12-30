<?php

namespace App\Http\Controllers;

use App\Payment\Gateways\AuthorizeNet;
use App\Payment\Gateways\PaypalExpress;
use Session;
use MessageBag;
use Illuminate\Http\Request;

class ChargeController extends Controller
{

    public function store(Request $request)
    {
        $this->validate($request,[
            'name' => 'required',
            'email' => 'required|email',
            'payment_method' => 'required|in:card,paypal',
        ]);

        $total = 20;
        $currency = 'USD';

        switch($request['payment_method']){
            case 'card':
                try {
                    if(!$request['save_info']){
                        $authorize_gateway = new AuthorizeNet('AuthorizeNet_AIM');
                        $response = $authorize_gateway->single_charge(
                            null,
                            null,
                            null,
                            null,
                            floatval($total),
                            $request['dataDescriptor'], $request['dataValue']
                        );
                        if ($response->isSuccessful()) {
                            $trans_id = json_decode($response->getTransactionReference(), true)['transId'];
                            return [
                                'status' => 'success',
                                'transaction_id' => $trans_id,
                                'transaction_response' => $response->getTransactionReference()
                            ];
                        } else {
                            return [
                                'status' => 'error'
                            ];
                        }
                    }
                    else{
                        $authorize_gateway = new AuthorizeNet('AuthorizeNet_CIM');
                        $response = $authorize_gateway->createCustomerProfile(
                            null,
                            null,
                            null,
                            null,
                            $request['name'],
                            $request['last_name'],
                            $request['address'],
                            $request['city'],
                            $request['state'],
                            $request['zip'],
                            $request['email'],
                            rand(1,1000),
                            $request['dataDescriptor'], $request['dataValue']
                        );
                        if ($response->isSuccessful()) {
                            $data = $response->getData();
                            $response_sale = $authorize_gateway->charge_a_profile(
                                $data['paymentProfile']['customerProfileId'],
                                $data['paymentProfile']['customerPaymentProfileId'],
                                $total,
                                'Sale'
                            );
                            $trans_id = json_decode($response_sale->getTransactionReference(), true)['transId'];
                            return [
                                'status' => 'success',
                                'transaction_id' => $trans_id,
                                'transaction_response' => $response_sale->getTransactionReference()
                            ];
                        } else {
                            return [
                                'status' => 'error'
                            ];
                        }
                    }
                } catch (\Exception $e) {
                    return [
                        'status' => 'error'
                    ];
                }

            break;
            case 'paypal':
                $paypal_gateway = new PaypalExpress( $currency );
                $response = $paypal_gateway->purchase(
                    ( floor(floatval($total) * 100)/100 ),
                    'Purchase test'
                );
                if ($response->isRedirect()) {
                    // session()->save();
                    // SAVE THE SESSION BEFORE MOVING TO AN OUTSIDE URL
                    return $response->redirect();
                }
                return redirect('/');
            break;
        }
    }

    function paypal_return(Request $request){
        $currency = 'USD';
        $return_token = $request['token'];
        $gateway = new PaypalExpress( $currency );
        $response = $gateway->finishPurchase( $return_token );
        $transaction = $response->getData();

        if(isset($transaction['ACK']) && $transaction['ACK']=='Failure'){
            $mensaje = $transaction['L_SHORTMESSAGE0'];
            $message_bag = new MessageBag;
            $message_bag->add('payment_error', 'Payment not accepted, Try again | '.$mensaje);
            return redirect('/')->withInput()->withErrors($message_bag->all());
        }elseif( !isset($transaction['ACK']) ){
            $mensaje = $transaction['L_SHORTMESSAGE0'];
            $your_message_bag = new MessageBag;
            $your_message_bag->add('no_cart', 'Payment not accepted, Try again | '.$mensaje);
            return redirect('/')->withInput()->withErrors($your_message_bag->all());
        }

        session(['transaction_id' => $transaction['PAYMENTINFO_0_TRANSACTIONID']]);
        session(['transaction_response' => json_encode($transaction) ]);

        return redirect('/success');

    }

}
