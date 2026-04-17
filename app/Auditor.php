<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Auditor extends Model
{
   protected $table = 'tbl_mibl_auditor';

   protected $fillable = [
      'user_type','name','sap_code','email','last_login_date','pan_no','mobile_no','address','active_yn','created_date','created_by'
   ];

}