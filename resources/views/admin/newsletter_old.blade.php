@include('admin/header')
@include('admin/side-menu') 

<?php 
$user_type_download_creative=session('user_type_download_creative');
$user_download_creative=session('user_download_creative');
?>

<style>
#myImg {
  border-radius: 5px;
  cursor: pointer;
  transition: 0.3s;
}

#myImg:hover {opacity: 0.7;}

/* The Modal (background) */
.modal {
  display: none; /* Hidden by default */
  position: fixed; /* Stay in place */
  z-index: 1; /* Sit on top */
  padding-top: 100px; /* Location of the box */
  left: 0;
  top: 0;
  width: 100%; /* Full width */
  height: 100%; /* Full height */
  overflow: auto; /* Enable scroll if needed */
  background-color: rgb(0,0,0); /* Fallback color */
  background-color: rgba(0,0,0,0.9); /* Black w/ opacity */
}

/* Modal Content (image) */
.modal-content {
  margin: auto;
  display: block;
  width: 80%;
  max-width: 700px;
}

/* Caption of Modal Image */
#caption {
  margin: auto;
  display: block;
  width: 80%;
  max-width: 700px;
  text-align: center;
  color: #ccc;
  padding: 10px 0;
  height: 150px;
  font-weight: 900;
}

/* Add Animation */
.modal-content, #caption {  
  -webkit-animation-name: zoom;
  -webkit-animation-duration: 0.6s;
  animation-name: zoom;
  animation-duration: 0.6s;
}

@-webkit-keyframes zoom {
  from {-webkit-transform:scale(0)} 
  to {-webkit-transform:scale(1)}
}

@keyframes zoom {
  from {transform:scale(0)} 
  to {transform:scale(1)}
}

/* The Close Button */
.close {
  position: absolute;
  top: 15px;
  right: 35px;
  color: #f1f1f1;
  font-size: 40px;
  font-weight: bold;
  transition: 0.3s;
}

.close:hover,
.close:focus {
  color: #bbb;
  text-decoration: none;
  cursor: pointer;
}
.page-item.active .page-link
{
  color: #fff !important;
  background-color: #d8002a !important;
  border-color: #d8002a !important;
}
.page-link{
  color: #d8002a !important;
}

.col-lg-12.main-photo{
    background-color: #eee;
}

#photos{
    column-count: 5 !important;
}


/* 100% Image Width on Smaller Screens */
@media only screen and (max-width: 700px){
  .modal-content {
    width: 100%;
  }
}

</style>   
<?php
$user_type=session('login_type');
?>
        <div class="col-lg-10 right_mainbox"><!-- col-lg-10 start-->
               <div class="row">
                    <div class="col-lg-12">
                           <div id="search1" class="wrapper4">
                              <form class="search" action="view-newsletter" method="get" role="form" id="searchForm" >
                              {{ csrf_field() }}    
                                <input type="text" placeholder="Campaign From"  class='monthnorder datepicker' name="from_date" value="<?php echo $from_date;?>"><i class="fa fa-calendar search_class1"></i>&nbsp;&nbsp;
                                <input type="text" placeholder="Campaign To"  class='monthnorder datepicker' name="to_date" value="<?php echo $to_date;?>"><i class="fa fa-calendar search_class1"></i>
                                <button type="submit" class="btn searchButton1"><img src="assets/img/search.png" class="img-fluid">
                                 Search</button>
                              
                           </div>
                        </div>
                </div>
               <br>
               </form>


               <!-- Preview code  start-->
               <div class="row">
                  <div class="col-lg-12 main-photo">
                     <section id="photos">

                        @if(count($creatives) > 0)
                        @foreach($creatives as $image) 


                        <?php
                        @$year=date("Y", strtotime($image->date_of_posting));
                        @$month=date("m", strtotime($image->date_of_posting));

                        $image_arr=explode(".",$image->photo_url);
                        $other_type=end($image_arr);
                        $VIDEOID=$image->video_url;
                        ?>
                        @if($image->source_file != '')
                        <input type="hidden" id="source_url_download_{{$image->id}}" value="{{ENV('APP_URL')}}newsletter/{{$year}}/{{$month}}/upload_source_file/{{$image->source_file}}">
                       @else
                       <input type="hidden" id="source_url_download_{{$image->id}}" value="">
                       @endif
                        @if($image->file_type == 'image')
                        <input type="hidden" id="url_download_{{$image->id}}" value="{{ENV('APP_URL')}}newsletter/{{$year}}/{{$month}}/original/{{$image->photo_url}}">
                        @else
                        @if($other_type == 'mp4')
                        <input type="hidden" id="url_download_{{$image->id}}" value="https://videodelivery.net/{{$VIDEOID}}/downloads/default.mp4">
                        @else
                        <input type="hidden" id="url_download_{{$image->id}}" value="{{ENV('APP_URL')}}newsletter/{{$year}}/{{$month}}/{{$image->photo_url}}">
                        @endif
                        @endif
                        <a href="{{ENV('APP_URL')}}flipbook/{{base64_encode($image->id)}}" target="_blank">
                        <?php 
                        if($image->file_type == 'other' && ($image->document_name == 'pdf' ) ){
                        $images_path_b="newsletter/".$year."/".$month."/".$image->photo_url;
                        if (file_exists($images_path_b)) {
                        $arr_2=explode(".",$image->photo_url);
                        $photo_url=$arr_2[0];
                        $images_path="newsletter/".$year."/".$month."/".$photo_url.".jpg";
                        if (!file_exists($images_path)) {
                        $imgExt = new Imagick();
                        $imgExt->readImage(public_path('newsletter/'.$year.'/'.$month.'/'.$image->photo_url.'[0]'));
                        $imgExt->writeImages('newsletter/'.$year.'/'.$month.'/'.$photo_url.'.jpg', true);
                        }
                        }
                        }
                        ?>
                        @if($image->file_type == 'other' && ($image->document_name == 'pdf' ))
                        <?php 
                        $arr_2=explode(".",$image->photo_url);
                        $photo_url=$arr_2[0];
                        ?>
                         <img src="{{ENV('APP_URL')}}newsletter/{{$year}}/{{$month}}/{{$photo_url}}.jpg" class="img-fluid">
                         @else
                         
                         @endif
                        </a>
                        @endforeach
                        @else
                        <span style="margin:10px"><h4><b>No data found</b></h4></span>                        
                        @endif
                     </section>
                  </div>
                  
                  <!-- Preview code End -->
          
             
               </div><!-- col-lg-10 close-->
               <br>
