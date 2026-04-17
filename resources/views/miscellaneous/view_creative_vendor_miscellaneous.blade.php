@include('admin/header')
@include('admin/side-menu') 


<?php
$user_type=session('login_type');
?>
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
        
        <div class="row">
        
        <div class="col-lg-4">
        <label for="document_type_id"><b>Campaign From :</b></label>
       <input type="date" placeholder="Campaign From" class="form-control  btnFiterSubmitSearchpage"  id="from_date" name="from_date" >
       <!--<label  class="fa fa-calendar input-icon"></label>--></div>
      <div class="col-lg-4">
        <label for="document_type_id"><b>Campaign To:</b></label>
       <input type="date" placeholder="Campaign To" class="form-control  btnFiterSubmitSearchpage"  id="to_date" name="to_date" >
      <!-- <label  class="fa fa-calendar input-icon"></label>-->
       <input type="hidden" name="advertisement_id" id="advertisement_id" value="">
       <input type="hidden" name="vendor_id" id="vendor_id" value="">
       <input type="hidden" name="archive_category_id" id="archive_category_id" value="">
       <input type="hidden" name="department_id" id="department_id" value="">

      </div>
       </div>
        <button class="btn btn-success search-form1-btn btnFiterSubmitSearchpage" type="text" id="btnFiterSubmitSearch">Search</button>
        <a class="btn btn-danger search-form1-btn btnFiterSubmitSearchpage" href="{{ENV('APP_URL')}}view-creative-vendor-miscellaneous" role="button">Cancel</a>
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
                <h4 class="viewdata"> Draft Miscellaneous Creatives </h4>
              </div>
            </div>
          </div>
          <div class="card-body">
            <table id='empTable' class="table table-striped table-hover table-responsive" width="100%">
              <thead>
                <tr>
                <th>Sr. No</th>
               <th>Creative File </th>
               <th>Advertisement ID</th>
               <th>File Name</th>
               <!--<th>Archive Category</th>-->
               <!-- <th>Brand</th> -->
               <th>Vendor</th>
               <!--<th>Department</th>-->
               <!-- <th>Document_type</th> -->
               <th>Campaign Month/Year</th>
               <th>Status</th>
               <th>Date Of Upload</th>
               <th>Source File </th>
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

  
   <script>
  $(document).ready(function () {
  var element = document.getElementById("manage_miscellaneous");
  document.getElementById("mastersmanage_miscellaneous").style.display = "block";

  element.classList.add("active");
  var element1 = document.getElementById("menu_miscellaneous");
  element1.classList.add("open_meunbox");
  });
  </script>  
  
  
  
    <!-- Script -->
  
  
  <script>
  $(document).ready(function () {
  var element = document.getElementById("manage_creative_miscellaneous_draft");
   if(element){
	   element.classList.add("active");
   } 
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
    //  $.noConflict(); 
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
      // url: "{{ route('creative_vendor_miscellaneous.getcreatives_vendor_miscellaneous') }}",
      url: "{{ENV('APP_URL')}}creative_vendor_miscellaneous/getcreatives_vendor_miscellaneous",
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
      { data: 'advertisement_id' },
      { data: 'file_name'},
    //   { data: 'archive_category_id'},
      // { data: 'brand_id'},
      { data: 'vendor_id'},
    //   { data: 'department_id'},
      // { data: 'document_type_id'},
      { data: 'date_of_posting'},
      { data: 'active_yn' },
      { data: 'created_date' },
      { data: 'source_file' },
      { data: 'action' },
      ]
      });

      });


      $('#btnFiterSubmitSearch').click(function(){
      $('#empTable').DataTable().draw(true);
      }); 
      </script>



<!--<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.4.1/jquery.js"  ></script>
<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.7.2/jquery-ui.min.js"></script>
<link rel="stylesheet" type="text/css" media="screen" href="https://ajax.googleapis.com/ajax/libs/jqueryui/1.7.2/themes/base/jquery-ui.css">-->

<script type="text/javascript"> 
$(function() {
$('.datepicker').datepicker( {
changeMonth: true,
changeYear: true,
showButtonPanel: true,
dateFormat: 'MM yy',
onSelect: function(dateText, inst) { 
    $(this).datepicker('setDate', new Date(inst.selectedYear, inst.selectedMonth, 1));
	return false;
}
});
});
</script>
  </body>
</html>