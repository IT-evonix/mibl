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


use App\Newsletter;

class FlipbookController extends Controller
{

 function view_newsletter_old()
 {
    $from_date = (!empty($_GET["from_date"])) ? ($_GET["from_date"]) : ('');
    $to_date   = (!empty($_GET["to_date"])) ? ($_GET["to_date"]) : ('');
    if(!empty($from_date) || !empty($to_date))
    {
    $from_date = date('Y-m', strtotime($from_date));
    $to_date = date('Y-m', strtotime($to_date));
    }

    $result = DB::table('tbl_mibl_newsletter');
    $result->select('tbl_mibl_newsletter.*',
    'tbl_mibl_master_archive_category.name as archive_name',
    'tbl_mibl_master_archive_sub_category.name as archive_sub_name',
    'tbl_mibl_master_document_type.name as document_name','tbl_mibl_master_department_type.department_type_name as department_type_name' 
    ,'tbl_mibl_master_department.name as department_name','tbl_mibl_master_vendor.name as vendor_name'
    ,'tbl_mibl_master_vendor_type.vendor_type_name as vendor_type_name');
    $result->leftJoin('tbl_mibl_master_archive_category', 'tbl_mibl_master_archive_category.id', '=', 'tbl_mibl_newsletter.archive_category_id');
    $result->leftJoin('tbl_mibl_master_archive_sub_category', 'tbl_mibl_master_archive_sub_category.id', '=', 'tbl_mibl_newsletter.archive_sub_category_id');
    $result->leftJoin('tbl_mibl_master_document_type','tbl_mibl_newsletter.document_type_id','=','tbl_mibl_master_document_type.id');
    $result->leftJoin('tbl_mibl_master_department_type','tbl_mibl_newsletter.department_type_id','=','tbl_mibl_master_department_type.id');
    $result->leftJoin('tbl_mibl_master_department','tbl_mibl_newsletter.department_id','=','tbl_mibl_master_department.id');  
    $result->leftJoin('tbl_mibl_master_vendor','tbl_mibl_newsletter.vendor_id','=','tbl_mibl_master_vendor.id');
    $result->leftJoin('tbl_mibl_master_vendor_type','tbl_mibl_newsletter.vendor_type_id','=','tbl_mibl_master_vendor_type.id');
    $result->where('tbl_mibl_newsletter.active_yn',0);
    if(!empty($from_date) && !empty($to_date)){
    $fdate=explode("-",$from_date);
    $from_date1=$fdate[0]."".$fdate[1];
    $tdate=explode("-",$to_date);
    $to_date1=$tdate[0]."".$tdate[1];
    $result->whereRaw("DATE_FORMAT(tbl_mibl_newsletter.date_of_posting, '%Y%m') >= '" . $from_date1 . "' AND DATE_FORMAT(tbl_mibl_newsletter.date_of_posting, '%Y%m') <= '" . $to_date1 . "'");
    }
    $result->orderBy('tbl_mibl_newsletter.id','DESC');
    $creatives=$result->paginate(10);


    return view('admin/newsletter',['creatives'=>$creatives,'searchValue'=>'','from_date'=>$from_date,'to_date'=>$to_date]);

 }   


