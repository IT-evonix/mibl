@include('admin/header')
@include('admin/side-menu')

<meta name="csrf_token" content="{{csrf_token()}}">
<style>
input#advertisement_id {
text-transform: uppercase;
}
.punctuation {
position: absolute;
color: #fff;
font-size: 10px;
left: 144px;
top: 27px;
padding: 0px 4px;
border-radius: 21px;
background-color: #D8002A;
transform: translate(-50%, -50%);
/* -webkit-user-select: none; */
user-select: none;
cursor: default;
}
#loading { display: none; }

.tooltip {
  position: relative;
  display: inline-block;
  border-bottom: 1px dotted black;
}

.tooltip .tooltiptext {
  visibility: hidden;
  width: 120px;
  background-color: black;
  color: #fff;
  text-align: center;
  border-radius: 6px;
  padding: 5px 0;

  /* Position the tooltip */
  position: absolute;
  z-index: 1;
}

.tooltip:hover .tooltiptext {
  visibility: visible;
}



/*--- loading Start--*/


/* Absolute Center Spinner */
.loading {
  position: fixed;
  z-index: 999;
  height: 2em;
  width: 2em;
  overflow: show;
  margin: auto;
  top: 0;
  left: 0;
  bottom: 0;
  right: 0;
}

/* Transparent Overlay */
.loading:before {
  content: '';
  display: block;
  position: fixed;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
    background: radial-gradient(rgba(20, 20, 20,.8), rgba(0, 0, 0, .8));

  background: -webkit-radial-gradient(rgba(20, 20, 20,.8), rgba(0, 0, 0,.8));
}

/* :not(:required) hides these rules from IE9 and below */
.loading:not(:required) {
  /* hide "loading..." text */
  font: 0/0 a;
  color: transparent;
  text-shadow: none;
  background-color: transparent;
  border: 0;
}

.loading:not(:required):after {
  content: '';
  display: block;
  font-size: 10px;
  width: 1em;
  height: 1em;
  margin-top: -0.5em;
  -webkit-animation: spinner 150ms infinite linear;
  -moz-animation: spinner 150ms infinite linear;
  -ms-animation: spinner 150ms infinite linear;
  -o-animation: spinner 150ms infinite linear;
  animation: spinner 150ms infinite linear;
  border-radius: 0.5em;
  -webkit-box-shadow: rgba(255,255,255, 0.75) 1.5em 0 0 0, rgba(255,255,255, 0.75) 1.1em 1.1em 0 0, rgba(255,255,255, 0.75) 0 1.5em 0 0, rgba(255,255,255, 0.75) -1.1em 1.1em 0 0, rgba(255,255,255, 0.75) -1.5em 0 0 0, rgba(255,255,255, 0.75) -1.1em -1.1em 0 0, rgba(255,255,255, 0.75) 0 -1.5em 0 0, rgba(255,255,255, 0.75) 1.1em -1.1em 0 0;
box-shadow: rgba(255,255,255, 0.75) 1.5em 0 0 0, rgba(255,255,255, 0.75) 1.1em 1.1em 0 0, rgba(255,255,255, 0.75) 0 1.5em 0 0, rgba(255,255,255, 0.75) -1.1em 1.1em 0 0, rgba(255,255,255, 0.75) -1.5em 0 0 0, rgba(255,255,255, 0.75) -1.1em -1.1em 0 0, rgba(255,255,255, 0.75) 0 -1.5em 0 0, rgba(255,255,255, 0.75) 1.1em -1.1em 0 0;
}

/* Animation */

