@extends('layouts\app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="card col-md-4">
                <div class="card-body">
                    <div class="col">
                        <div class="row py-2">
                            <div class="col">
                                <p>{{ $product->name }}</p>
                            </div>
                            <div class="col">
                                <p>{{ $product->price }}</p>
                            </div>
                        </div>
                        <div class="row py-2">
                            <div class="col">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
