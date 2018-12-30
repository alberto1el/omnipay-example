<?php

namespace App\Payment\Gateways;

use Omnipay\Omnipay;

class PaypalExpress
{
    protected $gateway;
    protected $credentials;

    function __construct( $currency = 'USD', $backurls = [
        'return_url' => '/paypal_return',
        'cancel_url' => '/paypal_cancel'
    ]){

        $this->gateway = Omnipay::create( 'PayPal_Express' );

        $this->gateway->initialize([
            'testMode' => env('PAYPAL_SANDBOX'), // Or false when you are ready for live transactions
        ]);
        $this->gateway->setHeaderImageUrl( env('COMPANY_LOGO_MEDIUM') );
        $this->gateway->setLogoImageUrl( env('COMPANY_LOGO_MEDIUM') );

        $this->credentials['username'] = env('PAYPAL_USERNAME');
        $this->credentials['password'] = env('PAYPAL_PASSWORD');
        $this->credentials['signature'] = env('PAYPAL_SIGNATURE');
        $this->credentials['currency'] = $currency;
        $this->credentials['returnurl'] = request()->root().$backurls['return_url'];
        $this->credentials['cancelurl'] = request()->root().$backurls['cancel_url'];

    }

    function purchase($amount, $sale_description){
        return $this->gateway->purchase([
            'username' => $this->credentials['username'],
            'password' => $this->credentials['password'],
            'signature' => $this->credentials['signature'],
            'amount' => $amount,
            'description' => $sale_description,
            'currency' => $this->credentials['currency'],
            'returnUrl' => $this->credentials['returnurl'],
            'cancelUrl' => $this->credentials['cancelurl'],
        ])->send();
    }

    function finishpurchase($token){

        $response = $this->gateway->fetchCheckout([
            'username' => $this->credentials['username'],
            'password' => $this->credentials['password'],
            'signature' => $this->credentials['signature'],
            'token'=> $token
        ])->send();

        $before_complete = $response->getData();

        $finish_purchase = $this->gateway->completePurchase([
            'username' => $this->credentials['username'],
            'password' => $this->credentials['password'],
            'signature' => $this->credentials['signature'],
            'token'=> $token,
            'amount'=> $before_complete['AMT'],
            'currency'=> $before_complete['CURRENCYCODE'],
            'payerid'=> $before_complete['PAYERID'],
        ])->send();

        return $finish_purchase;
    }

}
