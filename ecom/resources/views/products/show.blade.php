@extends('layouts\app')

@section('content')
    <div class="d-flex justify-content-center">
        <div class="col-md-4">
            <product-single-sheet product_id="{{ $product_id }}"></product-single-sheet>
        </div>
    </div>
@endsection
