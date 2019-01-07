# Omnipay Gateways integration with Laravel and VueJS

### Intro
This is a full Laravel 5.5 installation, with nothing customized beyond what needed in order to make it work (something like an MVP for testing Omnipay with VueJS and Laravel) although not production ready only for testing.

Gateways/Features tested:

- __omnipay-paypal__ [`purchase`]
- __omnipay-authorizenet__ (__AIM__ [`purchase`], __CIM__ [`createCard` (customer profile), `purchase` (charge a customer profile)])


![Checkout Form](https://raw.githubusercontent.com/alberto1el/omnipay-example/master/chekoutForm.png "Checkout Form")

### In order to run the form in the browser do:

1. `composer install`
2. yarn install (add `--no-bin-links` option if on Windows)
3. `npm run dev`
4. Add these environment variables to Laravels env file:
  - `PAYPAL_SANDBOX=true`
  - `COMPANY_LOGO_MEDIUM` (can be an empty string)
  - The following 3 variables must be obtained from your paypal account and these are the 'API credentials' not your paypal account information.
  - `PAYPAL_USERNAME`
  - `PAYPAL_PASSWORD`
  - `PAYPAL_SIGNATURE`
  - `AUTHNET_PUBLIC_CLIENT_KEY` (used by Accept.js Authorize.net) //Find it in your authorize.net account>Settings>Manage Public Client Key>Client Key
  - `AUTHNET_API_LOGIN_ID` (used by Accept.js Authorize.net) //Find it in your authorize.net account>Settings>API Credentials & Keys>API Login ID
  - `TEST_AUTHORIZENET_TRANSID` (the same as `AUTHNET_PUBLIC_CLIENT_KEY` I just like to differentiate between browser and server variables) //Find it in your authorize.net account>Settings>API Credentials & Keys>API Login ID
  - `TEST_AUTHORIZENET_TRANSKEY` (key used by the server to communicate to Authorize.net ) //Find it in your authorize.net account>Settings>API Credentials & Keys>Transaction Key

### Notes:
When paying with Authorize.net (card option), fist the card is opaqued using the Authorize.net Accept.js library, then the form will be sent to the laravel backend with only the opaqued data.

If you use the card option and choose 'Save this information for next time' an Authorize.net Customer Payment Profile will be created using the form information, then a charge will be made to that profile (in the same request).

When paying with Paypal, the form is sent as a form POST request to the server, then the browser is redirected to paypal then back to the laravel backend to finish the payment (which Laravel autoFinishes).

All relevant backend code can be found at: 

    /app/Http/Controllers/ChargeController
    /app/Payment/Gateways/

Relevant frontend code:

    /resources/assets/js/components/PaymentFormComponent.vue
    /resources/assets/js/app
    /resources/views/welcome.blade


### Extra Note:
I use this repository as a proof of concept for an application I am developing, feel free to comment in order to improve code I know it is not 100% production ready but it was done in a day and also helped get to understand VueRouter and VueComponents a little more.

Thanks for reading!
