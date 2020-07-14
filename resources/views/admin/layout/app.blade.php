<!DOCTYPE html>
<html lang="en">
  <head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <!-- Meta, title, CSS, favicons, etc. -->
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="brand-id" content="{{auth('admin')->user()->brand_id}}">
    <title>Gentelella Alela! | </title>

    <!-- Bootstrap -->
    <link href="{{url('admin_template/css/bootstrap.min.css')}}" rel="stylesheet">
    <link href="{{url('admin_template/css/all.min.css')}}" rel="stylesheet">
    {{-- <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.13.0/css/fontawesome.min.css" rel="stylesheet"> --}}
    <!-- NProgress -->
    <link href="{{url('admin_template/css/nprogress.css')}}" rel="stylesheet">
    <link href="{{url('admin_template/vendors/iCheck/skins/flat/blue.css')}}" rel="stylesheet">

    <!-- jQuery custom content scroller -->
    <link href="{{url('admin_template/css/jquery.mCustomScrollbar.min.css')}}" rel="stylesheet"/>

    <link href="{{url('admin_template/css/dataTables.bootstrap.min.css')}}" rel="stylesheet"/>
    <link href="{{url('admin_template/vendors/dropzone/dist/min/dropzone.min.css')}}" rel="stylesheet"/>

    <link href="{{url('admin_template/vendors/pnotify/dist/pnotify.css')}}" rel="stylesheet"/>
    <link href="{{url('admin_template/vendors/pnotify/dist/pnotify.buttons.css')}}" rel="stylesheet"/>

    <link href="{{url('admin_template/vendors/select2/dist/css/select2.min.css')}}" rel="stylesheet"/>

    <link href="{{url('admin_template/vendors/gijgo-datetimepicker/css/gijgo.min.css')}}" rel="stylesheet"/>
    
    <!-- Custom Theme Style -->
    <link href="{{url('admin_template/css/custom.min.css')}}" rel="stylesheet">
    <link href="{{url('admin_template/css/my-styles.css')}}" rel="stylesheet">
<style>
  li.read {
    background: #fff!important;
    border-top: 1px solid #e0dddd;
  }
</style>
  </head>

  <body class="nav-md">
    <div class="container body">
      <div class="main_container">
        @include('admin.layout.left_sidebar')
        <!-- top navigation -->
        @include('admin.layout.top_nav')
        <!-- /top navigation -->

        <!-- page content -->
        @yield('content')
        <!-- /page content -->

        <!-- footer content -->
        @include('admin.layout.footer')
        <!-- /footer content -->
      </div>
    </div>

    @yield('before-scripts')
    <!-- jQuery -->
    <script src="{{url('admin_template/js/jquery.min.js')}}"></script>
    <script src="{{url('admin_template/js/bootstrap.min.js')}}"></script>
    <!-- FastClick -->
    <script src="{{url('admin_template/js/fastclick.js')}}"></script>
    <!-- NProgress -->
    <script src="{{url('admin_template/js/nprogress.js')}}"></script>
    <!-- jQuery custom content scroller -->
    <script src="{{url('admin_template/js/jquery.mCustomScrollbar.concat.min.js')}}"></script>
    <script src="{{url('admin_template/js/jquery.dataTables.min.js')}}"></script>
    <script src="{{url('admin_template/js/dataTables.bootstrap.min.js')}}"></script>
    
    <script src="{{url('admin_template/vendors/dropzone/dist/min/dropzone.min.js')}}"></script>
    <script src="{{url('admin_template/js/sweetalert2@9.js')}}"></script>
    <script src="{{url('admin_template/vendors/pnotify/dist/pnotify.js')}}"></script>
    <script src="{{url('admin_template/vendors/pnotify/dist/pnotify.buttons.js')}}"></script>
    <script src="{{url('admin_template/vendors/iCheck/icheck.min.js')}}"></script>
    <script src="{{url('admin_template/vendors/select2/dist/js/select2.min.js')}}"></script>

    <script src="{{url('admin_template/vendors/gijgo-datetimepicker/js/gijgo.min.js')}}"></script>

    {{-- <script src="{{url('admin_template/vendors/moment/moment.min.js')}}"></script>

    <script src="{{url('admin_template/vendors/bootstrap-datetimepicker/build/js/bootstrap-datetimepicker.min.js')}}"></script> --}}
    <!-- Custom Theme Scripts -->
    <script src="{{url('admin_template/js/custom.min.js')}}"></script>
    <script src="{{url('admin_template/js/dropzone-config.js')}}"></script>
    <script src="{{url('admin_template/js/my-scripts.js')}}"></script>
    <script src="{{url('admin_template/js/pusher.min.js')}}"></script>
    <script>
      Pusher.logToConsole = true;
      const brand_id = $('meta[name=brand-id]').attr("content")
      var pusher = new Pusher('8c4f80a61d10b83a842d', {
        cluster: 'ap1'
      });

      var channel = pusher.subscribe('my-channel');
      channel.bind('my-event', function(data) {
        if (data.type == 'ticket' && data.brand_id == brand_id) {
          let template = `
          <li>
            <a href="${data.route}">
              <span class="message">
                ${data.message}
              </span>
              <span>
                <span class="time">${data.time}</span>
              </span>
            </a>
          </li>
          `;
          $("#notiWrapper").prepend(template);
          $("#menu1 li").bind('click', openNoti());
          let count = parseInt($("#countUnreadMessage").text());
          $("#countUnreadMessage").text(count + 1);
        }
      });

      $("#markReadAll").click( function (e) {
        e.preventDefault();
        if ($("#menu1 div li").length > 0) {
          $.ajax({
          type: 'get',
          url: '/admin/mark-as-read-all',
          data: {},
          success: function (data) {
            console.log(data);
            
            if (data.status === 200) {
              $("#menu1 li").each(function () {
                $(this).addClass('as-read');
                $("#countUnreadMessage").text(0);
              })
            }
          },
          error: function (error) {
            console.log(error);
            
          }
        })
        }
        
        
      });

      $("#menu1 div li").click(function (e) {
        const href= $(this).children("a").attr('href');
        if (href) {
          window.location = href;
        }
      });

      function openNoti() {
        const href= $(this).children("a").attr('href');
        if (href) {
          window.location = href;
        }
      }
    </script>
    @yield('after-scripts')
  </body>
</html>
