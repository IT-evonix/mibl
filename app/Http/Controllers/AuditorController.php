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
use App\Auditor;



class AuditorController extends Controller
{


public function view_auditor(Request $request)
{
return view('/admin/view_auditor');
}


public function getauditor(Request $request){

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
  $searchValue = $search_arr['value']; // Search value

  $searchValue1 = $search_arr['value']; // Search value
  
  if(Str::upper($searchValue1) == 'ACTIVE')
  {
  $status='0';
  }else if(Str::upper($searchValue1) == 'INACTIVE')
  {
   $status='1';
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

      $totalRecords = Auditor::select('count(*) as allcount')->count();
      $totalRecordswithFilter = Auditor::select('count(*) as allcount')
      ->where(function ($query) use ($searchValue,$created_dated){
                          $query ->where('name', 'like', '%' .$searchValue . '%')
                          ->where('name', 'like', '%' .$searchValue . '%')
                          ->orWhere('email', 'like', '%' .$searchValue . '%')
                          ->orWhere('user_type', 'like', '%' .$searchValue . '%')
                          ->orWhere('pan_no', 'like', '%' .$searchValue . '%')
                          ->orWhere('last_login_date', 'like', '%' .$searchValue . '%')
                          ->orWhere('mobile_no', 'like', '%' .$searchValue . '%')
                          ->orWhere('address', 'like', '%' .$searchValue . '%')
                          ->orWhere('sap_code', 'like', '%' .$searchValue . '%')
                          ->orWhere('created_date', 'like', '%' .$created_dated. '%');
              })
      ->where('flag','1')
      ->count();




  // Fetch records
  $records = Auditor::orderBy($columnName,$columnSortOrder)
  ->where(function ($query) use ($searchValue,$created_dated){
    $query ->where('name', 'like', '%' .$searchValue . '%')
    ->where('name', 'like', '%' .$searchValue . '%')
    ->orWhere('email', 'like', '%' .$searchValue . '%')
    ->orWhere('user_type', 'like', '%' .$searchValue . '%')
    ->orWhere('pan_no', 'like', '%' .$searchValue . '%')
    ->orWhere('last_login_date', 'like', '%' .$searchValue . '%')
    ->orWhere('mobile_no', 'like', '%' .$searchValue . '%')
    ->orWhere('address', 'like', '%' .$searchValue . '%')
    ->orWhere('sap_code', 'like', '%' .$searchValue . '%')
    ->orWhere('created_date', 'like', '%' .$created_dated. '%');
})
->where('flag','1')
    ->select('tbl_mibl_auditor.*')
    ->skip($start)
    ->take($rowperpage)
    ->get();

  $data_arr = array();
  $i=1;
  foreach($records as $record){
     
     $id = $record->id;

     if($record->active_yn == '0')
     {
      $status="<span style='color:green'>Active</span>";
     }else{
      $status="<span style='color:red'>Inactive</span>";
     }

     if($record->created_date)
     {
      $created_date = date("d/m/Y", strtotime($record->created_date));
     }else
     {
      $last_login_date='';
     }
     
     if(!empty($record->last_login_date))
     {
      $last_login_date=$newDate = date("d/m/Y", strtotime($record->last_login_date));
     }else
     {
      $last_login_date='';
     }
     if(!empty($record->id))
     {
      $APP_URL=$_ENV['APP_URL']."edit-auditor/".base64_encode($record->id);
      $img="<img src='".$_ENV['APP_URL']."assets/img/edit.png' class='img-fluid tab-img'>";

      if($record->user_type != 'Auditor User'){
      $APP_URL_user_access=$_ENV['APP_URL']."edit-auditor/".base64_encode($record->id);
      $roleaccess="<a href='".$APP_URL_user_access."'><i class='fa fa-lock' style='color:#da3d2c;margin-left: 5px;font-size:17px;'></i></a>";
      }else
      {
        $roleaccess='';
      }
      $edit_link="<a href='".$APP_URL."'>$img</a>".$roleaccess;
    
    }
     

     $data_arr[] = array(
       "id" =>$i,
       "name" =>$record->name,
       "sap_code" =>$record->sap_code,
       "email" =>$record->email,
       "user_type" =>$record->user_type,
       "last_login_date" =>$last_login_date,
       "pan_no" =>$record->pan_no,
       "mobile_no" =>$record->mobile_no,
       "address" =>$record->address,
       "active_yn" =>$status,
       "created_date"=>$created_date,
       "action"=>$edit_link
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


public function add_auditor(Request $request)
{
  $data = DB::table('tbl_mibl_master_user_type')
  ->select('*')
  ->where('active_yn',0)
  ->get();

  $data_sap = DB::table('tbl_mibl_user')
  ->select('*')
  ->where('flag',0)
  ->get();
  
  return view('/admin/add_auditor', ['user_type' => $data,'user_sap_code'=>$data_sap]);
}
 
 
public function insert_auditor(Request $request)
{

  $data = DB::table('tbl_mibl_auditor')
  ->select('*')
  ->Where('sap_code',$request->input('sap_code'))
  ->Where('flag','1')
  ->get();


if(count($data) == 0) { 
$name = $request->input('name');
$email = $request->input('email');
$mobile_no = $request->input('mobile_no');
$address = $request->input('address');
$password = Hash::make($request->input('password'));
$sap_code = $request->input('sap_code');




$user_id=session('id');
$user = DB::table('tbl_mibl_user')
->select('*')
->where('deleted_at','=',0)
->where('id',$user_id)
->orderBy('id', 'desc')
->first();
$username=$user->name;

$id=DB::table('tbl_mibl_auditor')->insertGetId([
    'user_type'=>"Auditor User",
    'name'=>$name,
    'email'=>$email,
    'sap_code'=>$sap_code,
    'mobile_no'=>$mobile_no,
    'address'=>$address,
    'flag'=>1,
    'created_date'=>date('Y-m-d H:i:s'),
    'created_by'=>$username,
    ]);


/*Insert user activity*/

$last_id=$id;

DB::table('tbl_mibl_user_activity')
->insert([
 'user_id' =>$user_id,
 'user_name'=>$username,
 'activity_group_id'=>$last_id,
 'messgage'=>'Auditor added successfully',
 'activity_type'=>'Insert',
 'activity_group'=>'Auditor',
 'created_date' => date('Y-m-d H:i:s'),
 ]);  
    session()->flash('successmsg', 'Auditor added successfully.');
    return redirect('view-auditor');
  }else
  {
    session()->flash('failmsg', 'Username already exists.');
    return redirect('view-auditor');
  }
} 



public function edit_auditor($id)
{

$id=base64_decode($id);  
$data = DB::table('tbl_mibl_auditor')
->select('*')
->where('id', '=', $id)
->get();

$user_type = DB::table('tbl_mibl_master_user_type')
  ->select('*')
  ->where('active_yn',0)
  ->get();

return view('/admin/edit_auditor', ['edit_services' => $data,'user_type'=>$user_type]);
}



public function update_auditor(Request $request)
{ 
$name = $request->input('name');
$email = $request->input('email');
$password_1 = $request->input('password');
$password = Hash::make($request->input('password'));
$pan_no = $request->input('pan_no');
$mobile_no = $request->input('mobile_no');
$address = $request->input('address');
$sap_code = $request->input('sap_code');
$active_yn = $request->input('active_yn');
$id = $request->input('id');


$data = DB::table('tbl_mibl_user')
  ->select('*')
  ->where('email',$request->input('email'))
  ->where('id','!=',$id)
  ->get();

if(count($data)== '0'){  

//Update Password
if(!empty($password_1))
{
DB::table('tbl_mibl_auditor')
->where('id',$id)
->update([
'password'=>$password,
]);
}

DB::table('tbl_mibl_auditor')
->where('id', $id)
->update([
'name'=>$name,
'email'=>$email,
'mobile_no'=>$mobile_no,
'address'=>$address,
'active_yn'=>$active_yn,
'sap_code'=>$sap_code
]);






 /*Insert user activity*/

 $user_id=session('id');
 $user = DB::table('tbl_mibl_user')
 ->select('*')
 ->where('deleted_at','=',0)
 ->where('id',$user_id)
 ->orderBy('id', 'desc')
 ->first();
 $username=$user->name;
 $last_id=$request->input('id');
 DB::table('tbl_mibl_user_activity')
 ->insert([
  'user_id' =>$user_id,
  'user_name'=>$username,
  'activity_group_id'=>$last_id,
  'messgage'=>'Auditor Updated successfully',
  'activity_type'=>'Updated',
  'activity_group'=>'Auditor',
  'created_date' => date('Y-m-d H:i:s'),
  ]);



session()->flash('successmsg', 'Auditor updated successfully.');
return redirect('view-auditor');
}else
{
  session()->flash('failmsg', 'Email or mobile no already exists.');
  return redirect('view-auditor');
}

}



public function view_auditor_creative(Request $request)
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


$document_types = DB::table('tbl_mibl_master_document_type')
->select('*')
->where('active_yn',0)
->get();




$from_date = (!empty($_GET["from_date"])) ? ($_GET["from_date"]) : ('');
$to_date = (!empty($_GET["to_date"])) ? ($_GET["to_date"]) : ('');
$vendor_name = (!empty($_GET["vendor_id"])) ? ($_GET["vendor_id"]) : ('');
$archive_category_ids = (!empty($_GET["archive_category_id"])) ? ($_GET["archive_category_id"]) : ('');
$department_id = (!empty($_GET["department_id"])) ? ($_GET["department_id"]) : ('');
$advertisement_id = (!empty($_GET["advertisement_id"])) ? ($_GET["advertisement_id"]) : ('');
$document_id = (!empty($_GET["document_id"])) ? ($_GET["document_id"]) : ('');
$type_of_creative = (!empty($_GET["type_of_creative"])) ? ($_GET["type_of_creative"]) : ('');


      $result=DB::table('tbl_mibl_creatives');
      $result->select('tbl_mibl_creatives.*','tbl_mibl_master_archive_category.name as archive_name','tbl_mibl_master_category.name as category_name',
         'tbl_mibl_master_brand.name as brand_name','tbl_mibl_master_vendor.name as vendor_name',
         'tbl_mibl_master_department.name as department_name','tbl_mibl_master_document_type.name as document_type_name',
         'tbl_mibl_master_archive_sub_category.name as archive_sub_category_name',
         'tbl_mibl_master_department_type.department_type_name as department_type_name',
         'tbl_mibl_master_vendor_type.vendor_type_name as vendor_type_name',
         'tbl_mibl_master_language.language as language');
      $result->leftJoin('tbl_mibl_master_document_type', 'tbl_mibl_master_document_type.id', '=', 'tbl_mibl_creatives.document_type_id');
      $result->leftJoin('tbl_mibl_master_archive_sub_category', 'tbl_mibl_master_archive_sub_category.id', '=', 'tbl_mibl_creatives.archive_sub_category_id');
      $result->leftJoin('tbl_mibl_master_department', 'tbl_mibl_master_department.id', '=', 'tbl_mibl_creatives.department_id');
      $result->leftJoin('tbl_mibl_master_vendor', 'tbl_mibl_master_vendor.id', '=', 'tbl_mibl_creatives.vendor_id');
      $result->leftJoin('tbl_mibl_master_brand', 'tbl_mibl_master_brand.id', '=', 'tbl_mibl_creatives.brand_id');
      $result->leftJoin('tbl_mibl_master_category', 'tbl_mibl_master_category.id', '=', 'tbl_mibl_creatives.category_id');
      $result->leftJoin('tbl_mibl_master_archive_category', 'tbl_mibl_master_archive_category.id', '=', 'tbl_mibl_creatives.archive_category_id');
      $result->leftJoin('tbl_mibl_master_department_type', 'tbl_mibl_master_department_type.id', '=', 'tbl_mibl_creatives.department_type_id');
      $result->leftJoin('tbl_mibl_master_vendor_type', 'tbl_mibl_master_vendor_type.id', '=', 'tbl_mibl_creatives.vendor_type_id');
      $result->leftJoin('tbl_mibl_master_language', 'tbl_mibl_master_language.id', '=', 'tbl_mibl_creatives.language_id');
      if (!empty($vendor_name)) {
        $arr_2=explode(",",$vendor_name);
        $vendor_type_id=$arr_2[0];
        $vendor_id=$arr_2[1];
        $result->where('tbl_mibl_master_vendor_type.id', '=',$vendor_type_id);
        if($vendor_id != 0){
        $result->where('tbl_mibl_master_vendor.id', '=',$vendor_id);
        }
      }
      
      if (!empty($advertisement_id)) {
        $result->where('tbl_mibl_creatives.advertisement_id', 'like', '%' .$advertisement_id. '%');
      }
      
      if (!empty($type_of_creative)) {
        $result->where('tbl_mibl_creatives.type_of_creative','=',$type_of_creative);
      }

      if (!empty($document_id)) {
        $result->where('tbl_mibl_creatives.document_type_id','=',$document_id);
      }
    
      if (!empty($archive_category_ids)) {
        $arr_3=explode(",",$archive_category_ids);
        @$archive_category_id=$arr_3[0];
        @$archive_category_sub_id=$arr_3[1];
        $result->where('tbl_mibl_master_archive_category.id', '=',$archive_category_id);
    
        if($archive_category_sub_id != 0 && $archive_category_sub_id != '')
        {
          $result->where('tbl_mibl_master_archive_sub_category.id', '=',$archive_category_sub_id);
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
    
      if(!empty($from_date) && !empty($to_date))
        {
            $from_date = date('Y-m', strtotime($from_date));
            $to_date = date('Y-m', strtotime($to_date));
            $fdate=explode("-",$from_date);
            $from_date1=$fdate[0]."".$fdate[1];
            $tdate=explode("-",$to_date);
            $to_date1=$tdate[0]."".$tdate[1];
            $result->whereRaw("DATE_FORMAT(tbl_mibl_creatives.date_of_posting, '%Y%m') >= '" . $from_date1 . "' AND DATE_FORMAT(tbl_mibl_creatives.date_of_posting, '%Y%m') <= '" . $to_date1 . "'");
    
        }
      $result->orderBy('id','DESC');
      $details=$result->paginate(5);


      return view('admin/view_auditor_creative',
      ['creatives'=>$details,
      'from_date'=>$from_date,
      'to_date'=>$to_date,
      'advertisement_id'=>$advertisement_id,
      'department_id'=>$department_id,
      'archive_category_id'=>$archive_category_ids,
      'vendor_id'=>$vendor_name,
      'archive_c'=>$archive_c,
      'department_c'=>$department_c,
      'vendor_c'=>$vendor_c,
      'document_types'=>$document_types,
      'document_id'=>$document_id,
      'type_of_creative'=>$type_of_creative]);

    }    


  
  
  
  

}