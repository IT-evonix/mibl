<?php
namespace App\Http\Controllers;
use App\Http\Requests;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Contracts\Filesystem\Filesystem;
use Illuminate\Pagination\Paginator;
use JianJye\CFStream\CFStreamLaravel;
use Google\Cloud\Vision\VisionClient;
use Intervention\Image\ImageManagerStatic as Image;
use Redirect;
use Validator;
use Session;
use Mail;
use File;
use DateTime;
use Imagick;
use ZipArchive;
use App\Brand;
use App\User_type;
use App\Category;
use App\Department;
use App\Document_type;
use App\Vendor;
use App\User;
use App\Vendor_type;
use App\Department_type;
use App\Archive_Category;
use App\Creatives;
use App\Language;
use App\Agreement;
use App\Agreement_detail;
use App\Archive_sub_category;
use App\Creative_vendor;
use App\Advertisementid_model;

class AdvertisementController extends Controller
{

public function advertisement_id_open_list(Request $request)
{
//Archive category type and Archive sub category 

$archive_category = DB::table('tbl_mibl_master_archive_category')
->select('*')
->where('active_yn',0)
->orderBy('name','ASC')
->get();
$archive_c=array();
foreach($archive_category as $archivecategory)
{
$sub_cat=array();
$data = DB::table('tbl_mibl_master_archive_sub_category')
 ->where('archive_category_id',$archivecategory->id)
 ->where('active_yn',0)->orderBy('name', 'ASC')->get();
 if(count($data) > 0){
 foreach($data as $dat)
 {
   $sub_cat[]=array(
    'sub_category_id'=>$dat->id,
      'sub_category'=>$dat->name
   );
 }
}else
{
  $sub_cat[]=array(
    'sub_category_id'=>0,
      'sub_category'=>$archivecategory->name
   );
}

$archive_c[]=array(
  'archive_category_id'=>$archivecategory->id,
  'archive_category'=>$archivecategory->name,
  'sub_list'=>$sub_cat
);

}

//department type and department 
$department = DB::table('tbl_mibl_master_department_type')
->select('*')
->where('active_yn',0)
->orderBy('department_type_name','ASC')
->get();
$department_c=array();
foreach($department as $department_type)
{
$sub_depart=array();
$data = DB::table('tbl_mibl_master_department')
 ->where('department_type_id',$department_type->id)
 ->where('active_yn',0)->orderBy('name', 'ASC')->get();
 if(count($data) > 0){
 foreach($data as $dat)
 {
   $sub_depart[]=array(
      'department_id'=>$dat->id,
      'department_name'=>$dat->name
   );
 }
}
else
{
  $sub_depart[]=array(
    'department_id'=>0,
    'department_name'=>$department_type->department_type_name
   );
}

$department_c[]=array(
  'department_type_id'=>$department_type->id,
  'department_type_name'=>$department_type->department_type_name,
  'department_list'=>$sub_depart
);


}

//Vendor type and vendor 

$vendor = DB::table('tbl_mibl_master_vendor_type')
->select('*')
->where('active_yn',0)
->orderBy('vendor_type_name','ASC')
->get();


$vendor_c=array();
foreach($vendor as $vendor_type)
{
$sub_vendor=array();
$data = DB::table('tbl_mibl_master_vendor')
   ->where('vendor_type_id',$vendor_type->id)
   ->where('active_yn',0)
   ->where('flag',1)->orderBy('name', 'ASC')->get();
 if(count($data) > 0){
 foreach($data as $dat)
 {
   $sub_vendor[]=array(
      'vendor_id'=>$dat->id,
      'vendor_name'=>$dat->name
   );
 }
}
else
{
  $sub_vendor[]=array(
    'vendor_id'=>0,
    'vendor_name'=>$vendor_type->vendor_type_name
   );
}

$vendor_c[]=array(
  'vendor_type_id'=>$vendor_type->id,
  'vendor_type_name'=>$vendor_type->vendor_type_name,
  'vendor_list'=>$sub_vendor
);

}



return view('/admin/advertisement_id_open_list',
['archive_c'=>$archive_c,
 'department_c'=>$department_c,
 'vendor_c'=>$vendor_c]);
}


public function getadvertisement_id_open_list(Request $request){


  //custom search 
  
  $vendor_name = trim((!empty($_GET["vendor_id"])) ? ($_GET["vendor_id"]) : (''));
  $advertisement_id = trim((!empty($_GET["advertisement_id"])) ? ($_GET["advertisement_id"]) : (''));
  $archive_category_id = trim((!empty($_GET["archive_category_id"])) ? ($_GET["archive_category_id"]) : (''));
  $department_id = trim((!empty($_GET["department_id"])) ? ($_GET["department_id"]) : (''));
  $from_date = (!empty($_GET["from_date"])) ? ($_GET["from_date"]) : ('');
  $to_date = (!empty($_GET["to_date"])) ? ($_GET["to_date"]) : ('');
  $status1 = trim((!empty($_GET["status"])) ? ($_GET["status"]) : (''));

  
    ## Read value
    $draw = $request->get('draw');
    $start = $request->get("start");
    $rowperpage = $request->get("length"); // Rows display per page
  
    $columnIndex_arr = $request->get('order');
    $columnName_arr = $request->get('columns');
    $order_arr = $request->get('order');
    $search_arr = $request->get('search');
  
    $columnIndex = $columnIndex_arr[0]['column']; // Column index
    $columnName = $columnName_arr[$columnIndex]['data']; // Column name
    $columnSortOrder = $order_arr[0]['dir']; // asc or desc
    @$searchValue = trim($search_arr['value']); // Search value
  
    @$searchValue1 = trim($search_arr['value']); // Search value
    
    if(Str::upper(@$searchValue1) == 'OPEN')
    {
    $status='0';
    }else if(Str::upper(@$searchValue1) == 'PENDING')
    {
     $status='1';
    }
    else if(Str::upper(@$searchValue1) == 'APPROVED')
    {
     $status='2';
    }
    else{
        $status=$searchValue1; 
    }

        //date search

        if(!empty($searchValue)){
          $var =$searchValue;
          $date = str_replace('/', '-', $var);
          $created_date=date('Y-m-d', strtotime($date));
          if (strtotime($created_date)) {
          $created_dated=$created_date;
          }
          else {
          $created_dated=$searchValue;
          }
          }else
          {
          $created_dated=$searchValue;
          }



  if(!empty($searchValue1))
  {
    $end_date = date('Y-m-d');
    $start_date = date("Y-m-d", strtotime("-3 years"));
  // Total records
    $totalRecords = Advertisementid_model::select('count(*) as allcount')->count();
    $totalRecordswithFilter = Advertisementid_model::select('count(*) as allcount')
  ->leftJoin('tbl_mibl_master_archive_category', 'tbl_mibl_master_archive_category.id', '=', 'tbl_mibl_advertisement_id.archive_category_id')
  ->leftJoin('tbl_mibl_master_archive_sub_category', 'tbl_mibl_master_archive_sub_category.id', '=', 'tbl_mibl_advertisement_id.archive_sub_category_id')
  ->leftJoin('tbl_mibl_master_vendor', 'tbl_mibl_master_vendor.id', '=', 'tbl_mibl_advertisement_id.vendor_id')
  ->leftJoin('tbl_mibl_master_department', 'tbl_mibl_master_department.id', '=', 'tbl_mibl_advertisement_id.department_id')
  ->leftJoin('tbl_mibl_master_department_type', 'tbl_mibl_master_department_type.id', '=', 'tbl_mibl_advertisement_id.department_type_id')
  ->leftJoin('tbl_mibl_master_vendor_type', 'tbl_mibl_master_vendor_type.id', '=', 'tbl_mibl_advertisement_id.vendor_type_id')
  ->leftJoin('tbl_mibl_master_language', 'tbl_mibl_master_language.id', '=', 'tbl_mibl_advertisement_id.language_id')
  ->where('tbl_mibl_advertisement_id.flag','=','0')
  ->where('tbl_mibl_advertisement_id.is_delete',0)
  ->where('tbl_mibl_master_archive_category.name', 'like', '%' .$searchValue . '%')
  ->orWhere('tbl_mibl_master_department_type.department_type_name', 'like', '%' .$searchValue . '%')
  ->orWhere('tbl_mibl_master_archive_sub_category.name', 'like', '%' .$searchValue . '%')
  ->orWhere('tbl_mibl_master_vendor_type.vendor_type_name', 'like', '%' .$searchValue . '%')
  ->orWhere('tbl_mibl_master_vendor.name', 'like', '%' .$searchValue . '%')
  ->orWhere('tbl_mibl_master_department.name', 'like', '%' .$searchValue . '%')
  ->orWhere('tbl_mibl_advertisement_id.flag', 'like', '%' .$status . '%')
  ->orWhere('tbl_mibl_advertisement_id.created_date', 'like', '%' .$created_dated . '%')
  ->count();
  
    // Fetch records
    $records = Advertisementid_model::orderBy($columnName,$columnSortOrder)
      ->where('tbl_mibl_advertisement_id.flag','=','0')
	  ->where('tbl_mibl_advertisement_id.is_delete',0)
      ->where('tbl_mibl_advertisement_id.flag', 'like', '%' .$status . '%')
      ->orWhere('tbl_mibl_advertisement_id.created_date', 'like', '%' .$created_dated . '%')
      ->orWhere('tbl_mibl_master_department_type.department_type_name', 'like', '%' .$searchValue . '%')
      ->orWhere('tbl_mibl_master_archive_sub_category.name', 'like', '%' .$searchValue . '%')
      ->orWhere('tbl_mibl_master_vendor_type.vendor_type_name', 'like', '%' .$searchValue . '%')
      ->orWhere('tbl_mibl_master_archive_category.name', 'like', '%' .$searchValue . '%')
      ->orWhere('tbl_mibl_master_vendor.name', 'like', '%' .$searchValue . '%')
      ->orWhere('tbl_mibl_master_department.name', 'like', '%' .$searchValue . '%')
      ->leftJoin('tbl_mibl_master_language', 'tbl_mibl_master_language.id', '=', 'tbl_mibl_advertisement_id.language_id')
      ->leftJoin('tbl_mibl_master_archive_sub_category', 'tbl_mibl_master_archive_sub_category.id', '=', 'tbl_mibl_advertisement_id.archive_sub_category_id')
      ->leftJoin('tbl_mibl_master_department', 'tbl_mibl_master_department.id', '=', 'tbl_mibl_advertisement_id.department_id')
      ->leftJoin('tbl_mibl_master_vendor', 'tbl_mibl_master_vendor.id', '=', 'tbl_mibl_advertisement_id.vendor_id')
      ->leftJoin('tbl_mibl_master_archive_category', 'tbl_mibl_master_archive_category.id', '=', 'tbl_mibl_advertisement_id.archive_category_id')
      ->leftJoin('tbl_mibl_master_department_type', 'tbl_mibl_master_department_type.id', '=', 'tbl_mibl_advertisement_id.department_type_id')
      ->leftJoin('tbl_mibl_master_vendor_type', 'tbl_mibl_master_vendor_type.id', '=', 'tbl_mibl_advertisement_id.vendor_type_id')
      ->select('tbl_mibl_advertisement_id.*',
         'tbl_mibl_master_archive_category.name as archive_name',
         'tbl_mibl_master_vendor.name as vendor_name',
         'tbl_mibl_master_department.name as department_name',
         'tbl_mibl_master_archive_sub_category.name as archive_sub_category_name',
         'tbl_mibl_master_department_type.department_type_name as department_type_name',
         'tbl_mibl_master_vendor_type.vendor_type_name as vendor_type_name',
         'tbl_mibl_master_language.language as language_name')
      ->skip($start)
      ->take($rowperpage)
      ->get();
  }else
  {
  
  // Total records
  $totalRecords = Advertisementid_model::select('count(*) as allcount')->count();
  $result_Filter =Advertisementid_model::select('count(*) as allcount');
  $result_Filter->leftJoin('tbl_mibl_master_department', 'tbl_mibl_master_department.id', '=', 'tbl_mibl_advertisement_id.department_id');
  $result_Filter->leftJoin('tbl_mibl_master_vendor', 'tbl_mibl_master_vendor.id', '=', 'tbl_mibl_advertisement_id.vendor_id');
  $result_Filter->leftJoin('tbl_mibl_master_archive_category', 'tbl_mibl_master_archive_category.id', '=', 'tbl_mibl_advertisement_id.archive_category_id');
  $result_Filter->leftJoin('tbl_mibl_master_archive_sub_category', 'tbl_mibl_master_archive_sub_category.id', '=', 'tbl_mibl_advertisement_id.archive_sub_category_id');
  $result_Filter->leftJoin('tbl_mibl_master_department_type', 'tbl_mibl_master_department_type.id', '=', 'tbl_mibl_advertisement_id.department_type_id');
  $result_Filter->leftJoin('tbl_mibl_master_vendor_type', 'tbl_mibl_master_vendor_type.id', '=', 'tbl_mibl_advertisement_id.vendor_type_id');
  $result_Filter->leftJoin('tbl_mibl_master_language', 'tbl_mibl_master_language.id', '=', 'tbl_mibl_advertisement_id.language_id');
  $result_Filter->where('tbl_mibl_advertisement_id.flag','=','0');
  $result_Filter->where('tbl_mibl_advertisement_id.is_delete',0);
  
  if (!empty($vendor_name)) {

    $arr_2=explode(",",$vendor_name);
    $vendor_type_id=$arr_2[0];
    $vendor_id=$arr_2[1];
    $result_Filter->where('tbl_mibl_master_vendor_type.id', '=',$vendor_type_id);
    if($vendor_id != 0){
    $result_Filter->where('tbl_mibl_master_vendor.id', '=',$vendor_id);
    }
  }
  if($status1 == '3' || $status1 == '1' || $status1 == '2') {
    if($status1 == '3')
    {
      $status12=0;
    }else if($status1 == '1')
    {
      $status12=1; 
    }else
    {
      $status12=2;
    }
    $result_Filter->where('tbl_mibl_advertisement_id.flag','=',$status12);
  }
  if (!empty($archive_category_id)) {

    $arr_2=explode(",",$archive_category_id);
    @$archive_category_id1=$arr_2[0];
    @$archive_category_sub_id1=$arr_2[1];
    $result_Filter->where('tbl_mibl_master_archive_category.id', '=',$archive_category_id1);

    if($archive_category_sub_id1 != 0 || $archive_category_sub_id1 != '')
    {
      $result_Filter->where('tbl_mibl_master_archive_sub_category.id', '=',$archive_category_sub_id1);
    }
  }
  if (!empty($department_id)) {
    $arr_2=explode(",",$department_id);
    @$department_type_id=$arr_2[0];
    @$department_ids=$arr_2[1];

    $result_Filter->where('tbl_mibl_master_department_type.id', '=',$department_type_id);
    if($department_ids != 0 && $department_ids != '')
    {
    $result_Filter->where('tbl_mibl_master_department.id', '=',$department_ids);
    }


  }

  if(!empty($from_date) && !empty($to_date))
  {
  $from_date = date('Y-m-d', strtotime($from_date));
  $to_date = date('Y-m-d', strtotime($to_date));
  $result_Filter->whereRaw("date(tbl_mibl_advertisement_id.created_date) >= '" . $from_date . "' AND date(tbl_mibl_advertisement_id.created_date) <= '" . $to_date . "'");

  }



  $totalRecordswithFilter=$result_Filter->count();
  
  
  
  
  
  // Fetch records
  $result =Advertisementid_model::orderBy($columnName,$columnSortOrder);
  
  if (!empty($vendor_name)) {
    $arr_2=explode(",",$vendor_name);
    $vendor_type_id=$arr_2[0];
    $vendor_id=$arr_2[1];
    $result->where('tbl_mibl_master_vendor_type.id', '=',$vendor_type_id);
    if($vendor_id != 0){
    $result->where('tbl_mibl_master_vendor.id', '=',$vendor_id);
    }
  }
  
  if (!empty($archive_category_id)) {
    $arr_3=explode(",",$archive_category_id);

    @$archive_category_id=$arr_3[0];
    @$archive_category_sub_id=$arr_3[1];
    if($archive_category_sub_id != 0 && $archive_category_sub_id != '')
    {
      $result->where('tbl_mibl_master_archive_sub_category.id', '=',$archive_category_sub_id);
    }else
    {
      $result->where('tbl_mibl_master_archive_category.id', '=',$archive_category_id);
    }
  }
  if (!empty($department_id)) {
    $arr_2=explode(",",$department_id);
    @$department_type_id=$arr_2[0];
    @$department_ids=$arr_2[1];

    $result->where('tbl_mibl_master_department_type.id', '=',$department_type_id);
    if($department_ids != 0 && $department_ids != '')
    {
    $result->where('tbl_mibl_master_department.id', '=',$department_ids);
    }
  }
  if($status1 == '3' || $status1 == '1' || $status1 == '2') {
    if($status1 == '3')
    {
      $status12=0;
    }else if($status1 == '1')
    {
      $status12=1; 
    }else
    {
      $status12=2;
    }
    $result->where('tbl_mibl_advertisement_id.flag','=',$status12);
  }

 
   if(!empty($from_date) && !empty($to_date))
   {
   $from_date = date('Y-m-d', strtotime($from_date));
   $to_date = date('Y-m-d', strtotime($to_date));
   $result->whereRaw("date(tbl_mibl_advertisement_id.created_date) >= '" . $from_date . "' AND date(tbl_mibl_advertisement_id.created_date) <= '" . $to_date . "'");
   }
  $result->where('tbl_mibl_advertisement_id.is_delete',0);
  $result->where('tbl_mibl_advertisement_id.flag','=','0'); 
  $result->leftJoin('tbl_mibl_master_department', 'tbl_mibl_master_department.id', '=', 'tbl_mibl_advertisement_id.department_id');
  $result->leftJoin('tbl_mibl_master_vendor', 'tbl_mibl_master_vendor.id', '=', 'tbl_mibl_advertisement_id.vendor_id');
  $result->leftJoin('tbl_mibl_master_archive_category', 'tbl_mibl_master_archive_category.id', '=', 'tbl_mibl_advertisement_id.archive_category_id');
  $result->leftJoin('tbl_mibl_master_archive_sub_category', 'tbl_mibl_master_archive_sub_category.id', '=', 'tbl_mibl_advertisement_id.archive_sub_category_id');
  $result->leftJoin('tbl_mibl_master_department_type', 'tbl_mibl_master_department_type.id', '=', 'tbl_mibl_advertisement_id.department_type_id');
  $result->leftJoin('tbl_mibl_master_vendor_type', 'tbl_mibl_master_vendor_type.id', '=', 'tbl_mibl_advertisement_id.vendor_type_id');
  $result->leftJoin('tbl_mibl_master_language', 'tbl_mibl_master_language.id', '=', 'tbl_mibl_advertisement_id.language_id');
  $result->select('tbl_mibl_advertisement_id.*',
  'tbl_mibl_master_archive_category.name as archive_name',
  'tbl_mibl_master_vendor.name as vendor_name',
  'tbl_mibl_master_department.name as department_name',
  'tbl_mibl_master_archive_sub_category.name as archive_sub_category_name',
  'tbl_mibl_master_department_type.department_type_name as department_type_name',
  'tbl_mibl_master_vendor_type.vendor_type_name as vendor_type_name',
  'tbl_mibl_master_language.language as language_name');
  $result->skip($start);
  $result->take($rowperpage);
  $records=$result->get(); 
  
  }
  //echo count($result);
  
    $data_arr = array();
    $i=1;
    foreach($records as $record){
       
       $id = $record->id;
  
       if($record->flag == '0')
       {
        $status="<span style='color:green'>Open</span>";
       }else if($record->flag == '1')
        {
          $status="<span style='color:red'>Pending</span>";
        }
       else{
        $status="<span style='color:red'>Approved</span>";
       }
  
       if($record->created_date)
       {
        $created_date= date("d/m/Y", strtotime($record->created_date));
       }else
       {
        $last_login_date='';
       }
  
      if($record->archive_sub_category_name)
      {
        $archive_name=$record->archive_sub_category_name;
      }else
      {
        $archive_name=$record->archive_name;
      }

      if($record->vendor_name)
      {
        $vendor_name=$record->vendor_name;
      }else
      {
        $vendor_name=$record->vendor_type_name;
      }

      if($record->department_name)
      {
        $department_name=$record->department_name;
      }else
      {
        $department_name=$record->department_type_name;
      }
  
  
  
       $data_arr[] = array(
         "id" =>$i,
         "advertisement_id" =>$record->advertisement_id,
         "archive_category_id" =>$archive_name,
         "vendor_id" =>$vendor_name,
         "language_name"=>$record->language_name,
         "department_id" =>$department_name,
         "active_yn" =>$status,
         "created_date"=>$created_date,
       );
       $i++;
    }
  
    $response = array(
       "draw" => intval($draw),
       "iTotalRecords" => $totalRecords,
       "iTotalDisplayRecords" => $totalRecordswithFilter,
       "aaData" => $data_arr
    );
  
    echo json_encode($response);
    exit;
  }
  
  
  function export_data_open(Request $request)
{
 
  $vendor_name = trim((!empty($_POST["vendor_id"])) ? ($_POST["vendor_id"]) : (''));
  $advertisement_id = trim((!empty($_POST["advertisement_id"])) ? ($_POST["advertisement_id"]) : (''));
  $archive_category_id = trim((!empty($_POST["archive_category_id"])) ? ($_POST["archive_category_id"]) : (''));
  $department_id = trim((!empty($_POST["department_id"])) ? ($_POST["department_id"]) : (''));
  $from_date = (!empty($_POST["from_date"])) ? ($_POST["from_date"]) : ('');
  $to_date = (!empty($_POST["to_date"])) ? ($_POST["to_date"]) : ('');
  $status1 = trim((!empty($_POST["status"])) ? ($_POST["status"]) : (''));

if($request->Export == "Export All"){
        
        
  // Fetch records
  $result =Advertisementid_model::orderBy('tbl_mibl_advertisement_id.id','ASC');
  
  if (!empty($vendor_name)) {
    $arr_2=explode(",",$vendor_name);
    $vendor_type_id=$arr_2[0];
    $vendor_id=$arr_2[1];
    $result->where('tbl_mibl_master_vendor_type.id', '=',$vendor_type_id);
    if($vendor_id != 0){
    $result->where('tbl_mibl_master_vendor.id', '=',$vendor_id);
    }
  }
  
  if (!empty($archive_category_id)) {
    $arr_3=explode(",",$archive_category_id);

    @$archive_category_id=$arr_3[0];
    @$archive_category_sub_id=$arr_3[1];
    if($archive_category_sub_id != 0 && $archive_category_sub_id != '')
    {
      $result->where('tbl_mibl_master_archive_sub_category.id', '=',$archive_category_sub_id);
    }else
    {
      $result->where('tbl_mibl_master_archive_category.id', '=',$archive_category_id);
    }
  }
  if (!empty($department_id)) {
    $arr_2=explode(",",$department_id);
    @$department_type_id=$arr_2[0];
    @$department_ids=$arr_2[1];

    $result->where('tbl_mibl_master_department_type.id', '=',$department_type_id);
    if($department_ids != 0 && $department_ids != '')
    {
    $result->where('tbl_mibl_master_department.id', '=',$department_ids);
    }
  }
  if($status1 == '3' || $status1 == '1' || $status1 == '2') {
    if($status1 == '3')
    {
      $status12=0;
    }else if($status1 == '1')
    {
      $status12=1; 
    }else
    {
      $status12=2;
    }
    $result->where('tbl_mibl_advertisement_id.flag','=',$status12);
  }

 
   if(!empty($from_date) && !empty($to_date))
   {
   $from_date = date('Y-m-d', strtotime($from_date));
   $to_date = date('Y-m-d', strtotime($to_date));
   $result->whereRaw("date(tbl_mibl_advertisement_id.created_date) >= '" . $from_date . "' AND date(tbl_mibl_advertisement_id.created_date) <= '" . $to_date . "'");
   }
    $result->where('tbl_mibl_advertisement_id.is_delete',0);
    $result->where('tbl_mibl_advertisement_id.flag','=','0');   
    $result->leftJoin('tbl_mibl_master_department', 'tbl_mibl_master_department.id', '=', 'tbl_mibl_advertisement_id.department_id');
    $result->leftJoin('tbl_mibl_master_vendor', 'tbl_mibl_master_vendor.id', '=', 'tbl_mibl_advertisement_id.vendor_id');
    $result->leftJoin('tbl_mibl_master_archive_category', 'tbl_mibl_master_archive_category.id', '=', 'tbl_mibl_advertisement_id.archive_category_id');
    $result->leftJoin('tbl_mibl_master_archive_sub_category', 'tbl_mibl_master_archive_sub_category.id', '=', 'tbl_mibl_advertisement_id.archive_sub_category_id');
    $result->leftJoin('tbl_mibl_master_department_type', 'tbl_mibl_master_department_type.id', '=', 'tbl_mibl_advertisement_id.department_type_id');
    $result->leftJoin('tbl_mibl_master_vendor_type', 'tbl_mibl_master_vendor_type.id', '=', 'tbl_mibl_advertisement_id.vendor_type_id');
    $result->leftJoin('tbl_mibl_master_language', 'tbl_mibl_master_language.id', '=', 'tbl_mibl_advertisement_id.language_id');
    $result->select('tbl_mibl_advertisement_id.*',
    'tbl_mibl_master_archive_category.name as archive_name',
    'tbl_mibl_master_vendor.name as vendor_name',
    'tbl_mibl_master_department.name as department_name',
    'tbl_mibl_master_archive_sub_category.name as archive_sub_category_name',
    'tbl_mibl_master_department_type.department_type_name as department_type_name',
    'tbl_mibl_master_vendor_type.vendor_type_name as vendor_type_name',
    'tbl_mibl_master_language.language as language_name');
    $exports=$result->get(); 
    
    $contents = "SR.NO,Advertisement Id,Vendor,Department,Archive Category,Language,Created Date,Status\n";
    
    $i = 1;
       
		foreach ($exports as $key) {
		    
		    
            if($key->flag == '0')
            {
            $status="Open";
            }else if($key->flag == '1')
            {
            $status="Pending";
            }
            else{
            $status="Approved";
            }
  
		    
            $contents .= $i . ",";
            $contents .= $key->advertisement_id.",";
            $contents .= str_replace(',',' ',$key->vendor_name) . ",";
            $contents .= str_replace(',',' ',$key->department_name) . ",";
            $contents .= str_replace(',',' ',$key->archive_name) . ",";
            $contents .= str_replace(',',' ',$key->language_name) . ",";
            $contents .= $key->created_date. ",";
            $contents .= $status."\n";
            $i++;   
            }
        $contents = strip_tags($contents);
		header("Content-Disposition: attachment; filename=exportall" . date('d-m-Y') . ".csv");
 		print $contents;
 	}
}

    




public function advertisement_id_open_vendor_list(Request $request)
{
//Archive category type and Archive sub category 

$archive_category = DB::table('tbl_mibl_master_archive_category')
->select('*')
->where('active_yn',0)
->orderBy('name','ASC')
->get();
$archive_c=array();
foreach($archive_category as $archivecategory)
{
$sub_cat=array();
$data = DB::table('tbl_mibl_master_archive_sub_category')
 ->where('archive_category_id',$archivecategory->id)
 ->where('active_yn',0)->orderBy('name', 'ASC')->get();
 if(count($data) > 0){
 foreach($data as $dat)
 {
   $sub_cat[]=array(
    'sub_category_id'=>$dat->id,
      'sub_category'=>$dat->name
   );
 }
}else
{
  $sub_cat[]=array(
    'sub_category_id'=>0,
      'sub_category'=>$archivecategory->name
   );
}

$archive_c[]=array(
  'archive_category_id'=>$archivecategory->id,
  'archive_category'=>$archivecategory->name,
  'sub_list'=>$sub_cat
);

}

//department type and department 
$department = DB::table('tbl_mibl_master_department_type')
->select('*')
->where('active_yn',0)
->orderBy('department_type_name','ASC')
->get();
$department_c=array();
foreach($department as $department_type)
{
$sub_depart=array();
$data = DB::table('tbl_mibl_master_department')
 ->where('department_type_id',$department_type->id)
 ->where('active_yn',0)->orderBy('name', 'ASC')->get();
 if(count($data) > 0){
 foreach($data as $dat)
 {
   $sub_depart[]=array(
      'department_id'=>$dat->id,
      'department_name'=>$dat->name
   );
 }
}
else
{
  $sub_depart[]=array(
    'department_id'=>0,
    'department_name'=>$department_type->department_type_name
   );
}

$department_c[]=array(
  'department_type_id'=>$department_type->id,
  'department_type_name'=>$department_type->department_type_name,
  'department_list'=>$sub_depart
);


}

//Vendor type and vendor 

$vendor = DB::table('tbl_mibl_master_vendor_type')
->select('*')
->where('active_yn',0)
->orderBy('vendor_type_name','ASC')
->get();


$vendor_c=array();
foreach($vendor as $vendor_type)
{
$sub_vendor=array();
$data = DB::table('tbl_mibl_master_vendor')
   ->where('vendor_type_id',$vendor_type->id)
   ->where('active_yn',0)
   ->where('flag',1)->orderBy('name', 'ASC')->get();
 if(count($data) > 0){
 foreach($data as $dat)
 {
   $sub_vendor[]=array(
      'vendor_id'=>$dat->id,
      'vendor_name'=>$dat->name
   );
 }
}
else
{
  $sub_vendor[]=array(
    'vendor_id'=>0,
    'vendor_name'=>$vendor_type->vendor_type_name
   );
}

$vendor_c[]=array(
  'vendor_type_id'=>$vendor_type->id,
  'vendor_type_name'=>$vendor_type->vendor_type_name,
  'vendor_list'=>$sub_vendor
);

}



return view('/admin/advertisement_id_open_vendor_list',
['archive_c'=>$archive_c,
 'department_c'=>$department_c,
 'vendor_c'=>$vendor_c]);
}



public function getadvertisement_id_open_vendor_list(Request $request){


  //custom search 
  $vendor_idd=session('id');  
  $vendor_name = trim((!empty($_GET["vendor_id"])) ? ($_GET["vendor_id"]) : (''));
  $advertisement_id = trim((!empty($_GET["advertisement_id"])) ? ($_GET["advertisement_id"]) : (''));
  $archive_category_id = trim((!empty($_GET["archive_category_id"])) ? ($_GET["archive_category_id"]) : (''));
  $department_id = trim((!empty($_GET["department_id"])) ? ($_GET["department_id"]) : (''));
  $from_date = (!empty($_GET["from_date"])) ? ($_GET["from_date"]) : ('');
  $to_date = (!empty($_GET["to_date"])) ? ($_GET["to_date"]) : ('');
  $status1 = trim((!empty($_GET["status"])) ? ($_GET["status"]) : (''));

  
    ## Read value
    $draw = $request->get('draw');
    $start = $request->get("start");
    $rowperpage = $request->get("length"); // Rows display per page
  
    $columnIndex_arr = $request->get('order');
    $columnName_arr = $request->get('columns');
    $order_arr = $request->get('order');
    $search_arr = $request->get('search');
  
    $columnIndex = $columnIndex_arr[0]['column']; // Column index
    $columnName = $columnName_arr[$columnIndex]['data']; // Column name
    $columnSortOrder = $order_arr[0]['dir']; // asc or desc
    @$searchValue = trim($search_arr['value']); // Search value
  
    @$searchValue1 = trim($search_arr['value']); // Search value
    
    if(Str::upper(@$searchValue1) == 'OPEN')
    {
    $status='0';
    }else if(Str::upper(@$searchValue1) == 'PENDING')
    {
     $status='1';
    }
    else if(Str::upper(@$searchValue1) == 'APPROVED')
    {
     $status='2';
    }
    else{
        $status=$searchValue1; 
    }

        //date search

        if(!empty($searchValue)){
          $var =$searchValue;
          $date = str_replace('/', '-', $var);
          $created_date=date('Y-m-d', strtotime($date));
          if (strtotime($created_date)) {
          $created_dated=$created_date;
          }
          else {
          $created_dated=$searchValue;
          }
          }else
          {
          $created_dated=$searchValue;
          }



  if(!empty($searchValue1))
  {
    $end_date = date('Y-m-d');
    $start_date = date("Y-m-d", strtotime("-3 years"));
  // Total records
    $totalRecords = Advertisementid_model::select('count(*) as allcount')->count();
    $totalRecordswithFilter = Advertisementid_model::select('count(*) as allcount')
  ->leftJoin('tbl_mibl_master_archive_category', 'tbl_mibl_master_archive_category.id', '=', 'tbl_mibl_advertisement_id.archive_category_id')
  ->leftJoin('tbl_mibl_master_archive_sub_category', 'tbl_mibl_master_archive_sub_category.id', '=', 'tbl_mibl_advertisement_id.archive_sub_category_id')
  ->leftJoin('tbl_mibl_master_vendor', 'tbl_mibl_master_vendor.id', '=', 'tbl_mibl_advertisement_id.vendor_id')
  ->leftJoin('tbl_mibl_master_department', 'tbl_mibl_master_department.id', '=', 'tbl_mibl_advertisement_id.department_id')
  ->leftJoin('tbl_mibl_master_department_type', 'tbl_mibl_master_department_type.id', '=', 'tbl_mibl_advertisement_id.department_type_id')
  ->leftJoin('tbl_mibl_master_vendor_type', 'tbl_mibl_master_vendor_type.id', '=', 'tbl_mibl_advertisement_id.vendor_type_id')
  ->leftJoin('tbl_mibl_master_language', 'tbl_mibl_master_language.id', '=', 'tbl_mibl_advertisement_id.language_id')
  ->where('tbl_mibl_advertisement_id.flag','=','0')
  ->where('tbl_mibl_advertisement_id.is_delete',0)
  ->where('tbl_mibl_advertisement_id.vendor_id','=',$vendor_idd)
  ->where('tbl_mibl_master_archive_category.name', 'like', '%' .$searchValue . '%')
  ->orWhere('tbl_mibl_master_department_type.department_type_name', 'like', '%' .$searchValue . '%')
  ->orWhere('tbl_mibl_master_archive_sub_category.name', 'like', '%' .$searchValue . '%')
  ->orWhere('tbl_mibl_master_vendor_type.vendor_type_name', 'like', '%' .$searchValue . '%')
  ->orWhere('tbl_mibl_master_vendor.name', 'like', '%' .$searchValue . '%')
  ->orWhere('tbl_mibl_master_department.name', 'like', '%' .$searchValue . '%')
  ->orWhere('tbl_mibl_advertisement_id.flag', 'like', '%' .$status . '%')
  ->orWhere('tbl_mibl_advertisement_id.created_date', 'like', '%' .$created_dated . '%')
  ->count();
  
    // Fetch records
    $records = Advertisementid_model::orderBy($columnName,$columnSortOrder)
      ->where('tbl_mibl_advertisement_id.flag','=','0')
	  ->where('tbl_mibl_advertisement_id.is_delete',0)
      ->where('tbl_mibl_advertisement_id.vendor_id','=',$vendor_idd)
      ->where('tbl_mibl_advertisement_id.flag', 'like', '%' .$status . '%')
      ->orWhere('tbl_mibl_advertisement_id.created_date', 'like', '%' .$created_dated . '%')
      ->orWhere('tbl_mibl_master_department_type.department_type_name', 'like', '%' .$searchValue . '%')
      ->orWhere('tbl_mibl_master_archive_sub_category.name', 'like', '%' .$searchValue . '%')
      ->orWhere('tbl_mibl_master_vendor_type.vendor_type_name', 'like', '%' .$searchValue . '%')
      ->orWhere('tbl_mibl_master_archive_category.name', 'like', '%' .$searchValue . '%')
      ->orWhere('tbl_mibl_master_vendor.name', 'like', '%' .$searchValue . '%')
      ->orWhere('tbl_mibl_master_department.name', 'like', '%' .$searchValue . '%')
      ->leftJoin('tbl_mibl_master_language', 'tbl_mibl_master_language.id', '=', 'tbl_mibl_advertisement_id.language_id')
      ->leftJoin('tbl_mibl_master_archive_sub_category', 'tbl_mibl_master_archive_sub_category.id', '=', 'tbl_mibl_advertisement_id.archive_sub_category_id')
      ->leftJoin('tbl_mibl_master_department', 'tbl_mibl_master_department.id', '=', 'tbl_mibl_advertisement_id.department_id')
      ->leftJoin('tbl_mibl_master_vendor', 'tbl_mibl_master_vendor.id', '=', 'tbl_mibl_advertisement_id.vendor_id')
      ->leftJoin('tbl_mibl_master_archive_category', 'tbl_mibl_master_archive_category.id', '=', 'tbl_mibl_advertisement_id.archive_category_id')
      ->leftJoin('tbl_mibl_master_department_type', 'tbl_mibl_master_department_type.id', '=', 'tbl_mibl_advertisement_id.department_type_id')
      ->leftJoin('tbl_mibl_master_vendor_type', 'tbl_mibl_master_vendor_type.id', '=', 'tbl_mibl_advertisement_id.vendor_type_id')
      ->select('tbl_mibl_advertisement_id.*',
         'tbl_mibl_master_archive_category.name as archive_name',
         'tbl_mibl_master_vendor.name as vendor_name',
         'tbl_mibl_master_department.name as department_name',
         'tbl_mibl_master_archive_sub_category.name as archive_sub_category_name',
         'tbl_mibl_master_department_type.department_type_name as department_type_name',
         'tbl_mibl_master_vendor_type.vendor_type_name as vendor_type_name',
         'tbl_mibl_master_language.language as language_name')
      ->skip($start)
      ->take($rowperpage)
      ->get();
  }else
  {
  
  // Total records
  $totalRecords = Advertisementid_model::select('count(*) as allcount')->count();
  $result_Filter =Advertisementid_model::select('count(*) as allcount');
  $result_Filter->leftJoin('tbl_mibl_master_department', 'tbl_mibl_master_department.id', '=', 'tbl_mibl_advertisement_id.department_id');
  $result_Filter->leftJoin('tbl_mibl_master_vendor', 'tbl_mibl_master_vendor.id', '=', 'tbl_mibl_advertisement_id.vendor_id');
  $result_Filter->leftJoin('tbl_mibl_master_archive_category', 'tbl_mibl_master_archive_category.id', '=', 'tbl_mibl_advertisement_id.archive_category_id');
  $result_Filter->leftJoin('tbl_mibl_master_archive_sub_category', 'tbl_mibl_master_archive_sub_category.id', '=', 'tbl_mibl_advertisement_id.archive_sub_category_id');
  $result_Filter->leftJoin('tbl_mibl_master_department_type', 'tbl_mibl_master_department_type.id', '=', 'tbl_mibl_advertisement_id.department_type_id');
  $result_Filter->leftJoin('tbl_mibl_master_vendor_type', 'tbl_mibl_master_vendor_type.id', '=', 'tbl_mibl_advertisement_id.vendor_type_id');
  $result_Filter->leftJoin('tbl_mibl_master_language', 'tbl_mibl_master_language.id', '=', 'tbl_mibl_advertisement_id.language_id');
  $result_Filter->where('tbl_mibl_advertisement_id.flag','=','0');
  $result_Filter->where('tbl_mibl_advertisement_id.is_delete',0);
  $result_Filter->where('tbl_mibl_advertisement_id.vendor_id','=',$vendor_idd);
  
  if (!empty($vendor_name)) {

    $arr_2=explode(",",$vendor_name);
    $vendor_type_id=$arr_2[0];
    $vendor_id=$arr_2[1];
    $result_Filter->where('tbl_mibl_master_vendor_type.id', '=',$vendor_type_id);
    if($vendor_id != 0){
    $result_Filter->where('tbl_mibl_master_vendor.id', '=',$vendor_id);
    }
  }
  if($status1 == '3' || $status1 == '1' || $status1 == '2') {
    if($status1 == '3')
    {
      $status12=0;
    }else if($status1 == '1')
    {
      $status12=1; 
    }else
    {
      $status12=2;
    }
    $result_Filter->where('tbl_mibl_advertisement_id.flag','=',$status12);
  }
  if (!empty($archive_category_id)) {

    $arr_2=explode(",",$archive_category_id);
    @$archive_category_id1=$arr_2[0];
    @$archive_category_sub_id1=$arr_2[1];
    $result_Filter->where('tbl_mibl_master_archive_category.id', '=',$archive_category_id1);

    if($archive_category_sub_id1 != 0 || $archive_category_sub_id1 != '')
    {
      $result_Filter->where('tbl_mibl_master_archive_sub_category.id', '=',$archive_category_sub_id1);
    }
  }
  if (!empty($department_id)) {
    $arr_2=explode(",",$department_id);
    @$department_type_id=$arr_2[0];
    @$department_ids=$arr_2[1];

    $result_Filter->where('tbl_mibl_master_department_type.id', '=',$department_type_id);
    if($department_ids != 0 && $department_ids != '')
    {
    $result_Filter->where('tbl_mibl_master_department.id', '=',$department_ids);
    }


  }

  if(!empty($from_date) && !empty($to_date))
  {
  $from_date = date('Y-m-d', strtotime($from_date));
  $to_date = date('Y-m-d', strtotime($to_date));
  $result_Filter->whereRaw("date(tbl_mibl_advertisement_id.created_date) >= '" . $from_date . "' AND date(tbl_mibl_advertisement_id.created_date) <= '" . $to_date . "'");

  }



  $totalRecordswithFilter=$result_Filter->count();
  
  
  
  
  
  // Fetch records
  $result =Advertisementid_model::orderBy($columnName,$columnSortOrder);
  
  if (!empty($vendor_name)) {
    $arr_2=explode(",",$vendor_name);
    $vendor_type_id=$arr_2[0];
    $vendor_id=$arr_2[1];
    $result->where('tbl_mibl_master_vendor_type.id', '=',$vendor_type_id);
    if($vendor_id != 0){
    $result->where('tbl_mibl_master_vendor.id', '=',$vendor_id);
    }
  }
  
  if (!empty($archive_category_id)) {
    $arr_3=explode(",",$archive_category_id);

    @$archive_category_id=$arr_3[0];
    @$archive_category_sub_id=$arr_3[1];
    if($archive_category_sub_id != 0 && $archive_category_sub_id != '')
    {
      $result->where('tbl_mibl_master_archive_sub_category.id', '=',$archive_category_sub_id);
    }else
    {
      $result->where('tbl_mibl_master_archive_category.id', '=',$archive_category_id);
    }
  }
  if (!empty($department_id)) {
    $arr_2=explode(",",$department_id);
    @$department_type_id=$arr_2[0];
    @$department_ids=$arr_2[1];

    $result->where('tbl_mibl_master_department_type.id', '=',$department_type_id);
    if($department_ids != 0 && $department_ids != '')
    {
    $result->where('tbl_mibl_master_department.id', '=',$department_ids);
    }
  }
  if($status1 == '3' || $status1 == '1' || $status1 == '2') {
    if($status1 == '3')
    {
      $status12=0;
    }else if($status1 == '1')
    {
      $status12=1; 
    }else
    {
      $status12=2;
    }
    $result->where('tbl_mibl_advertisement_id.flag','=',$status12);
  }

 
   if(!empty($from_date) && !empty($to_date))
   {
   $from_date = date('Y-m-d', strtotime($from_date));
   $to_date = date('Y-m-d', strtotime($to_date));
   $result->whereRaw("date(tbl_mibl_advertisement_id.created_date) >= '" . $from_date . "' AND date(tbl_mibl_advertisement_id.created_date) <= '" . $to_date . "'");
   }
  $result->where('tbl_mibl_advertisement_id.is_delete',0); 
  $result->where('tbl_mibl_advertisement_id.vendor_id','=',$vendor_idd);
  $result->where('tbl_mibl_advertisement_id.flag','=','0'); 
  $result->leftJoin('tbl_mibl_master_department', 'tbl_mibl_master_department.id', '=', 'tbl_mibl_advertisement_id.department_id');
  $result->leftJoin('tbl_mibl_master_vendor', 'tbl_mibl_master_vendor.id', '=', 'tbl_mibl_advertisement_id.vendor_id');
  $result->leftJoin('tbl_mibl_master_archive_category', 'tbl_mibl_master_archive_category.id', '=', 'tbl_mibl_advertisement_id.archive_category_id');
  $result->leftJoin('tbl_mibl_master_archive_sub_category', 'tbl_mibl_master_archive_sub_category.id', '=', 'tbl_mibl_advertisement_id.archive_sub_category_id');
  $result->leftJoin('tbl_mibl_master_department_type', 'tbl_mibl_master_department_type.id', '=', 'tbl_mibl_advertisement_id.department_type_id');
  $result->leftJoin('tbl_mibl_master_vendor_type', 'tbl_mibl_master_vendor_type.id', '=', 'tbl_mibl_advertisement_id.vendor_type_id');
  $result->leftJoin('tbl_mibl_master_language', 'tbl_mibl_master_language.id', '=', 'tbl_mibl_advertisement_id.language_id');
  $result->select('tbl_mibl_advertisement_id.*',
  'tbl_mibl_master_archive_category.name as archive_name',
  'tbl_mibl_master_vendor.name as vendor_name',
  'tbl_mibl_master_department.name as department_name',
  'tbl_mibl_master_archive_sub_category.name as archive_sub_category_name',
  'tbl_mibl_master_department_type.department_type_name as department_type_name',
  'tbl_mibl_master_vendor_type.vendor_type_name as vendor_type_name',
  'tbl_mibl_master_language.language as language_name');
  $result->skip($start);
  $result->take($rowperpage);
  $records=$result->get(); 
  
  }
  //echo count($result);
  
    $data_arr = array();
    $i=1;
    foreach($records as $record){
       
       $id = $record->id;
  
       if($record->flag == '0')
       {
        $status="<span style='color:green'>Open</span>";
       }else if($record->flag == '1')
        {
          $status="<span style='color:red'>Pending</span>";
        }
       else{
        $status="<span style='color:red'>Approved</span>";
       }
  
       if($record->created_date)
       {
        $created_date= date("d/m/Y", strtotime($record->created_date));
       }else
       {
        $last_login_date='';
       }
  
      if($record->archive_sub_category_name)
      {
        $archive_name=$record->archive_sub_category_name;
      }else
      {
        $archive_name=$record->archive_name;
      }

      if($record->vendor_name)
      {
        $vendor_name=$record->vendor_name;
      }else
      {
        $vendor_name=$record->vendor_type_name;
      }

      if($record->department_name)
      {
        $department_name=$record->department_name;
      }else
      {
        $department_name=$record->department_type_name;
      }
  
  
  
       $data_arr[] = array(
         "id" =>$i,
         "advertisement_id" =>$record->advertisement_id,
         "archive_category_id" =>$archive_name,
         "vendor_id" =>$vendor_name,
         "language_name"=>$record->language_name,
         "department_id" =>$department_name,
         "active_yn" =>$status,
         "created_date"=>$created_date,
       );
       $i++;
    }
  
    $response = array(
       "draw" => intval($draw),
       "iTotalRecords" => $totalRecords,
       "iTotalDisplayRecords" => $totalRecordswithFilter,
       "aaData" => $data_arr
    );
  
    echo json_encode($response);
    exit;
  }
  
  
  function export_data_open_vendor(Request $request)
{
 
 $vendor_idd=session('id');  
  $vendor_name = trim((!empty($_POST["vendor_id"])) ? ($_POST["vendor_id"]) : (''));
  $advertisement_id = trim((!empty($_POST["advertisement_id"])) ? ($_POST["advertisement_id"]) : (''));
  $archive_category_id = trim((!empty($_POST["archive_category_id"])) ? ($_POST["archive_category_id"]) : (''));
  $department_id = trim((!empty($_POST["department_id"])) ? ($_POST["department_id"]) : (''));
  $from_date = (!empty($_POST["from_date"])) ? ($_POST["from_date"]) : ('');
  $to_date = (!empty($_POST["to_date"])) ? ($_POST["to_date"]) : ('');
  $status1 = trim((!empty($_POST["status"])) ? ($_POST["status"]) : (''));

if($request->Export == "Export All"){
        
        
  // Fetch records
  $result =Advertisementid_model::orderBy('tbl_mibl_advertisement_id.id','ASC');
  
  if (!empty($vendor_name)) {
    $arr_2=explode(",",$vendor_name);
    $vendor_type_id=$arr_2[0];
    $vendor_id=$arr_2[1];
    $result->where('tbl_mibl_master_vendor_type.id', '=',$vendor_type_id);
    if($vendor_id != 0){
    $result->where('tbl_mibl_master_vendor.id', '=',$vendor_id);
    }
  }
  
  if (!empty($archive_category_id)) {
    $arr_3=explode(",",$archive_category_id);

    @$archive_category_id=$arr_3[0];
    @$archive_category_sub_id=$arr_3[1];
    if($archive_category_sub_id != 0 && $archive_category_sub_id != '')
    {
      $result->where('tbl_mibl_master_archive_sub_category.id', '=',$archive_category_sub_id);
    }else
    {
      $result->where('tbl_mibl_master_archive_category.id', '=',$archive_category_id);
    }
  }
  if (!empty($department_id)) {
    $arr_2=explode(",",$department_id);
    @$department_type_id=$arr_2[0];
    @$department_ids=$arr_2[1];

    $result->where('tbl_mibl_master_department_type.id', '=',$department_type_id);
    if($department_ids != 0 && $department_ids != '')
    {
    $result->where('tbl_mibl_master_department.id', '=',$department_ids);
    }
  }
  if($status1 == '3' || $status1 == '1' || $status1 == '2') {
    if($status1 == '3')
    {
      $status12=0;
    }else if($status1 == '1')
    {
      $status12=1; 
    }else
    {
      $status12=2;
    }
    $result->where('tbl_mibl_advertisement_id.flag','=',$status12);
  }

 
   if(!empty($from_date) && !empty($to_date))
   {
   $from_date = date('Y-m-d', strtotime($from_date));
   $to_date = date('Y-m-d', strtotime($to_date));
   $result->whereRaw("date(tbl_mibl_advertisement_id.created_date) >= '" . $from_date . "' AND date(tbl_mibl_advertisement_id.created_date) <= '" . $to_date . "'");
   }
    $result->where('tbl_mibl_advertisement_id.is_delete',0);
    $result->where('tbl_mibl_advertisement_id.vendor_id','=',$vendor_idd);
    $result->where('tbl_mibl_advertisement_id.flag','=','0');   
    $result->leftJoin('tbl_mibl_master_department', 'tbl_mibl_master_department.id', '=', 'tbl_mibl_advertisement_id.department_id');
    $result->leftJoin('tbl_mibl_master_vendor', 'tbl_mibl_master_vendor.id', '=', 'tbl_mibl_advertisement_id.vendor_id');
    $result->leftJoin('tbl_mibl_master_archive_category', 'tbl_mibl_master_archive_category.id', '=', 'tbl_mibl_advertisement_id.archive_category_id');
    $result->leftJoin('tbl_mibl_master_archive_sub_category', 'tbl_mibl_master_archive_sub_category.id', '=', 'tbl_mibl_advertisement_id.archive_sub_category_id');
    $result->leftJoin('tbl_mibl_master_department_type', 'tbl_mibl_master_department_type.id', '=', 'tbl_mibl_advertisement_id.department_type_id');
    $result->leftJoin('tbl_mibl_master_vendor_type', 'tbl_mibl_master_vendor_type.id', '=', 'tbl_mibl_advertisement_id.vendor_type_id');
    $result->leftJoin('tbl_mibl_master_language', 'tbl_mibl_master_language.id', '=', 'tbl_mibl_advertisement_id.language_id');
    $result->select('tbl_mibl_advertisement_id.*',
    'tbl_mibl_master_archive_category.name as archive_name',
    'tbl_mibl_master_vendor.name as vendor_name',
    'tbl_mibl_master_department.name as department_name',
    'tbl_mibl_master_archive_sub_category.name as archive_sub_category_name',
    'tbl_mibl_master_department_type.department_type_name as department_type_name',
    'tbl_mibl_master_vendor_type.vendor_type_name as vendor_type_name',
    'tbl_mibl_master_language.language as language_name');
    $exports=$result->get(); 
    
    $contents = "SR.NO,Advertisement Id,Vendor,Department,Archive Category,Language,Created Date,Status\n";
    
    $i = 1;
       
		foreach ($exports as $key) {
		    
		    
            if($key->flag == '0')
            {
            $status="Open";
            }else if($key->flag == '1')
            {
            $status="Pending";
            }
            else{
            $status="Approved";
            }
  
		    
            $contents .= $i . ",";
            $contents .= $key->advertisement_id.",";
            $contents .= str_replace(',',' ',$key->vendor_name) . ",";
            $contents .= str_replace(',',' ',$key->department_name) . ",";
            $contents .= str_replace(',',' ',$key->archive_name) . ",";
            $contents .= str_replace(',',' ',$key->language_name) . ",";
            $contents .= $key->created_date. ",";
            $contents .= $status."\n";
            $i++;   
            }
        $contents = strip_tags($contents);
		header("Content-Disposition: attachment; filename=exportall" . date('d-m-Y') . ".csv");
 		print $contents;
 	}
}

    




 
}