@extends('layouts.app')

@section('title')
    Profile
@endsection

@section('content')
    <div class="container">
        <div class="row">
            <div class="card col-md m-2">
                <div class="card-body">
                    <div class="row">
                        <div class="col">
                            <div>
                                <h3>Name</h3>
                                <p>{{ Auth::user()->name }}</p>
                            </div>
                        </div>
                        <div class="col">
                            <div>
                                <h3>E-mail</h3>
                                <p>{{ Auth::user()->email }}</p>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col">
                            <div>
                                <h3>Address</h3>
                                <p>{{ Auth::user()->address }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <h4>Orders</h4>
        <div class="row">
            <div class="card col-md m-2">
                <div class="card-body">
                    <table class="table table-striped table-hover">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Date</th>
                                <th>Price</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>1</td>
                                <td>2023.01.22</td>
                                <td>144 562 Ft</td>
                                <td><span class="badge text-bg-light">CLOSED</span></td>
                            </tr>
                            <tr>
                                <td>2</td>
                                <td>2023.01.22</td>
                                <td>144 562 Ft</td>
                                <td><span class="badge text-bg-info">NEW</span></td>
                            </tr>
                            <tr>
                                <td>3</td>
                                <td>2023.01.22</td>
                                <td>144 562 Ft</td>
                                <td><span class="badge text-bg-primary">IN DELIVERY</span></td>
                            </tr>
                            <tr>
                                <td>4</td>
                                <td>2023.01.22</td>
                                <td>144 562 Ft</td>
                                <td><span class="badge text-bg-danger">CANCELLED</span></td>
                            </tr>
                            <tr>
                                <td>5</td>
                                <td>2023.01.22</td>
                                <td>144 562 Ft</td>
                                <td><span class="badge text-bg-warning">ACTION REQUIRED</span></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection
