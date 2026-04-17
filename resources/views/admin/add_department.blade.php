@include('admin/header')
@include('admin/side-menu')


            <!-- main content -->
            <div class="col-lg-6">
            <h4 class="addeditdata"> Add Department </h4>  
                <!-- START FORM -->
                <div class="sign-in-up-form mt-5">
                    <div class="tab-content">
                        <div id="login">
                            <div class="wrapper">
                                <div class="container">

                                    <form method="POST" action="{{ url('/insert_department') }}">
                                    {{ csrf_field() }}
                                    <div class="form-group">
                                        <select id="department_type_id" name="department_type_id" class="form-control" required >
                                        <option value="">Select Department Type</option>
                                        @foreach($department_type_list as $department_type)
                                        <option value="{{$department_type->id}}">{{$department_type->department_type_name}}</option>
                                        @endforeach
                                        </select>
                                        <label for="department_type_id">Department Type</label>
                                        </div>
                                        <div class="form-group">
                                            <input type="text" class="form-control" 
                                                placeholder="Department Name" name="name" id="name" required pattern="[A-Z a-z]+">
                                            <label for="name">Department Name</label>
                                            <!-- <div class="line"></div> -->
                                        </div>
                                        <div class="form-group mt-5">
                                            <input type="text" class="form-control" 
                                                placeholder="Department Description" name="description" id="description">
                                            <label for="description">Department Description</label>
                                            <!-- <div class="line"></div> -->
                                        </div>
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
<!--<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>-->
<!-- tab script -->


<script>
$(document).ready(function () {
var element = document.getElementById("department");
element.classList.add("active");
document.getElementById("masters").style.display = "block";

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