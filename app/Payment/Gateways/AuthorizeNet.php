<?php

namespace App\Payment\Gateways;

use Omnipay\Omnipay;

class AuthorizeNet
{
    protected $gateway_auth;
    protected $authorizenet_endpoint;

    function __construct($gateway_implementation = 'AuthorizeNet_CIM'){
        $this->gateway_auth = Omnipay::create( $gateway_implementation );
        $this->gateway_auth->setDeveloperMode( true );
        $this->gateway_auth->setApiLoginId( env( 'TEST_AUTHORIZENET_TRANSID' ) );
        $this->gateway_auth->setTransactionKey( env( 'TEST_AUTHORIZENET_TRANSKEY' ) );
    }

    function createCustomerProfile(
        $card_number,
        $card_code,
        $card_expiration_month,
        $card_expiration_year,
        $name,
        $last_name,
        $address,
        $city,
        $state,
        $zipcode,
        $email,
        $user_customer_id,
        $dataDescriptor,
        $dataValue
    ){

        //OMNIPAYED
        $params = [
            'card' => [
                'billingFirstName' => $name,
                'billingLastName' => $last_name,
                'billingAddress1' => $address,
                'billingCity' => $city,
                'billingState' => $state,
                'billingPostcode' => $zipcode,
                'billingPhone' => '',
            ],
            'opaqueDataDescriptor' => $dataDescriptor,
            'opaqueDataValue' => $dataValue,
            'name' => $name,
            'email' => $email,
            'customerType' => 'individual',
            'customerId' => $user_customer_id,
            'description' => 'MEMBER ID' . $user_customer_id,
            'forceCardUpdate' => true
        ];
        $request = $this->gateway_auth->createCard( $params );
        $response = $request->send();
        return $response;
    }

    function get_customer_profile( $customerProfileId , $customerPaymentProfileId = 1 ){/*CUSTOMER PAYMENT PROFILE*/

        //OMNIPAYED
        $request = $this->gateway_auth->getPaymentProfile([
            'customerProfileId' => $customerProfileId,
            'customerPaymentProfileId' => $customerPaymentProfileId
        ]);
        $response = $request->send();
        return $response;
    }

    function get_customers_profile( $customerProfileId , $customerPaymentProfileId = 1 ){/*CUSTOMER PROFILE*/

        //OMNIPAYED
        $request = $this->gateway_auth->getCustomerProfile([
            'customerProfileId' => $customerProfileId,
        ]);
        $response = $request->send();
        return $response;
    }

    function deleteCard($profile_id, $payment_profile_id){

        //OMNIPAYED
        $request = $this->gateway_auth->deleteCard([
            'customerProfileId' => $profile_id,
            'customerPaymentProfileId' => $payment_profile_id
        ]);
        $response = $request->send();
        return $response;
    }

    function charge_a_profile($profile_id, $payment_profile_id, $charge_amount, $description = null){

        //OMNIPAYED
        $data['customerProfileId'] = $profile_id;
        $data['customerPaymentProfileId'] = $payment_profile_id;
        $cardRef = "";
        if (!empty($data['customerProfileId']) && !empty($data['customerPaymentProfileId'])) {
            $cardRef = json_encode($data);
        }
        $params = [
            'cardReference' => $cardRef,
            'amount' => $charge_amount,
            'description' => $description ?: 'Purchase'
        ];
        $request = $this->gateway_auth->purchase($params);
        $response = $request->send();
        return $response;
    }

    function single_charge(
        $card_number = null,
        $expmonth = null,
        $expyear = null,
        $cvv = null,
        $charge_amount,
        $dataDescriptor,
        $dataValue
    ){
        $charge_amount = (floor($charge_amount*100)/100);
        //OMNIPAYED
        $params = [
            'card' => [
                'number' => '',
                'cvv' => '',
                'expiryMonth' => '',
                'expiryYear' => '',
            ],
            'opaqueDataDescriptor' => $dataDescriptor,
            'opaqueDataValue' => $dataValue,
            'amount' => $charge_amount,
            'description' => 'Purchase'
        ];
        $request = $this->gateway_auth->purchase($params);
        $response = $request->send();
        return $response;
    }

    public function voidTransaction($transact_id)
    {

        $request = $this->gateway_auth->void([
            'transactionReference' => $transact_id
        ]);
        $response = $request->send();
        return $response;
    }

    function get_transaction_details($transactionId){
        $refId = 'ref' . time();
        $request = new AnetAPI\GetTransactionDetailsRequest();
        $request->setMerchantAuthentication($this->gateway_auth);
        $request->setTransId($transactionId);
        $controller = new AnetController\GetTransactionDetailsController($request);
        $response = $controller->executeWithApiResponse( $this->authorizenet_endpoint );
        return $response;
    }

}
