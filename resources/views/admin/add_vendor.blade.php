@include('admin/header')
@include('admin/side-menu')

<style>
.invalid
{
  color: red;
  margin-left: 35px;
}

.invalid:before {
  position: relative;
  left: -35px;
  content: "✖";
}    

.valid {
  color: green;
  margin-left: 35px;
}

.valid:before {
  position: relative;
  left: -35px;
  content: "✔";
}    
</style>
            <!-- main content -->
            <div class="col-lg-6">

            <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="#">Master</a></li>
            <li class="breadcrumb-item active" aria-current="page"><a href="#">Manage Field Type</a></li>
            <li class="breadcrumb-item"><a href="{{ENV('APP_URL')}}view-vendor">Manage Vendor</a></li>
            <li class="breadcrumb-item active" aria-current="page">Add Vendor</li>
            </ol>
            </nav>    

            <h4 class="addeditdata"> Add Vendor </h4>


                <!-- START FORM -->
                <div class="sign-in-up-form mt-5">
                    <div class="tab-content">
                        <div id="login">
                            <div class="wrapper">
                                <div class="container">

                                    <form method="POST" action="{{ url('/insert_vendor') }}">
                                    {{ csrf_field() }}

                                      <!--<div class="form-group">
                                        <label for="vendor_code">Vendor Code</label>
                                        <select id="vendor_code" name="vendor_code" class="form-control" required  oninput="get_vendor_details(this.value)">
                                        <option value="">Select Vendor Code</option>
                                        @foreach($vendor_list as $vendors)
                                        <option value="{{$vendors->vendor_code}}">{{$vendors->vendor_code}}</option>
                                        @endforeach
                                        </select>
                                        </div>-->
                                        <div class="form-group">
                                        <label for="name">Vendor Type</label>
                                        <select id="vendor_types" name="vendor_types" class="form-control" required  oninput="get_vendor_type(this.value)">
                                        <option value="">Select Vendor Type</option>
                                        <option value="Supplier">Supplier</option>
                                        <option value="Other">Other</option>
                                        </select>
                                       </div>
                                        
                                        <div class="form-group" id="vendorcodelists">
                                        <label for="name">Supplier Code</label>
                                            <input type="text" list="vendor_code_list" id="vendor_code" name="vendor_code" class="form-control" readonly   oninput="get_vendor_details(this.value)">
                                            <datalist id="vendor_code_list">
                                             @foreach($vendor_list as $vendors)
                                             <option value="{{$vendors->vendor_code}}">{{$vendors->vendor_code}}</option>
                                             @endforeach
                                            </datalist>
                                       </div>
                                       
                                       <div class="form-group" id="vendorcodelists2" style="display:none;">
                                        <label for="name">Supplier Code</label>
                                            <input type="text"  id="vendor_code1" name="vendor_code1" class="form-control">
                                       </div>
                                       
                                       
                                       
                                        <div class="form-group">
                                        <label for="keyword">Keyword</label>
                                        <input type="text" class="form-control" 
                                        placeholder="Enter keyword" name="keyword" id="keyword" required>
                                        </div>

                                        
                                       <!--
                                        <div class="form-group">
                                        <label for="vendor_type_id">Vendor Type</label>
                                        <select id="vendor_type_id" name="vendor_type_id" class="form-control" required>
                                        <option value="">Select Vendor Type</option>
                                        @foreach($vendor_type_list as $vendor_type)
                                        <option value="{{$vendor_type->id}}">{{$vendor_type->vendor_type_name}}</option>
                                        @endforeach
                                        </select>
                                        
                                        </div>-->
                                        <div class="form-group">
                                        <label for="name">Name</label>
                                            <input type="text" class="form-control" 
                                                placeholder="Enter Name" name="name" id="name" readonly >
                                            
                                        </div>

                                        <div class="form-group">
                                        <label for="email">Email ID</label>
                                            <input type="email" class="form-control" 
                                                placeholder="Enter Email ID" name="email" id="email" readonly  pattern="[a-z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,4}$" >
                                            
                                        </div>
                                        <div class="form-group">
                                        <label for="tax_identification_code">Tax Identification Code</label>
                                            <input type="text" class="form-control" 
                                                placeholder="Enter Tax Identification Code" name="tax_identification_code" id="tax_identification_code" readonly >
                                            
                                        </div>

                                        <div class="form-group">
                                        <label for="gstn">GSTN No </label>
                                            <input type="text" class="form-control" 
                                                placeholder="Enter GSTN" name="gstn" id="gstn" readonly >
                                            
                                        </div>
                                        <div class="form-group">
                                        <label for="city">City</label>
                                            <input type="text" class="form-control" 
                                                placeholder="Enter City" name="city" id="city" readonly pattern="^[a-zA-Z]+$" title="Please enter only alphabets">
                                            
                                        </div>

                                        <div class="form-group">
                                        <!-- <div class="form-group mt-5 col-lg-6"> -->
                                        <label for="state">&nbsp;State</label>
                                            <input type="text" class="form-control" 
                                                    placeholder="Enter State" name="state" id="state" readonly pattern="^[a-zA-Z]+$" title="Please enter only alphabets">
                                        </div>
                                        <!-- </div> -->
                                        <div class="form-group">
                                        <label for="postal_code">&nbsp;Postal Code</label>
                                            <input type="number" class="form-control" 
                                                placeholder="Enter Postal Code" name="postal_code" id="postal_code" readonly pattern="[0-9]" title="Please enter only Number">
                                            
                                        </div>
                                        
                                        <div class="form-group">
                                        <label for="keyword">Password</label>
                                        <input type="password" class="form-control" 
                                        placeholder="Enter Password" name="password" id="password" required pattern="(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{8,}">
                                        <span  id="eye_icon" toggle="#password-field" class="fa fa-fw fa-eye field-icon toggle-password"></span>
                                        <br>
                                        <p id="letter" class="invalid">A <b>lowercase</b> letter</p>
                                        <p id="capital" class="invalid">A <b>capital (uppercase)</b> letter</p>
                                        <p id="number" class="invalid">A <b>number</b></p>
                                        <p id="number" class="invalid">A <b>Special Character</b></p>
                                        <p id="length" class="invalid">Minimum <b>8 characters</b></p>
                                        <!-- <label toggle="#password-field"  class="fa fa-fw fa-eye field-icon toggle-password"></label>     -->
                                        </div>

                                        <!--<div class="form-group">
                                        <label for="address">Address</label>
                                            <textarea class="form-control" 
                                                placeholder="Enter Address" name="address" id="address" readonly></textarea>
                                            
                                        </div>-->
                                            <input type="hidden" name="id"  id="id">
                                            <button class="btn upload-btn" type="submit">Submit</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                        <!-- END FORM -->
                    </div>
                </div>
            </div>


            <!-- End tab -->

            @include('admin/footer')