@if ($creatives->lastPage() > 1)

<?php  
$str_pre=$creatives->url($creatives->currentPage()-1);
$str_pre1=explode("?",$str_pre);
$search_pre=$str_pre1[0]."?".$str_pre1[1].'&search='.$searchValue."&from_date=".$from_date."&to_date=".$to_date;
?>

<?php  
$str_nxt=$creatives->url($creatives->currentPage()+1);
$str_nxt1=explode("?",$str_nxt);
$search_nxt=$str_nxt1[0]."?".$str_nxt1[1].'&search='.$searchValue."&from_date=".$from_date."&to_date=".$to_date;
?>
<nav aria-label="Page navigation example">
  <ul class="pagination">
    <li class="page-item {{ ($creatives->currentPage() == 1) ? ' disabled' : '' }}">
      <a class="page-link" href="{{$search_pre}}" aria-label="Previous">
        <span aria-hidden="true">&laquo;</span>
        <span class="sr-only">Previous</span>
      </a>
    </li>
    @for ($i = 1; $i <= $creatives->lastPage(); $i++)
      <?php 
      $str=$creatives->url($i);
      $str1=explode("?",$str);
      $search=$str1[0]."?".$str1[1].'&search='.$searchValue."&from_date=".$from_date."&to_date=".$to_date;
      ?>
    <li class="page-item {{ ($creatives->currentPage() == $i) ? ' active' : '' }}"><a class="page-link" href="{{ $search }}">{{ $i }}</a></li>
    @endfor
    <li class="page-item {{ ($creatives->currentPage() == $creatives->lastPage()) ? ' disabled' : '' }}">
      <a class="page-link" href="{{$search_nxt}}" aria-label="Next">
        <span aria-hidden="true">&raquo;</span>
        <span class="sr-only">Next</span>
      </a>
    </li>
  </ul>
</nav>
@endif

<div id="myModal" class="modal">
  <span class="close">&times;</span>
  <img class="modal-content" id="img01">
  <div id="caption"></div>
</div>


   
@include('admin/footer')




    <script>
    $(document).ready(function () {
    var element = document.getElementById("manage_newsletter");
    document.getElementById("mastersmanage_newsletter").style.display = "block";

    element.classList.add("active");
    var element1 = document.getElementById("menu_newsletter");
    element1.classList.add("open_meunbox");
    });
    </script>  


<!-- Image Onview  -->

    <script>
    // Get the modal
    var modal = document.getElementById("myModal");
    function zoom_image(id){
    // Get the image and insert it inside the modal - use its "alt" text as a caption
    var img = document.getElementById(id);
    var modalImg = document.getElementById("img01");
    var captionText = document.getElementById("caption");
    img.onclick = function(){
    var images = this.src.replace('preview','original');
    modal.style.display = "block";
    modalImg.src = images;
    // captionText.innerHTML = document.getElementById('file_name').innerHTML;
    }

    // Get the <span> element that closes the modal
    var span = document.getElementsByClassName("close")[0];

    // When the user clicks on <span> (x), close the modal
    span.onclick = function() { 
    modal.style.display = "none";
    }
    }
    </script>




    <script>
    // Add active class to the current button (highlight it)
    var header = document.getElementById("myDIV");
    var btns = header.getElementsByClassName("btn");
    for (var i = 0; i < btns.length; i++) {
    btns[i].addEventListener("click", function () {
    var current = document.getElementsByClassName("active");
    current[0].className = current[0].className.replace(" active", "");
    this.className += " active";
    });
    }
    </script>

      
    <script>
    $(function(){
    $("#thumbnail img:eq(0)").nextAll().hide();
    $("#photos img").click(function(e){
    var index = $(this).index();
    $("#thumbnail img").eq(index).show().siblings().hide();
    });
    });
    </script>

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.4.1/jquery.js"></script>
    <script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.7.2/jquery-ui.min.js"></script>
    <link rel="stylesheet" type="text/css" media="screen" href="https://ajax.googleapis.com/ajax/libs/jqueryui/1.7.2/themes/base/jquery-ui.css">

    <script type="text/javascript"> 
    $(function() {
    $('.datepicker').datepicker( {
    changeMonth: true,
    changeYear: true,
    showButtonPanel: true,
    dateFormat: 'MM yy',
    onSelect: function(dateText, inst) { 
    $(this).datepicker('setDate', new Date(inst.selectedYear, inst.selectedMonth, 1));
    }
    });
    });
    </script>

   </body>
</html>