@-webkit-keyframes spinner {
  0% {
    -webkit-transform: rotate(0deg);
    -moz-transform: rotate(0deg);
    -ms-transform: rotate(0deg);
    -o-transform: rotate(0deg);
    transform: rotate(0deg);
  }
  100% {
    -webkit-transform: rotate(360deg);
    -moz-transform: rotate(360deg);
    -ms-transform: rotate(360deg);
    -o-transform: rotate(360deg);
    transform: rotate(360deg);
  }
}
@-moz-keyframes spinner {
  0% {
    -webkit-transform: rotate(0deg);
    -moz-transform: rotate(0deg);
    -ms-transform: rotate(0deg);
    -o-transform: rotate(0deg);
    transform: rotate(0deg);
  }
  100% {
    -webkit-transform: rotate(360deg);
    -moz-transform: rotate(360deg);
    -ms-transform: rotate(360deg);
    -o-transform: rotate(360deg);
    transform: rotate(360deg);
  }
}
@-o-keyframes spinner {
  0% {
    -webkit-transform: rotate(0deg);
    -moz-transform: rotate(0deg);
    -ms-transform: rotate(0deg);
    -o-transform: rotate(0deg);
    transform: rotate(0deg);
  }
  100% {
    -webkit-transform: rotate(360deg);
    -moz-transform: rotate(360deg);
    -ms-transform: rotate(360deg);
    -o-transform: rotate(360deg);
    transform: rotate(360deg);
  }
}
@keyframes spinner {
  0% {
    -webkit-transform: rotate(0deg);
    -moz-transform: rotate(0deg);
    -ms-transform: rotate(0deg);
    -o-transform: rotate(0deg);
    transform: rotate(0deg);
  }
  100% {
    -webkit-transform: rotate(360deg);
    -moz-transform: rotate(360deg);
    -ms-transform: rotate(360deg);
    -o-transform: rotate(360deg);
    transform: rotate(360deg);
  }
}

/*--- loading End--*/



</style>


<div class="col-lg-6 form">
<div class="loading" id="loading" style="display:none"></div>

