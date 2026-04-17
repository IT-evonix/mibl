<?php
    @$year= date("Y", strtotime($data->date_of_posting));
    @$month= date("m", strtotime($data->date_of_posting));
    if($data->file_type == 'image')
    {
    @$img=$_ENV['APP_URL']."uploads/".$year."/".$month."/"."thumbnail/".$data->photo_url;
    ?>

    <?php 
    }else if($data->file_type == 'other')
    { 
    $image_arr=explode(".",$data->photo_url);
    $image_type=end($image_arr);
    
    if(Str::upper($image_type) == 'PDF')
    {
   // @$img=$_ENV['APP_URL']."uploads/".@$year."/".@$month."/".@$data->photo_url;
    
    $images_path_b="uploads/".$year."/".$month."/".$data->photo_url;
    if (file_exists($images_path_b)) {
    $arr_2=explode(".",$data->photo_url);
    $photo_url_new=$arr_2[0];
    $images_path="uploads/".$year."/".$month."/".$photo_url_new.".jpg";
    if (!file_exists($images_path)) {
    $imgExt = new Imagick();
    $imgExt->readImage('uploads/'.$year.'/'.$month.'/'.$data->photo_url.'[0]');
    $imgExt->writeImages('uploads/'.$year.'/'.$month.'/'.$photo_url_new.'.jpg', true);
    }
    }
    @$img=$_ENV['APP_URL']."uploads/".$year."/".$month."/".$photo_url_new.'.jpg';
    ?>
    <?php 
    }else if (Str::upper($image_type) == 'PPT')
    {
    @$img=$_ENV['APP_URL']."uploads/".@$year."/".@$month."/".@$data->photo_url;
    ?>
    
    <?php
    }else	
    {
    $VIDEOID=$data->video_url;    
    $img=$_ENV['APP_URL']."uploads/".@$year."/".@$month."/".@$data->photo_url;
    $video_path="https://iframe.videodelivery.net/".$VIDEOID;
    $img="https://videodelivery.net/".$VIDEOID."/thumbnails/thumbnail.jpg"; 
    ?>
    
    <?php }
    }
    ?>


   <html lang="en">
   <head>
    <meta charset="UTF-8">
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <title>{{$data->file_name}}</title>
    
    
    <meta name="description" content="{{$data->file_description}}">
    <meta name="keywords" content="mbank mibl miblmbank">

    <meta property="og:title" content="{{$data->file_name}}">
    <meta property="og:site_name" content="MIBL Creatives">
    <meta property="og:url" content="{{ENV('APP_URL')}}creative-sharing-file/{{$data->share_link}}">
    <meta property="og:description" content="{{$data->file_description}}">
    <meta property="og:image" content="{{$img}}">   
    <meta property="og:image" itemprop="image" content="{{$img}}"> 
    <meta property="og:type" content="creatives">
     
    <meta name="csrf-token" content="qyOcbJfUjJt37tokmZQUlOMpcICVjsnbbIp7PA0c">
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.8.2/css/all.css" integrity="sha384-oS3vJWv+0UjzBfQzYUhtDYW+Pj2yciDJxpsK1OYPAYjqT085Qq/1cq5FLXAZQ7Ay" crossorigin="anonymous">
    <!-- Favicon Link -->
    <link rel="icon" type="image/png" sizes="16x16" href="{{ENV('APP_URL')}}assets/img/M_BankLogo.png">
    <script type='text/javascript' src='//platform-api.sharethis.com/js/sharethis.js#property=5d0e8cee0e548d0012f3df21&product=social-ab' async='async'></script>
    <!-- Global site tag (gtag.js) - Google Analytics -->
    <script async src="https://www.googletagmanager.com/gtag/js?id=UA-146081225-1"></script>
    <link rel="stylesheet" href="{{ENV('APP_URL')}}assets/css/bootstrap.min.css">
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script> 
	<script>
	$(document).ready(function(){
	$(document).bind("contextmenu",function(e){
	return false;
	});
	});
	</script>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>

