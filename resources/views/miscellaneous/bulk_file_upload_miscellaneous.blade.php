@include('admin/header')

@include('admin/side-menu') 

<style>
.tableFixHead {
overflow-y: auto;
height: 30%;
}
.tableFixHead thead th {
position: sticky;
top: 0;
}
table {
border-collapse: collapse;
width: 100%;
}
th,
td {
padding: 8px 16px;
}
th {
background-color: #da3d2c !important;
color: #fff !important;
}



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











</style>
    <!-- End header -->
    <!-- start tab -->
          
            <!-- main content -->
            <div class="col-lg-10 right_mainbox" id="bulk" >
            <div class="loading" id="loading" style="display:none"></div>


            <div class="row">
            <div class="col-md-12">
            @if(Session::has('successmsg'))
            <div class="alert alert-success"  id="success_message">
            <button type="button" class="close" data-dismiss="alert" aria-label="Close" onclick="get_text();">
            <span aria-hidden="true">&times;</span> </button>
            <h3 class="text-success"><i class="fa fa-check-circle"></i>Success</h3>
            {{Session::get('successmsg')}}
            </div>
            @endif
            @if(Session::has('failmsg'))
            <div class="alert alert-warning"  id="waring_message">
            <button type="button" class="close" data-dismiss="alert" aria-label="Close" onclick="get_text1();">
            <span aria-hidden="true">&times;</span> </button>
            <h3 class="text-warning"><i class="fa fa-exclamation-triangle"></i>Error!</h3>
            {{Session::get('failmsg')}}
            </div>
            @endif
            </div>
            </div>

                <!-- START UPLOAD -->
                <form method="POST" action="{{ url('/insert_bluk_upload_miscellaneous') }}" enctype="multipart/form-data">
                {{ csrf_field() }}

                <div class="row yr-month">
                    <div class="col-lg-3">
                    <h5>Years</h5>
                        <select class="form-control years"  name="year" id="year" required >
                        <option value="">Select Year</option>
                        @foreach($year_list as $years)
                        <option value="{{$years->year}}">{{$years->year}}</option>
                        @endforeach
                        </select>
                    </div>
                    <div class="col-lg-3">
                    <h5>Months</h5>
                        <select class="form-control months" name="month" id="month" required>
                        <option value="">Select Month</option>    
                        <option value="01">January</option>
                        <option value="02">February</option>
                        <option value="03">March</option>
                        <option value="04">April</option>
                        <option value="05">May</option>
                        <option value="06">June</option>
                        <option value="07">July</option>
                        <option value="08">August</option>
                        <option value="09">September</option>
                        <option value="10">October</option>
                        <option value="11">November</option>
                        <option value="12">December</option>
                        </select>
                    </div>

                    <div class="col-lg-6">
                    <a href="{{ENV('APP_URL')}}assets/sample_miscellaneous_creatives_bulk.csv">Download Sample File To Import</a>
                    </div>
                </div>


                <div class="row">
              
                    
                    <div class="col-lg-4">

                        <!--<h5>Years</h5>
                        <select class="form-control years"  name="year" id="year" required >
                        <option value="">Select Year</option>
                        @foreach($year_list as $years)
                        <option value="{{$years->year}}">{{$years->year}}</option>
                        @endforeach
                        </select>-->
                        <h5 class="mt-3">Upload Bulk File</h5>
                        <div class="image-upload mt-3">
                            <input type="file" name="photo[]" id="photo"  multiple required oninput="fileselect('filename')">
                            <label for="logo" class="upload-field" id="file-label">
                                <div class="file-thumbnail">
                                    <img id="image-preview" src="{{ENV('APP_URL')}}assets/img/upload.png" alt="" class="img-fluid">
                                    <h3 id="filename">
                                    Browse files to upload
                                    </h3>
                                </div>
                            </label>
                        </div>
                        <!-- End file upload -->
                    </div>

                    <div class="col-lg-4">
                        <h5 class="mt-3">Upload Source Bulk File</h5>
                        <div class="image-upload mt-3">
                            <input type="file" name="source_file[]" id="source_file" multiple oninput="fileselect('filename1')">
                            <label for="logo" class="upload-field" id="file-label">
                                <div class="file-thumbnail">
                                    <img id="" src="{{ENV('APP_URL')}}assets/img/upload.png" alt="" class="img-fluid" >
                                    <h3 id="filename1">
                                    Browse files to upload
                                    </h3>
                                </div>
                            </label>
                        </div>
                    </div>

                    <div class="col-lg-4">
                        <!-- start file upload -->
                       <!-- <h5>Months</h5>
                        <select class="form-control months" name="month" id="month" required>
                        <option value="">Select Month</option>     
                        <option value="1">January</option>
                        <option value="2">February</option>
                        <option value="3">March</option>
                        <option value="4">April</option>
                        <option value="5">May</option>
                        <option value="6">June</option>
                        <option value="7">July</option>
                        <option value="8">August</option>
                        <option value="9">September</option>
                        <option value="10">October</option>
                        <option value="11">November</option>
                        <option value="12">December</option>
                        </select>-->

                        <h5 class="mt-3">Upload CSV File</h5>
                        <div class="image-upload mt-3">
                            <input type="file" name="csv_file" id="csv_file" onchange="fileValue1(this)" required> 
                            <label for="logo" class="upload-field" id="file-label">
                                <div class="file-thumbnail">
                                    <img id="" src="{{ENV('APP_URL')}}assets/img/upload.png" alt="" class="img-fluid">
                                    <h3 id="filename2">
                                    Browse file to upload
                                    </h3>
                                </div>
                            </label>
                        </div>
                        
                        <!-- End file upload -->
                    </div>
                   
                </div>
                <!-- END UPLOAD -->
                <button class="btn upload-btn" type="submit">Upload</button>
                </form>