<h4 class="singlefileupload"> Edit Newsletter</h4>

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
   <!-- START FORM -->
   <div class="sign-in-up-form">
      <div class="tab-content">
         <div id="login">
            <div class="wrapper">
               <div class="container">
                   @foreach($edit_services as $creatives)
                  <form method="POST" action="{{ url('/update_newsletter') }}" enctype="multipart/form-data">
                     {{ csrf_field() }}
                     <div class="row">
                        <div class="form-group col-lg-6">
                           <h5 class="mt-3">Upload a File</h5>
                           <div class="image-upload mt-3">
                              <!-- <input type="file" name="photo" id="photo" onchange="loadFile1(event)"> -->
                              <input type="file" name="photo" id="photo" >
                              <label for="logo" class="upload-field" id="file-label">
                                 <div class="file-thumbnail">
                                    <img id="image-preview" src="{{ENV('APP_URL')}}assets/img/upload.png" alt="">
                                    <h3 id="filename">
                                     Browse a file to upload
                                    </h3>
                                 </div>
                              </label>
                           </div>
                           <!-- End file upload -->
                        </div>
                        <div class="form-group col-lg-6">
                           <h5 class="mt-3">Upload a Source File</h5>
                           <div class="image-upload mt-3">
                              <input type="file" name="source_file" id="source_file" onchange="fileValue1(this)">
                              <label for="logo" class="upload-field" id="file-label">
                                 <div class="file-thumbnail">
                                    <img id="image-preview" src="{{ENV('APP_URL')}}assets/img/upload.png" alt="">
                                    <h3 id="filename1">
                                     Browse a file to upload
                                    </h3>
                                 </div>
                              </label>
                           </div>
                           <!-- End file upload -->
                        </div>
                     </div>
                     <div class="form-group">
                     <label for="inputAddress">File name</label>
                        <input type="text" class="form-control" id="file_name"
                           placeholder="File Name" name="file_name" value="{{$creatives->file_name}}">
                        
                        <!-- <div class="line"></div> -->
                     </div>
                     <div class="form-group">
                     <label for="inputAddress2">Advertisement ID</label><div style="display:none" class="punctuation"> <span data-toggle="tooltip" title="File Name and Advertisement ID does not match">?</span></div>
                     <img id="image-preview2" src="{{ENV('APP_URL')}}assets/img/demo_wait.gif" alt="" style="display:none;">   
                     <input type="text" class="form-control" id="advertisement_id"
                           placeholder="Advertisement ID" name="advertisement_id" value="{{$creatives->advertisement_id}}">
                           <span id="advertisement_not_match" style="display:none"><b style="color:red;">Unable to read Advertisement ID from the selected file</b></span>
                     </div>
                     <div class="form-group">
                     <label>File Description</label>
                        <textarea class="form-control" rows="2"
                           placeholder="File Description" name="file_description" id="file_description">{{$creatives->file_description}}</textarea>
                        
                     </div>
                     <div class="row">
                        <div class="form-group col-lg-6">
                        <label for="language_id">Language</label>
                        <select id="language_id" name="language_id" class="form-control" required>
                              <option value="">Select Language</option>
                              @foreach($languages as $languag)
                              <option value="{{$languag->id}}">{{$languag->language}}</option>
                              @endforeach
                           </select>
                           
                            <script type="text/javascript">
                            document.getElementById('language_id').value="{{$creatives->language_id}}";
                            </script>
                        </div>
                        <div class="form-group col-lg-6">
                        <label for="brand_id">Brand</label>
                           <select id="brand_id" name="brand_id" class="form-control" required>
                              <option value="">Select Brand</option>
                              @foreach($brand_list as $brand)
                              <option value="{{$brand->id}}">{{$brand->name}}</option>
                              @endforeach
                           </select>
                           
                           <script type="text/javascript">
                            document.getElementById('brand_id').value="{{$creatives->brand_id}}";
                            </script>
                        </div>
                     </div>

                     <div class="row">
                        <div class="form-group col-lg-6">
                        <label for="department_type_id">Department</label>
                           <select id="department_type_id" name="department_type_id" class="form-control" required>
                           <option value="">Select Department</option>
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
                           </select>
                           
                           <script type="text/javascript">
                            document.getElementById('department_type_id').value="{{$creatives->department_type_id}},{{$creatives->department_id}}";
                            </script>
                        </div>

                        <div class="form-group col-lg-6">
                        <label for="vendor_type_id">Vendor Name</label>
                           <select id="vendor_type_id" name="vendor_type_id" class="form-control"  required>
                           <option value="">Select Vendor Name</option>
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
                           </select>
                           
                           <script type="text/javascript">
                            document.getElementById('vendor_type_id').value="{{$creatives->vendor_type_id}},{{$creatives->vendor_id}}";
                            </script>
                        </div>
                        
                     </div>
                 
                   <!--
                     <div class="form-row">
                        <div class="form-group col-lg-6">
                           <label for="irdai_date">Letter Addressed to IRDAI Date</label>
                           <input type="date" class="form-control" id="irdai_date" name="irdai_date" required value="{{$creatives->irdai_date}}">
                        </div>
                        <div class="form-group col-lg-6">
                        <label for="irdai_addressed">Letter Addressed to IRDAI</label>
                           <select id="irdai_addressed" name="irdai_addressed" class="form-control">
                              <option value="LETTER Addressed TO IF">LETTER Addressed TO IRDAI</option>
                              <option value="YES">YES</option>
                              <option value="NO">NO</option>
                           </select>
                           <script type="text/javascript">
                            document.getElementById('irdai_addressed').value="{{$creatives->irdai_addressed}}";
                            </script>
                           
                        </div>
                     </div> -->
                     <div>
                        <div class="row">
                           <div class="form-group col-lg-6">
                              <label for="date_of_posting" class="ml-2">Campaign Month/Year</label>
                              <?php 
                              $date_of_posting= date("F Y", strtotime($creatives->date_of_posting));
                              ?>
                              <input type="text" readonly class="form-control" id="date_of_posting1" name="date_of_posting1" required value="{{$date_of_posting}}">
                              <input type="hidden"  id="date_of_posting" name="date_of_posting" required value="{{$creatives->date_of_posting}}">
                           </div>
                           <?php
                           $date=date_create($creatives->date_of_upload);
                            $date_of_upload=date_format($date,"d/m/Y");
                           ?>
                           <div class="form-group col-lg-6">
                              <label for="date_of_upload" class="ml-2">Date Of Upload </label>
                              <input type="text" class="form-control" id="date_of_upload" name="date_of_upload" readonly value="{{$date_of_upload}}">
                           </div>
                        </div>
                     </div>

                     <div class="row">
                     <div class="form-group col-lg-12">
                                            <label for="active_yn">Status</label>
                                                <select id="active_yn" class="form-control" name="active_yn" required>
                                                    <option value=""> Select </option>
                                                    <option value="0">Active</option>
                                                    <option value="1">Inactive</option>
                                                </select>
                                                
                                      <script type="text/javascript">
                                       document.getElementById('active_yn').value="{{$creatives->active_yn}}";
                                       </script>

                                       </div>
                        <!--<div class="form-group col-lg-6">
                        <label for="date_of_upload" class="ml-2">Remark</label>
                        <input type="text" class="form-control" id="remark" name="remark"  value="{{$creatives->remark}}">
                        </div>-->
                     </div>
                     <div class="form-group" >
                        <label for="date_of_upload" class="ml-2">Remark</label>
                        <input type="text" class="form-control" id="remark" name="remark"  value="{{$creatives->remark}}">
                     </div>

                     <input type="hidden" class="form-control" id="id" name="id" value="{{$creatives->id}}">
                     <input type="hidden" class="form-control" id="created_date" name="created_date" value="{{$creatives->created_date}}">
                     <input type="hidden" class="form-control" id="file_type" name="file_type" value="{{$creatives->file_type}}">
                     <button class="btn upload-btn" type="submit">Update</button>
                  </form>
                 
               </div>
            </div>
         </div>
         <!-- END FORM -->
      </div>
   </div>
