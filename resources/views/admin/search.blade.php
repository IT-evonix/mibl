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

/* 100% Image Width on Smaller Screens */
@media only screen and (max-width: 700px){
  .modal-content {
    width: 100%;
  }
}
/*
.bootstrap-iso .formden_header h2, .bootstrap-iso .formden_header p, .bootstrap-iso form
{
  font-family: Arial, Helvetica, sans-serif; color: black
}
.bootstrap-iso form button, .bootstrap-iso form button:hover
{
  color: white !important;
} 
.asteriskField
{
  color: red;
}*/
</style>   
<?php
$user_type=session('login_type');
?>

        <div class="col-lg-10 right_mainbox"><!-- col-lg-10 start-->
               <div class="row">
                    <div class="col-lg-12">
                           <div id="search1" class="wrapper4">
                              <form class="search" action="view-search" method="get" role="form" id="searchForm" >
                              {{ csrf_field() }}    
                                <input type="text" class="searchText" placeholder="Search with Advertisement Id or key words" name="search" value="<?php echo $searchValue;?>">
                                 <button type="submit" class="btn searchButton"><img src="assets/img/search.png" class="img-fluid">
                                 Search</button>
                              
                           </div>
                        </div>
                </div>
               
               <div id="startenddate" class="startend mt-3">
                  <input type="text" placeholder="Campaign From"  class='monthnorder datepicker' name="from_date" value="<?php if(!empty($from_date)) { echo date("F Y", strtotime($from_date)); } ?>"><i class="fa fa-calendar search_class"></i>&nbsp;&nbsp;
                  <input type="text" placeholder="Campaign To"  class='monthnorder datepicker' name="to_date" value="<?php if(!empty($to_date)) { echo date("F Y", strtotime($to_date)); } ?>"><i class="fa fa-calendar search_class"></i>
               </div>
               <br>
               </form>

            <?php  if(!empty($from_date) ||  !empty($to_date) ||  !empty($searchValue))  { ?>

               <!-- Preview code  start-->
               <div class="row">
                  <div class="col-lg-8 main-photo">
                     <section id="photos">

                        @if(count($creatives) > 0)
                        @foreach($creatives as $image) 

                        
                        <?php
                        $VIDEOID=$image->video_url; 
                        $id=$image->id; 
                        $downloading=$image->downloading;
                        $filename=$image->photo_url;
                        $image_arr=explode(".",$filename);
                        $doc_type=end($image_arr);
                        if($downloading == 0 && $doc_type == 'mp4')
                        {
                        $url="https://api.cloudflare.com/client/v4/accounts/34cc3252d5c329c1d2ac13237b4972ed/stream/$VIDEOID/downloads";
                        $curl = curl_init();
                        curl_setopt_array($curl, [
                        CURLOPT_URL            => $url, // tmp url provided by cloudflare
                        CURLOPT_RETURNTRANSFER => 1,
                        CURLOPT_TIMEOUT        => 600,
                        CURLOPT_POST           => true,
                        CURLOPT_HTTPHEADER     => [
                          "X-Auth-Key: 43b3d73c452c8f2f536964033aa59622c3b9d","X-Auth-Email:marketing.mibl@gmail.com"
                        ],
                        ]);
                        $response = curl_exec($curl);
                        curl_close($curl);
                        $response=json_decode($response);
                        @$result=$response->result;
                        @$default=$result->default;
                        @$status=@$default->status;
                        if(@$status == 'ready'){
                        DB::table('tbl_mibl_creatives')
                        ->where('id', $id)
                        ->update([
                        'downloading'=>'1'
                        ]);
                        }      
                        }
                        ?>

                        <?php
                        @$year=date("Y", strtotime($image->date_of_posting));
                        @$month=date("m", strtotime($image->date_of_posting));

                        $image_arr=explode(".",$image->photo_url);
                        $other_type=end($image_arr);
                        $VIDEOID=$image->video_url;
                        ?>

                        @if($image->source_file != '')
                        <input type="hidden" id="source_url_download_{{$image->id}}" value="{{ENV('APP_URL')}}uploads/{{$year}}/{{$month}}/upload_source_file/{{$image->source_file}}">
                       @else
                       <input type="hidden" id="source_url_download_{{$image->id}}" value="">
                       @endif
                        @if($image->file_type == 'image')
                        <input type="hidden" id="url_download_{{$image->id}}" value="{{ENV('APP_URL')}}uploads/{{$year}}/{{$month}}/original/{{$image->photo_url}}">
                        @else
                        @if($other_type == 'mp4')
                        <input type="hidden" id="url_download_{{$image->id}}" value="https://videodelivery.net/{{$VIDEOID}}/downloads/default.mp4">
                        @else
                        <input type="hidden" id="url_download_{{$image->id}}" value="{{ENV('APP_URL')}}uploads/{{$year}}/{{$month}}/{{$image->photo_url}}">
                        @endif
                        @endif
                        <a onclick="preview_image('<?php echo $image->id;?>');">
                        <?php 
                        if($image->file_type == 'other' && ($image->document_name == 'pdf' ) ){
                        $images_path_b="uploads/".$year."/".$month."/".$image->photo_url;
                        if (file_exists($images_path_b)) {
                        $arr_2=explode(".",$image->photo_url);
                        $photo_url=$arr_2[0];
                        $images_path="uploads/".$year."/".$month."/".$photo_url.".jpg";
                        if (!file_exists($images_path)) {
                        $imgExt = new Imagick();
                        $imgExt->readImage('uploads/'.$year.'/'.$month.'/'.$image->photo_url.'[0]');
                        $imgExt->writeImages('uploads/'.$year.'/'.$month.'/'.$photo_url.'.jpg', true);
                        }
                        }
                        }
                        ?>
                        @if($image->file_type == 'image')
                        <img src="{{ENV('APP_URL')}}uploads/{{$year}}/{{$month}}/preview/{{$image->photo_url}}" class="img-fluid">
                        @elseif($image->file_type == 'other' && ($image->document_name == 'pdf' ))
                        <?php 
                        $arr_2=explode(".",$image->photo_url);
                        $photo_url=$arr_2[0];
                        ?>
                         <img src="{{ENV('APP_URL')}}uploads/{{$year}}/{{$month}}/{{$photo_url}}.jpg" class="img-fluid">
                         @elseif($image->file_type == 'other' && ($image->document_name == 'mp4'|| $image->document_name == 'mp3' || $image->other_document_type == 'mp3' || $image->other_document_type == 'mp4'))
                         <?php 
                        $VIDEOID=$image->video_url;
                        $thumbnail="https://videodelivery.net/".$VIDEOID."/thumbnails/thumbnail.jpg";
                        ?>
                        <img src="{{$thumbnail}}" class="img-fluid"> 
                         
                         <!--<video id="myVideo" width="220" height="176">
                          <source src="{{ENV('APP_URL')}}uploads/{{$year}}/{{$month}}/{{$image->photo_url}}"type="video/mp4">
                          <source src="{{ENV('APP_URL')}}uploads/{{$year}}/{{$month}}/{{$image->photo_url}}"type="video/ogg">
                          </video>-->
                        @elseif($image->document_name == 'ppt')
                        <img src="{{ENV('APP_URL')}}assets/img/ppt.png" class="img-fluid">
                         @else
                         
                         @endif
                        </a>
                        @endforeach
                        @else
                        <span style="margin:10px"><h4><b>No data found</b></h4></span>                        
                        @endif
                     </section>
                  </div>
                  <div class="col-lg-4 view-photo">
                     <div class="form-img">

                        <div class="row">
                        <div id="preview" class="col-lg-12">
                        @foreach($creatives as $image) 
                        <?php
                        $year=date("Y", strtotime($image->date_of_posting));
                        $month=date("m", strtotime($image->date_of_posting));
                        ?>
                        @if($image->file_type == 'image')
                        <img src="{{ENV('APP_URL')}}uploads/{{$year}}/{{$month}}/preview/{{$image->photo_url}}" class="img-fluid priviewimages" id="{{$image->id}}" onclick="zoom_image('<?php echo $image->id;?>')">
                        @elseif($image->file_type == 'other' && ($image->document_name == 'pdf' ))
                        <?php 
                        $arr_2=explode(".",$image->photo_url);
                        $photo_url=$arr_2[0];
                        ?>
                         <img src="{{ENV('APP_URL')}}uploads/{{$year}}/{{$month}}/{{$photo_url}}.jpg" class="img-fluid priviewimages" id="{{$image->id}}" onclick="zoom_image('<?php echo $image->id;?>')">
                         @elseif($image->document_name == 'ppt')
                        <img src="{{ENV('APP_URL')}}assets/img/ppt.png" class="img-fluid priviewimages" id="{{$image->id}}" onclick="zoom_image('<?php echo $image->id;?>')">
                         @elseif($image->file_type == 'other' && ($image->document_name == 'mp4'|| $image->document_name == 'mp3' || $image->other_document_type == 'mp3' || $image->other_document_type == 'mp4'))
                         <?php 
                        $VIDEOID=$image->video_url;
                        ?>
                         <!--<video width="320" height="240" poster="{{ENV('APP_URL')}}uploads/{{$year}}/{{$month}}/{{$image->photo_url}}" controls class="img-fluid priviewimages" id="{{$image->id}}">
                        <source src="{{ENV('APP_URL')}}uploads/{{$year}}/{{$month}}/{{$image->photo_url}}" type="video/mp4">
                        </video>-->
                        <!-- src="https://iframe.videodelivery.net/{{$VIDEOID}}"-->
                        <iframe
                        src="https://iframe.videodelivery.net/{{$VIDEOID}}"
                        style="border: none;"
                        height="500"
                        width="400"
                        allow="accelerometer; gyroscope; autoplay; encrypted-media; picture-in-picture;"
                        allowfullscreen="true"
                        class="img-fluid priviewimages" id="{{$image->id}}"></iframe>
                        @else

                         @endif
                        @endforeach
                        </div>
                        </div>
                        @foreach($creatives as $details)
                        <?php
                        @$id=$details->id;
                        @$restricted_link=$details->restricted_link;
                        @$share_link=$details->share_link;
                        @$advertisement_id=$details->advertisement_id;
                        @$file_name=$details->file_name;
                        @$archive_sub_name=$details->archive_sub_name;
                        @$archive_name=$details->archive_name;
                        @$department_name=$details->department_name;
                        @$vendor_name=$details->vendor_name;
                        @$photo_url=$details->photo_url;
                        @$source_file=$details->source_file;
                        @$file_type=$details->file_type;
                        @$year=date("Y", strtotime($details->date_of_posting));
                        @$month=date("m", strtotime($details->date_of_posting));
                        @$commonthyear=date("F Y", strtotime($details->date_of_posting));
                        @$VIDEOID=$details->video_url;
                        ?>
                        @break
                        @endforeach

                        @if($user_type == 'Super Admin' || ($user_type_download_creative == 'yes' && $user_download_creative == 'yes'))

                        <div class="row line2">
                          <?php
                             $image_arr=explode(".",@$photo_url);
                             $other_type=end($image_arr);
                            ?>
                            
                            <style>
                            .red_btn{
                                float: left;
    width: auto;
    background-color: #fff;
    border-radius: 70px;
}
                            .red_btn_icon {
    float: left;
    width: auto;
}
.red_btn span{
    float: left;
    width: auto;
    margin: 4px 10px 0 5px;
    font-size: 13px;
    font-weight: bold;
    color: #000;
}
                            </style>
                           <div class="col-lg-12 mt-2">
                             @if(@$file_type == 'other')
                             @if($other_type == 'mp4')
                            <a href="https://videodelivery.net/{{$VIDEOID}}/downloads/default.mp4" type="button" class="red_btn" download id="photo_url">
                            <div class="red_btn_icon"><img src="{{ENV('APP_URL')}}assets/images/download_icon.png"></div>
                            <span>Download</span>
                            </a>
                            @else
                            <a href="{{ENV('APP_URL')}}uploads/{{@$year}}/{{@$month}}/{{@$photo_url}}" type="button" class="red_btn" download id="photo_url">
                            <div class="red_btn_icon"><img src="{{ENV('APP_URL')}}assets/images/download_icon.png"></div>
                            <span>Download</span>
                            </a>
                            @endif
                            @else
	          <a href="{{ENV('APP_URL')}}uploads/{{@$year}}/{{@$month}}/original/{{@$photo_url}}" type="button" class="red_btn" download id="photo_url">
                            <div class="red_btn_icon"><img src="{{ENV('APP_URL')}}assets/images/download_icon.png"></div>
                            <span>Download</span>
                            </a>
                             @endif 
                            
                            @if($user_type == 'Super Admin' || $user_type == 'Admin User')
                            
                            <span class="idddddddddddddddd">    
                            <a href="javascript:void(0);""  onclick="myFunction_sharing('<?php echo @$id;?>')" class="red_btn" style="float:right">
                            <div class="red_btn_icon"><img src="{{ENV('APP_URL')}}assets/images/share_icon.png"></div>
                            <span>
                                <?php if(@$share_link != NULL || @$share_link !='') 
                                { 
                                echo "Creative link is shared"; 
                                } 
                                else 
                                { 
                                echo "Share link"; 
                                } 
                                ?>
                            </span>
                            </a>
                            <input type="hidden" id="restricted_link" value="{{@$restricted_link}}"> 
                            </span>
                            </div>
                            @endif
                            
                            <?php if(@$source_file)
                            {
                            $data_source_file="style=display:block";
                            }else
                            {
                              $data_source_file="style=display:none"; 
                            }
                            ?>
                             
                            <div class="col-lg-8 mt-2 mb-2">
                            <a href="{{ENV('APP_URL')}}uploads/{{@$year}}/{{@$month}}/upload_source_file/{{@$source_file}}" type="button" class="red_btn" download id="source_file" {{$data_source_file}}>  
                            <div class="red_btn_icon"><img src="{{ENV('APP_URL')}}assets/images/download_icon.png"></div>
                            <span>Source file download</span>
                            </a>
                            </div>    
                            
                        </div>
                        @endif
                      
                        <div class="row">
                           <div class="col-lg-12" >
                              <p class="pt-3 text-white"><b>Advertisement Id :</b> <span id="advertisement_id">{{@$advertisement_id}}</span></p>
                           </div>
                           <div class="col-lg-12">
                              <p class="text-white"><b>File Name :</b> <span id="file_name">{{@$file_name}}</span></p>
                           </div>
                        </div>
                     
                        <div class="row">
                           <div class="col-lg-12">
                              <table class="img-table">
                                 <tbody>
                                    <tr class="table-heading">
                                       <td><b>Brand Name : </b> <span id="brand_name">MIBL</span></td>
                                   </tr>  
                                   <tr class="table-heading"> 
                                       <td><b>Archive Category : </b> <span id="achive_category">@if(!empty(@$archive_sub_name)) 
                                                                      {{@$archive_sub_name}}
                                                                     @else
                                                                     {{@$archive_name}}
                                                                     @endif</span></td>
                                    </tr>
                                    <tr class="table-heading"> 
                                       <td><b>Department : </b> <span id="department_name"> 
                                                                      {{@$department_name}}
                                                                    </span></td>
                                    </tr>
                                    <tr class="table-heading"> 
                                       <td><b>Vendor : </b> <span id="vendor_name"> 
                                                                      {{@$vendor_name}}
                                                       </span></td>
                                    </tr>
                                    <tr class="table-heading"> 
                                       <td><b>Campaign Month/Year : </b> <span id="commonthyear"> 
                                                                      {{@$commonthyear}}
                                                       </span></td>
                                    </tr>
                               </tbody>
                              </table>
                           </div>
                        </div>

                     </div>
                  </div>
                  <!-- Preview code End -->
          
             
               </div><!-- col-lg-10 close-->
               <br>
               <!-- <div class="d-flex justify-content-left">
                  {!! $creatives->links() !!}
                  </div>   -->
				  
				<div class="d-flex justify-content-left">
					{{ $creatives->appends(request()->query())->links() }}
				</div>  
<br>
<!--
@if(count($creatives) > 0)

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
$str_nxt=$creatives->url($creatives->currentPage()+1);
$str_nxt1=explode("?",$str_nxt);
$search=$str_nxt1[0]."?".$str_nxt1[1].'&search='.$searchValue."&from_date=".$from_date."&to_date=".$to_date;
?>
<?php 
@$pagess=@$_GET['page'];
?>
@if(@$pagess > 7)

@if($i == 1 || $i == 2)
<li class="page-item {{ ($creatives->currentPage() == $i) ? ' active' : '' }}" ><a class="page-link" href="{{ $search }}">{{ $i }}</a></li>
@else

@if((@$pagess-4) < $i  && ($pagess+3) >= $i )
<li class="page-item {{ ($creatives->currentPage() == $i) ? ' active' : '' }}" ><a class="page-link" href="{{ $search }}">{{ $i }}</a></li>
@else

@if($i == 3)
<li class="page-item disabled" aria-disabled="true"><span class="page-link">...</span></li>
@endif

@if(($creatives->lastPage()-2) == $i)
<li class="page-item disabled" aria-disabled="true"><span class="page-link">...</span></li>
@endif

@if(($creatives->lastPage()-1) <= $i)
<li class="page-item {{ ($creatives->currentPage() == $i) ? ' active' : '' }}" ><a class="page-link" href="{{ $search }}">{{ $i }}</a></li>
@endif

@endif

@endif

@else
@if($i <= 10 && $pagess <= 10)
<li class="page-item {{ ($creatives->currentPage() == $i) ? ' active' : '' }}" ><a class="page-link" href="{{ $search }}">{{ $i }}</a></li>
@else
@if(($creatives->lastPage()-2) == $i)
<li class="page-item disabled" aria-disabled="true"><span class="page-link">...</span></li>
@endif
@if(($creatives->lastPage()-1) <= $i)
<li class="page-item {{ ($creatives->currentPage() == $i) ? ' active' : '' }}" ><a class="page-link" href="{{ $search }}">{{ $i }}</a></li>
@endif
@endif     
@endif

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
@endif-->
<div id="myModal" class="modal">
  <span class="close">&times;</span>
  <img class="modal-content" id="img01">
  <div id="caption"></div>
</div>

<?php } ?>
   
