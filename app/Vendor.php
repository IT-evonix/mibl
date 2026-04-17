<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Vendor extends Model
{
   protected $table = 'tbl_mibl_master_vendor';

   protected $fillable = [
      'name','email','vendor_code','contact_person','contact_email','pan_no','vendor_type','mobile_no','address','active_yn','created_date','created_by'
   ];

}