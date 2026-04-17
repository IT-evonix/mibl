<!DOCTYPE html>
<html lang="en">

<head>
<?php
header("X-XSS-Protection:1");
?>
     @if(Session::has('download.in.the.next.request'))
         <meta http-equiv="refresh" content="5;url={{ Session::get('download.in.the.next.request') }}">
      @endif
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MIBL</title>
    <!-- Favicons -->
    <link rel="icon" type="image/png" sizes="16x16" href="{{ENV('APP_URL')}}assets/img/M_BankLogo.png">

    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,600,600i,700,700i|Poppins:300,300i,400,400i,500,500i,600,600i,700,700i"
        rel="stylesheet">

    <!-- Template Main CSS File -->
    <link href="{{ENV('APP_URL')}}assets/css/style.css" rel="stylesheet">
    <link rel="stylesheet" href="{{ENV('APP_URL')}}assets/css/font-awesome.min.css">
    <link href="{{ENV('APP_URL')}}assets/css/responsive.css" rel="stylesheet">
    <link href="{{ENV('APP_URL')}}assets/css/buttons.dataTables.min.css" rel="stylesheet">
    <link rel="stylesheet" href="{{ENV('APP_URL')}}assets/css/bootstrap.min.css">
     <!-- Datatables CSS CDN -->
     <link rel="stylesheet" type="text/css" href="{{ENV('APP_URL')}}assets/css/jquery.dataTables.min.css">
     <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
     <link href="https://use.fontawesome.com/releases/v5.6.3/css/all.css" rel="stylesheet" integrity="sha384-UHRtZLI+pbxtHCWp1t77Bi1L4ZtiqrqD80Kn4Z8NTSRyMA2Fd33n5dQ8lWUE00s/" crossorigin="anonymous">
     <link href="https://cdn.syncfusion.com/ej2/material.css" rel="stylesheet">
     <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
		<base href="{{URL::to('/')}}"> 
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
 
    <script>
    $(document).ready(function(){
    $(document).bind("contextmenu",function(e){
    return false;
    });
    });
    </script>
    
    </head>

<body>
    <!-- start header -->
    <!-- <header id="header" class="header-fixed fixed-top"> -->
    <header id="header" class="header-fixed ">
        <div class="header-limiter">
            <h1>
                <img src="{{ENV('APP_URL')}}assets/img/logo.png" alt="logo" class="header-logo" title="">
            </h1>
            <!-- <nav>
                <a href="#" class="selected">Hi<span> Sonali</span></a>
                <img src="{{ENV('APP_URL')}}assets/img/user.png" class="img-fluid pl-2">
                <a class="line"></a>
            </nav> -->

 <?php 
 $name=session('name');
 $arr=explode(" ",$name);
 ?>
            <nav>   <div class="dropdown">
  <div class="dropbtn">
  <a href="#" class="selected">Hi<span> {{$arr[0]}}</span></a>
                <img src="{{ENV('APP_URL')}}assets/img/img15.png" class="img-fluid pl-2">
                <!-- <a class="line"></a> -->
</div>
  <div class="dropdown-content">
    <a href="{{ENV('APP_URL')}}logout">Logout</a>
  </div>
</div>
</nav>

        </div>
    </header>
    <!-- End header -->