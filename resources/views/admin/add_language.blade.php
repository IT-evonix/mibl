@include('admin/header')
@include('admin/side-menu')


            <!-- main content -->
            <div class="col-lg-6">
            <h4 class="addeditdata">Add Language</h4>
                <!-- START FORM -->
                <div class="sign-in-up-form mt-5">
                    <div class="tab-content">
                        <div id="login">
                            <div class="wrapper">
                                <div class="container">

                                    <form method="POST" action="{{ url('/insert_language') }}">
                                    {{ csrf_field() }}
                                        <div class="form-group">
                                            <input type="text" class="form-control" 
                                                placeholder="Language" name="language" id="language"  required pattern="[A-Z a-z]+">
                                            <label for="user_type_name">Language</label>
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
<!-- <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script> -->
<!-- tab script -->


<script>
$(document).ready(function () {
var element = document.getElementById("language");
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