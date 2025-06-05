@extends('layouts.app')
@section('title','Order')
@section('pagetitle','Order')
@section('sort_name',$data['sort_name'])
@section('content')
<div class="content-wrapper">
    <div class="page-header">
      <h3 class="page-title">View Order</h3>
      <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
          <li class="breadcrumb-item">
            <a href="{{url('orders')}}" title="Back">
              <label><- Back</label>
            </a>
          </li>
        </ol>
      </nav>
    </div>
    <div class="col-12 grid-margin stretch-card">
        <div class="col-lg-12 grid-margin stretch-card">
          <div class="card">
            <div class="card-body">
              <div class="row">
                <div class="col-md-3">
                  <address>
                    <p class="font-weight-bold">Order Details </p>
                    <p>Order Id: <span>{{$data['order']->order_number}}</span></p>
                    <p>Total: {{$data['order']->total_amount}}</p>
                  </address>
                </div>
                <div class="col-md-3">
                  <address>
                    <p class="font-weight-bold">Order Details </p>
                    <p>Area: {{$data['order']->address->first_address}}, {{$data['order']->address->second_address}}</p>
                    <p>City: {{$data['order']->address->city}}</p>
                    <p>State: {{$data['order']->address->state}}</p>
                    <p>Postal Code: {{$data['order']->address->postal_code}}</p>
                  </address>
                </div>
                <div class="col-md-3">
                  <address>
                    <p class="font-weight-bold"> Transaction Details</p>
                    <p>Transaction Id: {{$data['order']->transaction->transaction_id}}</p>
                    <p>Payment Method: {{$data['order']->transaction->payment_method}}</p>
                  </address>
                </div>
                <div class="col-md-3">
                  <address>
                    <p class="font-weight-bold"> User Details </p>
                    <p>Name: {{$data['order']->user->name}}</p>
                    <p>Email: {{$data['order']->user->email}}</p>
                  </address>
                </div>
              </div>
            </div>
            <div class="card-body">
              <table class="table">
                <thead>
                  <tr>
                    <th>Product</th>
                    <th>Color</th>
                    <th>Size</th>
                    <th>Quantity</th>
                    <th>Price</th>
                  </tr>
                </thead>
                <tbody>
                  @foreach($data['order']->detail as $prod)
                    <tr>
                      <td>{{$prod->product->name}}</td>
                      <td>{{$prod->colorname->name}}</td>
                      <td>{{$prod->sizecode->code}}</td>
                      <td>{{$prod->qty}}</td>
                      <td>{{$prod->price}}</td>
                    </tr>
                  @endforeach
                </tbody>
              </table>
            </div>
          </div>
        </div>
    </div>
</div>
@endsection