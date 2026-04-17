@include('admin/header')
@include('admin/side-menu') 

  <div class="container-fluid col-lg-10 right_mainbox" id="viewlistmaster">



        <div class="card">
        <div class="card-header" role="tab" id="headingOne">
        <h4 class="viewdata">
        Search Creative
        </h4>
        </div>

        <div id="collapseOne" class="collapse show" role="tabpanel" aria-labelledby="headingOne">
        <div class="card-body">
        {{ csrf_field() }}
        <div class="form-row">
        <div class="col-lg-4">
        <label for="advertisement_id"><b>Advertisement Id :</b></label>
        <input type="text" class="form-control" placeholder="Advertisement Id" id="advertisement_id" name="advertisement_id">
        </div>
        <div class="col-lg-4">
        <label for="archive_category_id"><b>Archive Category :</b></label>
        <input type="text" class="form-control" placeholder="Archive Category" id="archive_category_id" name="archive_category_id">
        </div>
        <div class="col-lg-4">
        <label for="vendor_id"><b>Vendor :</b></label>
        <input type="text" class="form-control" placeholder="Vendor" id="vendor_id" name="vendor_id"> 
        </div>
        </div>
        <br>
        <div class="form-row">
        <div class="col-lg-4">
        <label for="department_id"><b>Department :</b></label>
        <input type="text" class="form-control" placeholder="Department" id="department_id" name="department_id">
        </div>
        <div class="col-lg-4">
        <label for="document_type_id"><b>Document Type :</b></label>
        <input type="text" class="form-control" placeholder="Document Type" id="document_type_id" name="document_type_id">
        </div>
       </div>
        <button class="btn btn-success search-form1-btn" type="text" id="btnFiterSubmitSearch">Submit</button>
        <a class="btn btn-danger search-form1-btn" href="{{ENV('APP_URL')}}view-creatives" role="button">Cancel</a>
        </div>
        </div>
        </div>

<br><br>
 
  
  
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
              <div class="col-lg-12">
                <!-- <a href="{{ENV('APP_URL')}}add_user"> <button type="button" class="btn btn-block btn-secondary">Add</button></a> -->
                <h4 class="viewdata"> Manage Creatives </h4>
              </div>
            </div>
          </div>
          <div class="card-body">
            <table id='empTable' class="table table-bordered table-hover table-responsive" width="100%">
              <thead>
                <tr>
                <th>Sr. No</th>
               <th>Creative_file </th>
               <th>Advertisement_id</th>
               <th>File_name</th>
               <th>Archive_category</th>
               <th>Category</th>
               <!-- <th>Brand</th> -->
               <th>Vendor</th>
               <th>Department</th>
               <th>Document_type</th>
               <th>Date_of_posting</th>
               <th>Uploaded_date </th>
               <th>Status</th>
               <th>Created_date</th>
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
  var element = document.getElementById("manage_creatives");
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



  var _token = jQuery('input[name="_token"]').val();
$(document).ready(function(){

$.ajaxSetup({
headers: {
'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
}
});
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
         //ajax: "{{route('creatives.getcreatives')}}",
         ajax: {
          // url: "{{ route('creatives_custom.getcreatives_custom') }}",
          url: "{{ENV('APP_URL')}}creatives_custom/getcreatives_custom",
          type: 'GET',
          data: function (d) {
          d.vendor_id = $('#vendor_id').val();
          d.advertisement_id = $('#advertisement_id').val();
          d.archive_category_id = $('#archive_category_id').val();
          d.department_id = $('#department_id').val();
          d.document_type_id = $('#document_type_id').val();
          }
         },
         
         "language": {
            "infoFiltered":"",
            "processing": "<img src='{{ENV('APP_URL')}}assets/images/loadingNew1.gif' style='width:13%' />"
        },
       
         columns: [
            { data: 'id' },
            { data: 'photo_url' },
            { data: 'advertisement_id' },
            { data: 'file_name'},
            { data: 'archive_category_id'},
            { data: 'category_id'},
            // { data: 'brand_id'},
            { data: 'vendor_id'},
            { data: 'department_id'},
            { data: 'document_type_id'},
            { data: 'date_of_posting'},
            { data: 'date_of_upload'},
            { data: 'active_yn' },
            { data: 'created_date' },
            { data: 'action' },
         ]
      });

    });


$('#btnFiterSubmitSearch').click(function(){
     $('#empTable').DataTable().draw(true);
}); 
    </script>
  </body>
</html>