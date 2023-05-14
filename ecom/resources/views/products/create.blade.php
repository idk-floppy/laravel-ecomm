@extends('layouts\app')

@section('title')
    Create product
@endsection

@section('content')
    <div class="container">
        <div class="row">
            <div class="card col-md">
                <div class="card-body">
                    <form action="{{ route('products.store') }}" method="post" enctype="multipart/form-data">
                        @csrf
                        <div class="col">
                            <div class="row py-2">
                                <div class="col">
                                    <label for="name">Name</label>
                                    <input type="text" name="name" id="name" class="form-control" required>
                                </div>
                                <div class="col">
                                    <label for="price">Price</label>
                                    <input type="number" name="price" id="price" class="form-control" min="0"
                                        required>
                                </div>
                            </div>
                            <div class="row py-2">
                                <div class="col">
                                    <label for="image">Image</label>
                                    <input type="file" name="image" id="image" class="form-control" required>
                                </div>
                            </div>
                            <div class="row py-2">
                                <div class="col-sm col-md-3">
                                    <input type="submit" value="Create" class="btn btn-primary">
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
