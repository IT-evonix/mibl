<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <!-- Favicons -->
    <link href="" rel="icon">
    <link href="" rel="apple-touch-icon">

   
    <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,600,600i,700,700i|Poppins:300,300i,400,400i,500,500i,600,600i,700,700i"
        rel="stylesheet">

    <!-- Template Main CSS File -->
    <link href="{{ENV('APP_URL')}}assets/css/style.css" rel="stylesheet">
    <link href="{{ENV('APP_URL')}}assets/css/responsive.css" rel="stylesheet">

    <link rel="stylesheet" href="{{ENV('APP_URL')}}assets/css/font-awesome.min.css">
    <!-- <link rel="stylesheet" href="{{ENV('APP_URL')}}assets/css/font-awesome.css"> -->
    <link rel="stylesheet" href="{{ENV('APP_URL')}}assets/css/bootstrap.min.css">
    <style>
    input.btn.save {
    background: #D8002A 0% 0% no-repeat padding-box;
    border-radius: 25px;
    opacity: 1;
    color: #fff;
    padding: 8px 30px;
    margin-top: 15px;
    position: relative;
    left:60%;
}
    </style>

</head>

<body>
  
    <div id="login" class="container-fluid login-page">
        <img src="assets/img/logo.png" alt="logo" class="header-logo img-fluid" title="">
        <div class="row">

            <div class="col-lg-8 login1-banner">
                <div class="container">
                    
                </div>
                
            </div>

            <div class="col-lg-4">
            <form method="post" action="{{ url('/resetpassword') }}" class="log-form1">
                {{ csrf_field() }}
                @if(Session::has('successmsg'))
                <div class="alert alert-success alert-dismissible"  id="success_message" role="alert">
                  <button type="button" class="btn-close" data-bs-dismiss="alert" >
                    </button>
                  <h3 class="text-success"><i class="fa fa-check-circle"></i>Success</h3>
                  {{Session::get('successmsg')}}
                </div>
                @endif

                @if(Session::has('failmsg'))
                <div class="alert alert-warning alert-dismissible"  id="waring_message" role="alert">
                  <button type="button" class="btn-close" data-bs-dismiss="alert" >
                     </button>
                  <h3 class="text-warning"><i class="fa fa-exclamation-triangle"></i>Error!</h3>
                  {{Session::get('failmsg')}}
                </div>
                @endif

                      <h4>Forgot Password</h4>
                    <div class="group log-input">
                        <input type="email" id="email" name="email" placeholder="Enter Email id" required>
                        
                    </div>
                    <p class="form-text small text-muted pt-2" id="helpResetPasswordEmail">New password will be sent on registered email id...</p>
                    <div class="container-log-btn">
                       
                        <input id="" type="submit" class="btn save" name="btn_submit" value="Reset Password" >

                        <!-- <button type="button" class="btn save">Save changes</button> -->
                    </div> 
                </form>

            </div>
        </div>
        
    </div>
    <div class="banner2-img">
        <img src="assets/img/login-banner2.png" class="login-banner2 img-fluid">
    </div>
    


   <!-- <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>-->

</body>

</html>