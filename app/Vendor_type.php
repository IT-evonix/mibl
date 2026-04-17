<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Vendor_type extends Model
{
   protected $table = 'tbl_mibl_master_vendor_type';

   protected $fillable = [
      'vendor_type_name','active_yn','created_date','created_by'
   ];

}