<!-- <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script> -->
<!-- tab script -->


<script>
  $(document).ready(function () {
  var element = document.getElementById("vendor");
  element.classList.add("active");
  document.getElementById("masters").style.display = "block";
  document.getElementById("fields").style.display = "block";
  var element1 = document.getElementById("menu");
  element1.classList.add("open_meunbox");
  });
  </script>




<!-- form script -->
<script>


function get_vendor_type(type)
{
  if(type == 'Other' && type != 'Supplier'){
    $("#vendorcodelists").css("display","none");
    $("#vendorcodelists2").css("display","block");
    
    $("#name").attr("readonly", false); 
    $("#email").attr("readonly", false);
    $("#tax_identification_code").attr("readonly", false); 
    $("#gstn").attr("readonly", false); 
    $("#city").attr("readonly", false); 
    $("#state").attr("readonly", false); 
    $("#postal_code").attr("readonly", false); 
    
    $("#name").attr("required", true); 
    $("#email").attr("required", true);
    $("#tax_identification_code").attr("required", false); 
    $("#gstn").attr("required", false); 
    $("#city").attr("required", true); 
    $("#state").attr("required", true); 
    $("#postal_code").attr("required", true); 
    $("#vendor_code1").attr("required", true);
    $("#vendor_code").attr("required", false); 
    
    document.getElementById("name").value='';
    document.getElementById("tax_identification_code").value='';
    document.getElementById("gstn").value='';
    document.getElementById("city").value='';
    document.getElementById("email").value='';
    document.getElementById("state").value='';
    document.getElementById("postal_code").value='';
    document.getElementById("vendor_code").value='';
    document.getElementById("vendor_code1").value='';
    document.getElementById("keyword").value='';
    document.getElementById("id").value='';
    
  }else 
  {
   $("#vendorcodelists").css("display","block");
   $("#vendor_code").attr("readonly", false);
   
    $("#name").attr("readonly", true); 
    $("#email").attr("readonly", true);
    $("#tax_identification_code").attr("readonly", true); 
    $("#gstn").attr("readonly", true); 
    $("#city").attr("readonly", true); 
    $("#state").attr("readonly", true); 
    $("#postal_code").attr("readonly", true);
    
    
    $("#name").attr("required", false); 
    $("#email").attr("required", false);
    $("#tax_identification_code").attr("required", false); 
    $("#gstn").attr("required", false); 
    $("#city").attr("required", false); 
    $("#state").attr("required", false); 
    $("#postal_code").attr("required", false); 
    $("#vendor_code1").attr("required", false);
    $("#vendor_code").attr("required", true); 
    
    document.getElementById("name").value='';
    document.getElementById("tax_identification_code").value='';
    document.getElementById("gstn").value='';
    document.getElementById("city").value='';
    document.getElementById("email").value='';
    document.getElementById("state").value='';
    document.getElementById("postal_code").value='';
    document.getElementById("vendor_code").value='';
    document.getElementById("vendor_code1").value='';
    document.getElementById("keyword").value='';

    document.getElementById("id").value='';

   
   $("#vendorcodelists2").css("display","none"); 
   
   
  }
}




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





function get_vendor_details(vendor_code)
{
document.getElementById("name").value='';
document.getElementById("tax_identification_code").value='';
document.getElementById("gstn").value='';
document.getElementById("city").value='';
document.getElementById("email").value='';
document.getElementById("state").value='';
document.getElementById("postal_code").value='';
document.getElementById("id").value='';

var _token = jQuery('input[name="_token"]').val();
jQuery.ajax({
url: "{{ENV('APP_URL')}}get_vendor_details",
method: "POST",
data: {vendor_code:vendor_code,_token: _token
},
success: function(result) {
result = $.parseJSON(result);
document.getElementById("name").value=result.name;
document.getElementById("tax_identification_code").value=result.tax_identification_code;
document.getElementById("gstn").value=result.gstn;
document.getElementById("city").value=result.city;
document.getElementById("state").value=result.state;
document.getElementById("email").value=result.email;
document.getElementById("postal_code").value=result.postal_code;
document.getElementById("id").value=result.id;

}
})
}


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
</script>



<script>
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