@if(!empty($verify))
<br><br>
<div id="successMessage"> </div>  
<nav class="navbar navbar-light bg-light" id="bg-light">
  <!--<a class="navbar-brand" href="#">-->
  <button type="button" id="vefiy_check" class="btn btn-success" style="border-radius:25px;padding: 8px 30px;" onclick="getids()" disabled>Verify</button>
<!--</a>-->
</nav>
<div class="tableFixHead">
<table class="table table-striped table-responsive" style="height:80%;font-size: small;" id="header-fixed">
  <thead>
    <tr>
      <th scope="col">Select All<br><input type="checkbox" id="checkall" class="from-control checkboxid" onclick="toggle(this)"></th> 
      <th scope="col">Sr. No</th>
      <th scope="col">File Name</th>
      <!--<th scope="col">Advertisement Id</th>-->
      <th scope="col">File Description</th>
      <!--<th scope="col">Language</th>-->
      <th scope="col">Brand</th>
      <!--<th scope="col">Department Type</th>-->
      <!--<th scope="col">Department</th>-->
      <th scope="col">Document Type</th>
      <!--<th scope="col">Vendor Type</th>-->
      <th scope="col">Vendor</th>
      <!--<th scope="col">Archive Category</th>-->
      <!--<th scope="col">Archive Sub Category</th>-->
      <th scope="col">Photo</th>
      <th scope="col">Other Document Type</th>
      <th scope="col">File Type</th>
      <th scope="col">Source File</th>
    </tr>
  </thead>
  <tbody>
  <?php $id=1; ?> 
  @foreach(@$verify as $verified)
    <tr>
      <td scope="row"><input onclick="checkboxcheck()" type="checkbox" id="ids" name="id" class="from-control checkboxids" value="{{$verified->id}}"></td>
      <td scope="row">{{$id}}</td>
      <td>{{$verified->file_name}}</td>
      <!--<td>{{$verified->advertisement_id}}</td>-->
      <td>{{$verified->file_description}}</td>
      <!--<td>{{$verified->language_name}}</td>-->
      <td>{{$verified->brand_name}}</td>
      <!--<td>{{$verified->department_type_name}}</td>-->
      <!--<td>{{$verified->department_name}}</td>-->
      <td>{{$verified->document_type_name}}</td>
      <!--<td>{{$verified->vendor_type_name}}</td>-->
      <td>{{$verified->vendor_name}}</td>
      <!--<td>{{$verified->archive_name}}</td>-->
      <!--<td>{{$verified->archive_sub_category}}</td>-->
      <td>{{$verified->photo_url}}</td>
      <td>{{$verified->other_document_type}}</td>
      <td>{{$verified->file_type}}</td>
      <td>{{$verified->source_file}}</td>
    </tr>
    <?php $id++;?> 
    @endforeach

  </tbody>
