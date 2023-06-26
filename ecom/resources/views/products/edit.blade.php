@extends('layouts\app')

@section('title')
    Edit product
@endsection

@section('content')
    <div class="container">
        <div class="row">
            <div class="card col-md">
                <div class="card-body">
                    <product-update-form :product="{{ $product }}"></product-update-form>
                </div>
            </div>
        </div>
    </div>
@endsection
