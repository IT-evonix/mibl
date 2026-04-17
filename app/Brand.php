<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Brand extends Model
{
   protected $table = 'tbl_mibl_master_brand';

   protected $fillable = [
      'name','dscription','active_yn','created_date','created_by'
   ];

}