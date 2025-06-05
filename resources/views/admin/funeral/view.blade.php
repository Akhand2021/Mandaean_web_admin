@extends('layouts.app')
@section('title','Funeral')
@section('pagetitle','Funeral')
@section('sort_name',$data['sort_name'])
@section('content')
<div class="content-wrapper">
    <div class="page-header">
      <h3 class="page-title">View Funeral Details</h3>
      <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
          <li class="breadcrumb-item">
            <a href="{{url('funeral')}}" title="Back">
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
                    <p><strong>Name:</strong> <span>{{$data['funeral']->salutation}} {{$data['funeral']->name}}</span></p>
                    <p><strong>Family Name:</strong> <span>{{$data['funeral']->family_name}}</span></p>
                  </address>
                </div>
                <div class="col-md-3">
                  <address>
                    <p><strong>DOB:</strong> {{date('d M Y',strtotime($data['funeral']->dob))}}</p>
                    <p><strong>DOD:</strong> {{date('d M Y',strtotime($data['funeral']->dod))}}</p>
                  </address>
                </div>
                <div class="col-md-3">
                  <address>
                    <p><strong>Register Address:</strong> {{$data['funeral']->register_address}}</p>
                    <p><strong>Body Now:</strong> {{$data['funeral']->body_now}}</p>
                  </address>
                </div>
                <div class="col-md-3">
                  <address>
                    <p><strong>Identity:</strong> <img src="{{url('/')}}/{{$data['funeral']->identity}}" width="120" height="80"></p>
                  </address>
                </div>
              </div>
              <div class="row">
                <div class="col-md-3">
                  <address>
                    <p><strong>Kins Name:</strong> <span>{{$data['funeral']->kins_salutation}} {{$data['funeral']->kins_name}}</span></p>
                    <p><strong>Kins Family Name:</strong> <span>{{$data['funeral']->kins_family_name}}</span></p>
                  </address>
                </div>
                <div class="col-md-3">
                  <address>
                    <p><strong>Kins Email:</strong> {{$data['funeral']->kins_email}}</p>
                    <p><strong>Kins Mobile No.:</strong> {{$data['funeral']->kins_mobile}}</p>
                  </address>
                </div>
                <div class="col-md-3">
                  <address>
                    <p><strong>Kins Address:</strong> {{$data['funeral']->kins_address}}</p>
                    <p><strong>Relationship:</strong> {{$data['funeral']->relationship}}</p>
                  </address>
                </div>
                <div class="col-md-3">
                  <address>
                    <p><strong>Kins Identity:</strong> <img src="{{url('/')}}/{{$data['funeral']->kins_identity}}" width="120" height="80"></p>
                  </address>
                </div>
              </div>
              <div class="row">
                <div class="col-md-3">
                  <address>
                    <p><strong>Signature:</strong> <img src="{{url('/')}}/{{$data['funeral']->signature}}" width="120" height="80"></p>
                  </address>
                </div>
              </div>
            </div>
          </div>
        </div>
    </div>
</div>
@endsection