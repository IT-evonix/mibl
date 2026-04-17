@include('admin/header')
@include('admin/side-menu') 

<?php 
$user_type_download_creative=session('user_type_download_creative');
$user_download_creative=session('user_download_creative');
$user_type=session('login_type');

?>



<div class="container-fluid col-lg-10 right_mainbox" id="viewlistmaster">
   
<style>


select#document_id {
    background-color: white !important;
    border: 1px solid #b3b3b3 !important;
}
select#department_id {
    background-color: white !important;
    border: 1px solid #b3b3b3 !important;
}


select#archive_category_id {
    background-color: white !important;
    border: 1px solid #b3b3b3 !important;
}


select#vendor_id {
    background-color: white !important;
    border: 1px solid #b3b3b3 !important;
}

        table {
            width: 100%;
            border-collapse: collapse;
            border: none;
            float: left;
            /* margin: -20px 0 0 0; */
        }
        
        thead tr th {
            background-color: #d8002a;
            color: #fff;
            text-align: center;
        }
        
        tbody th td {
            background-color: rgba(255, 255, 255, 0.2);
            color: #000;
            text-align: center;
            width: 200px;
        }
        
        table td {
            width: 200px;
        }
        
        i.fa.fa-caret-down {
            font-size: 30px;
        }
        
        .open_row {
            background-color: #eee;
            padding: 15px 10px;
            border: aliceblue;
            border-radius: 10px;
            cursor: pointer;
        }
        .open_row:hover{
            box-shadow: 0 0 40px #ebebeb;
        }
        a.btn.download {
            padding: 5px 10px;
            background-color: #d8002a;
            color: #fff;
            text-decoration: none !important;
        }
        
        a.btn.edit {
            padding: 6px 10px;
            background-color: #d8002a;
            color: #fff;
            text-decoration: none !important;
        }
        
        span.table-detail {
            font-weight: bold;
        }
        
        .adv {
            padding-top: 35px;
        }
        
        h3.table_heading {
            font-size: 14px;
            font-weight: 400;
        }
        
        .manage_col4 .manage_row1 {
            margin: 15px;
        }
        
        .manage_col4 .manage_row2 {
            margin: 15px;
        }
        
        .table {
            background-color: #fff !important;
        }
        
        .hedding h1 {
            color: #fff;
            font-size: 25px;
        }
        
        .main-section {
            margin-top: 120px;
        }
        
        .hiddenRow {
            padding: 0 4px !important;
            font-size: 13px;
        }
        
        .cell-1 {
            border-collapse: separate;
            border-spacing: 0 4em;
            background: #ffffff;
            border-bottom: 5px solid transparent;
            background-clip: padding-box;
            cursor: pointer
        }
        
        thead {
            background: #dddcdc
        }
        
        .table-elipse {
            cursor: pointer
        }
        
        #demo {
            -webkit-transition: all 0.3s ease-in-out;
            -moz-transition: all 0.3s ease-in-out;
            -o-transition: all 0.3s 0.1s ease-in-out;
            transition: all 0.3s ease-in-out
        }
        
        .row-child {
            background-color: #000;
            color: #fff
        }
        
        td[colspan] {
            background-color: gray;
        }
        
        td[gray] button {
            width: 100%;
        }
        
        .hide {
            display: none;
        }
        
        .show {
            display: table-row !important;
        }
        
        .btn-toggle {
            border: 0;
            background-color: transparent;
            cursor: pointer;
            font-size: 20px;
            outline: 0;
        }
        
        .btn-toggle[aria-expanded="true"] i {
            transform: rotate(180deg);
            -ms-transform: rotate(180deg);
            -webkit-transform: rotate(180deg);
        }
        
        .btn-toggle1 {
            border: 0;
            background-color: transparent;
            cursor: pointer;
            font-size: 20px;
            outline: 0;
        }
        
        .btn-toggle1[aria-expanded="true"] i {
            transform: rotate(180deg);
            -ms-transform: rotate(180deg);
            -webkit-transform: rotate(180deg);
        }
        
        .btn-toggle2 {
            border: 0;
            background-color: transparent;
            cursor: pointer;
            font-size: 20px;
            outline: 0;
        }
        
        .btn-toggle2[aria-expanded="true"] i {
            transform: rotate(180deg);
            -ms-transform: rotate(180deg);
            -webkit-transform: rotate(180deg);
        }
        
       
        
        .open_close_icon {
            width: 10%;
        }


        .search_filter_box{
            width:100%;
            height:auto;
        }
        .width100{
            width:100%;
            height:auto;
        }
        .search_filter_box input{
            width:100%;
        }
        .search_filter_box select{
            width: 100%;
            border: 1px solid #CAC;
            padding: 6px;
            border-radius: 4px;
        }
        .ad_list_imagepopup_close{
            position: fixed;
            right: 0;
            top: 0;
            background-color: #ff220b;
            color: #fff;
            font-size: 24px;
            font-weight: normal;
            padding: 7px 20px 10px 20px;
            line-height: normal;
            margin: 10px 0px 0 0;
            border-radius: 6px 0px 0 6px;
        }
