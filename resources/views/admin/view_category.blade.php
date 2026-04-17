@include('admin/header')
@include('admin/side-menu') 


   


  <div class="container-fluid col-lg-10 right_mainbox" id="viewlistmaster">
  <h4 class="viewdata"> Manage Category </h4>
    <div class="row">
      <div class="col-12">
        <div class="card">
          <div class="card-header">
            <div class="row">
              <div class="col-lg-12 ">
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
              <!-- <div class="col-md-1 offset-5">
                <a href="{{ENV('APP_URL')}}add-category"> <button type="button" class="btn btn-block btn-secondary">Add</button></a>

              </div> -->
            </div>
            <form method="POST" action="{{ url('/insert_category') }}">
            {{ csrf_field() }}
            <div class="row">
              <div class="col-lg-12 ml-4">
              <lable class="font-weight-bolder pr-2 add-lable">Name</lable> <input type="text" id="name" name="name" placeholder='Name' required>
              <lable class="font-weight-bolder pr-2 add-lable">Description</lable> <input type="text" id="description" name="description" placeholder='Description'>
  
              <button class="btn btn-success ml-2" type="submit">Submit</button>
                <a class="btn btn-danger ml-2" href="#" role="button">Cancel</a>
              </div>
            </div>
          </form>
          </div>
          <div class="card-body">
            <table id='empTable' class="table table-bordered table-hover" width="100%">
              <thead>
                <tr>
                <th>Sr. No</th>
               <th>Name</th>
               <th>Description</th>
               <th>Status</th>
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
  var element = document.getElementById("category");
  element.classList.add("active");
  document.getElementById("masters").style.display = "block";
  document.getElementById("fields").style.display = "block";
  var element1 = document.getElementById("menu");
  element1.classList.add("open_meunbox");
  });
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
                    'pdf',
                    'print'
                ]
            }
        ],
      
         ajax: "ute('category.getcategory')}}",
         "language": {
            "infoFiltered":"",
            "processing": "<img src='{{ENV('APP_URL')}}assets/images/loadingNew1.gif' style='width:13%' />"
        },
       
         columns: [
            { data: 'id' },
            { data: 'name' },
            { data: 'description'},
            { data: 'active_yn' },
            { data: 'created_date' },
            { data: 'action' },
         ]
      });

    });
    </script>
  </body>
</html>