</table>
</div>
@endif



@if(!empty($unverify))
<br><br>
<nav class="navbar navbar-light bg-light" id="bg-light">
<a href="{{ENV('APP_URL')}}generate_csv_file_incomplete_miscellaneous" class="btn btn-info"  id="downloadLink" style="border-radius:25px;padding: 8px 30px;">Export Rejected Data</a>

<a href="{{ENV('APP_URL')}}bulk_upload_clear_all_miscellaneous" class="btn btn-info"  style="border-radius:25px;padding: 8px 30px;">Clear All</a>
</nav>
<div class="tableFixHead">
<table class="table table-striped table-responsive" style="height:80%;font-size: small;" id="header-fixed">
  <thead>     
    <tr>
      <th scope="col">Sr. No</th>
      <th scope="col">File Name</th>
      <!--<th scope="col">Advertisement Id</th>-->
      <th scope="col">File Description</th>
      <!--<th scope="col">Language</th>-->
      <th scope="col">Brand</th>
      <!--<th scope="col">Department Type</th>-->
      <!--<th scope="col">Department</th>-->
      <th scope="col">Document Type</th>
      <!--<th scope="col">Vendor Type</th>-->
      <th scope="col">Vendor</th>
      <!--<th scope="col">Archive Category</th>-->
      <!--<th scope="col">Archive Sub Category</th>-->
      <th scope="col">Photo</th>
      <th scope="col">Other Document Type</th>
      <th scope="col">File Type</th>
      <th scope="col">Source File</th>
      <th scope="col">Reason</th>
    </tr>
  </thead>
  <tbody>
  <?php $id=1; ?> 
  @foreach(@$unverify as $unverified)
    <tr>
      <td scope="row">{{$id}}</td>
      <td>{{$unverified->file_name}}</td>
      <!--<td>{{$unverified->advertisement_id}}</td>-->
      <td>{{$unverified->file_description}}</td>
      <!--<td>{{$unverified->language_name}}</td>-->
      <td>{{$unverified->brand_name}}</td>
      <!--<td>{{$unverified->department_type_name}}</td>-->
      <!--<td>{{$unverified->department_name}}</td>-->
      <td>{{$unverified->document_type_name}}</td>
      <!--<td>{{$unverified->vendor_type_name}}</td>-->
      <td>{{$unverified->vendor_name}}</td>
      <!--<td>{{$unverified->archive_name}}</td>-->
      <!--<td>{{$unverified->archive_sub_category}}</td>-->
      <td>{{$unverified->photo_url}}</td>
      <td>{{$unverified->other_document_type}}</td>
      <td>{{$unverified->file_type}}</td>
      <td>{{$unverified->source_file}}</td>
      <td>@if($unverified->status == 3) 
          @if($unverified->advertisement_id != '')
          Advertisement Id already exists
          @else
          Advertisement Id is missing
          @endif
          @elseif($unverified->status == 2) 
          Document Type does not match
          @else
          File is not selected
          @endif</td>
    </tr>
    <?php $id++;?> 
    @endforeach

  </tbody>
