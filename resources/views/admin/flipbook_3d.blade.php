
<?php
@$year=date("Y", strtotime($data->date_of_posting));
@$month=date("m", strtotime($data->date_of_posting));
@$photo_url=$data->photo_url;

$filename1="newsletter/".$year."/".$month."/".$photo_url; 

?>


<!DOCTYPE html>
<html>

<head>

<base href="{{URL::to('/')}}"> 
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>

<link rel="stylesheet" type="text/css" href="{{ENV('APP_URL')}}assets/3dflipbook/css/flipbook.style.css">

<!--<script src="{{ENV('APP_URL')}}assets/3dflipbook/js/flipbook.min.js"></script>-->

<script type="text/javascript">

  $(document).ready(function () {
        $("#container").flipBook({
            pdfUrl:"{{ENV('APP_URL')}}newsletter/{{$year}}/{{$month}}/{{$photo_url}}",
            pages:[
            	{
                    title:"Cover", 
                    // htmlContent:'<a href="3d.html">link to 3d flipbook</a><p style="color:#FFF">HTML Content on the page</p><div style="position:absolute;top:400px;"><iframe width="640" height="390" src="https://www.youtube.com/embed/w53Lp1AFkpo" frameborder="0" allowfullscreen></iframe></div>'
                },

            	{
                    htmlContent:'<iframe width="707" height="1000" src="page_iframe.html" frameborder="0" allowfullscreen></iframe>'
                },

            	{
                    title:"Page 3"
                },

            	{},

            	{},

            	{},

            	{},

            	{
                    title:"End"
                },
            ]

        });

    })
	
	document.querySelectorAll('a[target="_blank"]').forEach(a=>{
    a.rel = "noopener noreferrer";
});
</script>

<!-- Flipbook StyleSheets -->
<link href="{{ENV('APP_URL')}}/assets/dflip/css/dflip.min.css" rel="stylesheet" type="text/css">
<!-- themify-icons.min.css is not required in version 2.0 and above -->
<link href="{{ENV('APP_URL')}}/assets/dflip/css/themify-icons.min.css" rel="stylesheet" type="text/css">

</head>

<body>
<!--<div id="container1">
    <p>Real 3D Flipbook has lightbox feature - book can be displayed in the same page with lightbox effect.</p>
    <p>Click on a book cover to start reading.</p>
    <img src="images/book2/thumb1.jpg" />
</div> -->

<div class="_df_book" id="flipbok_example" source="{{ENV('APP_URL')}}newsletter/{{$year}}/{{$month}}/{{$photo_url}}"></div>

<!-- Scripts -->
<script src="{{ENV('APP_URL')}}/assets/dflip/js/libs/jquery.min.js" type="text/javascript"></script>
<script src="{{ENV('APP_URL')}}/assets/dflip/js/dflip.min.js" type="text/javascript"></script>

</body>

</html>
