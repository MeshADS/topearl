<!DOCTYPE html>
<html>
  <head>
    <meta http-equiv="content-type" content="text/html;charset=UTF-8" />
    <meta charset="utf-8" />
    <title>{{ $basic_data->shortname }} Backend</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />
    <link rel="apple-touch-icon" href="assets/wcp/pages/ico/60.png">
    <link rel="apple-touch-icon" sizes="76x76" href="assets/wcp/pages/ico/76.png">
    <link rel="apple-touch-icon" sizes="120x120" href="assets/wcp/pages/ico/120.png">
    <link rel="apple-touch-icon" sizes="152x152" href="assets/wcp/pages/ico/152.png">
    <link rel="icon" type="image/x-icon" href="favicon.ico" />
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-touch-fullscreen" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="default">
    <meta content="" name="description" />
    <meta content="" name="author" />
    <link href="{{URL::to('assets/wcp/plugins/pace/pace-theme-flash.css')}}" rel="stylesheet" type="text/css" />
    <link href="{{URL::to('assets/wcp/plugins/boostrapv3/css/bootstrap.min.css')}}" rel="stylesheet" type="text/css" />
    <link href="{{URL::to('assets/wcp/plugins/font-awesome/css/font-awesome.css')}}" rel="stylesheet" type="text/css" />
    <link href="{{URL::to('assets/wcp/plugins/jquery-scrollbar/jquery.scrollbar.css')}}" rel="stylesheet" type="text/css" media="screen" />
    <link href="{{URL::to('assets/wcp/plugins/bootstrap-select2/select2.css')}}" rel="stylesheet" type="text/css" media="screen" />
    <link href="{{URL::to('assets/wcp/plugins/switchery/css/switchery.min.css')}}" rel="stylesheet" type="text/css" media="screen" />
    <link href="{{URL::to('assets/wcp/pages/css/pages-icons.css')}}" rel="stylesheet" type="text/css">
    <link class="main-stylesheet" href="{{URL::to('assets/wcp/pages/css/pages.css')}}" rel="stylesheet" type="text/css" />
    <!--[if lte IE 9]>
        <link href="assets/wcp/pages/css/ie9.css" rel="stylesheet" type="text/css" />
    <![endif]-->
    <script type="text/javascript">
    window.onload = function()
    {
      // fix for windows 8
      if (navigator.appVersion.indexOf("Windows NT 6.2") != -1)
        document.head.innerHTML += '<link rel="stylesheet" type="text/css" href="assets/wcp/pages/css/windows.chrome.fix.css" />'
    }
    </script>
  </head>
  <body class="fixed-header   ">
    <!-- START PAGE-CONTAINER -->
    <div class="login-wrapper ">
      <!-- START Login Background Pic Wrapper-->
      <div class="bg-pic">
        <!-- START Background Pic-->
        <img src="{{ URL::to('assets/wcp/img/login/wcp_login_bg.png') }}" data-src="{{ URL::to('assets/wcp/img/login/wcp_login_bg.png') }}" data-src-retina="{{ URL::to('assets/wcp/img/login/wcp_login_bg.png') }}" alt="" class="lazy" style="opacity:1;">
        <!-- END Background Pic-->
        <!-- START Background Caption-->
        <div class="bg-caption pull-bottom sm-pull-bottom text-white p-l-20 m-b-20">
          <h2 class="semi-bold text-white">
					Welcome</h2>
          <p class="small">
            Please login with your email and password © {{ date('Y') }} {{ $basic_data->fullname }}.
          </p>
        </div>
        <!-- END Background Caption-->
      </div>
      <!-- END Login Background Pic Wrapper-->
      <!-- START Login Right Container-->
      <div class="login-container bg-white">
        <div class="p-l-50 m-l-20 p-r-50 m-r-20 p-t-50 m-t-30 sm-p-l-15 sm-p-r-15 sm-p-t-40">
          <img src="{{ URL::to($basic_data->logo) }}" alt="logo" data-src="{{ URL::to($basic_data->logo) }}" height="50px" data-src-retina="{{ URL::to($basic_data->logo2x) }}">
          <p class="p-t-35">Sign into your account</p>
          <!-- START Login Form -->
          {{ Form::open(['id'=>'form-login', 'class'=>'p-t-15', 'role'=>'form', 'url'=>'admin/login']) }}
            @if(!is_null($view_message))
              @if($view_message['type'] == 'login')
                <div class="alert alert-{{$view_message['level']}} alert-dismissable" role="alert">
                  <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true"><i class="fa fa-times"></i></span></button>
                  {{ $view_message['message'] }}
                </div>
              @endif
            @endif
            <!-- START Form Control-->
            <div class="form-group form-group-default">
              <label>Email</label>
              <div class="controls">
                <input type="text" name="email" placeholder="User Email" class="form-control" required>
              </div>
            </div>
            <!-- END Form Control-->
            <!-- START Form Control-->
            <div class="form-group form-group-default">
              <label>Password</label>
              <div class="controls">
                <input type="password" class="form-control" name="password" placeholder="Password" required>
              </div>
            </div>
            <!-- START Form Control-->
            <div class="row">
              <div class="col-md-6 no-padding">
                <div class="checkbox ">
                  <input type="checkbox" name="remember" value="1" id="checkbox1">
                  <label for="checkbox1">Remember me?</label>
                </div>
              </div>
              <div class="col-md-6 text-right">
               
              </div>
              <input type="hidden" name="return" value="{{ \Input::get('return','admin') }}">
            </div>
            <!-- END Form Control-->
            <button class="btn btn-success btn-cons m-t-10" type="submit">Sign in</button>
          {{ Form::close() }}
          <!--END Login Form-->
          <!-- <div class="pull-bottom sm-pull-bottom">
            <div class="m-b-30 p-r-80 sm-m-t-20 sm-p-r-15 sm-p-b-20 clearfix">
              <div class="col-sm-3 col-md-2 no-padding">
                <img alt="" class="m-t-5" data-src="assets/wcp/img/demo/pages_icon.png" data-src-retina="assets/wcp/img/demo/pages_icon_2x.png" height="60" src="assets/wcp/img/demo/pages_icon.png" width="60">
              </div>
              <div class="col-sm-9 no-padding m-t-10">
                <p><small>
		        		Create a pages account. If you have a facebook account, log into it for this process. Sign in with <a href="#" class="text-info">Facebook</a> or <a href="#" class="text-info">Google</a></small>
                </p>
              </div>
            </div>
          </div> -->
        </div>
      </div>
      <!-- END Login Right Container-->
    </div>
    <!-- END PAGE CONTAINER -->
    <!-- BEGIN VENDOR JS -->
    <script src="{{URL::to('assets/wcp/plugins/pace/pace.min.js')}}" type="text/javascript"></script>
    <script src="{{URL::to('assets/wcp/plugins/jquery/jquery-1.8.3.min.js')}}" type="text/javascript"></script>
    <script src="{{URL::to('assets/wcp/plugins/modernizr.custom.js')}}" type="text/javascript"></script>
    <script src="{{URL::to('assets/wcp/plugins/jquery-ui/jquery-ui.min.js')}}" type="text/javascript"></script>
    <script src="{{URL::to('assets/wcp/plugins/boostrapv3/js/bootstrap.min.js')}}" type="text/javascript"></script>
    <script src="{{URL::to('assets/wcp/plugins/jquery/jquery-easy.js')}}" type="text/javascript"></script>
    <script src="{{URL::to('assets/wcp/plugins/jquery-unveil/jquery.unveil.min.js')}}" type="text/javascript"></script>
    <script src="{{URL::to('assets/wcp/plugins/jquery-bez/jquery.bez.min.js')}}"></script>
    <script src="{{URL::to('assets/wcp/plugins/jquery-ios-list/jquery.ioslist.min.js')}}" type="text/javascript"></script>
    <script src="{{URL::to('assets/wcp/plugins/jquery-actual/jquery.actual.min.js')}}"></script>
    <script src="{{URL::to('assets/wcp/plugins/jquery-scrollbar/jquery.scrollbar.min.js')}}"></script>
    <script type="text/javascript" src="{{URL::to('assets/wcp/plugins/bootstrap-select2/select2.min.js')}}"></script>
    <script type="text/javascript" src="{{URL::to('assets/wcp/plugins/classie/classie.js')}}"></script>
    <script src="{{URL::to('assets/wcp/plugins/switchery/js/switchery.min.js')}}" type="text/javascript"></script>
    <script src="{{URL::to('assets/wcp/plugins/jquery-validation/js/jquery.validate.min.js')}}" type="text/javascript"></script>
    <!-- END VENDOR JS -->
    <!-- BEGIN CORE TEMPLATE JS -->
    <script src="{{URL::to('assets/wcp/pages/js/pages.min.js')}}"></script>
    <!-- END CORE TEMPLATE JS -->
    <!-- BEGIN PAGE LEVEL JS -->
    <script src="{{URL::to('assets/wcp/js/scripts.js')}}" type="text/javascript"></script>
    <!-- END PAGE LEVEL JS -->
    <script>
    $(function()
    {
      $('#form-login').validate()
    })
    </script>
  </body>
</html>