<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Advertisementid_model extends Model
{
   protected $table = 'tbl_mibl_advertisement_id';

   protected $fillable = [
      'advertisement_id','created_date','flag','department_type_id','department_id','vendor_type_id','vendor_id','archive_category_id','archive_sub_category_id','created_date','language_id'
   ];

}