</table>
</div>
@endif




</div>




            <!-- End tab -->

            @include('admin/footer')

            <!-- tab script -->
<script src="//cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.js"></script>
<link rel="stylesheet" href="//cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css" id="theme-styles">

<script>
$(document).ready(function () {
var element = document.getElementById("add_bulk_file_upload_miscellaneous");
element.classList.add("active");
document.getElementById("mastersmanage_miscellaneous_employee").style.display = "block";
var element1 = document.getElementById("menu_miscellaneous_employee");
element1.classList.add("open_meunbox");

});
</script>




<script>
//   $(document).ready(function () {
//   var element = document.getElementById("bluk_upload_miscellaneous");
//   element.classList.add("active");
//   });


  function fileselect(id)
  {
    document.getElementById(id).textContent="Files(s) selected successfully";
  }


  $("form").submit(function() {
    document.getElementById("loading").style.display = "block";
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


            <!-- File upload script -->
            <script>
                function fileValue(value) {
                    var path = value.value;
                    var extenstion = path.split('.').pop();
                    if (extenstion == "jpg" || extenstion == "svg" || extenstion == "jpeg" || extenstion == "png" || extenstion == "gif") {
                        document.getElementById('image-preview').src = window.URL.createObjectURL(value.files[0]);
                        var filename = path.replace(/^.*[\\\/]/, '').split('.').slice(0, -1).join('.');
                        document.getElementById("filename").innerHTML = filename;
                    } else {
                        alert("File not supported. Kindly Upload the Image of below given extension ")
                    }
                }
            </script>
            <script>
                function fileValue1(value) {
                    var path = value.value;
                    var extenstion = path.split('.').pop();
                    if (extenstion == "csv") {
                       document.getElementById('filename2').textContent="File selected successfully";
                        document.getElementById('image-preview1').src = window.URL.createObjectURL(value.files[0]);
                        var filename = path.replace(/^.*[\\\/]/, '').split('.').slice(0, -1).join('.');
                        document.getElementById("filename1").innerHTML = filename;
                    } else {
                        alert("File not supported.kindly upload only csv file")
                    }
                }
            </script>

<!-- check all  -->

<script>
function toggle(source) {
  var checkBox = document.getElementById("checkall");
  if (checkBox.checked == true){
  $('#vefiy_check').prop('disabled', false);
  }else
  {
    $('#vefiy_check').prop('disabled', true);
  }
  
  checkboxes = document.getElementsByName('id');
  for(var i=0, n=checkboxes.length;i<n;i++) {
    checkboxes[i].checked = source.checked;
  }
}


function checkboxcheck()
{
  checkboxes = document.getElementsByName('id');
  for(var i=0, n=checkboxes.length;i<n;i++) {
  if (checkboxes[i].checked == true){
  $('#vefiy_check').prop('disabled', false);
  return false;
  }else
  {
    $('#vefiy_check').prop('disabled', true);
  }
  }
}

function getids()
{

document.getElementById("loading").style.display = "block";
var array = []
var checkboxes = document.querySelectorAll('input[type=checkbox]:checked')

for (var i = 0; i < checkboxes.length; i++) {
  array.push(checkboxes[i].value);
}
var id=1;
var _token = jQuery('input[name="_token"]').val();
jQuery.ajax({
url: "{{ENV('APP_URL')}}insert_bulk_creative_main_miscellaneous",
method: "POST",

data: {ids:array,_token: _token},
success: function(result) {
  document.getElementById("loading").style.display = "none";
    Swal.fire(
  'Success',
  'Creative added successfully',
  'success'
).then(function() {
    location.reload();
});


}
});
}

$("#downloadLink").click(
    function(e) {   
        e.preventDefault();

        //open download link in new page
        window.open( $(this).attr("href") );

        //redirect current page to success page
        window.location="add-bulk-file-upload-miscellaneous";
        window.focus();
    }
);


</script>
           
</body>

</html>