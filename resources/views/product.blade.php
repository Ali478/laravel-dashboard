@extends('layout.master')
@section('contain')

<a href="/add/product" class="btn btn-primary mb-3">Add New Product</a>
    <div class="row">
        <div class="col-xl-12 col-xxl-12 col-lg-12 col-md-12">
            <div class="card">
                <div class="card-header border-0 pb-0">
                    <h4 class="card-title">Product</h4>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-responsive-sm mb-0">
                            <thead>
                            <tr>
                                <th><strong>ID</strong></th>
                                <th><strong>NAME</strong></th>
                                <th><strong>CATEGORY</strong></th>
                                <th><strong>PRICE</strong></th>
                                <th><strong>QUANTITY</strong></th>
                                <th><strong>CREATE AT</strong></th>
                                <th style="width:85px;"><strong>ACTION</strong></th>
                            </tr>
                            </thead>
                            <tbody>
                                @if($data)
                                    @foreach($data as $item)
                                        <tr>
                                            <td><b>{{ $item->id }}</b></td>
                                            <td><b>{{ $item->name }}</b></td>
                                            <td>{{ $item->category_name }}</td>
                                            <td> Rs: {{ $item->price }}</td>
                                            <td>{{ $item->qnt }}</td>
                                            <td>{{ $item->created_at }}</td>
                                            <td>
                                                <a href="/delete/product/{{ $item->id }}" class="btn btn-danger shadow btn-xs sharp"><i class="fa fa-trash"></i></a>
                                                <a href="/edit/product/{{ $item->id }}" class="btn btn-primary shadow btn-xs sharp"><i class="fa fa-pencil-square-o" aria-hidden="true"></i></a>
                                            </td>
                                        </tr>
                                    @endforeach
                                @else
                                    <tr class="text-center">
                                        <td colspan="7">No Data Found!</td>
                                    </tr>
                                @endif
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection
