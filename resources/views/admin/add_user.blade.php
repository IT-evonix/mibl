@include('admin/header')
@include('admin/side-menu')


            <!-- main content -->
            <div class="col-lg-6">

            <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ENV('APP_URL')}}view-user">Manage User</a></li>
            <li class="breadcrumb-item active" aria-current="page">Add User</li>
            </ol>
            </nav> 
            <h4 class="addeditdata"> Add User </h4>
                <!-- START FORM -->
                <div class="sign-in-up-form mt-5">
                    <div class="tab-content">
                        <div id="login">
                            <div class="wrapper">
                                <div class="container">

                                    <form method="POST" action="{{ url('/insert_user') }}" enctype="multipart/form-data" id="formId">
                                    {{ csrf_field() }}

                                    <div class="form-group">
                                        <label for="name">SAP Code</label>
                                            <input type="text" class="form-control" list="sap_code_list" placeholder="Enter SAP Code" name="sap_code" id="sap_code" required  oninput="get_user_details(this.value)">
                                            <datalist id="sap_code_list">
                                             @foreach($user_sap_code as $sapcode)
                                             <option value="{{$sapcode->sap_code}}">{{$sapcode->sap_code}}</option>
                                             @endforeach
                                            </datalist>
                                    </div>

                                      <div class="form-group">
                                      <label for="user_types">Role</label>
                                                <select id="user_types" class="form-control" name="user_type" required>
                                                    <option value="user_types"> Select Role</option>
                                                    @foreach($user_type as $usertype)
                                                    <option value="{{$usertype->user_type_name}}">{{$usertype->user_type_name}}</option>
                                                    @endforeach
                                                </select>
                                               
                                       </div>
                                        <div class="form-group">
                                        <label for="name">Name</label>
                                            <input type="text" class="form-control" placeholder="Enter Name" name="name" id="name" required pattern="[A-Z a-z]+" readonly>
                                            
                                        </div>
                                        <div class="form-group">
                                        <label for="email">Email</label>
                                            <input type="email" class="form-control" placeholder="Enter Email Id" name="email" id="email" required pattern="[a-z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,4}$" readonly>
                                            
                                        </div>
                                        <!--
                                        <div class="form-group">
                                            <input type="password" class="form-control" placeholder="Enter Password" name="password" id="password" required>
                                            <label for="password">Password</label>
                                        </div>-->
                                        <div class="form-row">
                                        <label for="mobile_no">&nbsp;Mobile No</label>
                                            <input type="text" class="form-control" 
                                                placeholder="Enter Mobile No" name="mobile_no" id="mobile_no" required maxlength="10" minlength="10" pattern="[0-9]+" readonly>
                                            

                                        <!-- <div class="form-group col-lg-6">
                                            <input type="text" class="form-control" placeholder="Enter Pan No" name="pan_no" id="pan_no" pattern="[A-Z]{5}[0-9]{4}[A-Z]{1}">
                                            <label for="pan_no">&nbsp;Pan No</label>
                                        </div> -->
                                        </div>

                                        <div class="form-group">
                                        <label for="address">Address</label>
                                            <textarea class="form-control" 
                                                placeholder="Enter Address" name="address" id="address" required readonly></textarea>
                                            
                                        </div>

                                        <!--<div class="form-group">
                                        <label for="photo">&nbsp;Photo Upload</label>
                                            <input type="file" class="form-control" name="photo" id="photo" oninput="loadFile1(event)">
                                            
                                        </div>-->
                                           <input type="hidden" name="id"  id="id">
                                            <button class="btn upload-btn" type="button" onclick="submitDetailsForm()">Submit</button>

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
var element = document.getElementById("user");
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

</body>




</html>