 public function view_newsletter(Request $request)
 {

$vendor_id = urldecode($request->vendor_id);
$advertisement_id = urldecode($request->advertisement_id);
$department_id = urldecode($request->department_id);

$query = request()->getQueryString();

if ($query) {
    $decoded = urldecode($query);

    if ((!empty($vendor_id) && !preg_match('/^\d+(,\d+)*$/', $vendor_id)) ||
	(!empty($advertisement_id) && !preg_match('/^[A-Za-z0-9\/]+$/', $advertisement_id))  ||
	(!empty($department_id) && !preg_match('/^\d+(,\d+)*$/', $department_id))

		|| preg_match('/<[^>]+>/', $decoded)) {
        return redirect()->to(request()->url());
    }
}

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

$from_date = (!empty($_GET["from_date"])) ? ($_GET["from_date"]) : ('');
$to_date = (!empty($_GET["to_date"])) ? ($_GET["to_date"]) : ('');
$vendor_name = (!empty($_GET["vendor_id"])) ? ($_GET["vendor_id"]) : ('');
$archive_category_ids = (!empty($_GET["archive_category_id"])) ? ($_GET["archive_category_id"]) : ('');
$department_id = (!empty($_GET["department_id"])) ? ($_GET["department_id"]) : ('');
$advertisement_id = (!empty($_GET["advertisement_id"])) ? ($_GET["advertisement_id"]) : ('');


   $result=DB::table('tbl_mibl_newsletter');
   $result->select('tbl_mibl_newsletter.*','tbl_mibl_master_archive_category.name as archive_name','tbl_mibl_master_category.name as category_name',
      'tbl_mibl_master_brand.name as brand_name','tbl_mibl_master_vendor.name as vendor_name',
      'tbl_mibl_master_department.name as department_name','tbl_mibl_master_document_type.name as document_type_name',
      'tbl_mibl_master_archive_sub_category.name as archive_sub_category_name',
      'tbl_mibl_master_department_type.department_type_name as department_type_name',
      'tbl_mibl_master_vendor_type.vendor_type_name as vendor_type_name',
      'tbl_mibl_master_language.language as language');
   $result->leftJoin('tbl_mibl_master_document_type', 'tbl_mibl_master_document_type.id', '=', 'tbl_mibl_newsletter.document_type_id');
   $result->leftJoin('tbl_mibl_master_archive_sub_category', 'tbl_mibl_master_archive_sub_category.id', '=', 'tbl_mibl_newsletter.archive_sub_category_id');
   $result->leftJoin('tbl_mibl_master_department', 'tbl_mibl_master_department.id', '=', 'tbl_mibl_newsletter.department_id');
   $result->leftJoin('tbl_mibl_master_vendor', 'tbl_mibl_master_vendor.id', '=', 'tbl_mibl_newsletter.vendor_id');
   $result->leftJoin('tbl_mibl_master_brand', 'tbl_mibl_master_brand.id', '=', 'tbl_mibl_newsletter.brand_id');
   $result->leftJoin('tbl_mibl_master_category', 'tbl_mibl_master_category.id', '=', 'tbl_mibl_newsletter.category_id');
   $result->leftJoin('tbl_mibl_master_archive_category', 'tbl_mibl_master_archive_category.id', '=', 'tbl_mibl_newsletter.archive_category_id');
   $result->leftJoin('tbl_mibl_master_department_type', 'tbl_mibl_master_department_type.id', '=', 'tbl_mibl_newsletter.department_type_id');
   $result->leftJoin('tbl_mibl_master_vendor_type', 'tbl_mibl_master_vendor_type.id', '=', 'tbl_mibl_newsletter.vendor_type_id');
   $result->leftJoin('tbl_mibl_master_language', 'tbl_mibl_master_language.id', '=', 'tbl_mibl_newsletter.language_id');
   if (!empty($vendor_name)) {
     $arr_2=explode(",",$vendor_name);
     $vendor_type_id=$arr_2[0];
     $vendor_id=$arr_2[1] ?? 0;
     $result->where('tbl_mibl_master_vendor_type.id', '=',$vendor_type_id);
     if($vendor_id != 0){
     $result->where('tbl_mibl_master_vendor.id', '=',$vendor_id);
     }
   }
   if (!empty($advertisement_id)) {
     $result->where('tbl_mibl_newsletter.advertisement_id', 'like', '%' .$advertisement_id. '%');
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
         $result->whereRaw("DATE_FORMAT(tbl_mibl_newsletter.date_of_posting, '%Y%m') >= '" . $from_date1 . "' AND DATE_FORMAT(tbl_mibl_newsletter.date_of_posting, '%Y%m') <= '" . $to_date1 . "'");
 
     }
   $result->orderBy('id','DESC');
   $details=$result->paginate(5);


   return view('admin/newsletter',
   ['creatives'=>$details,
   'from_date'=>$from_date,
   'to_date'=>$to_date,
   'advertisement_id'=>$advertisement_id,
   'department_id'=>$department_id,
   'archive_category_id'=>$archive_category_ids,
   'vendor_id'=>$vendor_name,
   'archive_c'=>$archive_c,
   'department_c'=>$department_c,
   'vendor_c'=>$vendor_c]);

 } 




 function upload_newsletter()
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
 