</div>

<?php 
$year= date("Y", strtotime($creatives->date_of_posting));
$month= date("m", strtotime($creatives->date_of_posting));

?>
<div class="col-md-3 pt-4">
<label class="ml-2" id="file_preview">Uploaded File Preview</label>
<label class="ml-2" style="display:none;" id="current_preview">Current File Preview</label>



      <div class="form-group output_display" style='display:none;'>
      <img id="output1" />
      <div style="clear:both">
      <iframe id="viewer" frameborder="0" scrolling="no" width="400" height="300"></iframe>
      </div>
      <video width="320" height="240" poster="" controls style="display:none" id="videomp4">
      <source  type="video/mp4">
      </video>    
      </div>
   <label class="ml-2" style="display:none;" id="old_preview">Old File Preview</label>
   <div class="form-group">
    @if($creatives->file_type == 'image')   
   <img id="output2"  src="{{ENV('APP_URL')}}newsletter/{{$year}}/{{$month}}/original/{{$creatives->photo_url}}" style="width:100%;height:100%;padding:20px;">
   @elseif($creatives->file_type == 'other' && $creatives->other_document_type != 'ppt')
   <?php
   $image_arr=explode(".",$creatives->photo_url);
   $other_type=end($image_arr);
   $VIDEOID=$creatives->video_url;
   ?>
  
   @if($other_type == 'mp4')
   <iframe
   src="https://iframe.videodelivery.net/{{$VIDEOID}}"
   style="border: none;"
   height="800"
   width="800"
   allow="accelerometer; gyroscope; autoplay; encrypted-media; picture-in-picture;"
   allowfullscreen="true"
   class="img-fluid priviewimages" ></iframe>
   @else
   <iframe src="{{ENV('APP_URL')}}newsletter/{{$year}}/{{$month}}/{{$creatives->photo_url}}" width="350" height="400" autostart="false"></iframe>
   @endif
   @else
   <a href="{{ENV('APP_URL')}}newsletter/{{$year}}/{{$month}}/{{$creatives->photo_url}}">click to view powerpoint show</a>
   @endif
</div>
  
</div>

@endforeach
<!-- End tab -->
<!--<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>-->
<!-- tab script -->
<script>
   

