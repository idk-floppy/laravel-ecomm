@extends('layouts\app')

@section('content')
    <div class="d-flex justify-content-center">
        <div class="col-md-4">
            <product-card :product="{{ $product }}"></product-card>
        </div>
    </div>
@endsection
