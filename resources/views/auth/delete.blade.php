<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Delete Account</title>
    <link rel="stylesheet" href="{{url('assets/vendors/mdi/css/materialdesignicons.min.css')}}">
    <link rel="stylesheet" href="{{url('assets/vendors/css/vendor.bundle.base.css')}}">
    <link rel="stylesheet" href="{{url('assets/css/style.css')}}">
    <link rel="shortcut icon" href="{{url('assets/images/favicon.ico')}}" />
  </head>
  <body>
    
    
  <div class="container-scroller">
    <div class="container-fluid page-body-wrapper full-page-wrapper">
      <div class="content-wrapper d-flex align-items-center auth">
        <div class="row flex-grow">
          <div class="col-lg-4 mx-auto">
            <div class="auth-form-light text-left p-5">
              <div class="brand-logo">
                <!-- <img src="{{url('assets/images/logo.svg')}}"> -->
                <h2 class="login-head">Delete Account</h2>
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
              <form class="pt-3" method="POST" action="{{url('delete-account-post')}}">
                @csrf
                <div class="form-group">
                  <input type="email" class="form-control form-control-lg" id="email" name="email" placeholder="Enter email">
                </div>
                <div class="form-group">
                  <input type="password" class="form-control form-control-lg" id="password" name="password" placeholder="Enter password">
                </div>
                <div class="mt-3">
                  <button type="submit" class="btn btn-block btn-gradient-primary btn-lg font-weight-medium auth-form-btn">Submit</button>
                </div>
              </form>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
<script src="{{url('assets/vendors/js/vendor.bundle.base.js')}}"></script>
<script src="{{url('assets/js/off-canvas.js')}}"></script>
<script src="{{url('assets/js/hoverable-collapse.js')}}"></script>
<script src="{{url('assets/js/misc.js')}}"></script>
</body>
</html>