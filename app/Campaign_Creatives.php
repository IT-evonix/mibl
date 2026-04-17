<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Campaign_Creatives extends Model
{
    protected $table = 'tbl_mibl_campaign_creatives';
    
    protected $fillable = [
    'vendor_id', 'campaign_name', 'campaign_file', 'campaign_date', 'added_by', 'added_on'
    ];

}