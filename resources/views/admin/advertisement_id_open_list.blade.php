@include('admin/header')
@include('admin/side-menu') 

  <div class="container-fluid col-lg-10 right_mainbox" id="viewlistmaster">



        <div class="card">
        <div class="card-header" role="tab" id="headingOne">
        <h4 class="viewdata">
        Search Open Advertisement Id
        </h4>
        </div>

        <div id="collapseOne" class="collapse show" role="tabpanel" aria-labelledby="headingOne">
        <div class="card-body">
        {{ csrf_field() }}
        
        
        <form method="POST" action="{{ url('/export_data_open') }}" enctype="multipart/form-data" id="singlefileupload"> 
        {{ csrf_field() }}
        <div class="row">
        
        <div class="col-lg-4">
        <label for="vendor_id"><b>Vendor Name :</b></label>
        <select class="form-control"  id="vendor_id" name="vendor_id">
          <option value="">-- Select --</option>
          <?php
          for($i=0;$i<count($vendor_c); $i++)
          { 
          $sub_vendor=$vendor_c[$i]['vendor_list'];
          ?>
          <optgroup label="{{$vendor_c[$i]['vendor_type_name']}}">
          <?php for($j=0;$j<count($sub_vendor); $j++){ ?>
          <option value="{{$vendor_c[$i]['vendor_type_id']}},{{$sub_vendor[$j]['vendor_id']}}">{{$sub_vendor[$j]['vendor_name']}}</option>
          <?php } ?> 
          </optgroup>
          <?php } ?>
        </select>
        </div>

        <?php 
        $start_date = date("Y-m", strtotime("-3 years"));
        $end_date = date('Y-m');
         ?>
        <div class="col-lg-4">
        <label for="document_type_id"><b>Created From :</b></label>
       <input type="date" class="form-control"  id="from_date" name="from_date" >
      </div>
      <div class="col-lg-4">
        <label for="document_type_id"><b>Created To :</b></label>
       <input type="date" class="form-control"  id="to_date" name="to_date"  >
      </div>
       </div>
       
        <input class="btn btn-success search-form1-btn" type="submit" id="btnFiterSubmitSearch1" value="Export All" name="Export">
        <a class="btn btn-danger search-form1-btn" href="{{ENV('APP_URL')}}advertisement-id-open-list" role="button">Cancel</a>
        </form>
         <button class="btn btn-success search-form1-btn"  id="btnFiterSubmitSearch" name="search">Search</button>
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
                <h4 class="viewdata"> Open Advertisement Id List </h4>
              </div>
            </div>
          </div>
          <div class="card-body">
            <table id='empTable' class="table table-striped table-hover table-responsive" width="100%">
              <thead>
                <tr>
               <th>Sr.No</th>
               <th>Advertisement ID</th>
               <th>Vendor</th>
               <th>Department</th>
               <th>Archive Category</th>
               <th>Language</th>
               <th>Created Date</th>
               <th>Status</th>
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
  var element = document.getElementById("open_advertisement_id_list");
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
                    
                ]
            }
        ],
         //ajax: "{{route('creatives.getcreatives')}}",
         ajax: {
          // url: "{{ route('advertisementidopenlist.getadvertisement_id_open_list') }}",
          url:"{{ENV('APP_URL')}}advertisementidopenlist/getadvertisement_id_open_list",
          type: 'GET',
          data: function (d) {

          d.status = $('#status').val();
          d.vendor_id = $('#vendor_id').val();
          d.advertisement_id = $('#advertisement_id').val();
          d.archive_category_id = $('#archive_category_id').val();
          d.department_id = $('#department_id').val();
          d.from_date = $('#from_date').val();
          d.to_date = $('#to_date').val();
          }
         },
         
         "language": {
            "infoFiltered":"",
            "processing": "<img src='{{ENV('APP_URL')}}assets/images/loadingNew1.gif' style='width:13%' />"
        },
       
         columns: [
            { data: 'id' },
            { data: 'advertisement_id' },
            { data: 'vendor_id'},
            { data: 'department_id'},
            { data: 'archive_category_id'},
            { data: 'language_name'},
            { data: 'created_date' },
            { data: 'active_yn' },
         ]
      });

    });


$('#btnFiterSubmitSearch').click(function(){
     $('#empTable').DataTable().draw(true);
}); 


$('#btnFiterSubmitSearch1').click(function(){
   empTable();  
}); 

//Export All data

  //DataTable
function empTable()
{
var _token = jQuery('input[name="_token"]').val();
var status = $('#status').val();
var vendor_id = $('#vendor_id').val();
var advertisement_id = $('#advertisement_id').val();
var archive_category_id = $('#archive_category_id').val();
var department_id = $('#department_id').val();
var from_date = $('#from_date').val();
var to_date = $('#to_date').val();

jQuery.ajax({
url: "{{ENV('APP_URL')}}export_data",
method: "POST",
data: {status:status,vendor_id:vendor_id,advertisement_id:advertisement_id,archive_category_id:archive_category_id,department_id:department_id,from_date:from_date,to_date:to_date,_token: _token},
success: function(result) {
// jQuery('#department_id').html(result);
}
})
}


</script>
  </body>
</html>