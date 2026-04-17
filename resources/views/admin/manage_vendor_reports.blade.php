@include('admin/header')
@include('admin/side-menu') 

<?php 
$user_type_download_creative=session('user_type_download_creative');
$user_download_creative=session('user_download_creative');
$user_type=session('login_type');

?>



<div class="container-fluid col-lg-10 right_mainbox" id="viewlistmaster">
   
<style>


select#department_id {
    background-color: white !important;
    border: 1px solid #b3b3b3 !important;
}


select#archive_category_id {
    background-color: white !important;
    border: 1px solid #b3b3b3 !important;
}


select#vendor_id {
    background-color: white !important;
    border: 1px solid #b3b3b3 !important;
}

        table {
            width: 100%;
            border-collapse: collapse;
            border: none;
            float: left;
            /* margin: -20px 0 0 0; */
        }
        
        thead tr th {
            background-color: #d8002a;
            color: #fff;
            text-align: center;
        }
        
        tbody th td {
            background-color: rgba(255, 255, 255, 0.2);
            color: #000;
            text-align: center;
            width: 200px;
        }
        
        table td {
            width: 200px;
        }
        
        i.fa.fa-caret-down {
            font-size: 30px;
        }
        
        .open_row {
            background-color: #eee;
            padding: 15px 10px;
            border: aliceblue;
            border-radius: 10px;
            cursor: pointer;
        }
        .open_row:hover{
            box-shadow: 0 0 40px #ebebeb;
        }
        a.btn.download {
            padding: 5px 10px;
            background-color: #d8002a;
            color: #fff;
            text-decoration: none !important;
        }
        
        a.btn.edit {
            padding: 6px 10px;
            background-color: #d8002a;
            color: #fff;
            text-decoration: none !important;
        }
        
        span.table-detail {
            font-weight: bold;
        }
        
        .adv {
            padding-top: 35px;
        }
        
        h3.table_heading {
            font-size: 14px;
            font-weight: 400;
        }
        
        .manage_col4 .manage_row1 {
            margin: 15px;
        }
        
        .manage_col4 .manage_row2 {
            margin: 15px;
        }
        
        .table {
            background-color: #fff !important;
        }
        
        .hedding h1 {
            color: #fff;
            font-size: 25px;
        }
        
        .main-section {
            margin-top: 120px;
        }
        
        .hiddenRow {
            padding: 0 4px !important;
            font-size: 13px;
        }
        
        .cell-1 {
            border-collapse: separate;
            border-spacing: 0 4em;
            background: #ffffff;
            border-bottom: 5px solid transparent;
            background-clip: padding-box;
            cursor: pointer
        }
        
        thead {
            background: #dddcdc
        }
        
        .table-elipse {
            cursor: pointer
        }
        
        #demo {
            -webkit-transition: all 0.3s ease-in-out;
            -moz-transition: all 0.3s ease-in-out;
            -o-transition: all 0.3s 0.1s ease-in-out;
            transition: all 0.3s ease-in-out
        }
        
        .row-child {
            background-color: #000;
            color: #fff
        }
        
        td[colspan] {
            background-color: gray;
        }
        
        td[gray] button {
            width: 100%;
        }
        
        .hide {
            display: none;
        }
        
        .show {
            display: table-row !important;
        }
        
        .btn-toggle {
            border: 0;
            background-color: transparent;
            cursor: pointer;
            font-size: 20px;
            outline: 0;
        }
        
        .btn-toggle[aria-expanded="true"] i {
            transform: rotate(180deg);
            -ms-transform: rotate(180deg);
            -webkit-transform: rotate(180deg);
        }
        
        .btn-toggle1 {
            border: 0;
            background-color: transparent;
            cursor: pointer;
            font-size: 20px;
            outline: 0;
        }
        
        .btn-toggle1[aria-expanded="true"] i {
            transform: rotate(180deg);
            -ms-transform: rotate(180deg);
            -webkit-transform: rotate(180deg);
        }
        
        .btn-toggle2 {
            border: 0;
            background-color: transparent;
            cursor: pointer;
            font-size: 20px;
            outline: 0;
        }
        
        .btn-toggle2[aria-expanded="true"] i {
            transform: rotate(180deg);
            -ms-transform: rotate(180deg);
            -webkit-transform: rotate(180deg);
        }
        
       
        
        .open_close_icon {
            width: 10%;
        }


        .search_filter_box{
            width:100%;
            height:auto;
        }
        .width100{
            width:100%;
            height:auto;
        }
        /*.search_filter_box input{*/
        /*    width:100%;*/
        /*}*/
        .search_filter_box select{
            width: 100%;
            border: 1px solid #CAC;
            padding: 6px;
            border-radius: 4px;
        }
        .ad_list_imagepopup_close{
            position: fixed;
            right: 0;
            top: 0;
            background-color: #ff220b;
            color: #fff;
            font-size: 24px;
            font-weight: normal;
            padding: 7px 20px 10px 20px;
            line-height: normal;
            margin: 10px 0px 0 0;
            border-radius: 6px 0px 0 6px;
        }
