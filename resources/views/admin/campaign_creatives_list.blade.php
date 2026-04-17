@include('admin/header')
@include('admin/side-menu') 

  <div class="container-fluid col-lg-10 right_mainbox" id="viewlistmaster">



        <div class="card">
        <div class="card-header" role="tab" id="headingOne">
        <h4 class="viewdata">
        Search Campaign Creatives
        </h4>
        </div>

        <div id="collapseOne" class="collapse show" role="tabpanel" aria-labelledby="headingOne">
        <div class="card-body">

        
        <!--<form method="POST" action="{{ url('/export_data') }}" enctype="multipart/form-data" id="singlefileupload"> -->
        {{ csrf_field() }}
        <div class="row">
        <!-- <div class="col-lg-4">
        <label for="advertisement_id"><b>Advertisement Id :</b></label>
        <input type="text" class="form-control" placeholder="Advertisement Id" id="advertisement_id" name="advertisement_id">
        </div> -->
        
        <div class="col-lg-4">
        <label for="vendor_id"><b>Vendor Name :</b></label>
        <select class="form-control"  id="vendor_id" name="vendor_id">
          <option value="">--Select--</option>
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
        <button class="btn btn-success search-form1-btn"  id="btnFiterSubmitSearch" name="search">Search</button>
        <!--<input class="btn btn-success search-form1-btn" type="submit" id="btnFiterSubmitSearch1" value="Export All" name="Export">-->
        <a class="btn btn-danger search-form1-btn" href="{{ENV('APP_URL')}}campaign-creatives-list" role="button">Cancel</a>
        <!--</form>-->
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
              <div class="col-md-2 offset-4">
                <a href="{{ENV('APP_URL')}}add-campaign-creatives"> <button type="button" class="btn btn-block btn-secondary">Add</button></a>

              </div>
              <div class="col-lg-12">
                 <!--<a href="{{ENV('APP_URL')}}add_user"> <button type="button" class="btn btn-block btn-secondary">Add</button></a> -->
                <h4 class="viewdata"> Campaign Creatives List </h4>
              </div>
            </div>
          </div>
          <div class="card-body">
            <table id='empTable' class="table table-striped table-hover dataTable no-footer" width="100%">
            <thead>
            <tr>
            <th>Sr.No</th>
            <th>Campaign Name</th>
            <th>Vendor Name</th>
            <th>Campaign Date</th>
            <th>Campaign File</th>
            <th>Created Date</th>
            <th>Action</th>
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
  var element = document.getElementById("campaign_upload");
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
          // url: "{{ route('campaigncreatives.getcampaigncreatives') }}",
          url:"{{ENV('APP_URL')}}campaigncreatives/getcampaigncreatives",
          type: 'GET',
          data: function (d) {
          d.vendor_id = $('#vendor_id').val();
          d.campaign_name = $('#campaign_name').val();
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
            { data: 'campaign_name' },
            { data: 'vendor_id'},
            { data: 'campaign_date'},
            { data: 'campaign_file'},
            { data: 'created_date' },
            { data: 'action' }
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