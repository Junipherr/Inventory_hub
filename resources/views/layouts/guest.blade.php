<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <!-- GLOBAL MAINLY STYLES-->
    <link href="{{ asset('assets/vendors/bootstrap/dist/css/bootstrap.min.css') }}" rel="stylesheet" />
    <link href="{{ asset('assets/vendors/font-awesome/css/font-awesome.min.css') }}" rel="stylesheet" />
    <link href="{{ asset('assets/vendors/themify-icons/css/themify-icons.css') }}" rel="stylesheet" />
    <!-- THEME STYLES-->
    <link href="{{ asset('assets/css/main.css') }}" rel="stylesheet" />
    <!-- PAGE LEVEL STYLES-->
    <link href="{{ asset('assets/css/pages/auth-light.css') }}" rel="stylesheet" />
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="bg-silver-300">
    <div class="content">
       
        <div class="brand">
            <a class="link" href="{{ url('/') }}">School Inventory</a>
        </div>
        {{ $slot }}
    </div>




     <!-- CORE PLUGINS -->
     <script src="{{ asset('assets/vendors/jquery/dist/jquery.min.js') }}"></script>
     <script src="{{ asset('assets/vendors/popper.js/dist/umd/popper.min.js') }}"></script>
     <script src="{{ asset('assets/vendors/bootstrap/dist/js/bootstrap.min.js') }}"></script>
     <!-- PAGE LEVEL PLUGINS -->
     <script src="{{ asset('assets/vendors/jquery-validation/dist/jquery.validate.min.js') }}"></script>
     <!-- CORE SCRIPTS -->
     <script src="{{ asset('assets/js/app.js') }}"></script>
 
     <script type="text/javascript">
         $(function () {
             $('#login-form').validate({
                 errorClass: "help-block",
                 rules: {
                     email: {
                         required: true,
                         email: true
                     },
                     password: {
                         required: true
                     }
                 },
                 highlight: function (e) {
                     $(e).closest(".form-group").addClass("has-error")
                 },
                 unhighlight: function (e) {
                     $(e).closest(".form-group").removeClass("has-error")
                 },
             });
         });
     </script>
 </body>
 
 </html>