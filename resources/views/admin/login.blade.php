 <!DOCTYPE html>
<html lang="en">

<head>
<?php
header("X-XSS-Protection:1");
?>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <!-- Favicons -->
    <link rel="icon" type="image/png" sizes="16x16" href="{{ENV('APP_URL')}}assets/img/M_BankLogo.png">
    <base href="{{URL::to('/')}}"> 
   
	<link href="https://fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,600,600i,700,700i|Poppins:300,300i,400,400i,500,500i,600,600i,700,700i"
	rel="stylesheet">
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
	<link href="https://use.fontawesome.com/releases/v5.6.3/css/all.css" rel="stylesheet" integrity="sha384-UHRtZLI+pbxtHCWp1t77Bi1L4ZtiqrqD80Kn4Z8NTSRyMA2Fd33n5dQ8lWUE00s/" crossorigin="anonymous">

    <!-- Template Main CSS File -->
    <link href="{{ENV('APP_URL')}}assets/css/style.css" rel="stylesheet">
    <link href="{{ENV('APP_URL')}}assets/css/responsive.css" rel="stylesheet">

    <link rel="stylesheet" href="{{ENV('APP_URL')}}assets/css/font-awesome.min.css">
    <!-- <link rel="stylesheet" href="{{ENV('APP_URL')}}assets/css/font-awesome.css"> -->
    <link rel="stylesheet" href="{{ENV('APP_URL')}}assets/css/bootstrap.min.css">
    
    <meta name="csrf-token" content="{{ csrf_token() }}">
	
	<!--<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>-->
<style>
.log-form1 {
    width: 500px;
    margin: 4em auto;
    padding: 14% 7%;
    background: 0% 0% no-repeat padding-box padding-box rgb(255, 255, 255);
    border-radius: 49px;
    opacity: 1;
    position: relative !important;
    left: -22%;
    margin-top: 28%;
    /* box-shadow: 24px 8px 20px -18px #00000030; */
    box-shadow: -1px 4px 28px 0px rgb(0 0 0 / 75%);
}

</style>
</head>

<body>
    <!-- start header -->
    <!-- <header class="header-fixed">
        <div class="header-limiter container-fix">
            <h1>
                <img src="assets/img/logo.png" alt="logo" class="header-logo" title="">
            </h1>
            
        </div>
    </header> -->
    <!-- End header -->
    <div id="login" class="container-fluid login-page">
        <img src="assets/img/logo1.png" alt="logo" class="header-logo img-fluid" title="">
        <div class="row">
            <div class="col-lg-8 login1-banner">
                <!-- <img src="assets/img/login-banner1.png" class="login-banner"> -->
                <div class="container">
                    <!-- <h1 class="banner-title">M Bank</span> </h1> -->
                    <!-- <p class="banner-content">Mahindra Insurance Brokers.</p> -->
                    <img src="assets/img/M_BankLogo.png" class="M_BankLogo">
                </div>
                
            </div>
            <div class="col-lg-4">
            <!--<form method="post" action="{{ url('/logincheck') }}" class="log-form1">
                {{ csrf_field() }}

                @if(Session::has('successmsg'))
                  <div class="alert alert-success">
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                      <span aria-hidden="true">&times;</span> </button>
                    <h3 class="text-success"><i class="fa fa-check-circle"></i>Success</h3>
                    {{Session::get('successmsg')}}
                  </div>
                  @endif

                  @if(Session::has('failmsg'))
                  <div class="alert alert-warning">
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                      <span aria-hidden="true">&times;</span> </button>
                    <h3 class="text-warning"><i class="fa fa-exclamation-triangle"></i>Error!</h3>
                    {{Session::get('failmsg')}}
                  </div>
                  @endif

                  <div class="logininputclass">
                        <input type="email" id="username" name="email"   placeholder="Username" required>
                    </div>

                    <div class="logininputclass">
                        <input type="password" id="password" name="password"   placeholder="Password" required>
                    </div>
                    <div class="container-log-btn">
  
                        <input id="" type="submit" name="btn_submit" value="Login" class="log-form1-btn">
                    </div> 
                    <div class="forget">
                   
                    </div>
                </form>-->

                <div class="content">
                    <form method="post" action="{{ url('/logincheck') }}" class="log-form1" autocomplete="off">
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
					
					<input type="text" name="fakeusernameremembered" autocomplete="off" style="display:none">
					<input type="password" name="fakepasswordremembered" autocomplete="off" style="display:none">

                    <div class="field-login">
                        <span class="fa fa-user"></span>
                        <input type="text" id="username" name="sap_code" placeholder="Username" required class="pl-3" autocomplete="off">
                        <span  id="eye_icon1" class="field-icon toggle-password"></span>

                    </div>
                    <div class="field-login space">
                        <span class="fa fa-lock"></span>
                        <input type="password" id="password" name="password" placeholder="Password" required class="pl-3" autocomplete="off" readonly  onfocus="this.removeAttribute('readonly');">
                        <span  id="eye_icon1" toggle="#password-field" class="fa fa-fw fa-eye-slash field-icon toggle-password"></span>
                    </div>
                    <div class="field-login space radio-button">
                        
                        <input type="radio" id="user_login_type" name="user_login_type" value="Employee" style="width:10%;height:50%" required>
                        <label for="html">Employee </label>
                        <input type="radio" id="user_login_type" name="user_login_type" value="Vendor" style="width:10%;height:50%" required>
                        <label for="html">Vendor </label>
                        <input type="radio" id="user_login_type" name="user_login_type" value="Auditor" style="width:10%;height:50%" required>
                        <label for="html">Auditor </label>
                    </div>
                    <div class="container-log-btn">
                        <input id="" type="submit" name="btn_submit" value="Login" class="log-form1-btn">
                    </div>
                    </form>
                </div>

            </div>
        </div>
    <div class="banner2-img">
      <!--  <img src="assets/img/ridge.png" class="login-banner2 img-fluid">-->
    </div>
    </div>
    
    
  <script type="text/javascript" src="{{ENV('APP_URL')}}assets/js/jquery.min.js"></script>
    <script type="text/javascript" src="{{ENV('APP_URL')}}assets/js/bootstrap.min.js"></script>

</body>














<script>
$(".toggle-password").click(function() {
$(this).toggleClass("fa-eye-slash fa-eye");
var input = $($(this).attr("toggle"));
var inputType = $('#password').attr('type');
if (inputType == "password") {
    $('#password').attr('type', 'text');
} else {
    $('#password').attr('type', 'password');
}
});

document.querySelectorAll('a[target="_blank"]').forEach(function(link) {
    link.setAttribute('rel','noopener noreferrer');
});
</script>


</html>