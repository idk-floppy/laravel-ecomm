@extends('layouts\app')

@section('content')
    <div class="d-flex justify-content-center">
        <div class="col-md-4">
            <card :product="{{ $product }}"></card>
        </div>
    </div>
@endsection
