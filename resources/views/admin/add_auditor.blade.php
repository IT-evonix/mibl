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
            <li class="breadcrumb-item"><a href="{{ENV('APP_URL')}}view-user">Manage Auditor</a></li>
            <li class="breadcrumb-item active" aria-current="page">Add Auditor</li>
            </ol>
            </nav> 
            <h4 class="addeditdata"> Add Auditor </h4>
                <!-- START FORM -->
                <div class="sign-in-up-form mt-5">
                    <div class="tab-content">
                        <div id="login">
                            <div class="wrapper">
                                <div class="container">

                                    <form method="POST" action="{{ url('/insert_auditor') }}" enctype="multipart/form-data" id="formId">
                                    {{ csrf_field() }}

                                   

                                        <div class="form-group">
                                        <label for="name">Name</label>
                                            <input autocomplete="off" type="text" class="form-control" placeholder="Enter Name" name="name" id="name" required pattern="[A-Z a-z]+">
                                        </div>
                                        <div class="form-group">
                                        <label for="email">Email</label>
                                            <input type="email" class="form-control" placeholder="Enter Email Id" name="email" id="email" required pattern="[a-z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,4}$" >
                                        </div>
                                        
                                        <div class="form-row">
                                        <label for="mobile_no">&nbsp;Mobile No</label>
                                            <input type="text" class="form-control" 
                                                placeholder="Enter Mobile No" name="mobile_no" id="mobile_no" required maxlength="10" minlength="10" pattern="[0-9]+" >
                                        </div>
                                        
                                        <div class="form-group">
                                        <label for="address">Address</label>
                                            <textarea class="form-control" 
                                                placeholder="Enter Address" name="address" id="address" required ></textarea>
                                        </div>

                                        
                                        <div class="form-group">
                                        <label for="name">Username</label>
                                        <input autocomplete="off" type="text" class="form-control" list="sap_code_list" placeholder="Enter Username" name="sap_code" id="sap_code" required>
                                        </div>
                                        
                                        <div class="form-group">
                                            <label for="password">Password</label>
                                            <input autocomplete="off" type="password" class="form-control" placeholder="Enter Password" name="password" id="password" required pattern="(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{8,}">
                                            <span  id="eye_icon" toggle="#password-field" class="fa fa-fw fa-eye field-icon toggle-password"></span>
                                        <br>
                                        <p id="letter" class="invalid">A <b>lowercase</b> letter</p>
                                        <p id="capital" class="invalid">A <b>capital (uppercase)</b> letter</p>
                                        <p id="number" class="invalid">A <b>number</b></p>
                                        <!--<p id="number" class="invalid">A <b>Special Character</b></p>-->
                                        <p id="length" class="invalid">Minimum <b>8 characters</b></p>
                                        </div>
                                        

                                        
                                        <button class="btn upload-btn" type="submit" >Submit</button>

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

<!--<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>-->
<!-- tab script -->

<script>
    $(document).ready(function () {
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
$(document).ready(function () {
var element = document.getElementById("auditor");
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

function get_user_details(sap_code)
{
document.getElementById("name").value='';
document.getElementById("email").value='';
document.getElementById("mobile_no").value='';
document.getElementById("address").value='';
document.getElementById("id").value='';

var _token = jQuery('input[name="_token"]').val();
jQuery.ajax({
url: "{{ENV('APP_URL')}}get_user_details",
method: "POST",
data: {sap_code:sap_code,_token: _token
},
success: function(result) {
result = $.parseJSON(result);
document.getElementById("name").value=result.name;
document.getElementById("email").value=result.email;
document.getElementById("mobile_no").value=result.mobile_no;
document.getElementById("address").value=result.address;
document.getElementById("id").value=result.id;


}
})
}

function submitDetailsForm() 
  {
    var card = document.getElementById("user_types");

      if (jQuery('#name').val().length == '')
      {
        alert("Invalid SAP Code");
        return false;
      }
      if(card.selectedIndex == 0) {
       alert('Please select user type');
       return false
       }
        
      else
      {
        $("#formId").submit();
      }
  }

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

<script>
$(document).ready(function() {
$('#formId').attr('autocomplete', 'off');
});
</script>



</body>




</html>