<script>
$.noConflict();
function download_link_save(id)
{  
var _token = jQuery('input[name="_token"]').val();
jQuery.ajax({
url: "{{ENV('APP_URL')}}download_link_save",
type: "POST",
data: {id:id,_token: _token
},
success: function(result) {
}
})
}
</script>

  <style>
    #imagesidd{
    display: block;
    margin-left: auto;
    margin-right: auto;
    width: 80%;
    padding-bottom:20px;
    }
    .btn-danger.ml-2{
    background-color: #da3d2c !important;
    border-color: #da3d2c !important;
    }  
    
  </style>
  
  
  </head>

    <body>
       {{ csrf_field() }}   
   <?php 
   
   
   $datarestricted_link="Anyone with the link"; 
   
   if($datarestricted_link != 'Restricted') { ?>
    <br>  
    <div class="container-fluid">
    <div class="row">
    <div class="col-sm-12 col-md-12 col-lg-12 mt-2">   

    <?php
    $image_arr=explode(".",$data->photo_url);
    $other_type=end($image_arr);
    ?>
    
    @if(@$data->file_type == 'other')
    @if(Str::upper($other_type) == 'MP4')
    <a  id="ids{{$data->id}}" href="https://videodelivery.net/{{$VIDEOID}}/downloads/default.mp4" type="button" class="btn download btn-danger ml-2" download onclick="download_link_save('<?php echo $data->share_link;?>')">Download</a>
    @else
    <a id="ids{{$data->id}}" href="{{ENV('APP_URL')}}uploads/{{@$year}}/{{@$month}}/{{@$data->photo_url}}" type="button" class="btn download btn-danger ml-2" download onclick="download_link_save('<?php echo $data->share_link;?>')">Download</a>
    @endif
    @else
    <a id="ids{{$data->id}}" href="{{ENV('APP_URL')}}uploads/{{@$year}}/{{@$month}}/original/{{@$data->photo_url}}" type="button" class="btn download btn-danger ml-2" download onclick="download_link_save('<?php echo $data->share_link;?>')">Download</a>
    @endif
    </div>
    <div class="col-sm-12 col-md-12 col-lg-12 mt-2">
    <?php
    @$year= date("Y", strtotime($data->date_of_posting));
    @$month= date("m", strtotime($data->date_of_posting));
    if($data->file_type == 'image')
    {
    @$img=$_ENV['APP_URL']."uploads/".$year."/".$month."/"."original/".$data->photo_url;
    @$img_popup=$_ENV['APP_URL']."uploads/".$year."/".$month."/"."original/".$data->photo_url;
    ?>
    <img src="{{$img}}" id="imagesidd" >
    <?php 
    }else if($data->file_type == 'other')
    { 
    $image_arr=explode(".",$data->photo_url);
    $image_type=end($image_arr);
    
    if(Str::upper($image_type) == 'PDF')
    {
    @$img=$_ENV['APP_URL']."uploads/".@$year."/".@$month."/".@$data->photo_url;
    
    $images_path_b="uploads/".$year."/".$month."/".$data->photo_url;
    if (file_exists($images_path_b)) {
    $arr_2=explode(".",$data->photo_url);
    $photo_url_new=$arr_2[0];
    $images_path="uploads/".$year."/".$month."/".$photo_url_new.".jpg";
    if (!file_exists($images_path)) {
    $imgExt = new Imagick();
    $imgExt->readImage('uploads/'.$year.'/'.$month.'/'.$data->photo_url.'[0]');
    $imgExt->writeImages('uploads/'.$year.'/'.$month.'/'.$photo_url_new.'.jpg', true);
    }
    }
    @$images_path_x=$_ENV['APP_URL']."uploads/".$year."/".$month."/".$photo_url_new.'.jpg';
    
    
    ?>
    <!--<embed src="{{$img}}#toolbar=0" id="imagesidd" style="height:700px;">-->
        <iframe src="{{$img}}#navpanes=0" width="20%" height="700px" id="imagesidd" class='fgffh' >

    <?php 
    }else if (Str::upper($image_type) == 'PPT')
    {
    @$img=$_ENV['APP_URL']."uploads/".@$year."/".@$month."/".@$data->photo_url;
    ?>
    
    <?php
    }else
    {
    $VIDEOID=$data->video_url;    
    $img=$_ENV['APP_URL']."uploads/".@$year."/".@$month."/".@$data->photo_url;
    $video_path="https://iframe.videodelivery.net/".$VIDEOID;
    $thumbnail="https://videodelivery.net/".$VIDEOID."/thumbnails/thumbnail.jpg"; 
    ?>
    <iframe src="https://iframe.videodelivery.net/{{$VIDEOID}}" style="border: none;" height="700" width="800"
    allow="accelerometer; gyroscope; autoplay; encrypted-media; picture-in-picture;"
    allowfullscreen="true" id="imagesidd"
    ></iframe>
    
    <?php }
    }
    ?>
   </div>
    </div>
  </div>   
  <?php  } else {  ?>
  <style>
* {
-webkit-box-sizing: border-box;
box-sizing: border-box;
}

body {
padding: 0;
margin: 0;
}

#notfound {
position: relative;
height: 95vh;
background: #f6f6f6;
}

#notfound .notfound {
position: absolute;
left: 50%;
top: 50%;
-webkit-transform: translate(-50%, -50%);
-ms-transform: translate(-50%, -50%);
transform: translate(-50%, -50%);
}

.notfound {
max-width: 767px;
width: 100%;
line-height: 1.4;
padding: 110px 40px;
text-align: center;
background: #fff;
-webkit-box-shadow: 0 15px 15px -10px rgba(0, 0, 0, 0.1);
box-shadow: 0 15px 15px -10px rgba(0, 0, 0, 0.1);
}

.notfound .notfound-404 {
position: relative;
height: 180px;
}

.notfound .notfound-404 h1 {
font-family: 'Roboto', sans-serif;
position: absolute;
left: 50%;
top: 50%;
-webkit-transform: translate(-50%, -50%);
-ms-transform: translate(-50%, -50%);
transform: translate(-50%, -50%);
font-size: 165px;
font-weight: 700;
margin: 0px;
color: #262626;
text-transform: uppercase;
}


.notfound h2 {
font-family: 'Roboto', sans-serif;
font-size: 22px;
font-weight: 400;
text-transform: uppercase;
color: #151515;
margin-top: 0px;
margin-bottom: 25px;
}

</style>
<div class="container-fluid">
<div class="row">
<div class="col-sm-12 col-md-12 col-lg-12 mt-2">   

<div id="notfound">
<div class="notfound">
<h2>Access to this link is restricted</h2>
</div>
</div>

</div>
</div>
</div>


  
  <?php } ?>



    </body>
    
    
    
    </html>
    
    


