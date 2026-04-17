@include('admin/header')
@include('admin/side-menu')

<meta name="csrf_token" content="{{csrf_token()}}">
<style>
/* input#advertisement_id {
text-transform: uppercase;
} */
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


</style>

<div class="col-lg-6 form">
<h4 class="singlefileupload"> Generate Advertisement Id </h4>

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
                  <form method="POST" action="{{ url('/insert_generate_advertisement_id') }}" enctype="multipart/form-data" id="singlefileupload"> 
                     {{ csrf_field() }}


                    
                     <div class="row">
                        <div class="form-group col-lg-12">
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
                           
                        </div>    
                     </div> 

                     <div class="row">
                     <div class="form-group col-lg-12">
                     <label for="archive_category_id">Archive Category</label>
                        <select id="archive_category_id" name="archive_category_id" class="form-control"  required>
                        <option value="">Select Archive Category</option>

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
                        </select>
                        </div>
                        <div class="form-group col-lg-12">
                        <label for="language_id">Language</label>
                           <select id="language_id" name="language_id" class="form-control" required>
                              <option value="">Select Language</option>
                              @foreach($languages as $languag)
                              <option value="{{$languag->id}}">{{$languag->language}}</option>
                              @endforeach
                           </select>
                           
                        </div>
                        
                        
                        <div class="form-group col-lg-12">
                        <label for="type">Type</label>
                           <select id="type" name="type" class="form-control" required>
                              <option value="">Select Type</option>
                              <option value="internal">Internal</option>
                              <option value="external">External</option>
                           </select>
                        </div>
                        
                        
                     </div>


                     <!--<div class="form-group">
                     <label for="inputAddress2">Advertisement ID</label><div style="display:none" class="punctuation"> <span data-toggle="tooltip" title="File Name and Advertisement ID does not match">?</span></div>
                     <img id="image-preview2" src="{{ENV('APP_URL')}}assets/img/demo_wait.gif" alt="" style="display:none;"> 
                        <input type="text" class="form-control" id="advertisement_id"
                           placeholder="Advertisement ID" name="advertisement_id">
                       <span id="advertisement_not_match" style="display:none"><b style="color:red;">Unable to read Advertisement ID from the selected file</b></span>
                     </div>-->
                    
                    <!--<div class="form-group">
                    <label for="date_of_upload" class="ml-2">Remark</label>
                    <input type="text" class="form-control" id="remark" name="remark" placeholder='Remark'>
                    </div>-->
                     <button class="btn upload-btn" type="submit">Generate</button>
                  </form>
               </div>
            </div>
         </div>
         <!-- END FORM -->
      </div>
   </div>
</div>
<div class="col-md-3">
<h4 class="singlefileupload"> Open Advertisement Id </h4>
   <div class="form-group">
      <table id='empTable' class="table table-striped table-hover" width="100%" style="font-size:14px;font-family: inherit;">
      <thead>
      <tr>
      <!-- <th>Sr. No</th> -->
      <th>Advertisement Id</th>
      <!-- <th>Created Date</th> -->
      </tr>
      </thead>
      <tbody><?php $i=1;?>
         @foreach($advertisement_ids as $advertisementid)
         <?php $created_date = date('d/m/Y', strtotime($advertisementid->created_date));?>
         <tr>
            <!-- <td>{{$i}}</td> -->
            <td>{{$advertisementid->advertisement_id}}</td>
            <!-- <td>{{$created_date}}</td> -->
         </tr> 
         <?php $i++ ?>
         @endforeach  
      </tbody>
      </table>
      {{-- Pagination --}}
        <div class="d-flex justify-content-center">
            {!! $advertisement_ids->links() !!}
        </div>
    </div>
   </div> 


</div>







<!-- End tab -->
<!-- <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
<!-- tab script -->
<script>



//OCR  Code Start
$('#photo').on('change', function(ev) {

   
   $('#image-preview2').show();
   $('#advertisement_id').val('');
   $(".punctuation").attr("style", "display:none");

	//  console.log("here inside");
	$('#advertisement_id').val();
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
	var showfilename = path.substr(0, path.lastIndexOf("."))
	$('#file_name').val(showfilename);
	document.getElementById('file_name').value = file_name[0];
	if(extenstion == 'png' || extenstion == 'jpg' || extenstion == 'jpeg' || extenstion == 'gif' || extenstion == 'pdf') {
		 
       
      if(extenstion == 'pdf') {
      $('#output1').hide();
		$('#viewer').show();
		pdffile = document.getElementById("photo").files[0];
		pdffile_url = URL.createObjectURL(pdffile);
		$('#viewer').attr('src', pdffile_url);
     }else
     {
      $('#viewer').hide();
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
	} else if(extenstion == 'ppt' || extenstion == 'pptx' || extenstion == 'excel') {} else {
		$('#output1').hide();
		$('#viewer').show();
		pdffile = document.getElementById("photo").files[0];
		pdffile_url = URL.createObjectURL(pdffile);
		$('#viewer').attr('src', pdffile_url);
	}
	//  reader.onload=function(ev){
	//    $('#output1').attr('src',ev.target.result).css('width','450px').css('height','450px');
	//  }
	//  reader.readAsDataURL(this.files[0]);
	// alert(fileid);
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

var path =document.getElementById('photo').value;
var extenstion = path.split('.').pop(); 

var fullPath = document.getElementById("photo").src;
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
   var element = document.getElementById("generate_advertisement_id");
   element.classList.add("active");
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
</script>
</body>
</html>