<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Agreement extends Model
{
   protected $table = 'tbl_mibl_master_agreement';

   protected $fillable = [
     'name','document','year','active_yn','created_date','created_by'
   ];

}