//OCR  Code Start
    $('#photo').on('change', function(ev) {

    var path = document.getElementById('photo').value;
    var extenstion = path.split('.').pop();

    if(extenstion == 'pdf')
    {



    document.getElementById('filename').textContent="File selected successfully";
    $('#advertisement_id').val('');
    $(".punctuation").attr("style", "display:none");
    $("#advertisement_not_match").attr("style", "display:none")
    //  console.log("here inside");
    $('#advertisement_id').val();

    document.getElementById("old_preview").style.display = "block";
    document.getElementById("current_preview").style.display = "block";
    document.getElementById("file_preview").style.display = "none";

    var filedata = this.files[0];
    var imgtype = filedata.type;
    //---image preview
    var reader = new FileReader();
    var path = document.getElementById('photo').value;
    var extenstion = path.split('.').pop();
    var fullPath = document.getElementById("photo").src;
    var filename = path.replace(/^.*[\\\/]/, '');
    var file_name = filename.split('.');
    // alert(file_name);


    /*Auto select*/ 

    var _token = jQuery('input[name="_token"]').val();
    jQuery.ajax({
    url: "{{ENV('APP_URL')}}get_document_data",
    method: "POST",
    data: {extenstion:extenstion,_token: _token
    },
    success: function(result) {
    result = $.parseJSON(result);
    document.getElementById("document_type_id").value=result.id;
    }
    });



    var showfilename = path.substr(0, path.lastIndexOf("."))
    $('#file_name').val(showfilename);
    $('.output_display').show();
    document.getElementById('file_name').value = file_name[0];
    if(extenstion == 'png' || extenstion == 'jpg' || extenstion == 'jpeg' || extenstion == 'gif' || extenstion == 'pdf') {
    $('#image-preview2').show();
    if(extenstion == 'pdf') {
    $('#output1').hide();
    $('#videomp4').hide();   
    $('#viewer').show();
    pdffile = document.getElementById("photo").files[0];
    pdffile_url = URL.createObjectURL(pdffile);
    $('#viewer').attr('src', pdffile_url);
    }else
    {
    $('#viewer').hide();
    $('#videomp4').hide();
    $('#output1').show();
    reader.onload = function() {
    var output = document.getElementById('output1');
    output.src = reader.result;
    };
    }
    reader.readAsDataURL(event.target.files[0]);
    var postData = new FormData();
    postData.append('file', this.files[0]);
    // postData.append(arrfileid);
    var url = "{{ENV('APP_URL')}}upload_image_get_advertisement_id";
    $.ajax({
    headers: {
    'X-CSRF-Token': $('meta[name=csrf_token]').attr('content')
    },
    async: true,
    type: "post",
    contentType: false,
    url: url,
    data: postData,
    processData: false,
    success: function(data) {
    //filename check with pattern
    var fileInput = document.getElementById('photo');
    var fname = fileInput.files[0].name;
    var replaced = fname.split('_').join('/');
    var fileid = replaced.substr(0, replaced.lastIndexOf("."))
    // $('#file_name').val(fileid);
    let matchfileid = fileid.match(/([Ff]+[A-Za-z0-9/]+[A-Za-z0-9]+[^a-z-0-9]+([\/]\/{0,})+(\d)+)/);
    if(matchfileid) {
    var arrfileid = matchfileid.toString().split(",");
    //alert(arrfileid[0]);
    var finalfileid = arrfileid[0];
    }
    //ocr start
    data = $.parseJSON(data);
    let ocrtext = data;
    let ocrid = ocrtext.match(/([Ff]+[A-Za-z0-9/]+[A-Za-z0-9]+[^a-z-0-9]+([\/]\/{0,})+(\d)+)/);
    if(ocrid) {
    var arrocrid = ocrid.toString().split(",");
    var finalocrid = arrocrid[0].toUpperCase();
    //alert(finalocrid);
    //compare ocr and file name
    if(finalfileid == finalocrid) {
    // alert("file id checked ")
    $('#advertisement_id').val(arrfileid[0]);
    $(".punctuation").attr("style", "display:none");
    $('#image-preview2').hide();
    } else {
    // alert("ocr id checked ")
    $('#advertisement_id').val(arrocrid[0]);
    $(".punctuation").attr("style", "display:block");
    $('#image-preview2').hide();
    }
    } else {
    //alert("Advertisement id is not present in given image ")
    // $('#advertisement_id').reset();
    $("#advertisement_not_match").attr("style", "display:block")
    $('#advertisement_id').val();
    $('#image-preview2').hide();
    }
    }
    });
    } else if(extenstion == 'ppt' || extenstion == 'pptx' || extenstion == 'excel') {

    } else if(extenstion == 'mp4' || extenstion == 'mp3')  
    {
    $('#output1').hide();
    $('#viewer').hide();
    $('#videomp4').show();   
    pdffile = document.getElementById("photo").files[0];
    pdffile_url = URL.createObjectURL(pdffile);
    $('#videomp4').attr('src', pdffile_url);
    }
    else {
    }

}else
{
alert("Please Select only pdf file")
document.getElementById('photo').value='';
}

    });
    //OCR Code End



