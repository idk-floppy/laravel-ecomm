@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">{{ __('Cart') }}</div>

                    <div class="card-body">
                        <cart-table></cart-table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
