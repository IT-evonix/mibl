@include('admin/header')
@include('admin/side-menu')


            <!-- main content -->
            <div class="col-lg-6">

            <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ENV('APP_URL')}}view-user">Manage User</a></li>
            <li class="breadcrumb-item active" aria-current="page">Edit User</li>
            </ol>
            </nav> 
            <h4 class="addeditdata"> Edit User </h4>
                <!-- START FORM -->
                <div class="sign-in-up-form mt-5">
                    <div class="tab-content">
                        <div id="login">
                            <div class="wrapper">
                                <div class="container">
                                @foreach($edit_services as $user)
                                    <form method="POST" action="{{ url('/update_user') }}" enctype="multipart/form-data">
                                    {{ csrf_field() }}
                                    
                                    <div class="form-group">
                                        <label for="name">SAP Code</label>
                                            <input type="text" class="form-control" placeholder="Enter SAP Code" name="sap_code" id="sap_code" required  value="{{$user->sap_code}}" readonly>
                                    </div>

                                    <div class="form-group">
                                    <label for="user_type">Role</label>
                                                <select id="user_type" class="form-control" name="user_type" required>
                                                   <option value="">Select Role</option>  
                                                  @foreach($user_type as $usertype)
                                                    @if($user->user_type == $usertype->user_type_name)
                                                    <option value="{{$usertype->user_type_name}}" selected="selected">{{$usertype->user_type_name}}</option>
                                                    @else
                                                    <option value="{{$usertype->user_type_name}}">{{$usertype->user_type_name}}</option>
                                                    @endif
                                                    @endforeach
                                                </select>
                                                
                                         <script type="text/javascript">
                                          document.getElementById('user_type').value="{{$user->user_type}}";
                                         </script>
                                       
                                    </div>
                                        <div class="form-group">
                                        <label for="name">Name</label>
                                            <input type="text" class="form-control" 
                                                placeholder="Enter Name" name="name" id="name" required value="{{$user->name}}" readonly >
                                            
                                        </div>
                                        <div class="form-group">
                                        <label for="email">Email </label>
                                            <input type="email" class="form-control" 
                                                placeholder="Enter Email Id" name="email" id="email" required value="{{$user->email}}" readonly>
                                            
                                        </div>

                                        <!-- <div class="form-group">
                                            <input type="password" class="form-control" placeholder="Enter Password" name="password" id="password">
                                            <label for="password">Password</label>
                                        </div> -->
                                        <div class="form-row">
                                        <div class="form-group col-lg-6">
                                        <label for="mobile_no">&nbsp;Mobile No</label>
                                            <input type="text" class="form-control" 
                                                placeholder="Enter Mobile No" name="mobile_no" id="mobile_no" required value="{{$user->mobile_no}}" maxlength="10" minlength="10" pattern="[0-9]+" readonly>
                                            
                                        </div>

                                            <!-- <div class="form-group mt-5 col-lg-6">
                                                <input type="text" class="form-control" 
                                                    placeholder="Enter Pan No" name="pan_no" id="pan_no" value="{{$user->pan_no}}" pattern="[A-Z]{5}[0-9]{4}[A-Z]{1}">
                                                <label for="pan_no">&nbsp;Pan No</label>
                                            </div> -->

                                            <div class="form-group col-lg-6">
                                            <label for="active_yn">Status</label>
                                                <select id="active_yn" class="form-control" name="active_yn" required>
                                                    <option value=""> Select </option>
                                                    <option value="0">Active</option>
                                                    <option value="1">Inactive</option>
                                                </select>
                                                
                                      <script type="text/javascript">
                                       document.getElementById('active_yn').value="{{$user->active_yn}}";
                                       </script>

                                       </div>

                                        </div>

                                       
                                       <div class="form-group">
                                       <label for="address">Address</label>
                                            <textarea class="form-control" 
                                                placeholder="Enter Address" name="address" id="address" readonly>{{$user->address}}</textarea>
                                            
                                        </div>
                                       
                                        <!--<div class="form-row">
                                        <div class="form-group col-lg-6">
                                        <label for="photo">&nbsp;Photo Upload</label>
                                            <input type="file" class="form-control" name="photo" id="photo" oninput="loadFile1(event)">
                                            
                                        </div>
                                        <div class="form-group col-lg-6">
                                        <img src="{{ENV('APP_URL')}}{{$user->photo}}" class="view-image" width="30%">
                                        </div>
                                        </div>-->
                                        
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
var element = document.getElementById("user");
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
</body>




</html>