.ad_list_icon {
  float: left;
  width: auto;
  position: absolute;
  margin: 15px 0 0 -36px;
  box-shadow: 4px 3px 4px rgba(0, 0, 0, 0.2);
  border: 1px solid #d8042d;
  border-radius: 6px;
  padding: 6px;
  background-color: #fff;
}
.ad_list_icon img{
    float: left;
  width: 30px;
}
.ad_list_icon span{
  display: none;
  background-color: #d8002a;
  float: left;
  font-size: 9px;
  padding: 2px 8px 3px 8px;
  line-height: normal;
  border-radius: 30px;
  text-transform: uppercase;
  color: #fff;
  margin: -20px 0 0 -20px;
  position: absolute;
}
.ad_list_icon:hover span{
    display: block;
}
        

    </style>
    
    <style>
body {font-family: Arial;}

/* Style the tab */
.tab {
  overflow: hidden;
  /* border: 1px solid #ccc;
  background-color: #f1f1f1; */
}

/* Style the buttons inside the tab */
.tab button {
  background-color: inherit;
  float: left;
  border: none;
  outline: none;
  cursor: pointer;
  padding: 14px 16px;
  transition: 0.3s;
  font-size: 17px;
}

/* Change background color of buttons on hover */
.tab button:hover {
  background-color: #ddd;
}

/* Create an active/current tablink class */
.tab button.active {
  background-color: #ccc;
}

/* Style the tab content */
.tabcontent {
  display: none;
  padding: 6px 12px;
  border: 1px solid #ccc;
  border-top: none;
}
</style>

      <!--==START ADVANCE SEARCH TABLE== -->
      <div class="container">
        
        <div class="search_filter_box">
            <h4>Generate Report</h4>
            <div class="width100">
                <div class="row">
                
                    <div class="col-lg-8">
                    <form class="search" action="manage-vendor-reports" method="get" role="form" id="searchForm" >
                              {{ csrf_field() }}  
                              
                        <div class="row">
                        
                            
                            <div class="col-lg-12  mb-2">
                                <label for="from_date"> From Date :</label>
                                <input type="date" placeholder="Campaign From" id="from_date" name="from_date" required>
                            </div>
                            <div class="col-lg-12  mb-2">
                                 <label for="to_date"> To Date :</label>
                                <input type="date" placeholder="Campaign To"  name="to_date"   id="to_date"  required style="margin-left: 26px;"> 
                            </div>
                            <div class="col-lg-12 mb-3">
                                <button class="btn btn-success ml-2" type="submit" style="margin:0px !important">Search</button>
                                <a class="btn btn-danger ml-2"  style="margin:0px !important" href="{{ENV('APP_URL')}}manage-vendor-reports" role="button">Cancel</a>
                            </div>
                            
                        </div>
                        </form>
                    </div>
                    
                    <div class="col-lg-4"></div>
                </div>
            </div>
        </div>
        <br><br><br><br><br><br><br> 

        <div class="open_main">
            @if(!empty($report) )
                <div class="row">  
                     <div class="col-lg-12"> <label><h4> Vendor Name : {{$vendor_report_name}} </h4></lable></div>
                     <div class="col-lg-12"> 
                        <label>From Date : <?php echo date("F Y", strtotime($_GET['from_date'])) ;?></lable> | 
                        <label>To Date : <?php echo date("F Y", strtotime($_GET['to_date'])); ?></lable>
                     </div> 
                </div>   
                
                <div class="tab mb-3" style="border: 1px solid #ccc;background-color: #f1f1f1;">
                      <button class="tablinks active" id="active" onclick="openCity(event, 'All')">Summary </button>
                      <button class="tablinks" onclick="openCity(event, 'Department-wise')">Department Wise Count</button> 
                </div>
                
                <div id="All" class="tabcontent" style="display:block;" >
                    <table id='empTable' class="table table-striped table-hover" >
                      <thead>
                        <tr>
                            <th>Sr.No</th>
                            <th>Archive Category</th>
                            <th>Total Count</th>
                        </tr>
                      </thead>
                      <tbody>
                          <?php $total=0; ?>
                          @foreach($report as $reports)
                               <tr class="text-center">
                                    <td>{{ @$loop->iteration }}</td>
                                    <td>{{ @$reports->name }}</td>
                                    <td>{{ @$reports->total }}</td> <?php $total+=@$reports->total; ?>
                                </tr>
                          @endforeach
                      </tbody>  
                    </table> 
                    <div><hr> <h4> Total Sum : {{ $total }} </h4></div>
                </div>   
                
                <div id="Department-wise" class="tabcontent">
                     <button id="export" class="mb-2">Export</button>
                        <table id='empTable_report' class="table table-striped table-hover" >
                            <thead>
                              <tr>
                                  <th>Sr.No</th>
                                  <th> Department Name </th>
                                  <th>Archive Category</th>
                                  <th>Total Count</th>
                              </tr>
                           </thead>
                            @foreach($vendor_dept_wise as $reports)   
                            @if(!empty($reports[0]->deptname) )
                            <tbody>
                                <tr>
                                    <td class="text-left" colspan="1"><b> {{@$reports[0]->deptname}} </b> </td>
                                    <td> </td>  
                                    <td> </td> 
                                    <td> </td> 
                                </tr> 
                                <!--</thead>--> 
                                @foreach($reports as $rep)
                                 <tr class="text-center">
                                    <td>{{ @$loop->iteration }}</td>
                                    <td> {{ @$rep->deptname }}  </td>
                                    <td>{{ @$rep->name }}</td>
                                    <td>{{ @$rep->total }}</td> 
                                 </tr> 
                                @endforeach    
                            </tbody>  
                            @endif
                            @endforeach     
                        </table> 
                </div>    
                @endif    
          </div>
      <br>
     
