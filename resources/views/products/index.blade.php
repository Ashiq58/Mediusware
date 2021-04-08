@extends('layouts.app')

@section('content')

    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Products</h1>
    </div>


    <div class="card">
        <form action="" method="get" class="card-header">
            <div class="form-row justify-content-between">
                <div class="col-md-2">
                    <input type="text" name="title" placeholder="Product Title" class="form-control" value="{{ request()->input('title') }}">
                </div>
                <div class="col-md-2">
                    <select name="variant" id="variant" class="form-control">
                        @foreach($variants as $rootVariant) 
                        <option value="" >--Select One --</option>
                        <option class="disabled-bg" disabled="disabled" >{{$rootVariant->title}}</option>
                        @foreach($rootVariant->productVariants->unique('variant')->all() as $variant) 
                        <option value="{{$variant->variant}}" >{{$variant->variant}}</option>
                        @endforeach
                        @endforeach
                    </select>
                </div>

                <div class="col-md-3">
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <span class="input-group-text">Price Range</span>
                        </div>
                        <input type="text" name="price_from" aria-label="First name" placeholder="From" class="form-control" value="{{ request()->input('price_from') }}">
                        <input type="text" name="price_to" aria-label="Last name" placeholder="To" class="form-control" value="{{ request()->input('price_to') }}">
                    </div>
                </div>
                <div class="col-md-2">
                    <input type="date" name="date" placeholder="Date" class="form-control" value="{{ request()->input('date') }}">
                </div>
                <div class="col-md-1">
                    <button type="submit" class="btn btn-primary float-right"><i class="fa fa-search"></i></button>
                </div>
            </div>
        </form>

        <div class="card-body">
            <div class="table-response">
                <table class="table">
                    <thead>
                    <tr>
                        <th>#</th>
                        <th>Title</th>
                        <th>Description</th>
                        <th>Variant</th>
                        <th width="150px">Action</th>
                    </tr>
                    </thead>

                    <tbody>
                    @foreach($products as $key=>$product)
                    <tr>
                        <td>1</td>
                        <td>{{$product->title}} <br> {{$product->created_at}}</td>
                        <td>{{$product->description}}</td>
                        <td>
                            <dl class="row mb-0" style="height: 80px; min-width: 300px; overflow: hidden" id="variant_{{$key}}">

                                @foreach($product->inventories as $inventory)
                                <dt class="col-sm-3 pb-0">
                                    @if($inventory->firstVariant)
                                    {{$inventory->firstVariant->variant}}
                                    @endif
                                    @if($inventory->secondVariant)
                                    / {{$inventory->secondVariant->variant}}
                                    @endif
                                    @if($inventory->thirdVariant)
                                    / {{$inventory->thirdVariant->variant}}
                                    @endif
                                </dt>
                                <dd class="col-sm-9">
                                    <dl class="row mb-0">
                                        <dt class="col-sm-4 pb-0">Price : {{ number_format($inventory->price,2) }}</dt>
                                        <dd class="col-sm-8 pb-0">InStock : {{ $inventory->stock }}</dd>
                                    </dl>
                                </dd>
                                @endforeach
                            </dl>
                            <button onclick="$('#variant_{{$key}}').toggleClass('h-auto')" class="btn btn-sm btn-link">Show more</button>
                        </td>
                        <td>
                            <div class="btn-group btn-group-sm">
                                <a href="{{ route('product.edit', 1) }}" class="btn btn-success">Edit</a>
                            </div>
                        </td>
                    </tr>
                   @endforeach
                    </tbody>

                </table>
            </div>

        </div>

        <div class="card-footer">
            <div class="row justify-content-between">
                <div class="col-md-6">
                    <p>Showing {{$products->firstItem()}} to {{$products->lastItem()}} out of {{$products->total()}}</p>
                </div>
                <div class="col-md-6">
                    {{ $products->links() }}
                </div>
            </div>
        </div>
    </div>

@endsection
