<template>
    <div>
        <form @submit.prevent="submit" id="paymentForm" method="post">
            <div class="alert alert-danger" v-if="form.errors.any()" v-cloak>
                Please check the form and try again!
            </div>
            <div class="row">
                <div class="col-md-4 order-md-2 mb-4">
                    <h4 class="d-flex justify-content-between align-items-center mb-3">
                        <span class="text-muted">Your cart</span>
                        <span class="badge badge-secondary badge-pill">3</span>
                    </h4>
                    <ul class="list-group mb-3">
                        <li class="list-group-item d-flex justify-content-between lh-condensed" v-for="product in cart_products">
                            <div>
                                <h6 class="my-0" v-text="product.name"></h6>
                                <small class="text-muted" v-text="product.description"></small>
                            </div>
                            <span class="text-muted" v-text="'$' + product.price"></span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between">
                            <span>Total (USD)</span>
                            <strong v-text="'$' + cart_total"></strong>
                        </li>
                    </ul>
                    <div class="input-group">
                        <input type="text" class="form-control" placeholder="Promo code">
                        <div class="input-group-append">
                            <button type="button" class="btn btn-secondary">Redeem</button>
                        </div>
                    </div>
                </div>

                <div class="col-md-8 order-md-1">
                    <h4 class="mb-3">Billing address</h4>
                    <div class="row">
                        <div class="col-md-6 mb-3" :class="{ 'has-error': form.errors.has('name') }">
                            <label for="name">First name</label>
                            <input v-model="form_data.name" name="name" type="text" class="form-control" id="name" placeholder="" value="" >
                            <div class="invalid-feedback" v-if="form.errors.has('name')" v-text="form.errors.get('name')"></div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="lastName">Last name</label>
                            <input v-model="form_data.last_name" name="lastName" type="text" class="form-control" id="lastName" placeholder="" value="" >
                            <div class="invalid-feedback" v-if="form.errors.has('last_name')" v-text="form.errors.get('last_name')"></div>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="username">Username</label>
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text">@</span>
                            </div>
                            <input v-model="form_data.username" name="username" type="text" class="form-control" id="username" placeholder="Username" >
                            <div class="invalid-feedback" v-if="form.errors.has('username')" v-text="form.errors.get('username')"></div>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="email">Email <span class="text-muted">(Optional)</span></label>
                        <input v-model="form_data.email" name="email" type="email" class="form-control" id="email" placeholder="you@example.com" >
                        <div class="invalid-feedback" v-if="form.errors.has('email')" v-text="form.errors.get('email')"></div>
                    </div>

                    <div class="mb-3">
                        <label for="address">Address</label>
                        <input v-model="form_data.address" name="address" type="text" class="form-control" id="address" placeholder="1234 Main St" >
                        <div class="invalid-feedback" v-if="form.errors.has('address')" v-text="form.errors.get('address')"></div>
                    </div>

                    <div class="mb-3">
                        <label name="address2" for="address2">Address 2 <span class="text-muted">(Optional)</span></label>
                        <input v-model="form_data.address2" type="text" class="form-control" id="address2" placeholder="Apartment or suite">
                        <div class="invalid-feedback" v-if="form.errors.has('address2')" v-text="form.errors.get('address2')"></div>
                    </div>

                    <div class="row">
                        <div class="col-md-3 mb-3">
                            <label name="country" for="country">Country</label>
                            <select v-model="form_data.country" class="custom-select d-block w-100" id="country" >
                                <option value="">Choose...</option>
                                <option value="United States">United States</option>
                            </select>
                            <div class="invalid-feedback" v-if="form.errors.has('country')" v-text="form.errors.get('country')"></div>
                        </div>
                        <div class="col-md-3 mb-3">
                            <label for="city">City</label>
                            <input v-model="form_data.city" name="city" class="custom-select d-block w-100" id="city">
                            <div class="invalid-feedback" v-if="form.errors.has('city')" v-text="form.errors.get('city')"></div>
                        </div>
                        <div class="col-md-3 mb-3">
                            <label for="state">State</label>
                            <select v-model="form_data.state" name="state" class="custom-select d-block w-100" id="state" >
                                <option value="">Choose...</option>
                                <option value="California">California</option>
                            </select>
                            <div class="invalid-feedback" v-if="form.errors.has('state')" v-text="form.errors.get('state')"></div>
                        </div>
                        <div class="col-md-3 mb-3">
                            <label for="zip">Zip</label>
                            <input v-model="form_data.zip" name="zip" type="text" class="form-control" id="zip" placeholder="" >
                            <div class="invalid-feedback" v-if="form.errors.has('zip')" v-text="form.errors.get('zip')"></div>
                        </div>
                    </div>

                    <hr class="mb-4">
                    <div class="custom-control custom-checkbox">
                        <input v-model="form_data.same_address" name="same_address" type="checkbox" class="custom-control-input" id="same-address">
                        <label class="custom-control-label" for="same-address">Shipping address is the same as my billing address</label>
                    </div>

                    <div class="custom-control custom-checkbox">
                        <input v-model="form_data.save_info" name="save_info" type="checkbox" class="custom-control-input" id="save-info">
                        <label class="custom-control-label" for="save-info">Save this information for next time</label>
                    </div>
                    <hr class="mb-4">

                    <h4 class="mb-3">Payment</h4>

                    <div class="d-block my-3">
                        <div class="custom-control custom-radio">
                            <input v-model="form_data.payment_method" name="payment_method" id="credit" type="radio" class="custom-control-input" checked  value="card">
                            <label class="custom-control-label" for="credit">Credit card</label>
                        </div>
                        <div class="custom-control custom-radio">
                            <input v-model="form_data.payment_method" name="payment_method" id="paypal" type="radio" class="custom-control-input"  value="paypal">
                            <label class="custom-control-label" for="paypal">PayPal</label>
                        </div>
                    </div>
                    <div v-if="form_data.payment_method == 'card'">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="cc-name">Name on card</label>
                                <input v-model="form_data.card_name" name="card_name" type="text" class="form-control" id="cc-name" placeholder="" >
                                <small class="text-muted">Full name as displayed on card</small>
                                <div class="invalid-feedback" v-if="form.errors.has('card_name')" v-text="form.errors.get('card_name')"></div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="cardNumber">Credit card number</label>
                                <input v-model="form_data.card_number" name="cardNumber" type="text" class="form-control" id="cardNumber" placeholder="" >
                                <div class="invalid-feedback" v-if="form.errors.has('card_number')" v-text="form.errors.get('card_number')"></div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-3 mb-3">
                                <label for="expMonth">Expiration <i>MM</i></label>
                                <input v-model="form_data.card_expiration_month" name="expMonth" type="text" class="form-control" id="expMonth" placeholder="" >
                                <div class="invalid-feedback" v-if="form.errors.has('card_expiration_month')" v-text="form.errors.get('card_expiration_month')"></div>
                            </div>
                            <div class="col-md-3 mb-3">
                                <label for="expYear">Expiration <i>YY</i></label>
                                <input v-model="form_data.card_expiration_year" name="expYear" type="text" class="form-control" id="expYear" placeholder="" >
                                <div class="invalid-feedback" v-if="form.errors.has('card_expiration_year')" v-text="form.errors.get('card_expiration_year')"></div>
                            </div>
                            <div class="col-md-3 mb-3">
                                <label for="cardCode">CVV</label>
                                <input v-model="form_data.card_cvv" name="cardCode" type="text" class="form-control" id="cardCode" placeholder="" >
                                <div class="invalid-feedback" v-if="form.errors.has('card_cvv')" v-text="form.errors.get('card_cvv')"></div>
                            </div>
                        </div>
                    </div>

                    <hr class="mb-4">
                    <input v-model="form_data.dataValue" type="hidden" name="dataValue" id="dataValue" />
                    <input v-model="form_data.dataDescriptor" type="hidden" name="dataDescriptor" id="dataDescriptor" />
                    <button class="btn btn-primary btn-lg btn-block" type="submit">Continue to checkout</button>
                </div>
            </div>
        </form>
    </div>
