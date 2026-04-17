@include('admin/header')
@include('admin/side-menu')


            <!-- main content -->
            <div class="col-lg-6">

            <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ENV('APP_URL')}}vendor-campaign-creatives-list">Manage Campaign Creatives</a></li>
            <li class="breadcrumb-item active" aria-current="page">Edit Campaign Creatives</li>
            </ol>
            </nav>
            
                        
     <div class="row">
      <div class="col-12">
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
          </div>
      <!-- /.col -->
    </div>
            
            
            <h4 class="addeditdata"> Edit Campaign Creatives </h4>
                <!-- START FORM -->
                <div class="sign-in-up-form mt-5">
                    <div class="tab-content">
                        <div id="login">
                            <div class="wrapper">
                                <div class="container">
                                @foreach($edit_services as $user)
                                    <form method="POST" action="{{ url('/vendor_update_campaign_creatives') }}" enctype="multipart/form-data">
                                    {{ csrf_field() }}
                                       
                                       
                                        <!--<div class="form-group">-->
                                        <!--<label for="email">Select Vendor<span style='color:red;'><b>*</b></span></label>-->
                                        <!--<select class="form-control"  name="vendor_id" id="vendor_id" required>-->
                                        <!--<option value="">Select Vendor</option> -->
                                        <!--@foreach($vendor as $ven)-->
                                        <!--<option value="{{$ven->id}}">{{$ven->name}}</option> -->
                                        <!--@endforeach-->
                                        <!--</select>-->
                                        <!--<script type="text/javascript">-->
                                        <!--document.getElementById('vendor_id').value="{{$user->vendor_id}}";-->
                                        <!--</script>-->
                                        <!--</div>-->
                                        
                                        <div class="form-group">
                                        <label for="name">Campaign Name <span style='color:red;'><b>*</b></span></label>
                                        <input type="text" class="form-control" 
                                        placeholder="Enter Name" name="campaign_name" id="campaign_name" required value="{{$user->campaign_name}}"  >
                                        </div>
                                        
                                        <div class="form-group">
                                        <label for="name">Campaign Date <span style='color:red;'><b>*</b></span></label>
                                        <input type="date" class="form-control" 
                                        placeholder="Enter Date" name="campaign_date" id="campaign_date" required value="{{$user->campaign_date}}"  >
                                        </div>
                                        
                                        <div class="form-group">
                                        <label for="name">Campaign File <span style='color:red;'><b>*</b></span> <b>( Upload only .zip file )</b></label>
                                        <input autocomplete="off" type="file" class="form-control" placeholder="Enter File" name="campaign_file" id="campaign_file"  accept=".zip" >
                                        <a href="{{ENV('APP_URL')}}{{$user->campaign_file}}" download><img src="{{ENV('APP_URL')}}/assets/images/download_icon.png" class='img-fluid tab-img'></a> 
                                        </div>
                                    
                                        <input type="hidden"  name="id" id="id"  value="{{$user->id}}">
                                        <button class="btn upload-btn" type="submit">Update</button>
                                    </form>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                        <!-- END FORM -->
                    </div>
                </div>
            </div>


            <!-- End tab -->
@include('admin/footer')

<!--<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>-->
<!-- tab script -->


<script>
$(document).ready(function () {
var element = document.getElementById("campaign_upload");
element.classList.add("active");
});

var loadFile1 = function(event) {

var reader = new FileReader();

var path =document.getElementById('photo').value;
var extenstion = path.split('.').pop(); 

if(extenstion == 'png' || extenstion == 'jpg' || extenstion == 'jpeg' || extenstion == 'gif')
{

}else
{   path =document.getElementById('photo').value='';
    alert('please select image');
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




<!-- form script -->
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

<script>


$(".toggle-password").click(function() {
$(this).toggleClass("fa-eye fa-eye-slash");
var input = $($(this).attr("toggle"));
var inputType = $('#password').attr('type');
if (inputType == "password") {
    $('#password').attr('type', 'text');
} else {
    $('#password').attr('type', 'password');
}
});


var myInput = document.getElementById("password");
var letter = document.getElementById("letter");
var capital = document.getElementById("capital");
var number = document.getElementById("number");
var length = document.getElementById("length");

// When the user clicks on the password field, show the message box
myInput.onfocus = function() {
  document.getElementById("message").style.display = "block";
}

// When the user clicks outside of the password field, hide the message box
myInput.onblur = function() {
  document.getElementById("message").style.display = "none";
}

// When the user starts to type something inside the password field
myInput.onkeyup = function() {
  // Validate lowercase letters
  var lowerCaseLetters = /[a-z]/g;
  if(myInput.value.match(lowerCaseLetters)) {  
    letter.classList.remove("invalid");
    letter.classList.add("valid");
  } else {
    letter.classList.remove("valid");
    letter.classList.add("invalid");
  }
  
  // Validate capital letters
  var upperCaseLetters = /[A-Z]/g;
  if(myInput.value.match(upperCaseLetters)) {  
    capital.classList.remove("invalid");
    capital.classList.add("valid");
  } else {
    capital.classList.remove("valid");
    capital.classList.add("invalid");
  }

  // Validate numbers
  var numbers = /[0-9]/g;
  if(myInput.value.match(numbers)) {  
    number.classList.remove("invalid");
    number.classList.add("valid");
  } else {
    number.classList.remove("valid");
    number.classList.add("invalid");
  }
  
  // Validate length
  if(myInput.value.length >= 8) {
    length.classList.remove("invalid");
    length.classList.add("valid");
  } else {
    length.classList.remove("valid");
    length.classList.add("invalid");
  }
}
</script>




</body>




</html>