@include('admin/footer')


<style>
.testbox_div {
float: left;
width: 100%;
padding: 30px 0 0 0;
position: fixed;
height: 100%;
background-color: #000000a8;
left: 0;
top: 0;
right: 0;
bottom: 0;
display: none;
z-index: 500000;
}
.testbox_open {
display:block;
}
.testbox_div_inner {
max-width: 400px;
margin: 0 auto;
float: none;
}
.testbox_div_innerwhite {
float: left;
width: 110%;
height: auto;
background-color: #fff;
padding: 10px;
/*margin-left: 450px;*/
}
.sharebuttons{
/*margin:20px !important;*/
font-size: 12px;
border-radius: 21px !important;
padding: 4% 13%;
}
.ad_list_imagepopup_close{
            /*position: fixed;*/
            right: 0;
            top: 0;
            background-color: #dc3545;
            color: #fff;
           /* font-size: 24px;*/
            font-weight: normal;
            padding: 10px 20px 13px 20px;
            line-height: normal;
            margin: 10px 0px 0 0;
            border-radius: 6px 6px 6px 6px;
        }

</style>      


<div class="testbox_div" id="testbox">
<div class="testbox_div_inner">
<div class="testbox_div_innerwhite">
<a href="javascript:void(0);""  onclick="myFunction_sharing1()" style="float:right;"><span class="ad_list_imagepopup_close">X</span></a>  
<span style='display:none' id="updatedmessage"><b>Access permission updated successfully.</b></span>
<hr style="margin-top:2rem">
<!--
<div class="form-group">
<label for="inputAddress"><b>General Access :</b></label>
<select type="text" class="form-control" id="sharefile" name="sharefile" oninput="myFunction_sharing('update')" disabled>
<option value="Restricted">Restricted</option>
<option value="Anyone with the link" selected>Anyone with the link</option>
</select>

