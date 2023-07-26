@extends('layouts\app')

@section('title')
    Create product
@endsection

@section('content')
    <div class="container">
        <div class="row">
            <div class="card col-md">
                <div class="card-body">
                    <product-form :is-create-mode="true"></product-form>
                </div>
            </div>
        </div>
    </div>
@endsection
