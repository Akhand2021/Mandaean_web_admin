@extends('layouts.front')
@section('title','Forgot')
@section('pagetitle','Forgot')
@section('content')
  <div class="container-scroller">
    <div class="container-fluid page-body-wrapper full-page-wrapper">
      <div class="content-wrapper d-flex align-items-center auth">
        <div class="row flex-grow">
          <div class="col-lg-4 mx-auto">
            <div class="auth-form-light text-left p-5">
              <div class="brand-logo">
                <!-- <img src="{{url('assets/images/logo.svg')}}"> -->
                <h2 class="login-head">Forgot Password</h2>
              </div>
              <!-- <h4>Hello! let's get started</h4> -->
              <!-- <h6 class="font-weight-light">Sign in to continue.</h6> -->
              @if(session('error'))
                <div class="alert alert-danger alert-box alert-dismissible fade show" role="alert">
                    {{session('error')}}
                    <!-- <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button> -->
                </div>
                @endif
                @if(session('message'))
                <div class="alert alert-success alert-box alert-dismissible fade show" role="alert">
                    {{session('message')}}
                    <!-- <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button> -->
                </div>
                @endif
              <form class="pt-3" method="POST" action="{{url('forgot-password')}}">
                @csrf
                <div class="form-group">
                  <input type="hidden" name="id" value="{{$id}}">
                  <input type="email" class="form-control form-control-lg" id="email" name="email" value="{{$email}}" readonly>
                </div>
                <div class="form-group">
                  <input type="password" class="form-control form-control-lg" id="password" name="password" placeholder="Enter new password" value="{{old('password')}}">
                </div>
                <div class="form-group">
                  <input type="password" class="form-control form-control-lg" id="confirm_password" name="confirm_password" placeholder="Enter confirm password" value="{{old('confirm_password')}}">
                </div>
                <div class="mt-3">
                  <button type="submit" class="btn btn-block btn-gradient-primary btn-lg font-weight-medium auth-form-btn">UPDATE PASSWORD</button>
                </div>
              </form>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
@endsection