</div>
</div>

<?php 
if(!empty($_GET['from_date'])) 
{
     $exctitle = 'Vendor : '.$vendor_report_name.' | From Date : '.date("d-m-Y", strtotime($_GET['from_date'])).' | To Date : '.date("d-m-Y", strtotime($_GET['to_date'])) ;
}else{
    $exctitle = "";
}
?> 
    <!--==END ADVANCE SEARCH TABLE== -->
  
  @include('admin/footer')
  
    <!-- Script -->
  
  <!-- LEFT-MENU-ACTIVE-CSS-START -->
  <script>
  $(document).ready(function () {
  });


  $(document).ready(function () {
  var element = document.getElementById("vendor_reports");
  element.classList.add("active");
  });
  
  
  $(document).ready(function() {
    $('#empTable').DataTable( {
        dom: 'Bfrtip',
        buttons: [
             {
                extend: 'excel',
                messageTop: '<?php echo $exctitle; ?>'
            },
        ]
    } );
  });
  
    $(document).ready(function() {
    $('#empTable_report').DataTable( {
        // dom: 'Bfrtip', 
        // buttons: [
        //      {
        //         extend: 'excel',
        //         messageTop: '<?php echo $exctitle; ?>',
        //           header: true
        //     },
        // ]
    } );
});
  
</script>

   <!-- LEFT-MENU-ACTIVE-CSS-START -->
  <script>
function openCity(evt, cityName) {
  var i, tabcontent, tablinks;
  tabcontent = document.getElementsByClassName("tabcontent");
  for (i = 0; i < tabcontent.length; i++) {
    tabcontent[i].style.display = "none";
  }
  tablinks = document.getElementsByClassName("tablinks");
  for (i = 0; i < tablinks.length; i++) {
    tablinks[i].className = tablinks[i].className.replace(" active", "");
  }
  document.getElementById(cityName).style.display = "block";
  evt.currentTarget.className += " active";
}
    
    setTimeout(() => {
  $("#active").addClass("active")
}, 500);     
</script>


  </body>

       <script src="https://cdn.rawgit.com/rainabba/jquery-table2excel/1.1.0/dist/jquery.table2excel.min.js"></script>
    <script>
        $(document).ready(function() {
        $('#export').on('click', function(e){ 
            $("#empTable_report").table2excel({
                exclude: ".noExport",
                name: "Data",
                filename: "Departmentwsie Report",
                });
           });
        });
    </script>
</html>