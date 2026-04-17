@include('admin/header')
@include('admin/side-menu') 
<?php
$user_login_type=session('user_login_type');
?>
<div class="container-fluid col-lg-10 right_mainbox" id="viewlistmaster">

<nav aria-label="breadcrumb">
<ol class="breadcrumb">
<li class="breadcrumb-item">Notification</li>
</ol>
</nav>
  <!-- <h4 class="viewdata"> Manage User </h4> -->
    <div class="row">
      <div class="col-12">
        <div class="card">
          <div class="card-header">
            <div class="row">
              <div class="col-md-6 ">
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
              @if($user_login_type == 'Vendor') 
              <div class="col-md-2 offset-4">
                <a href="{{ENV('APP_URL')}}view-notification"> <button type="button" class="btn btn-block btn-secondary">Back</button></a>
              </div>
              @else
              <div class="col-md-2 offset-4">
                <a href="{{ENV('APP_URL')}}view-notification-employee"> <button type="button" class="btn btn-block btn-secondary">Back</button></a>
              </div>
              @endif
            </div>
          </div>
          <div class="card-body">
            @foreach($view_notifiction as $viewnoti)  
            <b>Subject : </b> {{$viewnoti->subject}}
            <br><br>
            <b>Message : </b><br><p><br><?php echo htmlspecialchars_decode($viewnoti->message); ?></p>
            @endforeach
          </div>
          <!-- /.card-body -->
        </div>
        <!-- /.card -->

        <!-- /.card -->
      </div>
      <!-- /.col -->
    </div>
    <!-- /.row -->
  </div>
  <!-- /.container-fluid -->


@include('admin/footer')

@if($user_login_type == 'Vendor') 
<script>
  $(document).ready(function () {
  var element = document.getElementById("view_notification");
  element.classList.add("active");
  });
  </script>  
@else
<script>
  $(document).ready(function () {
  var element = document.getElementById("view_notification_employee");
  element.classList.add("active");
  });
  </script> 
@endif