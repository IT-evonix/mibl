@include('admin/header')
@include('admin/side-menu')

<meta name="csrf_token" content="{{csrf_token()}}">
<style>
/* input#advertisement_id {
text-transform: uppercase;
} */
.punctuation {
position: absolute;
color: #fff;
font-size: 10px;
left: 144px;
top: 27px;
padding: 0px 4px;
border-radius: 21px;
background-color: #D8002A;
transform: translate(-50%, -50%);
/* -webkit-user-select: none; */
user-select: none;
cursor: default;
}
#loading { display: none; }


.tooltip {
  position: relative;
  display: inline-block;
  border-bottom: 1px dotted black;
}

.tooltip .tooltiptext {
  visibility: hidden;
  width: 120px;
  background-color: black;
  color: #fff;
  text-align: center;
  border-radius: 6px;
  padding: 5px 0;

  /* Position the tooltip */
  position: absolute;
  z-index: 1;
}

.tooltip:hover .tooltiptext {
  visibility: visible;
}


</style>

<div class="col-lg-6 form">
<h4 class="singlefileupload"> Generate Adaptation Advertisement Id </h4>

   <div class="row">
      <div class="col-md-12">
        @if(Session::has('successmsg'))
		<div class="alert alert-success alert-dismissible"  id="success_message" role="alert">
		  <button type="button" class="btn-close" data-bs-dismiss="alert" >
			</button>
		  <h3 class="text-success"><i class="fa fa-check-circle"></i>Success</h3>
		  {!! Session::get('successmsg') !!}
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
   <!-- START FORM -->
   <div class="sign-in-up-form">
      <div class="tab-content">
         <div id="login">
            <div class="wrapper">
               <div class="container">
                  <form method="POST" action="{{ url('/insert_generate_advertisement_id_adaptation') }}" enctype="multipart/form-data" id="singlefileupload"> 
                     {{ csrf_field() }}


                    
                     <div class="row">
                        <div class="form-group col-lg-12">
                        <label for="department_type_id">Department</label>
                           <select id="department_type_id" name="department_type_id" class="form-control" required>
                           <option value="">Select Department</option>
                           <?php
                           for($i=0;$i<count($department_c); $i++)
                           { 
                           $sub_depart=$department_c[$i]['department_list'];
                           ?>
                           <optgroup label="{{$department_c[$i]['department_type_name']}}">
                           <?php for($j=0;$j<count($sub_depart); $j++){ ?>
                           <option value="{{$department_c[$i]['department_type_id']}},{{$sub_depart[$j]['department_id']}}">{{$sub_depart[$j]['department_name']}}</option>
                           <?php } ?> 
                           </optgroup>
                           <?php } ?>

                           </select>
                           
                        </div>    
                     </div> 

                     <div class="row">
                     <div class="form-group col-lg-12">
                     <label for="archive_category_id">Archive Category</label>
                        <select id="archive_category_id" name="archive_category_id" class="form-control"  required>
                        <option value="">Select Archive Category</option>

                           <?php
                           for($i=0;$i<count($archive_c); $i++)
                           { $sub_ca=$archive_c[$i]['sub_list'];
                           ?>
                        <optgroup label="{{$archive_c[$i]['archive_category']}}">
                          <?php for($j=0;$j<count($sub_ca); $j++){ ?>
                        <option value="{{$archive_c[$i]['archive_category_id']}},{{$sub_ca[$j]['sub_category_id']}}">{{$sub_ca[$j]['sub_category']}}</option>
                        
                        <?php } ?> 
                        </optgroup>
                        <?php } ?>
                        </select>
                        </div>
                        <div class="form-group col-lg-12">
                        <label for="language_id">Language</label>
                           <select id="language_id" name="language_id" class="form-control" required>
                              <option value="">Select Language</option>
                              @foreach($languages as $languag)
                              <option value="{{$languag->id}}">{{$languag->language}}</option>
                              @endforeach
                           </select>
                        </div>
                        
                        <div class="form-group col-lg-12">
                        <label for="type">Type</label>
                           <select id="type" name="type" class="form-control" required>
                              <option value="">Select Type</option>
                              <option value="internal">Internal</option>
                              <option value="external">External</option>
                           </select>
                        </div>
                        
                     </div>
                     <button class="btn upload-btn" type="submit">Generate</button>
                  </form>
               </div>
            </div>
         </div>
         <!-- END FORM -->
      </div>
   </div>
</div>
<div class="col-md-3">
<h4 class="singlefileupload"> Open Adaptation Advertisement Id </h4>
   <div class="form-group">
      <table id='empTable' class="table table-striped table-hover" width="100%" style="font-size:14px;font-family: inherit;">
      <thead>
      <tr>
      <!-- <th>Sr. No</th> -->
      <th>Advertisement Id</th>
      <!-- <th>Created Date</th> -->
      </tr>
      </thead>
      <tbody><?php $i=1;?>
         @foreach($advertisement_ids as $advertisementid)
         <?php $created_date = date('d/m/Y', strtotime($advertisementid->created_date));?>
         <tr>
            <!-- <td>{{$i}}</td> -->
            <td>{{$advertisementid->advertisement_id}}</td>
            <!-- <td>{{$created_date}}</td> -->
         </tr> 
         <?php $i++ ?>
         @endforeach  
      </tbody>
      </table>
      {{-- Pagination --}}
        <div class="d-flex justify-content-center">
            {!! $advertisement_ids->links() !!}
        </div>
    </div>
   </div> 


</div>


<!-- End tab -->
<!--<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>-->

@include('admin/footer')

<script>
   $(document).ready(function () {
    var element = document.getElementById("generate_advertisement_id_adaptation");
    element.classList.add("active");
    document.getElementById("mastersmanage_adaptation").style.display = "block";
    var element1 = document.getElementById("menu_adaptation");
    element1.classList.add("open_meunbox");

   });
</script>

<script>
   $(document).ready(function () {
   var element = document.getElementById("generate_advertisement_id_adaptation");
   element.classList.add("active");
   });
</script>

</body>
</html>