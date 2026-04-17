@include('admin/header')
@include('admin/side-menu')


            <!-- main content -->
            <div class="col-lg-6">
            <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="#">Master</a></li>
            <li class="breadcrumb-item active" aria-current="page"><a href="#">Manage Field Type</a></li>
            <li class="breadcrumb-item"><a href="{{ENV('APP_URL')}}view-vendor">Manage Vendor</a></li>
            <li class="breadcrumb-item active" aria-current="page">Edit Vendor</li>
            </ol>
            </nav>    
            <h4 class="addeditdata"> Edit Vendor </h4>

                <!-- START FORM -->
                <div class="sign-in-up-form mt-5">
                    <div class="tab-content">
                        <div id="login">
                            <div class="wrapper">
                                <div class="container">
                                @foreach($edit_services as $vendor)
                                    <form method="POST" action="{{ url('/update_vendor') }}">
                                    {{ csrf_field() }}
                                     <!-- <div class="form-group">
                                      <label for="vendor_type_id">Vendor Type</label>  
                                        <select id="vendor_type_id" name="vendor_type_id" class="form-control" required>
                                        <option value="">Select Vendor Type</option>
                                        @foreach($vendor_type_list as $vendor_type)
                                        <option value="{{$vendor_type->id}}">{{$vendor_type->vendor_type_name}}</option>
                                        @endforeach
                                        </select>
                                        
                                        <script type="text/javascript">
                                       document.getElementById('vendor_type_id').value="{{$vendor->vendor_type_id}}";
                                       </script>
                                        </div>
                                     -->
                                       <div class="form-group">
                                        <label for="name">Supplier Code</label>
                                            <input type="text" class="form-control" 
                                                placeholder="Enter Vendor Code" name="vendor_code" id="vendor_code" required value="{{$vendor->vendor_code}}" readonly>
                                            
                                        </div>

                                        <div class="form-group">
                                        <label for="keyword">Keyword</label>
                                            <input type="text" class="form-control" 
                                                placeholder="Enter keyword" name="keyword" id="keyword" required value="{{$vendor->keyword}}" >
                                        </div>

                                        <div class="form-group">
                                        <label for="name">Name</label>
                                            <input type="text" class="form-control" 
                                                placeholder="Enter Name" name="name" id="name" required value="{{$vendor->name}}" readonly>
                                            
                                        </div>
                                        <div class="form-group">
                                        <label for="email">Email ID</label>
                                            <input type="text" class="form-control" 
                                                placeholder="Enter Email ID" name="email" id="email" required value="{{$vendor->email}}" readonly>
                                            
                                        </div>
                                        <div class="form-group">
                                        <label for="email">Tax Identification Code</label>
                                            <input type="email" class="form-control" 
                                                placeholder="Enter Tax Identification Code" name="tax_identification_code" id="tax_identification_code" required value="{{$vendor->tax_identification_code}}" readonly>
                                            
                                        </div>

                                        <div class="form-group">
                                        <label for="contact_person">GSTN NO</label>
                                            <input type="text" class="form-control" 
                                                placeholder="Enter GSTN NO" name="gstn" id="gstn" value="{{$vendor->gstn}}" readonly>
                                            
                                        </div>
                                        <div class="form-group ">
                                        <label for="contact_email">City</label>
                                            <input type="email" class="form-control" 
                                                placeholder="Enter City" name="city" id="city" value="{{$vendor->city}}" readonly>
                                        </div>

                                        <div class="form-group ">
                                        <!-- <div class="form-group col-lg-6"> -->
                                        <label for="mobile_no">&nbsp;State</label>
                                            <input type="text" class="form-control" 
                                                placeholder="Enter State" name="state" id="state" required value="{{$vendor->state}}"  readonly>
                                            
                                        </div>

                                        <div class="form-group">
                                        <label for="pan_no">&nbsp;Postal Code</label>
                                            <input type="text" class="form-control" placeholder="Enter Postal Code" name="postal_code" id="postal_code" value="{{$vendor->postal_code}}" readonly>
                                           
                                        </div>
                                        <!-- </div> -->

                                        <div class="form-group">
                                        <label for="active_yn">Status</label>
                                                <select id="active_yn" class="form-control" name="active_yn" required>
                                                    <option value=""> Select </option>
                                                    <option value="0">Active</option>
                                                    <option value="1">Inactive</option>
                                                </select>
                                                
                                      <script type="text/javascript">
                                       document.getElementById('active_yn').value="{{$vendor->active_yn}}";
                                       </script>

                                       </div>

                                       <div class="form-group">
                                       <label for="password">Password</label>
                                            <input type="password" class="form-control" placeholder="Enter Password" name="password" id="password">
                                            <span  id="eye_icon" toggle="#password-field" class="fa fa-fw fa-eye-slash field-icon toggle-password"></span>
                                        </div>

                                        <!--<div class="form-group">
                                        <label for="address">Address</label>
                                            <textarea class="form-control" 
                                                placeholder="Enter Address" name="address" id="address" readonly>{{$vendor->address}}</textarea>
                                            
                                        </div>-->
                                        <input type="hidden"  name="id" id="id"  value="{{$vendor->id}}">
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

$(".toggle-password").click(function() {
$(this).toggleClass("fa-eye-slash fa-eye");
var input = $($(this).attr("toggle"));
var inputType = $('#password').attr('type');
if (inputType == "password") {
    $('#password').attr('type', 'text');
} else {
    $('#password').attr('type', 'password');
}
});
</script>
</body>




</html>