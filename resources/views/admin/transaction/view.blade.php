@extends('layouts.app')
@section('title','Transaction')
@section('pagetitle','Transaction')
@section('sort_name',$data['sort_name'])
@section('content')
<div class="content-wrapper">
    <div class="page-header">
      <h3 class="page-title">View Transaction</h3>
      <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
          <li class="breadcrumb-item">
            <a href="{{url('transaction')}}" title="Back">
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
              <table class="table">
                <thead>
                  <tr>
                    <th>Transaction Id</th>
                    <th>Payment method</th>
                    <th>User</th>
                    <th>Email</th>
                    <th>Amount</th>
                    <th>Status</th>
                  </tr>
                </thead>
                <tbody>
                  <tr>
                    <td>{{$data['transaction']->transaction_id}}</td>
                    <td>{{$data['transaction']->payment_method}}</td>
                    <td>{{$data['transaction']->user->name}}</td>
                    <td>{{$data['transaction']->user->email}}</td>
                    <td>{{$data['transaction']->amount}}</td>
                    <td>{{json_decode($data['transaction']->response)->status??"N/A"}}</td>
                  </tr>
                </tbody>
              </table>
            </div>
          </div>
        </div>
    </div>
</div>
@endsection