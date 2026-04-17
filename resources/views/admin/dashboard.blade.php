@include('admin/header')
@include('admin/side-menu') 

<style>
.card-body
{
background-color: #d8002a !important;
color: #ffff;    
}
.card-header
{
border-bottom: 1px solid #fff;
}
.tabcontent {
display: none;
}

.tab1 a{
background-color: #D8002A;
color: #FFF;
float: left;
outline: none;
cursor: pointer;
padding: 14px 16px;
transition: 0.3s;
font-size: 17px;
border: 1px solid #ffeaea;
margin: 0 20px 10px 0;
width: 100%;
border-radius:10px;
}

.page-item.active span, .page-item.disabled span{
background-color: #dcdcdc;
color: #FFF;
float: left;
outline: none;
padding: 14px 16px;
transition: 0.3s;
font-size: 17px;
border: 1px solid #efefef;
margin: 0 20px 10px 0;
width: 100%;
border-radius:10px;
}

.tab1 a:hover {
box-shadow: -1px 1px 20px 3px #acacac;
transform: translateY(-7px);
color: #D8002A;
background-color: #FFF;
border: 1px solid #D8002A;
}
</style>


<?php 
$user_type_download_creative=session('user_type_download_creative');
$user_download_creative=session('user_download_creative');
$user_type=session('login_type');
?>

        <div class="col-lg-10 right_mainbox"><!-- col-lg-10 start-->
            <h3><b>Dashboard</b></h3>
            <div class="col-lg-12">
                <div class="tab_list mt-4 mb-4">
                    <div class="tab1">
                        <h4>Approved Creatives</h4>
                        <hr>
                        
                    <div class="row">
                            
                        <div class="col-lg-3">
                        <div class="card mb-3">
                        <div class="card-header">
                        Today Creatives
                        </div>
                        <div class="card-body">
                         <?php echo count($today_approved);?>
                        </div>
                        </div>
                        </div>
                        
                        <div class="col-lg-3">
                        <div class="card mb-3">
                        <div class="card-header">
                        Current Week Creatives
                        </div>
                        <div class="card-body">
                         <?php echo count($week_approved);?>
                        </div>
                        </div>
                        </div>
                        
                        <div class="col-lg-3">
                        <div class="card mb-3">
                        <div class="card-header">
                        Current Month Creatives
                        </div>
                        <div class="card-body">
                         <?php echo count($month_approved);?>
                        </div>
                        </div>
                        </div>
                        
                        <div class="col-lg-3">
                        <div class="card mb-3">
                        <div class="card-header">
                        Current Year Creatives
                        </div>
                        <div class="card-body">
                         <?php echo count($year_approved);?>
                        </div>
                        </div>
                        </div>
                    </div>    
                    
                    <h4>Pending Creatives</h4>
                        <hr>
                    <div class="row">
                        <div class="col-lg-3">
                        <div class="card mb-3">
                        <div class="card-header">
                        Today Creatives
                        </div>
                        <div class="card-body">
                         <?php echo count($today_pending);?>
                        </div>
                        </div>
                        </div>
                        
                        <div class="col-lg-3">
                        <div class="card mb-3">
                        <div class="card-header">
                        Current Week Creatives
                        </div>
                        <div class="card-body">
                         <?php echo count($week_pending);?>
                        </div>
                        </div>
                        </div>
                        
                        <div class="col-lg-3">
                        <div class="card mb-3">
                        <div class="card-header">
                        Current Month Creatives
                        </div>
                        <div class="card-body">
                         <?php echo count($month_pending);?>
                        </div>
                        </div>
                        </div>
                        
                        <div class="col-lg-3">
                        <div class="card mb-3">
                        <div class="card-header">
                        Current Year Creatives
                        </div>
                        <div class="card-body">
                         <?php echo count($year_pending);?>
                        </div>
                        </div>
                        </div>
                    </div>
                    
                    
    <nav aria-label="breadcrumb">
    <ol class="breadcrumb">
    <li class="breadcrumb-item">New Creatives Notification</li>
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
          <!--<div class="card-body1">-->
            <table id='empTable' class="table table-striped table-hover" width="100%">
              <thead>
                <tr>
                <th>Sr. No</th>
                <th>Advertisement Id</th>
                <th>Vendor Name</th>
                <th>Close Notification</th>
                </tr>
              </thead>
               
              <tbody>
                  <?php $i=1;?>
              @foreach($creatives_vendor_details as $notify)
                <tr>
                <td><b>{{$i}}</b></td>
                <td><b>{{$notify->advertisement_id}}</b></td>
                <td><b>{{$notify->name}}</b></td>
                <td>
                <form  id="form" method="post" class="forms-sample" action="{{route('closedcreative')}}" enctype="multipart/form-data">
                {{csrf_field()}}
                <input type="hidden" name="id" value="{{$notify->id}}">
                <button type="submit" class="btn btn-success">Close</button>
                </form>
                </td>
                </tr>
              <?php $i++;?>
              @endforeach        
              </tbody>    
        
            </table>
            {!! $creatives_vendor_details->links() !!}
          <!--</div>-->
          <!-- /.card-body -->
        </div>
        <!-- /.card -->

        <!-- /.card -->
      </div>
      <!-- /.col -->
    </div>
    <!-- /.row -->
                    
                    
                    
                    
                
        
        </div> 
        </div> 
        </div> 
        </div> 
        
        
        
       
        
               

@include('admin/footer')

<script>
$(document).ready(function () {
var element = document.getElementById("dashboard");
element.classList.add("active");
});
</script>  
     
</body>
</html>



    
