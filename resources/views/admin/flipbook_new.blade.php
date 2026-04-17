<!doctype html>
<html lang="en">
<head>
<meta name="viewport" content="width = 1050, user-scalable = no" />
<script type="text/javascript" src="{{ENV('APP_URL')}}assets/flipbook/js/jquery.min.1.7.js"></script>
<script type="text/javascript" src="{{ENV('APP_URL')}}assets/flipbook/js/modernizr.2.5.3.min.js"></script>
</head>
<body>

<?php
@$year=date("Y", strtotime($data->date_of_posting));
@$month=date("m", strtotime($data->date_of_posting));
@$photo_url=$data->photo_url;
$image_arr=explode(".",$photo_url);
$pdffilename=$image_arr['0'];

$filename1="newsletter/".$year."/".$month."/".$photo_url; 
$path=$filename1;
$pdf = file_get_contents($path);
$number = preg_match_all("/\/Page\W/", $pdf, $dummy);
?>

<div class="flipbook-viewport">
	<div class="container">
		<div class="flipbook">
            <?php if($number != 1) { ?>
            <?php for($i=0;$i < $number; $i++) { ?>
			<div style="background-image:url({{ENV('APP_URL')}}newsletter/{{$year}}/{{$month}}/{{$pdffilename}}/{{$pdffilename}}-{{$i}}.jpg)"></div>
		     <?php }  } else { ?>
                <div style="background-image:url({{ENV('APP_URL')}}newsletter/{{$year}}/{{$month}}/{{$pdffilename}}/{{$pdffilename}}.jpg)"></div>
             <?php } ?>   

              
         </div>
	</div>
</div>


<script type="text/javascript">

function loadApp() {

	// Create the flipbook
	$('.flipbook').turn({
			// Width
			width:922,
			// Height
			height:600,
			// Elevation
			elevation: 50,
			// Enable gradients
			gradients: true,
			// Auto center this flipbook
			autoCenter: true,
            //display: 'double',
	});
}
// Load the HTML4 version if there's not CSS transform
yepnope({
	test : Modernizr.csstransforms,
	yep: ["{{ENV('APP_URL')}}assets/flipbook/js/turn.js"],
	nope: ["{{ENV('APP_URL')}}assets/flipbook/js/turn.html4.min.js"],
	both: ["{{ENV('APP_URL')}}assets/flipbook/css/basic.css"],
	complete: loadApp
});

</script>
</body>
</html>