   return view('/admin/upload_newsletter', 
   ['category_list' => $category,
    'document_type_list' => $document_type,
    'brand_list' => $brand,
    'archive_c'=>$archive_c,
    'department_c'=>$department_c,
    'vendor_c'=>$vendor_c,
    'languages'=>$language]);
 }




function insert_newsletter(Request $request)
{
  
 $date_of_posting = date('Y-m', strtotime($request->input('date_of_posting')));
 $advertisement_id=$request->input('advertisement_id');
 
 $data = DB::table('tbl_mibl_newsletter')
 ->select('*')
 ->where('advertisement_id',$advertisement_id)
 ->get();

 if(trim($advertisement_id) !='' )
  {

 if( count($data) == 0 ){
    # create directory of Year
    $year1=date("Y", strtotime($request->input('date_of_posting')));
    $year = "newsletter/".$year1;
    # create directory if not exists in upload/ directory
    if(!is_dir($year)){
      mkdir($year, 0755);
    }
   
     # create directory of Month
     $month1=date("m", strtotime($request->input('date_of_posting')));
     $month = "newsletter/".$year1."/".$month1;

     $month_new = "newsletter/".$year1."/".$month1;

     # create directory if not exists in upload/ directory
     if(!is_dir($month)){
       mkdir($month, 0755);
     }

     # create directory of Original
     $name_upload_source_file='upload_source_file';
     $name_upload_source_file = "newsletter/".$year1."/".$month1."/".$name_upload_source_file;
     # create directory if not exists in upload/ directory
     if(!is_dir($name_upload_source_file)){
       mkdir($name_upload_source_file, 0755);
     }


    if(isset($_FILES['photo'])) 
     {
        $mime = $_FILES['photo']['type'];
        $image=$request->file('photo');
        $filename=$image->getClientOriginalName();  
  
        $data = DB::table('tbl_mibl_newsletter')
        ->select('*')
        ->where('photo_url',$filename)
        ->get();
        
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


        $filetype='other'; 
        $image=$request->file('photo');
        $filename=$filename_new;
        $file_name = $_FILES["photo"]["name"];
        $file_tmp  = $_FILES["photo"]["tmp_name"];
        $filename_ne  = $month_new.'/'.$filename;
        $arr_data['photo']=move_uploaded_file($file_tmp, env('BASE_PATH') . $filename_ne);

         /* $pdf_folder = "newsletter/".$year1."/".$month1."/".$randomString;
          # create directory if not exists in upload/ directory
          if(!is_dir($pdf_folder)){
          mkdir($pdf_folder, 0755);
          }
          
          $path="newsletter/".$year1."/".$month1."/".$randomString.".pdf";
          $imgExt = new Imagick();
          $imgExt->setResolution(400,400);
          $imgExt->readImage(public_path($path));
          $imgExt->writeImages('newsletter/'.$year1.'/'.$month1.'/'.$randomString.'/'.$randomString.'.jpg', true);
          */  
          
         if ($request->file('source_file') != '') {
           $image=$request->file('source_file');
           $filenamesource_file  = $image->getClientOriginalName();
           $data = DB::table('tbl_mibl_newsletter')
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
     }  else
     {
        $filename1=""; 
        $file_name="";
        $filetype=""; 
     }

       
     
$user_id=session('id');
$user = DB::table('tbl_mibl_user')
->select('*')
->where('deleted_at','=',0)
->where('id',$user_id)
->orderBy('id', 'desc')
->first();


$document_details = DB::table('tbl_mibl_master_document_type')
->select('*')
->where('deleted_at','=',0)
->where('name','pdf')
->first();


$username=$user->name;
$file_name = $request->input('file_name');
$advertisement_id = $request->input('advertisement_id');
$file_description = $request->input('file_description');
$brand_id = $request->input('brand_id');
$document_type_id = $document_details->id;
$date_of_posting = $date_of_posting."-01";
$date_of_upload=$request->input('date_of_upload');
$other_document_type=$request->input('other_document_type');
$archive_category_id=$request->input('archive_category_id');
$department_type_id=$request->input('department_type_id');
$vendor_type_id=$request->input('vendor_type_id');
$language_id=$request->input('language_id');
$remark=$request->input('remark');

$photo_url = $filename;
$source_file = $filename1;
$filetype = $filetype;

$archive_category_details_details = DB::table('tbl_mibl_master_archive_sub_category')
->select('*')
->where('deleted_at','=',0)
->where('name','Newsletters')
->first();
@$archive_category_id=$archive_category_details_details->archive_category_id;
@$archive_sub_category_id=$archive_category_details_details->id;

$arr_1=explode(",",$department_type_id);
$department_type_id=$arr_1[0];
$department_id=$arr_1[1];


$arr_2=explode(",",$vendor_type_id);
$vendor_type_id=$arr_2[0];
$vendor_id=$arr_2[1];


$last_id=DB::table('tbl_mibl_newsletter')->insertGetId([
 'file_name'=>$file_name,
 'advertisement_id'=>$advertisement_id,
 'file_description'=>$file_description,
 'brand_id'=>$brand_id,
 'department_id'=>$department_id,
 'document_type_id'=>$document_type_id,
 'vendor_id'=>$vendor_id,
 'date_of_posting'=>$date_of_posting,
 'date_of_upload'=>date('Y-m-d'),
 'photo_url'=>$photo_url,
 'source_file'=>$source_file,
 'file_type'=>$filetype,
 'archive_category_id'=>$archive_category_id,
 'archive_sub_category_id'=>$archive_sub_category_id,
 'department_type_id'=>$department_type_id,
 'vendor_type_id'=>$vendor_type_id,
 'language_id'=>$language_id,
 'remark'=>$remark,
 'created_date'=>date('Y-m-d H:i:s'),
 'created_by'=>$username,
 ]);

/*Insert user activity*/
DB::table('tbl_mibl_user_activity')
->insert([
'user_id' =>$user_id,
'user_name'=>$username,
'activity_group_id'=>$last_id,
'messgage'=>'Newsletter upload successfully',
'activity_type'=>'Insert',
'activity_group'=>'add single file upload',
'created_date' => date('Y-m-d H:i:s'),
]);  
 
 session()->flash('successmsg', 'Newsletter added successfully.');
 return redirect('/upload-newsletter');
}else
{
 session()->flash('failmsg', 'Advertisement id already exists.');
 return redirect('/upload-newsletter');
}
}else
{
  session()->flash('failmsg', 'Advertisement ID can not be blank');
  return redirect('/upload-newsletter');
}


}



public function flipbook($id){

$id = base64_decode($id); 
$data=DB::table('tbl_mibl_newsletter')->where('id','=',$id)->first();
return view('admin/flipbook_3d', ['data' => $data]);

}
    



    
public function edit_newsletter($id)
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
    $data = DB::table('tbl_mibl_newsletter')
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
return view('admin/edit_newsletter', 
['edit_services' => $data,
'category_list' => $category,
'document_type_list' => $document_type,
'brand_list' => $brand,
'archive_c'=>$archive_c,
'department_c'=>$department_c,
'vendor_c'=>$vendor_c,
'languages'=>$languages]);
}