.ad_list_icon {
  float: left;
  width: auto;
  position: absolute;
  margin: 15px 0 0 -36px;
  box-shadow: 4px 3px 4px rgba(0, 0, 0, 0.2);
  border: 1px solid #d8042d;
  border-radius: 6px;
  padding: 6px;
  background-color: #fff;
}
.ad_list_icon img{
    float: left;
  width: 30px;
}
.ad_list_icon span{
  display: none;
  background-color: #d8002a;
  float: left;
  font-size: 9px;
  padding: 2px 8px 3px 8px;
  line-height: normal;
  border-radius: 30px;
  text-transform: uppercase;
  color: #fff;
  margin: -20px 0 0 -20px;
  position: absolute;
}
.ad_list_icon:hover span{
    display: block;
}
        

    </style>

      <!--==START ADVANCE SEARCH TABLE== -->
      <div class="container">
        
        <div class="search_filter_box">
            <h4>Search Approve Adaptation Creatives</h4>
            <div class="width100">
                <div class="row">
                
                    <div class="col-lg-8">
                    <form class="search" action="view-creative-vendor-approved-adaptation" method="get" role="form" id="searchForm" >
                              {{ csrf_field() }}  
                              
                        <div class="row">
                        
                            <div class="col-lg-6 mb-3"><input type="text" placeholder="Advertisement Id" id="advertisement_id" name="advertisement_id"></div>
                            <div class="col-lg-6 mb-3">
                            <select id="vendor_id" name="vendor_id">
                            <option value="">--Select Vendor Name--</option>
                            <?php
                            for($i=0;$i<count($vendor_c); $i++)
                            { 
                            $sub_vendor=$vendor_c[$i]['vendor_list'];
                            ?>
                            <optgroup label="{{$vendor_c[$i]['vendor_type_name']}}">
                            <?php for($j=0;$j<count($sub_vendor); $j++){ ?>
                            <option value="{{$vendor_c[$i]['vendor_type_id']}},{{$sub_vendor[$j]['vendor_id']}}">{{$sub_vendor[$j]['vendor_name']}}</option>
                            <?php } ?> 
                            </optgroup>
                            <?php } ?>
                            <script type="text/javascript">
                            document.getElementById('vendor_id').value="{{$vendor_id}}";
                            </script>
                            </select>
                            </div>
                            <div class="col-lg-6 mb-3">
                                <select   id="archive_category_id" name="archive_category_id">
                                <option value="">--Select Archive Category--</option>
                                <?php
                                for($i=0;$i<count($archive_c); $i++)
                                { $sub_ca=$archive_c[$i]['sub_list'];
                                ?>
                                <optgroup label="{{$archive_c[$i]['archive_category']}}">
                                <?php for($j=0;$j<count($sub_ca); $j++){ ?>
                                <option value="{{$archive_c[$i]['archive_category_id']}},{{$sub_ca[$j]['sub_category_id']}}">{{$sub_ca[$j]['sub_category']}}</option>

                                <?php } ?> 
                                </optgroup>
                                <?php } ?>
                                <script type="text/javascript">
                                document.getElementById('archive_category_id').value="{{$archive_category_id}}";
                                </script>
                               </select>
                            </div>
                            <div class="col-lg-6 mb-3">
                            <select id="department_id" name="department_id">
                            <option value="">--Select Department--</option>
                            <?php
                            for($i=0;$i<count($department_c); $i++)
                            { 
                            $sub_depart=$department_c[$i]['department_list'];
                            ?>
                            <optgroup label="{{$department_c[$i]['department_type_name']}}">
                            <?php for($j=0;$j<count($sub_depart); $j++){ ?>
                            <option value="{{$department_c[$i]['department_type_id']}},{{$sub_depart[$j]['department_id']}}">{{$sub_depart[$j]['department_name']}}</option>
                            <?php } ?> 
                            </optgroup>
                            <?php } ?>

                            <script type="text/javascript">
                                document.getElementById('department_id').value="{{$department_id}}";
                            </script>
                            </select>
                            </div>
                            <div class="col-lg-6 mb-3"><input type="text" class="datepicker" placeholder="Campaign From"  id="from_date" name="from_date" value="<?php if(!empty($from_date)) { echo date("F Y", strtotime($from_date)); } ?>"><i class="fa fa-calendar advance_search"></i></div>
                            <div class="col-lg-6 mb-3"><input type="text" class="datepicker" placeholder="Campaign To"   id="to_date" name="to_date"  value="<?php if(!empty($to_date)) { echo date("F Y", strtotime($to_date)); } ?>"><i class="fa fa-calendar advance_search"></i></div>
                            
                            <div class="col-lg-6 mb-3">
                            <select id="document_id" name="document_id">
                            <option value="">--Select Document Type--</option>
                            @foreach($document_types as $documenttypes)
                            <option value="{{$documenttypes->id}}">{{$documenttypes->name}}</option>
                            @endforeach
                            </select>
                            <script type="text/javascript">
                            document.getElementById('document_id').value="{{$document_id}}";
                            </script>
                            </div>
                           
                            <div class="col-lg-12 mb-3">
                                <button class="btn btn-success ml-2" type="submit" style="margin:0px !important">Search</button>
                                <a class="btn btn-danger ml-2"  style="margin:0px !important" href="{{ENV('APP_URL')}}view-creative-vendor-approved-adaptation" role="button">Cancel</a>
                            </div>
                            
                        </div>
                        </form>
                    </div>
                    
                    <div class="col-lg-4"></div>
                </div>
            </div>
        </div>
        <br><br><br><br><br><br><br>
        <br><br>

        <?php 
        if(!empty($from_date) ||  
        !empty($to_date) ||
        !empty($advertisement_id) ||  
        !empty($department_id) ||
        !empty($archive_category_id) ||
        !empty($vendor_id) ||
        !empty($document_id) 
        ) { ?>

        <div class="row">
        <div class="col-md-12">
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
        </div>
        </div>

        <div class="open_main">
        <?php $i=1;?>
          @foreach($creatives as $creative)





           <!-- ADVACNCE-LIST-HIDE-SHOW-START -->
           <script>
            function myFunction{{$creative->id}}() {
            var x = document.getElementById("ad_list{{$creative->id}}");
            if (x.className === "row ad_list{{$creative->id}}") {
                x.className += " ad_list_open{{$creative->id}}";
            } else {
                x.className = "row ad_list{{$creative->id}}";
            }
            }
            // IMAGE-ORIGINAL-POPUP-START
            function myFunction_img_pop{{$creative->id}}() {
            var x = document.getElementById("adlist_popup{{$creative->id}}");
            if (x.className === "adlist_popup{{$creative->id}}") {
                x.className += " adlist_popup_open{{$creative->id}}";
            } else {
                x.className = "adlist_popup{{$creative->id}}";
            }
            }
            // IMAGE-ORIGINAL-POPUP-END

            </script>
            <style>
            .ad_list{{$creative->id}} .ad_other_list{
                display:none;
            }
            .ad_list_open{{$creative->id}} .ad_other_list{
                display:table-row;
            }
            .ad_list_img{
                max-width:100%;
                text-align:center;
            }
            .ad_list_img img{
                max-width: 100%;
                max-height: 100px;
                transition-duration: 0.3s;
            }
            .ad_list_open{{$creative->id}} .ad_list_img img{
                max-height:230px;
            }

            .ad_list_imagepopup{
                display:none;
            }
            .ad_list_image{
                display:block;
            }
            .adlist_popup_open{{$creative->id}} .ad_list_image{
                display:none;
            }
            .adlist_popup_open{{$creative->id}} .ad_list_imagepopup{
                display: block;
                position: fixed;
                left: 0;
                top: 0;
                right: 0;
                bottom: 0;
                width: 100%;
                height: 100%;
                background-color: rgba(0, 0, 0, 0.8);
                z-index: 500;
                padding: 20px;
            }
            
            
            </style>
            <!-- ADVACNCE-LIST-HIDE-SHOW-END -->
            <div class="open_row mt-4" onclick="myFunction{{$creative->id}}()">
                <div id="ad_list{{$creative->id}}" class="row ad_list{{$creative->id}}">
                    <div class="col-lg-2 ad_list_img">

                    <div id="adlist_popup{{$creative->id}}" class="adlist_popup{{$creative->id}}" onclick="myFunction_img_pop{{$creative->id}}()">
                    <?php
                    @$year= date("Y", strtotime($creative->date_of_posting));
                    @$month= date("m", strtotime($creative->date_of_posting));
                    if($creative->file_type == 'image')
                    {
                    
                    @$img=$_ENV['APP_URL']."upload_vendor/".$year."/".$month."/"."preview/".$creative->photo_url;
                    @$img_popup=$_ENV['APP_URL']."upload_vendor/".$year."/".$month."/"."original/".$creative->photo_url;
                    //$images=$img;
                    ?>
                    <div class="sunny ad_list_icon"><img src="assets/img/img_icon.png"><span>Image</span></div>
                    <div class="ad_list_image"><img src="{{$img}}" id="{{$creative->id}}" ></div>
                    <div class="ad_list_imagepopup">
                        <span class="ad_list_imagepopup_close">X</span>
                        <img src="{{$img_popup}}" style="max-height: 100%;">
                    </div>
                    <?php 
                    }else if($creative->file_type == 'other')
                    { 
                    $image_arr=explode(".",$creative->photo_url);
                    $image_type=end($image_arr);
                    
                    if(Str::upper($image_type) == 'PDF')
                    {
                     
                   @$img=$_ENV['APP_URL']."upload_vendor/".@$year."/".@$month."/".@$creative->photo_url;
                    
                   $images_path_b="upload_vendor/".$year."/".$month."/".$creative->photo_url;
                   if (file_exists($images_path_b)) {
                   $arr_2=explode(".",$creative->photo_url);
                   $photo_url_new=$arr_2[0];
                   $images_path="upload_vendor/".$year."/".$month."/".$photo_url_new.".jpg";
                   if (!file_exists($images_path)) {
                   $imgExt = new Imagick();
                   $imgExt->readImage('upload_vendor/'.$year.'/'.$month.'/'.$creative->photo_url.'[0]');
                   $imgExt->writeImages('upload_vendor/'.$year.'/'.$month.'/'.$photo_url_new.'.jpg', true);
                   }
                   }
                   @$images_path_x=$_ENV['APP_URL']."upload_vendor/".$year."/".$month."/".$photo_url_new.'.jpg';

                    
                    ?>
                    <div class="sunny ad_list_icon"><img src="assets/img/pdf_icon.png"><span>PDF</span></div>
                    <div class="ad_list_image"><img src="{{@$images_path_x}}"></div>
                  <div class="ad_list_imagepopup">
                        <span class="ad_list_imagepopup_close">X</span>
                        <embed src="{{$img}}#toolbar=0" style="height:100%;" width="600">
                    </div>

                   <?php 
                    }else if (Str::upper($image_type) == 'PPT')
                    {
                    @$img=$_ENV['APP_URL']."upload_vendor/".@$year."/".@$month."/".@$creative->photo_url;
                     ?>
                    <div class="sunny ad_list_icon"><img src="assets/img/ppt_icon.png"><span>PPT</span></div>
                    <div class="ad_list_image"><img src="{{@$img}}"></div>
                    
                    <?php
                    }else
                    {
                    $VIDEOID=$creative->video_url;    
                    $img=$_ENV['APP_URL']."upload_vendor/".@$year."/".@$month."/".@$creative->photo_url;
                    $video_path="https://iframe.videodelivery.net/".$VIDEOID;
                    $thumbnail="https://videodelivery.net/".$VIDEOID."/thumbnails/thumbnail.jpg"; 
                    ?>

                   <div class="sunny ad_list_icon"><img src="assets/img/video_icon.png"><span>Video</span></div>
                    <div class="ad_list_image">
                    <video width="100" height="100" poster="{{$img}}" controls>
                    </video>
                    </div>
                    <div class="ad_list_imagepopup">
                    <span class="ad_list_imagepopup_close">X</span>
                    <video width="700" height="500" controls>
                    <source src="{{$img}}" type="video/mp4">
                    <source src="{{$img}}" type="video/ogg">
                    Your browser does not support the video tag.
                    </video>                   
                    </div>
                   <?php }
                    }
                    ?>
                    </div>
                    
                    </div>
                    <div class="col-lg-8">
                        <table class="main-table active">
                            <tbody>
                                <tr class="open_close ">
                                    
                                   <td><?php $date_of_posting=date("F Y", strtotime($creative->date_of_posting));?>
                                        <h3 class="table_heading">Campaign Month/Year <br><span class="table-detail ">{{$date_of_posting}}</span></h3>
                                    </td>
                                    <td>
                                        <h3 class="table_heading ">Advertisement ID<br><span class="table-detail ">{{$creative->advertisement_id}}</span></h3>
                                    </td>
                                    <td>
                                        <h3 class="table_heading ">File Name<br><span class="table-detail ">{{$creative->file_name}}</span></td>
                                </tr>
                                <tr class="ad_other_list">
                                    
                                    <td>
                                        <h3 class="table_heading pt-3">Archive Category <br><span class="table-detail ">@if($creative->archive_sub_category_name) {{$creative->archive_sub_category_name}} @else {{$creative->archive_name}} @endif</span></h3>
                                    </td>
                                    <td>
                                        <h3 class="table_heading pt-3">Vendor <br><span class="table-detail ">{{$creative->vendor_name}}</span></h3>
                                    </td>
                                    <td>
                                        <h3 class="table_heading pt-3">Department <br><span class="table-detail ">{{$creative->department_name}}</span></h3>
                                    </td>
                                </tr>
                                <tr class="ad_other_list">
                                    
                                    <td><?php $date_of_posting=date("F Y", strtotime($creative->date_of_posting));?>
                                        <h3 class="table_heading pt-3">Language <br><span class="table-detail ">{{@$creative->language}}</span></h3>
                                    </td>
                                    <td>
                                        <h3 class="table_heading pt-3">Status <br><span class="table-detail ">@if($creative->active_yn == 0) Active @else Inactive @endif</span></h3>
                                    </td>
                                    <td><?php $created_date= date("d/m/Y", strtotime($creative->created_date));?>
                                        <h3 class="table_heading pt-3">Created date <br><span class="table-detail ">{{$created_date}}</span></h3>
                                    </td>
                                </tr>
                                <tr class="ad_other_list">
                                  @if($creative->source_file)
                                  <?php 
                                  $images_path="upload_vendor/".$year."/".$month."/upload_source_file/".$creative->source_file;
                                  if (file_exists($images_path)) { ?>
                                    <td>
                                    <h3 class="table_heading pt-3">Source File Download <br><span class="table-detail ">
                                        <br><a href="{{ENV('APP_URL')}}upload_vendor/{{@$year}}/{{@$month}}/upload_source_file/{{@$creative->source_file}}" download >Click here to download</a></span></h3>
                                    </td>
                                    <?php } ?>
                                  @endif
                                </tr>    

                            </tbody>
                        </table>
                    </div>
                    @if($user_type == 'Super Admin' || ($user_type_download_creative == 'yes' && $user_download_creative == 'yes'))

                    <div class="col-lg-2 manage_col4 ">
                        <div class=" ">
                            <?php 
                            $APP_URL=$_ENV['APP_URL']."edit-creative-vendor-adaptation/".base64_encode($creative->id);
                            ?>
                            <!-- <a class="btn download " href="# " role="button"><img src="assets/img/icon/download.png " class="img-fluid edit_download " width="70%"></a> -->
                             <?php
                             $image_arr=explode(".",$creative->photo_url);
                             $other_type=end($image_arr);
                            ?>
                            
                            @if(@$creative->file_type == 'other')
                            @if(Str::upper($other_type) == 'MP4')
                            
                            <a href="{{ENV('APP_URL')}}upload_vendor/{{@$year}}/{{@$month}}/{{@$creative->photo_url}}" type="button" class="btn download" download ><img src="assets/img/icon/download.png " class="img-fluid edit_download " width="70%"></a>
                            @else
                            <a href="{{ENV('APP_URL')}}upload_vendor/{{@$year}}/{{@$month}}/{{@$creative->photo_url}}" type="button" class="btn download" download ><img src="assets/img/icon/download.png " class="img-fluid edit_download " width="70%"></a>
                            @endif

                            @else
                            <a href="{{ENV('APP_URL')}}upload_vendor/{{@$year}}/{{@$month}}/original/{{@$creative->photo_url}}" type="button" class="btn download" download ><img src="assets/img/icon/download.png " class="img-fluid edit_download " width="70%"></a>
                             @endif
                            <a class="btn edit"  href="{{$APP_URL}}" role="button "><img src="assets/img/icon/edit.png " class="img-fluid edit_download" width="70%"></a>
                        </div>
                    </div>
                    @endif

                </div>
            </div>

            
           
            <?php $i++;?>
      @endforeach

      <br>
@if ($creatives->lastPage() > 1)

<?php  
$str_pre=$creatives->url($creatives->currentPage()-1);
$str_pre1=explode("?",$str_pre);
$search_pre=$str_pre1[0]."?".$str_pre1[1].'&advertisement_id='.$advertisement_id.'&vendor_id='.$vendor_id.'&archive_category_id='.$archive_category_id.'&department_id='.$department_id."&from_date=".$from_date."&to_date=".$to_date."&document_id=".$document_id;
?>

<?php  
$str_nxt=$creatives->url($creatives->currentPage()+1);
$str_nxt1=explode("?",$str_nxt);
$search_nxt=$str_nxt1[0]."?".$str_nxt1[1].'&advertisement_id='.$advertisement_id.'&vendor_id='.$vendor_id.'&archive_category_id='.$archive_category_id.'&department_id='.$department_id."&from_date=".$from_date."&to_date=".$to_date."&document_id=".$document_id;
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
      $search=$str1[0]."?".$str1[1].'&advertisement_id='.$advertisement_id.'&vendor_id='.$vendor_id.'&archive_category_id='.$archive_category_id.'&department_id='.$department_id."&from_date=".$from_date."&to_date=".$to_date."&document_id=".$document_id;
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
<br><br>

</div>
</div>


<?php } ?>

<!-- Modal open-->


<!-- Modal Close -->
  

    <!--==END ADVANCE SEARCH TABLE== -->
  
  @include('admin/footer')
  
    <!-- Script -->

<script>
   $(document).ready(function () {
    var element = document.getElementById("approve_adaptation_creatives");
    element.classList.add("active");
    document.getElementById("mastersmanage_adaptation_employee").style.display = "block";
    var element1 = document.getElementById("menu_adaptation_employee");
    element1.classList.add("open_meunbox");

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


  <!-- LEFT-MENU-ACTIVE-CSS-START -->
  
  </body>
</html>