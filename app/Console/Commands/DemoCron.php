<?php
   
namespace App\Console\Commands;
   
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
   
class DemoCron extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'demo:cron';
    
    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';
    
    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }
    
    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        \Log::info("Cron is working fine!");
     
        /*
           Write your database logic we bellow:
           Item::create(['name'=>'hello new']);
           
        */


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
    
      $email_id="maruti.kadam@evonix.co";
      $employee= DB::table('tbl_mibl_user')
      ->select('*')
      ->where('deleted_at','=',0)
      ->where('email',$email_id)
      ->first();
      $employee_name=$employee->name;
      $employee_id=$employee->id;


      $date=date_create($contract_end_date);
      $agreement_end_date=date_format($date,"d F Y");   
      $subject="Gentle Reminder: Agreement with vendor ".$vendor_name." is about to end ".$days." Days";
      $message="    Dear ".$employee_name.",
      Agreement with vendor ".$vendor_name." will expire on ".$agreement_end_date.".

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
      Agreement with vendor ".$vendor_name." will expire on ".$agreement_end_date.".<br><br>

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

      $email_id="maruti.kadam@evonix.co";
      $employee= DB::table('tbl_mibl_user')
      ->select('*')
      ->where('deleted_at','=',0)
      ->where('email',$email_id)
      ->first();
      $employee_name=$employee->name;
      $employee_id=$employee->id;


      $date=date_create($contract_end_date);
      $agreement_end_date=date_format($date,"d F Y");   
      $subject="Gentle Reminder: Agreement with vendor ".$vendor_name." is about to end ".$days." Days";
      $message="    Dear ".$employee_name.",
      Agreement with vendor ".$vendor_name." will expire on ".$agreement_end_date.".

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
      Agreement with vendor ".$vendor_name." will expire on ".$agreement_end_date.".<br><br>

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

    $email_id="maruti.kadam@evonix.co";
    $employee= DB::table('tbl_mibl_user')
    ->select('*')
    ->where('deleted_at','=',0)
    ->where('email',$email_id)
    ->first();
    $employee_name=$employee->name;
    $employee_id=$employee->id;


    $date=date_create($contract_end_date);
    $agreement_end_date=date_format($date,"d F Y");   
    $subject="Gentle Reminder: Agreement with vendor ".$vendor_name." is about to end ".$days." Days";
    $message="    Dear ".$employee_name.",
    Agreement with vendor ".$vendor_name." will expire on ".$agreement_end_date.".

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
    Agreement with vendor ".$vendor_name." will expire on ".$agreement_end_date.".<br><br>

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
}