</div>-->
<div class="form-group">
<label for="date_of_posting"><b>Share Link :</b></label>
<textarea  id="sharelink" name="sharelink" class="form-control"></textarea>
<input type="hidden" id="idsss" >
<input type="hidden" class="form-control" id="sharefile" name="sharefile" >
<br>
<a onclick="myFunction_copy_link()" class="btn btn-danger" style="font-size:13px;border-radius:50px;"><i class="fa fa-link" aria-hidden="true"></i> Copy Link</a>
</div>   
<div data-toggle="tooltip" title="You must to log in!" class="stars"></div>
<hr>

</div>
</div>
</div>



<script>
function myFunction_sharing(id) {
var idsss=document.getElementById("idsss").value;
if(id == 'update'){
var sharefile=document.getElementById("sharefile").value;
var idsss=document.getElementById("idsss").value;
}else
{
 var sharefile='';   
 var idsss=id;
 document.getElementById("idsss").value=id;
}

var _token = jQuery('input[name="_token"]').val();
jQuery.ajax({ 
url: "{{ENV('APP_URL')}}get_share_links",
type:"POST",
data: {id:idsss,sharefile:sharefile,_token: _token},
success: function(result) {
result = $.parseJSON(result);
       
document.getElementById("sharefile").value=result.restricted_link;
document.getElementById("sharelink").value="https://www.miblmbank.com/files/"+result.share_link
if(sharefile == 'Restricted' || sharefile == 'Anyone with the link'){
$("#updatedmessage").attr("style", "display:block");
$("#updatedmessage").attr("style", "color:#28a745");
setTimeout(function(){
$("#updatedmessage").attr("style", "display:none");
}, 3000);
}
}
});


var testbox_div="testbox_div";
var testbox_open=" testbox_open";

var x = document.getElementById("testbox");
if (x.className === testbox_div) {
x.className += testbox_open;
} else {
if(sharefile == 'close'){
x.className = testbox_div;
}

}
}


