@include('admin/header')
@include('admin/side-menu') 

<div class="container-fluid col-lg-10 right_mainbox" id="viewlistmaster">

<nav aria-label="breadcrumb">
<ol class="breadcrumb">
<li class="breadcrumb-item">View Notification</li>
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
            </div>
          </div>
          <div class="card-body">
            <table id='empTable' class="table table-striped table-hover" width="100%">
              <thead>
                <tr>
                <th>Sr. No</th>
                <th>Date</th>
                <th>Subject</th>
                <th>Read More</th>
                </tr>
              </thead>
               
              <tbody>
                  <?php $i=1;?>
              @foreach($notification as $notify)
              @if($notify->read_status == '0')
              <tr>
                  <td><b>{{$i}}</b></td>
                  <?php 
                  $newDate = date("d/m/Y", strtotime($notify->send_date));
                  ?>
                  <td><b>{{$newDate}}</b></td>
                  <td><b>{{$notify->subject}}</b></td>
                  <td><a href="{{ENV('APP_URL')}}view-notification-message/{{base64_encode($notify->id)}}">Read More</a></td>
              </tr>
              @else
              <tr>
                  <td>{{$i}}</td>
                  <?php 
                  $newDate = date("d/m/Y", strtotime($notify->send_date));
                  ?>
                  <td>{{$newDate}}</td>
                  <td>{{$notify->subject}}</td>
                  <td><a href="{{ENV('APP_URL')}}view-notification-message/{{base64_encode($notify->id)}}">Read More</a></td>
              </tr>
              @endif
              <?php $i++;?>
              @endforeach        
              </tbody>    
        
            </table>
            {!! $notification->links() !!}
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


<script>
  $(document).ready(function () {
  var element = document.getElementById("view_notification_employee");
  element.classList.add("active");
  });
  </script>  
