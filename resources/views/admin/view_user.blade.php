@include('admin/header')
@include('admin/side-menu') 

  <div class="container-fluid col-lg-10 right_mainbox" id="viewlistmaster">

<nav aria-label="breadcrumb">
<ol class="breadcrumb">
<li class="breadcrumb-item">Manage User</li>
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
            <div class="col-md-2 offset-4">
                <a href="{{ENV('APP_URL')}}add-user"> <button type="button" class="btn btn-block btn-secondary">Add</button></a>

              </div>
            </div>
          </div>
          <div class="card-body">
            <table id='empTable' class="table table-striped table-hover table-responsive" width="100%">
              <thead>
                <tr>
                <th>Sr. No</th>
               <th>Roles</th>
               <th>SAP Code</th>
               <th>Name</th>
               <th>Email</th>
               <!-- <th>Pan No</th> -->
               <th>Mobile No</th>
               <!-- <th>Address</th> -->
               <th>Status</th>
               <th>Last Login </th>
               <th>Created Date</th>
               <th class="sorting_disabled">Action</th>
                </tr>
              </thead>
        
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



  <script type="text/javascript">
    $(document).ready(function(){

      // DataTable
      $('#empTable').DataTable({
        dom: 'lfBrtip',
         processing: true,
         serverSide: true,
         "order": [[ 0, "desc" ]],
         "aoColumnDefs" : [ {
         "bSortable" : false,
         "aTargets" : [ "sorting_disabled" ]
          } ],
         buttons: [
            {
                extend: 'collection',
                text: 'Export',
                buttons: [
                    'copy',
                    'excel',
                    'csv',
                    
                ]
            }
        ],
      
        //  ajax: "{{route('user.getuser')}}",
        ajax: "{{ENV('APP_URL')}}user/getuser",
         "language": {
            "infoFiltered":"",
            "processing": "<img src='{{ENV('APP_URL')}}assets/images/loadingNew1.gif' style='width:13%' />"
        },
       
         columns: [
            { data: 'id' },
            { data: 'user_type' },
            { data: 'sap_code' },
            { data: 'name' },
            { data: 'email'},
            // { data: 'pan_no'},
            { data: 'mobile_no'},
            // { data: 'address'},
            { data: 'active_yn' },
            { data: 'last_login_date' },
            { data: 'created_date' },
            { data: 'action' },
         ]
      });

    });
    </script>
  </body>
</html>