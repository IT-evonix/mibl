<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
   protected $table = 'tbl_mibl_master_category';

   protected $fillable = [
      'name','description','active_yn','created_date','created_by'
   ];

}