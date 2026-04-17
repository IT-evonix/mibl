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



class AdminController extends Controller
{

/* Login */







function sso(Request $request)
{
//$url ='https://esb.mmfsl.com/mahindrafinance/production/ldap/cryptography';
$url ='https://api.mmfsl.com/e-ldap/v1/cryptography';
$data = array("EntityUserPassword" => "Mahindra123");

$postdata = json_encode($data);

$ch = curl_init($url); 
curl_setopt($ch, CURLOPT_POST, 1);
curl_setopt($ch, CURLOPT_POSTFIELDS, $postdata);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); 
//curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json','x-ibm-client-id:695c2c87-e0dc-448c-a672-1be8401e346f'));
curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json','apikey:AsQQC6MoIXbxQDfNKOtxIC5aGvKdeqoyJNAMm6Kk2kA0NC7I'));
$result = curl_exec($ch);
curl_close($ch);
$result=json_decode($result);
$HashValue=$result->HashValue;


//$url ='https://esb.mmfsl.com/mahindrafinance/production/ldap/userauthentication';
$url ='https://api.mmfsl.com/e-ldap/v1/userauthentication';
$data = array("EntityUserName" => "27001989","EntityUserPassword"=>$HashValue);
$postdata = json_encode($data);
$ch = curl_init($url); 
curl_setopt($ch, CURLOPT_POST, 1);
curl_setopt($ch, CURLOPT_POSTFIELDS, $postdata);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); 
//curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json','x-ibm-client-id:695c2c87-e0dc-448c-a672-1be8401e346f'));
curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json','apikey:AsQQC6MoIXbxQDfNKOtxIC5aGvKdeqoyJNAMm6Kk2kA0NC7I'));
$result = curl_exec($ch);
curl_close($ch);
var_dump($result);
$result=json_decode($result);
echo $status=$result->Status;


}









public function logincheck(Request $request)
  { 
            //validation : email_id and password
            $validator = Validator::make($request->all(), [
            'sap_code' => 'required',
            'password' => 'required',
            'user_login_type' => 'required',
            ]);
            if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
            }
            // process form
         
     
            $user_login_type=$request->user_login_type;

            if($user_login_type == 'Employee'){
           //SSO   

            // $url ='https://esb.mmfsl.com/mahindrafinance/production/ldap/cryptography'; //LIVE
            // $url ='https://esbuat.mmfsl.com/mahindrafinance/uat/ldap/cryptography';       //UAT
			$url ='https://api.mmfsl.com/e-ldap/v1/cryptography'; //LIVE
            $data = array("EntityUserPassword" =>$request->password);
            
            $postdata = json_encode($data);
            
            $ch = curl_init($url); 
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $postdata);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); 
            //curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json','x-ibm-client-id:695c2c87-e0dc-448c-a672-1be8401e346f')); //LIVE
            // curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json','x-ibm-client-id:c900c140-52ce-4fdb-9cfb-17e37a98998f'));   //UAT
            curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json','apikey:AsQQC6MoIXbxQDfNKOtxIC5aGvKdeqoyJNAMm6Kk2kA0NC7I')); // LIVE
		   $result = curl_exec($ch);
            curl_close($ch);
            $result=json_decode($result);
            @$HashValue=$result->HashValue;
           
            
           // $url ='https://esb.mmfsl.com/mahindrafinance/production/ldap/userauthentication'; // LIVE
            // $url ='https://esbuat.mmfsl.com/mahindrafinance/uat/ldap/userauthentication';   // UAT
			$url ='https://api.mmfsl.com/e-ldap/v1/userauthentication'; //LIVE
            $data = array("EntityUserName" => $request->sap_code,"EntityUserPassword"=>$HashValue);
            $postdata = json_encode($data);
            $ch = curl_init($url); 
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $postdata);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); 
			//curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json','x-ibm-client-id:695c2c87-e0dc-448c-a672-1be8401e346f'));//LIVE
			// curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json','x-ibm-client-id:c900c140-52ce-4fdb-9cfb-17e37a98998f')); //UAT
			curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json','apikey:AsQQC6MoIXbxQDfNKOtxIC5aGvKdeqoyJNAMm6Kk2kA0NC7I')); // LIVE
		    $result = curl_exec($ch);
            curl_close($ch);
 
            //var_dump($result);die;
            $result=json_decode($result);

            //print_r($result);die;
            @$status=$result->Status;
            
            //SSO


         if($status == 'Success')
         {
          
         if (isset($request->sap_code)) {
            $sap_code=$request->sap_code;
            $password=$request->password;
            $admin = DB::table('tbl_mibl_user')
            ->select('id','email','sap_code','password','name','user_type')
            ->where('sap_code', '=', $sap_code)
            ->where('flag', '=', 1)
            ->where('active_yn', '=', 0)
            ->first();

        if(!empty($admin))
         {
          
            
        //   $credentials = [
        //     'sap_code' => $sap_code,
        //     'password' => 'password',
        //   ];

        // if(Auth::attempt($credentials)) {

          DB::table('tbl_mibl_user')
          ->where('id',$admin->id)
          ->update([
          'last_login_date' =>date('Y-m-d'),
          ]);


            Session::put('mibladmin', $admin->id);

            $user_access = DB::table('tbl_mibl_user_access')
            ->select('user_type_id','search_ad','advance_search',
                     'download_creative','bulk_upload_files',
                     'single_file_upload','agreement','approve_creatives','advertisement_id_list','manage_newsletter','upload_newsletter','notification','manage_report','manage_miscellaneous','manage_adaptation')
            ->where('user_id','=',$admin->id)
            ->first();

            

            
            @Session::put('name',$admin->name);
            @Session::put('login_code',$admin->sap_code);
            @Session::put('login_type',$admin->user_type);
            @Session::put('id',$admin->id);
            @Session::put('user_login_type',$user_login_type);

            @Session::put('user_search_ad',$user_access->search_ad);
            @Session::put('user_advance_search',$user_access->advance_search); 
            @Session::put('user_download_creative',$user_access->download_creative); 
            @Session::put('user_bulk_upload_files',$user_access->bulk_upload_files); 
            @Session::put('user_single_file_upload',$user_access->single_file_upload); 
            @Session::put('user_agreement',$user_access->agreement); 
            @Session::put('user_approve_creatives',$user_access->approve_creatives); 
            @Session::put('user_advertisement_id_list',$user_access->advertisement_id_list); 
            
            @Session::put('user_manage_newsletter',$user_access->manage_newsletter); 
            @Session::put('user_upload_newsletter',$user_access->upload_newsletter); 
            @Session::put('user_notification',$user_access->notification); 
            @Session::put('user_manage_report',$user_access->manage_report); 
            @Session::put('user_manage_miscellaneous',$user_access->manage_miscellaneous); 
            @Session::put('user_manage_adaptation',$user_access->manage_adaptation); 
            
            $user_type_access = DB::table('tbl_mibl_user_type_access')
            ->select('search_ad','advance_search',
                     'download_creative','bulk_upload_files',
                     'single_file_upload','agreement','approve_creatives','advertisement_id_list','manage_newsletter','upload_newsletter','notification','manage_report','manage_miscellaneous','manage_adaptation')
            ->where('user_type_id','=',@$user_access->user_type_id)
            ->first();
            
            @Session::put('user_type_search_ad',$user_type_access->search_ad);
            @Session::put('user_type_advance_search',$user_type_access->advance_search); 
            @Session::put('user_type_download_creative',$user_type_access->download_creative); 
            @Session::put('user_type_bulk_upload_files',$user_type_access->bulk_upload_files); 
            @Session::put('user_type_single_file_upload',$user_type_access->single_file_upload); 
            @Session::put('user_type_agreement',$user_type_access->agreement); 
            @Session::put('user_type_approve_creatives',$user_type_access->approve_creatives); 
            @Session::put('user_type_advertisement_id_list',$user_type_access->advertisement_id_list); 
            
            @Session::put('user_type_manage_newsletter',$user_type_access->manage_newsletter); 
            @Session::put('user_type_upload_newsletter',$user_type_access->upload_newsletter); 
            @Session::put('user_type_notification',$user_type_access->notification); 
            @Session::put('user_type_manage_report',$user_type_access->manage_report);
            @Session::put('user_type_manage_miscellaneous',$user_type_access->manage_miscellaneous); 
            @Session::put('user_type_manage_adaptation',$user_type_access->manage_adaptation);
            
            
              DB::table('tbl_mibl_logs')->insert([
                'user_id' =>$admin->id,
                'user_name' =>$admin->name,
                'ip_address'=>request()->ip(),
                'created_date' => date('Y-m-d H:i:s'),
                ]);
                // echo 'hii';die;
            if($admin->user_type == 'Auditor User') {   
            return redirect()->intended('/view-creatives-irdai');
            }else
            {
              
            return redirect()->intended('/admin-dashboard'); 
            
            }
           // }

            }else{
            session()->flash('failmsg', 'Kindly contact Admin to access this website');
            return back()->withErrors(['message' => "Invalid email "]);
            }
        }
        

      }
      else {
        session()->flash('failmsg', 'Invalid Username or Password');
        return back()->withErrors(['message' => "Invalid  password"]);
        }

  }else
  {

            if($user_login_type == 'Vendor'){
           
            if(isset($request->sap_code)) {
            $vendor_code=$request->sap_code;
            $password=$request->password;
            $admin = DB::table('tbl_mibl_master_vendor')
            ->select('id','email','vendor_code','password','name','vendor_type_id')
            ->where('vendor_code', '=', $vendor_code)
            ->where('flag', '=', 1)
            ->where('active_yn', '=', 0)
            ->first();

            if(!empty($admin))
            {
            $hash_password=$admin->password;
          if (password_verify($password, $hash_password)) {

            Session::put('mibladmin', $admin->id);

            $user_access = DB::table('tbl_mibl_user_access')
            ->select('user_type_id','search_ad','advance_search',
            'download_creative','bulk_upload_files',
            'single_file_upload','agreement','advertisement_id_list','approve_creatives','manage_newsletter','upload_newsletter','notification','manage_report','manage_miscellaneous','manage_adaptation')
            ->where('user_id','=','asw12')
            ->first();

            @Session::put('name',$admin->name);
            @Session::put('login_code',$admin->vendor_code);
            @Session::put('login_type',$admin->vendor_type_id);
            @Session::put('id',$admin->id);
            @Session::put('user_login_type',$user_login_type);

            @Session::put('user_search_ad',$user_access->search_ad);
            @Session::put('user_advance_search',$user_access->advance_search); 
            @Session::put('user_download_creative',$user_access->download_creative); 
            @Session::put('user_bulk_upload_files',$user_access->bulk_upload_files); 
            @Session::put('user_single_file_upload',$user_access->single_file_upload); 
            @Session::put('user_agreement',$user_access->agreement); 
            @Session::put('user_approve_creatives',$user_access->approve_creatives); 
            @Session::put('user_advertisement_id_list',$user_access->advertisement_id_list); 


            @Session::put('user_manage_newsletter',$user_access->manage_newsletter); 
            @Session::put('user_upload_newsletter',$user_access->upload_newsletter); 
            @Session::put('user_notification',$user_access->notification);
            @Session::put('user_manage_report',$user_access->manage_report);
            @Session::put('user_manage_miscellaneous',$user_access->manage_miscellaneous); 
            @Session::put('user_manage_adaptation',$user_access->manage_adaptation); 


            $user_type_access = DB::table('tbl_mibl_user_type_access')
            ->select('search_ad','advance_search',
            'download_creative','bulk_upload_files',
            'single_file_upload','agreement','advertisement_id_list','approve_creatives','manage_newsletter','upload_newsletter','notification','manage_report','manage_miscellaneous','manage_adaptation')
            ->where('user_type_id','=',@$user_access->user_type_id)
            ->first();

            @Session::put('user_type_search_ad',$user_type_access->search_ad);
            @Session::put('user_type_advance_search',$user_type_access->advance_search); 
            @Session::put('user_type_download_creative',$user_type_access->download_creative); 
            @Session::put('user_type_bulk_upload_files',$user_type_access->bulk_upload_files); 
            @Session::put('user_type_single_file_upload',$user_type_access->single_file_upload); 
            @Session::put('user_type_agreement',$user_type_access->agreement); 
            @Session::put('user_type_approve_creatives',$user_type_access->approve_creatives); 
            @Session::put('user_type_advertisement_id_list',$user_type_access->advertisement_id_list); 

            @Session::put('user_type_manage_newsletter',$user_type_access->manage_newsletter); 
            @Session::put('user_type_upload_newsletter',$user_type_access->upload_newsletter); 
            @Session::put('user_type_notification',$user_type_access->notification); 
            @Session::put('user_type_manage_report',$user_type_access->manage_report);
            @Session::put('user_type_manage_miscellaneous',$user_type_access->manage_miscellaneous); 
            @Session::put('user_type_manage_adaptation',$user_type_access->manage_adaptation);
            

            DB::table('tbl_mibl_logs')->insert([
            'user_id' =>$admin->id,
            'user_name' =>$admin->name,
            'ip_address'=>request()->ip(),
            'created_date' => date('Y-m-d H:i:s'),
            ]);
            // echo 'hii';die;

            return redirect()->intended('/view-creative-vendor'); 
            }else
            {
              session()->flash('failmsg', 'Invalid Username or Password');
              return back()->withErrors(['message' => "Invalid email "]);  

            }

            }else{
            session()->flash('failmsg', 'Kindly contact Admin to access this website');
            return back()->withErrors(['message' => "Invalid email "]);
            }
            }
            
            }else
            {
             
              if(isset($request->sap_code)) {
            $sap_code=$request->sap_code;
            $password=$request->password;
            $admin = DB::table('tbl_mibl_auditor')
            ->select('id','email','sap_code','password','name','user_type')
            ->where('sap_code', '=', $sap_code)
            ->where('flag', '=', 1)
            ->where('active_yn', '=', 0)
            ->first();

            if(!empty($admin))
            {
            $hash_password=$admin->password;
          if (password_verify($password, $hash_password)) {

            Session::put('mibladmin', $admin->id);

            $user_access = DB::table('tbl_mibl_user_access')
            ->select('user_type_id','search_ad','advance_search',
            'download_creative','bulk_upload_files',
            'single_file_upload','agreement','advertisement_id_list','approve_creatives','manage_newsletter','upload_newsletter','notification','manage_report','manage_miscellaneous','manage_adaptation')
            ->where('user_id','=','asw12')
            ->first();

            @Session::put('name',$admin->name);
            @Session::put('login_code',$admin->vendor_code);
            @Session::put('login_type',$admin->user_type);
            @Session::put('id',$admin->id);
            @Session::put('user_login_type',$user_login_type);

            @Session::put('user_search_ad',$user_access->search_ad);
            @Session::put('user_advance_search',$user_access->advance_search); 
            @Session::put('user_download_creative',$user_access->download_creative); 
            @Session::put('user_bulk_upload_files',$user_access->bulk_upload_files); 
            @Session::put('user_single_file_upload',$user_access->single_file_upload); 
            @Session::put('user_agreement',$user_access->agreement); 
            @Session::put('user_approve_creatives',$user_access->approve_creatives); 
            @Session::put('user_advertisement_id_list',$user_access->advertisement_id_list); 


            @Session::put('user_manage_newsletter',$user_access->manage_newsletter); 
            @Session::put('user_upload_newsletter',$user_access->upload_newsletter); 
            @Session::put('user_notification',$user_access->notification);
            @Session::put('user_manage_report',$user_access->manage_report);
            @Session::put('user_manage_miscellaneous',$user_access->manage_miscellaneous); 
            @Session::put('user_manage_adaptation',$user_access->manage_adaptation); 


            $user_type_access = DB::table('tbl_mibl_user_type_access')
            ->select('search_ad','advance_search',
            'download_creative','bulk_upload_files',
            'single_file_upload','agreement','advertisement_id_list','approve_creatives','manage_newsletter','upload_newsletter','notification','manage_report','manage_miscellaneous','manage_adaptation')
            ->where('user_type_id','=',@$user_access->user_type_id)
            ->first();

            @Session::put('user_type_search_ad',$user_type_access->search_ad);
            @Session::put('user_type_advance_search',$user_type_access->advance_search); 
            @Session::put('user_type_download_creative',$user_type_access->download_creative); 
            @Session::put('user_type_bulk_upload_files',$user_type_access->bulk_upload_files); 
            @Session::put('user_type_single_file_upload',$user_type_access->single_file_upload); 
            @Session::put('user_type_agreement',$user_type_access->agreement); 
            @Session::put('user_type_approve_creatives',$user_type_access->approve_creatives); 
            @Session::put('user_type_advertisement_id_list',$user_type_access->advertisement_id_list); 

            @Session::put('user_type_manage_newsletter',$user_type_access->manage_newsletter); 
            @Session::put('user_type_upload_newsletter',$user_type_access->upload_newsletter); 
            @Session::put('user_type_notification',$user_type_access->notification); 
            @Session::put('user_type_manage_report',$user_type_access->manage_report);
            @Session::put('user_type_manage_miscellaneous',$user_type_access->manage_miscellaneous); 
            @Session::put('user_type_manage_adaptation',$user_type_access->manage_adaptation);
            

            DB::table('tbl_mibl_logs')->insert([
            'user_id' =>$admin->id,
            'user_name' =>$admin->name,
            'ip_address'=>request()->ip(),
            'created_date' => date('Y-m-d H:i:s'),
            ]);
            // echo 'hii';die;

            return redirect()->intended('/view-auditor-creative'); 
            }else
            {
              session()->flash('failmsg', 'Invalid Username or Password');
              return back()->withErrors(['message' => "Invalid email "]);  

            }

            }else{
            session()->flash('failmsg', 'Kindly contact Admin to access this website');
            return back()->withErrors(['message' => "Invalid email "]);
            }
            }    
                
                
                
            }



  }      
  
  
}





















/*public function logincheck(Request $request)
  { 
            //validation : email_id and password
            $validator = Validator::make($request->all(), [
            'email' => 'required',
            'password' => 'required',
            ]);
            if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
            }
            // process form
            if (isset($request->email)) {
            $email=$request->email;
            $password=$request->password;
            $admin = DB::table('tbl_mibl_user')
            ->select('id','email','password','name')
            ->where('email', '=', $email)
            ->first();
        if(!empty($admin))
        {
            $password_hash=$admin->password;
            if (password_verify($password, $password_hash)) {
            Session::put('mibladmin', $admin->id);
            
            $credentials = $request->only('email', 'password');
            if (Auth::attempt($credentials)) {


            $user_access = DB::table('tbl_mibl_user_access')
            ->select('user_type_id','search_ad','advance_search',
                     'download_creative','bulk_upload_files',
                     'single_file_upload','agreement')
            ->where('user_id','=',$admin->id)
            ->first();
             
            Session::put('user_search_ad',$user_access->search_ad);
            Session::put('user_advance_search',$user_access->advance_search); 
            Session::put('user_download_creative',$user_access->download_creative); 
            Session::put('user_bulk_upload_files',$user_access->bulk_upload_files); 
            Session::put('user_single_file_upload',$user_access->single_file_upload); 
            Session::put('user_agreement',$user_access->agreement); 

            $user_type_access = DB::table('tbl_mibl_user_type_access')
            ->select('search_ad','advance_search',
                     'download_creative','bulk_upload_files',
                     'single_file_upload','agreement')
            ->where('user_type_id','=',$user_access->user_type_id)
            ->first();
            
            Session::put('user_type_search_ad',$user_type_access->search_ad);
            Session::put('user_type_advance_search',$user_type_access->advance_search); 
            Session::put('user_type_download_creative',$user_type_access->download_creative); 
            Session::put('user_type_bulk_upload_files',$user_type_access->bulk_upload_files); 
            Session::put('user_type_single_file_upload',$user_type_access->single_file_upload); 
            Session::put('user_type_agreement',$user_type_access->agreement); 
            


              DB::table('tbl_mibl_logs')->insert([
                'user_id' =>$admin->id,
                'user_name' =>$admin->name,
                'ip_address'=>request()->ip(),
                'created_date' => date('Y-m-d H:i:s'),
                ]);
            if(auth()->user()->user_type == 'Auditor User') {   
            return redirect()->intended('/view-creatives-irdai');
            }else
            {
            return redirect()->intended('/view-search'); 
            }
            }
            //return redirect('view_brand');
            } else {
            session()->flash('failmsg', 'Invalid Password');
            return back()->withErrors(['message' => "Invalid  password"]);
            }
            }else{
            session()->flash('failmsg', 'Invalid Email Address');
            return back()->withErrors(['message' => "Invalid email "]);
            }
        }
  }
*/

// User Type 
  
public function view_user_type(Request $request)
{
return view('admin/view_user_type');
}


public function add_user_type(Request $request)
{

 return view('admin/add_user_type');
}

public function insert_user_type(Request $request)
{

$user_type_name = $request->input('user_type_name');

$data = DB::table('tbl_mibl_master_user_type')
  ->select('*')
  ->where('user_type_name',$request->input('user_type_name'))
  ->get();
if(count($data)== '0'){  


$user_id=session('id');
$user = DB::table('tbl_mibl_user')
->select('*')
->where('deleted_at','=',0)
->where('id',$user_id)
->orderBy('id', 'desc')
->first();
$username=$user->name;


DB::table('tbl_mibl_master_user_type')->insert([
    'user_type_name' =>$user_type_name,
    'created_date' => date('Y-m-d H:i:s'),
    'created_by'=>$username
    ]);

/*Insert user activity*/

DB::table('tbl_mibl_user_activity')
     ->insert([
      'user_id' =>$user_id,
      'user_name'=>$username,
      'messgage'=>'User type added successfully',
      'activity_type'=>'Insert',
      'activity_group'=>'User Type',
      'created_date' => date('Y-m-d H:i:s'),
]);  
   
    session()->flash('successmsg', 'User type added successfully.');
    return redirect('view-user-type');
  }else
  {
    session()->flash('failmsg', 'User type already exists.');
    return redirect('view-user-type');
  }
}

public function edit_user_type($id)
{

$id = base64_decode($id); 

$data = DB::table('tbl_mibl_master_user_type')
->select('*')
->where('id', '=', $id)
->get();

return view('admin/edit_user_type', ['edit_services' => $data]);
}


