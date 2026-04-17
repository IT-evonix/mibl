<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Department_type extends Model
{
   protected $table = 'tbl_mibl_master_department_type';

   protected $fillable = [
      'department_type_name','active_yn','created_date','created_by'
   ];

}