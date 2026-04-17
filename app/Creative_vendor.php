<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Creative_vendor extends Model
{
   protected $table = 'tbl_mibl_creatives_vendor';

   protected $fillable = [
      'file_name','advertisement_id','file_description','category_id','brand_id','department_type_id','department_id','document_type_id','vendor_type_id',
       'archive_category','photo_url','file_type','date_of_posting','date_of_upload','other_document_type','posted_on_social_media','active_yn','created_date','created_by'
   ];

}