public function update_user_type(Request $request)
{

$user_type_name = $request->input('user_type_name');
$active_yn = $request->input('active_yn');
$id = $request->input('id');


$data = DB::table('tbl_mibl_master_user_type')
  ->select('*')
  ->where('user_type_name',$request->input('user_type_name'))
  ->where('id','!=',$id)
  ->get();


  
if(count($data)== '0'){  

DB::table('tbl_mibl_master_user_type')
->where('id', $id)
->update([
'user_type_name' => $user_type_name,
'active_yn' => $active_yn,
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

$last_id = $request->input('id');
DB::table('tbl_mibl_user_activity')
     ->insert([
      'user_id' =>$user_id,
      'user_name'=>$username,
      'activity_group_id'=>$last_id,
      'messgage'=>'User type updated successfully',
      'activity_type'=>'Updated',
      'activity_group'=>'User Type',
      'created_date' => date('Y-m-d H:i:s'),
      ]);


session()->flash('successmsg', 'User type updated successfully.');
return redirect('view-user-type');
}
else
  {
    session()->flash('failmsg', 'User type already exists.');
    return redirect('view-user-type');
  }
}


public function delete_user_type($id)
{
DB::table('tbl_mibl_master_user_type')
->where('id', $id)
->update([
'deleted_at' =>1,
]);

session()->flash('successmsg', 'User type deleted successfully.');
return redirect('welcome');
}

public function getuser_type(Request $request){

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


   //search date 
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
    
    // Total records
    $totalRecords = User_type::select('count(*) as allcount')->count();
    $totalRecordswithFilter = User_type::select('count(*) as allcount')
                             ->where('user_type_name', 'like', '%' .$searchValue . '%')
                             ->orWhere('active_yn', 'like', '%' .$status . '%')
                             ->orWhere('created_date', 'like', '%' .$created_dated. '%')
                             ->count();

    // Fetch records
    $records = User_type::orderBy($columnName,$columnSortOrder)
      ->where('tbl_mibl_master_user_type.user_type_name', 'like', '%' .$searchValue . '%')
      ->orWhere('tbl_mibl_master_user_type.active_yn', 'like', '%' .$status . '%')
      ->orWhere('tbl_mibl_master_user_type.created_date', 'like', '%' .$created_dated. '%')
      ->select('tbl_mibl_master_user_type.*')
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
        $created_date= date("d/m/Y", strtotime($record->created_date));
       }

       if(!empty($record->id))
       {
        $APP_URL=$_ENV['APP_URL']."edit-user-type/".base64_encode($record->id);
        $img="<img src='".$_ENV['APP_URL']."assets/img/edit.png' class='img-fluid tab-img'>";

        if($record->user_type_name != 'Auditor User'){
        $APP_URL_user_access=$_ENV['APP_URL']."edit-user-type-access/".base64_encode($record->id);
        $roleaccess="<a href='".$APP_URL_user_access."'><i class='fa fa-lock' style='color:#da3d2c;margin-left: 5px;font-size:17px;'></i></a>";
        }else
        {
          $roleaccess='';
        }
        $edit_link="<a href='".$APP_URL."'>$img</a>".$roleaccess;
       }
       

       $data_arr[] = array(
         "id" =>$i,
         "user_type_name" =>$record->user_type_name,
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






//Brand


public function view_brand(Request $request)
{
return view('/admin/view_brand');
}

public function add_brand(Request $request)
{
 return view('admin/add_brand');
}


public function insert_brand(Request $request)
{

  $data = DB::table('tbl_mibl_master_brand')
  ->select('*')
  ->where('name',$request->input('name'))
  ->get();
if(count($data)== '0'){  

$name = $request->input('name');
$description = $request->input('description');

$user_id=session('id');
$user = DB::table('tbl_mibl_user')
->select('*')
->where('deleted_at','=',0)
->where('id',$user_id)
->orderBy('id', 'desc')
->first();
$username=$user->name;

$last_id=DB::table('tbl_mibl_master_brand')->insertGetId([
    'name' =>$name,
    'description' =>$description,
    'created_by'=>$username,
    'created_date' => date('Y-m-d H:i:s'),
    ]);

 /*Insert user activity*/


DB::table('tbl_mibl_user_activity')
     ->insert([
      'user_id' =>$user_id,
      'user_name'=>$username,
      'activity_group_id'=>$last_id,
      'messgage'=>'Brand added successfully',
      'activity_type'=>'Insert',
      'activity_group'=>'Brand',
      'created_date' => date('Y-m-d H:i:s'),
      ]);
    
    session()->flash('successmsg', 'Brand added successfully.');
    return redirect('view-brand');

  }else
  {
    session()->flash('failmsg', 'Brand already exists.');
    return redirect('view-brand');
  }
}


public function edit_brand($id)
{

$id = base64_decode($id);
$data = DB::table('tbl_mibl_master_brand')
->select('*')
->where('id', '=', $id)
->get();

return view('/admin/edit_brand', ['edit_services' => $data]);
}

public function update_brand(Request $request)
{

$name = $request->input('name');
$description = $request->input('description');
$active_yn = $request->input('active_yn');
$id = $request->input('id');

$data = DB::table('tbl_mibl_master_brand')
->select('*')
->where('name',$request->input('name'))
->where('id','!=',$id)
->get();
if(count($data)== '0'){  

DB::table('tbl_mibl_master_brand')
->where('id', $id)
->update([
'name' => $name,
'description' => $description,
'active_yn' => $active_yn,
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

 $last_id = $request->input('id');
 DB::table('tbl_mibl_user_activity')
 ->insert([
  'user_id' =>$user_id,
  'user_name'=>$username,
  'activity_group_id'=>$last_id,
  'messgage'=>'Brand Updated successfully',
  'activity_type'=>'Updated',
  'activity_group'=>'Brand',
  'created_date' => date('Y-m-d H:i:s'),
  ]);

session()->flash('successmsg', 'Brand updated successfully.');
return redirect('view-brand');
}else
{
  session()->flash('failmsg', 'Brand already exists.');
  return redirect('view-brand');
}

}


public function delete_brand($id)
{
DB::table('tbl_mibl_master_brand')
->where('id', $id)
->update([
'deleted_at' =>1,
]);

session()->flash('successmsg', 'Brand deleted successfully.');
return redirect('welcome');
}

public function getbrand(Request $request){

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

    // Total records
    $totalRecords = Brand::select('count(*) as allcount')->count();
    $totalRecordswithFilter = Brand::select('count(*) as allcount')
                             ->where('name', 'like', '%' .$searchValue . '%')
                             ->orWhere('description', 'like', '%' .$searchValue . '%')
                             ->orWhere('active_yn', 'like', '%' .$status . '%')
                             ->orWhere('created_date', 'like', '%' .$searchValue. '%')
                             ->count();

    // Fetch records
    $records = Brand::orderBy($columnName,$columnSortOrder)
      ->where('tbl_mibl_master_brand.name', 'like', '%' .$searchValue . '%')
      ->orWhere('tbl_mibl_master_brand.description', 'like', '%' .$searchValue . '%')
      ->orWhere('tbl_mibl_master_brand.active_yn', 'like', '%' .$status . '%')
      ->orWhere('tbl_mibl_master_brand.created_date', 'like', '%' .$searchValue. '%')
      ->select('tbl_mibl_master_brand.*')
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
        $created_date=date("d/m/Y", strtotime($record->created_date));
       }

       if(!empty($record->id))
       {
        $APP_URL=$_ENV['APP_URL']."edit-brand/".base64_encode($record->id);
        $img="<img src='".$_ENV['APP_URL']."assets/img/edit.png' class='img-fluid tab-img'>";
        $edit_link="<a href='".$APP_URL."'>$img</a>";       }
       

       $data_arr[] = array(
         "id" =>$i,
         "name" =>$record->name,
         "description" =>$record->description,
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





//Category

public function view_category(Request $request)
{
return view('/admin/view_category');
}


public function add_category(Request $request)
{
 return view('/admin/add_category');
}


public function insert_category(Request $request)
{

$data = DB::table('tbl_mibl_master_category')
->select('*')
->where('name',$request->input('name'))
->get();
if(count($data)== '0'){    
$name = $request->input('name');
$description = $request->input('description');


$user_id=session('id');
$user = DB::table('tbl_mibl_user')
->select('*')
->where('deleted_at','=',0)
->where('id',$user_id)
->orderBy('id', 'desc')
->first();
$username=$user->name;

$last_id=DB::table('tbl_mibl_master_category')->insertGetId([
    'name' =>$name,
    'description' =>$description,
    'created_date' => date('Y-m-d H:i:s'),
    'created_by'=>$username,
    ]);
    

/*Insert user activity*/

DB::table('tbl_mibl_user_activity')
     ->insert([
      'user_id' =>$user_id,
      'user_name'=>$username,
      'activity_group_id'=>$last_id,
      'messgage'=>'Category added successfully',
      'activity_type'=>'Insert',
      'activity_group'=>'Category',
      'created_date' => date('Y-m-d H:i:s'),
      ]);  
      
      
    session()->flash('successmsg', 'Category added successfully.');
    return redirect('view-category');
  }else
  {
    session()->flash('failmsg', 'Category already exits.');
    return redirect('view-category');
  }
}

public function edit_category($id)
{
$id=base64_decode($id);
$data = DB::table('tbl_mibl_master_category')
->select('*')
->where('id', '=', $id)
->get();

return view('/admin/edit_category', ['edit_services' => $data]);
}

public function update_category(Request $request)
{

$name = $request->input('name');
$description = $request->input('description');
$active_yn = $request->input('active_yn');
$id = $request->input('id');

DB::table('tbl_mibl_master_category')
->where('id', $id)
->update([
'name' => $name,
'description' => $description,
'active_yn' => $active_yn,
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
  'messgage'=>'Category Updated successfully',
  'activity_type'=>'Updated',
  'activity_group'=>'Category',
  'created_date' => date('Y-m-d H:i:s'),
  ]);


session()->flash('successmsg', 'Category updated successfully.');
return redirect('view-category');

}

public function delete_category($id)
{
DB::table('tbl_mibl_master_category')
->where('id', $id)
->update([
'deleted_at' =>1,
]);

session()->flash('successmsg', 'Category deleted successfully.');
return redirect('welcome');
}




public function getcategory(Request $request){

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

    // Total records
    $totalRecords = Category::select('count(*) as allcount')->count();
    $totalRecordswithFilter = Category::select('count(*) as allcount')
                             ->where('name', 'like', '%' .$searchValue . '%')
                             ->orWhere('description', 'like', '%' .$searchValue . '%')
                             ->orWhere('active_yn', 'like', '%' .$status . '%')
                             ->orWhere('created_date', 'like', '%' .$searchValue. '%')
                             ->count();

    // Fetch records
    $records = Category::orderBy($columnName,$columnSortOrder)
      ->where('tbl_mibl_master_category.name', 'like', '%' .$searchValue . '%')
      ->orWhere('tbl_mibl_master_category.description', 'like', '%' .$searchValue . '%')
      ->orWhere('tbl_mibl_master_category.active_yn', 'like', '%' .$status . '%')
      ->orWhere('tbl_mibl_master_category.created_date', 'like', '%' .$searchValue. '%')
      ->select('tbl_mibl_master_category.*')
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
        $created_date= date("d/m/Y", strtotime($record->created_date));
       }

       if(!empty($record->id))
       {
        $APP_URL=$_ENV['APP_URL']."edit-category/".base64_encode($record->id);
        $img="<img src='".$_ENV['APP_URL']."assets/img/edit.png' class='img-fluid tab-img'>";
        $edit_link="<a href='".$APP_URL."'>$img</a>";       }
       

       $data_arr[] = array(
         "id" =>$i,
         "name" =>$record->name,
         "description" =>$record->description,
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




//Department

public function view_department(Request $request)
{
  $department_type_list = DB::table('tbl_mibl_master_department_type')
  ->select('*')
  ->where('active_yn',0)
  ->get(); 
return view('/admin/view_department',['department_type_list'=>$department_type_list]);
}


public function add_department(Request $request)
{
  $department_type_list = DB::table('tbl_mibl_master_department_type')
  ->select('*')
  ->where('active_yn',0)
  ->get(); 
 return view('/admin/add_department',['department_type_list'=>$department_type_list]);
}


public function insert_department(Request $request)
{

  $data = DB::table('tbl_mibl_master_department')
  ->select('*')
  ->where('name',$request->input('name'))
  ->get();
  if(count($data)== '0'){
$name = $request->input('name');
$description = $request->input('description');
$department_type_id=$request->input('department_type_id');


$user_id=session('id');
$user = DB::table('tbl_mibl_user')
->select('*')
->where('deleted_at','=',0)
->where('id',$user_id)
->orderBy('id', 'desc')
->first();
$username=$user->name;

$last_id=DB::table('tbl_mibl_master_department')->insertGetId([
    'department_type_id'=>$department_type_id,
    'name' =>$name,
    'description' =>$description,
    'created_date' => date('Y-m-d H:i:s'),
    'created_by'=>$username
    ]);

    
/*Insert user activity*/

DB::table('tbl_mibl_user_activity')
     ->insert([
      'user_id' =>$user_id,
      'user_name'=>$username,
      'activity_group_id'=>$last_id,
      'messgage'=>'Department added successfully',
      'activity_type'=>'Insert',
      'activity_group'=>'Department',
      'created_date' => date('Y-m-d H:i:s'),
      ]);  
      

    session()->flash('successmsg', 'Department added successfully.');
    return redirect('view-department');
  }else
  {
    session()->flash('failmsg', 'Department already exists.');
    return redirect('view-department');
  }
}

public function edit_department($id)
{

$department_type_list = DB::table('tbl_mibl_master_department_type')
->select('*')
->where('active_yn',0)
->get(); 
  
$id=base64_decode($id);    
$data = DB::table('tbl_mibl_master_department')
->select('*')
->where('id', '=', $id)
->get();

return view('/admin/edit_department', ['edit_services' => $data,'department_type_list'=>$department_type_list]);
}

public function update_department(Request $request)
{

$name = $request->input('name');
$keyword = $request->input('keyword');
$active_yn = $request->input('active_yn');
$department_type_id=$request->input('department_type_id');
$id = $request->input('id');


// $data = DB::table('tbl_mibl_master_department')
// ->select('*')
// ->where('name',$request->input('name'))
// ->where('id','!=',$id)
// ->get();

$data = DB::table('tbl_mibl_master_department')
->select('*')
->where(function ($query) use ($keyword,$name){
  $query->where('name',$name)
         ->orWhere('keyword',$keyword);
        })
->where('id','!=',$id)
->get();

if(count($data)== '0'){
DB::table('tbl_mibl_master_department')
->where('id', $id)
->update([
// 'name' => $name,
// 'department_type_id'=>$department_type_id,
'keyword' => $keyword,
'active_yn' => $active_yn,
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

 $last_id = $request->input('id');
 DB::table('tbl_mibl_user_activity')
 ->insert([
  'user_id' =>$user_id,
  'user_name'=>$username,
  'activity_group_id'=>$last_id,
  'messgage'=>'Department Updated successfully',
  'activity_type'=>'Updated',
  'activity_group'=>'Department',
  'created_date' => date('Y-m-d H:i:s'),
  ]);

session()->flash('successmsg', 'Department updated successfully.');
return redirect('view-department');
}else
{
  session()->flash('failmsg', 'Department already exists.');
  return redirect('view-department');
}

}

public function delete_department($id)
{
DB::table('tbl_mibl_master_department')
->where('id', $id)
->update([
'deleted_at' =>1,
]);

session()->flash('successmsg', 'Department deleted successfully.');
return redirect('welcome');
}


public function getdepartment(Request $request){

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
    $created_dated='';
    }

    // Total records
    $totalRecords = Department::select('count(*) as allcount')->count();
    $totalRecordswithFilter = Department::select('count(*) as allcount')
                            ->leftJoin('tbl_mibl_master_department_type', 'tbl_mibl_master_department.department_type_id', '=', 'tbl_mibl_master_department_type.id')
                            ->where('tbl_mibl_master_department.name', 'like', '%' .$searchValue . '%')
                            ->orWhere('tbl_mibl_master_department.keyword', 'like', '%' .$searchValue . '%')
                            ->orWhere('tbl_mibl_master_department_type.department_type_name', 'like', '%' .$searchValue . '%')
                            ->orWhere('tbl_mibl_master_department.active_yn', 'like', '%' .$status . '%')
                            ->orWhere('tbl_mibl_master_department.created_date', 'like', '%' .$created_dated. '%')
                            ->count();

    // Fetch records
    $records = Department::orderBy($columnName,$columnSortOrder)
      ->where('tbl_mibl_master_department.name', 'like', '%' .$searchValue . '%')
      ->orWhere('tbl_mibl_master_department.keyword', 'like', '%' .$searchValue . '%')
      ->orWhere('tbl_mibl_master_department.active_yn', 'like', '%' .$status . '%')
      ->orWhere('tbl_mibl_master_department_type.department_type_name', 'like', '%' .$searchValue . '%')
      ->orWhere('tbl_mibl_master_department.created_date', 'like', '%' .$created_dated. '%')
      ->leftJoin('tbl_mibl_master_department_type', 'tbl_mibl_master_department.department_type_id', '=', 'tbl_mibl_master_department_type.id')
      ->select('tbl_mibl_master_department.*','tbl_mibl_master_department_type.department_type_name')
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
        $created_date=date("d/m/Y", strtotime($record->created_date));
       }

       if(!empty($record->id))
       {
        $APP_URL=$_ENV['APP_URL']."edit-department/".base64_encode($record->id);
        $img="<img src='".$_ENV['APP_URL']."assets/img/edit.png' class='img-fluid tab-img'>";
        $edit_link="<a href='".$APP_URL."'>$img</a>";       }
       

       $data_arr[] = array(
         "id" =>$i,
         "department_type_name"=>$record->department_type_name,
         "name" =>$record->name,
         "keyword" =>$record->keyword,
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



//Document Type

public function view_document_type(Request $request)
{

return view('/admin/view_document_type');
}

public function add_document_type(Request $request)
{
 return view('/admin/add_document_type');
}


public function insert_document_type(Request $request)
{
  $data = DB::table('tbl_mibl_master_document_type')
  ->select('*')
  ->where('name',$request->input('name'))
  ->get();
  if(count($data)== '0'){
$name = $request->input('name');
$description = $request->input('description');

$user_id=session('id');
$user = DB::table('tbl_mibl_user')
->select('*')
->where('deleted_at','=',0)
->where('id',$user_id)
->orderBy('id', 'desc')
->first();
$username=$user->name;
$last_id=DB::table('tbl_mibl_master_document_type')->insertGetId([
    'name' =>$name,
    'description' =>$description,
    'created_date' => date('Y-m-d H:i:s'),
    'created_by'=>$username
    ]);
  

/*Insert user activity*/

DB::table('tbl_mibl_user_activity')
     ->insert([
      'user_id' =>$user_id,
      'user_name'=>$username,
      'activity_group_id'=>$last_id,
      'messgage'=>'Document Type added successfully',
      'activity_type'=>'Insert',
      'activity_group'=>'Document Type',
      'created_date' => date('Y-m-d H:i:s'),
      ]);  


    session()->flash('successmsg', 'Document type added successfully.');
    return redirect('view-document-type');
  }else
  {
    session()->flash('failmsg', 'Document type already exists.');
    return redirect('view-document-type');
  }
}

public function edit_document_type($id)
{
$id=base64_decode($id);    
$data = DB::table('tbl_mibl_master_document_type')
->select('*')
->where('id', '=', $id)
->get();

return view('/admin/edit_document_type', ['edit_services' => $data]);
}

public function update_document_type(Request $request)
{

$name = $request->input('name');
$description = $request->input('description');
$active_yn = $request->input('active_yn');
$id = $request->input('id');

$data = DB::table('tbl_mibl_master_document_type')
->select('*')
->where('name',$request->input('name'))
->where('id','!=',$id)
->get();
if(count($data)== '0'){

DB::table('tbl_mibl_master_document_type')
->where('id', $id)
->update([
'name' => $name,
'description' => $description,
'active_yn' => $active_yn,
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
  'messgage'=>'Document Type Updated successfully',
  'activity_type'=>'Updated',
  'activity_group'=>'Document Type',
  'created_date' => date('Y-m-d H:i:s'),
  ]);


session()->flash('successmsg', 'Document type updated successfully.');
return redirect('view-document-type');

}else
{
  session()->flash('failmsg', 'Document type already exists.');
  return redirect('view-document-type');
}

}

public function delete_document_type($id)
{
DB::table('tbl_mibl_master_document_type')
->where('id', $id)
->update([
'deleted_at' =>1,
]);

session()->flash('successmsg', 'Document Type deleted successfully.');
return redirect('welcome');
}



public function getdocument_type(Request $request){

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

    // Total records
    $totalRecords = Document_type::select('count(*) as allcount')->count();
    $totalRecordswithFilter = Document_type::select('count(*) as allcount')
                            ->where('name', 'like', '%' .$searchValue . '%')
                            ->orWhere('description', 'like', '%' .$searchValue . '%')
                            ->orWhere('active_yn', 'like', '%' .$status . '%')
                            ->orWhere('created_date', 'like', '%' .$created_dated. '%')
                            ->count();

    // Fetch records
    $records = Document_type::orderBy($columnName,$columnSortOrder)
      ->where('tbl_mibl_master_document_type.name', 'like', '%' .$searchValue . '%')
      ->orWhere('tbl_mibl_master_document_type.description', 'like', '%' .$searchValue . '%')
      ->orWhere('tbl_mibl_master_document_type.active_yn', 'like', '%' .$status . '%')
      ->orWhere('tbl_mibl_master_document_type.created_date', 'like', '%' .$created_dated. '%')
      ->select('tbl_mibl_master_document_type.*')
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
       }

       if(!empty($record->id))
       {
        $APP_URL=$_ENV['APP_URL']."edit-document-type/".base64_encode($record->id);
        $img="<img src='".$_ENV['APP_URL']."assets/img/edit.png' class='img-fluid tab-img'>";
        $edit_link="<a href='".$APP_URL."'>$img</a>";       }
       

       $data_arr[] = array(
         "id" =>$i,
         "name" =>$record->name,
         "description" =>$record->description,
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



//Vendor

public function view_vendor(Request $request)
{
return view('/admin/view_vendor');
}

public function add_vendor(Request $request)
{
  $vendor_type_list = DB::table('tbl_mibl_master_vendor_type')
  ->select('*')
  ->where('active_yn',0)
  ->get();


  $vendor_list = DB::table('tbl_mibl_master_vendor')
  ->select('*')
  ->where('active_yn',0)
  ->where('flag',0)
  ->get();
 return view('/admin/add_vendor',['vendor_type_list'=>$vendor_type_list,'vendor_list'=>$vendor_list]);
}


public function insert_vendor(Request $request)
{

  
  
  $keyword=$request->input('keyword');
  $password=$request->input('password');
  $vendor_types=$request->input('vendor_types');

if($vendor_types == 'Supplier')
 {
    $vendor_code=$request->input('vendor_code');
    $data = DB::table('tbl_mibl_master_vendor')
    ->select('*')
    ->where(function ($query) use ($keyword,$vendor_code){
    $query->where('vendor_code',$vendor_code)
    ->orWhere('keyword',$keyword);
    })
    ->where('flag','=','1')
    ->get();

 }
 else
 {
    $vendor_code=$request->input('vendor_code1');
    $data = DB::table('tbl_mibl_master_vendor')
    ->select('*')
    ->where('vendor_code',$vendor_code)
    ->get();
 }

  // $data = DB::table('tbl_mibl_master_vendor')
  // ->select('*')
  // ->Where('vendor_code',$request->input('vendor_code'))
  // ->Where('flag','1')
  // ->get();



$user_id=session('id');
$user = DB::table('tbl_mibl_user')
->select('*')
->where('deleted_at','=',0)
->where('id',$user_id)
->orderBy('id', 'desc')
->first();
$username=$user->name;


if(count($data)== '0'){




if($vendor_types == 'Supplier')
 {
    DB::table('tbl_mibl_master_vendor')
    ->where('vendor_code', $vendor_code)
    ->update([
    'flag'=>1,
    'keyword'=>$keyword,
    'password'=>Hash::make($password)
    ]);
    $last_id = $request->input('id');
 }
else
{
    
    $name = $request->input('name');
    $keyword = $request->input('keyword');
    $email = $request->input('email');
    $contact_person = $request->input('contact_person');
    $contact_email = $request->input('contact_email');
    $pan_no = $request->input('pan_no');
    $vendor_type_id ='1';
    $mobile_no = $request->input('mobile_no');
    $address = $request->input('address'); 
    
    $tax_identification_code=$request->input('tax_identification_code');
    $gstn=$request->input('gstn');
    $city=$request->input('city');
    $state=$request->input('state');
    $postal_code=$request->input('postal_code');

    
    $last_ids=DB::table('tbl_mibl_master_vendor')->insertGetId([
    'name' =>$name,
    'vendor_code'=>$vendor_code,
    'email' =>$email,
    'contact_person' =>$contact_person,
    'contact_email' =>$contact_email,
    'pan_no' =>$pan_no,
    'vendor_type_id' =>$vendor_type_id,
    'mobile_no' =>$mobile_no,
    'address' =>$address,
    'created_date' => date('Y-m-d H:i:s'),
    'created_by'=>$username,
    'flag'=>1,
    'types'=>'Other',
    'keyword'=>$keyword,
    'password'=>Hash::make($password),
    'tax_identification_code'=>$tax_identification_code,
    'gstn'=>$gstn,
    'city'=>$city,
    'state'=>$state,
    'postal_code'=>$postal_code
    ]);
 
     $last_id = $last_ids;
    
 }


/*Insert user activity*/

DB::table('tbl_mibl_user_activity')
->insert([
 'user_id' =>$user_id,
 'user_name'=>$username,
 'activity_group_id'=>$last_id,
 'messgage'=>'Vendor added successfully',
 'activity_type'=>'Insert',
 'activity_group'=>'Vendor',
 'created_date' => date('Y-m-d H:i:s'),
 ]);  

    session()->flash('successmsg', 'Vendor added successfully.');
    return redirect('view-vendor');
  }else
  {
    session()->flash('failmsg', 'Vendor already exists.');
    return redirect('view-vendor');
  }
}


public function edit_vendor($id)
{

$vendor_type_list = DB::table('tbl_mibl_master_vendor_type')
->select('*')
->where('active_yn',0)
->get();

$id=base64_decode($id);  
$data = DB::table('tbl_mibl_master_vendor')
->select('*')
->where('id', '=', $id)
->get();

return view('/admin/edit_vendor', ['edit_services' => $data,'vendor_type_list'=>$vendor_type_list]);
}

public function update_vendor(Request $request)
{
$name = $request->input('name');
$email = $request->input('email');
$contact_person = $request->input('contact_person');
$contact_email = $request->input('contact_email');
$pan_no = $request->input('pan_no');
$vendor_type_id=$request->input('vendor_type_id');
$mobile_no = $request->input('mobile_no');
$address = $request->input('address');
$password = Hash::make($request->input('password'));
$active_yn = $request->input('active_yn');
$id = $request->input('id');
$keyword = $request->input('keyword');



if(!empty($request->input('password')))
{
DB::table('tbl_mibl_master_vendor')
->where('id', $id)
->update([
'password' =>$password
]);

}
$data = DB::table('tbl_mibl_master_vendor')
->select('*')
->where('keyword',$keyword)
->where('id','!=',$id)
->get();

if(count($data)== '0'){

DB::table('tbl_mibl_master_vendor')
->where('id', $id)
->update([
'name' =>$name,
'keyword' =>$keyword,
'active_yn' => $active_yn,
// 'email' =>$email,
// 'contact_person' =>$contact_person,
// 'contact_email' =>$contact_email,

// 'vendor_type_id'=>$vendor_type_id,
// 'pan_no' =>$pan_no,
// 'mobile_no' =>$mobile_no,
// 'address' =>$address,
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
  'messgage'=>'Vendor Updated successfully',
  'activity_type'=>'Updated',
  'activity_group'=>'Vendor',
  'created_date' => date('Y-m-d H:i:s'),
  ]);


session()->flash('successmsg', 'Vendor updated successfully.');
return redirect('view-vendor');

}else
{
  session()->flash('failmsg', 'Vendor already exists.');
  return redirect('view-vendor');
}

}

public function delete_vendor($id)
{
DB::table('tbl_mibl_master_vendor')
->where('id', $id)
->update([
'deleted_at' =>1,
]);

session()->flash('successmsg', 'Vendor deleted successfully.');
return redirect('welcome');
}



public function getvendor(Request $request){

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

  // Total records
  /*$totalRecords = Vendor::select('count(*) as allcount')->count();
  $totalRecordswithFilter = Vendor::select('count(*) as allcount')
                          ->leftJoin('tbl_mibl_master_vendor_type', 'tbl_mibl_master_vendor.vendor_type_id', '=', 'tbl_mibl_master_vendor_type.id')
                      
                          
                          ->where('name', 'like', '%' .$searchValue . '%')
                          ->orWhere('email', 'like', '%' .$searchValue . '%')
                          ->orWhere('contact_person', 'like', '%' .$searchValue . '%')
                          ->orWhere('contact_email', 'like', '%' .$searchValue . '%')
                          ->orWhere('pan_no', 'like', '%' .$searchValue . '%')
                          ->orWhere('vendor_type_name', 'like', '%' .$searchValue . '%')
                          ->orWhere('mobile_no', 'like', '%' .$searchValue . '%')
                          ->orWhere('address', 'like', '%' .$searchValue . '%')
                          ->orWhere('tbl_mibl_master_vendor.active_yn', 'like', '%' .$status . '%')
                          ->orWhere('tbl_mibl_master_vendor.created_date', 'like', '%' .$searchValue. '%')
                          ->count();*/


                $totalRecords = Vendor::select('count(*) as allcount')->count();
                $totalRecordswithFilter = Vendor::select('count(*) as allcount')
                          ->leftJoin('tbl_mibl_master_vendor_type', 'tbl_mibl_master_vendor.vendor_type_id', '=', 'tbl_mibl_master_vendor_type.id')
                                    
                          ->where(function ($query) use ($searchValue,$created_dated){
                          $query ->where('name', 'like', '%' .$searchValue . '%')
                          ->where('name', 'like', '%' .$searchValue . '%')
                          ->orWhere('email', 'like', '%' .$searchValue . '%')
                          ->orWhere('contact_person', 'like', '%' .$searchValue . '%')
                          ->orWhere('contact_email', 'like', '%' .$searchValue . '%')
                          ->orWhere('keyword', 'like', '%' .$searchValue . '%')
                          ->orWhere('vendor_code', 'like', '%' .$searchValue . '%')
                          ->orWhere('tax_identification_code', 'like', '%' .$searchValue . '%')
                          ->orWhere('gstn', 'like', '%' .$searchValue . '%')
                          ->orWhere('city', 'like', '%' .$searchValue . '%')
                          ->orWhere('state', 'like', '%' .$searchValue . '%')
                          ->orWhere('postal_code', 'like', '%' .$searchValue . '%')
                          ->orWhere('vendor_type_name', 'like', '%' .$searchValue . '%')
                          ->orWhere('mobile_no', 'like', '%' .$searchValue . '%')
                          ->orWhere('address', 'like', '%' .$searchValue . '%')
                          ->orWhere('tbl_mibl_master_vendor.active_yn', 'like', '%' .$searchValue . '%')
                          ->orWhere('tbl_mibl_master_vendor.created_date', 'like', '%' .$created_dated. '%');
                      })
                      ->where('flag',1)    
                      ->count();
                               

  // Fetch records
  $records = Vendor::orderBy($columnName,$columnSortOrder)  
  ->where('flag',1) 
  ->where(function ($query) use ($searchValue,$created_dated){
    $query ->where('name', 'like', '%' .$searchValue . '%')
    ->where('tbl_mibl_master_vendor.name', 'like', '%' .$searchValue . '%')
    ->orWhere('tbl_mibl_master_vendor.email', 'like', '%' .$searchValue . '%')
    ->orWhere('tbl_mibl_master_vendor.contact_person', 'like', '%' .$searchValue . '%')
    ->orWhere('tbl_mibl_master_vendor.contact_email', 'like', '%' .$searchValue . '%')
    ->orWhere('tbl_mibl_master_vendor.keyword', 'like', '%' .$searchValue . '%')
    ->orWhere('tbl_mibl_master_vendor_type.vendor_type_name', 'like', '%' .$searchValue . '%')
    ->orWhere('tbl_mibl_master_vendor.mobile_no', 'like', '%' .$searchValue . '%')
    ->orWhere('tbl_mibl_master_vendor.address', 'like', '%' .$searchValue . '%')
    ->orWhere('tbl_mibl_master_vendor.vendor_code', 'like', '%' .$searchValue . '%')
    ->orWhere('tbl_mibl_master_vendor.tax_identification_code', 'like', '%' .$searchValue . '%')
    ->orWhere('tbl_mibl_master_vendor.gstn', 'like', '%' .$searchValue . '%')
    ->orWhere('tbl_mibl_master_vendor.city', 'like', '%' .$searchValue . '%')
    ->orWhere('tbl_mibl_master_vendor.state', 'like', '%' .$searchValue . '%')
    ->orWhere('tbl_mibl_master_vendor.postal_code', 'like', '%' .$searchValue . '%')
    ->orWhere('tbl_mibl_master_vendor.active_yn', 'like', '%' .$searchValue . '%')
    ->orWhere('tbl_mibl_master_vendor.created_date', 'like', '%' .$created_dated. '%');
    })
    ->leftJoin('tbl_mibl_master_vendor_type', 'tbl_mibl_master_vendor.vendor_type_id', '=', 'tbl_mibl_master_vendor_type.id')
    ->select('tbl_mibl_master_vendor.*','tbl_mibl_master_vendor_type.vendor_type_name')
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
     }

     if(!empty($record->id))
     {
      $APP_URL=$_ENV['APP_URL']."edit-vendor/".base64_encode($record->id);
      $img="<img src='".$_ENV['APP_URL']."assets/img/edit.png' class='img-fluid tab-img'>";
      $edit_link="<a href='".$APP_URL."'>$img</a>";     }
    
     $data_arr[] = array(
       "id" =>$i,
       "name" =>$record->name,
       "email" =>$record->email,
       "vendor_code" =>$record->vendor_code,
       "tax_identification_code" =>$record->tax_identification_code,
       "gstn" =>$record->gstn,
       "city" =>$record->city,
       "keyword" =>$record->keyword,
       "vendor_type_id" =>$record->vendor_type_name,
       "state" =>$record->state,
       "postal_code" =>$record->postal_code,
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


//User


public function view_user(Request $request)
{
return view('/admin/view_user');
}

public function add_user(Request $request)
{
  $data = DB::table('tbl_mibl_master_user_type')
  ->select('*')
  ->where('active_yn',0)
  ->get();

  $data_sap = DB::table('tbl_mibl_user')
  ->select('*')
  ->where('flag',0)
  ->get();
  
  return view('/admin/add_user', ['user_type' => $data,'user_sap_code'=>$data_sap]);
  }


public function insert_user(Request $request)
{

  $data = DB::table('tbl_mibl_user')
  ->select('*')
  ->Where('sap_code',$request->input('sap_code'))
  ->Where('flag','1')
  ->get();


if(count($data) == 0) { 
$user_type = $request->input('user_type');
$name = $request->input('name');
$email = $request->input('email');
$mobile_no = $request->input('mobile_no');
$address = $request->input('address');
$password = Hash::make($request->input('password'));
$pan_no = $request->input('pan_no');
$sap_code = $request->input('sap_code');
$banner = $request->file('photo');
$id = $request->input('id');


if ($request->file('photo') != '') {
$characters = '0123456789abcdefghijklmnopqrstuvwxyz';
$charactersLength = strlen($characters);
$randomString = '';
for ($i = 0; $i < 18; $i++) {
$randomString .= $characters[rand(0, $charactersLength - 1)];
}
$file_name = $_FILES["photo"]["name"];
$file_tmp = $_FILES["photo"]["tmp_name"];
$ext = pathinfo($file_name, PATHINFO_EXTENSION);
$random_file_name = $randomString . '.' . $ext;
$latest_member_image = 'uploads/profile_image/' . $random_file_name;
$arr_data['photo']=move_uploaded_file($file_tmp, env('BASE_PATH') . $latest_member_image);
}else
{
  $latest_member_image='';
}

$user_id=session('id');
$user = DB::table('tbl_mibl_user')
->select('*')
->where('deleted_at','=',0)
->where('id',$user_id)
->orderBy('id', 'desc')
->first();
$username=$user->name;



/*$id=DB::table('tbl_mibl_user')->insertGetId([
    'user_type'=>$user_type,
    'name'=>$name,
    'email'=>$email,
    'pan_no'=>$pan_no,
    'sap_code'=>$sap_code,
    'mobile_no'=>$mobile_no,
    'address'=>$address,
    'photo'=>$latest_member_image,
    'created_date'=>date('Y-m-d H:i:s'),
    'created_by'=>$username,
    ]);*/

DB::table('tbl_mibl_user')
->where('sap_code', $sap_code)
->update([
'flag'=>1,
'user_type'=>$user_type,
]);

  
  /*Insert user User Access*/

  $data_user_type = DB::table('tbl_mibl_user')
  ->select('tbl_mibl_master_user_type.id')
  ->leftJoin('tbl_mibl_master_user_type', 'tbl_mibl_master_user_type.user_type_name', '=', 'tbl_mibl_user.user_type')
  ->where('tbl_mibl_user.id',$id)
  ->first();


  $data_type_wise_access = DB::table('tbl_mibl_user_type_access')
  ->select('*')
  ->where('tbl_mibl_user_type_access.user_type_id',$data_user_type->id)
  ->first();

    DB::table('tbl_mibl_user_access')->insert([
    'user_id' =>$id,
    'user_type_id' =>$data_user_type->id,
    'search_ad' => $data_type_wise_access->search_ad,
    'advance_search'=>$data_type_wise_access->advance_search,
    'download_creative' => $data_type_wise_access->download_creative,
    'single_file_upload' => $data_type_wise_access->single_file_upload,
    'bulk_upload_files' => $data_type_wise_access->bulk_upload_files,
    'agreement' => $data_type_wise_access->agreement,
    'approve_creatives' => $data_type_wise_access->approve_creatives,
    'advertisement_id_list' => $data_type_wise_access->advertisement_id_list,

    'manage_newsletter' => $data_type_wise_access->manage_newsletter,
    'upload_newsletter' => $data_type_wise_access->upload_newsletter,
    'notification' => $data_type_wise_access->notification,
    'manage_report'=>$data_type_wise_access->manage_report,
    'manage_miscellaneous'=>$data_type_wise_access->manage_miscellaneous,
    'manage_adaptation'=>$data_type_wise_access->manage_adaptation,


   ]);



    

/*Insert user activity*/

$last_id=$request->input('id');

DB::table('tbl_mibl_user_activity')
->insert([
 'user_id' =>$user_id,
 'user_name'=>$username,
 'activity_group_id'=>$last_id,
 'messgage'=>'User added successfully',
 'activity_type'=>'Insert',
 'activity_group'=>'User',
 'created_date' => date('Y-m-d H:i:s'),
 ]);  
    session()->flash('successmsg', 'User added successfully.');
    return redirect('view-user');
  }else
  {
    session()->flash('failmsg', 'Email or SAP Code already exists.');
    return redirect('view-user');
  }
}



public function edit_user($id)
{

$id=base64_decode($id);  
$data = DB::table('tbl_mibl_user')
->select('*')
->where('id', '=', $id)
->get();

$user_type = DB::table('tbl_mibl_master_user_type')
  ->select('*')
  ->where('active_yn',0)
  ->get();

return view('/admin/edit_user', ['edit_services' => $data,'user_type'=>$user_type]);
}



public function update_user(Request $request)
{ 
$name = $request->input('name');
$email = $request->input('email');
$user_type = $request->input('user_type');
$password = Hash::make($request->input('password'));
$pan_no = $request->input('pan_no');
$mobile_no = $request->input('mobile_no');
$address = $request->input('address');
$sap_code = $request->input('sap_code');
$active_yn = $request->input('active_yn');
$id = $request->input('id');

$data_user_type = DB::table('tbl_mibl_user')
  ->select('*')
  ->where('email',$request->input('email'))
  ->where('id','=',$id)
  ->where('user_type','=',$user_type)
  ->get();


if(count($data_user_type) == '0'){  

/* User Role Access */
$data_user_type = DB::table('tbl_mibl_master_user_type')
->select('tbl_mibl_master_user_type.id')
->where('tbl_mibl_master_user_type.user_type_name',$user_type)
->first();

$data_type_wise_access = DB::table('tbl_mibl_user_type_access')
->select('*')
->where('tbl_mibl_user_type_access.user_type_id',$data_user_type->id)
->first();


DB::table('tbl_mibl_user_access')
->where('user_id', $id)
->update([
'user_type_id' =>$data_user_type->id,
'search_ad' => $data_type_wise_access->search_ad,
'advance_search'=>$data_type_wise_access->advance_search,
'download_creative' => $data_type_wise_access->download_creative,
'single_file_upload' => $data_type_wise_access->single_file_upload,
'bulk_upload_files' => $data_type_wise_access->bulk_upload_files,
'agreement' =>@$data_type_wise_access->agreement,
'approve_creatives' =>@$data_type_wise_access->approve_creatives,
'advertisement_id_list' =>@$data_type_wise_access->advertisement_id_list,
'manage_newsletter' =>@$data_type_wise_access->manage_newsletter,
'upload_newsletter' =>@$data_type_wise_access->upload_newsletter,
'notification' =>@$data_type_wise_access->notification,
'manage_report'=>@$data_type_wise_access->manage_report,
'manage_miscellaneous'=>@$data_type_wise_access->manage_miscellaneous,
'manage_adaptation'=>@$data_type_wise_access->manage_adaptation,

]);
}
$data = DB::table('tbl_mibl_user')
  ->select('*')
  ->where('email',$request->input('email'))
  ->where('id','!=',$id)
  ->get();

if(count($data)== '0'){  

//Update Password
if(!empty($password))
{
/*DB::table('tbl_mibl_user')
->where('id', $id)
->update([
'password' =>$password,
]);*/
}

//Update Image
if ($request->file('photo') != '') {

  $characters = '0123456789abcdefghijklmnopqrstuvwxyz';
  $charactersLength = strlen($characters);
  $randomString = '';
  for ($i = 0; $i < 18; $i++) {
  $randomString .= $characters[rand(0, $charactersLength - 1)];
  }
  $file_name = $_FILES["photo"]["name"];
  $file_tmp = $_FILES["photo"]["tmp_name"];
  $ext = pathinfo($file_name, PATHINFO_EXTENSION);
  $random_file_name = $randomString . '.' . $ext;
  $latest_member_image = 'uploads/profile_image/' . $random_file_name;
  move_uploaded_file($file_tmp, env('BASE_PATH') . $latest_member_image);
  
  DB::table('tbl_mibl_user')
  ->where('id', $id)
  ->update(['photo' => $latest_member_image]);
  } 
  

DB::table('tbl_mibl_user')
->where('id', $id)
->update([
'name' =>$name,
'email' =>$email,
'user_type' =>$user_type,
'pan_no' =>$pan_no,
'mobile_no' =>$mobile_no,
'address' =>$address,
'active_yn' => $active_yn,
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
  'messgage'=>'User Updated successfully',
  'activity_type'=>'Updated',
  'activity_group'=>'User',
  'created_date' => date('Y-m-d H:i:s'),
  ]);



session()->flash('successmsg', 'User updated successfully.');
return redirect('view-user');
}else
{
  session()->flash('failmsg', 'Email or mobile no already exists.');
  return redirect('view-user');
}

}



public function getuser(Request $request){

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


  // Total records
  // $totalRecords = User::select('count(*) as allcount')->count();
  // $totalRecordswithFilter = User::select('count(*) as allcount')
  //                         ->where('flag',1)
  //                         ->where('name', 'like', '%' .$searchValue . '%')
  //                         ->orWhere('email', 'like', '%' .$searchValue . '%')
  //                         ->orWhere('user_type', 'like', '%' .$searchValue . '%')
  //                         ->orWhere('pan_no', 'like', '%' .$searchValue . '%')
  //                         ->orWhere('last_login_date', 'like', '%' .$searchValue . '%')
  //                         ->orWhere('mobile_no', 'like', '%' .$searchValue . '%')
  //                         ->orWhere('address', 'like', '%' .$searchValue . '%')
  //                         ->orWhere('active_yn', 'like', '%' .$status . '%')
  //                         ->orWhere('created_date', 'like', '%' .$searchValue. '%')
  //                         ->count();

                          $totalRecords = User::select('count(*) as allcount')->count();
                          $totalRecordswithFilter = User::select('count(*) as allcount')
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
  $records = User::orderBy($columnName,$columnSortOrder)
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
    ->select('tbl_mibl_user.*')
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
      $APP_URL=$_ENV['APP_URL']."edit-user/".base64_encode($record->id);
      $img="<img src='".$_ENV['APP_URL']."assets/img/edit.png' class='img-fluid tab-img'>";

      if($record->user_type != 'Auditor User'){
      $APP_URL_user_access=$_ENV['APP_URL']."edit-user-access/".base64_encode($record->id);
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

function add_single_file_upload()
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

  return view('/admin/add_single_file_upload', 
  ['category_list' => $category,
   'document_type_list' => $document_type,
   'brand_list' => $brand,
   'archive_c'=>$archive_c,
   'department_c'=>$department_c,
   'vendor_c'=>$vendor_c,
   'languages'=>$language]);
}



function insert_single_file_upload(Request $request)
 {
  
if (( $_FILES["photo"]["size"] <= 200000000 )) {
 
  $date_of_posting = date('Y-m', strtotime($request->input('date_of_posting')));
  $advertisement_id=$request->input('advertisement_id');

  $data = DB::table('tbl_mibl_creatives')
  ->select('*')
  ->where('advertisement_id',$advertisement_id)
  ->get();

  $data_bulk = DB::table('tbl_mibl_creatives_bulk')
  ->select('*')
  ->where('advertisement_id',$advertisement_id)
  ->where('status','4')
  ->get();

  if(trim($advertisement_id) !='' )
  {
    
  if(count($data) == 0 && count($data_bulk) == 0){
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

      //Download video Enbled

      // $url="https://api.cloudflare.com/client/v4/accounts/34cc3252d5c329c1d2ac13237b4972ed/stream/$VIDEOID/downloads";
      // $curl = curl_init();
      // curl_setopt_array($curl, [
      // CURLOPT_URL            => $url, // tmp url provided by cloudflare
      // CURLOPT_RETURNTRANSFER => 1,
      // CURLOPT_TIMEOUT        => 600,
      // CURLOPT_POST           => true,
      // CURLOPT_HTTPHEADER     => [
      // "X-Auth-Key: 43b3d73c452c8f2f536964033aa59622c3b9d","X-Auth-Email:marketing.mibl@gmail.com"
      // ],
      // ]);
      // $response = curl_exec($curl);
      // curl_close($curl);
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
$advertisement_id = $request->input('advertisement_id');
$file_description = $request->input('file_description');
$category_id =$request->input('category_id');
$brand_id = $request->input('brand_id');
// $department_id = $request->input('department_id');
$document_type_id = $request->input('document_type_id');
// $vendor_id = $request->input('vendor_id');
$date_of_posting = $date_of_posting."-01";
$date_of_upload=$request->input('date_of_upload');
$other_document_type=$request->input('other_document_type');
$archive_category_id=$request->input('archive_category_id');
$department_type_id=$request->input('department_type_id');
$vendor_type_id=$request->input('vendor_type_id');
$language_id=$request->input('language_id');

$irdai_date=$request->input('irdai_date');
$irdai_addressed=$request->input('irdai_addressed');
$remark=$request->input('remark');

$photo_url = $filename;
$source_file = $filename1;
$filetype = $filetype;

$arr=explode(",",$archive_category_id);

$archive_category_id=$arr[0];
$archive_sub_category_id=$arr[1];


$arr_1=explode(",",$department_type_id);

$department_type_id=$arr_1[0];
$department_id=$arr_1[1];


$arr_2=explode(",",$vendor_type_id);

$vendor_type_id=$arr_2[0];
$vendor_id=$arr_2[1];


$last_id=DB::table('tbl_mibl_creatives')->insertGetId([
  'file_name'=>$file_name,
  'advertisement_id'=>$advertisement_id,
  'file_description'=>$file_description,
  'category_id'=>$category_id,
  'brand_id'=>$brand_id,
  'department_id'=>$department_id,
  'document_type_id'=>$document_type_id,
  'vendor_id'=>$vendor_id,
  'date_of_posting'=>$date_of_posting,
  'date_of_upload'=>date('Y-m-d'),
  'other_document_type'=>$other_document_type,
  'photo_url'=>$photo_url,
  'source_file'=>$source_file,
  'file_type'=>$filetype,
  'archive_category_id'=>$archive_category_id,
  'archive_sub_category_id'=>$archive_sub_category_id,
  'department_type_id'=>$department_type_id,
  'vendor_type_id'=>$vendor_type_id,
  'language_id'=>$language_id,
  'irdai_date'=>$irdai_date,
  'irdai_addressed'=>$irdai_addressed,
  'remark'=>$remark,
  'video_url'=>$VIDEOID,
  'created_date'=>date('Y-m-d H:i:s'),
  'created_by'=>$username,
  ]);

/*Insert user activity*/
DB::table('tbl_mibl_user_activity')
->insert([
 'user_id' =>$user_id,
 'user_name'=>$username,
 'activity_group_id'=>$last_id,
 'messgage'=>'add single file upload successfully',
 'activity_type'=>'Insert',
 'activity_group'=>'add single file upload',
 'created_date' => date('Y-m-d H:i:s'),
 ]);  
  
  session()->flash('successmsg', 'Creative added successfully.');
  return redirect('/add-single-file-upload');
}else
{
  session()->flash('failmsg', 'Advertisement id already exists.');
  return redirect('/add-single-file-upload');
}


}else
{
  session()->flash('failmsg', 'Advertisement ID can not be blank');
  return redirect('/add-single-file-upload');
}

}else
{
  session()->flash('failmsg', 'Kindly upload file upto size 200MB.');
  return redirect('/add-single-file-upload');   
}


}

//Vendor Type
  
public function view_vendor_type(Request $request)
{
return view('admin/view_vendor_type');
}

public function add_vendor_type(Request $request)
{
 return view('admin/add_vendor_type');
}

public function insert_vendor_type(Request $request)
{

$vendor_type_name = $request->input('vendor_type_name');

$data = DB::table('tbl_mibl_master_vendor_type')
  ->select('*')
  ->where('vendor_type_name',$request->input('vendor_type_name'))
  ->get();
if(count($data)== '0'){  


$user_id=session('id');
$user = DB::table('tbl_mibl_user')
->select('*')
->where('deleted_at','=',0)
->where('id',$user_id)
->orderBy('id', 'desc')
->first();
$username=$user->name;


$last_id=DB::table('tbl_mibl_master_vendor_type')->insertGetId([
    'vendor_type_name' =>$vendor_type_name,
    'created_date' => date('Y-m-d H:i:s'),
    'created_by'=>$username
    ]);

/*Insert user activity*/

DB::table('tbl_mibl_user_activity')
     ->insert([
      'user_id' =>$user_id,
      'user_name'=>$username,
      'activity_group_id'=>$last_id,
      'messgage'=>'Vendor added successfully',
      'activity_type'=>'Insert',
      'activity_group'=>'User Type',
      'created_date' => date('Y-m-d H:i:s'),
      ]);  
   
    session()->flash('successmsg', 'Vendor type added successfully.');
    return redirect('view-vendor-type');
  }else
  {
    session()->flash('failmsg', 'Vendor type already exists.');
    return redirect('view-vendor-type');
  }
}



public function edit_vendor_type($id)
{

$id = base64_decode($id); 

$data = DB::table('tbl_mibl_master_vendor_type')
->select('*')
->where('id', '=', $id)
->get();

return view('admin/edit_vendor_type', ['edit_services' => $data]);
}


public function update_vendor_type(Request $request)
{

$vendor_type_name = $request->input('vendor_type_name');
$active_yn = $request->input('active_yn');
$id = $request->input('id');

$data = DB::table('tbl_mibl_master_vendor_type')
  ->select('*')
  ->where('vendor_type_name',$request->input('vendor_type_name'))
  ->where('id','!=',$id)
  ->get();
if(count($data)== '0'){  

DB::table('tbl_mibl_master_vendor_type')
->where('id', $id)
->update([
'vendor_type_name' => $vendor_type_name,
'active_yn' => $active_yn,
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

$last_id = $request->input('id');
DB::table('tbl_mibl_user_activity')
     ->insert([
      'user_id' =>$user_id,
      'user_name'=>$username,
      'activity_group_id'=>$last_id,
      'messgage'=>'Vendor type updated successfully',
      'activity_type'=>'Updated',
      'activity_group'=>'Vendor Type',
      'created_date' => date('Y-m-d H:i:s'),
      ]);


session()->flash('successmsg', 'Vendor type updated successfully.');
return redirect('view-vendor-type');
}else
{
  session()->flash('failmsg', 'Vendor type already exists.');
  return redirect('view-vendor-type');
}
}





public function getvendor_type(Request $request){

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

  // Total records
  $totalRecords = Vendor_type::select('count(*) as allcount')->count();
  $totalRecordswithFilter = Vendor_type::select('count(*) as allcount')
                           ->where('vendor_type_name', 'like', '%' .$searchValue . '%')
                           ->orWhere('active_yn', 'like', '%' .$status . '%')
                           ->orWhere('created_date', 'like', '%' .$searchValue. '%')
                           ->count();

  // Fetch records
  $records = Vendor_type::orderBy($columnName,$columnSortOrder)
    ->where('tbl_mibl_master_vendor_type.vendor_type_name', 'like', '%' .$searchValue . '%')
    ->orWhere('tbl_mibl_master_vendor_type.active_yn', 'like', '%' .$status . '%')
    ->orWhere('tbl_mibl_master_vendor_type.created_date', 'like', '%' .$searchValue. '%')
    ->select('tbl_mibl_master_vendor_type.*')
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
      $created_date= date("d/m/Y", strtotime($record->created_date));
     }

     if(!empty($record->id))
     {
      $APP_URL=$_ENV['APP_URL']."edit-vendor-type/".base64_encode($record->id);
      $img="<img src='".$_ENV['APP_URL']."assets/img/edit.png' class='img-fluid tab-img'>";
      $edit_link="<a href='".$APP_URL."'>$img</a>";     }
     

     $data_arr[] = array(
       "id" =>$i,
       "vendor_type_name" =>$record->vendor_type_name,
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



//Department Type
  
public function view_department_type(Request $request)
{
return view('admin/view_department_type');
}

public function add_department_type(Request $request)
{
 return view('admin/add_department_type');
}

public function insert_department_type(Request $request)
{

$department_type_name = $request->input('department_type_name');

$data = DB::table('tbl_mibl_master_department_type')
  ->select('*')
  ->where('department_type_name',$request->input('department_type_name'))
  ->get();
if(count($data)== '0'){  


$user_id=session('id');
$user = DB::table('tbl_mibl_user')
->select('*')
->where('deleted_at','=',0)
->where('id',$user_id)
->orderBy('id', 'desc')
->first();
$username=$user->name;


$last_id=DB::table('tbl_mibl_master_department_type')->insertGetId([
    'department_type_name' =>$department_type_name,
    'created_date' => date('Y-m-d H:i:s'),
    'created_by'=>$username
    ]);

/*Insert user activity*/

DB::table('tbl_mibl_user_activity')
     ->insert([
      'user_id' =>$user_id,
      'user_name'=>$username,
      'activity_group_id'=>$last_id,
      'messgage'=>'Department Type added successfully',
      'activity_type'=>'Insert',
      'activity_group'=>'User Type',
      'created_date' => date('Y-m-d H:i:s'),
      ]);  
   
    session()->flash('successmsg', 'Department type added successfully.');
    return redirect('view-department-type');
  }else
  {
    session()->flash('failmsg', 'Department type already exists.');
    return redirect('view-department-type');
  }
}



public function edit_department_type($id)
{

$id = base64_decode($id); 

$data = DB::table('tbl_mibl_master_department_type')
->select('*')
->where('id', '=', $id)
->get();

return view('admin/edit_department_type', ['edit_services' => $data]);
}


public function update_department_type(Request $request)
{

$department_type_name = $request->input('department_type_name');
$active_yn = $request->input('active_yn');
$id = $request->input('id');


$data = DB::table('tbl_mibl_master_department_type')
  ->select('*')
  ->where('department_type_name',$request->input('department_type_name'))
  ->where('id','!=',$id)
  ->get();
if(count($data)== '0'){

DB::table('tbl_mibl_master_department_type')
->where('id', $id)
->update([
'department_type_name' => $department_type_name,
'active_yn' => $active_yn,
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
$last_id = $request->input('id');
DB::table('tbl_mibl_user_activity')
     ->insert([
      'user_id' =>$user_id,
      'user_name'=>$username,
      'activity_group_id'=>$last_id,
      'messgage'=>'Department type updated successfully',
      'activity_type'=>'Updated',
      'activity_group'=>'Department Type',
      'created_date' => date('Y-m-d H:i:s'),
      ]);


session()->flash('successmsg', 'Department type updated successfully.');
return redirect('view-department-type');
}else
{
  session()->flash('failmsg', 'Department type already exists.');
  return redirect('view-department-type');
}
}





public function getdepartment_type(Request $request){

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

  // Total records
  $totalRecords = Department_type::select('count(*) as allcount')->count();
  $totalRecordswithFilter = Department_type::select('count(*) as allcount')
                           ->where('department_type_name', 'like', '%' .$searchValue . '%')
                           ->orWhere('active_yn', 'like', '%' .$status . '%')
                           ->orWhere('created_date', 'like', '%' .$searchValue. '%')
                           ->count();

  // Fetch records
  $records = Department_type::orderBy($columnName,$columnSortOrder)
    ->where('tbl_mibl_master_department_type.department_type_name', 'like', '%' .$searchValue . '%')
    ->orWhere('tbl_mibl_master_department_type.active_yn', 'like', '%' .$status . '%')
    ->orWhere('tbl_mibl_master_department_type.created_date', 'like', '%' .$searchValue. '%')
    ->select('tbl_mibl_master_department_type.*')
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
      $created_date= date("d/m/Y", strtotime($record->created_date));
     }

     if(!empty($record->id))
     {
      $APP_URL=$_ENV['APP_URL']."edit-department-type/".base64_encode($record->id);
      $img="<img src='".$_ENV['APP_URL']."assets/img/edit.png' class='img-fluid tab-img'>";
      $edit_link="<a href='".$APP_URL."'>$img</a>";     }
     

     $data_arr[] = array(
       "id" =>$i,
       "department_type_name" =>$record->department_type_name,
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



/*  */

public function view_archive_category(Request $request)
{
return view('/admin/view_archive_category');
}


public function add_archive_category(Request $request)
{
 return view('/admin/add_archive_category');
}


public function insert_archive_category(Request $request)
{

$data = DB::table('tbl_mibl_master_archive_category')
->select('*')
->where('name',$request->input('name'))
->orWhere('keyword',$request->input('keyword'))
->get();
if(count($data)== '0'){    
$name = $request->input('name');
$keyword = $request->input('keyword');


$user_id=session('id');
$user = DB::table('tbl_mibl_user')
->select('*')
->where('deleted_at','=',0)
->where('id',$user_id)
->orderBy('id', 'desc')
->first();
$username=$user->name;

$last_id=DB::table('tbl_mibl_master_archive_category')->insertGetId([
    'name' =>$name,
    'keyword' =>$keyword,
    'created_date' => date('Y-m-d H:i:s'),
    'created_by'=>$username,
    ]);
    

/*Insert user activity*/

DB::table('tbl_mibl_user_activity')
     ->insert([
      'user_id' =>$user_id,
      'user_name'=>$username,
      'activity_group_id'=>$last_id,
      'messgage'=>'Archive Category added successfully',
      'activity_type'=>'Insert',
      'activity_group'=>'Category',
      'created_date' => date('Y-m-d H:i:s'),
      ]);  
      
      
    session()->flash('successmsg', 'Archive category added successfully.');
    return redirect('view-archive-category');
  }else
  {
    session()->flash('failmsg', 'Archive category already exists.');
    return redirect('view-archive-category');
  }
}

public function edit_archive_category($id)
{
$id=base64_decode($id);
$data = DB::table('tbl_mibl_master_archive_category')
->select('*')
->where('id', '=', $id)
->get();

return view('/admin/edit_archive_category', ['edit_services' => $data]);
}

public function update_archive_category(Request $request)
{

$name = $request->input('name');
$keyword = $request->input('keyword');
$active_yn = $request->input('active_yn');
$id = $request->input('id');


$data = DB::table('tbl_mibl_master_archive_category')
->select('*')
// ->where('name',$request->input('name'))
// ->orWhere('keyword',$request->input('keyword'))
->where(function ($query) use ($keyword,$name){
  $query->where('name',$name)
         ->orWhere('keyword',$keyword);
        })
->where('id','!=',$id)
->get();

  

if(count($data)== '0'){    

DB::table('tbl_mibl_master_archive_category')
->where('id', $id)
->update([
'name' => $name,
'keyword' =>$keyword,
'active_yn' => $active_yn,
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
 $last_id = $request->input('id');

 DB::table('tbl_mibl_user_activity')
 ->insert([
  'user_id' =>$user_id,
  'user_name'=>$username,
  'activity_group_id'=>$last_id,
  'messgage'=>'Archive Category Updated successfully',
  'activity_type'=>'Updated',
  'activity_group'=>'Archive Category',
  'created_date' => date('Y-m-d H:i:s'),
  ]);


session()->flash('successmsg', 'Archive category updated successfully.');
return redirect('view-archive-category');
}else
{
  session()->flash('failmsg', 'Archive category already exists.');
  return redirect('view-archive-category');
}

}





public function getarchive_category(Request $request){

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
  $created_dated='';
}


  // Total records
  $totalRecords = Archive_Category::select('count(*) as allcount')->count();
  $totalRecordswithFilter = Archive_Category::select('count(*) as allcount')
                           ->where('name', 'like', '%' .$searchValue . '%')
                           ->orWhere('keyword', 'like', '%' .$searchValue . '%')
                           ->orWhere('active_yn', 'like', '%' .$status . '%')
                           ->orWhere('created_date', 'like', '%' .$created_dated. '%')
                           ->count();

  // Fetch records
  $records = Archive_Category::orderBy($columnName,$columnSortOrder)
    ->where('tbl_mibl_master_archive_category.name', 'like', '%' .$searchValue . '%')
    ->orWhere('tbl_mibl_master_archive_category.keyword', 'like', '%' .$searchValue . '%')
    ->orWhere('tbl_mibl_master_archive_category.active_yn', 'like', '%' .$status . '%')
    ->orWhere('tbl_mibl_master_archive_category.created_date', 'like', '%' .$created_dated. '%')
    ->select('tbl_mibl_master_archive_category.*')
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
      $created_date=date("d/m/Y", strtotime($record->created_date));
     }

     if(!empty($record->id))
     {
      $APP_URL=$_ENV['APP_URL']."edit-archive-category/".base64_encode($record->id);
      $img="<img src='".$_ENV['APP_URL']."assets/img/edit.png' class='img-fluid tab-img'>";
      $edit_link="<a href='".$APP_URL."'>$img</a>";     }
     

     $data_arr[] = array(
       "id" =>$i,
       "name" =>$record->name,
       "keyword" =>$record->keyword,
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

public function get_department(Request $request)
{
    $department_type_id = $request->get('department_type_id');
    $data = DB::table('tbl_mibl_master_department')->where('department_type_id',$department_type_id)->where('active_yn',0)->orderBy('name', 'ASC')->get();
    $output = '<option value="">Select Department</option>';
    foreach ($data as $row) {
        $output .= '<option value="' . $row->id . '">' . $row->name . '</option>';
    }
    echo $output;
    die;
}


public function get_vendor(Request $request)
{
    $vendor_type_id = $request->get('vendor_type_id');
    $data = DB::table('tbl_mibl_master_vendor')->where('vendor_type_id',$vendor_type_id)->where('active_yn',0)->orderBy('name', 'ASC')->get();
    $output = '<option value="">Select Vendor</option>';
    foreach ($data as $row) {
        $output .= '<option value="' . $row->id . '">' . $row->name . '</option>';
    }
    echo $output;
    die;
}


public function get_archive_sub_category(Request $request)
{
    $archive_category_id = $request->get('archive_category_id');
    $data = DB::table('tbl_mibl_master_archive_sub_category')->where('archive_category_id',$archive_category_id)->where('active_yn',0)->orderBy('name', 'ASC')->get();
    $output = '<option value="">Select Archive Sub Category</option>';
    foreach ($data as $row) {
        $output .= '<option value="' . $row->id . '">' . $row->name . '</option>';
    }
    echo $output;
    die;
}

//Manage Creatives

public function view_creatives(Request $request)
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



return view('/admin/view_creatives',
['archive_c'=>$archive_c,
 'department_c'=>$department_c,
 'document_type_list'=>$document_type,
 'vendor_c'=>$vendor_c]);
}

public function getcreatives(Request $request){


  //custom search 
  
  $vendor_name = (!empty($_GET["vendor_id"])) ? ($_GET["vendor_id"]) : ('');
  $advertisement_id = (!empty($_GET["advertisement_id"])) ? ($_GET["advertisement_id"]) : ('');
  $archive_category_id = (!empty($_GET["archive_category_id"])) ? ($_GET["archive_category_id"]) : ('');
  $department_id = (!empty($_GET["department_id"])) ? ($_GET["department_id"]) : ('');
  $from_date = (!empty($_GET["from_date"])) ? ($_GET["from_date"]) : ('');
  $to_date = (!empty($_GET["to_date"])) ? ($_GET["to_date"]) : ('');
  
  
// if(!empty($from_date) && !empty($to_date)){
//     $fdate=explode("-",$from_date);
//     $from_date1=$fdate[0]."".$fdate[1];
//     $tdate=explode("-",$to_date);
//     $to_date1=$tdate[0]."".$tdate[1];
//     $result->whereRaw("DATE_FORMAT(tbl_mibl_creatives.date_of_posting, '%Y%m') >= '" . $from_date1 . "' AND DATE_FORMAT(tbl_mibl_creatives.date_of_posting, '%Y%m') <= '" . $to_date1 . "'");
//   }


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
  if(!empty($searchValue1))
  {
    $end_date = date('Y-m-d');
    $start_date = date("Y-m-d", strtotime("-3 years"));
  // Total records
    $totalRecords = Creatives::select('count(*) as allcount')->count();
    $totalRecordswithFilter = Creatives::select('count(*) as allcount')
  ->leftJoin('tbl_mibl_master_archive_category', 'tbl_mibl_master_archive_category.id', '=', 'tbl_mibl_creatives.archive_category_id')
  ->leftJoin('tbl_mibl_master_archive_sub_category', 'tbl_mibl_master_archive_sub_category.id', '=', 'tbl_mibl_creatives.archive_sub_category_id')
  ->leftJoin('tbl_mibl_master_category', 'tbl_mibl_master_category.id', '=', 'tbl_mibl_creatives.category_id')
  ->leftJoin('tbl_mibl_master_brand', 'tbl_mibl_master_brand.id', '=', 'tbl_mibl_creatives.brand_id')
  ->leftJoin('tbl_mibl_master_vendor', 'tbl_mibl_master_vendor.id', '=', 'tbl_mibl_creatives.vendor_id')
  ->leftJoin('tbl_mibl_master_department', 'tbl_mibl_master_department.id', '=', 'tbl_mibl_creatives.department_id')
  ->leftJoin('tbl_mibl_master_document_type', 'tbl_mibl_master_document_type.id', '=', 'tbl_mibl_creatives.document_type_id')
  ->leftJoin('tbl_mibl_master_department_type', 'tbl_mibl_master_department_type.id', '=', 'tbl_mibl_creatives.department_type_id')
  ->leftJoin('tbl_mibl_master_vendor_type', 'tbl_mibl_master_vendor_type.id', '=', 'tbl_mibl_creatives.vendor_type_id')
  ->where('tbl_mibl_creatives.file_name', 'like', '%' .$searchValue . '%')
  ->orWhere('tbl_mibl_master_archive_category.name', 'like', '%' .$searchValue . '%')
  ->orWhere('tbl_mibl_master_category.name', 'like', '%' .$searchValue . '%')
  ->orWhere('tbl_mibl_master_department_type.department_type_name', 'like', '%' .$searchValue . '%')
  ->orWhere('tbl_mibl_master_archive_sub_category.name', 'like', '%' .$searchValue . '%')
  ->orWhere('tbl_mibl_master_vendor_type.vendor_type_name', 'like', '%' .$searchValue . '%')
  ->orWhere('tbl_mibl_master_brand.name', 'like', '%' .$searchValue . '%')
  ->orWhere('tbl_mibl_master_vendor.name', 'like', '%' .$searchValue . '%')
  ->orWhere('tbl_mibl_master_department.name', 'like', '%' .$searchValue . '%')
  ->orWhere('tbl_mibl_master_document_type.name', 'like', '%' .$searchValue . '%')
  ->orWhere('tbl_mibl_creatives.active_yn', 'like', '%' .$status . '%')
  ->orWhere('tbl_mibl_creatives.created_date', 'like', '%' .$searchValue. '%')
  ->whereRaw("date(tbl_mibl_creatives.created_date) >= '" . $start_date . "' AND date(tbl_mibl_creatives.created_date) <= '" . $end_date . "'")
  ->count();
  
    // Fetch records
    $records = Creatives::orderBy($columnName,$columnSortOrder)
      ->where('tbl_mibl_creatives.file_name', 'like', '%' .$searchValue . '%')
      ->orWhere('tbl_mibl_creatives.active_yn', 'like', '%' .$status . '%')
      ->orWhere('tbl_mibl_master_department_type.department_type_name', 'like', '%' .$searchValue . '%')
      ->orWhere('tbl_mibl_master_archive_sub_category.name', 'like', '%' .$searchValue . '%')
      ->orWhere('tbl_mibl_master_vendor_type.vendor_type_name', 'like', '%' .$searchValue . '%')
      ->orWhere('tbl_mibl_master_archive_category.name', 'like', '%' .$searchValue . '%')
      ->orWhere('tbl_mibl_master_category.name', 'like', '%' .$searchValue . '%')
      ->orWhere('tbl_mibl_master_brand.name', 'like', '%' .$searchValue . '%')
      ->orWhere('tbl_mibl_master_vendor.name', 'like', '%' .$searchValue . '%')
      ->orWhere('tbl_mibl_master_department.name', 'like', '%' .$searchValue . '%')
      ->orWhere('tbl_mibl_master_document_type.name', 'like', '%' .$searchValue . '%')
      ->orWhere('tbl_mibl_creatives.created_date', 'like', '%' .$searchValue. '%')
      ->whereRaw("date(tbl_mibl_creatives.created_date) >= '" . $start_date . "' AND date(tbl_mibl_creatives.created_date) <= '" . $end_date . "'")
      ->leftJoin('tbl_mibl_master_document_type', 'tbl_mibl_master_document_type.id', '=', 'tbl_mibl_creatives.document_type_id')
      ->leftJoin('tbl_mibl_master_archive_sub_category', 'tbl_mibl_master_archive_sub_category.id', '=', 'tbl_mibl_creatives.archive_sub_category_id')
      ->leftJoin('tbl_mibl_master_department', 'tbl_mibl_master_department.id', '=', 'tbl_mibl_creatives.department_id')
      ->leftJoin('tbl_mibl_master_vendor', 'tbl_mibl_master_vendor.id', '=', 'tbl_mibl_creatives.vendor_id')
      ->leftJoin('tbl_mibl_master_brand', 'tbl_mibl_master_brand.id', '=', 'tbl_mibl_creatives.brand_id')
      ->leftJoin('tbl_mibl_master_category', 'tbl_mibl_master_category.id', '=', 'tbl_mibl_creatives.category_id')
      ->leftJoin('tbl_mibl_master_archive_category', 'tbl_mibl_master_archive_category.id', '=', 'tbl_mibl_creatives.archive_category_id')
      ->leftJoin('tbl_mibl_master_department_type', 'tbl_mibl_master_department_type.id', '=', 'tbl_mibl_creatives.department_type_id')
      ->leftJoin('tbl_mibl_master_vendor_type', 'tbl_mibl_master_vendor_type.id', '=', 'tbl_mibl_creatives.vendor_type_id')
      ->select('tbl_mibl_creatives.*','tbl_mibl_master_archive_category.name as archive_name','tbl_mibl_master_category.name as category_name',
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
  $totalRecords = Creatives::select('count(*) as allcount')->count();
  $result_Filter =Creatives::select('count(*) as allcount');
  $result_Filter->leftJoin('tbl_mibl_master_document_type', 'tbl_mibl_master_document_type.id', '=', 'tbl_mibl_creatives.document_type_id');
  $result_Filter->leftJoin('tbl_mibl_master_department', 'tbl_mibl_master_department.id', '=', 'tbl_mibl_creatives.department_id');
  $result_Filter->leftJoin('tbl_mibl_master_vendor', 'tbl_mibl_master_vendor.id', '=', 'tbl_mibl_creatives.vendor_id');
  $result_Filter->leftJoin('tbl_mibl_master_brand', 'tbl_mibl_master_brand.id', '=', 'tbl_mibl_creatives.brand_id');
  $result_Filter->leftJoin('tbl_mibl_master_category', 'tbl_mibl_master_category.id', '=', 'tbl_mibl_creatives.category_id');
  $result_Filter->leftJoin('tbl_mibl_master_archive_category', 'tbl_mibl_master_archive_category.id', '=', 'tbl_mibl_creatives.archive_category_id');
  $result_Filter->leftJoin('tbl_mibl_master_archive_sub_category', 'tbl_mibl_master_archive_sub_category.id', '=', 'tbl_mibl_creatives.archive_sub_category_id');
  $result_Filter->leftJoin('tbl_mibl_master_department_type', 'tbl_mibl_master_department_type.id', '=', 'tbl_mibl_creatives.department_type_id');
  $result_Filter->leftJoin('tbl_mibl_master_vendor_type', 'tbl_mibl_master_vendor_type.id', '=', 'tbl_mibl_creatives.vendor_type_id');

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
    $result_Filter->where('tbl_mibl_creatives.advertisement_id', 'like', '%' .$advertisement_id. '%');
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

  /*if(!empty($from_date) && !empty($to_date))
    {
    $start_date = date('Y-m-d', strtotime($from_date));
    $end_date = date('Y-m-d', strtotime($to_date));
    $result_Filter->whereRaw("date(tbl_mibl_creatives.created_date) >= '" . $start_date . "' AND date(tbl_mibl_creatives.created_date) <= '" . $end_date . "'");
    }else
    { 
      $end_date = date('Y-m-d');
      $start_date = date("Y-m-d", strtotime("-3 years"));
      $result_Filter->whereRaw("date(tbl_mibl_creatives.created_date) >= '" . $start_date . "' AND date(tbl_mibl_creatives.created_date) <= '" . $end_date . "'");
    }*/


    if(!empty($from_date) && !empty($to_date))
    {
    $from_date = date('Y-m', strtotime($from_date));
    $to_date = date('Y-m', strtotime($to_date));
    $fdate=explode("-",$from_date);
    $from_date1=$fdate[0]."".$fdate[1];
    $tdate=explode("-",$to_date);
    $to_date1=$tdate[0]."".$tdate[1];
    $result_Filter->whereRaw("DATE_FORMAT(tbl_mibl_creatives.date_of_posting, '%Y%m') >= '" . $from_date1 . "' AND DATE_FORMAT(tbl_mibl_creatives.date_of_posting, '%Y%m') <= '" . $to_date1 . "'");

    }
    
    /*else
    { 
    $to_date = date('Y-m');
    $from_date = date("Y-m", strtotime("-3 years"));
    $fdate=explode("-",$from_date);
    $from_date1=$fdate[0]."".$fdate[1];
    $tdate=explode("-",$to_date);
    $to_date1=$tdate[0]."".$tdate[1];
    $result_Filter->whereRaw("DATE_FORMAT(tbl_mibl_creatives.date_of_posting, '%Y%m') >= '" . $from_date1 . "' AND DATE_FORMAT(tbl_mibl_creatives.date_of_posting, '%Y%m') <= '" . $to_date1 . "'");
    }*/



  $totalRecordswithFilter=$result_Filter->count();
  
  
  
  
  
  // Fetch records
  $result =Creatives::orderBy($columnName,$columnSortOrder);
  
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

 /* if(!empty($from_date) && !empty($to_date))
    {
    $start_date = date('Y-m-d', strtotime($from_date));
    $end_date = date('Y-m-d', strtotime($to_date));
    $result->whereRaw("date(tbl_mibl_creatives.created_date) >= '" . $start_date . "' AND date(tbl_mibl_creatives.created_date) <= '" . $end_date . "'");
   }else
   { 
    $end_date = date('Y-m-d');
    $start_date = date("Y-m-d", strtotime("-3 years"));
     $result->whereRaw("date(tbl_mibl_creatives.created_date) >= '" . $start_date . "' AND date(tbl_mibl_creatives.created_date) <= '" . $end_date . "'");
   }
*/
  if(!empty($from_date) && !empty($to_date))
    {
    $from_date = date('Y-m', strtotime($from_date));
    $to_date = date('Y-m', strtotime($to_date));
    //$result_Filter->whereRaw("date(tbl_mibl_creatives.created_date) >= '" . $start_date . "' AND date(tbl_mibl_creatives.created_date) <= '" . $end_date . "'");
       $fdate=explode("-",$from_date);
        $from_date1=$fdate[0]."".$fdate[1];
        $tdate=explode("-",$to_date);
        $to_date1=$tdate[0]."".$tdate[1];
        $result->whereRaw("DATE_FORMAT(tbl_mibl_creatives.date_of_posting, '%Y%m') >= '" . $from_date1 . "' AND DATE_FORMAT(tbl_mibl_creatives.date_of_posting, '%Y%m') <= '" . $to_date1 . "'");

    }
    
    /*else
    { 
      $to_date = date('Y-m');
      $from_date = date("Y-m", strtotime("-3 years"));
      //$result_Filter->whereRaw("date(tbl_mibl_creatives.created_date) >= '" . $start_date . "' AND date(tbl_mibl_creatives.created_date) <= '" . $end_date . "'");
        $fdate=explode("-",$from_date);
        $from_date1=$fdate[0]."".$fdate[1];
        $tdate=explode("-",$to_date);
        $to_date1=$tdate[0]."".$tdate[1];
        $result->whereRaw("DATE_FORMAT(tbl_mibl_creatives.date_of_posting, '%Y%m') >= '" . $from_date1 . "' AND DATE_FORMAT(tbl_mibl_creatives.date_of_posting, '%Y%m') <= '" . $to_date1 . "'");
    }*/

  
 

  $result->leftJoin('tbl_mibl_master_document_type', 'tbl_mibl_master_document_type.id', '=', 'tbl_mibl_creatives.document_type_id');
  $result->leftJoin('tbl_mibl_master_department', 'tbl_mibl_master_department.id', '=', 'tbl_mibl_creatives.department_id');
  $result->leftJoin('tbl_mibl_master_vendor', 'tbl_mibl_master_vendor.id', '=', 'tbl_mibl_creatives.vendor_id');
  $result->leftJoin('tbl_mibl_master_brand', 'tbl_mibl_master_brand.id', '=', 'tbl_mibl_creatives.brand_id');
  $result->leftJoin('tbl_mibl_master_category', 'tbl_mibl_master_category.id', '=', 'tbl_mibl_creatives.category_id');
  $result->leftJoin('tbl_mibl_master_archive_category', 'tbl_mibl_master_archive_category.id', '=', 'tbl_mibl_creatives.archive_category_id');
  $result->leftJoin('tbl_mibl_master_archive_sub_category', 'tbl_mibl_master_archive_sub_category.id', '=', 'tbl_mibl_creatives.archive_sub_category_id');
  $result->leftJoin('tbl_mibl_master_department_type', 'tbl_mibl_master_department_type.id', '=', 'tbl_mibl_creatives.department_type_id');
  $result->leftJoin('tbl_mibl_master_vendor_type', 'tbl_mibl_master_vendor_type.id', '=', 'tbl_mibl_creatives.vendor_type_id');
  $result->select('tbl_mibl_creatives.*','tbl_mibl_master_archive_category.name as archive_name','tbl_mibl_master_category.name as category_name',
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
        $status="<span style='color:green'>Active</span>";
       }else{
        $status="<span style='color:red'>Inactive</span>";
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
        $APP_URL=$_ENV['APP_URL']."edit-creatives/".base64_encode($record->id);
        $img="<img src='".$_ENV['APP_URL']."assets/img/edit.png' class='img-fluid tab-img'>";
        $edit_link="<a href='".$APP_URL."'>$img</a>";  
       }
       
       if($record->file_type == 'image')
       {
        $year= date("Y", strtotime($record->date_of_posting));
        $month= date("m", strtotime($record->date_of_posting));
        $img="<img src='".$_ENV['APP_URL']."uploads/".$year."/".$month."/"."thumbnail/".$record->photo_url."' class='img-fluid tab-img'>";
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

        $source_file=$_ENV['APP_URL']."uploads/".$year."/".$month."/upload_source_file/".$record->source_file;
       if (file_exists($source_file)) {
        $source_file_d="<a href='".$source_file."'>Download</a>";
       }else
       {
        $source_file_d='';
       }
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
    
public function edit_creatives($id)
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

$data = DB::table('tbl_mibl_creatives')
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







return view('admin/edit_creatives', 
['edit_services' => $data,
'category_list' => $category,
'document_type_list' => $document_type,
'brand_list' => $brand,
'archive_c'=>$archive_c,
'department_c'=>$department_c,
'vendor_c'=>$vendor_c,
'languages'=>$languages]);
}

public function update_creatives(Request $request)
{
    
  $submitButton=$_POST['submitButton'];
  if($submitButton == 'update')
  {
      
  $advertisement_id=$request->input('advertisement_id');
  $id=$request->input('id');

  $data = DB::table('tbl_mibl_creatives')
  ->select('*')
  ->where('advertisement_id',$advertisement_id)
  ->where('id','!=',$id)
  ->get();

  $data_bulk = DB::table('tbl_mibl_creatives_bulk')
  ->select('*')
  ->where('advertisement_id',$advertisement_id)
  ->where('status','4')
  ->get();
  if(count($data) == 0 && count($data_bulk) == 0){



  if($request->file('photo') != '') 
  {

     if (( $_FILES["photo"]["size"] <= 200000000 )) {
   
      $mime = $_FILES['photo']['type'];
      $image=$request->file('photo');
      $filename=$image->getClientOriginalName();  
      $id=$request->input('id');
      $data = DB::table('tbl_mibl_creatives')
      ->select('*')
      ->where('id',$id)
      ->first();

      $source_file_new= $data->source_file;


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



      $year= date("Y", strtotime($request->input('date_of_posting')));
      $month= date("m", strtotime($request->input('date_of_posting')));
      
      $name_thumbnail='thumbnail';
      $name_thumbnail = "uploads/".$year."/".$month."/".$name_thumbnail;

      $name_preview='preview';
      $name_preview = "uploads/".$year."/".$month."/".$name_preview;

      $name_original='original';
      $name_original = "uploads/".$year."/".$month."/".$name_original;

      $name_upload_source_file='upload_source_file';
      $name_upload_source_file = "uploads/".$year."/".$month."/".$name_upload_source_file;

      $month_new = "uploads/".$year."/".$month;

$file_type=$request->input('file_type');

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
          DB::table('tbl_mibl_creatives')
          ->where('id', $id)
          ->update([
          'photo_url'=>$photo_url,
          'file_type'=>$filetype,
          ]);
                 

    }
     else
      {

      $filetype='other'; 
      
         
       //Video upload cloudflare
       $image_path=$request->file('photo')->getRealPath();
       $image=$request->file('photo');
       $filename1_test=$image->getClientOriginalName();
       $image_arr=explode(".",$filename1_test);
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
   
         //Download video Enbled
   
         // $url="https://api.cloudflare.com/client/v4/accounts/34cc3252d5c329c1d2ac13237b4972ed/stream/$VIDEOID/downloads";
         // $curl = curl_init();
         // curl_setopt_array($curl, [
         // CURLOPT_URL            => $url, // tmp url provided by cloudflare
         // CURLOPT_RETURNTRANSFER => 1,
         // CURLOPT_TIMEOUT        => 600,
         // CURLOPT_POST           => true,
         // CURLOPT_HTTPHEADER     => [
         // "X-Auth-Key: 43b3d73c452c8f2f536964033aa59622c3b9d","X-Auth-Email:marketing.mibl@gmail.com"
         // ],
         // ]);
         // $response = curl_exec($curl);
         // curl_close($curl);
         DB::table('tbl_mibl_creatives')
            ->where('id', $id)
            ->update([
            'video_url'=>$VIDEOID,
            'photo_url'=>$filename1_test,
            'file_type'=>$filetype,
            ]);
     }
     else
     {
      $image     = $request->file('photo');
      $filename=$filename_new;
      $file_name = $_FILES["photo"]["name"];
      $file_tmp  = $_FILES["photo"]["tmp_name"];
      $filename_ne  = $month_new.'/'.$filename;
      $arr_data['photo']=move_uploaded_file($file_tmp, env('BASE_PATH') . $filename_ne);

      $photo_url = $filename;           
      DB::table('tbl_mibl_creatives')
      ->where('id', $id)
      ->update([
      'photo_url'=>$photo_url,
      'file_type'=>$filetype,
      ]);

      }    
      
  }  
  
    }else
    {
    session()->flash('failmsg', 'Kindly upload file upto size 200MB.');
    return redirect('/edit-creatives/'.base64_encode($id));
    }  
        
  }


  if ($request->file('source_file') != '') {



      // $id=$request->input('id');
      // $data = DB::table('tbl_mibl_creatives')
      // ->select('*')
      // ->where('id',$id)
      // ->first();

      $image=$request->file('source_file');
      $filenamesource_file=$image->getClientOriginalName();  

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



    $year= date("Y", strtotime($request->input('date_of_posting')));
    $month= date("m", strtotime($request->input('date_of_posting')));

    $name_upload_source_file='upload_source_file';
    $name_upload_source_file = "uploads/".$year."/".$month."/".$name_upload_source_file; 

    $image     = $request->file('source_file');
    $filename1  =$filename_sourcefile;
    $file_name = $_FILES["source_file"]["name"];
    $file_tmp  = $_FILES["source_file"]["tmp_name"];
    $filename_n = $name_upload_source_file.'/'.$filename1;
    $arr_data['source_file']=move_uploaded_file($file_tmp, env('BASE_PATH') . $filename_n);
    $source_file = $filename1;
    DB::table('tbl_mibl_creatives')
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
$category_id =$request->input('category_id');
$brand_id = $request->input('brand_id');
// $department_id = $request->input('department_id');
$document_type_id = $request->input('document_type_id');
// $vendor_id = $request->input('vendor_id');
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


$arr=explode(",",$archive_category_id);

$archive_category_id=$arr[0];
$archive_sub_category_id=$arr[1];


$arr_1=explode(",",$department_type_id);

$department_type_id=$arr_1[0];
$department_id=$arr_1[1];


$arr_2=explode(",",$vendor_type_id);

$vendor_type_id=$arr_2[0];
$vendor_id=$arr_2[1];


DB::table('tbl_mibl_creatives')
->where('id', $id)
->update([
  'file_name'=>$file_name,
  'advertisement_id'=>$advertisement_id,
  'file_description'=>$file_description,
  'category_id'=>$category_id,
  'brand_id'=>$brand_id,
  'department_id'=>$department_id,
  'document_type_id'=>$document_type_id,
  'vendor_id'=>$vendor_id,
  // 'date_of_posting'=>$date_of_posting,
  // 'date_of_upload'=>$date_of_upload,
  'other_document_type'=>$other_document_type,
  'archive_category_id'=>$archive_category_id,
  'archive_sub_category_id'=>$archive_sub_category_id,
  'department_type_id'=>$department_type_id,
  'vendor_type_id'=>$vendor_type_id,
  'active_yn'=>$active_yn,
  'language_id'=>$language_id,
  'remark'=>$remark,
  'irdai_addressed'=>$irdai_addressed,
  'irdai_date'=>$irdai_date,
  'modify_date'=>date('Y-m-d H:i:s'),
  'created_by'=>$username,
  ]);

 $last_id=$id;
 DB::table('tbl_mibl_user_activity')
 ->insert([
  'user_id' =>$user_id,
  'user_name'=>$username,
  'activity_group_id'=>$last_id,
  'messgage'=>'Creative Updated successfully',
  'activity_type'=>'Updated',
  'activity_group'=>'Creative',
  'created_date' => date('Y-m-d H:i:s'),
  ]);

    

  session()->flash('successmsg', 'Creative updated successfully.');
  return redirect('/edit-creatives/'.base64_encode($id));
}else
{
  session()->flash('failmsg', 'Advertisement id already exists.');
  return redirect('/edit-creatives/'.base64_encode($id));
}

}
else
{
    
  $id=$request->input('id');

  $user_id=session('id');
  $user = DB::table('tbl_mibl_user')
  ->select('*')
  ->where('deleted_at','=',0)
  ->where('id',$user_id)
  ->orderBy('id', 'desc')
  ->first();
  
    $username=$user->name;
    $bulk_list = DB::table('tbl_mibl_creatives')
    ->select('*')
    ->where('id',$id)
    ->first();
    
    $advertisement_id=$bulk_list->advertisement_id;

    $data = DB::table('tbl_mibl_creatives_vendor')
    ->select('*')
    ->where('advertisement_id',$advertisement_id)
    ->get();
    
    if(count($data) == 0){
        
       # create directory of Year
       $year1=date("Y", strtotime($bulk_list->date_of_posting));
       $year = "upload_vendor/".$year1;
       # create directory if not exists in upload/ directory
       if(!is_dir($year)){
         mkdir($year, 0777);
       }
      
        # create directory of Month
        $month1=date("m", strtotime($bulk_list->date_of_posting));
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
  

          //Video upload cloudflare
          $image_arr=explode(".",$bulk_list->photo_url);
          $doc_type=end($image_arr);
          $VIDEOID='';

          if($bulk_list->file_type == 'other')
            {

            if($doc_type == 'mp4')
            {
            $VIDEOID=$bulk_list->video_url;
            $filename_new=$bulk_list->photo_url;
            }else
            {

            $filename=$bulk_list->photo_url;
            $data = DB::table('tbl_mibl_creatives_vendor')
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
            $filename_ne="uploads/$year/$month/$bulk_list->photo_url";
            $copy_other="upload_vendor/$year/$month/$filename_new";
            File::copy($filename_ne,$copy_other); 

            if (file_exists($filename_ne)) {
              @unlink($filename_ne);
             }
          }

          }
            
            if($bulk_list->file_type == 'image')
            {

            $filename=$bulk_list->photo_url;
            $data = DB::table('tbl_mibl_creatives_vendor')
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
            $filename_thumbnail="uploads/$year/$month/thumbnail/$bulk_list->photo_url"; 
            $filename_preview="uploads/$year/$month/preview/$bulk_list->photo_url"; 
            $filename_original="uploads/$year/$month/original/$bulk_list->photo_url"; 

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
            $filename_source_file="uploads/$year/$month/upload_source_file/$bulk_list->source_file"; 
            $copy_source_file=$name_upload_source_file."/".$bulk_list->source_file;
            File::copy($filename_source_file,$copy_source_file);
            
            if (file_exists($filename_source_file)) {
              @unlink($filename_source_file);
             }
            
          }


        
        $last_id = DB::table('tbl_mibl_creatives_vendor')->insertGetId([
        'file_name'=>$bulk_list->file_name,
        'advertisement_id'=>$bulk_list->advertisement_id,
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
        'type_id'=>$bulk_list->type_id,
        'remark'=>$bulk_list->remark,
        'created_date'=>date('Y-m-d H:i:s'),
        'created_by'=>$username,
        'video_url'=>$VIDEOID,
        'type_of_creative'=>$bulk_list->type_of_creative
        ]); 






      

    //Insert user activity

    DB::table('tbl_mibl_user_activity')
    ->insert([
    'user_id' =>$user_id,
    'user_name'=>$username,
    'activity_group_id'=>$last_id,
    'messgage'=>'Undo creative successfully',
    'activity_type'=>'Insert',
    'activity_group'=>'Undo Creative',
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

$subject="MBank: Admin has unapprove creative ".$advertisement_id;
$message="
Dear User,
Admin has unapprove the ".$file_name." creative with ".$advertisement_id." on MBank.

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
Admin has undo the ".$file_name." undo with ".$advertisement_id." on MBank.<br><br>

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

//=============== Notification Email Code End =============

      DB::table('tbl_mibl_advertisement_id')
      ->where('advertisement_id',$advertisement_id)
      ->update([
      'flag'=>2,
      ]);

    //delete bulk entry
    
    DB::table('tbl_mibl_creatives')
    ->where('id',$id)
    ->delete();
    
    session()->flash('successmsg', 'creative is unapproved successfully.');
    return redirect('/view-advance-search/');  
 }
     DB::table('tbl_mibl_creatives')
    ->where('id',$id)
    ->delete();
    session()->flash('successmsg', 'creative is unapproved successfully.');
    return redirect('/view-advance-search/');  
}


}



//Bluk File upload


public function add_bulk_file_upload(Request $request)
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
   ->whereNull('tbl_mibl_creatives_bulk.flag')
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
   ->whereNull('tbl_mibl_creatives_bulk.flag')
   ->whereIn('tbl_mibl_creatives_bulk.status', [1, 2, 3])
->get();


return view('/admin/bulk_file_upload',['verify' => $verify,'unverify'=>$unverify,'year_list'=>$year_list]);
  //return view('/admin/bulk_file_upload',['year_list' => $year_list]);
}



function insert_bluk_upload(Request $request)
{ 



  $file=$request->file('csv_file');
  $handle=fopen($request->file('csv_file'),'r');
  while (($filesop = fgetcsv($handle, 1000, ",")) !== false) {
    // count($line) is the number of columns
    $numcols = count($filesop);
  }





if($numcols == 17)
{

  $file=$request->file('csv_file');
  $j=0;
  $sk=0;
  $handle=fopen($request->file('csv_file'),'r');
  while (($filesop = fgetcsv($handle, 1000, ",")) !== false) {
    $j++;
    $source_file = $filesop[16];
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
						$advertisement_id = $filesop[2];
						$file_description = $filesop[3];
            $language = $filesop[4];
						$brand = $filesop[5];
						$department_type = $filesop[6];
						$department = $filesop[7];
						$document_type = $filesop[8];
						$vendor_type = $filesop[9];
            $vendor = $filesop[10];
            $archive_category = $filesop[11];
            $archive_sub_category = $filesop[12];
            $photo_url = $filesop[13];
            $other_document_type = $filesop[14];
            $file_type = $filesop[15];
            $source_file = $filesop[16];


            @$date_of_posting=$year1."-".$month1."-01";

            $language_details =  DB::table('tbl_mibl_master_language')->where('language', trim($language))->first();
            @$language_id = $language_details->id;

            $brand_details =  DB::table('tbl_mibl_master_brand')->where('name', trim($brand))->first();
            @$brand_id = $brand_details->id;

            $department_details =  DB::table('tbl_mibl_master_department')->where('name', trim($department))->first();
            @$department_id = $department_details->id;
            @$department_type_id = $department_details->department_type_id;

            $document_type_details =  DB::table('tbl_mibl_master_document_type')->where('name', trim($document_type))->first();
            @$document_type_id = $document_type_details->id;

            $vendor_type_details =  DB::table('tbl_mibl_master_vendor_type')->where('vendor_type_name', trim($vendor_type))->first();
            @$vendor_type_id = $vendor_type_details->id;

            $vendor_details =  DB::table('tbl_mibl_master_vendor')->where('name', trim($vendor))->first();
            @$vendor_id = $vendor_details->id;


            $archive_category_details =  DB::table('tbl_mibl_master_archive_category')->where('name', trim($archive_category))->first();
            @$archive_category_id = $archive_category_details->id;

            $archive_sub_category_details =  DB::table('tbl_mibl_master_archive_sub_category')->where('name', trim($archive_sub_category))->where('archive_category_id', trim($archive_category_id))->first();
            @$archive_sub_category_id = $archive_sub_category_details->id;
           

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

        //  echo $filename_new;die;


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
              $data = DB::table('tbl_mibl_creatives')
              ->select('*')
              ->where('advertisement_id',$advertisement_id)
              ->get();

              $data_bulk = DB::table('tbl_mibl_creatives_bulk')
              ->select('*')
              ->where('advertisement_id',$advertisement_id)
              ->where('status','4')
              ->get();
              if(count($data) == 0 && count($data_bulk) == 0 && $advertisement_id !=''){  
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
            
						$insertGetId = DB::table('tbl_mibl_creatives_bulk')->insert([
							'file_name' => $file_name, 
              'advertisement_id' => $advertisement_id, 
              'file_description' => $file_description, 
              'language_id' => $language_id, 
              'brand_id' => $brand_id, 
              'department_id' => $department_id, 
              'department_type_id' => $department_type_id,
							'document_type_id' => $document_type_id,
              'vendor_type_id' => $vendor_type_id,
              'vendor_id' => $vendor_id,
              'archive_category_id' => $archive_category_id,
              'archive_sub_category_id' => $archive_sub_category_id,
							'photo_url' => $filename_new,
							'other_document_type' =>$other_document_type,
							'file_type' => $file_type,
							'date_of_posting' => $date_of_posting,
							'date_of_upload' =>date('Y-m-d H:i:s'),
              'created_date' =>date('Y-m-d H:i:s'),
              'created_by' => $username,
              'status'=>$status,
              'source_file'=>$filename_newsource_file,
						]);
          }
            $c ++;
					}


  session()->flash('successmsg', 'Files fetched successfully.');
  return redirect('add-bulk-file-upload');
}else
{
  session()->flash('failmsg', 'Uploaded Source file count does not match with csv file.');
  return redirect('add-bulk-file-upload');
}
}
else
{
  session()->flash('failmsg', 'Uploaded image count does not match with csv file.');
  return redirect('add-bulk-file-upload');
}
}else
{
  session()->flash('failmsg', 'Kindly select correct csv file for Bulk upload. Please refer sample csv file provided.');
  return redirect('add-bulk-file-upload');
}

}//main close


public function insert_bulk_creative_main(Request $request)
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
          //  echo  "<pre>";
          //  echo $result->preview;

          /*   sleep(15);
          //Download video Enbled
            $VIDEOID=$result->uid;
            $url="https://api.cloudflare.com/client/v4/accounts/34cc3252d5c329c1d2ac13237b4972ed/stream/$VIDEOID/downloads";
            $curl = curl_init();
            curl_setopt_array($curl, [
            CURLOPT_URL            => $url, // tmp url provided by cloudflare
            CURLOPT_RETURNTRANSFER => 1,
            CURLOPT_TIMEOUT        => 30,
            CURLOPT_POST           => true,
            CURLOPT_HTTPHEADER     => [
            "X-Auth-Key: 43b3d73c452c8f2f536964033aa59622c3b9d","X-Auth-Email:marketing.mibl@gmail.com"
            ],
            ]);
            $response = curl_exec($curl);
            curl_close($curl);
             */
            if (file_exists($filename_ne)) {
                @unlink($filename_ne);
            }

          }




    $last_id=DB::table('tbl_mibl_creatives')->insertGetId([
      'file_name'=>$bulk_list->file_name,
      'advertisement_id'=>$bulk_list->advertisement_id,
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
      'video_url'=>$VIDEOID
      ]); 


      /*Insert user activity*/

      DB::table('tbl_mibl_user_activity')
      ->insert([
      'user_id' =>$user_id,
      'user_name'=>$username,
      'activity_group_id'=>$last_id,
      'messgage'=>'Bulk Creative upload successfully',
      'activity_type'=>'Insert',
      'activity_group'=>'Bulk Creative upload',
      'created_date' => date('Y-m-d H:i:s'),
      ]);  
 

      //delete bulk entry

        DB::table('tbl_mibl_creatives_bulk')
        ->where('id' , $id)
        ->delete();
    }   

  }
  
  return response()->json(['success'=>'200']);


}



//incomplete data export

public function generate_csv_file_incomplete(Request $request)
	{
		$contents = "id,file_name,advertisement_id,file_description,language,brand,department_type,department,document_type,vendor_type,vendor,archive_category,archive_sub_category,photo_url,other_document_type,file_type,Source_file\n";
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
             'tbl_mibl_master_archive_sub_category.name as archive_sub_category',
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
       ->whereNull('tbl_mibl_creatives_bulk.flag')
       ->whereIn('tbl_mibl_creatives_bulk.status', [1, 2, 3])
       ->get();
    if(!empty($data)){
		foreach ($data as $key) {
      $contents .= $i . ",";
			$contents .= $key->file_name . ",";
			$contents .= $key->advertisement_id . ",";
			$contents .= $key->file_description . ",";
			$contents .= $key->language_name . ",";
			$contents .= $key->brand_name . ",";
			$contents .= $key->department_type_name . ",";
			$contents .= $key->department_name . ",";
			$contents .= $key->document_type_name . ",";
			$contents .= $key->vendor_type_name . ",";
			$contents .= $key->vendor_name . ",";
      $contents .= $key->archive_name . ",";
      $contents .= $key->archive_sub_category . ",";
      $contents .= $key->photo_url . ",";
      $contents .= $key->other_document_type . ",";
      $contents .= $key->file_type . ",";
			$contents .= $key->source_file . "\n";

      $i++;
		}

//delete bulk entry

DB::table('tbl_mibl_creatives_bulk')
->whereNull('tbl_mibl_creatives_bulk.flag')
->whereIn('tbl_mibl_creatives_bulk.status', [1, 2, 3])
->delete();

		$contents = strip_tags($contents);
		header("Content-Disposition: attachment; filename=creativefile" . date('d-m-Y') . ".csv");
		print $contents;



  }

  //sleep(10);

  //return redirect('add-bulk-file-upload');
}



/*============Logout======================*/


public function logout()
{
    Auth::logout();
    $user = [];
    // Session::put('user', $user);
    Session::put('mibladmin', $user);
    return redirect('/login');
}


/* Forget password*/

public function resetpassword(Request $request)
  {
      //validation : email_id and password
      $validator = Validator::make($request->all(), [
          'email' => 'required',
      ]);
      if ($validator->fails()) {
          return back()->withErrors($validator)->withInput();
      }
      //process form
      if (isset($request->email)) {
          $email=$request->email;
          $admin = DB::table('tbl_mibl_user')
                   ->select('*')
                   ->where('email', '=', $email)
                   ->first();
          if (!empty($admin)) {
               $str_result = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz';
              $randompassword=  substr(str_shuffle($str_result), 0, 8);
              // echo $test;
             $password = Hash::make($randompassword, ['rounds' => 8,]);
            $updatepass= DB::table('tbl_mibl_user')
             ->where('email', '=', $email)
             ->update([
              'password' => $password
             ]);
             $data = array();
          $data['email'] = $email;
          $data['password']= $randompassword;
          $data['name'] = $admin->name;
          $subject="Password Reset";
          Mail::send('ResetMail',['data'=>$data], function($message) use ($email, $subject) {
            $message->to($email)->subject($subject);
        });
        // return view('admin/forgot_password');
              session()->flash('successmsg', 'We have sent password to your  Mail Id.');
              return \Redirect::back();
          } else {
              session()->flash('failmsg', 'Invalid Email Address');
              return back()->withErrors(['message' => "Invalid Email Address"]);
          }
          return redirect('forgotpassword');
  }
}


//search code

public function view_search(Request $request)
{
  $searchValue = trim((!empty($_GET["search"])) ? ($_GET["search"]) : (''));
  $from_date = (!empty($_GET["from_date"])) ? ($_GET["from_date"]) : ('');
  $to_date = (!empty($_GET["to_date"])) ? ($_GET["to_date"]) : ('');
  if(!empty($from_date) || !empty($to_date))
  {
  $from_date = date('Y-m', strtotime($from_date));
  $to_date = date('Y-m', strtotime($to_date));
  }

  if(!empty($searchValue)){

  $result=DB::table('tbl_mibl_creatives');
  $result->leftJoin('tbl_mibl_master_archive_category','tbl_mibl_creatives.archive_category_id','=','tbl_mibl_master_archive_category.id');
  $result->leftJoin('tbl_mibl_master_archive_sub_category','tbl_mibl_creatives.archive_sub_category_id','=','tbl_mibl_master_archive_sub_category.id');
  $result->leftJoin('tbl_mibl_master_vendor','tbl_mibl_creatives.vendor_id','=','tbl_mibl_master_vendor.id');
  $result->leftJoin('tbl_mibl_master_vendor_type','tbl_mibl_creatives.vendor_type_id','=','tbl_mibl_master_vendor_type.id');
  $result->leftJoin('tbl_mibl_master_department_type','tbl_mibl_creatives.department_type_id','=','tbl_mibl_master_department_type.id');
  $result->leftJoin('tbl_mibl_master_department','tbl_mibl_creatives.department_id','=','tbl_mibl_master_department.id');
  $result->leftJoin('tbl_mibl_master_document_type','tbl_mibl_creatives.document_type_id','=','tbl_mibl_master_document_type.id');

  $result->where('tbl_mibl_creatives.active_yn',0);
  // $result->where('tbl_mibl_creatives.file_type','!=','other');
  if(!empty($from_date) && !empty($to_date)){
    $fdate=explode("-",$from_date);
    $from_date1=$fdate[0]."".$fdate[1];
    $tdate=explode("-",$to_date);
    $to_date1=$tdate[0]."".$tdate[1];
    $result->whereRaw("DATE_FORMAT(tbl_mibl_creatives.date_of_posting, '%Y%m') >= '" . $from_date1 . "' AND DATE_FORMAT(tbl_mibl_creatives.date_of_posting, '%Y%m') <= '" . $to_date1 . "'");
  }
$result->where(function ($query) use ($searchValue){
    $query->where('tbl_mibl_creatives.file_name','like','%'.$searchValue.'%')
           ->orWhere('tbl_mibl_creatives.advertisement_id','like','%'.$searchValue.'%')
           ->orWhere('tbl_mibl_master_archive_sub_category.name','like','%'.$searchValue.'%')
           ->orWhere('tbl_mibl_master_archive_category.name','like','%'.$searchValue.'%')
           ->orWhere('tbl_mibl_master_archive_category.name','like','%'.$searchValue.'%')
           ->orWhere('tbl_mibl_master_vendor.name','like','%'.$searchValue)
           ->orWhere('tbl_mibl_master_vendor_type.vendor_type_name','like','%'.$searchValue.'%')
           ->orWhere('tbl_mibl_master_department_type.department_type_name','like','%'.$searchValue.'%')
           ->orWhere('tbl_mibl_master_department.name','like','%'.$searchValue.'%')
           ->orWhere('tbl_mibl_creatives.photo_url','like','%'.$searchValue.'%');

          });
  $result->select('tbl_mibl_creatives.*','tbl_mibl_master_archive_category.name as archive_name','tbl_mibl_master_archive_sub_category.name as archive_sub_name','tbl_mibl_master_vendor.name as vendor_name'
  ,'tbl_mibl_master_vendor_type.vendor_type_name as vendor_type_name'
  ,'tbl_mibl_master_department_type.department_type_name as department_type_name' 
  ,'tbl_mibl_master_department.name as department_name','tbl_mibl_master_document_type.name as document_name'
  );
  $creatives=$result->paginate(9);

  } else
   {
    $result = DB::table('tbl_mibl_creatives');
    $result->select('tbl_mibl_creatives.*',
    'tbl_mibl_master_archive_category.name as archive_name',
    'tbl_mibl_master_archive_sub_category.name as archive_sub_name',
    'tbl_mibl_master_document_type.name as document_name','tbl_mibl_master_department_type.department_type_name as department_type_name' 
    ,'tbl_mibl_master_department.name as department_name','tbl_mibl_master_vendor.name as vendor_name'
    ,'tbl_mibl_master_vendor_type.vendor_type_name as vendor_type_name');
    $result->leftJoin('tbl_mibl_master_archive_category', 'tbl_mibl_master_archive_category.id', '=', 'tbl_mibl_creatives.archive_category_id');
    $result->leftJoin('tbl_mibl_master_archive_sub_category', 'tbl_mibl_master_archive_sub_category.id', '=', 'tbl_mibl_creatives.archive_sub_category_id');
    $result->leftJoin('tbl_mibl_master_document_type','tbl_mibl_creatives.document_type_id','=','tbl_mibl_master_document_type.id');
    $result->leftJoin('tbl_mibl_master_department_type','tbl_mibl_creatives.department_type_id','=','tbl_mibl_master_department_type.id');
    $result->leftJoin('tbl_mibl_master_department','tbl_mibl_creatives.department_id','=','tbl_mibl_master_department.id');  
    $result->leftJoin('tbl_mibl_master_vendor','tbl_mibl_creatives.vendor_id','=','tbl_mibl_master_vendor.id');
    $result->leftJoin('tbl_mibl_master_vendor_type','tbl_mibl_creatives.vendor_type_id','=','tbl_mibl_master_vendor_type.id');
    $result->where('tbl_mibl_creatives.active_yn',0);
    // $result->where('tbl_mibl_creatives.file_type','!=','other');
    if(!empty($from_date) && !empty($to_date)){
      $fdate=explode("-",$from_date);
      $from_date1=$fdate[0]."".$fdate[1];
      $tdate=explode("-",$to_date);
      $to_date1=$tdate[0]."".$tdate[1];
      $result->whereRaw("DATE_FORMAT(tbl_mibl_creatives.date_of_posting, '%Y%m') >= '" . $from_date1 . "' AND DATE_FORMAT(tbl_mibl_creatives.date_of_posting, '%Y%m') <= '" . $to_date1 . "'");
    }
    $result->orderBy('tbl_mibl_creatives.id','DESC');
    $creatives=$result->paginate(9);
   }


return view('admin/search',['creatives'=>$creatives,'searchValue'=>$searchValue,'from_date'=>$from_date,'to_date'=>$to_date]);
}


public function get_creatives_data(Request $request)
{

  $id = $request->get('id');
  $creatives = DB::table('tbl_mibl_creatives')
  ->select('tbl_mibl_creatives.*','tbl_mibl_master_archive_category.name as archive_name','tbl_mibl_master_archive_sub_category.name as archive_sub_name','tbl_mibl_master_department.name as department_name','tbl_mibl_master_vendor.name as vendor_name'
  ,'tbl_mibl_master_vendor_type.vendor_type_name as vendor_type_name')
  ->leftJoin('tbl_mibl_master_archive_category', 'tbl_mibl_master_archive_category.id', '=', 'tbl_mibl_creatives.archive_category_id')
  ->leftJoin('tbl_mibl_master_archive_sub_category', 'tbl_mibl_master_archive_sub_category.id', '=', 'tbl_mibl_creatives.archive_sub_category_id')
  ->leftJoin('tbl_mibl_master_department_type','tbl_mibl_creatives.department_type_id','=','tbl_mibl_master_department_type.id')
  ->leftJoin('tbl_mibl_master_department','tbl_mibl_creatives.department_id','=','tbl_mibl_master_department.id')  
  ->leftJoin('tbl_mibl_master_vendor','tbl_mibl_creatives.vendor_id','=','tbl_mibl_master_vendor.id')
  ->leftJoin('tbl_mibl_master_vendor_type','tbl_mibl_creatives.vendor_type_id','=','tbl_mibl_master_vendor_type.id')
  ->where('tbl_mibl_creatives.id',$id)
  ->first();
  echo json_encode($creatives);
  exit;
}





//language

  
public function view_language(Request $request)
{
return view('admin/view_language');
}


public function add_language(Request $request)
{
 return view('admin/add_language');
}

public function insert_language(Request $request)
{

$language = $request->input('language');

$data = DB::table('tbl_mibl_master_language')
  ->select('*')
  ->where('language',$request->input('language'))
  ->get();
if(count($data)== '0'){  


$user_id=session('id');
$user = DB::table('tbl_mibl_user')
->select('*')
->where('deleted_at','=',0)
->where('id',$user_id)
->orderBy('id', 'desc')
->first();
$username=$user->name;


$last_id=DB::table('tbl_mibl_master_language')->insertGetId([
    'language' =>$language,
    'created_date' => date('Y-m-d H:i:s'),
    'created_by'=>$username
    ]);

/*Insert user activity*/

DB::table('tbl_mibl_user_activity')
     ->insert([
      'user_id' =>$user_id,
      'user_name'=>$username,
      'activity_group_id'=>$last_id,
      'messgage'=>'Language added successfully',
      'activity_type'=>'Insert',
      'activity_group'=>'User Type',
      'created_date' => date('Y-m-d H:i:s'),
      ]);  
   
    session()->flash('successmsg', 'Language added successfully.');
    return redirect('view-language');
  }else
  {
    session()->flash('failmsg', 'Language already exists.');
    return redirect('view-language');
  }
}

public function edit_language($id)
{

$id = base64_decode($id); 

$data = DB::table('tbl_mibl_master_language')
->select('*')
->where('id', '=', $id)
->get();

return view('admin/edit_language', ['edit_services' => $data]);
}

public function update_language(Request $request)
{

$language = $request->input('language');
$active_yn = $request->input('active_yn');
$id = $request->input('id');

$data = DB::table('tbl_mibl_master_language')
  ->select('*')
  ->where('language',$request->input('language'))
  ->where('id','!=',$id)
  ->get();
if(count($data)== '0'){  

DB::table('tbl_mibl_master_language')
->where('id', $id)
->update([
'language' => $language,
'active_yn' => $active_yn,
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
$last_id = $request->input('id');

DB::table('tbl_mibl_user_activity')
     ->insert([
      'user_id' =>$user_id,
      'user_name'=>$username,
      'activity_group_id'=>$last_id,
      'messgage'=>'Language updated successfully',
      'activity_type'=>'Updated',
      'activity_group'=>'User Type',
      'created_date' => date('Y-m-d H:i:s'),
      ]);


session()->flash('successmsg', 'Language updated successfully.');
return redirect('view-language');
}else
{
  session()->flash('failmsg', 'Language already exists.');
  return redirect('view-language');
}
}



public function getlanguage(Request $request){

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


  // Total records
  $totalRecords = Language::select('count(*) as allcount')->count();
  $totalRecordswithFilter = Language::select('count(*) as allcount')
                           ->where('language', 'like', '%' .$searchValue . '%')
                           ->orWhere('active_yn', 'like', '%' .$status . '%')
                           ->orWhere('created_date', 'like', '%' .$created_dated. '%')
                           ->count();

  // Fetch records
  $records = Language::orderBy($columnName,$columnSortOrder)
    ->where('tbl_mibl_master_language.language', 'like', '%' .$searchValue . '%')
    ->orWhere('tbl_mibl_master_language.active_yn', 'like', '%' .$status . '%')
    ->orWhere('tbl_mibl_master_language.created_date', 'like', '%' .$created_dated. '%')
    ->select('tbl_mibl_master_language.*')
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
      $created_date= date("d/m/Y", strtotime($record->created_date));
     }

     if(!empty($record->id))
     {
      $APP_URL=$_ENV['APP_URL']."edit-language/".base64_encode($record->id);
      $img="<img src='".$_ENV['APP_URL']."assets/img/edit.png' class='img-fluid tab-img'>";
      $edit_link="<a href='".$APP_URL."'>$img</a>";
     }
     

     $data_arr[] = array(
       "id" =>$i,
       "language" =>$record->language,
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


//agreement
public function view_agreement(Request $request)
{

$year_list = DB::table('tbl_mibl_master_year')
->select('*')
->where('active_yn',0)
->get();  

return view('admin/view_agreement',['year_list'=>$year_list]);
}


public function insert_agreement(Request $request)
{

$data = DB::table('tbl_mibl_master_agreement')
  ->select('*')
  ->where('name',$request->input('name'))
  ->get();

if(count($data)== '0'){  
$name = $request->input('name');

$user_id=session('id');
$user = DB::table('tbl_mibl_user')
->select('*')
->where('deleted_at','=',0)
->where('id',$user_id)
->orderBy('id', 'desc')
->first();
$username=$user->name;



$last_id=DB::table('tbl_mibl_master_agreement')->insertGetId([
    'name'=>$name,
    'created_date'=>date('Y-m-d H:i:s'),
    'created_by'=>$username,
    ]);

/*Insert user activity*/

DB::table('tbl_mibl_user_activity')
->insert([
 'user_id' =>$user_id,
 'user_name'=>$username,
 'activity_group_id'=>$last_id,
 'messgage'=>'Agreement Type added successfully',
 'activity_type'=>'Insert',
 'activity_group'=>'Agreement Type',
 'created_date' => date('Y-m-d H:i:s'),
 ]);  
    session()->flash('successmsg', 'Agreement added successfully.');
    return redirect('view-agreement');
  }else
  {
    session()->flash('failmsg', 'Agreement already exists.');
    return redirect('view-agreement');
  }
}


public function getagreement(Request $request){

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

  // Total records
  $totalRecords = Agreement::select('count(*) as allcount')->count();
  $totalRecordswithFilter = Agreement::select('count(*) as allcount')
                          ->where('tbl_mibl_master_agreement.name', 'like', '%' .$searchValue . '%')
                          ->orWhere('tbl_mibl_master_agreement.year', 'like', '%' .$searchValue . '%')
                          ->orWhere('tbl_mibl_master_agreement.active_yn', 'like', '%' .$status . '%')
                          ->orWhere('tbl_mibl_master_agreement.created_date', 'like', '%' .$created_dated. '%')
                          ->count();

  // Fetch records
  $records = Agreement::orderBy($columnName,$columnSortOrder)
    ->where('tbl_mibl_master_agreement.name', 'like', '%' .$searchValue . '%')
    ->orWhere('tbl_mibl_master_agreement.year', 'like', '%' .$searchValue . '%')
    ->orWhere('tbl_mibl_master_agreement.active_yn', 'like', '%' .$status . '%')
    ->orWhere('tbl_mibl_master_agreement.created_date', 'like', '%' .$created_dated. '%')
    ->select('tbl_mibl_master_agreement.*')
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
      $created_date=date("d/m/Y", strtotime($record->created_date));
     }

     if(!empty($record->id))
     {
      $APP_URL=$_ENV['APP_URL']."edit-agreement/".base64_encode($record->id);
      $img="<img src='".$_ENV['APP_URL']."assets/img/edit.png' class='img-fluid tab-img'>";
      $edit_link="<a href='".$APP_URL."'>$img</a>";       }
     

     $data_arr[] = array(
       "id" =>$i,
       "name" =>$record->name,
       "year" =>$record->year,
       "document" =>$record->document,
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



public function edit_agreement($id)
{
$id=base64_decode($id);
$data = DB::table('tbl_mibl_master_agreement')
->select('*')
->where('id', '=', $id)
->get();

return view('/admin/edit_agreement', ['edit_services' => $data]);
}

public function update_agreement(Request $request)
{

$name = $request->input('name');
$active_yn = $request->input('active_yn');
$id = $request->input('id');

$data = DB::table('tbl_mibl_master_agreement')
  ->select('*')
  ->where('name',$request->input('name'))
  ->where('id','!=',$id)
  ->get();

if(count($data)== '0'){  
DB::table('tbl_mibl_master_agreement')
->where('id', $id)
->update([
'name' => $name,
'active_yn' => $active_yn,
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

 $last_id = $request->input('id');
 DB::table('tbl_mibl_user_activity')
 ->insert([
  'user_id' =>$user_id,
  'user_name'=>$username,
  'activity_group_id'=>$last_id,
  'messgage'=>'Agreement Type Updated successfully',
  'activity_type'=>'Updated',
  'activity_group'=>'Agreement Type',
  'created_date' => date('Y-m-d H:i:s'),
  ]);


session()->flash('successmsg', 'Agreement updated successfully.');
return redirect('view-agreement');
}else
{
  session()->flash('failmsg', 'Agreement already exists.');
  return redirect('view-agreement');
}

}


//IRDAI

public function view_creatives_irdai(Request $request){

 
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



return view('/admin/view_creatives_irdai',
['archive_c'=>$archive_c,
 'department_c'=>$department_c,
 'document_type_list'=>$document_type,
 'vendor_c'=>$vendor_c]);
}


public function getcreatives_irdai(Request $request){


  //custom search 
  
  $vendor_name = trim((!empty($_GET["vendor_id"])) ? ($_GET["vendor_id"]) : (''));
  $advertisement_id = trim((!empty($_GET["advertisement_id"])) ? ($_GET["advertisement_id"]) : (''));
  $archive_category_id = trim((!empty($_GET["archive_category_id"])) ? ($_GET["archive_category_id"]) : (''));
  $department_id = trim((!empty($_GET["department_id"])) ? ($_GET["department_id"]) : (''));
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
  if(!empty($searchValue1))
  {
    $end_date = date('Y-m-d');
    $start_date = date("Y-m-d", strtotime("-3 years"));
  // Total records
    $totalRecords = Creatives::select('count(*) as allcount')->count();
    $totalRecordswithFilter = Creatives::select('count(*) as allcount')
  ->leftJoin('tbl_mibl_master_archive_category', 'tbl_mibl_master_archive_category.id', '=', 'tbl_mibl_creatives.archive_category_id')
  ->leftJoin('tbl_mibl_master_archive_sub_category', 'tbl_mibl_master_archive_sub_category.id', '=', 'tbl_mibl_creatives.archive_sub_category_id')
  ->leftJoin('tbl_mibl_master_category', 'tbl_mibl_master_category.id', '=', 'tbl_mibl_creatives.category_id')
  ->leftJoin('tbl_mibl_master_brand', 'tbl_mibl_master_brand.id', '=', 'tbl_mibl_creatives.brand_id')
  ->leftJoin('tbl_mibl_master_vendor', 'tbl_mibl_master_vendor.id', '=', 'tbl_mibl_creatives.vendor_id')
  ->leftJoin('tbl_mibl_master_department', 'tbl_mibl_master_department.id', '=', 'tbl_mibl_creatives.department_id')
  ->leftJoin('tbl_mibl_master_document_type', 'tbl_mibl_master_document_type.id', '=', 'tbl_mibl_creatives.document_type_id')
  ->leftJoin('tbl_mibl_master_department_type', 'tbl_mibl_master_department_type.id', '=', 'tbl_mibl_creatives.department_type_id')
  ->leftJoin('tbl_mibl_master_vendor_type', 'tbl_mibl_master_vendor_type.id', '=', 'tbl_mibl_creatives.vendor_type_id')
  ->where('tbl_mibl_creatives.file_name', 'like', '%' .$searchValue . '%')
  ->orWhere('tbl_mibl_master_archive_category.name', 'like', '%' .$searchValue . '%')
  ->orWhere('tbl_mibl_master_category.name', 'like', '%' .$searchValue . '%')
  ->orWhere('tbl_mibl_master_department_type.department_type_name', 'like', '%' .$searchValue . '%')
  ->orWhere('tbl_mibl_master_archive_sub_category.name', 'like', '%' .$searchValue . '%')
  ->orWhere('tbl_mibl_master_vendor_type.vendor_type_name', 'like', '%' .$searchValue . '%')
  ->orWhere('tbl_mibl_master_brand.name', 'like', '%' .$searchValue . '%')
  ->orWhere('tbl_mibl_master_vendor.name', 'like', '%' .$searchValue . '%')
  ->orWhere('tbl_mibl_master_department.name', 'like', '%' .$searchValue . '%')
  ->orWhere('tbl_mibl_master_document_type.name', 'like', '%' .$searchValue . '%')
  ->orWhere('tbl_mibl_creatives.active_yn', 'like', '%' .$status . '%')
  ->orWhere('tbl_mibl_creatives.created_date', 'like', '%' .$searchValue. '%')
  ->whereRaw("date(tbl_mibl_creatives.created_date) >= '" . $start_date . "' AND date(tbl_mibl_creatives.created_date) <= '" . $end_date . "'")
  ->count();
  
    // Fetch records
    $records = Creatives::orderBy($columnName,$columnSortOrder)
      ->where('tbl_mibl_creatives.file_name', 'like', '%' .$searchValue . '%')
      ->orWhere('tbl_mibl_creatives.active_yn', 'like', '%' .$status . '%')
      ->orWhere('tbl_mibl_master_department_type.department_type_name', 'like', '%' .$searchValue . '%')
      ->orWhere('tbl_mibl_master_archive_sub_category.name', 'like', '%' .$searchValue . '%')
      ->orWhere('tbl_mibl_master_vendor_type.vendor_type_name', 'like', '%' .$searchValue . '%')
      ->orWhere('tbl_mibl_master_archive_category.name', 'like', '%' .$searchValue . '%')
      ->orWhere('tbl_mibl_master_category.name', 'like', '%' .$searchValue . '%')
      ->orWhere('tbl_mibl_master_brand.name', 'like', '%' .$searchValue . '%')
      ->orWhere('tbl_mibl_master_vendor.name', 'like', '%' .$searchValue . '%')
      ->orWhere('tbl_mibl_master_department.name', 'like', '%' .$searchValue . '%')
      ->orWhere('tbl_mibl_master_document_type.name', 'like', '%' .$searchValue . '%')
      ->orWhere('tbl_mibl_creatives.created_date', 'like', '%' .$searchValue. '%')
      ->whereRaw("date(tbl_mibl_creatives.created_date) >= '" . $start_date . "' AND date(tbl_mibl_creatives.created_date) <= '" . $end_date . "'")
      ->leftJoin('tbl_mibl_master_document_type', 'tbl_mibl_master_document_type.id', '=', 'tbl_mibl_creatives.document_type_id')
      ->leftJoin('tbl_mibl_master_archive_sub_category', 'tbl_mibl_master_archive_sub_category.id', '=', 'tbl_mibl_creatives.archive_sub_category_id')
      ->leftJoin('tbl_mibl_master_department', 'tbl_mibl_master_department.id', '=', 'tbl_mibl_creatives.department_id')
      ->leftJoin('tbl_mibl_master_vendor', 'tbl_mibl_master_vendor.id', '=', 'tbl_mibl_creatives.vendor_id')
      ->leftJoin('tbl_mibl_master_brand', 'tbl_mibl_master_brand.id', '=', 'tbl_mibl_creatives.brand_id')
      ->leftJoin('tbl_mibl_master_category', 'tbl_mibl_master_category.id', '=', 'tbl_mibl_creatives.category_id')
      ->leftJoin('tbl_mibl_master_archive_category', 'tbl_mibl_master_archive_category.id', '=', 'tbl_mibl_creatives.archive_category_id')
      ->leftJoin('tbl_mibl_master_department_type', 'tbl_mibl_master_department_type.id', '=', 'tbl_mibl_creatives.department_type_id')
      ->leftJoin('tbl_mibl_master_vendor_type', 'tbl_mibl_master_vendor_type.id', '=', 'tbl_mibl_creatives.vendor_type_id')
      ->select('tbl_mibl_creatives.*','tbl_mibl_master_archive_category.name as archive_name','tbl_mibl_master_category.name as category_name',
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
  $totalRecords = Creatives::select('count(*) as allcount')->count();
  $result_Filter =Creatives::select('count(*) as allcount');
  $result_Filter->leftJoin('tbl_mibl_master_document_type', 'tbl_mibl_master_document_type.id', '=', 'tbl_mibl_creatives.document_type_id');
  $result_Filter->leftJoin('tbl_mibl_master_department', 'tbl_mibl_master_department.id', '=', 'tbl_mibl_creatives.department_id');
  $result_Filter->leftJoin('tbl_mibl_master_vendor', 'tbl_mibl_master_vendor.id', '=', 'tbl_mibl_creatives.vendor_id');
  $result_Filter->leftJoin('tbl_mibl_master_brand', 'tbl_mibl_master_brand.id', '=', 'tbl_mibl_creatives.brand_id');
  $result_Filter->leftJoin('tbl_mibl_master_category', 'tbl_mibl_master_category.id', '=', 'tbl_mibl_creatives.category_id');
  $result_Filter->leftJoin('tbl_mibl_master_archive_category', 'tbl_mibl_master_archive_category.id', '=', 'tbl_mibl_creatives.archive_category_id');
  $result_Filter->leftJoin('tbl_mibl_master_archive_sub_category', 'tbl_mibl_master_archive_sub_category.id', '=', 'tbl_mibl_creatives.archive_sub_category_id');
  $result_Filter->leftJoin('tbl_mibl_master_department_type', 'tbl_mibl_master_department_type.id', '=', 'tbl_mibl_creatives.department_type_id');
  $result_Filter->leftJoin('tbl_mibl_master_vendor_type', 'tbl_mibl_master_vendor_type.id', '=', 'tbl_mibl_creatives.vendor_type_id');

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
    $result_Filter->where('tbl_mibl_creatives.advertisement_id', 'like', '%' .$advertisement_id. '%');
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

  /*
  if(!empty($from_date) && !empty($to_date))
    {
    $start_date = date('Y-m-d', strtotime($from_date));
    $end_date = date('Y-m-d', strtotime($to_date));
    $result_Filter->whereRaw("date(tbl_mibl_creatives.created_date) >= '" . $start_date . "' AND date(tbl_mibl_creatives.created_date) <= '" . $end_date . "'");
    }else
    { 
      $end_date = date('Y-m-d');
      $start_date = date("Y-m-d", strtotime("-3 years"));
      $result_Filter->whereRaw("date(tbl_mibl_creatives.created_date) >= '" . $start_date . "' AND date(tbl_mibl_creatives.created_date) <= '" . $end_date . "'");
    }
   */

  if(!empty($from_date) && !empty($to_date))
  {
  $from_date = date('Y-m', strtotime($from_date));
  $to_date = date('Y-m', strtotime($to_date));
  $fdate=explode("-",$from_date);
  $from_date1=$fdate[0]."".$fdate[1];
  $tdate=explode("-",$to_date);
  $to_date1=$tdate[0]."".$tdate[1];
  $result_Filter->whereRaw("DATE_FORMAT(tbl_mibl_creatives.date_of_posting, '%Y%m') >= '" . $from_date1 . "' AND DATE_FORMAT(tbl_mibl_creatives.date_of_posting, '%Y%m') <= '" . $to_date1 . "'");

  }
  /*else
  { 
  $to_date = date('Y-m');
  $from_date = date("Y-m", strtotime("-3 years"));
  $fdate=explode("-",$from_date);
  $from_date1=$fdate[0]."".$fdate[1];
  $tdate=explode("-",$to_date);
  $to_date1=$tdate[0]."".$tdate[1];
  $result_Filter->whereRaw("DATE_FORMAT(tbl_mibl_creatives.date_of_posting, '%Y%m') >= '" . $from_date1 . "' AND DATE_FORMAT(tbl_mibl_creatives.date_of_posting, '%Y%m') <= '" . $to_date1 . "'");
  }*/



  $totalRecordswithFilter=$result_Filter->count();
  
  
  
  
  
  // Fetch records
  $result =Creatives::orderBy($columnName,$columnSortOrder);
  
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
 /*
  if(!empty($from_date) && !empty($to_date))
    {
    $start_date = date('Y-m-d', strtotime($from_date));
    $end_date = date('Y-m-d', strtotime($to_date));
    $result->whereRaw("date(tbl_mibl_creatives.created_date) >= '" . $start_date . "' AND date(tbl_mibl_creatives.created_date) <= '" . $end_date . "'");
   }else
   { 
    $end_date = date('Y-m-d');
    $start_date = date("Y-m-d", strtotime("-3 years"));
     $result->whereRaw("date(tbl_mibl_creatives.created_date) >= '" . $start_date . "' AND date(tbl_mibl_creatives.created_date) <= '" . $end_date . "'");
   }*/
 
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
   /*else
   { 
     $to_date = date('Y-m');
     $from_date = date("Y-m", strtotime("-3 years"));
       $fdate=explode("-",$from_date);
       $from_date1=$fdate[0]."".$fdate[1];
       $tdate=explode("-",$to_date);
       $to_date1=$tdate[0]."".$tdate[1];
       $result->whereRaw("DATE_FORMAT(tbl_mibl_creatives.date_of_posting, '%Y%m') >= '" . $from_date1 . "' AND DATE_FORMAT(tbl_mibl_creatives.date_of_posting, '%Y%m') <= '" . $to_date1 . "'");
   }*/


  $result->leftJoin('tbl_mibl_master_document_type', 'tbl_mibl_master_document_type.id', '=', 'tbl_mibl_creatives.document_type_id');
  $result->leftJoin('tbl_mibl_master_department', 'tbl_mibl_master_department.id', '=', 'tbl_mibl_creatives.department_id');
  $result->leftJoin('tbl_mibl_master_vendor', 'tbl_mibl_master_vendor.id', '=', 'tbl_mibl_creatives.vendor_id');
  $result->leftJoin('tbl_mibl_master_brand', 'tbl_mibl_master_brand.id', '=', 'tbl_mibl_creatives.brand_id');
  $result->leftJoin('tbl_mibl_master_category', 'tbl_mibl_master_category.id', '=', 'tbl_mibl_creatives.category_id');
  $result->leftJoin('tbl_mibl_master_archive_category', 'tbl_mibl_master_archive_category.id', '=', 'tbl_mibl_creatives.archive_category_id');
  $result->leftJoin('tbl_mibl_master_archive_sub_category', 'tbl_mibl_master_archive_sub_category.id', '=', 'tbl_mibl_creatives.archive_sub_category_id');
  $result->leftJoin('tbl_mibl_master_department_type', 'tbl_mibl_master_department_type.id', '=', 'tbl_mibl_creatives.department_type_id');
  $result->leftJoin('tbl_mibl_master_vendor_type', 'tbl_mibl_master_vendor_type.id', '=', 'tbl_mibl_creatives.vendor_type_id');
  $result->select('tbl_mibl_creatives.*','tbl_mibl_master_archive_category.name as archive_name','tbl_mibl_master_category.name as category_name',
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
        $status="<span style='color:green'>Active</span>";
       }else{
        $status="<span style='color:red'>Inactive</span>";
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
        $APP_URL=$_ENV['APP_URL']."edit-creatives/".base64_encode($record->id);
        $img="<img src='".$_ENV['APP_URL']."assets/img/edit.png' class='img-fluid tab-img'>";
        $edit_link="<a href='".$APP_URL."'>$img</a>";  
       }
       
       if($record->file_type == 'image')
       {
        $year= date("Y", strtotime($record->date_of_posting));
        $month= date("m", strtotime($record->date_of_posting));
        $img="<img src='".$_ENV['APP_URL']."uploads/".$year."/".$month."/"."thumbnail/".$record->photo_url."' class='img-fluid tab-img'>";
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
  
  
//Manage Agreement

public function view_agreements(Request $request)
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



return view('/admin/view_agreements',
['vendor_c'=>$vendor_c]);
}




public function getagreements(Request $request){


  //custom search 
  
  $vendor_name = (!empty($_GET["vendor_id"])) ? ($_GET["vendor_id"]) : ('');
  $advertisement_id = (!empty($_GET["advertisement_id"])) ? ($_GET["advertisement_id"]) : ('');
  $archive_category_id = (!empty($_GET["archive_category_id"])) ? ($_GET["archive_category_id"]) : ('');
  $department_id = (!empty($_GET["department_id"])) ? ($_GET["department_id"]) : ('');
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
  // Total records
    $totalRecords = Agreement_detail::select('count(*) as allcount')->count();
    $totalRecordswithFilter = Agreement_detail::select('count(*) as allcount')
  ->leftJoin('tbl_mibl_master_brand', 'tbl_mibl_master_brand.id', '=', 'tbl_mibl_agreement_details.brand_id')
  ->leftJoin('tbl_mibl_master_vendor', 'tbl_mibl_master_vendor.id', '=', 'tbl_mibl_agreement_details.vendor_id')
  ->leftJoin('tbl_mibl_master_document_type', 'tbl_mibl_master_document_type.id', '=', 'tbl_mibl_agreement_details.document_type_id')
  
  ->where('tbl_mibl_agreement_details.file_name', 'like', '%' .$searchValue . '%')
  ->orWhere('tbl_mibl_master_brand.name', 'like', '%' .$searchValue . '%')
  ->orWhere('tbl_mibl_master_vendor.name', 'like', '%' .$searchValue . '%')
  ->orWhere('tbl_mibl_master_document_type.name', 'like', '%' .$searchValue . '%')
  ->orWhere('tbl_mibl_agreement_details.active_yn', 'like', '%' .$status . '%')
  ->orWhere('tbl_mibl_agreement_details.created_date', 'like', '%' .$created_dated. '%')
  ->orWhere('tbl_mibl_agreement_details.contract_start_date', 'like', '%' .$created_dated. '%')
  ->orWhere('tbl_mibl_agreement_details.contract_end_date', 'like', '%' .$created_dated. '%')
  ->count();
  
    // Fetch records
    $records = Agreement_detail::orderBy($columnName,$columnSortOrder)
      ->where('tbl_mibl_agreement_details.file_name', 'like', '%' .$searchValue . '%')
      ->orWhere('tbl_mibl_agreement_details.active_yn', 'like', '%' .$status . '%')
      ->orWhere('tbl_mibl_master_brand.name', 'like', '%' .$searchValue . '%')
      ->orWhere('tbl_mibl_master_vendor.name', 'like', '%' .$searchValue . '%')
      ->orWhere('tbl_mibl_master_vendor_type.vendor_type_name', 'like', '%' .$searchValue . '%')
      ->orWhere('tbl_mibl_master_document_type.name', 'like', '%' .$searchValue . '%')
      ->orWhere('tbl_mibl_agreement_details.created_date', 'like', '%' .$created_dated. '%')
      ->orWhere('tbl_mibl_agreement_details.contract_start_date', 'like', '%' .$created_dated. '%')
      ->orWhere('tbl_mibl_agreement_details.contract_end_date', 'like', '%' .$created_dated. '%')
      ->leftJoin('tbl_mibl_master_vendor_type', 'tbl_mibl_master_vendor_type.id', '=', 'tbl_mibl_agreement_details.vendor_type_id')
      ->leftJoin('tbl_mibl_master_document_type', 'tbl_mibl_master_document_type.id', '=', 'tbl_mibl_agreement_details.document_type_id')
      ->leftJoin('tbl_mibl_master_vendor', 'tbl_mibl_master_vendor.id', '=', 'tbl_mibl_agreement_details.vendor_id')
      ->leftJoin('tbl_mibl_master_brand', 'tbl_mibl_master_brand.id', '=', 'tbl_mibl_agreement_details.brand_id')
      ->select('tbl_mibl_agreement_details.*','tbl_mibl_master_brand.name as brand_name',
               'tbl_mibl_master_vendor.name as vendor_name',
               'tbl_mibl_master_vendor_type.vendor_type_name as vendor_type_name')
      ->skip($start)
      ->take($rowperpage)
      ->get();
  }else
  {
  
  // Total records
  $totalRecords = Agreement_detail::select('count(*) as allcount')->count();
  $result_Filter =Agreement_detail::select('count(*) as allcount');
  $result_Filter->leftJoin('tbl_mibl_master_document_type', 'tbl_mibl_master_document_type.id', '=', 'tbl_mibl_agreement_details.document_type_id');
  $result_Filter->leftJoin('tbl_mibl_master_vendor', 'tbl_mibl_master_vendor.id', '=', 'tbl_mibl_agreement_details.vendor_id');
  $result_Filter->leftJoin('tbl_mibl_master_vendor_type', 'tbl_mibl_master_vendor_type.id', '=', 'tbl_mibl_agreement_details.vendor_type_id');
  $result_Filter->leftJoin('tbl_mibl_master_brand', 'tbl_mibl_master_brand.id', '=', 'tbl_mibl_agreement_details.brand_id');
  if (!empty($vendor_name)) {
    $arr_2=explode(",",$vendor_name);
    $vendor_type_id=$arr_2[0];
    $vendor_id=$arr_2[1];
    $result_Filter->where('tbl_mibl_master_vendor_type.id','=',$vendor_type_id);
    if($vendor_id != 0){
    $result_Filter->where('tbl_mibl_master_vendor.id','=',$vendor_id);
    }
  }
  if(!empty($from_date) && !empty($to_date))
    {
    $start_date = date('Y-m-d', strtotime($from_date));
    $end_date = date('Y-m-d', strtotime($to_date));
    $result_Filter->whereRaw("date(tbl_mibl_agreement_details.created_date) >= '" . $start_date . "' AND date(tbl_mibl_agreement_details.created_date) <= '" . $end_date . "'");
    }
  $totalRecordswithFilter=$result_Filter->count();
  
  
  
  
  
  // Fetch records
  $result =Agreement_detail::orderBy($columnName,$columnSortOrder);
  
  if (!empty($vendor_name)) {

    $arr_2=explode(",",$vendor_name);
    $vendor_type_id=$arr_2[0];
    $vendor_id=$arr_2[1];
    $result->where('tbl_mibl_master_vendor_type.id','=',$vendor_type_id);
    if($vendor_id != 0){
    $result->where('tbl_mibl_master_vendor.id','=',$vendor_id);
    }
  }
  
  if (!empty($archive_category_id)) {
    $result->where('tbl_mibl_master_archive_category.name', 'like', '%' .$archive_category_id. '%');
  }
  if (!empty($department_id)) {
    $result->where('tbl_mibl_master_department.name', 'like', '%' .$department_id. '%');
  }

  if(!empty($from_date) && !empty($to_date))
    {
    $start_date = date('Y-m-d', strtotime($from_date));
    $end_date = date('Y-m-d', strtotime($to_date));
    $result->whereRaw("date(tbl_mibl_agreement_details.created_date) >= '" . $start_date . "' AND date(tbl_mibl_agreement_details.created_date) <= '" . $end_date . "'");
   }
 
  $result->leftJoin('tbl_mibl_master_vendor_type', 'tbl_mibl_master_vendor_type.id', '=', 'tbl_mibl_agreement_details.vendor_type_id');
  $result->leftJoin('tbl_mibl_master_document_type', 'tbl_mibl_master_document_type.id', '=', 'tbl_mibl_agreement_details.document_type_id');
  $result->leftJoin('tbl_mibl_master_vendor', 'tbl_mibl_master_vendor.id', '=', 'tbl_mibl_agreement_details.vendor_id');
  $result->leftJoin('tbl_mibl_master_brand', 'tbl_mibl_master_brand.id', '=', 'tbl_mibl_agreement_details.brand_id');
  $result->select('tbl_mibl_agreement_details.*',
  'tbl_mibl_master_brand.name as brand_name','tbl_mibl_master_vendor.name as vendor_name',
  'tbl_mibl_master_document_type.name as document_type_name',
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
        $status="<span style='color:green'>Active</span>";
       }else{
        $status="<span style='color:red'>Inactive</span>";
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
        $date_of_posting= date("Y", strtotime($record->date_of_posting));
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
       
       if(!empty($record->contract_start_date))
       {
        $contract_start_date= date("d/m/Y", strtotime($record->contract_start_date));
       }else
       {
        $contract_start_date='';
       }

       if(!empty($record->contract_end_date))
       {
        $contract_end_date= date("d/m/Y", strtotime($record->contract_end_date));
       }else
       {
        $contract_end_date='';
       }

       if(!empty($record->vendor_name))
       {
       $vendor_name=$record->vendor_name;
       }else
       {
        $vendor_name=$record->vendor_type_name;
       }

       if(!empty($record->id))
       {
        $APP_URL=$_ENV['APP_URL']."edit-agreements/".base64_encode($record->id);
        $img="<img src='".$_ENV['APP_URL']."assets/img/edit.png' class='img-fluid tab-img'>";
        $edit_link="<a href='".$APP_URL."'>$img</a>";  
       }
       
       if($record->file_type == 'image')
       {
        $year= date("Y", strtotime($record->created_date));
        $month= date("m", strtotime($record->created_date));
        $img="<img src='".$_ENV['APP_URL']."uploads/".$year."/".$month."/"."thumbnail/".$record->photo_url."' class='img-fluid tab-img'>";
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
        }else if(Str::upper($image_type) == 'MP4' || Str::upper($image_type) == 'MP3')
        {
          $img="<img src='".$_ENV['APP_URL']."assets/img/video.png' class='img-fluid tab-img'>";
        }else
        {
          $year= date("Y", strtotime($record->created_date));
          $img="<img src='".$_ENV['APP_URL']."uploads/agreement_document/$year/$record->photo_url' class='img-fluid tab-img'>";
        }
        $images=$img;
      }
  
       $data_arr[] = array(
         "id" =>$i,
         "file_name" =>$record->file_name,
         "photo_url" =>$images,
         "brand_id" =>$record->brand_name,
         "vendor_id" =>$vendor_name,
         "contract_start_date" =>$contract_start_date,
         "contract_end_date" =>$contract_end_date,
         "document_type_id" =>$record->document_type_name,
         "date_of_posting" =>$date_of_posting,
         "date_of_upload" =>$date_of_upload,
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



  function add_agreements()
  {
       $aggrement_type = DB::table('tbl_mibl_master_agreement')
    ->select('*')
    ->where('active_yn',0)
    ->get();
    
    $year = DB::table('tbl_mibl_master_year')
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
  
  
    return view('/admin/add_agreement', 
    ['year_list' => $year,
     'document_type_list' => $document_type,
     'vendor_c' => $vendor_c,
     'brand_list' => $brand,
     'aggrement_list'=>$aggrement_type]);
  }
  



function insert_agreements(Request $request)
 {
  


     # create directory of Year
     $year1=date("Y");
     $year = "uploads/agreement_document/".$year1;
     $year_agreement=$year;
     # create directory if not exists in upload/ directory
     if(!is_dir($year)){
       mkdir($year, 0777);
     }
    
    # create directory of Original
    $name_upload_source_file='upload_source_file';
    $name_upload_source_file = "uploads/agreement_document/".$name_upload_source_file;
    # create directory if not exists in upload/ directory
    if(!is_dir($name_upload_source_file)){
      mkdir($name_upload_source_file, 0777);
    }



if(isset($_FILES['photo'])) 
  {


      $mime = $_FILES['photo']['type'];
      $image=$request->file('photo');
      $filename=$image->getClientOriginalName();  

      $data = DB::table('tbl_mibl_agreement_details')
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

        
        $filename_n = $year_agreement.'/'.$filename_new;
        $file_name = $_FILES["photo"]["name"];
        $file_tmp  = $_FILES["photo"]["tmp_name"];
        $arr_data['photo']=move_uploaded_file($file_tmp, env('BASE_PATH') . $filename_n);

        
        if ($request->file('source_file') != '') {
          $image     = $request->file('source_file');
          $filename1  = $image->getClientOriginalName();
          $file_name = $_FILES["source_file"]["name"];
          $file_tmp  = $_FILES["source_file"]["tmp_name"];
          $filename_n = $name_upload_source_file.'/'.$filename1;
          $arr_data['source_file']=move_uploaded_file($file_tmp, env('BASE_PATH') . $filename_n);
          }else
          {
            $filename1='';
          }

        
  } else
    {
      $filename1=''; 
      $filename_new=''; 
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
$file_description = $request->input('file_description');
$brand_id = $request->input('brand_id');
$document_type_id = $request->input('document_type_id');
// $vendor_id = $request->input('vendor_id');
$date_of_posting=date('Y-m-d');
$date_of_upload=date('Y-m-d');
$other_document_type=$request->input('other_document_type');
$vendor_type_id=$request->input('vendor_type_id');
$remark=$request->input('remark');

$aggrement_type_id=$request->input('aggrement_type_id');
$contract_start_date=$request->input('contract_start_date');
$contract_end_date=$request->input('contract_end_date');

$photo_url = $filename_new;
$source_file = $filename1;


$arr_2=explode(",",$vendor_type_id);

$vendor_type_id=$arr_2[0];
$vendor_id=$arr_2[1];

$last_id=DB::table('tbl_mibl_agreement_details')->insertGetId([
  'file_name'=>$file_name,
  'file_description'=>$file_description,
  'brand_id'=>$brand_id,
  'document_type_id'=>$document_type_id,
  'vendor_id'=>$vendor_id,
  'date_of_posting'=>$date_of_posting,
  'date_of_upload'=>$date_of_upload,
  'other_document_type'=>$other_document_type,
  'photo_url'=>$photo_url,
  'source_file'=>$source_file,
  'file_type'=>'other',
  'vendor_type_id'=>$vendor_type_id,
  'remark'=>$remark,
  'created_date'=>date('Y-m-d H:i:s'),
  'created_by'=>$username,
  'aggrement_type_id'=>$aggrement_type_id,
  'contract_start_date'=>$contract_start_date,
  'contract_end_date'=>$contract_end_date,
  ]);
  

/*Insert user activity*/

DB::table('tbl_mibl_user_activity')
->insert([
 'user_id' =>$user_id,
 'user_name'=>$username,
 'activity_group_id'=>$last_id,
 'messgage'=>'Agreement added successfully',
 'activity_type'=>'Insert',
 'activity_group'=>'Agreement',
 'created_date' => date('Y-m-d H:i:s'),
 ]); 
  session()->flash('successmsg', 'Agreement added successfully.');
  return redirect('/upload-agreement');



}



  
public function edit_agreements($id)
{

$year = DB::table('tbl_mibl_master_year')
->select('*')
->where('active_yn',0)
->get();
$aggrement_type = DB::table('tbl_mibl_master_agreement')
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

$data = DB::table('tbl_mibl_agreement_details')
->select('*')
->where('id', '=', $id)
->get();

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
 


return view('admin/edit_agreements', 
['edit_services' => $data,
'document_type_list' => $document_type,
'vendor_c' => $vendor_c,
'brand_list' => $brand,
'aggrement_list'=>$aggrement_type]);
}





public function update_agreements(Request $request)
{
  

  $id=$request->input('id');
  $year= date("Y", strtotime($request->input('date_of_posting')));
      
  $agreement_document = "uploads/agreement_document/".$year;
  $name_upload_source_file = "uploads/agreement_document/upload_source_file";

  if($request->file('photo') != '') 
  {

      $mime = $_FILES['photo']['type'];
      $image=$request->file('photo');
      $filename=$image->getClientOriginalName();  
      $id=$request->input('id');
      $data = DB::table('tbl_mibl_agreement_details')
      ->select('*')
      ->where('id',$id)
      ->first();

      $filename_new= $data->photo_url;
      $source_file_new= $data->source_file;

        $image = $request->file('photo');
        $filename=$filename_new;
        $file_name = $_FILES["photo"]["name"];
        $file_tmp  = $_FILES["photo"]["tmp_name"];
        $filename2 = $agreement_document.'/'.$filename;
        $arr_data['photo']=move_uploaded_file($file_tmp, env('BASE_PATH') . $filename2);

          $photo_url = $filename;
          $filetype = 'other';            
          DB::table('tbl_mibl_agreement_details')
          ->where('id', $id)
          ->update([
          'photo_url'=>$photo_url,
          'file_type'=>$filetype,
          ]);
        
  }


  if ($request->file('source_file') != '') {



    $id=$request->input('id');
    $data = DB::table('tbl_mibl_agreement_details')
    ->select('*')
    ->where('id',$id)
    ->first();
    $source_file_new= $data->source_file;
    
    $image     = $request->file('source_file');
    $filename1  =$source_file_new;
    $file_name = $_FILES["source_file"]["name"];
    $file_tmp  = $_FILES["source_file"]["tmp_name"];
    $filename_n = $name_upload_source_file.'/'.$filename1;
    $arr_data['source_file']=move_uploaded_file($file_tmp, env('BASE_PATH') . $filename_n);

    $source_file = $filename1;
    DB::table('tbl_mibl_agreement_details')
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
$file_description = $request->input('file_description');
$brand_id = $request->input('brand_id');
$document_type_id = $request->input('document_type_id');
// $vendor_id = $request->input('vendor_id');
$date_of_posting = $request->input('date_of_posting');
$date_of_upload=$request->input('date_of_upload');
$other_document_type=$request->input('other_document_type');
$vendor_type_id=$request->input('vendor_type_id');
$remark=$request->input('remark');
$aggrement_type_id=$request->input('aggrement_type_id');
$contract_start_date=$request->input('contract_start_date');
$contract_end_date=$request->input('contract_end_date');

$arr_2=explode(",",$vendor_type_id);

@$vendor_type_id=$arr_2[0];
@$vendor_id=$arr_2[1];


DB::table('tbl_mibl_agreement_details')
->where('id', $id)
->update([
  'file_name'=>$file_name,
  'file_description'=>$file_description,
  'brand_id'=>$brand_id,
  'document_type_id'=>$document_type_id,
  'vendor_id'=>$vendor_id,
  'date_of_posting'=>$date_of_posting,
  // 'date_of_upload'=>$date_of_upload,
  'other_document_type'=>$other_document_type,
  'vendor_type_id'=>$vendor_type_id,
  'remark'=>$remark,
  'modify_date'=>date('Y-m-d H:i:s'),
  'created_by'=>$username,
  'aggrement_type_id'=>$aggrement_type_id,
  'contract_start_date'=>$contract_start_date,
  'contract_end_date'=>$contract_end_date,
  ]);
    

  $last_id = $request->input('id');
  DB::table('tbl_mibl_user_activity')
  ->insert([
   'user_id' =>$user_id,
   'user_name'=>$username,
   'activity_group_id'=>$last_id,
   'messgage'=>'Agreement Updated successfully',
   'activity_type'=>'Updated',
   'activity_group'=>'Agreement ',
   'created_date' => date('Y-m-d H:i:s'),
   ]);
 

  session()->flash('successmsg', 'Agreement updated successfully.');
  return redirect('view-agreements');

}


function view_archive_sub_category()
{
$archive_sub_categorylist = DB::table('tbl_mibl_master_archive_category')
->select('*')
->where('active_yn',0)
->get(); 
return view('/admin/view_archive_sub_category',['archive_sub_category_list'=>$archive_sub_categorylist]);
  }
  
  public function getarchive_sub_category(Request $request){

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
      $created_dated='';
    }

    // Total records
    $totalRecords = Archive_sub_category::select('count(*) as allcount')->count();
    $totalRecordswithFilter = Archive_sub_category::select('count(*) as allcount')
                            ->leftJoin('tbl_mibl_master_archive_category', 'tbl_mibl_master_archive_sub_category.archive_category_id', '=', 'tbl_mibl_master_archive_category.id')
                            ->where('tbl_mibl_master_archive_sub_category.name', 'like', '%' .$searchValue . '%')
                            ->orWhere('tbl_mibl_master_archive_sub_category.keyword', 'like', '%' .$searchValue . '%')
                            ->orWhere('tbl_mibl_master_archive_category.name', 'like', '%' .$searchValue . '%')
                            ->orWhere('tbl_mibl_master_archive_sub_category.active_yn', 'like', '%' .$status . '%')
                            ->orWhere('tbl_mibl_master_archive_sub_category.created_date', 'like', '%' .$created_dated. '%')
                            ->count();

    // Fetch records
    $records = Archive_sub_category::orderBy($columnName,$columnSortOrder)
      ->where('tbl_mibl_master_archive_sub_category.name', 'like', '%' .$searchValue . '%')
      ->orWhere('tbl_mibl_master_archive_sub_category.keyword', 'like', '%' .$searchValue . '%')
      ->orWhere('tbl_mibl_master_archive_sub_category.active_yn', 'like', '%' .$status . '%')
      ->orWhere('tbl_mibl_master_archive_category.name', 'like', '%' .$searchValue . '%')
      ->orWhere('tbl_mibl_master_archive_sub_category.created_date', 'like', '%' .$created_dated. '%')
      ->leftJoin('tbl_mibl_master_archive_category', 'tbl_mibl_master_archive_sub_category.archive_category_id', '=', 'tbl_mibl_master_archive_category.id')
      ->select('tbl_mibl_master_archive_sub_category.*','tbl_mibl_master_archive_category.name as category_name')
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
        $created_date=date("d/m/Y", strtotime($record->created_date));
       }

       if(!empty($record->id))
       {
        $APP_URL=$_ENV['APP_URL']."edit-archive-sub-category/".base64_encode($record->id);
        $img="<img src='".$_ENV['APP_URL']."assets/img/edit.png' class='img-fluid tab-img'>";
        $edit_link="<a href='".$APP_URL."'>$img</a>";       }
       

       $data_arr[] = array(
         "id" =>$i,
         "category_name"=>$record->category_name,
         "name" =>$record->name,
         "keyword" =>$record->keyword,
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



  public function insert_archive_sub_category(Request $request)
{
  $name=$request->input('name');
  $keyword=$request->input('keyword');


  // $data = DB::table('tbl_mibl_master_archive_sub_category')
  // ->select('*')
  // ->where('name',$request->input('name'))
  // ->where('archive_category_id',$request->input('archive_category_id'))
  // ->get();


$data = DB::table('tbl_mibl_master_archive_sub_category')
->select('*')
->where(function ($query) use ($keyword,$name){
  $query->where('name',$name)
         ->orWhere('keyword',$keyword);
        })
  ->where('archive_category_id',$request->input('archive_category_id'))
  ->get();

  if(count($data)== '0'){
$name = $request->input('name');
$description = $request->input('description');
$archive_category_id=$request->input('archive_category_id');


$user_id=session('id');
$user = DB::table('tbl_mibl_user')
->select('*')
->where('deleted_at','=',0)
->where('id',$user_id)
->orderBy('id', 'desc')
->first();
$username=$user->name;

$last_id=DB::table('tbl_mibl_master_archive_sub_category')->insertGetId([
    'archive_category_id'=>$archive_category_id,
    'name' =>$name,
    'description' =>$description,
    'keyword'=>$keyword,
    'created_date' => date('Y-m-d H:i:s'),
    'created_by'=>$username
    ]);

    
/*Insert user activity*/

    DB::table('tbl_mibl_user_activity')
    ->insert([
    'user_id' =>$user_id,
    'user_name'=>$username,
    'activity_group_id'=>$last_id,
    'messgage'=>'Archive Sub Category added successfully',
    'activity_type'=>'Insert',
    'activity_group'=>'Archive Sub Category',
    'created_date' => date('Y-m-d H:i:s'),
    ]);  


    session()->flash('successmsg', 'Archive sub category added successfully.');
    return redirect('view-archive-sub-category');
  }else
  {
    session()->flash('failmsg', 'Archive sub category already exists.');
    return redirect('view-archive-sub-category');
  }
}


public function edit_archive_sub_category($id)
{

$archive_category = DB::table('tbl_mibl_master_archive_category')
->select('*')
->where('active_yn',0)
->get(); 
  
$id=base64_decode($id);    
$data = DB::table('tbl_mibl_master_archive_sub_category')
->select('*')
->where('id', '=', $id)
->get();

return view('/admin/edit_archive_sub_category', ['edit_services' => $data,'archive_category'=>$archive_category]);
}

public function update_archive_sub_category(Request $request)
{

$name = $request->input('name');
$keyword = $request->input('keyword');
$active_yn = $request->input('active_yn');
$archive_category_id=$request->input('archive_category_id');
$id = $request->input('id');


// $data = DB::table('tbl_mibl_master_archive_sub_category')
// ->select('*')
// ->where('name',$request->input('name'))
// ->where('archive_category_id',$request->input('archive_category_id'))
// ->where('id','!=',$id)
// ->get();

$data = DB::table('tbl_mibl_master_archive_sub_category')
->select('*')
->where(function ($query) use ($keyword,$name){
  $query->where('name',$name)
         ->orWhere('keyword',$keyword);
        })
  ->where('archive_category_id',$request->input('archive_category_id'))
  ->where('id','!=',$id)
  ->get();


if(count($data)== '0'){

DB::table('tbl_mibl_master_archive_sub_category')
->where('id', $id)
->update([
'name' => $name,
'archive_category_id'=>$archive_category_id,
'keyword' => $keyword,
'active_yn' => $active_yn,
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
 $last_id = $request->input('id');
 DB::table('tbl_mibl_user_activity')
 ->insert([
  'user_id' =>$user_id,
  'user_name'=>$username,
  'activity_group_id'=>$last_id,
  'messgage'=>'Archive Sub Category Updated successfully',
  'activity_type'=>'Updated',
  'activity_group'=>'Archive Sub Category',
  'created_date' => date('Y-m-d H:i:s'),
  ]);

session()->flash('successmsg', 'Archive sub category updated successfully.');
return redirect('view-archive-sub-category');
}else
{
  session()->flash('failmsg', 'Archive sub category already exists.');
  return redirect('view-archive-sub-category');
}

}



public function upload_image_get_advertisement_id_new()
{
  $path="pdf/A4 Brochure1.pdf";
 
//echo $path."[$number]";die;
  $imgExt = new Imagick();
  $imgExt->setResolution(300,300);
  $imgExt->readImage($path);
  $imgExt->writeImages('pdf/A4 Brochure1.jpg', true);

  dd("Document has been converted");
}

//user type wise Access

public function edit_user_type_access($id)
{

$id = base64_decode($id); 


$data = DB::table('tbl_mibl_user_type_access')
  ->select('*')
  ->where('user_type_id',$id)
  ->get();
if(count($data)== '0'){  

DB::table('tbl_mibl_user_type_access')->insert([
  'user_type_id' =>$id,
  ]);
}

$data = DB::table('tbl_mibl_user_type_access')
->select('*')
->where('user_type_id', '=', $id)
->get();

$data_type = DB::table('tbl_mibl_master_user_type')
->select('*')
->where('id', '=', $id)
->first();
return view('admin/edit_user_type_access', ['edit_services' => $data,'data_type'=>$data_type]);
}

public function update_user_type_access(Request $request)
{

$search_ad = $request->input('search_ad') ? 'yes' : 'no';
$advance_search = $request->input('advance_search')? 'yes' : 'no';
$download_creative = $request->input('download_creative') ? 'yes' : 'no';
$single_file_upload = $request->input('single_file_upload') ? 'yes' : 'no';
$bulk_upload_files = $request->input('bulk_upload_files') ? 'yes' : 'no'; 
$agreement = $request->input('agreement') ? 'yes' : 'no';
$approve_creatives = $request->input('approve_creatives') ? 'yes' : 'no';
$advertisement_id_list = $request->input('advertisement_id_list') ? 'yes' : 'no';

$manage_newsletter = $request->input('manage_newsletter') ? 'yes' : 'no';
$upload_newsletter = $request->input('upload_newsletter') ? 'yes' : 'no';
$notification = $request->input('notification') ? 'yes' : 'no';
$manage_report = $request->input('manage_report') ? 'yes' : 'no';
$manage_miscellaneous = $request->input('manage_miscellaneous') ? 'yes' : 'no';
$manage_adaptation = $request->input('manage_adaptation') ? 'yes' : 'no';





$id = $request->input('id');
$user_type_id=$request->input('user_type_id');

DB::table('tbl_mibl_user_type_access')
->where('id', $id)
->update([
'search_ad' => $search_ad,
'advance_search'=>$advance_search,
'download_creative' => $download_creative,
'single_file_upload' => $single_file_upload,
'bulk_upload_files' => $bulk_upload_files,
'agreement' => $agreement,
'approve_creatives'=>$approve_creatives,
'advertisement_id_list'=>$advertisement_id_list,

'manage_newsletter'=>$manage_newsletter,
'upload_newsletter'=>$upload_newsletter,
'notification'=>$notification,
'manage_report'=>$manage_report,
'manage_miscellaneous'=>$manage_miscellaneous,
'manage_adaptation'=>$manage_adaptation,


]);

DB::table('tbl_mibl_user_access')
->where('user_type_id', $user_type_id)
->update([
'search_ad' => $search_ad,
'advance_search'=>$advance_search,
'download_creative' => $download_creative,
'single_file_upload' => $single_file_upload,
'bulk_upload_files' => $bulk_upload_files,
'agreement' => $agreement,
'approve_creatives'=>$approve_creatives,
'advertisement_id_list'=>$advertisement_id_list,

'manage_newsletter'=>$manage_newsletter,
'upload_newsletter'=>$upload_newsletter,
'notification'=>$notification,
'manage_report'=>$manage_report,
'manage_miscellaneous'=>$manage_miscellaneous,
'manage_adaptation'=>$manage_adaptation,


]);

session()->flash('successmsg', 'User type access updated  successfully.');
return redirect('view-user-type');


}


//User Wise Role Management
public function edit_user_access($id)
{

$id = base64_decode($id); 


$data = DB::table('tbl_mibl_user_access')
  ->select('*')
  ->where('user_id',$id)
  ->get();
if(count($data)== '0'){  

  $data_user_type = DB::table('tbl_mibl_user')
  ->select('tbl_mibl_master_user_type.id')
  ->leftJoin('tbl_mibl_master_user_type', 'tbl_mibl_master_user_type.user_type_name', '=', 'tbl_mibl_user.user_type')
  ->where('tbl_mibl_user.id',$id)
  ->first();

DB::table('tbl_mibl_user_access')->insert([
  'user_id' =>$id,
  'user_type_id' =>$data_user_type->id,
  ]);
}

$data_user = DB::table('tbl_mibl_user')
->select('*')
->where('id', '=', $id)
->first();

$data = DB::table('tbl_mibl_user_access')
->select('*')
->where('user_id', '=', $id)
->get();



return view('admin/edit_user_access', ['edit_services' => $data,'data_user'=>$data_user]);
}


public function update_user_access(Request $request)
{

$search_ad = $request->input('search_ad') ? 'yes' : 'no';
$advance_search = $request->input('advance_search')? 'yes' : 'no';
$download_creative = $request->input('download_creative') ? 'yes' : 'no';
$single_file_upload = $request->input('single_file_upload') ? 'yes' : 'no';
$bulk_upload_files = $request->input('bulk_upload_files') ? 'yes' : 'no'; 
$agreement = $request->input('agreement') ? 'yes' : 'no';
$approve_creatives = $request->input('approve_creatives') ? 'yes' : 'no';
$advertisement_id_list = $request->input('advertisement_id_list') ? 'yes' : 'no';

$manage_newsletter = $request->input('manage_newsletter') ? 'yes' : 'no';
$upload_newsletter = $request->input('upload_newsletter') ? 'yes' : 'no';
$notification = $request->input('notification') ? 'yes' : 'no';
$manage_report = $request->input('manage_report') ? 'yes' : 'no';
$manage_miscellaneous = $request->input('manage_miscellaneous') ? 'yes' : 'no';
$manage_adaptation = $request->input('manage_adaptation') ? 'yes' : 'no';




$id = $request->input('id');

DB::table('tbl_mibl_user_access')
->where('id', $id)
->update([
'search_ad' => $search_ad,
'advance_search'=>$advance_search,
'download_creative' => $download_creative,
'single_file_upload' => $single_file_upload,
'bulk_upload_files' => $bulk_upload_files,
'agreement' => $agreement,
'approve_creatives' => $approve_creatives,
'advertisement_id_list' => $advertisement_id_list,

'manage_newsletter'=>$manage_newsletter,
'upload_newsletter'=>$upload_newsletter,
'notification'=>$notification,
'manage_report'=>$manage_report,
'manage_miscellaneous'=>$manage_miscellaneous,
'manage_adaptation'=>$manage_adaptation,


]);
session()->flash('successmsg', 'User access updated successfully.');
return redirect('view-user');


}


function cloudflareuploadvideo()
{
  
$photo='https://dyte-recordings-test.s3.ap-south-1.amazonaws.com/48fb1c4b-cc97-44e6-b9c4-d397249a3190/ybrbss-ahzxoh_1647426976289.mp4?X-Amz-Algorithm=AWS4-HMAC-SHA256&X-Amz-Credential=AKIA5YAHRKZROAVDF4QD%2F20220316%2Fap-south-1%2Fs3%2Faws4_request&X-Amz-Date=20220316T104218Z&X-Amz-Expires=604800&X-Amz-SignedHeaders=host&x-id=GetObject&X-Amz-Signature=c1e7cf8662182482b96e46e57083f6958968c9c636225bba93dcec4d6ccc6b71';
$url="https://api.cloudflare.com/client/v4/accounts/3464b2dc14b129074e9534e416f3dec3/stream";
     $curl = curl_init();
     curl_setopt_array($curl, [
         CURLOPT_URL            => $url, // tmp url provided by cloudflare
         CURLOPT_RETURNTRANSFER => 1,
         CURLOPT_TIMEOUT        => 6000,
         CURLOPT_POST           => true,
         CURLOPT_POSTFIELDS     => ['file'=>new \CURLFile($photo),'video/mp4','test_name'],
         CURLOPT_HTTPHEADER     => [
             "X-Auth-Key: 479d51bd5c51c2a55530c46106c9d1a426f88","X-Auth-Email:info@evonix.co"
         ],
     ]);
     $response = curl_exec($curl);
     curl_close($curl);
     var_dump($response);
     $response=json_decode($response);
     $result=$response->result;





    //  echo  "<pre>";
    //  echo $result->preview;
   /* sleep(10);
    //Download video Enbled
      $VIDEOID=$result->uid;
      $url="https://api.cloudflare.com/client/v4/accounts/34cc3252d5c329c1d2ac13237b4972ed/stream/$VIDEOID/downloads";
      $curl = curl_init();
      curl_setopt_array($curl, [
      CURLOPT_URL            => $url, // tmp url provided by cloudflare
      CURLOPT_RETURNTRANSFER => 1,
      CURLOPT_TIMEOUT        => 30,
      CURLOPT_POST           => true,
      CURLOPT_HTTPHEADER     => [
      "X-Auth-Key: 43b3d73c452c8f2f536964033aa59622c3b9d","X-Auth-Email:marketing.mibl@gmail.com"
      ],
      ]);
      $response = curl_exec($curl);
      curl_close($curl);*/
}





//Bluk File upload before 2019


public function add_bulk_file_upload_before(Request $request)
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
   'tbl_mibl_master_language.language as language_name'
   )
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
   ->where('tbl_mibl_creatives_bulk.flag','=','before')
   ->get();



$unverify = DB::table('tbl_mibl_creatives_bulk')
->select('tbl_mibl_creatives_bulk.*','tbl_mibl_master_archive_category.name as archive_name','tbl_mibl_master_category.name as category_name',
   'tbl_mibl_master_brand.name as brand_name','tbl_mibl_master_vendor.name as vendor_name',
   'tbl_mibl_master_department.name as department_name','tbl_mibl_master_document_type.name as document_type_name','tbl_mibl_master_department_type.department_type_name as department_type_name'
   ,'tbl_mibl_master_vendor_type.vendor_type_name as vendor_type_name',
   'tbl_mibl_master_language.language as language_name')
   ->leftJoin('tbl_mibl_master_language', 'tbl_mibl_master_language.id', '=', 'tbl_mibl_creatives_bulk.language_id')
   ->leftJoin('tbl_mibl_master_document_type', 'tbl_mibl_master_document_type.id', '=', 'tbl_mibl_creatives_bulk.document_type_id')
   ->leftJoin('tbl_mibl_master_department_type', 'tbl_mibl_master_department_type.id', '=', 'tbl_mibl_creatives_bulk.department_type_id')
   ->leftJoin('tbl_mibl_master_vendor_type', 'tbl_mibl_master_vendor_type.id', '=', 'tbl_mibl_creatives_bulk.vendor_type_id')
   ->leftJoin('tbl_mibl_master_department', 'tbl_mibl_master_department.id', '=', 'tbl_mibl_creatives_bulk.department_id')
   ->leftJoin('tbl_mibl_master_vendor', 'tbl_mibl_master_vendor.id', '=', 'tbl_mibl_creatives_bulk.vendor_id')
   ->leftJoin('tbl_mibl_master_brand', 'tbl_mibl_master_brand.id', '=', 'tbl_mibl_creatives_bulk.brand_id')
   ->leftJoin('tbl_mibl_master_category', 'tbl_mibl_master_category.id', '=', 'tbl_mibl_creatives_bulk.category_id')
   ->leftJoin('tbl_mibl_master_archive_category', 'tbl_mibl_master_archive_category.id', '=', 'tbl_mibl_creatives_bulk.archive_category_id')
   ->where('tbl_mibl_creatives_bulk.flag','=','before')
   ->whereIn('tbl_mibl_creatives_bulk.status', [1, 2, 3])
   ->get();


return view('/admin/bulk_file_upload_before',['verify' => $verify,'unverify'=>$unverify,'year_list'=>$year_list]);
  //return view('/admin/bulk_file_upload',['year_list' => $year_list]);
}





function insert_bluk_upload_before(Request $request)
{ 

  $file=$request->file('csv_file');
  $handle=fopen($request->file('csv_file'),'r');
  while (($filesop = fgetcsv($handle, 1000, ",")) !== false) {
    // count($line) is the number of columns
    $numcols = count($filesop);
  }
if($numcols == 9 ){
    $file=$request->file('csv_file');
    $j=0;
    $sk=0;
    $handle=fopen($request->file('csv_file'),'r');
    while (($filesop = fgetcsv($handle, 1000, ",")) !== false) {
      $j++;
      $source_file = $filesop[8];
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
     $img_count=count($request->file('photo'));
 
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
   $year1='2018';
   $year = "uploads/".$year1;
   # create directory if not exists in upload/ directory
   if(!is_dir($year)){
     mkdir($year, 0777);
   }
  
    # create directory of Month
    $month1='01';
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
  // $filename_source  = $image->getClientOriginalName();

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
  $filename_source=$filename_new;
  $image->move($name_upload_source_file.'/', $filename_source);
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
            $advertisement_id = $filesop[2];
						$brand = $filesop[3];
						$document_type = $filesop[4];
						
            $photo_url = $filesop[5];
						$other_document_type = $filesop[6];
						$file_type = $filesop[7];
            $source_file = $filesop[8];
            @$date_of_posting='2018-01-01';


            $brand_details =  DB::table('tbl_mibl_master_brand')->where('name', trim($brand))->first();
            @$brand_id = $brand_details->id;
            
            $document_type_details =  DB::table('tbl_mibl_master_document_type')->where('name', trim($document_type))->first();
            @$document_type_id = $document_type_details->id;

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

            
   
           //  echo $filename_new;die;
   
   
               if($file_type == 'image')
               {
               $filename="uploads/".$year1."/".$month1."/thumbnail/".$filename_new;
   
   
              if($advertisement_id == ''){
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
               
               }
               else
               {
                $filename="uploads/".$year1."/".$month1."/".$filename_new; 
                $image_arr=explode(".",$filename_new);
                $doc_type=end($image_arr);
               
              //PDF OCR Start
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
              //PDF OCR End
                
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

              //advertisement_id check
               if($status == '3')
               {
               $data = DB::table('tbl_mibl_creatives')
               ->select('*')
               ->where('advertisement_id',$advertisement_id)
               ->get();
 
               $data_bulk = DB::table('tbl_mibl_creatives_bulk')
               ->select('*')
               ->where('advertisement_id',$advertisement_id)
               ->where('status','4')
               ->get();
               if(count($data) == 0 && count($data_bulk) == 0 &&  $advertisement_id !=''){  
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
   
   
            //Create created date code

            $month_count=strlen($month1);
            if($month_count == 1)
            {
            $month2="0".$month1;
            }else
            {
              $month2=$month1;
            }
            
            $created_date=$year1."-".$month2."-".date('d')." 00:00:00";

						$insertGetId = DB::table('tbl_mibl_creatives_bulk')->insert([
							'file_name' => $file_name, 
              'advertisement_id' =>$advertisement_id, 
              'brand_id' => $brand_id, 
							'document_type_id' => $document_type_id,
							'photo_url' => $filename_new,
							'other_document_type' =>$other_document_type,
							'file_type' => $file_type,
              'source_file'=>$filename_newsource_file,
							'date_of_posting' => $date_of_posting,
              'created_date' =>date('Y-m-d H:i:s'),
              'created_by' => $username,
              'status'=>$status,
              'flag'=>'before',
						]);
          }
            $c ++;
					}


  session()->flash('successmsg', 'Files fetched successfully.');
  return redirect('add-bulk-file-upload-before');
}else
{
  session()->flash('failmsg', 'Uploaded Source file count does not match with csv file.');
  return redirect('add-bulk-file-upload-before');
}
}
else
{
  session()->flash('failmsg', 'Uploaded image count does not match with csv file.');
  return redirect('add-bulk-file-upload-before');
}
}else
{
  session()->flash('failmsg', 'Kindly select correct csv file for Bulk upload. Please refer sample csv file provided.');
  return redirect('add-bulk-file-upload-before');
}



}//main close




//incomplete data export

public function generate_csv_file_incomplete_before(Request $request)
	{
		$contents = "id,file_name,advertisement_id,brand,document_type,photo_name,other_document_type,file_type\n";
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
             'tbl_mibl_master_archive_sub_category.name as archive_sub_category',
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
       ->where('tbl_mibl_creatives_bulk.flag','=','before')
       ->whereIn('tbl_mibl_creatives_bulk.status', [1, 2, 3])
        ->get();
    if(!empty($data)){
		foreach ($data as $key) {
      $contents .= $i . ",";
			$contents .= $key->file_name . ",";
      $contents .= $key->advertisement_id . ",";
			$contents .= $key->brand_name . ",";
			$contents .= $key->document_type_name . ",";
      $contents .= $key->photo_url . ",";
      $contents .= $key->other_document_type . ",";
			$contents .= $key->file_type . "\n";
      $i++;
		}

//delete bulk entry

DB::table('tbl_mibl_creatives_bulk')
->where('tbl_mibl_creatives_bulk.flag','=','before')
->whereIn('tbl_mibl_creatives_bulk.status', [1, 2, 3])
->delete();

		$contents = strip_tags($contents);
		header("Content-Disposition: attachment; filename=creativefile" . date('d-m-Y') . ".csv");
		print $contents;



  }

}




function OCR()
{
  $vision = new VisionClient(['keyFile'=> json_decode(file_get_contents("key4.json"),true)]);
  $imagepath = fopen("uploads/temp/20210924 Emailer ~ Week 4.jpg",'r');
    $image = $vision->image($imagepath,['TEXT_DETECTION']);
    $result=$vision->annotate($image);
    // var_dump($result);
    $document = $result->fullText();
       $data = $document->text();
$pattern = "([A-Z0-9/]+[A-Za-z0-9]+[^a-z-0-9]+([\/]\/{0,2})+(\d)+)";
if(preg_match_all($pattern,  $data, $matches)) {
  //print_r($matches[0]);
  strtoupper($matches[0][0]);
}
}



//get User details

public function get_user_details(Request $request)
{

  $sap_code = $request->get('sap_code');
  $sap_code_details = DB::table('tbl_mibl_user')
  ->select('tbl_mibl_user.*')
  ->where('tbl_mibl_user.sap_code',$sap_code)
  ->first();
  echo json_encode($sap_code_details);
  exit;
}



public function copyImage(Request $request)
    {
        File::copy('uploads/PDF.png','uploads/temp/PDF_copy.png');
    }

  
  



    public function view_creatives_new(Request $request)
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


      return view('admin/view_advance_search',
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



    function bulk_upload_clear_all()
    {
      DB::table('tbl_mibl_creatives_bulk')
      ->whereNull('tbl_mibl_creatives_bulk.flag')
      ->whereIn('tbl_mibl_creatives_bulk.status', [1, 2, 3])
      ->delete();

      session()->flash('successmsg', 'Rejected data cleared successfully.');
      return redirect('add-bulk-file-upload');
    }


    function bulk_upload_before_clear_all()
    {
      DB::table('tbl_mibl_creatives_bulk')
      ->where('tbl_mibl_creatives_bulk.flag','=','before')
      ->whereIn('tbl_mibl_creatives_bulk.status', [1, 2, 3])
      ->delete();

      session()->flash('successmsg', 'Rejected data cleared successfully.');
      return redirect('add-bulk-file-upload-before');
    }

   
//get User details

public function get_vendor_details(Request $request)
{

  $vendor_code = $request->get('vendor_code');
  $vendor_code_details = DB::table('tbl_mibl_master_vendor')
  ->select('tbl_mibl_master_vendor.*')
  ->where('tbl_mibl_master_vendor.vendor_code',$vendor_code)
  ->first();
  echo json_encode($vendor_code_details);
  exit;
}







// ========================= Vendor functionlity ==========================


public function view_creative_vendor(Request $request)
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



  return view('/admin/view_creative_vendor',
  ['archive_c'=>$archive_c,
  'department_c'=>$department_c,
  'document_type_list'=>$document_type,
  'vendor_c'=>$vendor_c]);
  }





public function getcreatives_vendor(Request $request){


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
  ->where('tbl_mibl_creatives_vendor.type_of_creative', '=','normal')
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
      ->where('tbl_mibl_creatives_vendor.type_of_creative', '=','normal')
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
  $result_Filter->where('tbl_mibl_creatives_vendor.type_of_creative', '=','normal');
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

  
  $result->where('tbl_mibl_creatives_vendor.type_of_creative', '=','normal');
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
        $ids_1=base64_encode("no");
        $ids_2=base64_encode("no");
        $ids_3=base64_encode("no");
        $ids_4=base64_encode("no");
        $ids_5=base64_encode("no");
        $ids_6=base64_encode("no");
        $ids_7=base64_encode("no");
        $ids_8=base64_encode("no");
        $APP_URL=$_ENV['APP_URL']."edit-creative-vendor/".base64_encode($record->id).'/'.$ids_1.'/'.$ids_2.'/'.$ids_3.'/'.$ids_4.'/'.$ids_5.'/'.$ids_6.'/'.$ids_7.'/'.$ids_8;
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




function add_single_file_upload_vendor()
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
  
  $vendor_ids=session('id');
  $advertisement_id_list = DB::table('tbl_mibl_advertisement_id')
  ->select('*')
  ->where('flag',0)
  ->where('tbl_mibl_advertisement_id.is_delete',0)
  ->where('vendor_id',$vendor_ids)
  ->get();

  return view('/admin/add_single_file_upload_vendor', 
  ['category_list' => $category,
   'document_type_list' => $document_type,
   'brand_list' => $brand,
   'archive_c'=>$archive_c,
   'department_c'=>$department_c,
   'vendor_c'=>$vendor_c,
   'languages'=>$language,
   'advertisement_id_list'=>$advertisement_id_list]);
}




function insert_single_file_upload_vendor(Request $request)
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
$advertisement_id = $request->input('advertisement_id');
$file_description = $request->input('file_description');
$category_id =$request->input('category_id');
$brand_id = $request->input('brand_id');
// $department_id = $request->input('department_id');
$document_type_id = $request->input('document_type_id');
// $vendor_id = $request->input('vendor_id');
$date_of_posting =$date_of_posting."-01";
$date_of_upload=$request->input('date_of_upload');
$other_document_type=$request->input('other_document_type');
$archive_category_id=$request->input('archive_category_id');
$department_type_id=$request->input('department_type_id');
$vendor_type_id=$request->input('vendor_type_id');
//$language_id=$request->input('language_id');

$irdai_date=$request->input('irdai_date');
$irdai_addressed=$request->input('irdai_addressed');
$remark=$request->input('remark');
$type_id=$request->input('type_id');

$photo_url = $filename;
$source_file = $filename1;
$filetype = $filetype;
/*
$arr=explode(",",$archive_category_id);

$archive_category_id=$arr[0];
$archive_sub_category_id=$arr[1];


$arr_1=explode(",",$department_type_id);

$department_type_id=$arr_1[0];
$department_id=$arr_1[1];


$arr_2=explode(",",$vendor_type_id);

$vendor_type_id=$arr_2[0];
$vendor_id=$arr_2[1];
*/


$advertisement_details = DB::table('tbl_mibl_advertisement_id')
->select('*')
->where('advertisement_id',$advertisement_id)
->first();

$vendor_type_id=$advertisement_details->vendor_type_id;
$vendor_id=$advertisement_details->vendor_id;
$department_type_id=$advertisement_details->department_type_id;
$department_id=$advertisement_details->department_id;
$archive_category_id=$advertisement_details->archive_category_id;
$archive_sub_category_id=$advertisement_details->archive_sub_category_id;
$language_id=$advertisement_details->language_id;

$last_id=DB::table('tbl_mibl_creatives_vendor')->insertGetId([
  'file_name'=>$file_name,
  'advertisement_id'=>$advertisement_id,
  'file_description'=>$file_description,
  'category_id'=>$category_id,
  'brand_id'=>$brand_id,
  'department_id'=>$department_id,
  'document_type_id'=>$document_type_id,
  'vendor_id'=>$vendor_id,
  'date_of_posting'=>$date_of_posting,
  'date_of_upload'=>date('Y-m-d'),
  'other_document_type'=>$other_document_type,
  'photo_url'=>$photo_url,
  'source_file'=>$source_file,
  'file_type'=>$filetype,
  'archive_category_id'=>$archive_category_id,
  'archive_sub_category_id'=>$archive_sub_category_id,
  'department_type_id'=>$department_type_id,
  'vendor_type_id'=>$vendor_type_id,
  'language_id'=>$language_id,
  'irdai_date'=>$irdai_date,
  'irdai_addressed'=>$irdai_addressed,
  'remark'=>$remark,
  'type_id'=>$type_id,
  'created_date'=>date('Y-m-d H:i:s'),
  'created_by'=>$username,
  
  ]);


  /*Insert user activity*/

  DB::table('tbl_mibl_user_activity')
  ->insert([
  'user_id' =>$user_id,
  'user_name'=>$username,
  'activity_group_id'=>$last_id,
  'messgage'=>'Vendor Single creative upload added successfully',
  'activity_type'=>'Insert',
  'activity_group'=>'Vendor Single creative upload',
  'created_date' => date('Y-m-d H:i:s'),
  ]);  

  DB::table('tbl_mibl_advertisement_id')
          ->where('advertisement_id', $advertisement_id)
          ->update([
          'flag'=>1,
          ]);
                 



//====================Notification Email Code Start=============


$email_id="sawant.priyanka@mahindra.com";
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
  return redirect('/add-single-file-upload-vendor');
 }else
 {
   session()->flash('failmsg', 'Kindly upload file upto size 200MB.');
  return redirect('/add-single-file-upload-vendor');     
 }
  
  

}



    
public function edit_creative_vendor($id,$ids_1,$ids_2,$ids_3,$ids_4,$ids_5,$ids_6,$ids_7,$ids_8)
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


return view('admin/edit_creative_vendor', 
['edit_services' => $data,
'category_list' => $category,
'document_type_list' => $document_type,
'brand_list' => $brand,
'archive_c'=>$archive_c,
'department_c'=>$department_c,
'vendor_c'=>$vendor_c,
'languages'=>$languages,
'ids_1'=>$ids_1,
'ids_2'=>$ids_2,
'ids_3'=>$ids_3,
'ids_4'=>$ids_4,
'ids_5'=>$ids_5,
'ids_6'=>$ids_6,
'ids_7'=>$ids_7,
'ids_8'=>$ids_8]);
}



public function update_creative_vendor(Request $request)
{
  
    $advertisement_id=$request->input('advertisement_id');
    $id=$request->input('id');
    
    $ids_1=$request->ids_1;
    $ids_2=$request->ids_2;
    $ids_3=$request->ids_3;
    $ids_4=$request->ids_4;
    $ids_5=$request->ids_5;
    $ids_6=$request->ids_6;
    $ids_7=$request->ids_7;
    $ids_8=$request->ids_8;


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
      return redirect('edit-creative-vendor/'.base64_encode($id).'/'.$ids_1.'/'.$ids_2.'/'.$ids_3.'/'.$ids_4.'/'.$ids_5.'/'.$ids_6.'/'.$ids_7.'/'.$ids_8);     
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
// $department_id = $request->input('department_id');
$document_type_id = $request->input('document_type_id');
// $vendor_id = $request->input('vendor_id');
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


// $arr=explode(",",$archive_category_id);

// $archive_category_id=$arr[0];
// $archive_sub_category_id=$arr[1];


// $arr_1=explode(",",$department_type_id);

// $department_type_id=$arr_1[0];
// $department_id=$arr_1[1];


// $arr_2=explode(",",$vendor_type_id);

// $vendor_type_id=$arr_2[0];
// $vendor_id=$arr_2[1];

$creatives_vendor_details=DB::table('tbl_mibl_creatives_vendor')
->where('id', $id)
->first();

    $remarkold=$creatives_vendor_details->remark;
    if($remarkold != $remark){
    DB::table('tbl_mibl_creatives_vendor')
    ->where('id', $id)
    ->update([
    'commented_creative_date'=>date('Y-m-d H:i:s')
    ]);
    }
  



DB::table('tbl_mibl_creatives_vendor')
->where('id', $id)
->update([
  'file_name'=>$file_name,
  'advertisement_id'=>$advertisement_id,
  'file_description'=>$file_description,
  // 'category_id'=>$category_id,
  // 'brand_id'=>$brand_id,
  // 'department_id'=>$department_id,
  'document_type_id'=>$document_type_id,
  // 'vendor_id'=>$vendor_id,
  // 'date_of_posting'=>$date_of_posting,
  // 'date_of_upload'=>$date_of_upload,
  'other_document_type'=>$other_document_type,
  // 'archive_category_id'=>$archive_category_id,
  // 'archive_sub_category_id'=>$archive_sub_category_id,
  // 'department_type_id'=>$department_type_id,
  // 'vendor_type_id'=>$vendor_type_id,
  //'active_yn'=>$active_yn,
  // 'language_id'=>$language_id,
  'remark'=>$remark,
  // 'irdai_addressed'=>$irdai_addressed,
  // 'irdai_date'=>$irdai_date,
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
    'messgage'=>'Vendor Single creative upload Update successfully',
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

$email_id="sawant.priyanka@mahindra.com";
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
   return redirect('edit-creative-vendor/'.base64_encode($id).'/'.$ids_1.'/'.$ids_2.'/'.$ids_3.'/'.$ids_4.'/'.$ids_5.'/'.$ids_6.'/'.$ids_7.'/'.$ids_8);     
  }else
  {
  return redirect('edit-creative-vendor/'.base64_encode($id).'/'.$ids_1.'/'.$ids_2.'/'.$ids_3.'/'.$ids_4.'/'.$ids_5.'/'.$ids_6.'/'.$ids_7.'/'.$ids_8);     
  }

}





public function view_creative_approved(Request $request)
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



  return view('/admin/view_creative_approved',
  ['archive_c'=>$archive_c,
  'department_c'=>$department_c,
  'document_type_list'=>$document_type,
  'vendor_c'=>$vendor_c]);
  }



public function getcreatives_approved(Request $request){

  //custom search 
  
  $vendor_name = (!empty($_GET["vendor_id"])) ? ($_GET["vendor_id"]) : ('');
  $advertisement_id = (!empty($_GET["advertisement_id"])) ? ($_GET["advertisement_id"]) : ('');
  $archive_category_id = (!empty($_GET["archive_category_id"])) ? ($_GET["archive_category_id"]) : ('');
  $department_id = (!empty($_GET["department_id"])) ? ($_GET["department_id"]) : ('');
  $from_date = (!empty($_GET["from_date"])) ? ($_GET["from_date"]) : ('');
  $to_date = (!empty($_GET["to_date"])) ? ($_GET["to_date"]) : ('');
  
  $vendor_idd=session('id');
  
// if(!empty($from_date) && !empty($to_date)){
//     $fdate=explode("-",$from_date);
//     $from_date1=$fdate[0]."".$fdate[1];
//     $tdate=explode("-",$to_date);
//     $to_date1=$tdate[0]."".$tdate[1];
//     $result->whereRaw("DATE_FORMAT(tbl_mibl_creatives.date_of_posting, '%Y%m') >= '" . $from_date1 . "' AND DATE_FORMAT(tbl_mibl_creatives.date_of_posting, '%Y%m') <= '" . $to_date1 . "'");
//   }


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
    $totalRecords = Creatives::select('count(*) as allcount')->count();
    $totalRecordswithFilter = Creatives::select('count(*) as allcount')
  ->leftJoin('tbl_mibl_master_archive_category', 'tbl_mibl_master_archive_category.id', '=', 'tbl_mibl_creatives.archive_category_id')
  ->leftJoin('tbl_mibl_master_archive_sub_category', 'tbl_mibl_master_archive_sub_category.id', '=', 'tbl_mibl_creatives.archive_sub_category_id')
  ->leftJoin('tbl_mibl_master_category', 'tbl_mibl_master_category.id', '=', 'tbl_mibl_creatives.category_id')
  ->leftJoin('tbl_mibl_master_brand', 'tbl_mibl_master_brand.id', '=', 'tbl_mibl_creatives.brand_id')
  ->leftJoin('tbl_mibl_master_vendor', 'tbl_mibl_master_vendor.id', '=', 'tbl_mibl_creatives.vendor_id')
  ->leftJoin('tbl_mibl_master_department', 'tbl_mibl_master_department.id', '=', 'tbl_mibl_creatives.department_id')
  ->leftJoin('tbl_mibl_master_document_type', 'tbl_mibl_master_document_type.id', '=', 'tbl_mibl_creatives.document_type_id')
  ->leftJoin('tbl_mibl_master_department_type', 'tbl_mibl_master_department_type.id', '=', 'tbl_mibl_creatives.department_type_id')
  ->leftJoin('tbl_mibl_master_vendor_type', 'tbl_mibl_master_vendor_type.id', '=', 'tbl_mibl_creatives.vendor_type_id')
  ->where('tbl_mibl_master_vendor.id', '=',$vendor_idd)
  ->where('tbl_mibl_master_vendor.active_yn', '=','0')
  ->whereRaw("date(tbl_mibl_creatives.created_date) >= '" . $start_date . "' AND date(tbl_mibl_creatives.created_date) <= '" . $end_date . "'")
  ->where(function ($query) use ($searchValue,$status,$start_date,$end_date,$created_dated){
    $query->where('tbl_mibl_creatives.file_name', 'like', '%' .$searchValue . '%')
    ->orWhere('tbl_mibl_master_archive_category.name', 'like', '%' .$searchValue . '%')
    ->orWhere('tbl_mibl_master_category.name', 'like', '%' .$searchValue . '%')
    ->orWhere('tbl_mibl_master_department_type.department_type_name', 'like', '%' .$searchValue . '%')
    ->orWhere('tbl_mibl_master_archive_sub_category.name', 'like', '%' .$searchValue . '%')
    ->orWhere('tbl_mibl_master_vendor_type.vendor_type_name', 'like', '%' .$searchValue . '%')
    ->orWhere('tbl_mibl_master_brand.name', 'like', '%' .$searchValue . '%')
    ->orWhere('tbl_mibl_master_vendor.name', 'like', '%' .$searchValue . '%')
    ->orWhere('tbl_mibl_master_department.name', 'like', '%' .$searchValue . '%')
    ->orWhere('tbl_mibl_master_document_type.name', 'like', '%' .$searchValue . '%')
    ->orWhere('tbl_mibl_creatives.created_date', 'like', '%' .$created_dated . '%')
    ->orWhere('tbl_mibl_creatives.active_yn', 'like', '%' .$status . '%');
})
 /* ->where('tbl_mibl_creatives.file_name', 'like', '%' .$searchValue . '%')
  ->orWhere('tbl_mibl_master_archive_category.name', 'like', '%' .$searchValue . '%')
  ->orWhere('tbl_mibl_master_category.name', 'like', '%' .$searchValue . '%')
  ->orWhere('tbl_mibl_master_department_type.department_type_name', 'like', '%' .$searchValue . '%')
  ->orWhere('tbl_mibl_master_archive_sub_category.name', 'like', '%' .$searchValue . '%')
  ->orWhere('tbl_mibl_master_vendor_type.vendor_type_name', 'like', '%' .$searchValue . '%')
  ->orWhere('tbl_mibl_master_brand.name', 'like', '%' .$searchValue . '%')
  ->orWhere('tbl_mibl_master_vendor.name', 'like', '%' .$searchValue . '%')
  ->orWhere('tbl_mibl_master_department.name', 'like', '%' .$searchValue . '%')
  ->orWhere('tbl_mibl_master_document_type.name', 'like', '%' .$searchValue . '%')
  ->orWhere('tbl_mibl_creatives.active_yn', 'like', '%' .$status . '%')
  ->orWhere('tbl_mibl_creatives.created_date', 'like', '%' .$searchValue. '%')
  ->whereRaw("date(tbl_mibl_creatives.created_date) >= '" . $start_date . "' AND date(tbl_mibl_creatives.created_date) <= '" . $end_date . "'")
  */
  ->count();
  
    // Fetch records
    $records = Creatives::orderBy($columnName,$columnSortOrder)
     /* ->where('tbl_mibl_creatives.file_name', 'like', '%' .$searchValue . '%')
      ->orWhere('tbl_mibl_creatives.active_yn', 'like', '%' .$status . '%')
      ->orWhere('tbl_mibl_master_department_type.department_type_name', 'like', '%' .$searchValue . '%')
      ->orWhere('tbl_mibl_master_archive_sub_category.name', 'like', '%' .$searchValue . '%')
      ->orWhere('tbl_mibl_master_vendor_type.vendor_type_name', 'like', '%' .$searchValue . '%')
      ->orWhere('tbl_mibl_master_archive_category.name', 'like', '%' .$searchValue . '%')
      ->orWhere('tbl_mibl_master_category.name', 'like', '%' .$searchValue . '%')
      ->orWhere('tbl_mibl_master_brand.name', 'like', '%' .$searchValue . '%')
      ->orWhere('tbl_mibl_master_vendor.name', 'like', '%' .$searchValue . '%')
      ->orWhere('tbl_mibl_master_department.name', 'like', '%' .$searchValue . '%')
      ->orWhere('tbl_mibl_master_document_type.name', 'like', '%' .$searchValue . '%')
      ->orWhere('tbl_mibl_creatives.created_date', 'like', '%' .$searchValue. '%')
      ->whereRaw("date(tbl_mibl_creatives.created_date) >= '" . $start_date . "' AND date(tbl_mibl_creatives.created_date) <= '" . $end_date . "'")
      */
  ->where('tbl_mibl_master_vendor.id', '=',$vendor_idd)
  ->where('tbl_mibl_master_vendor.active_yn', '=','0')
  ->whereRaw("date(tbl_mibl_creatives.created_date) >= '" . $start_date . "' AND date(tbl_mibl_creatives.created_date) <= '" . $end_date . "'")
  ->where(function ($query) use ($searchValue,$status,$start_date,$end_date,$created_dated){
    $query->where('tbl_mibl_creatives.file_name', 'like', '%' .$searchValue . '%')
    ->orWhere('tbl_mibl_master_archive_category.name', 'like', '%' .$searchValue . '%')
    ->orWhere('tbl_mibl_master_category.name', 'like', '%' .$searchValue . '%')
    ->orWhere('tbl_mibl_master_department_type.department_type_name', 'like', '%' .$searchValue . '%')
    ->orWhere('tbl_mibl_master_archive_sub_category.name', 'like', '%' .$searchValue . '%')
    ->orWhere('tbl_mibl_master_vendor_type.vendor_type_name', 'like', '%' .$searchValue . '%')
    ->orWhere('tbl_mibl_master_brand.name', 'like', '%' .$searchValue . '%')
    ->orWhere('tbl_mibl_master_vendor.name', 'like', '%' .$searchValue . '%')
    ->orWhere('tbl_mibl_master_department.name', 'like', '%' .$searchValue . '%')
    ->orWhere('tbl_mibl_master_document_type.name', 'like', '%' .$searchValue . '%')
    ->orWhere('tbl_mibl_creatives.created_date', 'like', '%' .$created_dated . '%')
    ->orWhere('tbl_mibl_creatives.active_yn', 'like', '%' .$status . '%');
})
      ->leftJoin('tbl_mibl_master_document_type', 'tbl_mibl_master_document_type.id', '=', 'tbl_mibl_creatives.document_type_id')
      ->leftJoin('tbl_mibl_master_archive_sub_category', 'tbl_mibl_master_archive_sub_category.id', '=', 'tbl_mibl_creatives.archive_sub_category_id')
      ->leftJoin('tbl_mibl_master_department', 'tbl_mibl_master_department.id', '=', 'tbl_mibl_creatives.department_id')
      ->leftJoin('tbl_mibl_master_vendor', 'tbl_mibl_master_vendor.id', '=', 'tbl_mibl_creatives.vendor_id')
      ->leftJoin('tbl_mibl_master_brand', 'tbl_mibl_master_brand.id', '=', 'tbl_mibl_creatives.brand_id')
      ->leftJoin('tbl_mibl_master_category', 'tbl_mibl_master_category.id', '=', 'tbl_mibl_creatives.category_id')
      ->leftJoin('tbl_mibl_master_archive_category', 'tbl_mibl_master_archive_category.id', '=', 'tbl_mibl_creatives.archive_category_id')
      ->leftJoin('tbl_mibl_master_department_type', 'tbl_mibl_master_department_type.id', '=', 'tbl_mibl_creatives.department_type_id')
      ->leftJoin('tbl_mibl_master_vendor_type', 'tbl_mibl_master_vendor_type.id', '=', 'tbl_mibl_creatives.vendor_type_id')
      ->select('tbl_mibl_creatives.*','tbl_mibl_master_archive_category.name as archive_name','tbl_mibl_master_category.name as category_name',
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
  $totalRecords = Creatives::select('count(*) as allcount')->count();
  $result_Filter =Creatives::select('count(*) as allcount');
  $result_Filter->leftJoin('tbl_mibl_master_document_type', 'tbl_mibl_master_document_type.id', '=', 'tbl_mibl_creatives.document_type_id');
  $result_Filter->leftJoin('tbl_mibl_master_department', 'tbl_mibl_master_department.id', '=', 'tbl_mibl_creatives.department_id');
  $result_Filter->leftJoin('tbl_mibl_master_vendor', 'tbl_mibl_master_vendor.id', '=', 'tbl_mibl_creatives.vendor_id');
  $result_Filter->leftJoin('tbl_mibl_master_brand', 'tbl_mibl_master_brand.id', '=', 'tbl_mibl_creatives.brand_id');
  $result_Filter->leftJoin('tbl_mibl_master_category', 'tbl_mibl_master_category.id', '=', 'tbl_mibl_creatives.category_id');
  $result_Filter->leftJoin('tbl_mibl_master_archive_category', 'tbl_mibl_master_archive_category.id', '=', 'tbl_mibl_creatives.archive_category_id');
  $result_Filter->leftJoin('tbl_mibl_master_archive_sub_category', 'tbl_mibl_master_archive_sub_category.id', '=', 'tbl_mibl_creatives.archive_sub_category_id');
  $result_Filter->leftJoin('tbl_mibl_master_department_type', 'tbl_mibl_master_department_type.id', '=', 'tbl_mibl_creatives.department_type_id');
  $result_Filter->leftJoin('tbl_mibl_master_vendor_type', 'tbl_mibl_master_vendor_type.id', '=', 'tbl_mibl_creatives.vendor_type_id');
  $result_Filter->where('tbl_mibl_master_vendor.id', '=',$vendor_idd);

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
    $result_Filter->where('tbl_mibl_creatives.advertisement_id', 'like', '%' .$advertisement_id. '%');
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

  /*if(!empty($from_date) && !empty($to_date))
    {
    $start_date = date('Y-m-d', strtotime($from_date));
    $end_date = date('Y-m-d', strtotime($to_date));
    $result_Filter->whereRaw("date(tbl_mibl_creatives.created_date) >= '" . $start_date . "' AND date(tbl_mibl_creatives.created_date) <= '" . $end_date . "'");
    }else
    { 
      $end_date = date('Y-m-d');
      $start_date = date("Y-m-d", strtotime("-3 years"));
      $result_Filter->whereRaw("date(tbl_mibl_creatives.created_date) >= '" . $start_date . "' AND date(tbl_mibl_creatives.created_date) <= '" . $end_date . "'");
    }*/


    if(!empty($from_date) && !empty($to_date))
    {
    $from_date = date('Y-m', strtotime($from_date));
    $to_date = date('Y-m', strtotime($to_date));
    $fdate=explode("-",$from_date);
    $from_date1=$fdate[0]."".$fdate[1];
    $tdate=explode("-",$to_date);
    $to_date1=$tdate[0]."".$tdate[1];
    $result_Filter->whereRaw("DATE_FORMAT(tbl_mibl_creatives.date_of_posting, '%Y%m') >= '" . $from_date1 . "' AND DATE_FORMAT(tbl_mibl_creatives.date_of_posting, '%Y%m') <= '" . $to_date1 . "'");

    }
    /*else
    { 
    $to_date = date('Y-m');
    $from_date = date("Y-m", strtotime("-3 years"));
    $fdate=explode("-",$from_date);
    $from_date1=$fdate[0]."".$fdate[1];
    $tdate=explode("-",$to_date);
    $to_date1=$tdate[0]."".$tdate[1];
    $result_Filter->whereRaw("DATE_FORMAT(tbl_mibl_creatives.date_of_posting, '%Y%m') >= '" . $from_date1 . "' AND DATE_FORMAT(tbl_mibl_creatives.date_of_posting, '%Y%m') <= '" . $to_date1 . "'");
    }*/



  $totalRecordswithFilter=$result_Filter->count();
  
  
  
  
  
  // Fetch records
  $result =Creatives::orderBy($columnName,$columnSortOrder);
  
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

 /* if(!empty($from_date) && !empty($to_date))
    {
    $start_date = date('Y-m-d', strtotime($from_date));
    $end_date = date('Y-m-d', strtotime($to_date));
    $result->whereRaw("date(tbl_mibl_creatives.created_date) >= '" . $start_date . "' AND date(tbl_mibl_creatives.created_date) <= '" . $end_date . "'");
   }else
   { 
    $end_date = date('Y-m-d');
    $start_date = date("Y-m-d", strtotime("-3 years"));
     $result->whereRaw("date(tbl_mibl_creatives.created_date) >= '" . $start_date . "' AND date(tbl_mibl_creatives.created_date) <= '" . $end_date . "'");
   }
*/
  if(!empty($from_date) && !empty($to_date))
    {
    $from_date = date('Y-m', strtotime($from_date));
    $to_date = date('Y-m', strtotime($to_date));
    //$result_Filter->whereRaw("date(tbl_mibl_creatives.created_date) >= '" . $start_date . "' AND date(tbl_mibl_creatives.created_date) <= '" . $end_date . "'");
       $fdate=explode("-",$from_date);
        $from_date1=$fdate[0]."".$fdate[1];
        $tdate=explode("-",$to_date);
        $to_date1=$tdate[0]."".$tdate[1];
        $result->whereRaw("DATE_FORMAT(tbl_mibl_creatives.date_of_posting, '%Y%m') >= '" . $from_date1 . "' AND DATE_FORMAT(tbl_mibl_creatives.date_of_posting, '%Y%m') <= '" . $to_date1 . "'");

    }
    /*else
    { 
      $to_date = date('Y-m');
      $from_date = date("Y-m", strtotime("-3 years"));
      //$result_Filter->whereRaw("date(tbl_mibl_creatives.created_date) >= '" . $start_date . "' AND date(tbl_mibl_creatives.created_date) <= '" . $end_date . "'");
        $fdate=explode("-",$from_date);
        $from_date1=$fdate[0]."".$fdate[1];
        $tdate=explode("-",$to_date);
        $to_date1=$tdate[0]."".$tdate[1];
        $result->whereRaw("DATE_FORMAT(tbl_mibl_creatives.date_of_posting, '%Y%m') >= '" . $from_date1 . "' AND DATE_FORMAT(tbl_mibl_creatives.date_of_posting, '%Y%m') <= '" . $to_date1 . "'");
    }*/

  
 
  $result->where('tbl_mibl_master_vendor.id', '=',$vendor_idd);
  $result->leftJoin('tbl_mibl_master_document_type', 'tbl_mibl_master_document_type.id', '=', 'tbl_mibl_creatives.document_type_id');
  $result->leftJoin('tbl_mibl_master_department', 'tbl_mibl_master_department.id', '=', 'tbl_mibl_creatives.department_id');
  $result->leftJoin('tbl_mibl_master_vendor', 'tbl_mibl_master_vendor.id', '=', 'tbl_mibl_creatives.vendor_id');
  $result->leftJoin('tbl_mibl_master_brand', 'tbl_mibl_master_brand.id', '=', 'tbl_mibl_creatives.brand_id');
  $result->leftJoin('tbl_mibl_master_category', 'tbl_mibl_master_category.id', '=', 'tbl_mibl_creatives.category_id');
  $result->leftJoin('tbl_mibl_master_archive_category', 'tbl_mibl_master_archive_category.id', '=', 'tbl_mibl_creatives.archive_category_id');
  $result->leftJoin('tbl_mibl_master_archive_sub_category', 'tbl_mibl_master_archive_sub_category.id', '=', 'tbl_mibl_creatives.archive_sub_category_id');
  $result->leftJoin('tbl_mibl_master_department_type', 'tbl_mibl_master_department_type.id', '=', 'tbl_mibl_creatives.department_type_id');
  $result->leftJoin('tbl_mibl_master_vendor_type', 'tbl_mibl_master_vendor_type.id', '=', 'tbl_mibl_creatives.vendor_type_id');
  $result->select('tbl_mibl_creatives.*','tbl_mibl_master_archive_category.name as archive_name','tbl_mibl_master_category.name as category_name',
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
  
       
        $status="<span style='color:green'>Approved</span>";
      
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
        $APP_URL=$_ENV['APP_URL']."edit-creatives/".base64_encode($record->id);
        $img="<img src='".$_ENV['APP_URL']."assets/img/edit.png' class='img-fluid tab-img'>";
        $edit_link="<a href='".$APP_URL."'>$img</a>";  
       }
       
       if($record->file_type == 'image')
       {
        $year= date("Y", strtotime($record->date_of_posting));
        $month= date("m", strtotime($record->date_of_posting));
        $img="<img src='".$_ENV['APP_URL']."uploads/".$year."/".$month."/"."thumbnail/".$record->photo_url."' class='img-fluid tab-img'>";
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
        $year= date("Y", strtotime($record->date_of_posting));
        $month= date("m", strtotime($record->date_of_posting));
        $source_file=$_ENV['APP_URL']."uploads/".$year."/".$month."/upload_source_file/".$record->source_file;
       if (file_exists($source_file)) {
        $source_file_d="<a href='".$source_file."'>Download</a>";
       }else
       {
        $source_file_d='';
       }
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
    

function generate_advertisement_id()
{

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

  
  $language = DB::table('tbl_mibl_master_language')
  ->select('*')
  ->where('active_yn',0)
  ->get();

    $id=session('id');
    $result = DB::table('tbl_mibl_advertisement_id');
    $result->select('tbl_mibl_advertisement_id.*');
    $result->where('tbl_mibl_advertisement_id.flag',0);
	$result->where('tbl_mibl_advertisement_id.is_delete',0);
    $result->where('tbl_mibl_advertisement_id.vendor_id',$id);
    $result->orderBy('tbl_mibl_advertisement_id.id','DESC');
    $advertisement_id=$result->paginate(20);


  return view('/admin/generate_advertisement_id',
  ['archive_c' => $archive_c,
   'department_c'=>$department_c,
   'vendor_c'=>$vendor_c,
   'languages'=>$language,
   'advertisement_ids'=>$advertisement_id]);

}


function insert_generate_advertisement_id(Request $request)
 {
   


  $user_id=session('id');
  $user = DB::table('tbl_mibl_master_vendor')
  ->select('*')
  ->where('deleted_at','=',0)
  ->where('id',$user_id)
  ->orderBy('id', 'desc')
  ->first();
  $username=$user->name;


  $remark=$request->input('remark');
  $department_typeid=$request->input('department_type_id');
  $vendor_typeid=$request->input('vendor_type_id'); 
  $archive_categoryid=$request->input('archive_category_id');
  $language_id=$request->input('language_id');
  $type=$request->input('type');

  $arr_1=explode(",",$department_typeid);
  @$department_type_id=$arr_1[0];
  @$department_id=$arr_1[1];



  $arr_3=explode(",",$archive_categoryid);
  @$archive_category_id=$arr_3[0];
  @$archive_id=$arr_3[1];


  $data_department=DB::table('tbl_mibl_master_department')
  ->select('*')
  ->where('department_type_id',$department_type_id)
  ->where('id',$department_id)
  ->limit(1)
  ->first();
  $department_name=$data_department->name;
  $department_keyword=$data_department->keyword;

  $vendor_name=$username;
  $vendor_keyword=$user->keyword;
  $vendor_type_id=$user->vendor_type_id;
  $vendor_id=$user->id;
   
  if(!empty($archive_id))
  {
  $data_archive=DB::table('tbl_mibl_master_archive_sub_category')
  ->select('*')
  ->where('archive_category_id',$archive_category_id)
  ->where('id',$archive_id)
  ->limit(1)
  ->first();
  $archive_name=$data_archive->name;
  $archive_keyword=$data_archive->keyword;
  }else
  {
  $data_archive=DB::table('tbl_mibl_master_archive_category')
  ->select('*')
  ->where('id',$archive_category_id)
  ->limit(1)
  ->first();
  $archive_name=$data_archive->name; 
  $archive_keyword=$data_archive->keyword;
  }


  $data_language=DB::table('tbl_mibl_master_language')
  ->select('*')
  ->where('id',$language_id)
  ->limit(1)
  ->first();
  $language_name=$data_language->language;

  // $department=substr($department_name,0,3);
  // $vendor=substr($vendor_name,0,3);
  // $archive_category=substr($archive_name,0,3);
  $language=substr($language_name,0,3);

if(!empty($department_keyword))
{
$department=$department_keyword;
}else
{
  $department=substr($department_name,0,3);
}

if(!empty($vendor_keyword))
{
$vendor=$vendor_keyword;
}else
{
  $vendor=substr($vendor_name,0,3);
}

if(!empty($archive_keyword))
{
$archive_category=$archive_keyword;
}else
{
  $archive_category=substr($archive_name,0,3);
}

  
    if ( date('m') > 3 ) {
    $year = date('y') + 1;
    }
    else {
    $year = date('y');
    }


  $data_s = DB::table('tbl_mibl_advertisement_id')
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
    
    if($type == 'internal')
    {
     $type_details="IN";   
    }else
    {
     $type_details="EX";     
    }

  $advertisement_id=strtoupper('F'.$year.'/'.$department.'/'.$vendor.'/'.$archive_category.'/'.$language.'/'.$type_details."/".$serial_no);

  $data = DB::table('tbl_mibl_advertisement_id')
  ->select('*')
  ->where('advertisement_id',$advertisement_id)
  ->get();
  if(count($data) == 0){

  $last_id=DB::table('tbl_mibl_advertisement_id')->insertGetId([
  'advertisement_id'=>$advertisement_id,
  'remark'=>$remark,
  'department_type_id'=>$department_type_id,
  'department_id'=>$department_id,
  'type'=>$type,
  'vendor_type_id'=>$vendor_type_id,
  'vendor_id'=>$user_id,
  'archive_category_id'=>$archive_category_id,
  'archive_sub_category_id'=>$archive_id,
  'language_id'=>$language_id,
  'created_date'=>date('Y-m-d H:i:s'),
  'created_by'=>$username
  ]);

    /*Insert user activity*/

    DB::table('tbl_mibl_user_activity')
    ->insert([
    'user_id' =>$user_id,
    'user_name'=>$username,
    'activity_group_id'=>$last_id,
    'messgage'=>'Generate Advertisement Id successfully',
    'activity_type'=>'Insert',
    'activity_group'=>'Generate Advertisement Id',
    'created_date' => date('Y-m-d H:i:s'),
    ]);  
  
  
  session()->flash('successmsg','Advertisement id : '.$advertisement_id.'<br>Advertisement id generated successfully.');
  return redirect('/generate-advertisement-id');
}else
{
  session()->flash('failmsg', 'Advertisement id already exists.');
  return redirect('/generate-advertisement-id');
}


}


// Employee approved creative




public function view_creative_vendor_approve(Request $request)
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



  return view('/admin/view_creative_vendor_approve',
  ['archive_c'=>$archive_c,
  'department_c'=>$department_c,
  'document_type_list'=>$document_type,
  'vendor_c'=>$vendor_c]);
  }




public function getcreatives_vendor_approve(Request $request){


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
    
    if(Str::upper(@$searchValue1) == 'PENDING')
    {
    $status='0';
    }
    // else if(Str::upper(@$searchValue1) == 'INACTIVE')
    // {
    //  $status='1';
    // }
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
  // ->where('tbl_mibl_master_vendor.id', '=',$vendor_idd)
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
      // ->where('tbl_mibl_master_vendor.id', '=',$vendor_idd)
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
  // $result_Filter->where('tbl_mibl_master_vendor.id', '=',$vendor_idd);
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

  
 
  // $result->where('tbl_mibl_master_vendor.id', '=',$vendor_idd);
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
        $status="<span style='color:red'>Pending</span>";
       }else{
        // $status="<span style='color:red'>Inactive</span>";
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
        $ids_1=base64_encode("no");
        $ids_2=base64_encode("no");
        $ids_3=base64_encode("no");
        $ids_4=base64_encode("no");
        $ids_5=base64_encode("no");
        $ids_6=base64_encode("no");
        $ids_7=base64_encode("no");
        $ids_8=base64_encode("no");
        
        $APP_URL=$_ENV['APP_URL']."edit-creative-vendor/".base64_encode($record->id).'/'.$ids_1.'/'.$ids_2.'/'.$ids_3.'/'.$ids_4.'/'.$ids_5.'/'.$ids_6.'/'.$ids_7.'/'.$ids_8;
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
        $year= date("Y", strtotime($record->date_of_posting));
        $month= date("m", strtotime($record->date_of_posting));
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
  

    // print_r($data_arr);
    $response = array(
       "draw" => intval($draw),
       "iTotalRecords" => $totalRecords,
       "iTotalDisplayRecords" => $totalRecordswithFilter,
       "aaData" => $data_arr
    );
  
    echo json_encode($response);
    exit;
  }





public function insert_creative_main(Request $request)
{


  $id =$request->get('id');
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

    $data = DB::table('tbl_mibl_creatives')
    ->select('*')
    ->where('advertisement_id',$advertisement_id)
    ->get();
  
    $data_bulk = DB::table('tbl_mibl_creatives_bulk')
    ->select('*')
    ->where('advertisement_id',$advertisement_id)
    ->where('status','4')
    ->get();
    if(count($data) == 0 && count($data_bulk) == 0){
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
           
           $img1=$_ENV['APP_URL']."".$filename_ne;
           if (file_exists($img1)){
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
            $VIDEOID=$bulk_list->video_url;
            $filename_new=$bulk_list->photo_url;  
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






$last_id = DB::table('tbl_mibl_creatives')->insertGetId([
      'file_name'=>$bulk_list->file_name,
      'advertisement_id'=>$bulk_list->advertisement_id,
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
      'type_id'=>$bulk_list->type_id,
      'remark'=>$bulk_list->remark,
      'created_date'=>date('Y-m-d H:i:s'),
      'created_by'=>$username,
      'video_url'=>$VIDEOID,
      'type_of_creative'=>$bulk_list->type_of_creative
      ]); 






      

    /*Insert user activity*/

    DB::table('tbl_mibl_user_activity')
    ->insert([
    'user_id' =>$user_id,
    'user_name'=>$username,
    'activity_group_id'=>$last_id,
    'messgage'=>'Approve creative successfully',
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

//=============== Notification Email Code End =============






  

      DB::table('tbl_mibl_advertisement_id')
      ->where('advertisement_id',$advertisement_id)
      ->update([
      'flag'=>2,
      ]);
             

      //delete bulk entry

       DB::table('tbl_mibl_creatives_vendor')
        ->where('id' , $id)
        ->delete();
        
        if(!empty($email_id))
        {
        return response()->json(['success'=>'200']);
        }else
        {
          return response()->json(['success'=>'201']);
        }
    }else
    {
      return response()->json(['success'=>'300']);
    }
}



public function get_advertisement_id_details(Request $request)
{

  $advertisement_id = $request->get('advertisement_id');
  $creatives = DB::table('tbl_mibl_advertisement_id')
  ->select('tbl_mibl_master_archive_category.name as archive_name',
          'tbl_mibl_master_archive_sub_category.name as archive_sub_name',
          'tbl_mibl_master_vendor_type.vendor_type_name as vendor_type_name',
          'tbl_mibl_master_vendor.name as vendor_name',
          'tbl_mibl_master_department_type.department_type_name as department_type_name',
          'tbl_mibl_master_department.name as department_name',
          'tbl_mibl_master_language.language as language',
          'tbl_mibl_advertisement_id.type as type')
  ->leftJoin('tbl_mibl_master_archive_category', 'tbl_mibl_master_archive_category.id', '=', 'tbl_mibl_advertisement_id.archive_category_id')
  ->leftJoin('tbl_mibl_master_archive_sub_category', 'tbl_mibl_master_archive_sub_category.id', '=', 'tbl_mibl_advertisement_id.archive_sub_category_id')
  ->leftJoin('tbl_mibl_master_vendor_type', 'tbl_mibl_master_vendor_type.id', '=', 'tbl_mibl_advertisement_id.vendor_type_id')
  ->leftJoin('tbl_mibl_master_vendor', 'tbl_mibl_master_vendor.id', '=', 'tbl_mibl_advertisement_id.vendor_id')
  ->leftJoin('tbl_mibl_master_department_type', 'tbl_mibl_master_department_type.id', '=', 'tbl_mibl_advertisement_id.department_type_id')
  ->leftJoin('tbl_mibl_master_department', 'tbl_mibl_master_department.id', '=', 'tbl_mibl_advertisement_id.department_id')
  ->leftJoin('tbl_mibl_master_language', 'tbl_mibl_master_language.id', '=', 'tbl_mibl_advertisement_id.language_id')
  ->where('tbl_mibl_advertisement_id.advertisement_id',$advertisement_id)
  ->first();
  echo json_encode($creatives);
  exit;
}



function change_password()
{
  return view('admin/vendor_change_password');
}



public function update_change_password(Request $request)
{
$password = Hash::make($request->input('password'));
$password_old = $request->input('password_old');

$id=session('id');


$vendor_list=DB::table('tbl_mibl_master_vendor')
->select('*')
->where('id',$id)
->orderBy('id', 'desc')
->first();
$hash_password=$vendor_list->password;

if (password_verify($password_old, $hash_password)) {

if(!empty($request->input('password')))
{
   DB::table('tbl_mibl_master_vendor')
    ->where('id', $id)
    ->update([
    'password' =>$password
    ]);

    Auth::logout();
    $user = [];
    // Session::put('user', $user);
    Session::put('mibladmin', $user);
    return redirect('/login');
    
    session()->flash('successmsg', 'Password has been changed successfully');
    return redirect('change-password');
}

}else
{
 session()->flash('failmsg', 'Current password entered is wrong. Kindly enter correct password.');
 return redirect('change-password');   
}

}





public function advertisement_id_list(Request $request)
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



return view('/admin/advertisement_id_list',
['archive_c'=>$archive_c,
 'department_c'=>$department_c,
 'vendor_c'=>$vendor_c]);
}


public function getadvertisement_id_list(Request $request){


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




  public function get_document_data(Request $request)
{

  $extenstion = $request->get('extenstion');
  $creatives = DB::table('tbl_mibl_master_document_type')
  ->select('tbl_mibl_master_document_type.id',)
  ->where('tbl_mibl_master_document_type.name',$extenstion)
  ->first();
  echo json_encode($creatives);
  exit;
}




public function view_creatives_irdai_new(Request $request)
{
	
$vendor_id = urldecode($request->vendor_id);
$archive_category_id = urldecode($request->archive_category_id);
$department_id = urldecode($request->department_id);

$query = request()->getQueryString();

if ($query) {
    $decoded = urldecode($query);

    if ((!empty($vendor_id) && !preg_match('/^\d+(,\d+)*$/', $vendor_id)) ||
	(!empty($archive_category_id) && !preg_match('/^\d+(,\d+)*$/', $archive_category_id))  ||
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
    $vendor_id=$arr_2[1] ?? 0;
    $result->where('tbl_mibl_master_vendor_type.id', '=',$vendor_type_id);
    if($vendor_id != 0){
    $result->where('tbl_mibl_master_vendor.id', '=',$vendor_id);
    }
  }
  if (!empty($advertisement_id)) {
    $result->where('tbl_mibl_creatives.advertisement_id', 'like', '%' .$advertisement_id. '%');
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


  return view('admin/view_creatives_irdai',
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








function sendcronjob()
{
  // $data=array('name'=>'hii');
  // Mail::send('mail', $data, function($message) {
  //   $message->to('sawant.priyanka@mahindra.com', 'Tutorials Point')->subject
  //   ('Homeflic Wegrow Community Pvt Ltd');
  //   $message->from('marutikadam.evonix@gmail.com','Homeflic Wegrow');
  //   });
  $username='3I INFOTECH LTD';
  $advertisement_id='F22/CD/3IFL/BAN/ENG/026';
  $email_id="sawant.priyanka@mahindra.com";
  $employee= DB::table('tbl_mibl_user')
  ->select('*')
  ->where('deleted_at','=',0)
  ->where('email',$email_id)
  ->first();
  $employee_name=$employee->name;
  $employee_id=$employee->id;
  
  $subject="MBank: Vendor ".$username." has uploaded creative ".$advertisement_id;
  $message="Dear ".$employee_name.",
  Vendor ".$username." has uploaded creative for creative Id ".$advertisement_id." on MBank.
  
  Sincerely,
  MBank";
  $url="https://eapi.instaalerts.zone/email?uname=MIBL_ITmail&pass=bQ@8ajbv&fromName=MBank&fromEmail=info@MAHINDRAINSURANCE.COM&toEmail=".urlencode($email_id)."&subject=".urlencode($subject)."&msgPlain=".urlencode($message);
  $ch = curl_init($url);
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
  $curl_scraped_page = curl_exec($ch);
  curl_close($ch);

}



function view_notification_vendor()
{
    $user_id=session('id');
    $result = DB::table('tbl_mibl_notification');
    $result->select('tbl_mibl_notification.*');
    $result->where('vendor_id',$user_id);
    $result->whereIn('type', ['Approved','Note-Employee']);
    $result->orderBy('tbl_mibl_notification.id','DESC');
    $notification=$result->paginate(10);

 return view('admin/view_notification_vendor',['notification'=>$notification]);
}


function view_notification_message($id)
{
$id = base64_decode($id); 
$data = DB::table('tbl_mibl_notification')
->select('*')
->where('id', '=', $id)
->get();

DB::table('tbl_mibl_notification')
->where('id',$id)
->update([
'read_status'=>'1',  
'read_date' =>date('Y-m-d'),
]);

return view('admin/view_notification_message', ['view_notifiction' => $data]);
}


function view_notification_employee()
{
    $user_id=session('id');
    $result = DB::table('tbl_mibl_notification');
    $result->select('tbl_mibl_notification.*');
    $result->where('employee_id',$user_id);
    $result->whereIn('type', ['Upload','Note-Vendor']);
    $result->orderBy('tbl_mibl_notification.id','DESC');
    $notification=$result->paginate(10);

 return view('admin/view_notification_employee',['notification'=>$notification]);
}




function cronjobsendagreement()
{
$today_date=date('Y-m-d');
$agreement_details=DB::table('tbl_mibl_agreement_details')
->select('tbl_mibl_agreement_details.*','tbl_mibl_master_vendor.name as vendor_name')
->leftJoin('tbl_mibl_master_vendor', 'tbl_mibl_master_vendor.id', '=', 'tbl_mibl_agreement_details.vendor_id')
->where('tbl_mibl_agreement_details.active_yn',0)
->where('tbl_mibl_agreement_details.agreement_mail','!=','3')
->whereRaw("date(tbl_mibl_agreement_details.contract_end_date) >= '" .$today_date."'")
->orderBy('tbl_mibl_agreement_details.id','ASC')
->get();

foreach($agreement_details as $agrrements)
{

  $contract_end_date=$agrrements->contract_end_date;
  $agreement_mail=$agrrements->agreement_mail;
  $id=$agrrements->id;
  $vendor_name=$agrrements->vendor_name;
  $vendor_id=$agrrements->vendor_id;
  $today_date=date('Y-m-d');

  $date1=date_create($today_date);
  $date2=date_create($contract_end_date);
  $diff=date_diff($date1,$date2);
  $days=$diff->format("%a");

  if(($days <= 25 && $agreement_mail == '0'))
   {
    
      $email_id="sawant.priyanka@mahindra.com";
      $employee= DB::table('tbl_mibl_user')
      ->select('*')
      ->where('deleted_at','=',0)
      ->where('email',$email_id)
      ->first();
      $employee_name=$employee->name;
      $employee_id=$employee->id;


      $date=date_create($contract_end_date);
      $agreement_end_date=date_format($date,"d F Y");   
      $subject="Gentle Reminder: Agreement with of ".$vendor_name." is about to expire ".$days." Days";
      $message="    Dear ".$employee_name.",
      Agreement of ".$vendor_name." will expire on ".$agreement_end_date.".
  
      Sincerely,
      MBank";

      $url="https://eapi.instaalerts.zone/email?uname=MIBL_ITmail&pass=bQ@8ajbv&fromName=MBank&fromEmail=info@MAHINDRAINSURANCE.COM&toEmail=".urlencode($email_id)."&subject=".urlencode($subject)."&msgPlain=".urlencode($message);
      $ch = curl_init($url);
      curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
      $curl_scraped_page = curl_exec($ch);
      curl_close($ch);

      DB::table('tbl_mibl_agreement_details')
      ->where('id',$id)
      ->update([
      'agreement_mail'=>'1'
      ]);

      //Insert Noification 
      $messages=
      "Dear ".$employee_name.",<br>
      Agreement of ".$vendor_name." will expire on ".$agreement_end_date.".<br><br>

      Sincerely,<br>
      MBank";
      DB::table('tbl_mibl_agreement_remainder')
      ->insert([
      'employee_id' =>$employee_id,
      'vendor_id'=>$vendor_id,
      'subject'=>$subject,
      'message'=>$messages,
      'type'=>'Remainder',
      'send_date' =>date('Y-m-d'),
      'read_status' =>0,
      ]);  


   }

   if(($days <= 15 && $agreement_mail == '1'))
   {

      $email_id="sawant.priyanka@mahindra.com";
      $employee= DB::table('tbl_mibl_user')
      ->select('*')
      ->where('deleted_at','=',0)
      ->where('email',$email_id)
      ->first();
      $employee_name=$employee->name;
      $employee_id=$employee->id;


      $date=date_create($contract_end_date);
      $agreement_end_date=date_format($date,"d F Y");   
      $subject="Gentle Reminder: Agreement with of ".$vendor_name." is about to expire ".$days." Days";
      $message="    Dear ".$employee_name.",
      Agreement of ".$vendor_name." will expire on ".$agreement_end_date.".
  
      Sincerely,
      MBank";

      $url="https://eapi.instaalerts.zone/email?uname=MIBL_ITmail&pass=bQ@8ajbv&fromName=MBank&fromEmail=info@MAHINDRAINSURANCE.COM&toEmail=".urlencode($email_id)."&subject=".urlencode($subject)."&msgPlain=".urlencode($message);
      $ch = curl_init($url);
      curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
      $curl_scraped_page = curl_exec($ch);
      curl_close($ch);

      DB::table('tbl_mibl_agreement_details')
      ->where('id',$id)
      ->update([
      'agreement_mail'=>'2'
      ]);

      //Insert Noification 
      $messages=
      "Dear ".$employee_name.",<br>
      Agreement of ".$vendor_name." will expire on ".$agreement_end_date.".<br><br>

      Sincerely,<br>
      MBank";
      DB::table('tbl_mibl_agreement_remainder')
      ->insert([
      'employee_id' =>$employee_id,
      'vendor_id'=>$vendor_id,
      'subject'=>$subject,
      'message'=>$messages,
      'type'=>'Remainder',
      'send_date' =>date('Y-m-d'),
      'read_status' =>0,
      ]);  

   }

   if(($days <= 5 && $agreement_mail == '2'))
   {

    $email_id="sawant.priyanka@mahindra.com";
    $employee= DB::table('tbl_mibl_user')
    ->select('*')
    ->where('deleted_at','=',0)
    ->where('email',$email_id)
    ->first();
    $employee_name=$employee->name;
    $employee_id=$employee->id;


    $date=date_create($contract_end_date);
    $agreement_end_date=date_format($date,"d F Y");   
    $subject="Gentle Reminder: Agreement with of ".$vendor_name." is about to expire ".$days." Days";
    $message="    Dear ".$employee_name.",
    Agreement of ".$vendor_name." will expire on ".$agreement_end_date.".

    Sincerely,
    MBank";

    $url="https://eapi.instaalerts.zone/email?uname=MIBL_ITmail&pass=bQ@8ajbv&fromName=MBank&fromEmail=info@MAHINDRAINSURANCE.COM&toEmail=".urlencode($email_id)."&subject=".urlencode($subject)."&msgPlain=".urlencode($message);
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $curl_scraped_page = curl_exec($ch);
    curl_close($ch);

    DB::table('tbl_mibl_agreement_details')
    ->where('id',$id)
    ->update([
    'agreement_mail'=>'3'
    ]);

    //Insert Noification 
    $messages=
    "Dear ".$employee_name.",<br>
    Agreement of ".$vendor_name." will expire on ".$agreement_end_date.".<br><br>

    Sincerely,<br>
    MBank";
    DB::table('tbl_mibl_agreement_remainder')
    ->insert([
    'employee_id' =>$employee_id,
    'vendor_id'=>$vendor_id,
    'subject'=>$subject,
    'message'=>$messages,
    'type'=>'Remainder',
    'send_date' =>date('Y-m-d'),
    'read_status' =>0,
    ]);

   }

  }
}










public function view_creatives_approved_new(Request $request)
{


//Archive category type and Archive sub category 
$vendor_idd=session('id');

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
  $result->where('tbl_mibl_creatives.vendor_id',$vendor_idd);
  if (!empty($vendor_name)) {
    $arr_2=explode(",",$vendor_name);
    $vendor_type_id=$arr_2[0];
    $vendor_id=$arr_2[1];
    $result->where('tbl_mibl_master_vendor_type.id', '=',$vendor_type_id);
    if($vendor_id != 0){
    $result->where('tbl_mibl_master_vendor.id', '=',$vendor_id);
    }
  }
  if (!empty($type_of_creative)) {
    $result->where('tbl_mibl_creatives.type_of_creative','=',$type_of_creative);
  }
  
  if (!empty($advertisement_id)) {
    $result->where('tbl_mibl_creatives.advertisement_id', 'like', '%' .$advertisement_id. '%');
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


  return view('admin/view_creative_approved_new',
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
  'type_of_creative'=>$type_of_creative]);

} 



public function manage_reports(Request $request)
    {
    
    $from_date = (!empty($_GET["from_date"])) ? ($_GET["from_date"]) : ('');
    $to_date = (!empty($_GET["to_date"])) ? ($_GET["to_date"]) : ('');
    $vendor_id =  (!empty($_GET["vendor_id"])) ? ($_GET["vendor_id"]) : ('');
    //Archive category type and Archive sub category 
    
    $from_date_13=$from_date;
    $to_date_13=$to_date;
    
   //print_r($vendor_id);

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


$vendor_details_list = DB::table('tbl_mibl_master_vendor')
   ->where('active_yn',0)
   ->where('flag',1)
   ->orderBy('name', 'ASC')
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



    $from_date = date('Y-m', strtotime($from_date));
    $to_date = date('Y-m', strtotime($to_date));
    $fdate=explode("-",$from_date);
    $from_date_12=$fdate[0]."".$fdate[1];
    $tdate=explode("-",$to_date);
    $to_date_12=$tdate[0]."".$tdate[1];
    
    $type_of_creative=" AND type_of_creative='normal' ";   
 
 $report_s=array();
 $monthsYearsList=array();
  if(!empty($from_date) && !empty($to_date) && !empty($vendor_id))
    {
        
     
        
    // Define the start and end dates
    $startDate = new DateTime($from_date);
    $endDate = new DateTime($to_date);
    
    // Create an array to store the list of months and years
    $monthsYearsList = [];
    
    // Loop through the dates and add each month and year to the list
    while ($startDate <= $endDate) {
    $monthsYearsList[] = $startDate->format('Y-m-01'); // Format the date as 'Month Year'
    $startDate->modify('+1 month'); // Move to the next month
    }


// Output the list



      foreach($vendor_id as $vendorid) {

        // $arr_2=explode(",",$vendor_id);
        $vendorid=$vendorid;
        
        
        $vendor_details = DB::table('tbl_mibl_master_vendor')
        ->select('*')
        ->where('id',$vendorid)
        ->first();
        
        
        $reportsss = DB::select('select allcategory,catdetails,
            case when
                 catdetails = "cat" then catt.name  
                 else subcatt.name
                 end as name,
            count(allcategory) as total 
            from (
                select case when
                     archive_sub_category_id = 0 then archive_category_id  
                     else archive_sub_category_id
                     end as allcategory,
                case when
                     archive_sub_category_id = 0 then "cat"  
                     else "subcat"
                     end as catdetails
                from tbl_mibl_creatives
                 where vendor_id = '.$vendorid.'
              AND DATE_FORMAT(tbl_mibl_creatives.date_of_posting, "%Y%m") BETWEEN "'.$from_date_12.'" AND "'.$to_date_12.'" '.$type_of_creative.' 
            ) as mytable
        left join tbl_mibl_master_archive_category as catt 
            on  mytable.allcategory=catt.id 
        left join tbl_mibl_master_archive_sub_category as subcatt 
            on  mytable.allcategory=subcatt.id 
        group by allcategory');
        

        
        

        
foreach($reportsss as $reportss_s)
{
    
$allcategory=$reportss_s->allcategory;   
$catdetails=$reportss_s->catdetails;
$name=$reportss_s->name;
$total=$reportss_s->total;

if(!empty($allcategory)){
$month_wise=array();    
foreach ($monthsYearsList as $date) {
       
      

        $from_date = date('Y-m', strtotime($date));
        $to_date = date('Y-m', strtotime($date));
        $fdate=explode("-",$from_date);
        $from_date1=$fdate[0]."".$fdate[1];
        $tdate=explode("-",$to_date);
        $to_date1=$tdate[0]."".$tdate[1];
        $month = date('m', strtotime($date));
        $year = date('Y', strtotime($date));
       
       if($catdetails == 'subcat'){
           
        $report_final = DB::select('select allcategory,catdetails,
            case when
                 catdetails = "cat" then catt.name  
                 else subcatt.name
                 end as name,
            count(allcategory) as total 
            from (
                select case when
                     archive_sub_category_id = 0 then archive_category_id  
                     else archive_sub_category_id
                     end as allcategory,
                case when
                     archive_sub_category_id = 0 then "cat"  
                     else "subcat"
                     end as catdetails
                from tbl_mibl_creatives
                 where  vendor_id = '.$vendorid.'
              AND DATE_FORMAT(tbl_mibl_creatives.date_of_posting, "%Y%m") BETWEEN "'.$from_date1.'" AND "'.$to_date1.'"  '.$type_of_creative.' 
            ) as mytable
        left join tbl_mibl_master_archive_category as catt 
            on  mytable.allcategory=catt.id 
        left join tbl_mibl_master_archive_sub_category as subcatt 
            on  mytable.allcategory=subcatt.id  where subcatt.id = '.$allcategory.'
        group by allcategory');
       }else
       {
         $report_final = DB::select('select allcategory,catdetails,
            case when
                 catdetails = "cat" then catt.name  
                 else subcatt.name
                 end as name,
            count(allcategory) as total 
            from (
                select case when
                     archive_sub_category_id = 0 then archive_category_id  
                     else archive_sub_category_id
                     end as allcategory,
                case when
                     archive_sub_category_id = 0 then "cat"  
                     else "subcat"
                     end as catdetails
                from tbl_mibl_creatives
                 where  vendor_id = '.$vendorid.'
              AND DATE_FORMAT(tbl_mibl_creatives.date_of_posting, "%Y%m") BETWEEN "'.$from_date1.'" AND "'.$to_date1.'"  '.$type_of_creative.' 
            ) as mytable
        left join tbl_mibl_master_archive_category as catt 
            on  mytable.allcategory=catt.id 
        left join tbl_mibl_master_archive_sub_category as subcatt 
            on  mytable.allcategory=subcatt.id  where catt.id = '.$allcategory.'
        group by allcategory');  
       }
     $month_wise[]=array('month'=>$month,'year'=>$year,'data'=>$report_final);
     
     }  
 
    $report_s[]=array(
        'cate_name'=>$name,
        'vendor_name'=>$vendor_details->name,
        'month_wise_data'=>$month_wise);
 
 }
 

        
}  

}
// echo "<pre>";
// print_r($report_s);
// die();

   
    $vendor_dept = DB::table('tbl_mibl_creatives')
    ->select('department_id') 
    ->whereIn('vendor_id',$vendor_id)
    ->groupBy('department_id')
    ->get();
    
    $vendor_dept_wise=array();
    foreach($vendor_dept as $v_dept)
    {
        
    $departmentdetails = DB::table('tbl_mibl_master_department')
        ->select('*')
        ->where('id',$v_dept->department_id)
        ->first(); 
     
    $vendor_dept_wise_1=array();
    foreach($vendor_id as $vendorids) {
        // for all Dept 
        
            
        $vendordetails = DB::table('tbl_mibl_master_vendor')
        ->select('*')
        ->where('deleted_at','=',0)
        ->where('id',$vendorids)
        ->orderBy('id', 'desc')
        ->first();    
        $vendor_names=str_replace("'"," ",$vendordetails->name);
        $vendor_names1=str_replace("."," ",$vendor_names);
         
                    $dept_report_1 = DB::select('select allcategory,catdetails,deptname,vendorid,
                    case when
                         catdetails = "cat" then catt.name  
                         else subcatt.name
                         end as name,
                    count(allcategory) as total 
                    from (
                        select case when
                             archive_sub_category_id = 0 then archive_category_id  
                             else archive_sub_category_id
                             end as allcategory,
                        case when
                             archive_sub_category_id = 0 then "cat"  
                             else "subcat"
                             end as catdetails,
                         tbl_mibl_master_department.name as deptname,
                         "'.$vendor_names1.'" as vendorid
                        from tbl_mibl_creatives
                        join tbl_mibl_master_department on tbl_mibl_creatives.department_id = tbl_mibl_master_department.id  
                         where 
                            vendor_id = '.$vendorids.'
                            AND tbl_mibl_creatives.department_id = "'.$v_dept->department_id.'" 
                            AND DATE_FORMAT(tbl_mibl_creatives.date_of_posting, "%Y%m") BETWEEN "'.$from_date_12.'" AND "'.$to_date_12.'" '.$type_of_creative.' 
                    ) as mytable
                left join tbl_mibl_master_archive_category as catt 
                    on  mytable.allcategory=catt.id 
                left join tbl_mibl_master_archive_sub_category as subcatt 
                    on mytable.allcategory=subcatt.id             
                group by allcategory');
            if(count($dept_report_1) > 0){    
            $vendor_dept_wise_1[]=$dept_report_1;
            }
         }
         if(count($vendor_dept_wise_1) > 0 ){
        $vendor_dept_wise[]=array(
            'department_name'=>@$departmentdetails->name,
            'department_data'=>$vendor_dept_wise_1
            );
         }
    }  
    // echo "<pre>";
    // print_r($vendor_dept_wise);die();
      
        $vendordetails = DB::table('tbl_mibl_master_vendor')
        ->select('*')
        ->where('deleted_at','=',0)
        ->whereIn('id',$vendor_id)
        ->orderBy('id', 'desc')
        ->get();
        $vendor_report_name=array();
      foreach($vendordetails as $vendordetailss){    
       $vendor_report_name[]=$vendordetailss->name; 
      }
       
     
     
    }else{
       $report=""; 
       $vendor_report_name="";  
       $vendor_dept_wise="";
    }
    
    // echo "<pre>";
    // print_r($report_s);die();
    
    //   dd($vendor_dept_wise);

if(!empty($from_date) && !empty($to_date) && !empty($vendor_id))
{    
$total_advertisement=array();
foreach($vendor_id as $vendorid) {
    
    $vendordetails = DB::table('tbl_mibl_master_vendor')
        ->select('*')
        ->where('id',$vendorid)
        ->first();
    
    $advertisement_ids = DB::table('tbl_mibl_advertisement_id')
    ->select('*')
    ->where('vendor_id',$vendorid)
	->where('tbl_mibl_advertisement_id.is_delete',0)
    ->whereBetween('created_date', [$from_date_13, $to_date_13])
    ->get();
        
    $use_advertisement = DB::table('tbl_mibl_advertisement_id')
    ->select('*')
    ->join('tbl_mibl_creatives', 'tbl_mibl_creatives.advertisement_id', '=', 'tbl_mibl_advertisement_id.advertisement_id')
    ->where('tbl_mibl_advertisement_id.vendor_id',$vendorid)
	->where('tbl_mibl_advertisement_id.is_delete',0)
    ->whereBetween('tbl_mibl_advertisement_id.created_date', [$from_date_13, $to_date_13])
    ->get();
        
        $total_advertisement[]=array(
            'vendor_name'=>$vendordetails->name,
            'total_advertisement'=>count(@$advertisement_ids),
            'use_advertisement'=>@count($use_advertisement)
            );
}
}else
{
    $total_advertisement=array();
}
 
$archive_category_ids='';
$vendor_name='';
      return view('admin/manage_reports',
      [
      'archive_category_id'=>$archive_category_ids,
      'vendor_id'=>$vendor_name,
      'archive_c'=>$archive_c, 
      'vendor_c'=>$vendor_c,
      'vendor_details_list'=>$vendor_details_list,
      'vendor_report_name'=>$vendor_report_name,
      'report'=>$report_s,
      'vendor_dept_wise'=>$vendor_dept_wise,
      'monthsYearsList'=>$monthsYearsList,
      'total_advertisement'=>$total_advertisement
       ]);
// 'dept_report' => $dept_report
} 
 






public function view_creative_vendor_approved(Request $request)
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
    
    
    if(@$_GET["from_date"] != 'no'){
    $from_date = (!empty($_GET["from_date"])) ? ($_GET["from_date"]) : ('');    
    }else
    {
    $from_date="";   
    }
    
    if(@$_GET["to_date"] != 'no'){
    $to_date = (!empty($_GET["to_date"])) ? ($_GET["to_date"]) : ('');    
    }else
    {
    $to_date="";   
    }
    
    
    if(@$_GET["vendor_id"] != 'no'){
    $vendor_name = (!empty($_GET["vendor_id"])) ? ($_GET["vendor_id"]) : ('');    
    }else
    {
    $vendor_name="";   
    }
    
    
    if(@$_GET["archive_category_id"] != 'no'){
    $archive_category_ids = (!empty($_GET["archive_category_id"])) ? ($_GET["archive_category_id"]) : ('');    
    }else
    {
    $archive_category_ids="";   
    }
    
    if(@$_GET["department_id"] != 'no'){
    $department_id = (!empty($_GET["department_id"])) ? ($_GET["department_id"]) : ('');    
    }else
    {
    $department_id="";   
    }
    
    
    if(@$_GET["advertisement_id"] != 'no'){
    $advertisement_id = (!empty($_GET["advertisement_id"])) ? ($_GET["advertisement_id"]) : ('');    
    }else
    {
    $advertisement_id="";   
    }
    
    if(@$_GET["document_id"] != 'no'){
    $document_id = (!empty($_GET["document_id"])) ? ($_GET["document_id"]) : ('');    
    }else
    {
    $document_id="";   
    }
    
    if(@$_GET["search_creative"] != 'no'){
    $search_creative = (!empty($_GET["search_creative"])) ? ($_GET["search_creative"]) : ('');    
    }else
    {
    $search_creative="";   
    }
    
    
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
    $result->where('tbl_mibl_creatives_vendor.type_of_creative', '=','normal');
    
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
    
    
    if($search_creative == 'newcreatives'){
     $result->where('tbl_mibl_creatives_vendor.remark', '=',NULL);    
     $result->orderBy('created_date','DESC');
    }
    
    elseif($search_creative == 'commentedcreatives'){
     $result->where('tbl_mibl_creatives_vendor.remark', '!=',NULL);
     $result->orderBy('commented_creative_date','DESC');
    }else
    {
     $result->orderBy('id','DESC');   
    }
    
    $details=$result->paginate(5);
    
    
    return view('admin/view_creative_vendor_approved',
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
    'search_creative'=>$search_creative]);
    
    }  
    
    



    public function manage_vendor_reports(Request $request)
    {

   $from_date = (!empty($_GET["from_date"])) ? ($_GET["from_date"]) : ('');
   $to_date = (!empty($_GET["to_date"])) ? ($_GET["to_date"]) : ('');
   $vendor_id =session('id');
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
 

  if(!empty($from_date) && !empty($to_date) && !empty($vendor_id))
    {
        // $arr_2=explode(",",$vendor_id);
        // $vendor_id=$arr_2[1];
        
        $vendor_id=$vendor_id;
        
        $from_date = date('Y-m', strtotime($from_date));
        $to_date = date('Y-m', strtotime($to_date));
        $fdate=explode("-",$from_date);
        $from_date1=$fdate[0]."".$fdate[1];
        $tdate=explode("-",$to_date);
        $to_date1=$tdate[0]."".$tdate[1];
 
        $report = DB::select('select allcategory,catdetails,
            case when
                 catdetails = "cat" then catt.name  
                 else subcatt.name
                 end as name,
            count(allcategory) as total 
            from (
                select case when
                     archive_sub_category_id = 0 then archive_category_id  
                     else archive_sub_category_id
                     end as allcategory,
                case when
                     archive_sub_category_id = 0 then "cat"  
                     else "subcat"
                     end as catdetails
                from tbl_mibl_creatives
                 where vendor_id = '.$vendor_id.'
              AND DATE_FORMAT(tbl_mibl_creatives.date_of_posting, "%Y%m") BETWEEN "'.$from_date1.'" AND "'.$to_date1.'" 
            ) as mytable
        left join tbl_mibl_master_archive_category as catt 
            on  mytable.allcategory=catt.id 
        left join tbl_mibl_master_archive_sub_category as subcatt 
            on  mytable.allcategory=subcatt.id 
        group by allcategory');
        
        // for all Dept 
        $vendor_dept = DB::table('tbl_mibl_creatives')
            ->select('department_id') 
            ->where('vendor_id',$vendor_id)
            ->groupBy('department_id')
            ->get();
        $vendor_dept_wise=array();
         foreach($vendor_dept as $v_dept)
         {
                    $dept_report_1 = DB::select('select allcategory,catdetails,deptname,
                    case when
                         catdetails = "cat" then catt.name  
                         else subcatt.name
                         end as name,
                    count(allcategory) as total 
                    from (
                        select case when
                             archive_sub_category_id = 0 then archive_category_id  
                             else archive_sub_category_id
                             end as allcategory,
                        case when
                             archive_sub_category_id = 0 then "cat"  
                             else "subcat"
                             end as catdetails,
                         tbl_mibl_master_department.name as deptname     
                        from tbl_mibl_creatives
                        join tbl_mibl_master_department on tbl_mibl_creatives.department_id = tbl_mibl_master_department.id  
                         where 
                            vendor_id = "'.$vendor_id.'"
                            AND tbl_mibl_creatives.department_id = "'.$v_dept->department_id.'" 
                            AND DATE_FORMAT(tbl_mibl_creatives.date_of_posting, "%Y%m") BETWEEN "'.$from_date1.'" AND "'.$to_date1.'"
                    ) as mytable
                left join tbl_mibl_master_archive_category as catt 
                    on  mytable.allcategory=catt.id 
                left join tbl_mibl_master_archive_sub_category as subcatt 
                    on mytable.allcategory=subcatt.id             
                group by allcategory');
         $vendor_dept_wise[]=$dept_report_1;
         }
          
        
        $user = DB::table('tbl_mibl_master_vendor')
            ->select('*')
            ->where('deleted_at','=',0)
            ->where('id',$vendor_id)
            ->orderBy('id', 'desc')
            ->first();
            $vendor_report_name=$user->name;    
    }else{
       $report=""; 
       $vendor_report_name="";  
       $vendor_dept_wise="";
    }

      //   dd($vendor_dept_wise);
 
$archive_category_ids='';
$vendor_name='';
      return view('admin/manage_vendor_reports',
      [
      'archive_category_id'=>$archive_category_ids,
      'vendor_id'=>$vendor_name,
      'archive_c'=>$archive_c, 
      'vendor_c'=>$vendor_c,
      'vendor_report_name'=>$vendor_report_name,
       'report' => $report,'vendor_dept_wise' => $vendor_dept_wise ]);
// 'dept_report' => $dept_report
    } 



    
    public function zipDownload(Request $request)
    {
        
    $ids =$request->get('ids');
    $count=count($ids);
   
    $fileName='attachments.zip';
    $image_path1 =$fileName;
    if (file_exists($image_path1)) {
    @unlink($image_path1);
    }
   
   
    $zip      = new ZipArchive;
    $fileName = 'attachments.zip';
    if($zip->open($fileName, ZipArchive::CREATE) === TRUE) {
    
    
    
    for($i=0;$i<$count;$i++)
    {
    $id=$ids[$i];
    if($id !='on'){
        
    $bulk_list = DB::table('tbl_mibl_creatives')
    ->select('*')
    ->where('id',$id)
    ->first();
    @$year= date("Y", strtotime($bulk_list->date_of_posting));
    @$month= date("m", strtotime($bulk_list->date_of_posting));
    
    $image_arr=explode(".",$bulk_list->photo_url);
    $image_type=end($image_arr);
    
    if(Str::upper($image_type) == 'PDF' || Str::upper($image_type) == 'PPT')
    {
    $imgesfile="uploads/".$year."/".$month."/".$bulk_list->photo_url;
    }else if (Str::upper($image_type) == 'MP4')
    {
    $imgesfile ="";
    }else
    {
    $imgesfile="uploads/".$year."/".$month."/"."original/".$bulk_list->photo_url;
    }
    if (Str::upper($image_type) != 'MP4'){
    $relativeName = basename($imgesfile);
    $zip->addFile($imgesfile, $relativeName);
    }
    }
    }
    $zip->close();
    }
     
     
     
    }





//New Functionality share link

public function get_share_links(Request $request)
{

$id = $request->get('id');
$sharefile=$request->get('sharefile');


$creativesss = DB::table('tbl_mibl_creatives')
->select('*')
->where('id',$id)
->where(function ($query) {
    $query->where('share_link','=',NULL)
        ->orWhere('share_link','=','');
})
->get();
if(count($creativesss) > 0 ){

$chars = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz";
$val = "";
for ($i = 0; $i < 14; $i++){
$val .= $chars[mt_rand(0, strlen($chars)-1)];
}

$chars2 = "0123456789";
$val2 = "";
for ($i = 0; $i < 6; $i++){
$val2 .= $chars2[mt_rand(0, strlen($chars2)-1)];
}        

$cr  = substr(md5(microtime()),rand(0,26),2);
$sharing_link_code =md5($val.$val2.$cr);

if($sharefile != ''){
DB::table('tbl_mibl_creatives')
->where('id',$id)
->update([
'restricted_link'=>$sharefile,
'share_link'=>$sharing_link_code
]);
}else
{
 DB::table('tbl_mibl_creatives')
->where('id',$id)
->update([
'share_link'=>$sharing_link_code
]);   
}

}else
{
if($sharefile != ''){    
 DB::table('tbl_mibl_creatives')
->where('id',$id)
->update([
'restricted_link'=>$sharefile,
]); 
}
}

$creatives = DB::table('tbl_mibl_creatives')
->select('*')
->where('id',$id)
->first();
echo json_encode($creatives);
exit;
}

function creative_sharing_file($id)
{
$data=DB::table('tbl_mibl_creatives')->where('share_link','=',$id)->first();
DB::table('tbl_mibl_sharing_file_log')->insert([
'file_token' =>$id,
'ip_address'=>request()->ip(),
'view_date' => date('Y-m-d H:i:s'),
]);

return view('sharing_file', ['data' => $data]);

}




public function insert_creative_main_share(Request $request)
{


  $id =$request->get('id');
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

    $data = DB::table('tbl_mibl_creatives')
    ->select('*')
    ->where('advertisement_id',$advertisement_id)
    ->get();
  
    $data_bulk = DB::table('tbl_mibl_creatives_bulk')
    ->select('*')
    ->where('advertisement_id',$advertisement_id)
    ->where('status','4')
    ->get();
    if(count($data) == 0 && count($data_bulk) == 0){
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
           $img1=$_ENV['APP_URL']."".$filename_ne;
           if (file_exists($img1)){
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
            $VIDEOID=$bulk_list->video_url;
            $filename_new=$bulk_list->photo_url;  
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


$chars = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz";
$val = "";
for ($i = 0; $i < 14; $i++){
$val .= $chars[mt_rand(0, strlen($chars)-1)];
}

$chars2 = "0123456789";
$val2 = "";
for ($i = 0; $i < 6; $i++){
$val2 .= $chars2[mt_rand(0, strlen($chars2)-1)];
}        

$cr  = substr(md5(microtime()),rand(0,26),2);
$sharing_link_code =md5($val.$val2.$cr);



$last_id = DB::table('tbl_mibl_creatives')->insertGetId([
      'file_name'=>$bulk_list->file_name,
      'advertisement_id'=>$bulk_list->advertisement_id,
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
      'type_id'=>$bulk_list->type_id,
      'irdai_addressed'=>$bulk_list->irdai_addressed,
      'remark'=>$bulk_list->remark,
      'created_date'=>date('Y-m-d H:i:s'),
      'created_by'=>$username,
      'video_url'=>$VIDEOID,
      'share_link'=>$sharing_link_code,
      'type_of_creative'=>$bulk_list->type_of_creative
      ]); 


    /*Insert user activity*/

    DB::table('tbl_mibl_user_activity')
    ->insert([
    'user_id' =>$user_id,
    'user_name'=>$username,
    'activity_group_id'=>$last_id,
    'messgage'=>'Approve creative successfully',
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

//=============== Notification Email Code End =============


    DB::table('tbl_mibl_advertisement_id')
    ->where('advertisement_id',$advertisement_id)
    ->update([
    'flag'=>2,
    ]);
             

      //delete bulk entry

       DB::table('tbl_mibl_creatives_vendor')
        ->where('id' , $id)
        ->delete();
        
        
          $sharing_link=$_ENV['APP_URL']."files/".$sharing_link_code;
      
        if(!empty($email_id))
        {
        return response()->json(['success'=>'200','sharing_link'=>$sharing_link]);
        }else
        {
          return response()->json(['success'=>'201','sharing_link'=>$sharing_link]);
        }
    }else
    {
      return response()->json(['success'=>'300']);
    }
}








//Download creatives save

public function download_link_save(Request $request)
{
    $id = $request->get('id');
    DB::table('tbl_mibl_sharing_file_download_log')->insert([
    'file_token' =>$id,
    'ip_address'=>request()->ip(),
    'view_date' => date('Y-m-d H:i:s'),
    ]);
    die;
}



public function files_report(Request $request)
{






$result_Filter = DB::table('tbl_mibl_creatives');
$result_Filter->select('*');
$result_Filter->where('tbl_mibl_creatives.share_link','!=',NULL);
$ord=$result_Filter->get(); 
$ordss=array();
foreach($ord as $ords)
{
    $share_link_open = DB::table('tbl_mibl_sharing_file_log')
    ->select('id')
    ->where('file_token','=',$ords->share_link)
    ->groupBy('file_token','ip_address')
    ->get(); 
    
    $download_file_link = DB::table('tbl_mibl_sharing_file_download_log')
    ->select('id')
    ->where('file_token','=',$ords->share_link)
    ->groupBy('file_token','ip_address')
    ->get();
    
    $ordss[]=array(
        "advertisement_id"=>$ords->advertisement_id,
        "file_name"=>$ords->file_name,
        "share_link_open"=>count($share_link_open),
        "download_file_link"=>count($download_file_link)
        );
}


return view('admin/view_files_report',['data'=>$ordss]);
}












public function insert_creative_main_bulk(Request $request)
{


 $idsss =$request->get('ids');
 
//  print_r($idsss);die(); 
  for($ikj=0;$ikj<count($idsss);$ikj++){
      
  $id=$idsss[$ikj];
  if($id !=  'on')
  {
      
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

    $data = DB::table('tbl_mibl_creatives')
    ->select('*')
    ->where('advertisement_id',$advertisement_id)
    ->get();
  
    $data_bulk = DB::table('tbl_mibl_creatives_bulk')
    ->select('*')
    ->where('advertisement_id',$advertisement_id)
    ->where('status','4')
    ->get();
    if(count($data) == 0 && count($data_bulk) == 0){
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






$last_id = DB::table('tbl_mibl_creatives')->insertGetId([
      'file_name'=>$bulk_list->file_name,
      'advertisement_id'=>$bulk_list->advertisement_id,
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
      'type_id'=>$bulk_list->type_id,
      'remark'=>$bulk_list->remark,
      'created_date'=>date('Y-m-d H:i:s'),
      'created_by'=>$username,
      'video_url'=>$VIDEOID,
      'type_of_creative'=>$bulk_list->type_of_creative
      ]); 






      

    //Insert user activity

    DB::table('tbl_mibl_user_activity')
    ->insert([
    'user_id' =>$user_id,
    'user_name'=>$username,
    'activity_group_id'=>$last_id,
    'messgage'=>'Approve creative successfully',
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

//=============== Notification Email Code End =============

      DB::table('tbl_mibl_advertisement_id')
      ->where('advertisement_id',$advertisement_id)
      ->update([
      'flag'=>2,
      ]);
             

      //delete bulk entry

       DB::table('tbl_mibl_creatives_vendor')
        ->where('id' , $id)
        ->delete();
   
  }
  
} 
      
      
}
    
    return response()->json(['success'=>'200']);
       
    
}










public function manage_reports_adaptation(Request $request)
    {
    
    $from_date = (!empty($_GET["from_date"])) ? ($_GET["from_date"]) : ('');
    $to_date = (!empty($_GET["to_date"])) ? ($_GET["to_date"]) : ('');
    $vendor_id =  (!empty($_GET["vendor_id"])) ? ($_GET["vendor_id"]) : ('');
    //Archive category type and Archive sub category 
    
    $from_date_13=$from_date;
    $to_date_13=$to_date;
    
   //print_r($vendor_id);

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


$vendor_details_list = DB::table('tbl_mibl_master_vendor')
   ->where('active_yn',0)
   ->where('flag',1)
   ->orderBy('name', 'ASC')
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



    $from_date = date('Y-m', strtotime($from_date));
    $to_date = date('Y-m', strtotime($to_date));
    $fdate=explode("-",$from_date);
    $from_date_12=$fdate[0]."".$fdate[1];
    $tdate=explode("-",$to_date);
    $to_date_12=$tdate[0]."".$tdate[1];
    
    $type_of_creative=" AND type_of_creative='adaptation' ";   
 
 $report_s=array();
 $monthsYearsList=array();
  if(!empty($from_date) && !empty($to_date) && !empty($vendor_id))
    {
        
     
        
    // Define the start and end dates
    $startDate = new DateTime($from_date);
    $endDate = new DateTime($to_date);
    
    // Create an array to store the list of months and years
    $monthsYearsList = [];
    
    // Loop through the dates and add each month and year to the list
    while ($startDate <= $endDate) {
    $monthsYearsList[] = $startDate->format('Y-m-01'); // Format the date as 'Month Year'
    $startDate->modify('+1 month'); // Move to the next month
    }


// Output the list



      foreach($vendor_id as $vendorid) {

        // $arr_2=explode(",",$vendor_id);
        $vendorid=$vendorid;
        
        
        $vendor_details = DB::table('tbl_mibl_master_vendor')
        ->select('*')
        ->where('id',$vendorid)
        ->first();
        
        
        $reportsss = DB::select('select allcategory,catdetails,
            case when
                 catdetails = "cat" then catt.name  
                 else subcatt.name
                 end as name,
            count(allcategory) as total 
            from (
                select case when
                     archive_sub_category_id = 0 then archive_category_id  
                     else archive_sub_category_id
                     end as allcategory,
                case when
                     archive_sub_category_id = 0 then "cat"  
                     else "subcat"
                     end as catdetails
                from tbl_mibl_creatives
                 where vendor_id = '.$vendorid.'
              AND DATE_FORMAT(tbl_mibl_creatives.date_of_posting, "%Y%m") BETWEEN "'.$from_date_12.'" AND "'.$to_date_12.'" '.$type_of_creative.' 
            ) as mytable
        left join tbl_mibl_master_archive_category as catt 
            on  mytable.allcategory=catt.id 
        left join tbl_mibl_master_archive_sub_category as subcatt 
            on  mytable.allcategory=subcatt.id 
        group by allcategory');
        

        
        

        
foreach($reportsss as $reportss_s)
{
    
$allcategory=$reportss_s->allcategory;   
$catdetails=$reportss_s->catdetails;
$name=$reportss_s->name;
$total=$reportss_s->total;

if(!empty($allcategory)){
$month_wise=array();    
foreach ($monthsYearsList as $date) {
       
      

        $from_date = date('Y-m', strtotime($date));
        $to_date = date('Y-m', strtotime($date));
        $fdate=explode("-",$from_date);
        $from_date1=$fdate[0]."".$fdate[1];
        $tdate=explode("-",$to_date);
        $to_date1=$tdate[0]."".$tdate[1];
        $month = date('m', strtotime($date));
        $year = date('Y', strtotime($date));
       
       if($catdetails == 'subcat'){
           
        $report_final = DB::select('select allcategory,catdetails,
            case when
                 catdetails = "cat" then catt.name  
                 else subcatt.name
                 end as name,
            count(allcategory) as total 
            from (
                select case when
                     archive_sub_category_id = 0 then archive_category_id  
                     else archive_sub_category_id
                     end as allcategory,
                case when
                     archive_sub_category_id = 0 then "cat"  
                     else "subcat"
                     end as catdetails
                from tbl_mibl_creatives
                 where  vendor_id = '.$vendorid.'
              AND DATE_FORMAT(tbl_mibl_creatives.date_of_posting, "%Y%m") BETWEEN "'.$from_date1.'" AND "'.$to_date1.'"  '.$type_of_creative.' 
            ) as mytable
        left join tbl_mibl_master_archive_category as catt 
            on  mytable.allcategory=catt.id 
        left join tbl_mibl_master_archive_sub_category as subcatt 
            on  mytable.allcategory=subcatt.id  where subcatt.id = '.$allcategory.'
        group by allcategory');
       }else
       {
         $report_final = DB::select('select allcategory,catdetails,
            case when
                 catdetails = "cat" then catt.name  
                 else subcatt.name
                 end as name,
            count(allcategory) as total 
            from (
                select case when
                     archive_sub_category_id = 0 then archive_category_id  
                     else archive_sub_category_id
                     end as allcategory,
                case when
                     archive_sub_category_id = 0 then "cat"  
                     else "subcat"
                     end as catdetails
                from tbl_mibl_creatives
                 where  vendor_id = '.$vendorid.'
              AND DATE_FORMAT(tbl_mibl_creatives.date_of_posting, "%Y%m") BETWEEN "'.$from_date1.'" AND "'.$to_date1.'"  '.$type_of_creative.' 
            ) as mytable
        left join tbl_mibl_master_archive_category as catt 
            on  mytable.allcategory=catt.id 
        left join tbl_mibl_master_archive_sub_category as subcatt 
            on  mytable.allcategory=subcatt.id  where catt.id = '.$allcategory.'
        group by allcategory');  
       }
     $month_wise[]=array('month'=>$month,'year'=>$year,'data'=>$report_final);
     
     }  
 
    $report_s[]=array(
        'cate_name'=>$name,
        'vendor_name'=>$vendor_details->name,
        'month_wise_data'=>$month_wise);
 
 }
 

        
}  

}
// echo "<pre>";
// print_r($report_s);
// die();

   
    $vendor_dept = DB::table('tbl_mibl_creatives')
    ->select('department_id') 
    ->whereIn('vendor_id',$vendor_id)
    ->groupBy('department_id')
    ->get();
    
    $vendor_dept_wise=array();
    foreach($vendor_dept as $v_dept)
    {
        
    $departmentdetails = DB::table('tbl_mibl_master_department')
        ->select('*')
        ->where('id',$v_dept->department_id)
        ->first(); 
     
    $vendor_dept_wise_1=array();
    foreach($vendor_id as $vendorids) {
        // for all Dept 
        
            
        $vendordetails = DB::table('tbl_mibl_master_vendor')
        ->select('*')
        ->where('deleted_at','=',0)
        ->where('id',$vendorids)
        ->orderBy('id', 'desc')
        ->first();    
        $vendor_names=str_replace("'"," ",$vendordetails->name);
        $vendor_names1=str_replace("."," ",$vendor_names);
         
                    $dept_report_1 = DB::select('select allcategory,catdetails,deptname,vendorid,
                    case when
                         catdetails = "cat" then catt.name  
                         else subcatt.name
                         end as name,
                    count(allcategory) as total 
                    from (
                        select case when
                             archive_sub_category_id = 0 then archive_category_id  
                             else archive_sub_category_id
                             end as allcategory,
                        case when
                             archive_sub_category_id = 0 then "cat"  
                             else "subcat"
                             end as catdetails,
                         tbl_mibl_master_department.name as deptname,
                         "'.$vendor_names1.'" as vendorid
                        from tbl_mibl_creatives
                        join tbl_mibl_master_department on tbl_mibl_creatives.department_id = tbl_mibl_master_department.id  
                         where 
                            vendor_id = '.$vendorids.'
                            AND tbl_mibl_creatives.department_id = "'.$v_dept->department_id.'" 
                            AND DATE_FORMAT(tbl_mibl_creatives.date_of_posting, "%Y%m") BETWEEN "'.$from_date_12.'" AND "'.$to_date_12.'" '.$type_of_creative.' 
                    ) as mytable
                left join tbl_mibl_master_archive_category as catt 
                    on  mytable.allcategory=catt.id 
                left join tbl_mibl_master_archive_sub_category as subcatt 
                    on mytable.allcategory=subcatt.id             
                group by allcategory');
            if(count($dept_report_1) > 0){    
            $vendor_dept_wise_1[]=$dept_report_1;
            }
         }
         if(count($vendor_dept_wise_1) > 0 ){
        $vendor_dept_wise[]=array(
            'department_name'=>@$departmentdetails->name,
            'department_data'=>$vendor_dept_wise_1
            );
         }
    }  
    // echo "<pre>";
    // print_r($vendor_dept_wise);die();
      
        $vendordetails = DB::table('tbl_mibl_master_vendor')
        ->select('*')
        ->where('deleted_at','=',0)
        ->whereIn('id',$vendor_id)
        ->orderBy('id', 'desc')
        ->get();
        $vendor_report_name=array();
      foreach($vendordetails as $vendordetailss){    
       $vendor_report_name[]=$vendordetailss->name; 
      }
       
     
     
    }else{
       $report=""; 
       $vendor_report_name="";  
       $vendor_dept_wise="";
    }
    
    // echo "<pre>";
    // print_r($report_s);die();
    
    //   dd($vendor_dept_wise);

if(!empty($from_date) && !empty($to_date) && !empty($vendor_id))
{    
$total_advertisement=array();
foreach($vendor_id as $vendorid) {
    
    $vendordetails = DB::table('tbl_mibl_master_vendor')
        ->select('*')
        ->where('id',$vendorid)
        ->first();
    
    $advertisement_ids = DB::table('tbl_mibl_adaptation_advertisement_id')
    ->select('*')
    ->where('vendor_id',$vendorid)
    ->whereBetween('created_date', [$from_date_13, $to_date_13])
    ->get();
        
    $use_advertisement = DB::table('tbl_mibl_adaptation_advertisement_id')
    ->select('*')
    ->join('tbl_mibl_creatives', 'tbl_mibl_creatives.advertisement_id', '=', 'tbl_mibl_adaptation_advertisement_id.advertisement_id')
    ->where('tbl_mibl_adaptation_advertisement_id.vendor_id',$vendorid)
    ->whereBetween('tbl_mibl_adaptation_advertisement_id.created_date', [$from_date_13, $to_date_13])
    ->get();
        
        $total_advertisement[]=array(
            'vendor_name'=>$vendordetails->name,
            'total_advertisement'=>count(@$advertisement_ids),
            'use_advertisement'=>@count($use_advertisement)
            );
}
}else
{
    $total_advertisement=array();
}
 
$archive_category_ids='';
$vendor_name='';
      return view('adaptation/manage_reports_adaptation',
      [
      'archive_category_id'=>$archive_category_ids,
      'vendor_id'=>$vendor_name,
      'archive_c'=>$archive_c, 
      'vendor_c'=>$vendor_c,
      'vendor_details_list'=>$vendor_details_list,
      'vendor_report_name'=>$vendor_report_name,
      'report'=>$report_s,
      'vendor_dept_wise'=>$vendor_dept_wise,
      'monthsYearsList'=>$monthsYearsList,
      'total_advertisement'=>$total_advertisement
       ]);
// 'dept_report' => $dept_report
} 



public function manage_reports_miscellaneous(Request $request)
    {
    
    $from_date = (!empty($_GET["from_date"])) ? ($_GET["from_date"]) : ('');
    $to_date = (!empty($_GET["to_date"])) ? ($_GET["to_date"]) : ('');
    $vendor_id =  (!empty($_GET["vendor_id"])) ? ($_GET["vendor_id"]) : ('');
    //Archive category type and Archive sub category 
    
    $from_date_13=$from_date;
    $to_date_13=$to_date;
    
   //print_r($vendor_id);

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


$vendor_details_list = DB::table('tbl_mibl_master_vendor')
   ->where('active_yn',0)
   ->where('flag',1)
   ->orderBy('name', 'ASC')
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

$type_of_creative=" AND type_of_creative='miscellaneous' ";   

if(!empty($from_date) && !empty($to_date))
{    
    
    $total_advertisement=array();
    $monthsYearsList=array();
    // Define the start and end dates
    $startDate = new DateTime($from_date);
    $endDate = new DateTime($to_date);
    
    // Create an array to store the list of months and years
    $monthsYearsList = [];
    
    // Loop through the dates and add each month and year to the list
    while ($startDate <= $endDate) {
    $monthsYearsList[] = $startDate->format('Y-m-01'); // Format the date as 'Month Year'
    $startDate->modify('+1 month'); // Move to the next month
    }
    
    $creatives_data = DB::table('tbl_mibl_creatives') 
    ->whereBetween('tbl_mibl_creatives.created_date', [$from_date_13, $to_date_13])
    ->where('type_of_creative','miscellaneous')
    ->groupBy('tbl_mibl_creatives.vendor_id')
    ->get();
    $vendorwise_data=array();
     foreach($vendor_id as $vendorid) {

        // $arr_2=explode(",",$vendor_id);
        $vendorid=$vendorid;
     
     $vendor_details = DB::table('tbl_mibl_master_vendor')
        ->select('*')
        ->where('id',$vendorid)
        ->first();
     
     $month_wise=array();
     foreach ($monthsYearsList as $date) {
       
        $from_date = date('Y-m', strtotime($date));
        $to_date = date('Y-m', strtotime($date));
        $fdate=explode("-",$from_date);
        $from_date1=$fdate[0]."".$fdate[1];
        $tdate=explode("-",$to_date);
        $to_date1=$tdate[0]."".$tdate[1];
        $month = date('m', strtotime($date));
        $year = date('Y', strtotime($date));
        $report_final = DB::select('select *
                from tbl_mibl_creatives
                 where  vendor_id = '.$vendorid.'
              AND DATE_FORMAT(tbl_mibl_creatives.date_of_posting, "%Y%m") BETWEEN "'.$from_date1.'" AND "'.$to_date1.'" '.$type_of_creative.' ');
        
        $month_wise[]=array('month'=>$month,'year'=>$year,'total_creatives'=>count($report_final));

     }
     
    $vendorwise_data[]=array('vendor_name'=>$vendor_details->name,
    'month_wise_data'=>$month_wise);   
    }
    
    

foreach($vendor_id as $vendorid) {

        // $arr_2=explode(",",$vendor_id);
        $vendorid=$vendorid;
    
    $vendordetails = DB::table('tbl_mibl_master_vendor')
        ->select('*')
        ->where('id',$vendorid)
        ->first();
    
    $advertisement_ids = DB::table('tbl_mibl_advertisement_id')
    ->select('*')
    ->where('vendor_id',$vendorid)
	->where('tbl_mibl_advertisement_id.is_delete',0)
    ->whereBetween('created_date', [$from_date_13, $to_date_13])
    ->get();
        
    $use_advertisement = DB::table('tbl_mibl_advertisement_id')
    ->select('*')
    ->join('tbl_mibl_creatives', 'tbl_mibl_creatives.advertisement_id', '=', 'tbl_mibl_advertisement_id.advertisement_id')
    ->where('tbl_mibl_advertisement_id.vendor_id',$vendorid)
	->where('tbl_mibl_advertisement_id.is_delete',0)
    ->whereBetween('tbl_mibl_advertisement_id.created_date', [$from_date_13, $to_date_13])
    ->get();
        
        $total_advertisement[]=array(
            'vendor_name'=>$vendordetails->name,
            'total_advertisement'=>count(@$advertisement_ids),
            'use_advertisement'=>@count($use_advertisement)
            );
}
}else
{
    $total_advertisement=array();
    // $vendor_details_list=array();
    $vendorwise_data=array();
    $monthsYearsList=array();
}
 
$archive_category_ids='';
$vendor_name='';
      return view('miscellaneous/manage_reports_miscellaneous',
      [
      'archive_category_id'=>$archive_category_ids,
      'vendor_id'=>$vendor_name,
      'archive_c'=>$archive_c, 
      'vendorwise_data'=>$vendorwise_data,
      'vendor_c'=>$vendor_c,
      'vendor_details_list'=>$vendor_details_list,
      'monthsYearsList'=>$monthsYearsList,
      'total_advertisement'=>$total_advertisement
       ]);
// 'dept_report' => $dept_report
} 





function export_data(Request $request)
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
