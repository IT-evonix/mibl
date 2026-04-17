<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Language extends Model
{
   protected $table = 'tbl_mibl_master_language';

   protected $fillable = [
      'language','active_yn','created_date','created_by'
   ];

}