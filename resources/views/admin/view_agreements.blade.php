@include('admin/header')
@include('admin/side-menu') 

<?php
$user_type=session('login_type');
?>

  <div class="container-fluid col-lg-10 right_mainbox" id="viewlistmaster">



        <div class="card">
        <div class="card-header" role="tab" id="headingOne">
        <h4 class="viewdata">
        Search Agreement
        </h4>
        </div>

        <div id="collapseOne" class="collapse show" role="tabpanel" aria-labelledby="headingOne">
        <div class="card-body">
        {{ csrf_field() }}
        <div class="row">
        <div class="col-lg-4">
        <label for="vendor_id"><b>Vendor Name:</b></label>
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
        $start_date = date("Y-m-d", strtotime("-3 years"));
        $end_date = date('Y-m-d');
         ?>
        <div class="col-lg-4">
        <label for="document_type_id"><b>Created From :</b></label>
       <input type="date" class="form-control calender_icon"  id="from_date" name="from_date">
      </div>
      <div class="col-lg-4">
        <label for="document_type_id"><b>Created To :</b></label>
       <input type="date" class="form-control calender_icon"  id="to_date" name="to_date">
      </div>
       </div>
        <button class="btn btn-success search-form1-btn" type="text" id="btnFiterSubmitSearch">Search</button>
        <a class="btn btn-danger search-form1-btn" href="{{ENV('APP_URL')}}view-agreements" role="button">Cancel</a>
        </div>
        </div>
        </div>

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
                <h4 class="viewdata"> Manage Agreement </h4>
              </div>
            </div>
          </div>
          <div class="card-body">
            <table id='empTable' class="table table-striped table-hover table-responsive" width="100%">
              <thead>
                <tr>
                <th>Sr. No</th>
               <th>Creative File </th>
               <th>File Name</th>
               <th>Brand</th>
               <th>Vendor</th>
               <!-- <th>Document Type</th> -->
               <th>Contract Start date</th>
               <th>Contract End date</th>
               <th>Status</th>
               <th>Created Date</th>
               @if($user_type == 'Super Admin')
               <th class="sorting_disabled">Action</th>
               @endif
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
  var element = document.getElementById("manage_agreement");
  document.getElementById("mastersmanage_agreement").style.display = "block";

  element.classList.add("active");
  var element1 = document.getElementById("menu_agreement");
  element1.classList.add("open_meunbox");
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
          // url: "{{ route('agreements.getagreements') }}",
          url: "{{ENV('APP_URL')}}agreements/getagreements",
          type: 'GET',
          data: function (d) {
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
            { data: 'photo_url' },
            { data: 'file_name'},
            { data: 'brand_id'},
            { data: 'vendor_id'},
            // { data: 'document_type_id'},
            { data: 'contract_start_date'},
            { data: 'contract_end_date'},
            { data: 'active_yn' },
            { data: 'created_date' },
            <?php if($user_type == 'Super Admin'){ ?>
            { data: 'action' },
            <?php } ?>
         ]
      });

    });


$('#btnFiterSubmitSearch').click(function(){
     $('#empTable').DataTable().draw(true);
}); 
    </script>
  </body>
</html>