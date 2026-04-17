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

class MiscellaneousController extends Controller
{
    function add_single_file_upload_vendor_miscellaneous()
    {
        //Archive category type and Archive sub category

        $archive_category = DB::table("tbl_mibl_master_archive_category")
            ->select("*")
            ->where("active_yn", 0)
            ->orderBy("name", "ASC")
            ->get();
        $archive_c = [];
        foreach ($archive_category as $archivecategory) {
            $sub_cat = [];
            $data = DB::table("tbl_mibl_master_archive_sub_category")
                ->where("archive_category_id", $archivecategory->id)
                ->where("active_yn", 0)
                ->orderBy("name", "ASC")
                ->get();
            if (count($data) > 0) {
                foreach ($data as $dat) {
                    $sub_cat[] = [
                        "sub_category_id" => $dat->id,
                        "sub_category" => $dat->name,
                    ];
                }
            } else {
                $sub_cat[] = [
                    "sub_category_id" => 0,
                    "sub_category" => $archivecategory->name,
                ];
            }

            $archive_c[] = [
                "archive_category_id" => $archivecategory->id,
                "archive_category" => $archivecategory->name,
                "sub_list" => $sub_cat,
            ];
        }

        //department type and department
        $department = DB::table("tbl_mibl_master_department_type")
            ->select("*")
            ->where("active_yn", 0)
            ->orderBy("department_type_name", "ASC")
            ->get();
        $department_c = [];
        foreach ($department as $department_type) {
            $sub_depart = [];
            $data = DB::table("tbl_mibl_master_department")
                ->where("department_type_id", $department_type->id)
                ->where("active_yn", 0)
                ->orderBy("name", "ASC")
                ->get();
            if (count($data) > 0) {
                foreach ($data as $dat) {
                    $sub_depart[] = [
                        "department_id" => $dat->id,
                        "department_name" => $dat->name,
                    ];
                }
            } else {
                $sub_depart[] = [
                    "department_id" => 0,
                    "department_name" => $department_type->department_type_name,
                ];
            }

            $department_c[] = [
                "department_type_id" => $department_type->id,
                "department_type_name" =>
                    $department_type->department_type_name,
                "department_list" => $sub_depart,
            ];
        }

        //Vendor type and vendor

        $vendor = DB::table("tbl_mibl_master_vendor_type")
            ->select("*")
            ->where("active_yn", 0)
            ->orderBy("vendor_type_name", "ASC")
            ->get();

        $vendor_c = [];
        foreach ($vendor as $vendor_type) {
            $sub_vendor = [];
            $data = DB::table("tbl_mibl_master_vendor")
                ->where("vendor_type_id", $vendor_type->id)
                ->where("active_yn", 0)
                ->where("flag", 1)
                ->orderBy("name", "ASC")
                ->get();
            if (count($data) > 0) {
                foreach ($data as $dat) {
                    $sub_vendor[] = [
                        "vendor_id" => $dat->id,
                        "vendor_name" => $dat->name,
                    ];
                }
            } else {
                $sub_vendor[] = [
                    "vendor_id" => 0,
                    "vendor_name" => $vendor_type->vendor_type_name,
                ];
            }

            $vendor_c[] = [
                "vendor_type_id" => $vendor_type->id,
                "vendor_type_name" => $vendor_type->vendor_type_name,
                "vendor_list" => $sub_vendor,
            ];
        }

        $category = DB::table("tbl_mibl_master_category")
            ->select("*")
            ->where("active_yn", 0)
            ->get();

        $document_type = DB::table("tbl_mibl_master_document_type")
            ->select("*")
            ->where("active_yn", 0)
            ->get();

        $brand = DB::table("tbl_mibl_master_brand")
            ->select("*")
            ->where("active_yn", 0)
            ->get();

        $language = DB::table("tbl_mibl_master_language")
            ->select("*")
            ->where("active_yn", 0)
            ->get();

        $vendor_ids = session("id");
        $advertisement_id_list = DB::table("tbl_mibl_advertisement_id")
            ->select("*")
            ->where("flag", 0)
            ->where("vendor_id", $vendor_ids)
            ->get();

        return view("miscellaneous/add_single_file_upload_vendor_miscellaneous", [
            "category_list" => $category,
            "document_type_list" => $document_type,
            "brand_list" => $brand,
            "archive_c" => $archive_c,
            "department_c" => $department_c,
            "vendor_c" => $vendor_c,
            "languages" => $language,
            "advertisement_id_list" => $advertisement_id_list,
        ]);
    }
    
    
    
    
    
function insert_single_file_upload_vendor_miscellaneous(Request $request)
 {

  
   if (( $_FILES["photo"]["size"] <= 200000000 )) {
  
    $date_of_posting = date('Y-m', strtotime($request->input('date_of_posting')));

     # create directory of Year
     $year1=date("Y", strtotime($request->input('date_of_posting')));
     $year = "upload_vendor/".$year1;
     # create directory if not exists in upload/ directory
     if(!is_dir($year)){
       mkdir($year, 0777);
     }
    
      # create directory of Month
      $month1=date("m", strtotime($request->input('date_of_posting')));
      $month = "upload_vendor/".$year1."/".$month1;

      $month_new = "upload_vendor/".$year1."/".$month1;

      # create directory if not exists in upload/ directory
      if(!is_dir($month)){
        mkdir($month, 0777);
      }
      
      # create directory of Thumbnail
      $name_thumbnail='thumbnail';
      $name_thumbnail = "upload_vendor/".$year1."/".$month1."/".$name_thumbnail;
      # create directory if not exists in upload/ directory
      if(!is_dir($name_thumbnail)){
        mkdir($name_thumbnail, 0777);
      }
      
      # create directory of Preview
      $name_preview='preview';
      $name_preview = "upload_vendor/".$year1."/".$month1."/".$name_preview;
      # create directory if not exists in upload/ directory
      if(!is_dir($name_preview)){
        mkdir($name_preview, 0777);
      }
      
      # create directory of Original
      $name_original='original';
      $name_original = "upload_vendor/".$year1."/".$month1."/".$name_original;
      # create directory if not exists in upload/ directory
      if(!is_dir($name_original)){
        mkdir($name_original, 0777);
      }
      # create directory of Original
      $name_upload_source_file='upload_source_file';
      $name_upload_source_file = "upload_vendor/".$year1."/".$month1."/".$name_upload_source_file;
      # create directory if not exists in upload/ directory
      if(!is_dir($name_upload_source_file)){
        mkdir($name_upload_source_file, 0777);
      }


if(isset($_FILES['photo'])) 
  {
      $mime = $_FILES['photo']['type'];
      $image=$request->file('photo');
      $filename=$image->getClientOriginalName();  

      $data = DB::table('tbl_mibl_creatives')
      ->select('*')
      ->where('photo_url',$filename)
      ->get();
      
      if(count($data)== '0'){  
        $filename_new= $filename;
      }else
      {
        $characters='0123456789abcdefghijklmnopqrstuvwxyz';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < 18; $i++) {
        $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
        $file_name = $_FILES["photo"]["name"];
        $file_tmp = $_FILES["photo"]["tmp_name"];
        $ext = pathinfo($file_name, PATHINFO_EXTENSION);
        $filename = $randomString . '.' . $ext;
        $filename_new = $filename;
      }

   if(strstr($mime, "image/"))
   {
       
       $filetype = "image";
       $image=$request->file('photo');
       $image_info = getimagesize($image);
     
       $original_width=$image_info[0];
       $original_height=$image_info[1];
       $ratio = 1.0;
       $scaled = false;
     
       // FIXME size should be configurable thumbnail
       if ($original_width > 200) {
         $ratio = 200 / $original_width;
         $width_t = $original_width * $ratio;
         $height_t = $original_height * $ratio;
         $scaled = true;
       } else {
         $width_t = $original_width;
         $height_t = $original_height;
       }
     
       if ($height_t > 200) {
         $ratio = 200 / $original_height;
         $width_t = $original_width * $ratio;
         $height_t = $original_height * $ratio;
         $scaled = true;
         } 
         
      // FIXME size should be configurable Preview
       if ($original_width > 500) {
        $ratio = 500 / $original_width;
        $width_p = $original_width * $ratio;
        $height_p = $original_height * $ratio;
        $scaled = true;
      } else {
        $width_p = $original_width;
        $height_p = $original_height;
      }
    
      if ($height_p > 500) {
        $ratio = 500 / $original_height;
        $width_p = $original_width * $ratio;
        $height_p = $original_height * $ratio;
        $scaled = true;
        }  
     

        if($request->hasFile('photo')) {
        $image       = $request->file('photo');
        //$filename    = $image->getClientOriginalName();
        $filename=$filename_new;
        $image_resize = Image::make($image->getRealPath());
        $image_resize->resize($width_t, $height_t);
        $image_resize->save($name_thumbnail.'/' .$filename);
        }

        if($request->hasFile('photo')) {
        $image       = $request->file('photo');
        //$filename    = $image->getClientOriginalName();
        $filename=$filename_new;
        $image_resize = Image::make($image->getRealPath());
        $image_resize->resize($width_p, $height_p);
        $image_resize->save($name_preview.'/' .$filename);
        }

        if($request->hasFile('photo')) {
        $image     = $request->file('photo');
        //$filename  = $image->getClientOriginalName();
        $filename=$filename_new;
        $file_name = $_FILES["photo"]["name"];
        $file_tmp  = $_FILES["photo"]["tmp_name"];
        $filename2 = $name_original.'/'.$filename;
        $arr_data['photo']=move_uploaded_file($file_tmp, env('BASE_PATH') . $filename2);
        }

        if ($request->file('source_file') != '') {

          $image=$request->file('source_file');
          $filenamesource_file  = $image->getClientOriginalName();

          $data = DB::table('tbl_mibl_creatives')
          ->select('*')
          ->where('source_file',$filenamesource_file)
          ->get();

          if(count($data)== '0'){  
          $filename_sourcefile= $filenamesource_file;
          }else
          {
          $characters='0123456789abcdefghijklmnopqrstuvwxyz';
          $charactersLength = strlen($characters);
          $randomString = '';
          for ($i = 0; $i < 18; $i++) {
          $randomString .= $characters[rand(0, $charactersLength - 1)];
          }
          $file_name = $_FILES["source_file"]["name"];
          $file_tmp = $_FILES["source_file"]["tmp_name"];
          $ext = pathinfo($file_name, PATHINFO_EXTENSION);
          $filenamesource_file = $randomString . '.' . $ext;
          $filename_sourcefile = $filenamesource_file;
          }
          $filename1=$filename_sourcefile;


          
          // $image     = $request->file('source_file');
          // $filename1  = $image->getClientOriginalName();
          $file_name = $_FILES["source_file"]["name"];
          $file_tmp  = $_FILES["source_file"]["tmp_name"];
          $filename_n = $name_upload_source_file.'/'.$filename1;
          $arr_data['source_file']=move_uploaded_file($file_tmp, env('BASE_PATH') . $filename_n);
          }else
          {
            $filename1='';
          }

    }else
      {
            $filetype='other'; 
            $image     = $request->file('photo');
            //$filename  = $image->getClientOriginalName();
            $filename=$filename_new;
            $file_name = $_FILES["photo"]["name"];
            $file_tmp  = $_FILES["photo"]["tmp_name"];
            $filename_ne  = $month_new.'/'.$filename;
            $arr_data['photo']=move_uploaded_file($file_tmp, env('BASE_PATH') . $filename_ne);


          if ($request->file('source_file') != '') {

                $image=$request->file('source_file');
                $filenamesource_file  = $image->getClientOriginalName();

                $data = DB::table('tbl_mibl_creatives')
                ->select('*')
                ->where('source_file',$filenamesource_file)
                ->get();

                if(count($data)== '0'){  
                $filename_sourcefile= $filenamesource_file;
                }else
                {
                $characters='0123456789abcdefghijklmnopqrstuvwxyz';
                $charactersLength = strlen($characters);
                $randomString = '';
                for ($i = 0; $i < 18; $i++) {
                $randomString .= $characters[rand(0, $charactersLength - 1)];
                }
                $file_name = $_FILES["source_file"]["name"];
                $file_tmp = $_FILES["source_file"]["tmp_name"];
                $ext = pathinfo($file_name, PATHINFO_EXTENSION);
                $filenamesource_file = $randomString . '.' . $ext;
                $filename_sourcefile = $filenamesource_file;
                }
                $filename1=$filename_sourcefile;

            
            
            $file_name = $_FILES["source_file"]["name"];
            $file_tmp  = $_FILES["source_file"]["tmp_name"];
            $filename_n = $name_upload_source_file.'/'.$filename1;
            $arr_data['source_file']=move_uploaded_file($file_tmp, env('BASE_PATH') . $filename_n);
            }else
            {
              $filename1='';
            }
      }       
   }else
    {
      $filename1=''; 
      $filename=''; 
      $filetype=''; 
    }   
    
 
$user_id=session('id');
$user = DB::table('tbl_mibl_master_vendor')
->select('*')
->where('deleted_at','=',0)
->where('id',$user_id)
->orderBy('id', 'desc')
->first();
$username=$user->name;



$file_name = $request->input('file_name');
$advertisement_id= "Miscellaneous";
$file_description = $request->input('file_description');
$brand_id = $request->input('brand_id');
$document_type_id = $request->input('document_type_id');
$date_of_posting =$date_of_posting."-01";
$date_of_upload=$request->input('date_of_upload');
$other_document_type=$request->input('other_document_type');
$vendor_type_id=$request->input('vendor_type_id');
$irdai_date=$request->input('irdai_date');
$irdai_addressed=$request->input('irdai_addressed');
$remark=$request->input('remark');
$photo_url = $filename;
$source_file = $filename1;
$filetype = $filetype;
$vendor_type_id=$user->vendor_type_id;
$vendor_id=$user->id;

$last_id=DB::table('tbl_mibl_creatives_vendor')->insertGetId([
  'file_name'=>$file_name,
  'advertisement_id'=>$advertisement_id,
  'file_description'=>$file_description,
  'brand_id'=>$brand_id,
  'document_type_id'=>$document_type_id,
  'vendor_id'=>$vendor_id,
  'date_of_posting'=>$date_of_posting,
  'date_of_upload'=>date('Y-m-d'),
  'other_document_type'=>$other_document_type,
  'photo_url'=>$photo_url,
  'source_file'=>$source_file,
  'file_type'=>$filetype,
  'vendor_type_id'=>$vendor_type_id,
  'remark'=>$remark,
  'type_of_creative'=>"miscellaneous",
  'created_date'=>date('Y-m-d H:i:s'),
  'created_by'=>$username,
  ]);


  /*Insert user activity*/

  DB::table('tbl_mibl_user_activity')
  ->insert([
  'user_id' =>$user_id,
  'user_name'=>$username,
  'activity_group_id'=>$last_id,
  'messgage'=>'Vendor single miscellaneous creative upload added successfully',
  'activity_type'=>'Insert',
  'activity_group'=>'Vendor Single miscellaneous creative upload',
  'created_date' => date('Y-m-d H:i:s'),
  ]);  

    DB::table('tbl_mibl_advertisement_id')
    ->where('advertisement_id', $advertisement_id)
    ->update([
    'flag'=>1,
    ]);




//====================Notification Email Code Start=============


//$email_id="UPADHYAY.KRUTI@Mahindra.com";
$email_id="priyanka.surti@evonix.co";
$employee= DB::table('tbl_mibl_user')
->select('*')
->where('deleted_at','=',0)
->where('email',$email_id)
->first();
$employee_name=!empty($employee->name)?$employee->name:'';
$employee_id=!empty($employee->id)?$employee->id:'';

$subject="MBank: Vendor ".$username." has uploaded miscellaneous creative";
$message="
Dear User,
".$username." has uploaded final source file of ".$file_name." on MBank.
Request you to please approve the same.

Sincerely,
MBank";
$url="https://eapi.instaalerts.zone/email?uname=MIBL_ITmail&pass=bQ@8ajbv&fromName=MBank&fromEmail=info@MAHINDRAINSURANCE.COM&toEmail=".urlencode($email_id)."&subject=".urlencode($subject)."&msgPlain=".urlencode($message);
$ch = curl_init($url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
$curl_scraped_page = curl_exec($ch);
curl_close($ch);


//Insert Noification 

$messages="
Dear User,<br>
".$username." has uploaded final source file of ".$file_name." on MBank.<br>
Request you to please approve the same.<br><br>

Sincerely,<br>
MBank";
DB::table('tbl_mibl_notification')
->insert([
'employee_id' =>$employee_id,
'vendor_id'=>$user_id,
'subject'=>$subject,
'message'=>$messages,
'type'=>'Upload',
'send_by'=>$username,
'send_date' =>date('Y-m-d'),
'read_status' =>0,
]);  



//=============== Notification Email Code End =============


  session()->flash('successmsg', 'Creative added successfully.');
  return redirect('/add-single-file-upload-vendor-miscellaneous');
}else

{
  session()->flash('failmsg', 'Kindly upload file upto size 200MB.');
  return redirect('/add-single-file-upload-vendor-miscellaneous');
    
}
       
}




public function view_creative_vendor_miscellaneous(Request $request)
{


  $document_type = DB::table('tbl_mibl_master_document_type')
  ->select('*')
  ->where('active_yn',0)
  ->get();


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



  return view('miscellaneous/view_creative_vendor_miscellaneous',
  ['archive_c'=>$archive_c,
  'department_c'=>$department_c,
  'document_type_list'=>$document_type,
  'vendor_c'=>$vendor_c]);
  }
  
  
  
  
  
  

public function getcreatives_vendor_miscellaneous(Request $request){


  //custom search 
  
  $vendor_name = (!empty($_GET["vendor_id"])) ? ($_GET["vendor_id"]) : ('');
  $advertisement_id = (!empty($_GET["advertisement_id"])) ? ($_GET["advertisement_id"]) : ('');
  $archive_category_id = (!empty($_GET["archive_category_id"])) ? ($_GET["archive_category_id"]) : ('');
  $department_id = (!empty($_GET["department_id"])) ? ($_GET["department_id"]) : ('');
  $from_date = (!empty($_GET["from_date"])) ? ($_GET["from_date"]) : ('');
  $to_date = (!empty($_GET["to_date"])) ? ($_GET["to_date"]) : ('');


  $vendor_idd=session('id');
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
    @$searchValue = $search_arr['value']; // Search value
  
    @$searchValue1 = $search_arr['value']; // Search value
    
    if(Str::upper(@$searchValue1) == 'ACTIVE')
    {
    $status='0';
    }else if(Str::upper(@$searchValue1) == 'INACTIVE')
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


  if(!empty($searchValue1))
  {
    $end_date = date('Y-m-d');
    $start_date = date("Y-m-d", strtotime("-3 years"));
  // Total records
    $totalRecords = Creative_vendor::select('count(*) as allcount')->count();
    $totalRecordswithFilter = Creative_vendor::select('count(*) as allcount')
  ->leftJoin('tbl_mibl_master_archive_category', 'tbl_mibl_master_archive_category.id', '=', 'tbl_mibl_creatives_vendor.archive_category_id')
  ->leftJoin('tbl_mibl_master_archive_sub_category', 'tbl_mibl_master_archive_sub_category.id', '=', 'tbl_mibl_creatives_vendor.archive_sub_category_id')
  ->leftJoin('tbl_mibl_master_category', 'tbl_mibl_master_category.id', '=', 'tbl_mibl_creatives_vendor.category_id')
  ->leftJoin('tbl_mibl_master_brand', 'tbl_mibl_master_brand.id', '=', 'tbl_mibl_creatives_vendor.brand_id')
  ->leftJoin('tbl_mibl_master_vendor', 'tbl_mibl_master_vendor.id', '=', 'tbl_mibl_creatives_vendor.vendor_id')
  ->leftJoin('tbl_mibl_master_department', 'tbl_mibl_master_department.id', '=', 'tbl_mibl_creatives_vendor.department_id')
  ->leftJoin('tbl_mibl_master_document_type', 'tbl_mibl_master_document_type.id', '=', 'tbl_mibl_creatives_vendor.document_type_id')
  ->leftJoin('tbl_mibl_master_department_type', 'tbl_mibl_master_department_type.id', '=', 'tbl_mibl_creatives_vendor.department_type_id')
  ->leftJoin('tbl_mibl_master_vendor_type', 'tbl_mibl_master_vendor_type.id', '=', 'tbl_mibl_creatives_vendor.vendor_type_id')
  ->where('tbl_mibl_master_vendor.id', '=',$vendor_idd)
  ->where('tbl_mibl_creatives_vendor.type_of_creative', '=','miscellaneous')
  ->where('tbl_mibl_master_vendor.active_yn', '=','0')
  ->whereRaw("date(tbl_mibl_creatives_vendor.created_date) >= '" . $start_date . "' AND date(tbl_mibl_creatives_vendor.created_date) <= '" . $end_date . "'")
  ->where(function ($query) use ($searchValue,$status,$start_date,$end_date,$created_dated){
    $query ->where('tbl_mibl_creatives_vendor.file_name', 'like', '%' .$searchValue . '%')
    ->orWhere('tbl_mibl_master_archive_category.name', 'like', '%' .$searchValue . '%')
    ->orWhere('tbl_mibl_master_category.name', 'like', '%' .$searchValue . '%')
    ->orWhere('tbl_mibl_master_department_type.department_type_name', 'like', '%' .$searchValue . '%')
    ->orWhere('tbl_mibl_master_archive_sub_category.name', 'like', '%' .$searchValue . '%')
    ->orWhere('tbl_mibl_master_vendor_type.vendor_type_name', 'like', '%' .$searchValue . '%')
    ->orWhere('tbl_mibl_master_brand.name', 'like', '%' .$searchValue . '%')
    ->orWhere('tbl_mibl_master_vendor.name', 'like', '%' .$searchValue . '%')
    ->orWhere('tbl_mibl_master_department.name', 'like', '%' .$searchValue . '%')
    ->orWhere('tbl_mibl_master_document_type.name', 'like', '%' .$searchValue . '%')
    ->orWhere('tbl_mibl_creatives_vendor.active_yn', 'like', '%' .$status . '%')
    ->orWhere('tbl_mibl_creatives_vendor.created_date', 'like', '%' .$created_dated. '%');
   })
  ->count();
  
    // Fetch records
    $records = Creative_vendor::orderBy($columnName,$columnSortOrder)
     /* ->where('tbl_mibl_creatives_vendor.file_name', 'like', '%' .$searchValue . '%')
      ->orWhere('tbl_mibl_creatives_vendor.active_yn', 'like', '%' .$status . '%')
      ->orWhere('tbl_mibl_master_department_type.department_type_name', 'like', '%' .$searchValue . '%')
      ->orWhere('tbl_mibl_master_archive_sub_category.name', 'like', '%' .$searchValue . '%')
      ->orWhere('tbl_mibl_master_vendor_type.vendor_type_name', 'like', '%' .$searchValue . '%')
      ->orWhere('tbl_mibl_master_archive_category.name', 'like', '%' .$searchValue . '%')
      ->orWhere('tbl_mibl_master_category.name', 'like', '%' .$searchValue . '%')
      ->orWhere('tbl_mibl_master_brand.name', 'like', '%' .$searchValue . '%')
      ->orWhere('tbl_mibl_master_vendor.name', 'like', '%' .$searchValue . '%')
      ->orWhere('tbl_mibl_master_department.name', 'like', '%' .$searchValue . '%')
      ->orWhere('tbl_mibl_master_document_type.name', 'like', '%' .$searchValue . '%')
      ->orWhere('tbl_mibl_creatives_vendor.created_date', 'like', '%' .$searchValue. '%')
      ->whereRaw("date(tbl_mibl_creatives_vendor.created_date) >= '" . $start_date . "' AND date(tbl_mibl_creatives_vendor.created_date) <= '" . $end_date . "'")
      */
      ->where('tbl_mibl_creatives_vendor.type_of_creative', '=','miscellaneous')
      ->where('tbl_mibl_master_vendor.id', '=',$vendor_idd)
      ->where('tbl_mibl_master_vendor.active_yn', '=','0')
      ->whereRaw("date(tbl_mibl_creatives_vendor.created_date) >= '" . $start_date . "' AND date(tbl_mibl_creatives_vendor.created_date) <= '" . $end_date . "'")
      ->where(function ($query) use ($searchValue,$status,$start_date,$end_date,$created_dated){
      $query ->where('tbl_mibl_creatives_vendor.file_name', 'like', '%' .$searchValue . '%')
      ->orWhere('tbl_mibl_master_archive_category.name', 'like', '%' .$searchValue . '%')
      ->orWhere('tbl_mibl_master_category.name', 'like', '%' .$searchValue . '%')
      ->orWhere('tbl_mibl_master_department_type.department_type_name', 'like', '%' .$searchValue . '%')
      ->orWhere('tbl_mibl_master_archive_sub_category.name', 'like', '%' .$searchValue . '%')
      ->orWhere('tbl_mibl_master_vendor_type.vendor_type_name', 'like', '%' .$searchValue . '%')
      ->orWhere('tbl_mibl_master_brand.name', 'like', '%' .$searchValue . '%')
      ->orWhere('tbl_mibl_master_vendor.name', 'like', '%' .$searchValue . '%')
      ->orWhere('tbl_mibl_master_department.name', 'like', '%' .$searchValue . '%')
      ->orWhere('tbl_mibl_master_document_type.name', 'like', '%' .$searchValue . '%')
      ->orWhere('tbl_mibl_creatives_vendor.active_yn', 'like', '%' .$status . '%')
      ->orWhere('tbl_mibl_creatives_vendor.created_date', 'like', '%' .$created_dated. '%');
      })
      ->leftJoin('tbl_mibl_master_document_type', 'tbl_mibl_master_document_type.id', '=', 'tbl_mibl_creatives_vendor.document_type_id')
      ->leftJoin('tbl_mibl_master_archive_sub_category', 'tbl_mibl_master_archive_sub_category.id', '=', 'tbl_mibl_creatives_vendor.archive_sub_category_id')
      ->leftJoin('tbl_mibl_master_department', 'tbl_mibl_master_department.id', '=', 'tbl_mibl_creatives_vendor.department_id')
      ->leftJoin('tbl_mibl_master_vendor', 'tbl_mibl_master_vendor.id', '=', 'tbl_mibl_creatives_vendor.vendor_id')
      ->leftJoin('tbl_mibl_master_brand', 'tbl_mibl_master_brand.id', '=', 'tbl_mibl_creatives_vendor.brand_id')
      ->leftJoin('tbl_mibl_master_category', 'tbl_mibl_master_category.id', '=', 'tbl_mibl_creatives_vendor.category_id')
      ->leftJoin('tbl_mibl_master_archive_category', 'tbl_mibl_master_archive_category.id', '=', 'tbl_mibl_creatives_vendor.archive_category_id')
      ->leftJoin('tbl_mibl_master_department_type', 'tbl_mibl_master_department_type.id', '=', 'tbl_mibl_creatives_vendor.department_type_id')
      ->leftJoin('tbl_mibl_master_vendor_type', 'tbl_mibl_master_vendor_type.id', '=', 'tbl_mibl_creatives_vendor.vendor_type_id')
      ->select('tbl_mibl_creatives_vendor.*','tbl_mibl_master_archive_category.name as archive_name','tbl_mibl_master_category.name as category_name',
         'tbl_mibl_master_brand.name as brand_name','tbl_mibl_master_vendor.name as vendor_name',
         'tbl_mibl_master_department.name as department_name','tbl_mibl_master_document_type.name as document_type_name',
         'tbl_mibl_master_archive_sub_category.name as archive_sub_category_name',
         'tbl_mibl_master_department_type.department_type_name as department_type_name',
         'tbl_mibl_master_vendor_type.vendor_type_name as vendor_type_name')
      ->skip($start)
      ->take($rowperpage)
      ->get();
  }else
  {
  
  // Total records
  $totalRecords = Creative_vendor::select('count(*) as allcount')->count();
  $result_Filter =Creative_vendor::select('count(*) as allcount');
  $result_Filter->leftJoin('tbl_mibl_master_document_type', 'tbl_mibl_master_document_type.id', '=', 'tbl_mibl_creatives_vendor.document_type_id');
  $result_Filter->leftJoin('tbl_mibl_master_department', 'tbl_mibl_master_department.id', '=', 'tbl_mibl_creatives_vendor.department_id');
  $result_Filter->leftJoin('tbl_mibl_master_vendor', 'tbl_mibl_master_vendor.id', '=', 'tbl_mibl_creatives_vendor.vendor_id');
  $result_Filter->leftJoin('tbl_mibl_master_brand', 'tbl_mibl_master_brand.id', '=', 'tbl_mibl_creatives_vendor.brand_id');
  $result_Filter->leftJoin('tbl_mibl_master_category', 'tbl_mibl_master_category.id', '=', 'tbl_mibl_creatives_vendor.category_id');
  $result_Filter->leftJoin('tbl_mibl_master_archive_category', 'tbl_mibl_master_archive_category.id', '=', 'tbl_mibl_creatives_vendor.archive_category_id');
  $result_Filter->leftJoin('tbl_mibl_master_archive_sub_category', 'tbl_mibl_master_archive_sub_category.id', '=', 'tbl_mibl_creatives_vendor.archive_sub_category_id');
  $result_Filter->leftJoin('tbl_mibl_master_department_type', 'tbl_mibl_master_department_type.id', '=', 'tbl_mibl_creatives_vendor.department_type_id');
  $result_Filter->leftJoin('tbl_mibl_master_vendor_type', 'tbl_mibl_master_vendor_type.id', '=', 'tbl_mibl_creatives_vendor.vendor_type_id');
  $result_Filter->where('tbl_mibl_master_vendor.id', '=',$vendor_idd);
  $result_Filter->where('tbl_mibl_creatives_vendor.type_of_creative', '=','miscellaneous');
  if (!empty($vendor_name)) {

    $arr_2=explode(",",$vendor_name);
    $vendor_type_id=$arr_2[0];
    $vendor_id=$arr_2[1];
    $result_Filter->where('tbl_mibl_master_vendor_type.id', '=',$vendor_type_id);
    if($vendor_id != 0){
    $result_Filter->where('tbl_mibl_master_vendor.id', '=',$vendor_id);
    }
  }
  if (!empty($advertisement_id)) {
    $result_Filter->where('tbl_mibl_creatives_vendor.advertisement_id', 'like', '%' .$advertisement_id. '%');
  }
  if (!empty($archive_category_id)) {

    $arr_2=explode(",",$archive_category_id);
    @$archive_category_id=$arr_2[0];
    @$archive_category_sub_id=$arr_2[1];
    $result_Filter->where('tbl_mibl_master_archive_category.id', '=',$archive_category_id);

    if($archive_category_sub_id != 0 || $archive_category_sub_id != '')
    {
      $result_Filter->where('tbl_mibl_master_archive_sub_category.id', '=',$archive_category_sub_id);
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
    $from_date = date('Y-m', strtotime($from_date));
    $to_date = date('Y-m', strtotime($to_date));
    $fdate=explode("-",$from_date);
    $from_date1=$fdate[0]."".$fdate[1];
    $tdate=explode("-",$to_date);
    $to_date1=$tdate[0]."".$tdate[1];
    $result_Filter->whereRaw("DATE_FORMAT(tbl_mibl_creatives_vendor.date_of_posting, '%Y%m') >= '" . $from_date1 . "' AND DATE_FORMAT(tbl_mibl_creatives_vendor.date_of_posting, '%Y%m') <= '" . $to_date1 . "'");

    }
    
    /*else
    { 
    $to_date = date('Y-m');
    $from_date = date("Y-m", strtotime("-3 years"));
    $fdate=explode("-",$from_date);
    $from_date1=$fdate[0]."".$fdate[1];
    $tdate=explode("-",$to_date);
    $to_date1=$tdate[0]."".$tdate[1];
    $result_Filter->whereRaw("DATE_FORMAT(tbl_mibl_creatives_vendor.date_of_posting, '%Y%m') >= '" . $from_date1 . "' AND DATE_FORMAT(tbl_mibl_creatives_vendor.date_of_posting, '%Y%m') <= '" . $to_date1 . "'");
    }*/



  $totalRecordswithFilter=$result_Filter->count();
  
  
  
  
  
  // Fetch records
  $result =Creative_vendor::orderBy($columnName,$columnSortOrder);
  
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
    $result->where('tbl_mibl_creatives_vendor.advertisement_id', 'like', '%' .$advertisement_id. '%');
  }

  if (!empty($archive_category_id)) {
    $arr_3=explode(",",$archive_category_id);
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
        $result->whereRaw("DATE_FORMAT(tbl_mibl_creatives_vendor.date_of_posting, '%Y%m') >= '" . $from_date1 . "' AND DATE_FORMAT(tbl_mibl_creatives_vendor.date_of_posting, '%Y%m') <= '" . $to_date1 . "'");

    }
    
    /*else
    { 
        $to_date = date('Y-m');
        $from_date = date("Y-m", strtotime("-3 years"));
        $fdate=explode("-",$from_date);
        $from_date1=$fdate[0]."".$fdate[1];
        $tdate=explode("-",$to_date);
        $to_date1=$tdate[0]."".$tdate[1];
        $result->whereRaw("DATE_FORMAT(tbl_mibl_creatives_vendor.date_of_posting, '%Y%m') >= '" . $from_date1 . "' AND DATE_FORMAT(tbl_mibl_creatives_vendor.date_of_posting, '%Y%m') <= '" . $to_date1 . "'");
    }*/

  
  $result->where('tbl_mibl_creatives_vendor.type_of_creative', '=','miscellaneous');
  $result->where('tbl_mibl_master_vendor.id', '=',$vendor_idd);
  $result->leftJoin('tbl_mibl_master_document_type', 'tbl_mibl_master_document_type.id', '=', 'tbl_mibl_creatives_vendor.document_type_id');
  $result->leftJoin('tbl_mibl_master_department', 'tbl_mibl_master_department.id', '=', 'tbl_mibl_creatives_vendor.department_id');
  $result->leftJoin('tbl_mibl_master_vendor', 'tbl_mibl_master_vendor.id', '=', 'tbl_mibl_creatives_vendor.vendor_id');
  $result->leftJoin('tbl_mibl_master_brand', 'tbl_mibl_master_brand.id', '=', 'tbl_mibl_creatives_vendor.brand_id');
  $result->leftJoin('tbl_mibl_master_category', 'tbl_mibl_master_category.id', '=', 'tbl_mibl_creatives_vendor.category_id');
  $result->leftJoin('tbl_mibl_master_archive_category', 'tbl_mibl_master_archive_category.id', '=', 'tbl_mibl_creatives_vendor.archive_category_id');
  $result->leftJoin('tbl_mibl_master_archive_sub_category', 'tbl_mibl_master_archive_sub_category.id', '=', 'tbl_mibl_creatives_vendor.archive_sub_category_id');
  $result->leftJoin('tbl_mibl_master_department_type', 'tbl_mibl_master_department_type.id', '=', 'tbl_mibl_creatives_vendor.department_type_id');
  $result->leftJoin('tbl_mibl_master_vendor_type', 'tbl_mibl_master_vendor_type.id', '=', 'tbl_mibl_creatives_vendor.vendor_type_id');
  $result->select('tbl_mibl_creatives_vendor.*','tbl_mibl_master_archive_category.name as archive_name','tbl_mibl_master_category.name as category_name',
  'tbl_mibl_master_brand.name as brand_name','tbl_mibl_master_vendor.name as vendor_name',
  'tbl_mibl_master_department.name as department_name','tbl_mibl_master_document_type.name as document_type_name',
  'tbl_mibl_master_archive_sub_category.name as archive_sub_category_name',
  'tbl_mibl_master_department_type.department_type_name as department_type_name',
  'tbl_mibl_master_vendor_type.vendor_type_name as vendor_type_name');
  $result->skip($start);
  $result->take($rowperpage);
  $records=$result->get(); 
  
  }
  //echo count($result);
  
    $data_arr = array();
    $i=1;
    foreach($records as $record){
       
       $id = $record->id;
  
       if($record->active_yn == '0')
       {
        $status="<span style='color:#da3d2c'>Pending</span>";
       }
  
       if($record->created_date)
       {
        $created_date= date("d/m/Y", strtotime($record->created_date));
       }else
       {
        $last_login_date='';
       }
  
       if($record->date_of_posting)
       {
        $date_of_posting= date("F Y", strtotime($record->date_of_posting));
       }else
       {
        $date_of_posting='';
       }
       
       if(!empty($record->date_of_upload))
       {
        $date_of_upload= date("d/m/Y", strtotime($record->date_of_upload));
       }else
       {
        $date_of_upload='';
       }
  
       if(!empty($record->id))
       {
        $APP_URL=$_ENV['APP_URL']."edit-creative-vendor-miscellaneous/".base64_encode($record->id);
        $img="<img src='".$_ENV['APP_URL']."assets/img/edit.png' class='img-fluid tab-img'>";
        $edit_link="<a href='".$APP_URL."'>$img</a>";  
       }
       
       if($record->file_type == 'image')
       {
        $year= date("Y", strtotime($record->date_of_posting));
        $month= date("m", strtotime($record->date_of_posting));
        $img="<img src='".$_ENV['APP_URL']."upload_vendor/".$year."/".$month."/"."thumbnail/".$record->photo_url."' class='img-fluid tab-img'>";
        $images=$img;
         
       }else if($record->file_type == 'other')
       { 
  
        $image_arr=explode(".",$record->photo_url);
        $image_type=end($image_arr);
        if(Str::upper($image_type) == 'PDF')
        {
          $img="<img src='".$_ENV['APP_URL']."assets/img/pdf.png' class='img-fluid tab-img'>";
        }else if (Str::upper($image_type) == 'PPT')
        {
          $img="<img src='".$_ENV['APP_URL']."assets/img/ppt.png' class='img-fluid tab-img'>";
        }else
        {
          $img="<img src='".$_ENV['APP_URL']."assets/img/video.png' class='img-fluid tab-img'>";
        }
        $images=$img;
      }


      if(!empty($record->source_file))
       {

        $source_file=$_ENV['APP_URL']."upload_vendor/".$year."/".$month."/upload_source_file/".$record->source_file;
        $source_file_d="<a href='".$source_file."' download>Download</a>";
       }
       else
       {
        $source_file_d='';
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
         "file_name" =>$record->file_name,
         "photo_url" =>$images,
         "advertisement_id" =>$record->advertisement_id,
         "archive_category_id" =>$archive_name,
         "category_id" =>$record->category_name,
         "brand_id" =>$record->brand_name,
         "vendor_id" =>$vendor_name,
         "department_id" =>$department_name,
         "document_type_id" =>$record->document_type_name,
         "date_of_posting" =>$date_of_posting,
         "date_of_upload" =>$date_of_upload,
         "active_yn" =>$status,
         "created_date"=>$created_date,
         'source_file'=>$source_file_d,
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



   
public function edit_creative_vendor_miscellaneous($id)
{

  $category = DB::table('tbl_mibl_master_category')
  ->select('*')
  ->where('active_yn',0)
  ->get();
  
  $document_type = DB::table('tbl_mibl_master_document_type')
  ->select('*')
  ->where('active_yn',0)
  ->get();

  $brand = DB::table('tbl_mibl_master_brand')
  ->select('*')
  ->where('active_yn',0)
  ->get();
  

$id = base64_decode($id); 

$data = DB::table('tbl_mibl_creatives_vendor')
->select('*')
->where('id', '=', $id)
->get();

foreach($data as $cl)
{
  $vendor_type_id=$cl->vendor_type_id;
  $department_type_id=$cl->department_type_id;
}



$languages = DB::table('tbl_mibl_master_language')
->select('*')
->where('active_yn',0)
->get();


//Archive Category and Archive Sub Category  

$archive_category = DB::table('tbl_mibl_master_archive_category')
  ->select('*')
  ->where('active_yn',0)
  ->get();
  $archive_c=array();
foreach($archive_category as $archivecategory)
{
  $sub_cat=array();
  $data_new = DB::table('tbl_mibl_master_archive_sub_category')
   ->where('archive_category_id',$archivecategory->id)
   ->where('active_yn',0)->orderBy('name', 'ASC')->get();
   if(count($data_new) > 0){
   foreach($data_new as $dat)
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
  $data_d = DB::table('tbl_mibl_master_department')
   ->where('department_type_id',$department_type->id)
   ->where('active_yn',0)->orderBy('name', 'ASC')->get();
   if(count($data_d) > 0){
   foreach($data_d as $dat)
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
  $data_v = DB::table('tbl_mibl_master_vendor')
   ->where('vendor_type_id',$vendor_type->id)
   ->where('active_yn',0)
   ->where('flag',1)->orderBy('name', 'ASC')->get();
   if(count($data_v) > 0){
   foreach($data_v as $dat)
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


return view('miscellaneous/edit_creative_vendor_miscellaneous', 
['edit_services' => $data,
'category_list' => $category,
'document_type_list' => $document_type,
'brand_list' => $brand,
'archive_c'=>$archive_c,
'department_c'=>$department_c,
'vendor_c'=>$vendor_c,
'languages'=>$languages]);
}







public function update_creative_vendor_miscellaneous(Request $request)
{
  
  $advertisement_id=$request->input('advertisement_id');
  $id=$request->input('id');


  if($request->file('photo') != '') 
  {
     
     if (( $_FILES["photo"]["size"] <= 200000000 )) {
     
      $mime = $_FILES['photo']['type'];
      $image=$request->file('photo');
      $filename=$image->getClientOriginalName();  
      $id=$request->input('id');
      $data = DB::table('tbl_mibl_creatives_vendor')
      ->select('*')
      ->where('id',$id)
      ->first();

      @$source_file_new= $data->source_file;

      $mime = $_FILES['photo']['type'];
      $image=$request->file('photo');
      $filename=$image->getClientOriginalName();  

      $data = DB::table('tbl_mibl_creatives_vendor')
      ->select('*')
      ->where('photo_url',$filename)
      ->get();
      
      if(count($data)== '0'){  
        $filename_new= $filename;
      }else
      {
        $characters='0123456789abcdefghijklmnopqrstuvwxyz';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < 18; $i++) {
        $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
        $file_name = $_FILES["photo"]["name"];
        $file_tmp = $_FILES["photo"]["tmp_name"];
        $ext = pathinfo($file_name, PATHINFO_EXTENSION);
        $filename = $randomString . '.' . $ext;
        $filename_new = $filename;
      }


      $year= date("Y", strtotime($request->input('date_of_posting')));
      $month= date("m", strtotime($request->input('date_of_posting')));
      
      $name_thumbnail='thumbnail';
      $name_thumbnail = "upload_vendor/".$year."/".$month."/".$name_thumbnail;

      $name_preview='preview';
      $name_preview = "upload_vendor/".$year."/".$month."/".$name_preview;

      $name_original='original';
      $name_original = "upload_vendor/".$year."/".$month."/".$name_original;

      $name_upload_source_file='upload_source_file';
      $name_upload_source_file = "upload_vendor/".$year."/".$month."/".$name_upload_source_file;

      $month_new = "upload_vendor/".$year."/".$month;


   if(strstr($mime, "image/"))
    {
       
        $filetype = "image";
        $image=$request->file('photo');
        $image_info = getimagesize($image);
      
        $original_width=$image_info[0];
        $original_height=$image_info[1];
        $ratio = 1.0;
        $scaled = false;
      
        // FIXME size should be configurable thumbnail
        if ($original_width > 200) {
          $ratio = 200 / $original_width;
          $width_t = $original_width * $ratio;
          $height_t = $original_height * $ratio;
          $scaled = true;
        } else {
          $width_t = $original_width;
          $height_t = $original_height;
        }
      
        if ($height_t > 200) {
          $ratio = 200 / $original_height;
          $width_t = $original_width * $ratio;
          $height_t = $original_height * $ratio;
          $scaled = true;
          } 
          
          
       // FIXME size should be configurable Preview
        if ($original_width > 500) {
         $ratio = 500 / $original_width;
         $width_p = $original_width * $ratio;
         $height_p = $original_height * $ratio;
         $scaled = true;
       } else {
         $width_p = $original_width;
         $height_p = $original_height;
       }
     
       if ($height_p > 500) {
         $ratio = 500 / $original_height;
         $width_p = $original_width * $ratio;
         $height_p = $original_height * $ratio;
         $scaled = true;
         }  

        if($request->hasFile('photo')) {
        $image=$request->file('photo');
        $filename=$filename_new;
        $image_resize = Image::make($image->getRealPath());
        $image_resize->resize($width_t,$height_t);
        $image_resize->save($name_thumbnail.'/' .$filename);
        }

        if($request->hasFile('photo')) {
        $image=$request->file('photo');
        $filename=$filename_new;
        $image_resize = Image::make($image->getRealPath());
        $image_resize->resize($width_p,$height_p);
        $image_resize->save($name_preview.'/' .$filename);
        }

        if($request->hasFile('photo')) {
        $image = $request->file('photo');
        $filename=$filename_new;
        $file_name = $_FILES["photo"]["name"];
        $file_tmp  = $_FILES["photo"]["tmp_name"];
        $filename2 = $name_original.'/'.$filename;
        $arr_data['photo']=move_uploaded_file($file_tmp, env('BASE_PATH') . $filename2);
        }

          $photo_url = $filename;
          $filetype = $filetype;            
          DB::table('tbl_mibl_creatives_vendor')
          ->where('id', $id)
          ->update([
          'photo_url'=>$photo_url,
          'file_type'=>$filetype,
          ]);
                 

    }
     else
      {
            $filetype='other'; 
            $image     = $request->file('photo');
            $filename=$filename_new;
            $file_name = $_FILES["photo"]["name"];
            $file_tmp  = $_FILES["photo"]["tmp_name"];
            $filename_ne  = $month_new.'/'.$filename;
            $arr_data['photo']=move_uploaded_file($file_tmp, env('BASE_PATH') . $filename_ne);

            $photo_url = $filename;
            $filetype = $filetype;            
            DB::table('tbl_mibl_creatives_vendor')
            ->where('id', $id)
            ->update([
            'photo_url'=>$photo_url,
            'file_type'=>$filetype,
            ]);
         
      } 
      
    }else
    
    {
    session()->flash('failmsg', 'Kindly upload file upto size 200MB.');
    return redirect('edit-creative-vendor-miscellaneous/'.base64_encode($id));
    }  
        
  }


  if ($request->file('source_file') != '') {



    $image=$request->file('source_file');
    $filenamesource_file  = $image->getClientOriginalName();

    $data = DB::table('tbl_mibl_creatives')
    ->select('*')
    ->where('source_file',$filenamesource_file)
    ->get();

    if(count($data)== '0'){  
    $filename_sourcefile= $filenamesource_file;
    }else
    {
    $characters='0123456789abcdefghijklmnopqrstuvwxyz';
    $charactersLength = strlen($characters);
    $randomString = '';
    for ($i = 0; $i < 18; $i++) {
    $randomString .= $characters[rand(0, $charactersLength - 1)];
    }
    $file_name = $_FILES["source_file"]["name"];
    $file_tmp = $_FILES["source_file"]["tmp_name"];
    $ext = pathinfo($file_name, PATHINFO_EXTENSION);
    $filenamesource_file = $randomString . '.' . $ext;
    $filename_sourcefile = $filenamesource_file;
    }
    $filename1=$filename_sourcefile;

    $year= date("Y", strtotime($request->input('date_of_posting')));
    $month= date("m", strtotime($request->input('date_of_posting')));

    $name_upload_source_file='upload_source_file';
    $name_upload_source_file = "upload_vendor/".$year."/".$month."/".$name_upload_source_file; 

    // $image     = $request->file('source_file');
    // $filename1  =$source_file_new;
    $file_name = $_FILES["source_file"]["name"];
    $file_tmp  = $_FILES["source_file"]["tmp_name"];
    $filename_n = $name_upload_source_file.'/'.$filename1;
    $arr_data['source_file']=move_uploaded_file($file_tmp, env('BASE_PATH') . $filename_n);

    $source_file = $filename1;
    DB::table('tbl_mibl_creatives_vendor')
    ->where('id', $id)
    ->update([
    'source_file'=>$source_file,
    ]);

    }

    
 
    $user_login_type=session('user_login_type');

    if($user_login_type == 'Employee')
    {
    $user_id=session('id');
    $user = DB::table('tbl_mibl_user')
    ->select('*')
    ->where('deleted_at','=',0)
    ->where('id',$user_id)
    ->orderBy('id', 'desc')
    ->first();
    $username=$user->name;
    }else

    {
    $user_id=session('id');
    $user = DB::table('tbl_mibl_master_vendor')
    ->select('*')
    ->where('deleted_at','=',0)
    ->where('id',$user_id)
    ->orderBy('id', 'desc')
    ->first();
    $username=$user->name;  
    }



$file_name = $request->input('file_name');
$advertisement_id = $request->input('advertisement_id');
$file_description = $request->input('file_description');
$category_id =$request->input('category_id');
$brand_id = $request->input('brand_id');
$document_type_id = $request->input('document_type_id');
$date_of_posting = $request->input('date_of_posting')."-01";
$date_of_upload=$request->input('date_of_upload');
$other_document_type=$request->input('other_document_type');
$archive_category_id=$request->input('archive_category_id');
$department_type_id=$request->input('department_type_id');
$vendor_type_id=$request->input('vendor_type_id');
$active_yn=$request->input('active_yn');
$irdai_date=$request->input('irdai_date');
$irdai_addressed=$request->input('irdai_addressed');
$remark=$request->input('remark');
$language_id=$request->input('language_id');


DB::table('tbl_mibl_creatives_vendor')
->where('id', $id)
->update([
  'file_name'=>$file_name,
//   'advertisement_id'=>$advertisement_id,
  'file_description'=>$file_description,
  'document_type_id'=>$document_type_id,
  'other_document_type'=>$other_document_type,
  'remark'=>$remark,
  'modify_date'=>date('Y-m-d H:i:s'),
  'created_by'=>$username,
  ]);

    /*Insert user activity*/
    $last_id=$request->input('id');

    DB::table('tbl_mibl_user_activity')
    ->insert([
    'user_id' =>$user_id,
    'user_name'=>$username,
    'activity_group_id'=>$last_id,
    'messgage'=>'Vendor Single Miscellaneous creative upload Update successfully',
    'activity_type'=>'Insert',
    'activity_group'=>'Vendor Single creative upload Update',
    'created_date' => date('Y-m-d H:i:s'),
    ]);  
  
    

if(!empty($remark))
{

if($user_login_type == 'Employee')
{

//====================Notification Email Code Start=============


$vendor_id=$request->input('vendor_id');
$vendor= DB::table('tbl_mibl_master_vendor')
->select('*')
->where('deleted_at','=',0)
->where('id',$vendor_id)
->first();
$vendor_name=$vendor->name;
$vendor_id=$vendor->id;
$email_id=$vendor->email;
$employee_id=$user_id;

$mesage_vendor="";

if(!empty($email_id))
{

$subject="MBank: Admin has added notes in creative ".$advertisement_id;
$message="
Dear User,
Admin has added notes on ".$file_name." creative with ".$advertisement_id." on MBank.
Kindly check and submit revised post approval.

Sincerely,
MBank";
$url="https://eapi.instaalerts.zone/email?uname=MIBL_ITmail&pass=bQ@8ajbv&fromName=MBank&fromEmail=info@MAHINDRAINSURANCE.COM&toEmail=".urlencode($email_id)."&subject=".urlencode($subject)."&msgPlain=".urlencode($message);
$ch = curl_init($url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
$curl_scraped_page = curl_exec($ch);
curl_close($ch);


//Insert Noification 

$messages="
Dear User,<br>
Admin has added notes on ".$file_name." creative with ".$advertisement_id." on MBank.<br>
Kindly check and submit revised post approval.<br><br>

Sincerely,<br>
MBank";

DB::table('tbl_mibl_notification')
->insert([
'employee_id' =>$employee_id,
'vendor_id'=>$vendor_id,
'subject'=>$subject,
'message'=>$messages,
'type'=>'Note-Employee',
'send_by'=>$username,
'send_date' =>date('Y-m-d'),
'read_status' =>0,
]);  
$mesage_vendor="";
}else
{
  $mesage_vendor="Vendor email id is not present in system. Kindly update the same.";
}


//=============== Notification Email Code End =============

}else
{
//====================Notification Email Code Start=============

//$email_id="UPADHYAY.KRUTI@Mahindra.com";
$email_id="priyanka.surti@evonix.co";
$employee= DB::table('tbl_mibl_user')
->select('*')
->where('deleted_at','=',0)
->where('email',$email_id)
->first();
$employee_name=$employee->name;
$employee_id=$employee->id;

$subject="MBank: Vendor ".$username." has uploaded creative ".$advertisement_id;
$message="
Dear User,
".$username." has uploaded final source file of ".$file_name." with ".$advertisement_id." on MBank.
Note : ".$remark."
Request you to please approve the same.

Sincerely,
MBank";
$url="https://eapi.instaalerts.zone/email?uname=MIBL_ITmail&pass=bQ@8ajbv&fromName=MBank&fromEmail=info@MAHINDRAINSURANCE.COM&toEmail=".urlencode($email_id)."&subject=".urlencode($subject)."&msgPlain=".urlencode($message);
$ch = curl_init($url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
$curl_scraped_page = curl_exec($ch);
curl_close($ch);

//Insert Noification 
$messages="
Dear User,<br>
".$username." has uploaded final source file of ".$file_name." with ".$advertisement_id." on MBank.<br>
Note : ".$remark."<br>
Request you to please approve the same.<br><br>

Sincerely,<br>
MBank";

DB::table('tbl_mibl_notification')
->insert([
'employee_id' =>$employee_id,
'vendor_id'=>$user_id,
'subject'=>$subject,
'message'=>$messages,
'type'=>'Note-Vendor',
'send_by'=>$username,
'send_date' =>date('Y-m-d'),
'read_status' =>0,
]);  

//=============== Notification Email Code End =============

}

}


if(!empty($email_id))
{
  session()->flash('successmsg', 'Creative updated successfully.');
}else
{
 session()->flash('successmsg', 'Creative updated successfully.'."<br>".$mesage_vendor);
}
  $user_login_type=session('user_login_type');
  if($user_login_type == 'Employee')
  {
    return redirect('edit-creative-vendor-miscellaneous/'.base64_encode($id));
  }else
  {
    return redirect('edit-creative-vendor-miscellaneous/'.base64_encode($id));
  }

}








public function view_creative_vendor_approved_miscellaneous(Request $request)
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
    
    
    $result=DB::table('tbl_mibl_creatives_vendor');
    $result->select('tbl_mibl_creatives_vendor.*','tbl_mibl_master_archive_category.name as archive_name','tbl_mibl_master_category.name as category_name',
    'tbl_mibl_master_brand.name as brand_name','tbl_mibl_master_vendor.name as vendor_name',
    'tbl_mibl_master_department.name as department_name','tbl_mibl_master_document_type.name as document_type_name',
    'tbl_mibl_master_archive_sub_category.name as archive_sub_category_name',
    'tbl_mibl_master_department_type.department_type_name as department_type_name',
    'tbl_mibl_master_vendor_type.vendor_type_name as vendor_type_name',
    'tbl_mibl_master_language.language as language');
    $result->leftJoin('tbl_mibl_master_document_type', 'tbl_mibl_master_document_type.id', '=', 'tbl_mibl_creatives_vendor.document_type_id');
    $result->leftJoin('tbl_mibl_master_archive_sub_category', 'tbl_mibl_master_archive_sub_category.id', '=', 'tbl_mibl_creatives_vendor.archive_sub_category_id');
    $result->leftJoin('tbl_mibl_master_department', 'tbl_mibl_master_department.id', '=', 'tbl_mibl_creatives_vendor.department_id');
    $result->leftJoin('tbl_mibl_master_vendor', 'tbl_mibl_master_vendor.id', '=', 'tbl_mibl_creatives_vendor.vendor_id');
    $result->leftJoin('tbl_mibl_master_brand', 'tbl_mibl_master_brand.id', '=', 'tbl_mibl_creatives_vendor.brand_id');
    $result->leftJoin('tbl_mibl_master_category', 'tbl_mibl_master_category.id', '=', 'tbl_mibl_creatives_vendor.category_id');
    $result->leftJoin('tbl_mibl_master_archive_category', 'tbl_mibl_master_archive_category.id', '=', 'tbl_mibl_creatives_vendor.archive_category_id');
    $result->leftJoin('tbl_mibl_master_department_type', 'tbl_mibl_master_department_type.id', '=', 'tbl_mibl_creatives_vendor.department_type_id');
    $result->leftJoin('tbl_mibl_master_vendor_type', 'tbl_mibl_master_vendor_type.id', '=', 'tbl_mibl_creatives_vendor.vendor_type_id');
    $result->leftJoin('tbl_mibl_master_language', 'tbl_mibl_master_language.id', '=', 'tbl_mibl_creatives_vendor.language_id');
    $result->where('tbl_mibl_creatives_vendor.type_of_creative', '=','miscellaneous');
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
    $result->where('tbl_mibl_creatives_vendor.advertisement_id', 'like', '%' .$advertisement_id. '%');
    }
    
    if (!empty($document_id)) {
    $result->where('tbl_mibl_creatives_vendor.document_type_id', 'like', '%' .$document_id. '%');
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
    $result->whereRaw("DATE_FORMAT(tbl_mibl_creatives_vendor.date_of_posting, '%Y%m') >= '" . $from_date1 . "' AND DATE_FORMAT(tbl_mibl_creatives_vendor.date_of_posting, '%Y%m') <= '" . $to_date1 . "'");
    
    }
    $result->orderBy('id','DESC');
    $details=$result->paginate(5);
    
    
    return view('miscellaneous/view_creative_vendor_approved_miscellaneous',
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
    'document_id'=>$document_id]);
    
    }  
    
    
    
    
    
    
    
    
public function insert_creative_main_miscellaneous(Request $request)
 {
    
    $id =$request->get('id');
    $id_misc =$request->get('id');
    
    $user_id=session('id');
    $user = DB::table('tbl_mibl_user')
    ->select('*')
    ->where('deleted_at','=',0)
    ->where('id',$user_id)
    ->orderBy('id', 'desc')
    ->first();
    $username=$user->name;
    
    $bulk_list = DB::table('tbl_mibl_creatives_vendor')
    ->select('*')
    ->where('id',$id)
    ->first();

    $advertisement_id=$bulk_list->advertisement_id;

        
       # create directory of Year
       $year1=date("Y", strtotime($bulk_list->date_of_posting));
       $year = "uploads/".$year1;
       # create directory if not exists in upload/ directory
       if(!is_dir($year)){
         mkdir($year, 0777);
       }
      
        # create directory of Month
        $month1=date("m", strtotime($bulk_list->date_of_posting));
        $month = "uploads/".$year1."/".$month1;
  
        $month_new = "uploads/".$year1."/".$month1;
  
        # create directory if not exists in upload/ directory
        if(!is_dir($month)){
          mkdir($month, 0777);
        }
        
        # create directory of Thumbnail
        $name_thumbnail='thumbnail';
        $name_thumbnail = "uploads/".$year1."/".$month1."/".$name_thumbnail;
        # create directory if not exists in upload/ directory
        if(!is_dir($name_thumbnail)){
          mkdir($name_thumbnail, 0777);
        }
        
        # create directory of Preview
        $name_preview='preview';
        $name_preview = "uploads/".$year1."/".$month1."/".$name_preview;
        # create directory if not exists in upload/ directory
        if(!is_dir($name_preview)){
          mkdir($name_preview, 0777);
        }
        
        # create directory of Original
        $name_original='original';
        $name_original = "uploads/".$year1."/".$month1."/".$name_original;
        # create directory if not exists in upload/ directory
        if(!is_dir($name_original)){
          mkdir($name_original, 0777);
        }
        # create directory of Original
        $name_upload_source_file='upload_source_file';
        $name_upload_source_file = "uploads/".$year1."/".$month1."/".$name_upload_source_file;
        # create directory if not exists in upload/ directory
        if(!is_dir($name_upload_source_file)){
          mkdir($name_upload_source_file, 0777);
        }
  
  

          //Video upload cloudflare
          $image_arr=explode(".",$bulk_list->photo_url);
          $doc_type=end($image_arr);
          $VIDEOID='';

          if($bulk_list->file_type == 'other')
            {

            if($doc_type == 'mp4')
             {
           $year= date("Y", strtotime($bulk_list->date_of_posting));
           $month= date("m", strtotime($bulk_list->date_of_posting));            
           $filename_ne="upload_vendor/$year/$month/$bulk_list->photo_url";    
           $photo=$filename_ne;
           $url="https://api.cloudflare.com/client/v4/accounts/34cc3252d5c329c1d2ac13237b4972ed/stream";
           $curl = curl_init();
           curl_setopt_array($curl, [
               CURLOPT_URL            => $url, // tmp url provided by cloudflare
               CURLOPT_RETURNTRANSFER => 1,
               CURLOPT_TIMEOUT        => 60000,
               CURLOPT_POST           => true,
               CURLOPT_POSTFIELDS     => ['file'=>new \CURLFile($photo),'video/mp4','test_name'],
               CURLOPT_HTTPHEADER     => [
                   "X-Auth-Key: 43b3d73c452c8f2f536964033aa59622c3b9d","X-Auth-Email:marketing.mibl@gmail.com"
               ],
           ]);
           $response = curl_exec($curl);
           curl_close($curl);
           $response=json_decode($response);
           $result=$response->result;
           $VIDEOID=$result->uid;
           $filename_new=$bulk_list->photo_url;

           if (file_exists($filename_ne)) {
            @unlink($filename_ne);
           }

          }else
          {

            $filename=$bulk_list->photo_url;
            $data = DB::table('tbl_mibl_creatives')
            ->select('*')
            ->where('photo_url',$filename)
            ->get();
             
            if(count($data)== '0'){  
              $filename_new= $filename;
            }else{

            $characters='0123456789abcdefghijklmnopqrstuvwxyz';
              $charactersLength = strlen($characters);
              $randomString = '';
              for ($i = 0; $i < 18; $i++) {
              $randomString .= $characters[rand(0, $charactersLength - 1)];
              }
              $image_arr=explode(".",$bulk_list->photo_url);
              $doc_type=end($image_arr);
              $filename = $randomString . '.' . $doc_type;
              $filename_new=$filename;
            }

            $year= date("Y", strtotime($bulk_list->date_of_posting));
            $month= date("m", strtotime($bulk_list->date_of_posting)); 
            $filename_ne="upload_vendor/$year/$month/$bulk_list->photo_url";
            $copy_other="uploads/$year/$month/$filename_new";
            File::copy($filename_ne,$copy_other); 

            if (file_exists($filename_ne)) {
              @unlink($filename_ne);
             }
          }

          }
            
            if($bulk_list->file_type == 'image')
            {

            $filename=$bulk_list->photo_url;
            $data = DB::table('tbl_mibl_creatives')
            ->select('*')
            ->where('photo_url',$filename)
            ->get();
             
            if(count($data)== '0'){  
              $filename_new= $filename;
            }else{

            $characters='0123456789abcdefghijklmnopqrstuvwxyz';
              $charactersLength = strlen($characters);
              $randomString = '';
              for ($i = 0; $i < 18; $i++) {
              $randomString .= $characters[rand(0, $charactersLength - 1)];
              }
              $image_arr=explode(".",$bulk_list->photo_url);
              $doc_type=end($image_arr);
              $filename = $randomString . '.' . $doc_type;
              $filename_new=$filename;
            }

            $year= date("Y", strtotime($bulk_list->date_of_posting));
            $month= date("m", strtotime($bulk_list->date_of_posting));            
            $filename_thumbnail="upload_vendor/$year/$month/thumbnail/$bulk_list->photo_url"; 
            $filename_preview="upload_vendor/$year/$month/preview/$bulk_list->photo_url"; 
            $filename_original="upload_vendor/$year/$month/original/$bulk_list->photo_url"; 

            $copy_thumbnail=$name_thumbnail."/".$filename_new;
            $copy_preview=$name_preview."/".$filename_new;
            $copy_original=$name_original."/".$filename_new;
            $copy_original=$name_original."/".$filename_new;

            File::copy($filename_thumbnail,$copy_thumbnail);
            File::copy($filename_preview,$copy_preview);
            File::copy($filename_original,$copy_original);

            if (file_exists($filename_thumbnail)) {
              @unlink($filename_thumbnail);
             }
             if (file_exists($filename_preview)) {
              @unlink($filename_preview);
             }
             if (file_exists($filename_original)) {
              @unlink($filename_original);
             }
          }



          if(!empty($bulk_list->source_file))
          {
            $year= date("Y", strtotime($bulk_list->date_of_posting));
            $month= date("m", strtotime($bulk_list->date_of_posting));            
            $filename_source_file="upload_vendor/$year/$month/upload_source_file/$bulk_list->source_file"; 
            $copy_source_file=$name_upload_source_file."/".$bulk_list->source_file;
            File::copy($filename_source_file,$copy_source_file);
            
            if (file_exists($filename_source_file)) {
              @unlink($filename_source_file);
             }
            
          }


    if ( date('m') > 3 ) {
    $year = date('y') + 1;
    }
    else {
    $year = date('y');
    }
    
    $data_s = DB::table('tbl_mibl_miscellaneous_advertisement_id')
    ->select('*')
    ->orderby('id','DESC')
    ->limit(1)
    ->first();

    if(!empty($data_s->id)){
    $id=$data_s->id+1;
    } else {
    $id=1;
    }
    if(strlen($id) == 1)
    {
    $serial_no='00'.$id;
    }
    else if(strlen($id) == 2)
    {
    $serial_no='0'.$id;
    }else 
    {
    $serial_no=$id;
    }
    
     $miscellaneous="MISC";
     $advertisement_id=strtoupper('F'.$year.'/'.$miscellaneous.'/'.$serial_no);
     
     
    $last_id=DB::table('tbl_mibl_miscellaneous_advertisement_id')->insertGetId([
    'advertisement_id'=>$advertisement_id,
    'remark'=>'',
    'created_date'=>date('Y-m-d H:i:s'),
    'created_by'=>$username
    ]);
     
   
     $last_id = DB::table('tbl_mibl_creatives')->insertGetId([
      'file_name'=>$bulk_list->file_name,
      'advertisement_id'=>$advertisement_id,
      'file_description'=>$bulk_list->file_description,
      'category_id'=>$bulk_list->category_id,
      'brand_id'=>$bulk_list->brand_id,
      'department_id'=>$bulk_list->department_id,
      'document_type_id'=>$bulk_list->document_type_id,
      'vendor_id'=>$bulk_list->vendor_id,
      'date_of_posting'=>$bulk_list->date_of_posting,
      'date_of_upload'=>$bulk_list->date_of_upload,
      'other_document_type'=>$bulk_list->other_document_type,
      'photo_url'=>$filename_new,
      'file_type'=>$bulk_list->file_type,
      'archive_category_id'=>$bulk_list->archive_category_id,
      'archive_sub_category_id'=>$bulk_list->archive_sub_category_id,
      'department_type_id'=>$bulk_list->department_type_id,
      'vendor_type_id'=>$bulk_list->vendor_type_id,
      'language_id'=>$bulk_list->language_id,
      'source_file'=>$bulk_list->source_file,
      'irdai_date'=>$bulk_list->irdai_date,
      'irdai_addressed'=>$bulk_list->irdai_addressed,
      'remark'=>$bulk_list->remark,
      'created_date'=>date('Y-m-d H:i:s'),
      'created_by'=>$username,
      'video_url'=>$VIDEOID,
      'type_of_creative'=>'miscellaneous'
      ]); 

      
    /*Insert user activity*/

    DB::table('tbl_mibl_user_activity')
    ->insert([
    'user_id' =>$user_id,
    'user_name'=>$username,
    'activity_group_id'=>$last_id,
    'messgage'=>'Approve miscellaneous creative successfully',
    'activity_type'=>'Insert',
    'activity_group'=>'Approve Creative',
    'created_date' => date('Y-m-d H:i:s'),
    ]);  



    //====================Notification Email Code Start=============
    $vendor_id=$bulk_list->vendor_id;
    $advertisement_id=$bulk_list->advertisement_id;
    $file_name=$bulk_list->file_name;
    $vendor= DB::table('tbl_mibl_master_vendor')
    ->select('*')
    ->where('deleted_at','=',0)
    ->where('id',$vendor_id)
    ->first();
    $vendor_name=$vendor->name;
    $vendor_id=$vendor->id;
    $employee_id=$user_id;
    $email_id=$vendor->email;

    if(!empty($email_id))
    {
    
    
    $subject="MBank: Admin has approved creative ".$advertisement_id;
    $message="
    Dear User,
    Admin has approved the ".$file_name." creative with ".$advertisement_id." on MBank.
    
    Sincerely,
    MBank";
    $url="https://eapi.instaalerts.zone/email?uname=MIBL_ITmail&pass=bQ@8ajbv&fromName=MBank&fromEmail=info@MAHINDRAINSURANCE.COM&toEmail=".urlencode($email_id)."&subject=".urlencode($subject)."&msgPlain=".urlencode($message);
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $curl_scraped_page = curl_exec($ch);
    curl_close($ch);


    //Insert Noification 
    
    $messages="
    Dear User,<br>
    Admin has approved the ".$file_name." creative with ".$advertisement_id." on MBank.<br><br>
    
    Sincerely,<br>
    MBank";
    DB::table('tbl_mibl_notification')
    ->insert([
    'employee_id' =>$employee_id,
    'vendor_id'=>$vendor_id,
    'subject'=>$subject,
    'message'=>$messages,
    'type'=>'Approved',
    'send_by'=>$username,
    'send_date' =>date('Y-m-d'),
    'read_status' =>0,
    ]);  
    
    }
             

      //delete bulk entry

       DB::table('tbl_mibl_creatives_vendor')
        ->where('id' , $id_misc)
        ->delete();
        
        if(!empty($email_id))
        {
        return response()->json(['success'=>'200']);
        }else
        {
          return response()->json(['success'=>'201']);
        }
    
}



function add_single_file_upload_miscellaneous()
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


  $category = DB::table('tbl_mibl_master_category')
  ->select('*')
  ->where('active_yn',0)
  ->get();
  


  $document_type = DB::table('tbl_mibl_master_document_type')
  ->select('*')
  ->where('active_yn',0)
  ->get();


  $brand = DB::table('tbl_mibl_master_brand')
  ->select('*')
  ->where('active_yn',0)
  ->get();

  $language = DB::table('tbl_mibl_master_language')
  ->select('*')
  ->where('active_yn',0)
  ->get();

  return view('/miscellaneous/add_single_file_upload_miscellaneous', 
  ['category_list' => $category,
   'document_type_list' => $document_type,
   'brand_list' => $brand,
   'archive_c'=>$archive_c,
   'department_c'=>$department_c,
   'vendor_c'=>$vendor_c,
   'languages'=>$language]);
}







function insert_single_file_upload_miscellaneous(Request $request)
 {
   
    if (( $_FILES["photo"]["size"] <= 200000000 )) {

    $date_of_posting = date('Y-m', strtotime($request->input('date_of_posting')));
    $advertisement_id=$request->input('advertisement_id');

     # create directory of Year
     $year1=date("Y", strtotime($request->input('date_of_posting')));
     $year = "uploads/".$year1;
     # create directory if not exists in upload/ directory
     if(!is_dir($year)){
       mkdir($year, 0777);
     }
    
      # create directory of Month
      $month1=date("m", strtotime($request->input('date_of_posting')));
      $month = "uploads/".$year1."/".$month1;

      $month_new = "uploads/".$year1."/".$month1;

      # create directory if not exists in upload/ directory
      if(!is_dir($month)){
        mkdir($month, 0777);
      }
      
      # create directory of Thumbnail
      $name_thumbnail='thumbnail';
      $name_thumbnail = "uploads/".$year1."/".$month1."/".$name_thumbnail;
      # create directory if not exists in upload/ directory
      if(!is_dir($name_thumbnail)){
        mkdir($name_thumbnail, 0777);
      }
      
      # create directory of Preview
      $name_preview='preview';
      $name_preview = "uploads/".$year1."/".$month1."/".$name_preview;
      # create directory if not exists in upload/ directory
      if(!is_dir($name_preview)){
        mkdir($name_preview, 0777);
      }
      
      # create directory of Original
      $name_original='original';
      $name_original = "uploads/".$year1."/".$month1."/".$name_original;
      # create directory if not exists in upload/ directory
      if(!is_dir($name_original)){
        mkdir($name_original, 0777);
      }
      # create directory of Original
      $name_upload_source_file='upload_source_file';
      $name_upload_source_file = "uploads/".$year1."/".$month1."/".$name_upload_source_file;
      # create directory if not exists in upload/ directory
      if(!is_dir($name_upload_source_file)){
        mkdir($name_upload_source_file, 0777);
      }


    if(isset($_FILES['photo'])) 
      {

      $mime = $_FILES['photo']['type'];
      $image=$request->file('photo');
      $filename=$image->getClientOriginalName();  

      $data = DB::table('tbl_mibl_creatives')
      ->select('*')
      ->where('photo_url',$filename)
      ->get();
      
      if(count($data)== '0'){  
        $filename_new= $filename;
      }else
      {
        $characters='0123456789abcdefghijklmnopqrstuvwxyz';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < 18; $i++) {
        $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
        $file_name = $_FILES["photo"]["name"];
        $file_tmp = $_FILES["photo"]["tmp_name"];
        $ext = pathinfo($file_name, PATHINFO_EXTENSION);
        $filename = $randomString . '.' . $ext;
        $filename_new = $filename;
      }


 
    $VIDEOID=''; 
   if(strstr($mime, "image/"))
   {
       
       $filetype = "image";

       $image=$request->file('photo');
       $image_info = getimagesize($image);
     
       $original_width=$image_info[0];
       $original_height=$image_info[1];
       $ratio = 1.0;
       $scaled = false;
     
       // FIXME size should be configurable thumbnail
       if ($original_width > 200) {
         $ratio = 200 / $original_width;
         $width_t = $original_width * $ratio;
         $height_t = $original_height * $ratio;
         $scaled = true;
       } else {
         $width_t = $original_width;
         $height_t = $original_height;
       }
     
       if ($height_t > 200) {
         $ratio = 200 / $original_height;
         $width_t = $original_width * $ratio;
         $height_t = $original_height * $ratio;
         $scaled = true;
         } 
         
         

      // FIXME size should be configurable Preview
       if ($original_width > 500) {
        $ratio = 500 / $original_width;
        $width_p = $original_width * $ratio;
        $height_p = $original_height * $ratio;
        $scaled = true;
      } else {
        $width_p = $original_width;
        $height_p = $original_height;
      }
    
      if ($height_p > 500) {
        $ratio = 500 / $original_height;
        $width_p = $original_width * $ratio;
        $height_p = $original_height * $ratio;
        $scaled = true;
        }  
     





        if($request->hasFile('photo')) {
        $image       = $request->file('photo');
        //$filename    = $image->getClientOriginalName();
        $filename=$filename_new;
        $image_resize = Image::make($image->getRealPath());
        $image_resize->resize($width_t, $height_t);
        $image_resize->save($name_thumbnail.'/' .$filename);
        }

        if($request->hasFile('photo')) {
        $image       = $request->file('photo');
        //$filename    = $image->getClientOriginalName();
        $filename=$filename_new;
        $image_resize = Image::make($image->getRealPath());
        $image_resize->resize($width_p, $height_p);
        $image_resize->save($name_preview.'/' .$filename);
        }

        if($request->hasFile('photo')) {
        $image     = $request->file('photo');
        //$filename  = $image->getClientOriginalName();
        $filename=$filename_new;
        $file_name = $_FILES["photo"]["name"];
        $file_tmp  = $_FILES["photo"]["tmp_name"];
        $filename2 = $name_original.'/'.$filename;
        $arr_data['photo']=move_uploaded_file($file_tmp, env('BASE_PATH') . $filename2);
        }

        if ($request->file('source_file') != '') {

          $image=$request->file('source_file');
          $filenamesource_file  = $image->getClientOriginalName();

          $data = DB::table('tbl_mibl_creatives')
          ->select('*')
          ->where('source_file',$filenamesource_file)
          ->get();

          if(count($data)== '0'){  
          $filename_sourcefile= $filenamesource_file;
          }else
          {
          $characters='0123456789abcdefghijklmnopqrstuvwxyz';
          $charactersLength = strlen($characters);
          $randomString = '';
          for ($i = 0; $i < 18; $i++) {
          $randomString .= $characters[rand(0, $charactersLength - 1)];
          }
          $file_name = $_FILES["source_file"]["name"];
          $file_tmp = $_FILES["source_file"]["tmp_name"];
          $ext = pathinfo($file_name, PATHINFO_EXTENSION);
          $filenamesource_file = $randomString . '.' . $ext;
          $filename_sourcefile = $filenamesource_file;
          }
          $filename1=$filename_sourcefile;

          // $image     = $request->file('source_file');
          // $filename1  = $image->getClientOriginalName();
          $file_name = $_FILES["source_file"]["name"];
          $file_tmp  = $_FILES["source_file"]["tmp_name"];
          $filename_n = $name_upload_source_file.'/'.$filename1;
          $arr_data['source_file']=move_uploaded_file($file_tmp, env('BASE_PATH') . $filename_n);
          }else
          {
            $filename1='';
          }

    }else
      {
        $filetype='other'; 
           
          if ($request->file('source_file') != '') {


            $image=$request->file('source_file');
            $filenamesource_file  = $image->getClientOriginalName();

            $data = DB::table('tbl_mibl_creatives')
            ->select('*')
            ->where('source_file',$filenamesource_file)
            ->get();

            if(count($data)== '0'){  
            $filename_sourcefile= $filenamesource_file;
            }else
            {
            $characters='0123456789abcdefghijklmnopqrstuvwxyz';
            $charactersLength = strlen($characters);
            $randomString = '';
            for ($i = 0; $i < 18; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
            }
            $file_name = $_FILES["source_file"]["name"];
            $file_tmp = $_FILES["source_file"]["tmp_name"];
            $ext = pathinfo($file_name, PATHINFO_EXTENSION);
            $filenamesource_file = $randomString . '.' . $ext;
            $filename_sourcefile = $filenamesource_file;
            }
            $filename1=$filename_sourcefile;



            
            // $image     = $request->file('source_file');
            // $filename1  = $image->getClientOriginalName();
            $file_name = $_FILES["source_file"]["name"];
            $file_tmp  = $_FILES["source_file"]["tmp_name"];
            $filename_n = $name_upload_source_file.'/'.$filename1;
            $arr_data['source_file']=move_uploaded_file($file_tmp, env('BASE_PATH') . $filename_n);
            }else
            {
              $filename1='';
            }


    //Video upload cloudflare
    $image_path=$request->file('photo')->getRealPath();
    $image=$request->file('photo');
    $filename12=$image->getClientOriginalName();
    $image_arr=explode(".",$filename12);
    $doc_type=end($image_arr);
if($doc_type == 'mp4')
  {
      $photo=$image_path;
      $url="https://api.cloudflare.com/client/v4/accounts/34cc3252d5c329c1d2ac13237b4972ed/stream/";
      $curl = curl_init();
      curl_setopt_array($curl, [
      CURLOPT_URL            => $url, // tmp url provided by cloudflare
      CURLOPT_RETURNTRANSFER => 1,
      CURLOPT_TIMEOUT        => 6000,
      CURLOPT_POST           => true,
      CURLOPT_POSTFIELDS     => ['file'=>new \CURLFile($photo),'video/mp4','test_name'],
      CURLOPT_HTTPHEADER     => [
      "X-Auth-Key: 43b3d73c452c8f2f536964033aa59622c3b9d","X-Auth-Email:marketing.mibl@gmail.com"
      ],
      ]);
      $response = curl_exec($curl);
      curl_close($curl);
      $response=json_decode($response);
      @$result=$response->result;
      //  echo  "<pre>";
      //  echo $result->preview;
      @$VIDEOID=$result->uid;
  }
  else
  {
    $image     = $request->file('photo');
    //$filename  = $image->getClientOriginalName();
    $filename=$filename_new;
    $file_name = $_FILES["photo"]["name"];
    $file_tmp  = $_FILES["photo"]["tmp_name"];
    $filename_ne  = $month_new.'/'.$filename;
    $arr_data['photo']=move_uploaded_file($file_tmp, env('BASE_PATH') . $filename_ne);
  }



           
         
      }  

        
  } else
    {
      $filename1=''; 
      $filename=''; 
      $filetype=''; 
    }   
    

$user_id=session('id');
$user = DB::table('tbl_mibl_user')
->select('*')
->where('deleted_at','=',0)
->where('id',$user_id)
->orderBy('id', 'desc')
->first();
$username=$user->name;

$file_name = $request->input('file_name');
// $advertisement_id = $request->input('advertisement_id');
$file_description = $request->input('file_description');
// $category_id =$request->input('category_id');
$brand_id = $request->input('brand_id');
// $department_id = $request->input('department_id');
$document_type_id = $request->input('document_type_id');
// $vendor_id = $request->input('vendor_id');
$date_of_posting = $date_of_posting."-01";
$date_of_upload=$request->input('date_of_upload');
$other_document_type=$request->input('other_document_type');
// $archive_category_id=$request->input('archive_category_id');
// $department_type_id=$request->input('department_type_id');
$vendor_type_id=$request->input('vendor_type_id');
// $language_id=$request->input('language_id');
// $irdai_date=$request->input('irdai_date');
// $irdai_addressed=$request->input('irdai_addressed');
$remark=$request->input('remark');

$photo_url = $filename;
$source_file = $filename1;
$filetype = $filetype;

$arr_2=explode(",",$vendor_type_id);
$vendor_type_id=$arr_2[0];
$vendor_id=$arr_2[1];



    if ( date('m') > 3 ) {
    $year = date('y') + 1;
    }
    else {
    $year = date('y');
    }
    
    $data_s = DB::table('tbl_mibl_miscellaneous_advertisement_id')
    ->select('*')
    ->orderby('id','DESC')
    ->limit(1)
    ->first();

    if(!empty($data_s->id)){
    $id=$data_s->id+1;
    } else {
    $id=1;
    }
    if(strlen($id) == 1)
    {
    $serial_no='00'.$id;
    }
    else if(strlen($id) == 2)
    {
    $serial_no='0'.$id;
    }else 
    {
    $serial_no=$id;
    }
    
     $miscellaneous="MISC";
     $advertisement_id=strtoupper('F'.$year.'/'.$miscellaneous.'/'.$serial_no);
     
     
    $last_id=DB::table('tbl_mibl_miscellaneous_advertisement_id')->insertGetId([
    'advertisement_id'=>$advertisement_id,
    'remark'=>'',
    'created_date'=>date('Y-m-d H:i:s'),
    'created_by'=>$username
    ]);






$last_id=DB::table('tbl_mibl_creatives')->insertGetId([
  'file_name'=>$file_name,
  'advertisement_id'=>$advertisement_id,
  'file_description'=>$file_description,
  'brand_id'=>$brand_id,
  'document_type_id'=>$document_type_id,
  'vendor_id'=>$vendor_id,
  'date_of_posting'=>$date_of_posting,
  'date_of_upload'=>date('Y-m-d'),
  'other_document_type'=>$other_document_type,
  'photo_url'=>$photo_url,
  'source_file'=>$source_file,
  'file_type'=>$filetype,
  'vendor_type_id'=>$vendor_type_id,
  'remark'=>$remark,
  'video_url'=>$VIDEOID,
  'created_date'=>date('Y-m-d H:i:s'),
  'created_by'=>$username,
  'type_of_creative'=>'miscellaneous'
  ]);

/*Insert user activity*/
DB::table('tbl_mibl_user_activity')
->insert([
 'user_id' =>$user_id,
 'user_name'=>$username,
 'activity_group_id'=>$last_id,
 'messgage'=>'add single miscellaneous file upload successfully',
 'activity_type'=>'Insert',
 'activity_group'=>'add single file upload',
 'created_date' => date('Y-m-d H:i:s'),
 ]);  
  
  session()->flash('successmsg', 'Creative added successfully.');
  return redirect('/add-single-file-upload-miscellaneous');
 }
 else
 {
  session()->flash('failmsg', 'Kindly upload file upto size 200MB.');
  return redirect('/add-single-file-upload-miscellaneous');
 }

}





public function add_bulk_file_upload_miscellaneous(Request $request)
{

$year_list = DB::table('tbl_mibl_master_year')
->select('*')
->where('active_yn',0)
->get();

$verify = DB::table('tbl_mibl_creatives_bulk')
->select('tbl_mibl_creatives_bulk.*',
'tbl_mibl_master_archive_category.name as archive_name',
'tbl_mibl_master_category.name as category_name',
   'tbl_mibl_master_brand.name as brand_name',
   'tbl_mibl_master_vendor.name as vendor_name',
   'tbl_mibl_master_department.name as department_name',
   'tbl_mibl_master_document_type.name as document_type_name',
   'tbl_mibl_master_department_type.department_type_name as department_type_name',
   'tbl_mibl_master_vendor_type.vendor_type_name as vendor_type_name',
   'tbl_mibl_master_language.language as language_name',
   'tbl_mibl_master_archive_sub_category.name as archive_sub_category'
   )
   ->leftJoin('tbl_mibl_master_archive_sub_category', 'tbl_mibl_master_archive_sub_category.id', '=', 'tbl_mibl_creatives_bulk.archive_sub_category_id')
   ->leftJoin('tbl_mibl_master_language', 'tbl_mibl_master_language.id', '=', 'tbl_mibl_creatives_bulk.language_id')
   ->leftJoin('tbl_mibl_master_document_type', 'tbl_mibl_master_document_type.id', '=', 'tbl_mibl_creatives_bulk.document_type_id')
   ->leftJoin('tbl_mibl_master_department_type', 'tbl_mibl_master_department_type.id', '=', 'tbl_mibl_creatives_bulk.department_type_id')
   ->leftJoin('tbl_mibl_master_vendor_type', 'tbl_mibl_master_vendor_type.id', '=', 'tbl_mibl_creatives_bulk.vendor_type_id')
   ->leftJoin('tbl_mibl_master_department', 'tbl_mibl_master_department.id', '=', 'tbl_mibl_creatives_bulk.department_id')
   ->leftJoin('tbl_mibl_master_vendor', 'tbl_mibl_master_vendor.id', '=', 'tbl_mibl_creatives_bulk.vendor_id')
   ->leftJoin('tbl_mibl_master_brand', 'tbl_mibl_master_brand.id', '=', 'tbl_mibl_creatives_bulk.brand_id')
   ->leftJoin('tbl_mibl_master_category', 'tbl_mibl_master_category.id', '=', 'tbl_mibl_creatives_bulk.category_id')
   ->leftJoin('tbl_mibl_master_archive_category', 'tbl_mibl_master_archive_category.id', '=', 'tbl_mibl_creatives_bulk.archive_category_id')
   ->where('tbl_mibl_creatives_bulk.status','=',4)
   ->where('tbl_mibl_creatives_bulk.flag','miscellaneous')
   ->get();



$unverify = DB::table('tbl_mibl_creatives_bulk')
->select('tbl_mibl_creatives_bulk.*','tbl_mibl_master_archive_category.name as archive_name','tbl_mibl_master_category.name as category_name',
   'tbl_mibl_master_brand.name as brand_name','tbl_mibl_master_vendor.name as vendor_name',
   'tbl_mibl_master_department.name as department_name','tbl_mibl_master_document_type.name as document_type_name','tbl_mibl_master_department_type.department_type_name as department_type_name'
   ,'tbl_mibl_master_vendor_type.vendor_type_name as vendor_type_name',
   'tbl_mibl_master_language.language as language_name',
   'tbl_mibl_master_archive_sub_category.name as archive_sub_category')
   ->leftJoin('tbl_mibl_master_archive_sub_category', 'tbl_mibl_master_archive_sub_category.id', '=', 'tbl_mibl_creatives_bulk.archive_sub_category_id')
   ->leftJoin('tbl_mibl_master_language', 'tbl_mibl_master_language.id', '=', 'tbl_mibl_creatives_bulk.language_id')
   ->leftJoin('tbl_mibl_master_document_type', 'tbl_mibl_master_document_type.id', '=', 'tbl_mibl_creatives_bulk.document_type_id')
   ->leftJoin('tbl_mibl_master_department_type', 'tbl_mibl_master_department_type.id', '=', 'tbl_mibl_creatives_bulk.department_type_id')
   ->leftJoin('tbl_mibl_master_vendor_type', 'tbl_mibl_master_vendor_type.id', '=', 'tbl_mibl_creatives_bulk.vendor_type_id')
   ->leftJoin('tbl_mibl_master_department', 'tbl_mibl_master_department.id', '=', 'tbl_mibl_creatives_bulk.department_id')
   ->leftJoin('tbl_mibl_master_vendor', 'tbl_mibl_master_vendor.id', '=', 'tbl_mibl_creatives_bulk.vendor_id')
   ->leftJoin('tbl_mibl_master_brand', 'tbl_mibl_master_brand.id', '=', 'tbl_mibl_creatives_bulk.brand_id')
   ->leftJoin('tbl_mibl_master_category', 'tbl_mibl_master_category.id', '=', 'tbl_mibl_creatives_bulk.category_id')
   ->leftJoin('tbl_mibl_master_archive_category', 'tbl_mibl_master_archive_category.id', '=', 'tbl_mibl_creatives_bulk.archive_category_id')
   ->where('tbl_mibl_creatives_bulk.flag','miscellaneous')
   ->whereIn('tbl_mibl_creatives_bulk.status', [1, 2, 3])
->get();


return view('/miscellaneous/bulk_file_upload_miscellaneous',['verify' => $verify,'unverify'=>$unverify,'year_list'=>$year_list]);
  //return view('/admin/bulk_file_upload',['year_list' => $year_list]);
}





function insert_bluk_upload_miscellaneous(Request $request)
{ 



    $file=$request->file('csv_file');
    $handle=fopen($request->file('csv_file'),'r');
    while (($filesop = fgetcsv($handle, 1000, ",")) !== false) {
    // count($line) is the number of columns
    $numcols = count($filesop);
    }





if($numcols == 10)
{

  $file=$request->file('csv_file');
  $j=0;
  $sk=0;
  $handle=fopen($request->file('csv_file'),'r');
  while (($filesop = fgetcsv($handle, 1000, ",")) !== false) {
    $j++;
    $source_file = $filesop[9];
    if(!empty($source_file))
    {
    $sk++;
    }
    }
    $img_count=count($request->file('photo'));
    @$source_file_count=$request->file('source_file');
    if(!empty($source_file_count)){
    @$source_file_count=count($request->file('source_file'));
    }else
    {
      $source_file_count=0;
    }


   


 if(($j-1) == $img_count)
 {

if( ($sk-1) == $source_file_count)
 {
 



//csv file Upload
if ($request->hasfile('csv_file')) 
{
$image=$request->file('csv_file');
$filename_csv  = $image->getClientOriginalName();
$filenamecsv="uploads/csv_file/".$filename_csv;
if (!file_exists($filenamecsv)) {
$image->move('uploads/csv_file/', $filename_csv);
}
else
{
$characters='0123456789abcdefghijklmnopqrstuvwxyz';
$charactersLength = strlen($characters);
$randomString = '';
for ($i = 0; $i < 18; $i++) {
$randomString .= $characters[rand(0, $charactersLength - 1)];
}
$file_name = $_FILES["csv_file"]["name"];
$file_tmp = $_FILES["csv_file"]["tmp_name"];
$ext = pathinfo($file_name, PATHINFO_EXTENSION);
$filenamecsv = $randomString . '.' . $ext;
$filename_csv = $filenamecsv; 
$image->move('uploads/csv_file/', $filename_csv);
}
}

$user_id=session('id');
$user = DB::table('tbl_mibl_user')
->select('*')
->where('deleted_at','=',0)
->where('id',$user_id)
->orderBy('id', 'desc')
->first();
$username=$user->name;


$csvinsertid=DB::table('tbl_mibl_csv_file')->insertGetId([
'csv_file'=>$filename_csv,
'created_date'=>date('Y-m-d H:i:s'),
'created_by'=>$username,
]);

  
   
   # create directory of Year
   $year1=$request->input('year');
   $year = "uploads/".$year1;
   # create directory if not exists in upload/ directory
   if(!is_dir($year)){
     mkdir($year, 0777);
   }
  
    # create directory of Month
    $month1=$request->input('month');
    $month = "uploads/".$year1."/".$month1;
    $month_new = "uploads/".$year1."/".$month1;
    # create directory if not exists in upload/ directory
    if(!is_dir($month)){
      mkdir($month, 0777);
    }
    
    # create directory of Thumbnail
    $name_thumbnail='thumbnail';
    $name_thumbnail = "uploads/".$year1."/".$month1."/".$name_thumbnail;
    # create directory if not exists in upload/ directory
    if(!is_dir($name_thumbnail)){
      mkdir($name_thumbnail, 0777);
    }
    
    # create directory of Preview
    $name_preview='preview';
    $name_preview = "uploads/".$year1."/".$month1."/".$name_preview;
    # create directory if not exists in upload/ directory
    if(!is_dir($name_preview)){
      mkdir($name_preview, 0777);
    }
    
    # create directory of Original
    $name_original='original';
    $name_original = "uploads/".$year1."/".$month1."/".$name_original;
    # create directory if not exists in upload/ directory
    if(!is_dir($name_original)){
      mkdir($name_original, 0777);
    }
    # create directory of Original
    $name_upload_source_file='upload_source_file';
    $name_upload_source_file = "uploads/".$year1."/".$month1."/".$name_upload_source_file;
    # create directory if not exists in upload/ directory
    if(!is_dir($name_upload_source_file)){
      mkdir($name_upload_source_file, 0777);
    }

  if ($request->hasfile('photo')) 
  {
    foreach ($request->file('photo') as $image)
    {
      $mime= $image->getMimeType();
      if(strstr($mime, "image/"))
      {
        $filetype="image";


        $image=$image;
        $image_info = getimagesize($image);
      
        $original_width=$image_info[0];
        $original_height=$image_info[1];
        $ratio = 1.0;
        $scaled = false;
      
        // FIXME size should be configurable thumbnail
        if ($original_width > 200) {
          $ratio = 200 / $original_width;
          $width_t = $original_width * $ratio;
          $height_t = $original_height * $ratio;
          $scaled = true;
        } else {
          $width_t = $original_width;
          $height_t = $original_height;
        }
      
        if ($height_t > 200) {
          $ratio = 200 / $original_height;
          $width_t = $original_width * $ratio;
          $height_t = $original_height * $ratio;
          $scaled = true;
          } 
          
          
 
       // FIXME size should be configurable Preview
        if ($original_width > 500) {
         $ratio = 500 / $original_width;
         $width_p = $original_width * $ratio;
         $height_p = $original_height * $ratio;
         $scaled = true;
       } else {
         $width_p = $original_width;
         $height_p = $original_height;
       }
     
       if ($height_p > 500) {
         $ratio = 500 / $original_height;
         $width_p = $original_width * $ratio;
         $height_p = $original_height * $ratio;
         $scaled = true;
         }  
         

         $filename_c=$image->getClientOriginalName();

         $data = DB::table('tbl_mibl_creatives')
         ->select('*')
         ->where('photo_url',$filename_c)
         ->get();

         $data_bulk = DB::table('tbl_mibl_creatives_bulk')
         ->select('*')
         ->where('photo_url',$filename_c)
         ->get();
         
         if(count($data) == 0 && count($data_bulk) == 0){  
           $filename_new = $filename_c;
         }else
         {
          $filename_1 = pathinfo($filename_c, PATHINFO_FILENAME);
          $extension = pathinfo($filename_c, PATHINFO_EXTENSION);
          $filename_new = $filename_1."".$csvinsertid.".".$extension;
         }

       //Preview
        //$filename3 =$image->getClientOriginalName();
        $filename3=$filename_new;
        $image_resize= Image::make($image->getRealPath());
        $image_resize->resize($width_p, $height_p);
        $image_resize->save($name_preview.'/' .$filename3);
        
        //thumbnail
        //$filename4    = $image->getClientOriginalName();
        $filename4=$filename_new;
        $image_resize = Image::make($image->getRealPath());
        $image_resize->resize($width_t, $height_t);
        $image_resize->save($name_thumbnail.'/' .$filename4);

      // original
       //$filename1  = $image->getClientOriginalName();
       $filename1=$filename_new;
       $image->move($name_original.'/', $filename1);
        
      }else
      {

        $filename_c=$image->getClientOriginalName();
         $data = DB::table('tbl_mibl_creatives')
         ->select('*')
         ->where('photo_url',$filename_c)
         ->get();

         $data_bulk = DB::table('tbl_mibl_creatives_bulk')
         ->select('*')
         ->where('photo_url',$filename_c)
         ->get();
         
         if(count($data) == 0 && count($data_bulk) == 0){  
           $filename_new = $filename_c;
         }else
         {
          $filename_1 = pathinfo($filename_c, PATHINFO_FILENAME);
          $extension = pathinfo($filename_c, PATHINFO_EXTENSION);
          $filename_new = $filename_1."".$csvinsertid.".".$extension;
         }
 

            $filetype="other";

            //$filename1  = $image->getClientOriginalName();
            $filename1=$filename_new;
            $image->move($month_new.'/', $filename1);

      }
    }//foreach close

  }//image close



//Source bulk upload 

  
if ($request->file('source_file') != '') {

  foreach ($request->file('source_file') as $image)
  {


    $filename_c=$image->getClientOriginalName();
    $data = DB::table('tbl_mibl_creatives')
    ->select('*')
    ->where('source_file',$filename_c)
    ->get();

    $data_bulk = DB::table('tbl_mibl_creatives_bulk')
    ->select('*')
    ->where('source_file',$filename_c)
    ->get();
    
    if(count($data) == 0 && count($data_bulk) == 0){  
      $filename_new = $filename_c;
    }else
    {
     $filename_1 = pathinfo($filename_c, PATHINFO_FILENAME);
     $extension = pathinfo($filename_c, PATHINFO_EXTENSION);
     $filename_new = $filename_1."".$csvinsertid.".".$extension;
    }

  $filename2=$filename_new;
  $image->move($name_upload_source_file.'/', $filename2);
  }
}





     // Insert CSV Code
     
     $handle=fopen('uploads/csv_file/'.$filename_csv,'r');
 		  
     
      $c = 1;
      $user_id=session('id');
      $user = DB::table('tbl_mibl_user')
      ->select('*')
      ->where('deleted_at','=',0)
      ->where('id',$user_id)
      ->orderBy('id', 'desc')
      ->first();
      $username=$user->name;

    while (($filesop = fgetcsv($handle, 1000, ",")) !== false) {
            if($c != 1)
            {
                
                $id = $filesop[0];
                $file_name = $filesop[1];
                $file_description = $filesop[2];
                $brand = $filesop[3];
                $document_type = $filesop[4];
                $vendor = $filesop[5];
                $photo_url = $filesop[6];
                $other_document_type = $filesop[7];
                $file_type = $filesop[8];
                $source_file = $filesop[9];
                $advertisement_id="Miscellaneous";


            @$date_of_posting=$year1."-".$month1."-01";

            $brand_details =  DB::table('tbl_mibl_master_brand')->where('name', trim($brand))->first();
            @$brand_id = $brand_details->id;

            $document_type_details =  DB::table('tbl_mibl_master_document_type')->where('name', trim($document_type))->first();
            @$document_type_id = $document_type_details->id;

            $vendor_details =  DB::table('tbl_mibl_master_vendor')->where('name', trim($vendor))->first();
            @$vendor_id = $vendor_details->id;
            @$vendor_type_id = $vendor_details->vendor_type_id;
            
            $filename_check=$photo_url;
            $data = DB::table('tbl_mibl_creatives')
            ->select('*')
            ->where('photo_url',$filename_check)
            ->get();
            
            $data_bulk = DB::table('tbl_mibl_creatives_bulk')
            ->select('*')
            ->where('photo_url',$filename_check)
            ->get();
            
            if(count($data) == 0 && count($data_bulk) == 0){  
            $filename_new = $filename_check;
            }else
            {
            $filename_1 = pathinfo($filename_check, PATHINFO_FILENAME);
            $extension = pathinfo($filename_check, PATHINFO_EXTENSION);
            $filename_new = $filename_1."".$csvinsertid.".".$extension;
            }  

          //Source File 
            if(!empty($source_file)) { 
            $filename_checksource_file=$source_file;
            $data = DB::table('tbl_mibl_creatives')
            ->select('*')
            ->where('source_file',$filename_checksource_file)
            ->get();
            
            $data_bulk = DB::table('tbl_mibl_creatives_bulk')
            ->select('*')
            ->where('source_file',$filename_checksource_file)
            ->get();
            
            if(count($data) == 0 && count($data_bulk) == 0){  
            $filename_newsource_file = $filename_checksource_file;
            }else
            {
            $filename_1 = pathinfo($filename_checksource_file, PATHINFO_FILENAME);
            $extension = pathinfo($filename_checksource_file, PATHINFO_EXTENSION);
            $filename_newsource_file = $filename_1."".$csvinsertid.".".$extension;
            }
            }else
            {
            $filename_newsource_file ="";
            }

            if($file_type == 'image')
            {
            $filename="uploads/".$year1."/".$month1."/thumbnail/".$filename_new;
            if (file_exists($filename)) {
            $filename1="uploads/".$year1."/".$month1."/original/".$filename_new;
            
            $vision = new VisionClient(['keyFile'=> json_decode(file_get_contents("key4.json"),true)]);
            $imagepath = fopen($filename1,'r');
            $image = $vision->image($imagepath,['TEXT_DETECTION']);
            $result=$vision->annotate($image);
            // var_dump($result);
            $document = $result->fullText();
            $data = $document->text();
            $pattern = "([A-Z0-9/]+[A-Za-z0-9]+[^a-z-0-9]+([\/]\/{0,2})+(\d)+)";
            if(preg_match_all($pattern,  $data, $matches)) {
            $advertisement_id=strtoupper($matches[0][0]);
            }
            }
            
            }
            else
            {
             $filename="uploads/".$year1."/".$month1."/".$filename_new;
             $image_arr=explode(".",$filename_new);
             $doc_type=end($image_arr);
            
                /*PDF OCR Start*/
                if($doc_type == 'pdf')
                {
                if($advertisement_id == ''){
                if (file_exists($filename)) {
                $filename1="uploads/".$year1."/".$month1."/".$filename_new; 
                
                $path=$filename1;
                $pdf = file_get_contents($path);
                $number = preg_match_all("/\/Page\W/", $pdf, $dummy);
                if($number == 1)
                {
                $number=0;
                }else
                {
                $number=$number-1;
                }
                $photo_url=$filename_new;
                $arr_2=explode(".",$photo_url);
                $photo_url=$arr_2[0];
                $imgExt = new Imagick();
                $imgExt->setResolution(400,400);
                $imgExt->readImage($path."[$number]");
                $imgExt->writeImages('uploads/'.$year1.'/'.$month1.'/'.$photo_url.'.jpg', true);
                $filename_pdf=$photo_url.".jpg";
                
                $filename2='uploads/'.$year1.'/'.$month1.'/'.$filename_pdf;
                
                $vision = new VisionClient(['keyFile'=> json_decode(file_get_contents("key4.json"),true)]);
                $imagepath = fopen($filename2,'r');
                $image = $vision->image($imagepath,['TEXT_DETECTION']);
                $result=$vision->annotate($image);
                // var_dump($result);
                $document = $result->fullText();
                $data = $document->text();
                $pattern = "([A-Z0-9/]+[A-Za-z0-9]+[^a-z-0-9]+([\/]\/{0,2})+(\d)+)";
                if(preg_match_all($pattern,  $data, $matches)) {
                $advertisement_id=strtoupper($matches[0][0]);
                }
                
                $image_path1 =$filename2;
                if (file_exists($image_path1)) {
                @unlink($image_path1);
                }
                
                }
                }
                }
           /*PDF OCR End*/

            }

            if (file_exists($filename)) {
              $status=2;
            }else
            {
              $status=1;
            }
            //check document type vs upload image type
            $image_arr=explode(".",$filename_new);
            $image_type=end($image_arr);
            if($status == '2')
            {
            if($image_type == $document_type || $image_type == $other_document_type)
            {
              $status=3;
            }
            else
            {
              $status=$status;
            }
            }else
            {
              $status=$status;
            }


              if($status == '3' )
              {
              $data="0";
              $data_bulk="0";
              $advertisement_id="Miscellaneous";
              
              if($data == 0 && $data_bulk == 0 && $advertisement_id !=''){  
              $status=4;
              }
              else
              {
              $status=$status;
              }
              }else
              {
              $status=$status;
              }

              if($status == '3' && $image_type == 'mp4')
              {
                $status=4;
              }


            //Create created date code

            $month_count=strlen($month1);
            if($month_count == 1)
            {
            $month2="0".$month1;
            }else
            {
              $month2=$month1;
            }
            
            $advertisement_id="Miscellaneous";
            $insertGetId = DB::table('tbl_mibl_creatives_bulk')->insert([
            'file_name' => $file_name, 
            'advertisement_id' => $advertisement_id, 
            'file_description' => $file_description, 
            'brand_id' => $brand_id, 
            'document_type_id' => $document_type_id,
            'vendor_type_id' => $vendor_type_id,
            'vendor_id' => $vendor_id,
            'photo_url' => $filename_new,
            'other_document_type' =>$other_document_type,
            'file_type' => $file_type,
            'date_of_posting' => $date_of_posting,
            'date_of_upload' =>date('Y-m-d H:i:s'),
            'created_date' =>date('Y-m-d H:i:s'),
            'created_by' => $username,
            'status'=>$status,
            'source_file'=>$filename_newsource_file,
            'flag'=>"miscellaneous",
            ]);
          }
        $c ++;
	}


  session()->flash('successmsg', 'Files fetched successfully.');
  return redirect('add-bulk-file-upload-miscellaneous');
}else
{
  session()->flash('failmsg', 'Uploaded Source file count does not match with csv file.');
  return redirect('add-bulk-file-upload-miscellaneous');
}
}
else
{
  session()->flash('failmsg', 'Uploaded image count does not match with csv file.');
  return redirect('add-bulk-file-upload-miscellaneous');
}
}else
{
  session()->flash('failmsg', 'Kindly select correct csv file for Bulk upload. Please refer sample csv file provided.');
  return redirect('add-bulk-file-upload-miscellaneous');
}

}//main close



public function insert_bulk_creative_main_miscellaneous(Request $request)
{
  $ids =$request->get('ids');
  $count=count($ids);

  $user_id=session('id');
  $user = DB::table('tbl_mibl_user')
  ->select('*')
  ->where('deleted_at','=',0)
  ->where('id',$user_id)
  ->orderBy('id', 'desc')
  ->first();
  $username=$user->name;
  for($i=0;$i<$count;$i++)
  {
    $id=$ids[$i];
    
    $id_ssss=$ids[$i];
    
    $bulk_list = DB::table('tbl_mibl_creatives_bulk')
    ->select('*')
    ->where('id',$id)
    ->first();

    if(!empty($bulk_list)){


          //Video upload cloudflare
          $image_arr=explode(".",$bulk_list->photo_url);
          $doc_type=end($image_arr);
          $VIDEOID='';
        if($doc_type == 'mp4')
        {
            $year= date("Y", strtotime($bulk_list->date_of_posting));
            $month= date("m", strtotime($bulk_list->date_of_posting));            
            $filename_ne="uploads/$year/$month/$bulk_list->photo_url";    
            $photo=$filename_ne;
            $url="https://api.cloudflare.com/client/v4/accounts/34cc3252d5c329c1d2ac13237b4972ed/stream";
            $curl = curl_init();
            curl_setopt_array($curl, [
            CURLOPT_URL            => $url, // tmp url provided by cloudflare
            CURLOPT_RETURNTRANSFER => 1,
            CURLOPT_TIMEOUT        => 6000,
            CURLOPT_POST           => true,
            CURLOPT_POSTFIELDS     => ['file'=>new \CURLFile($photo),'video/mp4','test_name'],
            CURLOPT_HTTPHEADER     => [
            "X-Auth-Key: 43b3d73c452c8f2f536964033aa59622c3b9d","X-Auth-Email:marketing.mibl@gmail.com"
            ],
            ]);
            $response = curl_exec($curl);
            curl_close($curl);
            $response=json_decode($response);
            $result=$response->result;
            $VIDEOID=$result->uid;
            if (file_exists($filename_ne)) {
            @unlink($filename_ne);
            }

          }
          
          
    if ( date('m') > 3 ) {
    $year = date('y') + 1;
    }
    else {
    $year = date('y');
    }
    
    $data_s = DB::table('tbl_mibl_miscellaneous_advertisement_id')
    ->select('*')
    ->orderby('id','DESC')
    ->limit(1)
    ->first();

    if(!empty($data_s->id)){
    $id=$data_s->id+1;
    } else {
    $id=1;
    }
    if(strlen($id) == 1)
    {
    $serial_no='00'.$id;
    }
    else if(strlen($id) == 2)
    {
    $serial_no='0'.$id;
    }else 
    {
    $serial_no=$id;
    }
    
     $miscellaneous="MISC";
     $advertisement_id=strtoupper('F'.$year.'/'.$miscellaneous.'/'.$serial_no);
     
     
    $last_id=DB::table('tbl_mibl_miscellaneous_advertisement_id')->insertGetId([
    'advertisement_id'=>$advertisement_id,
    'remark'=>'',
    'created_date'=>date('Y-m-d H:i:s'),
    'created_by'=>$username
    ]);
      
          

    $last_id=DB::table('tbl_mibl_creatives')->insertGetId([
      'file_name'=>$bulk_list->file_name,
      'advertisement_id'=>$advertisement_id,
      'file_description'=>$bulk_list->file_description,
      'category_id'=>$bulk_list->category_id,
      'brand_id'=>$bulk_list->brand_id,
      'department_id'=>$bulk_list->department_id,
      'document_type_id'=>$bulk_list->document_type_id,
      'vendor_id'=>$bulk_list->vendor_id,
      'date_of_posting'=>$bulk_list->date_of_posting,
      'date_of_upload'=>$bulk_list->date_of_upload,
      'other_document_type'=>$bulk_list->other_document_type,
      'photo_url'=>$bulk_list->photo_url,
      'source_file'=>$bulk_list->source_file,
      'file_type'=>$bulk_list->file_type,
      'archive_category_id'=>$bulk_list->archive_category_id,
      'department_type_id'=>$bulk_list->department_type_id,
      'vendor_type_id'=>$bulk_list->vendor_type_id,
      'language_id'=>$bulk_list->language_id,
      'source_file'=>$bulk_list->source_file,
      'irdai_date'=>$bulk_list->irdai_date,
      'irdai_addressed'=>$bulk_list->irdai_addressed,
      'remark'=>$bulk_list->remark,
      'created_date'=>date('Y-m-d H:i:s'),
      'created_by'=>$username,
      'video_url'=>$VIDEOID,
      'type_of_creative'=>"miscellaneous",
      ]); 


      /*Insert user activity*/

      DB::table('tbl_mibl_user_activity')
      ->insert([
      'user_id' =>$user_id,
      'user_name'=>$username,
      'activity_group_id'=>$last_id,
      'messgage'=>'Bulk Miscellaneous Creative upload successfully',
      'activity_type'=>'Insert',
      'activity_group'=>'Bulk Miscellaneous Creative upload',
      'created_date' => date('Y-m-d H:i:s'),
      ]);  
 

      //delete bulk entry

        DB::table('tbl_mibl_creatives_bulk')
        ->where('id' , $id_ssss)
        ->delete();
    }   

  }
  
  return response()->json(['success'=>'200']);


}




function bulk_upload_clear_all_miscellaneous()
{
DB::table('tbl_mibl_creatives_bulk')
->where('tbl_mibl_creatives_bulk.flag','miscellaneous')
->whereIn('tbl_mibl_creatives_bulk.status', [1, 2, 3])
->delete();

session()->flash('successmsg', 'Rejected data cleared successfully.');
return redirect('add-bulk-file-upload-miscellaneous');
}



public function generate_csv_file_incomplete_miscellaneous(Request $request)
	{
		$contents = "id,file_name,file_description,brand,document_type,vendor,photo_url,other_document_type,file_type,Source_file\n";
		$i = 1;
        $data = DB::table('tbl_mibl_creatives_bulk')
        ->select('tbl_mibl_creatives_bulk.*',
        'tbl_mibl_master_archive_category.name as archive_name',
        'tbl_mibl_master_category.name as category_name',
        'tbl_mibl_master_brand.name as brand_name',
        'tbl_mibl_master_vendor.name as vendor_name',
        'tbl_mibl_master_department.name as department_name',
        'tbl_mibl_master_document_type.name as document_type_name',
        'tbl_mibl_master_department_type.department_type_name as department_type_name',
        'tbl_mibl_master_vendor_type.vendor_type_name as vendor_type_name',
        'tbl_mibl_master_language.language as language_name',
        'tbl_mibl_master_archive_sub_category.name as archive_sub_category')
        ->leftJoin('tbl_mibl_master_archive_sub_category', 'tbl_mibl_master_archive_sub_category.id', '=', 'tbl_mibl_creatives_bulk.archive_sub_category_id')      
        ->leftJoin('tbl_mibl_master_language', 'tbl_mibl_master_language.id', '=', 'tbl_mibl_creatives_bulk.language_id')      
        ->leftJoin('tbl_mibl_master_document_type', 'tbl_mibl_master_document_type.id', '=', 'tbl_mibl_creatives_bulk.document_type_id')
        ->leftJoin('tbl_mibl_master_department_type', 'tbl_mibl_master_department_type.id', '=', 'tbl_mibl_creatives_bulk.department_type_id')
        ->leftJoin('tbl_mibl_master_vendor_type', 'tbl_mibl_master_vendor_type.id', '=', 'tbl_mibl_creatives_bulk.vendor_type_id')
        ->leftJoin('tbl_mibl_master_department', 'tbl_mibl_master_department.id', '=', 'tbl_mibl_creatives_bulk.department_id')
        ->leftJoin('tbl_mibl_master_vendor', 'tbl_mibl_master_vendor.id', '=', 'tbl_mibl_creatives_bulk.vendor_id')
        ->leftJoin('tbl_mibl_master_brand', 'tbl_mibl_master_brand.id', '=', 'tbl_mibl_creatives_bulk.brand_id')
        ->leftJoin('tbl_mibl_master_category', 'tbl_mibl_master_category.id', '=', 'tbl_mibl_creatives_bulk.category_id')
        ->leftJoin('tbl_mibl_master_archive_category', 'tbl_mibl_master_archive_category.id', '=', 'tbl_mibl_creatives_bulk.archive_category_id')
        ->where('tbl_mibl_creatives_bulk.flag','miscellaneous')
        ->whereIn('tbl_mibl_creatives_bulk.status', [1, 2, 3])
        ->get();
    if(!empty($data)){
		foreach ($data as $key) {
        $contents .= $i . ",";
        $contents .= $key->file_name . ",";
        $contents .= $key->file_description . ",";
        $contents .= $key->brand_name . ",";
        $contents .= $key->document_type_name . ",";
        $contents .= $key->vendor_name . ",";
        $contents .= $key->photo_url . ",";
        $contents .= $key->other_document_type . ",";
        $contents .= $key->file_type . ",";
        $contents .= $key->source_file . "\n";
        $i++;
		}

//delete bulk entry

DB::table('tbl_mibl_creatives_bulk')
->where('tbl_mibl_creatives_bulk.flag','miscellaneous')
->whereIn('tbl_mibl_creatives_bulk.status', [1, 2, 3])
->delete();

$contents = strip_tags($contents);
header("Content-Disposition: attachment; filename=creativefile" . date('d-m-Y') . ".csv");
print $contents;

  }

}

























}

?>