function myFunction_sharing1(){
var testbox_div="testbox_div";
var testbox_open=" testbox_open";

var x = document.getElementById("testbox");
if (x.className === testbox_div) {
x.className += testbox_open;
} else {
x.className = testbox_div;
}

}


function myFunction_copy_link() {
/* Get the text field */
var copyText = document.getElementById("sharelink");
copyText.select();
document.execCommand('copy')
console.log('Copied Text')
}

</script>


















<script>
$(document).ready(function () {
var element = document.getElementById("search");
element.classList.add("active");
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
            function preview_image(id)
            {
                
                
    
            var url_download=document.getElementById('url_download_'+id).value;
            var source_url_download=document.getElementById('source_url_download_'+id).value;


            $("#preview img:eq(0)").nextAll().hide();
            var x = document.getElementsByClassName("priviewimages");
            var i;
            for (i = 0; i < x.length; i++) {
            x[i].style.display = 'none';
            }
            document.getElementById(id).style.display = "block";

            var _token = jQuery('input[name="_token"]').val();
            jQuery.ajax({
            url: "{{ENV('APP_URL')}}get_creatives_data",
            type: "POST",
            data: {id:id,_token: _token
            },
            success: function(result) {
            result = $.parseJSON(result);
            document.getElementById("advertisement_id").textContent=result.advertisement_id;
            document.getElementById("file_name").textContent=result.file_name;
            document.getElementById("department_name").textContent=result.department_name;
            document.getElementById("vendor_name").textContent=result.vendor_name;

            if(result.share_link !='' && result.share_link != null)
            {
            var resultsss='<a href="javascript:void(0);"  onclick="myFunction_sharing('+id+')" class="red_btn" style="float:right"><div class="red_btn_icon"><img src="https://democheck.in/MIBL-Repository-Creatives/assets/images/share_icon.png"></div><span>Creative link is shared</span></a>';
            jQuery('.idddddddddddddddd').html(resultsss);
            
            }else
            {
            var resultsss='<a href="javascript:void(0);"  onclick="myFunction_sharing('+id+')" class="red_btn" style="float:right"><div class="red_btn_icon"><img src="https://democheck.in/MIBL-Repository-Creatives/assets/images/share_icon.png"></div><span>Share link</span></a>';
            jQuery('.idddddddddddddddd').html(resultsss);
            
            }
          
          
          
          
            var mydate = new Date(result.date_of_posting);
            var month = ["January", "February", "March", "April", "May", "June",
            "July", "August", "September", "October", "November", "December"][mydate.getMonth()];
            var str = month + ' ' + mydate.getFullYear();

            document.getElementById("commonthyear").textContent=str;

            
            if(result.archive_sub_name){
            document.getElementById("achive_category").textContent=result.archive_sub_name;
            }else
            {
            document.getElementById("achive_category").textContent=result.archive_name;  
            }
            document.getElementById("photo_url").href = url_download;

            if(source_url_download)
              {
              document.getElementById("source_file").style.display = "block";            
              document.getElementById("source_file").href = source_url_download;

              }else
              {
              document.getElementById("source_file").style.display = "none";
              document.getElementById("source_file").href = source_url_download;
              }

            }
            });
              

            }

$(function(){
   /*$("#preview img:eq(0)").nextAll().hide();
   //  document.getElementById('17').style.display = "block";

    $("#photos img").click(function(e){
        var index = $(this).index();
        $("#preview img").eq(index).show().siblings().hide();
    });*/

            var x = document.getElementsByClassName("priviewimages");
            var i;
            for (i = 1; i < x.length; i++) {
            x[i].style.display = 'none';
            }
          //  document.getElementById().style.display = "block";


 });
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

<!--<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.4.1/jquery.js"></script>
<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.7.2/jquery-ui.min.js"></script>
<link rel="stylesheet" type="text/css" media="screen" href="https://ajax.googleapis.com/ajax/libs/jqueryui/1.7.2/themes/base/jquery-ui.css">-->

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

<!-- <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.1.0/jquery.min.js"></script> -->


   </body>
</html>



    
