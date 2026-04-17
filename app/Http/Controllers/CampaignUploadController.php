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
use Carbon\Carbon;

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
use App\Campaign_Creatives;

class CampaignUploadController extends Controller
{

public function campaign_creatives_list(Request $request)
{

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



return view('/admin/campaign_creatives_list',
['vendor_c'=>$vendor_c]);
}


public function getcampaigncreatives(Request $request)
{

  //custom search 
  
  $vendor_name = trim((!empty($_GET["vendor_id"])) ? ($_GET["vendor_id"]) : (''));
  $from_date = (!empty($_GET["from_date"])) ? ($_GET["from_date"]) : ('');
  $to_date = (!empty($_GET["to_date"])) ? ($_GET["to_date"]) : ('');

  
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
    $totalRecords = Campaign_Creatives::select('count(*) as allcount')->count();
    $totalRecordswithFilter = Campaign_Creatives::select('count(*) as allcount')
  ->leftJoin('tbl_mibl_master_vendor', 'tbl_mibl_master_vendor.id', '=', 'tbl_mibl_campaign_creatives.vendor_id')
  ->where('tbl_mibl_campaign_creatives.name', 'like', '%' .$searchValue . '%')
  ->orWhere('tbl_mibl_master_vendor.name', 'like', '%' .$searchValue . '%')
  ->orWhere('tbl_mibl_campaign_creatives.created_date', 'like', '%' .$created_dated . '%')
  ->count();
  
    // Fetch records
    $records = Campaign_Creatives::orderBy($columnName,$columnSortOrder)
      ->where('tbl_mibl_campaign_creatives.created_date', 'like', '%' .$created_dated . '%')
      ->orWhere('tbl_mibl_master_vendor.name', 'like', '%' .$searchValue . '%')
      ->leftJoin('tbl_mibl_master_vendor', 'tbl_mibl_master_vendor.id', '=', 'tbl_mibl_campaign_creatives.vendor_id')
      ->select('tbl_mibl_campaign_creatives.*','tbl_mibl_master_vendor.name as vendor_name')
      ->skip($start)
      ->take($rowperpage)
      ->get();
  }else
  {
  
  // Total records
  $totalRecords = Campaign_Creatives::select('count(*) as allcount')->count();
  $result_Filter =Campaign_Creatives::select('count(*) as allcount');
  $result_Filter->leftJoin('tbl_mibl_master_vendor', 'tbl_mibl_master_vendor.id', '=', 'tbl_mibl_campaign_creatives.vendor_id');
  
  if (!empty($vendor_name)) {

    $arr_2=explode(",",$vendor_name);
    $vendor_type_id=$arr_2[0];
    $vendor_id=$arr_2[1];
    if($vendor_id != 0){
    $result_Filter->where('tbl_mibl_master_vendor.id', '=',$vendor_id);
    }
  }
  
  
  if(!empty($from_date) && !empty($to_date))
  {
  $from_date = date('Y-m-d', strtotime($from_date));
  $to_date = date('Y-m-d', strtotime($to_date));
  $result_Filter->whereRaw("date(tbl_mibl_campaign_creatives.created_date) >= '" . $from_date . "' AND date(tbl_mibl_campaign_creatives.created_date) <= '" . $to_date . "'");
  }



  $totalRecordswithFilter=$result_Filter->count();
  
  
  
  
  
  // Fetch records
  $result =Campaign_Creatives::orderBy($columnName,$columnSortOrder);
  
    if (!empty($vendor_name)) {
    $arr_2=explode(",",$vendor_name);
    $vendor_type_id=$arr_2[0];
    $vendor_id=$arr_2[1];
    if($vendor_id != 0){
    $result->where('tbl_mibl_master_vendor.id', '=',$vendor_id);
    }
    }
    
    if(!empty($from_date) && !empty($to_date))
    {
    $from_date = date('Y-m-d', strtotime($from_date));
    $to_date = date('Y-m-d', strtotime($to_date));
    $result->whereRaw("date(tbl_mibl_campaign_creatives.created_date) >= '" . $from_date . "' AND date(tbl_mibl_campaign_creatives.created_date) <= '" . $to_date . "'");
    }


  $result->leftJoin('tbl_mibl_master_vendor', 'tbl_mibl_master_vendor.id', '=', 'tbl_mibl_campaign_creatives.vendor_id');
  $result->select('tbl_mibl_campaign_creatives.*','tbl_mibl_master_vendor.name as vendor_name');
  $result->skip($start);
  $result->take($rowperpage);
  $records=$result->get(); 
  
  }
  //echo count($result);
  