</template>

<script>

    //found on stackOverflow : https://stackoverflow.com/questions/149055/how-can-i-format-numbers-as-dollars-currency-string-in-javascript
    function formatMoney(amount, decimalCount = 2, decimal = ".", thousands = ",") {
        try {
            decimalCount = Math.abs(decimalCount);
            decimalCount = isNaN(decimalCount) ? 2 : decimalCount;

            const negativeSign = amount < 0 ? "-" : "";

            let i = parseInt(amount = Math.abs(Number(amount) || 0).toFixed(decimalCount)).toString();
            let j = (i.length > 3) ? i.length % 3 : 0;

            return negativeSign + (j ? i.substr(0, j) + thousands : '') + i.substr(j).replace(/(\d{3})(?=\d)/g, "$1" + thousands) + (decimalCount ? decimal + Math.abs(amount - i).toFixed(decimalCount).slice(2) : "");
        } catch (e) {
            console.log(e)
        }
    };

    import Form from 'form-object';

    function post_self(path, params, method) {
        //used to send all input when Paypal is selected
        method = method || "post";
        var form = document.createElement("form");
        form.setAttribute("method", method);
        form.setAttribute("action", path);

        var hiddenField = document.createElement("input");
        hiddenField.setAttribute("type", "hidden");
        hiddenField.setAttribute("name", '_token');
        hiddenField.setAttribute("value", document.head.querySelector('meta[name="csrf-token"]').content);

        form.appendChild(hiddenField);
        var le_vale = '';
        for(var key in params) {
            if(params.hasOwnProperty(key)) {
                hiddenField = document.createElement("input");
                hiddenField.setAttribute("type", "hidden");
                hiddenField.setAttribute("name", key);
                if(isdict(params[key]) || params[key] instanceof Array){
                    le_vale = JSON.stringify(params[key]);
                }else{
                    le_vale = params[key]
                }
                hiddenField.setAttribute("value", le_vale);
                form.appendChild(hiddenField);
                hiddenField = null;
            }
        }
        document.body.appendChild(form);
        form.submit();
    }
    function isdict(v) {
        return  v !== undefined
            && v!==null
            && typeof v==='object'
            && v.constructor!==Array
            && v.constructor!==Date;
    }

    const { validationMixin, default: Vuelidate } = require('vuelidate')
    const { required, minLength, requiredIf, email, between, maxLength } = require('vuelidate/lib/validators')

    export default {
        props:['cart_products'],
        data() {
            return {
                form_data: {
                    dataValue:null,
                    dataDescriptor:null,
                    name: '',
                    last_name: '',
                    username: '',
                    email: '',
                    address: '',
                    address2: '',
                    country: '',
                    city: '',
                    state: '',
                    zip: '',
                    same_address: '',
                    save_info: '',
                    payment_method: 'card',
                    card_name: '',
                    card_number: '',
                    card_expiration_month: '',
                    card_expiration_year: '',
                    card_cvv: ''
                },
                form: new Form()
            }
        },

        methods: {
            submit(){
                blockpage();
                this.$v.$touch()
                if (this.$v.$invalid) {
                    unBlockpage();
                    console.log('INVALID FORM');
                } else {
                    if(this.form_data.payment_method == 'paypal'){
                        post_self('/pay', this.form_data, 'post');
                        return;
                    }else{
                        var cardData = {};
                        cardData.cardNumber = '' + this.form_data['card_number'];
                        cardData.month = '' + this.form_data['card_expiration_month'];
                        cardData.year = '' + this.form_data['card_expiration_year'];
                        cardData.cardCode = '' + this.form_data['card_cvv'];
                        var secureData = {};
                        secureData.authData = authData;
                        secureData.cardData = cardData;
                        Accept.dispatchData(secureData, this.authorizeResponseHandler);
                    }
                }
            },
            authorizeResponseHandler(response){
                if (response.messages.resultCode === "Error") {
                    var i = 0;
                    while (i < response.messages.message.length) {
                        console.log( response.messages.message[i].code + ": " + response.messages.message[i].text );
                        i = i + 1;
                    }
                    unBlockpage();
                } else {
                    this.form_data.dataValue = response.opaqueData.dataValue;
                    this.form_data.dataDescriptor = response.opaqueData.dataDescriptor;
                    document.getElementById("cardNumber").value = "";
                    document.getElementById("expMonth").value = "";
                    document.getElementById("expYear").value = "";
                    document.getElementById("cardCode").value = "";
                    this.submit_payment();
                }
            },
            submit_payment(){
                var that = this;
                this.form.submit('post', '/pay', this.form_data).then(data => {
                    if(data['status'] === 'success'){
                        that.$router.push({
                            name: 'successPage',
                            params : {
                                transaction_id: data['transaction_id'],
                                transaction_response: data['transaction_response']
                            }
                        });
                    }else{
                        alert('ERROR WHEN PROCESSING PAYMENT');
                    }
                    unBlockpage();
                }).catch(error => {
                    console.log(error)
                    unBlockpage();
                });
            }
        },
        computed:{
            cart_total(){
                var total = _.reduce(this.cart_products, function(sum, n) {
                    return sum + parseFloat(n.price);
                }, 0);
                return formatMoney(total);
            }
        },
        validations: {
            form_data: {
                dataValue: {
                    required: requiredIf(function (cvv) {
                        return this.payment_method == 'card'
                    })
                },
                dataDescriptor: {
                    required: requiredIf(function (cvv) {
                        return this.payment_method == 'card'
                    })
                },
                name: {
                    required
                },
                last_name: {
                    required
                },
                username: {
                    required
                },
                email: {
                    required,
                    email
                },
                address: {
                    required
                },
                country: {
                    required
                },
                city: {
                    required
                },
                state: {
                    required
                },
                zip: {
                    required
                },
                payment_method: {
                    required
                },
                card_name: {
                    required: requiredIf(function (cvv) {
                        return this.payment_method == 'card'
                    })
                },
                card_number: {
                    required: requiredIf(function () {
                        return this.payment_method == 'card'
                    }),
                    minLength: minLength(12)
                },
                card_expiration_month: {
                    required: requiredIf(function () {
                        return this.payment_method == 'card'
                    }),
                    between:between(1,12)
                },
                card_expiration_year: {
                    required: requiredIf(function () {
                        return this.payment_method == 'card'
                    }),
                    maxLength: maxLength(2)
                },
                card_cvv: {
                    required: requiredIf(function (cvv) {
                        return this.payment_method == 'card'
                    }),
                    maxLength: maxLength(4)
                },
            }
        }
    }
</script>
