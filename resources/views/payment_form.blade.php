@extends('welcome')

@section('content')
<div class="py-5 text-center">
    <img class="d-block mx-auto mb-4" src="https://getbootstrap.com/docs/4.1/assets/brand/bootstrap-solid.svg" alt="" width="72" height="72">
    <h2>Checkout form</h2>
    <p class="lead">Below is an example form built entirely with Bootstrap's form controls. Each required form group has a validation state that can be triggered by attempting to submit the form without completing it.</p>
</div>

<router-view v-cloak></router-view>

@endsection