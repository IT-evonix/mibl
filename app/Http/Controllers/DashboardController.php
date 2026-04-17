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

class DashboardController extends Controller
{

public function dashboard(Request $request)
{
   

    // Get the current date and time
    $currentDateTime = Carbon::now();
    
    
        //today
        $today_approved = Creatives::where('date_of_upload',date('Y-m-d'))
        ->get();
        
        //Week
        $week_approved = Creatives::whereBetween('created_date', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()])
        ->get();
        
        //Month
        $month_approved = Creatives::whereMonth('created_date', date('m'))
        ->whereYear('created_date', date('Y'))
        ->get();
        
        //year
        $year_approved = Creatives::whereYear('created_date', date('Y'))
        ->get();

        
        //pending
        $today_pending = Creative_vendor::where('date_of_upload',date('Y-m-d'))
        ->get();
        
        $week_pending = Creative_vendor::whereBetween('created_date', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()])
        ->get();
        
        $month_pending = Creative_vendor::whereMonth('created_date', date('m'))
        ->whereYear('created_date', date('Y'))
        ->get();
        
        $year_pending = Creative_vendor::whereYear('created_date', date('Y'))
        ->get();

       
        $result = DB::table('tbl_mibl_creatives_vendor');
        $result->select('tbl_mibl_creatives_vendor.*','tbl_mibl_master_vendor.name');
        $result->join('tbl_mibl_master_vendor','tbl_mibl_master_vendor.id','=','tbl_mibl_creatives_vendor.vendor_id');
        $result->where('closed_status',1);
        $result->orderBy('tbl_mibl_creatives_vendor.id','DESC');
        $creatives_vendor_details=$result->paginate(10);



  return view('admin/dashboard',[
      'today_approved'=>$today_approved,
      'week_approved'=>$week_approved,
      'month_approved'=>$month_approved,
      'year_approved'=>$year_approved,
      
      'today_pending'=>$today_pending,
      'week_pending'=>$week_pending,
      'month_pending'=>$month_pending,
      'year_pending'=>$year_pending,
      'creatives_vendor_details'=>$creatives_vendor_details
    ]);
 
}


 public function closedcreative(Request $request)
    {
        $date=date('d-m-Y'); 
        $id=$request->id;
        DB::table('tbl_mibl_creatives_vendor')
        ->where('id', $id)
        ->update(['closed_status'=>2]);

        return redirect()->back()->with('success', 'Creatives closed successfully');
    }
    
}
    
