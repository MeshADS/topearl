<!DOCTYPE html>
<html>
  <head>
    <meta http-equiv="content-type" content="text/html;charset=UTF-8" />
    <meta charset="utf-8" />
    <title>Admin :: Acorns And Oaks Academy</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />
    <link rel="apple-touch-icon" href="assets/wcp/pages/ico/60.png">
    <link rel="apple-touch-icon" sizes="76x76" href="{{ URL::to('assets/wcp/pages/ico/76.png') }}">
    <link rel="apple-touch-icon" sizes="120x120" href="{{ URL::to('assets/wcp/pages/ico/120.png') }}">
    <link rel="apple-touch-icon" sizes="152x152" href="{{ URL::to('assets/wcp/pages/ico/152.png') }}">
    <link rel="icon" type="image/x-icon" href="favicon.ico" />
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-touch-fullscreen" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="default">
    <meta content="" name="description" />
    <meta content="" name="author" />
    {{ HTML::style("assets/wcp/plugins/pace/pace-theme-flash.css") }}
    {{ HTML::style("assets/wcp/plugins/boostrapv3/css/bootstrap.min.css") }}
    {{ HTML::style("assets/wcp/plugins/font-awesome/css/font-awesome.css") }}
    {{ HTML::style("assets/wcp/plugins/jquery-scrollbar/jquery.scrollbar.css") }}
    {{ HTML::style("assets/wcp/plugins/bootstrap-select2/select2.css") }}
    {{ HTML::style("assets/wcp/plugins/switchery/css/switchery.min.css") }}
    {{ HTML::style("assets/wcp/pages/css/pages-icons.css") }}
    <link class="main-stylesheet" href="{{ URL::to('assets/wcp/pages/css/pages.css') }}" rel="stylesheet" type="text/css" />
    @yield("stylesheet")
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
    <script type="text/javascript">
        window.Site = window.Site || {};
        //Config
        Site.Config = Site.Config || {};
        Site.Config.env = "{{ App::environment() }}";
        Site.Config.url = "{{ Config::get('app.url') }}";
        Site.Config.token = "{{ Session::token() }}";
    </script>
    <!-- Boot Data -->
    @yield("bootdata")
  </head>
  <body class="fixed-header menu-pin">
    
    @if(!is_null($view_message))
        @if($view_message["type"] == "page")
            <input type="hidden" class="view_message" value="{{ $view_message['message'] }}" data-level="{{ $view_message['level'] }}">
        @endif
    @endif

    @include('wcp::_partials.html.side_panel')
    <!-- START PAGE-CONTAINER -->
    <div class="page-container">
      @include('wcp::_partials.html.header')
      <!-- START PAGE CONTENT WRAPPER -->
      <div class="page-content-wrapper">
        <!-- START PAGE CONTENT -->
        <div class="content">
            @yield('jumbotron')
            @yield('content')
        </div>
        <!-- END PAGE CONTENT -->
       @include('wcp::_partials.html.header')
      </div>
      <!-- END PAGE CONTENT WRAPPER -->
    </div>
    <!-- END PAGE CONTAINER -->
    <!-- BEGIN VENDOR JS -->
    {{ HTML::script("assets/wcp/plugins/pace/pace.min.js") }}
    {{ HTML::script("assets/wcp/plugins/jquery/jquery-1.8.3.min.js") }}
    {{ HTML::script("assets/wcp/plugins/modernizr.custom.js") }}
    {{ HTML::script("assets/wcp/plugins/jquery-ui/jquery-ui.min.js") }}
    {{ HTML::script("assets/wcp/plugins/boostrapv3/js/bootstrap.min.js") }}
    {{ HTML::script("assets/wcp/plugins/jquery/jquery-easy.js") }}
    {{ HTML::script("assets/wcp/plugins/jquery-unveil/jquery.unveil.min.js") }}
    {{ HTML::script("assets/wcp/plugins/jquery-bez/jquery.bez.min.js") }}
    {{ HTML::script("assets/wcp/plugins/jquery-ios-list/jquery.ioslist.min.js") }}
    {{ HTML::script("assets/wcp/plugins/jquery-actual/jquery.actual.min.js") }}
    {{ HTML::script("assets/wcp/plugins/jquery-scrollbar/jquery.scrollbar.min.js") }}
    {{ HTML::script("assets/wcp/plugins/bootstrap-select2/select2.min.js") }}
    {{ HTML::script("assets/wcp/plugins/classie/classie.js") }}
    {{ HTML::script("assets/wcp/plugins/switchery/js/switchery.min.js") }}
    {{ HTML::script('assets/wcp/plugins/bower_components/moment/min/moment.min.js') }}
    {{ HTML::script('assets/wcp/plugins/bower_components/moment/livestamp.min.js') }}
    <!-- END VENDOR JS -->
    <!-- BEGIN CORE TEMPLATE JS -->
    {{ HTML::script("assets/wcp/pages/js/pages.min.js") }}
    <!-- END CORE TEMPLATE JS -->
    <!-- BEGIN PAGE LEVEL JS -->
    {{ HTML::script("assets/wcp/js/scripts.js") }}
    <script type="text/javascript">
        $(function(){
            // Init notification
            if($("input").hasClass("view_message")){
                var msg = $(".view_message").val(),
                    lvl = $(".view_message").attr("data-level");
                $('body').pgNotification({
                    message:msg,
                    style:'flip',
                    type: lvl,
                    timeout:60000,
                    showClose:true
                }).show();
            };
            // Init switchery
            var elems = Array.prototype.slice.call(document.querySelectorAll('.switchery'));
            // Success color: #10CFBD
            elems.forEach(function(html) {
                var thisColor = $(this).attr("data-color");
                if (typeof thisColor === undefined) { thisColor = '#10CFBD';};
                var switchery = new Switchery(html, {color: thisColor});
            });

            $('.panel-js').portlet();
        });
    </script>
    @yield("javascript")
    <!-- END PAGE LEVEL JS -->
  </body>
</html>