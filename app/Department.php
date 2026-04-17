<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Department extends Model
{
   protected $table = 'tbl_mibl_master_department';

   protected $fillable = [
      'user_type_name','active_yn','created_date','created_by'
   ];

}