public function update_newsletter(Request $request)
  {

    $advertisement_id=$request->input('advertisement_id');
    $id=$request->input('id');

    $data = DB::table('tbl_mibl_newsletter')
    ->select('*')
    ->where('advertisement_id',$advertisement_id)
    ->where('id','!=',$id)
    ->get();

    if( count($data) == 0 ){

    if($request->file('photo') != '') 
    {

    $mime = $_FILES['photo']['type'];
    $image=$request->file('photo');
    $filename=$image->getClientOriginalName();  
    $id=$request->input('id');
    $data = DB::table('tbl_mibl_creatives')
    ->select('*')
    ->where('id',$id)
    ->first();

    // $source_file_new= $data->source_file;


    $mime = $_FILES['photo']['type'];
    $image=$request->file('photo');
    $filename=$image->getClientOriginalName();  

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

    $year= date("Y", strtotime($request->input('date_of_posting')));
    $month= date("m", strtotime($request->input('date_of_posting')));
    $name_upload_source_file='upload_source_file';
    $name_upload_source_file = "newsletter/".$year."/".$month."/".$name_upload_source_file;
    $month_new = "newsletter/".$year."/".$month;
    $file_type=$request->input('file_type');
    
    $filetype='other';   
    $image=$request->file('photo');
    $filename=$filename_new;
    $file_name = $_FILES["photo"]["name"];
    $file_tmp  = $_FILES["photo"]["tmp_name"];
    $filename_ne  = $month_new.'/'.$filename;
    $arr_data['photo']=move_uploaded_file($file_tmp, env('BASE_PATH') . $filename_ne);

    $photo_url = $filename;           
    DB::table('tbl_mibl_newsletter')
    ->where('id', $id)
    ->update([
    'photo_url'=>$photo_url,
    'file_type'=>$filetype,
    ]);

    /*$pdf_folder = "newsletter/".$year."/".$month."/".$randomString;
    # create directory if not exists in upload/ directory
    if(!is_dir($pdf_folder)){
    mkdir($pdf_folder, 0755);
    }
    
    $path="newsletter/".$year."/".$month."/".$randomString.".pdf";
    $imgExt = new Imagick();
    $imgExt->setResolution(400,400);
    $imgExt->readImage(public_path($path));
    $imgExt->writeImages('newsletter/'.$year.'/'.$month.'/'.$randomString.'/'.$randomString.'.jpg', true);
   */

    }


    if ($request->file('source_file') != '') {

    $image=$request->file('source_file');
    $filenamesource_file=$image->getClientOriginalName();  
    $data = DB::table('tbl_mibl_newsletter')
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

    $year= date("Y", strtotime($request->input('date_of_posting')));
    $month= date("m", strtotime($request->input('date_of_posting')));

    $name_upload_source_file='upload_source_file';
    $name_upload_source_file = "newsletter/".$year."/".$month."/".$name_upload_source_file; 

    $image     = $request->file('source_file');
    $filename1  =$filename_sourcefile;
    $file_name = $_FILES["source_file"]["name"];
    $file_tmp  = $_FILES["source_file"]["tmp_name"];
    $filename_n = $name_upload_source_file.'/'.$filename1;
    $arr_data['source_file']=move_uploaded_file($file_tmp, env('BASE_PATH') . $filename_n);
    $source_file = $filename1;
    DB::table('tbl_mibl_newsletters')
    ->where('id', $id)
    ->update([
    'source_file'=>$source_file,
    ]);

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
    $advertisement_id = $request->input('advertisement_id');
    $file_description = $request->input('file_description');
    $brand_id = $request->input('brand_id');
    $date_of_posting = $request->input('date_of_posting')."-01";
    $date_of_upload=$request->input('date_of_upload');
    $archive_category_id=$request->input('archive_category_id');
    $department_type_id=$request->input('department_type_id');
    $vendor_type_id=$request->input('vendor_type_id');
    $active_yn=$request->input('active_yn');
    $language_id=$request->input('language_id');
    $remark=$request->input('remark');


    $arr_1=explode(",",$department_type_id);
    $department_type_id=$arr_1[0];
    $department_id=$arr_1[1];

    $arr_2=explode(",",$vendor_type_id);
    $vendor_type_id=$arr_2[0];
    $vendor_id=$arr_2[1];


    DB::table('tbl_mibl_newsletter')
    ->where('id', $id)
    ->update([
    'file_name'=>$file_name,
    'advertisement_id'=>$advertisement_id,
    'file_description'=>$file_description,
    'brand_id'=>$brand_id,
    'department_id'=>$department_id,
    'vendor_id'=>$vendor_id,
    'department_type_id'=>$department_type_id,
    'vendor_type_id'=>$vendor_type_id,
    'active_yn'=>$active_yn,
    'language_id'=>$language_id,
    'remark'=>$remark,
    'modify_date'=>date('Y-m-d H:i:s'),
    'created_by'=>$username,
    ]);

    $last_id=$id;
    DB::table('tbl_mibl_user_activity')
    ->insert([
    'user_id' =>$user_id,
    'user_name'=>$username,
    'activity_group_id'=>$last_id,
    'messgage'=>'Newsletter updated successfully',
    'activity_type'=>'Updated',
    'activity_group'=>'Creative',
    'created_date' => date('Y-m-d H:i:s'),
    ]);

    session()->flash('successmsg', 'Newsletter updated successfully.');
    return redirect('/view-newsletter');
    }else
    {
    session()->flash('failmsg', 'Advertisement id already exists.');
    return redirect('/view-newsletter');
    }

  }

 






}