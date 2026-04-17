@include('admin/header')
@include('admin/side-menu')

<div class="col-lg-6 form">
<h4 class="singlefileupload"> Upload Agreement </h4>

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
                  <form method="POST" action="{{ url('/insert_agreements') }}" enctype="multipart/form-data">
                     {{ csrf_field() }}
                     <div class="row">
                        <div class="form-group col-lg-6">
                           <h5 class="mt-3">Upload a File</h5>
                           <div class="image-upload mt-3">
                              <input type="file" name="photo" id="photo" onchange="loadFile1(event)" required>
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
                           placeholder="File Name" name="file_name" >
                       
                        <!-- <div class="line"></div> -->
                     </div>

                     <div class="form-group">
                     <label>File Description</label>
                        <textarea class="form-control" rows="2"
                           placeholder="File Description" name="file_description" id="file_description"></textarea>
                     </div>
                     <div class="row">

                        <div class="form-group col-lg-6">
                        <label for="brand_id">Brand</label>
                           <select id="brand_id" name="brand_id" class="form-control" required>
                              <option value="">Select Brand</option>
                              @foreach($brand_list as $brand)
                              <option value="{{$brand->id}}">{{$brand->name}}</option>
                              @endforeach
                           </select>
                        </div>
                        <div class="form-group col-lg-6">
                        <label for="document_type_id">Document Type</label>
                           <select id="document_type_id" name="document_type_id" class="form-control" oninput="fileValue()" required>
                              <option value="">Select Document Type</option>
                              @foreach($document_type_list as $document_type)
                              <option value="{{$document_type->id}}">{{$document_type->name}}</option>
                              @endforeach
                           </select>
                          
                     </div>
                    
                     </div>
                     <div class="form-group" id="othertype" style='display:none;'>
                        <label for="other_document_type">Other Document Type</label>
                        <input type="text" class="form-control" id="other_document_type" placeholder="Other Document Type" name="other_document_type" onchange="fileValue()">
                        </div>

   
                     <div class="row">
                        <div class="form-group col-lg-6">
                        <label for="vendor_type_id">Vendor Name</label>
                           <select id="vendor_type_id" name="vendor_type_id" class="form-control"  oninput="get_vendor_id(this.value)" required>
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
                           
                        </div>
                        <div class="form-group col-lg-6">
                              <label for="date_of_posting" class="ml-2">Agreement Type</label>
                              <select id="aggrement_type_id" name="aggrement_type_id" class="form-control" required>
                              <option value="">Select Agreement Type</option>
                              @foreach($aggrement_list as $row)
                              <option value="{{$row->id}}">{{$row->name}}</option>
                              @endforeach
                           </select>
                           </div>
                        <!--<div class="form-group col-lg-6">
                              <label for="date_of_posting" class="ml-2">Year</label>
                              <select id="date_of_posting" name="date_of_posting" class="form-control" required>
                              <option value="">Select Year</option>
                              @foreach($year_list as $years)
                              <option value="{{$years->year}}">{{$years->year}}</option>
                              @endforeach
                           </select>
                           </div>-->
                        
                     </div>


                     <div>
                        <div class="row">
                        <div class="form-group col-lg-6">
                              <label for="date_of_upload" class="ml-2">Remark</label>
                              <input type="text" class="form-control" id="remark" name="remark" >
                        </div>
                           <div class="form-group col-lg-6">
                              <label for="date_of_upload" class="ml-2">Date Of Upload </label>
                              <input type="date" class="form-control" id="date_of_upload" name="date_of_upload" required value="<?php echo date('Y-m-d');?>" readonly>
                           </div>
                        </div>
                     </div>
                     <div class="row">
                          
                           <div class="form-group col-lg-6">
                              <label for="contract_start_date" class="ml-2">Contract  Start date </label>
                              <input type="date" class="form-control " id="contract_start_date" name="contract_start_date" required  oninput="datecheck()">
                           </div>
                           <div class="form-group col-lg-6">
                              <label for="contract_end_date" class="ml-2">Contract  End date</label>
                              <input type="date" class="form-control " id="contract_end_date" name="contract_end_date" required oninput="datecheck()">
                           </div>
                           </div>
                    <!-- <div class="form-row">calender_icon1
                        
                           
                        </div> -->
                     
                     <button class="btn upload-btn" type="submit">Upload</button>
                  </form>
               </div>
            </div>
         </div>
         <!-- END FORM -->
      </div>
   </div>
</div>
<div class="col-md-3 pt-4">
<label class="ml-2">Uploaded File Preview</label>
   <div class="form-group">
      <img id="output1" />
      <div style="clear:both">
       <iframe id="viewer" frameborder="0" scrolling="no" width="350" height="300"></iframe>
    </div>
    <video width="320" height="240" poster="" controls style="display:none" id="videomp4">
      <source  type="video/mp4">
      </video> 
   </div>


</div>
<!-- End tab -->
<!--<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>-->
<!--<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>-->
<!-- tab script -->
<script>


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



function get_archive_sub_category_id(archive_category_id)
{
var _token = jQuery('input[name="_token"]').val();
jQuery.ajax({
url: "{{ENV('APP_URL')}}get_archive_sub_category",
method: "POST",
data: {archive_category_id:archive_category_id,_token: _token
},
success: function(result) {
jQuery('#archive_sub_category_id').html(result);
}
})
}




var loadFile1 = function(event) {
var reader = new FileReader();
document.getElementById('filename').textContent="File selected successfully";  

var path =document.getElementById('photo').value;
var extenstion = path.split('.').pop(); 

var fullPath = document.getElementById("photo").src;
var filename = path.replace(/^.*[\\\/]/, '');
var file_name = filename.split('.'); 


document.getElementById('file_name').value=file_name[0];

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



if(extenstion == 'png' || extenstion == 'jpg' || extenstion == 'jpeg' || extenstion == 'gif')
{  
$('#viewer').hide();    
$('#videomp4').hide();
$('#output1').show();   
reader.onload = function() {
var output = document.getElementById('output1');
output.src = reader.result;
};
reader.readAsDataURL(event.target.files[0]);

} 
else if(extenstion == 'pdf')
{  
   $('#output1').hide(); 
   $('#videomp4').hide();
   $('#viewer').show();
    pdffile=document.getElementById("photo").files[0];
    pdffile_url=URL.createObjectURL(pdffile);
    $('#viewer').attr('src',pdffile_url);

}else if (extenstion == 'mp4' || extenstion == 'mp3')
{
      $('#output1').hide();
		$('#viewer').hide();
      $('#videomp4').show();   
		pdffile = document.getElementById("photo").files[0];
		pdffile_url = URL.createObjectURL(pdffile);
		$('#videomp4').attr('src', pdffile_url);

}else {

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
    var element = document.getElementById("upload_assgreement");
    element.classList.add("active");
    document.getElementById("mastersmanage_agreement").style.display = "block";
    var element1 = document.getElementById("menu_agreement");
    element1.classList.add("open_meunbox");

   });
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

function fileValue1()
{
   document.getElementById('filename1').textContent="File selected successfully";  
}


   function datecheck()
   {
   var startDate = document.getElementById("contract_start_date").value;
   var endDate = document.getElementById("contract_end_date").value;
   if ((Date.parse(startDate) > Date.parse(endDate))) {
   alert("Contract End date should be  equal or greater than Contract Start date");
   document.getElementById("contract_end_date").value = "";
   }
   }
</script>
</body>
</html>