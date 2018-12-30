@extends('welcome')

@section('content')
    <div >
        <main role="main" class="container">
            <h1 class="mt-5">Success</h1>
            <p class="lead">Transaction processed.</p>
            <p>Transaction ID: <a href="#!">{{ session('transaction_id') }}</a></p>
            <p>Transaction Response: <pre>{{ session('transaction_response') }}</pre> </p>
            <p class="text-center"><a href="/" class="btn btn-danger">Make another payment</a></p>
        </main>
    </div>
@endsection