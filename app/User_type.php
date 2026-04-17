<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class User_type extends Model
{
   protected $table = 'tbl_mibl_master_user_type';

   protected $fillable = [
      'user_type_name','active_yn','created_date','created_by'
   ];

}