function get_department_id(department_type_id)
{
var _token = jQuery('input[name="_token"]').val();
jQuery.ajax({
url: "{{ENV('APP_URL')}}get_department",
method: "POST",
data: {department_type_id:department_type_id,_token: _token
},
success: function(result) {
jQuery('#department_id').html(result);
}
})
}

function get_vendor_id(vendor_type_id)
{
var _token = jQuery('input[name="_token"]').val();
jQuery.ajax({
url: "{{ENV('APP_URL')}}get_vendor",
method: "POST",
data: {vendor_type_id:vendor_type_id,_token: _token
},
success: function(result) {
jQuery('#vendor_id').html(result);
}
})
}




var loadFile1 = function(event) {
var reader = new FileReader();

$('.output_display').show();

var path =document.getElementById('photo').value;
var extenstion = path.split('.').pop(); 

var filename = path.replace(/^.*[\\\/]/, '');
var file_name = filename.split('.'); 
document.getElementById('file_name').value=file_name[0];

if(extenstion == 'png' || extenstion == 'jpg' || extenstion == 'jpeg' || extenstion == 'gif')
{  
$('#viewer').hide();     
$('#output1').show(); 
  
reader.onload = function() {
var output = document.getElementById('output1');
output.src = reader.result;
};
reader.readAsDataURL(event.target.files[0]);

} else if(extenstion == 'ppt' || extenstion == 'pptx' || extenstion == 'excel')
{

}
else
{  $('#output1').hide(); 
   $('#viewer').show(); 
    pdffile=document.getElementById("photo").files[0];
    pdffile_url=URL.createObjectURL(pdffile);
    $('#viewer').attr('src',pdffile_url);

}



};



   function fileValue() {
      
   var path =document.getElementById('photo').value;
   var extenstion = path.split('.').pop();
   var myArray = path.split(".");
   var exten=myArray.slice(-1).pop();
   var value=$("#document_type_id option:selected").text();
   var other_document_type =document.getElementById('other_document_type').value;
   
   if(value != 'other')
   {
    document.getElementById("other_document_type").required=false;
   } 




   if(value != 'other')
   {
   $('#othertype').hide();    
   if (extenstion == value) {
   } 
   else if(extenstion == ''){
   alert("Please upload a file.");
   document.getElementById('document_type_id').value="";
   }
   else {
   alert("Please select document type of uploaded file.");
   document.getElementById('document_type_id').value="";
   }
   }else
   {
   if(extenstion == ''){
   alert("Please upload a file.");
   document.getElementById('document_type_id').value="";
   }
   else if(extenstion != other_document_type && other_document_type != '' )
   {
      alert("Please select document type of uploaded file.");
      document.getElementById('other_document_type').value="";
   }
   else
    {
     $('#othertype').show(); 
     document.getElementById("other_document_type").required=true;

    }
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
<!-- File upload script -->
<script>
   function checkValue(element) {
   // check if the input has any value (if we've typed into it)
   if ($(element).val())
   $(element).addClass('has-value');
   else
   $(element).removeClass('has-value');
   }
   
   $(document).ready(function () {
   // Run on page load
   $('.form-control').each(function () {
   checkValue(this);
   })
   // Run on input exit
   $('.form-control').blur(function () {
   checkValue(this);
   });
   
   });
</script>
@include('admin/footer')
<script>
   $(document).ready(function () {
   var element = document.getElementById("manage_creatives");
   element.classList.add("active");
   });

   
function fileValue1()
{
document.getElementById('filename1').textContent="File selected successfully";  
}

$("form").submit(function() {
document.getElementById("loading").style.display = "block";
});


</script>
</body>
</html>