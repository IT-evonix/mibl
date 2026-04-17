@include('admin/header')
@include('admin/side-menu')


            <!-- main content -->
            <div class="col-lg-6">
            <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="#">Master</a></li>
            <li class="breadcrumb-item active" aria-current="page"><a href="#">Manage Field Type</a></li>
            <li class="breadcrumb-item"><a href="{{ENV('APP_URL')}}view-department">Manage Department</a></li>
            <li class="breadcrumb-item active" aria-current="page">Edit Department</li>
            </ol>
            </nav>    


            <h4 class="addeditdata"> Edit Department </h4>  
                <!-- START FORM -->
                <div class="sign-in-up-form mt-5">
                    <div class="tab-content">
                        <div id="login">
                            <div class="wrapper">
                                <div class="container">
                                   @foreach($edit_services as $department)
                                    <form method="POST" action="{{ url('/update_department') }}">
                                    {{ csrf_field() }}
                                    <div class="form-group">
                                    <label for="department_type_id">Department Type</label>
                                        <select id="department_type_id" name="department_type_id" class="form-control" required disabled>
                                        <option value="">Select Department Type</option>
                                        @foreach($department_type_list as $department_type)
                                        <option value="{{$department_type->id}}">{{$department_type->department_type_name}}</option>
                                        @endforeach
                                        </select>
                                       
                                        <script type="text/javascript">
                                       document.getElementById('department_type_id').value="{{$department->department_type_id}}";
                                       </script>
                                        </div>
                                        <div class="form-group">
                                        <label for="name"> Name</label>
                                            <input type="text" class="form-control" placeholder="Department Name" name="name" id="name" readonly value="{{$department->name}}">
                                            
                                            <!-- <div class="line"></div> -->
                                        </div>
                                        <div class="form-group">
                                        <label for="description"> Keyword</label>
                                            <input type="text" class="form-control"
                                                placeholder="Keyword" name="keyword" id="keyword" value="{{$department->keyword}}">
                                            
                                            <!-- <div class="line"></div> -->
                                        </div>
                                        <div class="form-group">
                                        <label for="active_yn">Status</label>
                                                <select id="active_yn" class="form-control" name="active_yn" required>
                                                    <option value=""> Select </option>
                                                    <option value="0">Active</option>
                                                    <option value="1">Inactive</option>
                                                </select>
                                                
                                      <script type="text/javascript">
                                       document.getElementById('active_yn').value="{{$department->active_yn}}";
                                       </script>

                                       </div>
                                       <input type="hidden"  name="id" id="id"  value="{{$department->id}}">
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

<script>
$(document).ready(function () {
var element = document.getElementById("department");
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
</script>
</body>




</html>