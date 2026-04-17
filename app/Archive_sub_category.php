<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Archive_sub_category extends Model
{
   protected $table = 'tbl_mibl_master_archive_sub_category';

   protected $fillable = [
      'archive_category_id','name','description','active_yn','created_date','created_by'
   ];

}