<template>
    <div >
        <main role="main" class="container">
            <h1 class="mt-5">Success</h1>
            <p class="lead">Transaction processed.</p>
            <p>Transaction ID: <a href="#!" v-text="transaction_id"></a></p>
            <p>Transaction Response: <pre v-text="transaction_response"></pre> </p>
            <p class="text-center"><router-link to="/" class="btn btn-danger">Make another payment</router-link></p>
        </main>
    </div>
</template>

<script>

    export default {
        props:['transaction_id','transaction_response'],
        data() {
            return {

            }
        },

        methods: {

        }
    }
</script>
