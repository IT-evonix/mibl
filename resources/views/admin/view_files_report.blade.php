@include('admin/header')
@include('admin/side-menu') 

<?php
$user_type=session('login_type');
?>

  <div class="container-fluid col-lg-10 right_mainbox" id="viewlistmaster">

<br><br>
 
  
  
    <div class="row">
      <div class="col-12">
        <div class="card">
          <div class="card-header">
            <div class="row">
              <div class="col-md-12">
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
              <div class="col-lg-12">
                <!-- <a href="{{ENV('APP_URL')}}add_user"> <button type="button" class="btn btn-block btn-secondary">Add</button></a> -->
                <h4 class="viewdata"> Share Link Report </h4>
              </div>
            </div>
          </div>
          <div class="card-body">
            <table id='empTable' class="table table-striped table-hover" width="100%">
            <thead>
            <tr>
            <th>Sr. No</th>
            <th>Advertisement Id</th>
            <th>File Name</th>
            <th>Link Visit Count</th>
            <th>Download Count</th>
            </tr>
            </thead>
            <tbody>
                <?php $i=1;?>
                @foreach($data as $dts)
                <tr>
                    <td>{{$i}}</td>
                    <td>{{$dts['advertisement_id']}}</td>
                    <td>{{$dts['file_name']}}</td>
                    <td>{{$dts['share_link_open']}}</td>
                    <td>{{$dts['download_file_link']}}</td>
                </tr>
                <?php $i++;?>    
                @endforeach
            </tbody>
        
            </table>
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

  
    <!-- Script -->
  
  
<script>
$(document).ready(function () {
var element = document.getElementById("manage_reports_files");
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
   
   
    $(document).ready(function() {
    $('#empTable').DataTable( {
        dom: 'Bfrtip',
        buttons: [
             {
                extend: 'excel',
            },
        ]
    } );
  });
 </script>
  </body>
</html>
