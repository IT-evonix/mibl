<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Agreement_detail extends Model
{
   protected $table = 'tbl_mibl_agreement_details';

   protected $fillable = [
    'file_name','file_description','brand_id','document_type_id','vendor_type_id',
     'photo_url','file_type','date_of_posting','date_of_upload','other_document_type','vendor_id','active_yn','created_date','created_by'
 ];

}