    $data_arr = array();
    $i=1;
    foreach($records as $record){
       
       $id = $record->id;
  

       if($record->created_date)
       {
        $created_date= date("d/m/Y", strtotime($record->created_date));
       }else
       {
        $last_login_date='';
       }
       
       
        if($record->campaign_date)
        {
        $campaign_date= date("d/m/Y", strtotime($record->campaign_date));
        }else
        {
        $campaign_date='';
        }
       
        $APP_URL=$_ENV['APP_URL']."/".$record->campaign_file;
        $img="<img src='".$_ENV['APP_URL']."assets/images/download_icon.png' class='img-fluid tab-img'>";
        $download_link="<a href='".$APP_URL."' download>$img</a>"; 
        
        $vendor_name=$record->vendor_name;
        
      if(!empty($record->id))
       {
        $APP_URL=$_ENV['APP_URL']."edit-campaign-creatives/".base64_encode($record->id);
        $img="<img src='".$_ENV['APP_URL']."assets/img/edit.png' class='img-fluid tab-img'>";
        $edit_link="<a href='".$APP_URL."'>$img</a>";  
       }
  
  
  
       $data_arr[] = array(
         "id" =>$i,
         "campaign_name" =>$record->campaign_name,
         "vendor_id" =>$vendor_name,
         "campaign_date"=>$campaign_date,
         "campaign_file"=>$download_link,
         "created_date"=>$created_date,
         "action"=>$edit_link,
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
  
  
  
public function add_campaign_creatives(Request $request)
{
  $vendor = DB::table('tbl_mibl_master_vendor')
  ->select('*')
  ->where('active_yn',0)
  ->get();

  $vendor_type = DB::table('tbl_mibl_master_vendor_type')
  ->select('*')
//   ->where('flag',0)
  ->get();
  
  return view('/admin/add_campaign_creatives', ['vendor' => $vendor,'vendor_type'=>$vendor_type]);
}


public function insert_campaign_creatives(Request $request)
{

$vendor_id = $request->input('vendor_id');
$campaign_name = $request->input('campaign_name');
$campaign_date = $request->input('campaign_date');

$user_id=session('id');
$user = DB::table('tbl_mibl_user')
->select('*')
->where('deleted_at','=',0)
->where('id',$user_id)
->orderBy('id', 'desc')
->first();
$username=$user->name;

if ($request->file('campaign_file') != '') {
$characters = '0123456789abcdefghijklmnopqrstuvwxyz';
$charactersLength = strlen($characters);
$randomString = '';
for ($i = 0; $i < 18; $i++) {
$randomString .= $characters[rand(0, $charactersLength - 1)];
}

$file_name = $_FILES["campaign_file"]["name"];
$file_tmp = $_FILES["campaign_file"]["tmp_name"];
$ext = pathinfo($file_name, PATHINFO_EXTENSION);

if($ext != 'zip')
{
session()->flash('failmsg', 'Upload only .zip file');
return redirect('add-campaign-creatives');   
}

$campaign_name1=str_replace(" ","-",$campaign_name);
$campaign_name2=str_replace("/","-",$campaign_name1);

$random_file_name =$campaign_name2.'-'.$randomString . '.' . $ext;
$latest_member_image = 'uploads_campaign_creatives/' . $random_file_name;
$arr_data['campaign_file']=move_uploaded_file($file_tmp, env('BASE_PATH') . $latest_member_image);
}else
{
  $latest_member_image='';
}


$id=DB::table('tbl_mibl_campaign_creatives')->insertGetId([
    'vendor_id'=>$vendor_id,
    'campaign_name'=>$campaign_name,
    'campaign_date'=>$campaign_date,
    'campaign_file'=>$latest_member_image,
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
    'messgage'=>'Campaign creatives added successfully',
    'activity_type'=>'Insert',
    'activity_group'=>'Campaign creatives',
    'created_date' => date('Y-m-d H:i:s'),
    ]);  
    session()->flash('successmsg', 'Campaign creatives added successfully.');
    return redirect('add-campaign-creatives');
} 



public function edit_campaign_creatives($id)
{

$id=base64_decode($id);  
$data = DB::table('tbl_mibl_campaign_creatives')
->select('*')
->where('id', '=', $id)
->get();

$vendor = DB::table('tbl_mibl_master_vendor')
  ->select('*')
  ->where('active_yn',0)
  ->get();

  $vendor_type = DB::table('tbl_mibl_master_vendor_type')
  ->select('*')
//   ->where('flag',0)
  ->get();


return view('/admin/edit_campaign_creatives', ['vendor'=>$vendor,'vendor_type'=>$vendor_type,'edit_services' => $data]);
}


public function update_campaign_creatives(Request $request)
{ 

$vendor_id = $request->input('vendor_id');
$campaign_name = $request->input('campaign_name');
$campaign_date = $request->input('campaign_date');
$campaign_id=$request->input('id');

$user_id=session('id');
$user = DB::table('tbl_mibl_user')
->select('*')
->where('deleted_at','=',0)
->where('id',$user_id)
->orderBy('id', 'desc')
->first();
$username=$user->name;

if ($request->file('campaign_file') != '') {
    
$characters = '0123456789abcdefghijklmnopqrstuvwxyz';
$charactersLength = strlen($characters);
$randomString = '';
for ($i = 0; $i < 18; $i++) {
$randomString .= $characters[rand(0, $charactersLength - 1)];
}
$file_name = $_FILES["campaign_file"]["name"];
$file_tmp = $_FILES["campaign_file"]["tmp_name"];
$ext = pathinfo($file_name, PATHINFO_EXTENSION);
if($ext != 'zip')
{
session()->flash('failmsg', 'Upload only .zip file');
return redirect('campaign-creatives-list');   
}
$campaign_name1=str_replace(" ","-",$campaign_name);
$campaign_name2=str_replace("/","-",$campaign_name1);


$random_file_name =$campaign_name2.'-'.$randomString . '.' . $ext;
$latest_member_image = 'uploads_campaign_creatives/' . $random_file_name;
$arr_data['campaign_file']=move_uploaded_file($file_tmp, env('BASE_PATH') . $latest_member_image);

DB::table('tbl_mibl_campaign_creatives')
->where('id',$campaign_id)
->update(['campaign_file'=>$latest_member_image]);

}


DB::table('tbl_mibl_campaign_creatives')
->where('id', $campaign_id)
->update([
'vendor_id'=>$vendor_id,
'campaign_name'=>$campaign_name,
'campaign_date'=>$campaign_date,
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
  'messgage'=>'Campaign creatives Updated successfully',
  'activity_type'=>'Updated',
  'activity_group'=>'Campaign creatives',
  'created_date' => date('Y-m-d H:i:s'),
  ]);


session()->flash('successmsg', 'Campaign creatives updated successfully.');
return redirect('campaign-creatives-list');


}





    
}
    
