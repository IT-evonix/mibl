<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/
Route::get('/clear-all', function () {
    Artisan::call('config:cache');
    Artisan::call('config:clear');
    Artisan::call('route:clear');
    Artisan::call('view:clear');

    return "Cache, route, view, config is cleared";
});

Route::get('/', function () {
    return view('admin/login');
});

//Route::get('getocrimagestext/', 'OCR_Controller@getocrimagestext');

Route::get('/getocrimagestext', 'App\Http\Controllers\OCR_Controller@getocrimagestext');

Route::get('/newsletter/{id}','App\Http\Controllers\FlipbookController@flipbook');
Route::get('/files/{id}', 'App\Http\Controllers\AdminController@creative_sharing_file');
Route::post('/download_link_save','App\Http\Controllers\AdminController@download_link_save')->name('website.download_link_save');



/* Login */
Route::get('/login', function () {
    return view('admin/login');
});

Route::post('/logincheck', 'App\Http\Controllers\AdminController@logincheck');
Route::get('/logout', 'App\Http\Controllers\AdminController@logout');



//foreget password

Route::get('/forgotpassword', function () {
    return view('admin/forgot_password');
});
Route::post('/resetpassword', 'App\Http\Controllers\AdminController@resetpassword');



Route::get('/sso', 'App\Http\Controllers\AdminController@sso');


//Master Admin Panel 


Route::group(['middleware' => 'prevent-back-history'],function(){

Route::middleware('session.has.admin')->group(function () {



//search code
Route::get('/view-search', 'App\Http\Controllers\AdminController@view_search');
Route::post('/get_creatives_data','App\Http\Controllers\AdminController@get_creatives_data')->name('website.get_creatives_data');
Route::post('/upload_image_get_advertisement_id', 'App\Http\Controllers\OCR_Controller@upload_image_get_advertisement_id');

Route::get('/cloudflareuploadvideo', 'App\Http\Controllers\AdminController@cloudflareuploadvideo');

Route::get('/OCR', 'App\Http\Controllers\AdminController@OCR');
Route::get('/copyImage', 'App\Http\Controllers\AdminController@copyImage');


//User Type 

Route::get('/view-user-type', 'App\Http\Controllers\AdminController@view_user_type');

Route::get('/add-user-type', 'App\Http\Controllers\AdminController@add_user_type');
Route::post('/insert_user_type', 'App\Http\Controllers\AdminController@insert_user_type');

Route::get('/edit-user-type/{id}','App\Http\Controllers\AdminController@edit_user_type');
Route::post('/update_user_type','App\Http\Controllers\AdminController@update_user_type');

Route::get('/delete_user_type/{id}','App\Http\Controllers\AdminController@delete_user_type');
Route::get('/user_type/getuser_type/','App\Http\Controllers\AdminController@getuser_type')->name('user_type.getuser_type');


//Vendor Type 

Route::get('/view-vendor-type', 'App\Http\Controllers\AdminController@view_vendor_type');

Route::get('/add-vendor-type', 'App\Http\Controllers\AdminController@add_vendor_type');
Route::post('/insert_vendor_type', 'App\Http\Controllers\AdminController@insert_vendor_type');

Route::get('/edit-vendor-type/{id}','App\Http\Controllers\AdminController@edit_vendor_type');
Route::post('/update_vendor_type','App\Http\Controllers\AdminController@update_vendor_type');


Route::get('/vendor_type/getvendor_type/','App\Http\Controllers\AdminController@getvendor_type')->name('vendor_type.getvendor_type');


//Vendor Type 
Route::get('/view-department-type', 'App\Http\Controllers\AdminController@view_department_type');

Route::get('/add-department-type', 'App\Http\Controllers\AdminController@add_department_type');
Route::post('/insert_department_type', 'App\Http\Controllers\AdminController@insert_department_type');

Route::get('/edit-department-type/{id}','App\Http\Controllers\AdminController@edit_department_type');
Route::post('/update_department_type','App\Http\Controllers\AdminController@update_department_type');

Route::get('/department_type/getdepartment_type/','App\Http\Controllers\AdminController@getdepartment_type')->name('department_type.getdepartment_type');

//Brand

Route::get('/view-brand', 'App\Http\Controllers\AdminController@view_brand');

Route::get('/add-brand', 'App\Http\Controllers\AdminController@add_brand');
Route::post('/insert_brand', 'App\Http\Controllers\AdminController@insert_brand');

Route::get('/edit-brand/{id}','App\Http\Controllers\AdminController@edit_brand');
Route::post('/update_brand','App\Http\Controllers\AdminController@update_brand');

Route::get('/delete_brand/{id}','App\Http\Controllers\AdminController@delete_brand');
Route::get('/brand/getbrand/','App\Http\Controllers\AdminController@getbrand')->name('brand.getbrand');


//Category

Route::get('/view-category', 'App\Http\Controllers\AdminController@view_category');

Route::get('/add-category', 'App\Http\Controllers\AdminController@add_category');
Route::post('/insert_category', 'App\Http\Controllers\AdminController@insert_category');

Route::get('/edit-category/{id}','App\Http\Controllers\AdminController@edit_category');
Route::post('/update_category','App\Http\Controllers\AdminController@update_category');

Route::get('/delete_category/{id}','App\Http\Controllers\AdminController@delete_category');
Route::get('/category/getcategory/','App\Http\Controllers\AdminController@getcategory')->name('category.getcategory');

// Archive Category

Route::get('/view-archive-category', 'App\Http\Controllers\AdminController@view_archive_category');

Route::get('/add-archive-category', 'App\Http\Controllers\AdminController@add_archive_category');
Route::post('/insert_archive_category', 'App\Http\Controllers\AdminController@insert_archive_category');

Route::get('/edit-archive-category/{id}','App\Http\Controllers\AdminController@edit_archive_category');
Route::post('/update_archive_category','App\Http\Controllers\AdminController@update_archive_category');


Route::get('/archive_category/getarchive_category/','App\Http\Controllers\AdminController@getarchive_category')->name('archive_category.getarchive_category');

//Department

Route::get('/view-department', 'App\Http\Controllers\AdminController@view_department');

Route::get('/add-department', 'App\Http\Controllers\AdminController@add_department');
Route::post('/insert_department', 'App\Http\Controllers\AdminController@insert_department');

Route::get('/edit-department/{id}','App\Http\Controllers\AdminController@edit_department');
Route::post('/update_department','App\Http\Controllers\AdminController@update_department');

Route::get('/delete_department/{id}','App\Http\Controllers\AdminController@delete_department');
Route::get('/department/getdepartment/','App\Http\Controllers\AdminController@getdepartment')->name('department.getdepartment');

//Document Type

Route::get('/view-document-type', 'App\Http\Controllers\AdminController@view_document_type');

Route::get('/add-document-type', 'App\Http\Controllers\AdminController@add_document_type');
Route::post('/insert_document_type', 'App\Http\Controllers\AdminController@insert_document_type');

Route::get('/edit-document-type/{id}','App\Http\Controllers\AdminController@edit_document_type');
Route::post('/update_document_type','App\Http\Controllers\AdminController@update_document_type');

Route::get('/delete_document_type/{id}','App\Http\Controllers\AdminController@delete_document_type');
Route::get('/document_type/getdocument_type/','App\Http\Controllers\AdminController@getdocument_type')->name('document_type.getdocument_type');

//Vendor

Route::get('/view-vendor', 'App\Http\Controllers\AdminController@view_vendor');
Route::get('/add-vendor', 'App\Http\Controllers\AdminController@add_vendor');
Route::post('/insert_vendor', 'App\Http\Controllers\AdminController@insert_vendor');
Route::get('/edit-vendor/{id}','App\Http\Controllers\AdminController@edit_vendor');
Route::post('/update_vendor','App\Http\Controllers\AdminController@update_vendor');
Route::get('/delete_vendor/{id}','App\Http\Controllers\AdminController@delete_vendor');
Route::get('/vendor/getvendor/','App\Http\Controllers\AdminController@getvendor')->name('vendor.getvendor');
Route::post('/get_vendor_details','App\Http\Controllers\AdminController@get_vendor_details')->name('website.get_user_details');

//User

Route::get('/view-user', 'App\Http\Controllers\AdminController@view_user');
Route::get('/add-user', 'App\Http\Controllers\AdminController@add_user');
Route::post('/insert_user', 'App\Http\Controllers\AdminController@insert_user');
Route::get('/edit-user/{id}','App\Http\Controllers\AdminController@edit_user');
Route::post('/update_user','App\Http\Controllers\AdminController@update_user');
Route::post('/get_user_details','App\Http\Controllers\AdminController@get_user_details');
Route::get('/user/getuser/','App\Http\Controllers\AdminController@getuser')->name('user.getuser');




//Auditor
Route::get('/view-auditor', 'App\Http\Controllers\AuditorController@view_auditor');
Route::get('/add-auditor', 'App\Http\Controllers\AuditorController@add_auditor');
Route::get('/auditor/getauditor/','App\Http\Controllers\AuditorController@getauditor')->name('auditor.getauditor');
Route::post('/insert_auditor', 'App\Http\Controllers\AuditorController@insert_auditor');
Route::get('/edit-auditor/{id}','App\Http\Controllers\AuditorController@edit_auditor');
Route::post('/update_auditor','App\Http\Controllers\AuditorController@update_auditor');

Route::get('/view-auditor-creative', 'App\Http\Controllers\AuditorController@view_auditor_creative');




//single file upload

Route::get('/add-single-file-upload', 'App\Http\Controllers\AdminController@add_single_file_upload');

Route::post('/insert_single_file_upload', 'App\Http\Controllers\AdminController@insert_single_file_upload');

Route::get('/password_check', 'App\Http\Controllers\AdminController@password_check');

Route::post('/get_department','App\Http\Controllers\AdminController@get_department')->name('website.get_department');
Route::post('/get_vendor','App\Http\Controllers\AdminController@get_vendor')->name('website.get_vendor');
Route::post('/get_archive_sub_category','App\Http\Controllers\AdminController@get_archive_sub_category')->name('website.get_archive_sub_category');

Route::get('/upload_image_get_advertisement_id_new','App\Http\Controllers\AdminController@upload_image_get_advertisement_id_new');

Route::post('/export_data','App\Http\Controllers\AdminController@export_data')->name('website.export_data');


//Manage Creatives
Route::get('/view-creatives', 'App\Http\Controllers\AdminController@view_creatives');

Route::get('/creatives/getcreatives/','App\Http\Controllers\AdminController@getcreatives')->name('creatives.getcreatives');

Route::get('/edit-creatives/{id}','App\Http\Controllers\AdminController@edit_creatives');
Route::post('/update_creatives','App\Http\Controllers\AdminController@update_creatives');



Route::get('/view-creatives-search', 'App\Http\Controllers\AdminController@view_creative_search');
Route::get('/creatives_custom/getcreatives_custom/','App\Http\Controllers\AdminController@getcreatives_custom')->name('creatives_custom.getcreatives_custom');



//Bulk file upload

Route::get('/add-bulk-file-upload', 'App\Http\Controllers\AdminController@add_bulk_file_upload');

Route::post('/insert_bluk_upload', 'App\Http\Controllers\AdminController@insert_bluk_upload');

Route::post('/insert_bulk_creative_main','App\Http\Controllers\AdminController@insert_bulk_creative_main')->name('website.insert_bulk_creative_main');
// Route::post('/generate_csv_file_incomplete','App\Http\Controllers\AdminController@insert_bulk_creative_main')->name('website.generate_csv_file_incomplete');

Route::get('/generate_csv_file_incomplete','App\Http\Controllers\AdminController@generate_csv_file_incomplete');

Route::get('/bulk_upload_clear_all', 'App\Http\Controllers\AdminController@bulk_upload_clear_all');



//Language

Route::get('/view-language', 'App\Http\Controllers\AdminController@view_language');

Route::get('/add-language', 'App\Http\Controllers\AdminController@add_language');
Route::post('/insert_language', 'App\Http\Controllers\AdminController@insert_language');

Route::get('/edit-language/{id}','App\Http\Controllers\AdminController@edit_language');
Route::post('/update_language','App\Http\Controllers\AdminController@update_language');

Route::get('/language/getlanguage/','App\Http\Controllers\AdminController@getlanguage')->name('language.getlanguage');


//agreement

Route::get('/view-agreement', 'App\Http\Controllers\AdminController@view_agreement');
Route::post('/insert_agreement', 'App\Http\Controllers\AdminController@insert_agreement');
Route::get('/agreement/getagreement/','App\Http\Controllers\AdminController@getagreement')->name('agreement.getagreement');

Route::get('/edit-agreement/{id}','App\Http\Controllers\AdminController@edit_agreement');
Route::post('/update_agreement','App\Http\Controllers\AdminController@update_agreement');

//Manage IRDAI Creatives

Route::get('/view-creatives-irdai', 'App\Http\Controllers\AdminController@view_creatives_irdai');
Route::get('/view-creatives-irdai', 'App\Http\Controllers\AdminController@view_creatives_irdai_new');


Route::get('/creatives_irdai/getcreatives_irdai/','App\Http\Controllers\AdminController@getcreatives_irdai')->name('creatives_irdai.getcreatives_irdai');

//Manage agreement 

Route::get('/view-agreements', 'App\Http\Controllers\AdminController@view_agreements');
Route::get('/upload-agreement', 'App\Http\Controllers\AdminController@add_agreements');

Route::post('/insert_agreements', 'App\Http\Controllers\AdminController@insert_agreements');
Route::get('/agreements/getagreements/','App\Http\Controllers\AdminController@getagreements')->name('agreements.getagreements');
Route::get('/edit-agreements/{id}','App\Http\Controllers\AdminController@edit_agreements');
Route::post('/update_agreements','App\Http\Controllers\AdminController@update_agreements');



//Archive Sub Category 
Route::get('/view-archive-sub-category', 'App\Http\Controllers\AdminController@view_archive_sub_category');
Route::post('/insert_archive_sub_category', 'App\Http\Controllers\AdminController@insert_archive_sub_category');
Route::get('/edit-archive-sub-category/{id}','App\Http\Controllers\AdminController@edit_archive_sub_category');
Route::post('/update_archive_sub_category','App\Http\Controllers\AdminController@update_archive_sub_category');
Route::get('/archive_sub_category/getarchive_sub_category/','App\Http\Controllers\AdminController@getarchive_sub_category')->name('archive_sub_category.getarchive_sub_category');




Route::get('/passwordhash', 'App\Http\Controllers\AdminController@passwordhash');

//Role Access
Route::get('/edit-user-type-access/{id}','App\Http\Controllers\AdminController@edit_user_type_access');
Route::post('/update_user_type_access','App\Http\Controllers\AdminController@update_user_type_access');

Route::get('/edit-user-access/{id}','App\Http\Controllers\AdminController@edit_user_access');
Route::post('/update_user_access','App\Http\Controllers\AdminController@update_user_access');

//Bulk file upload Before 2019

Route::get('/add-bulk-file-upload-before', 'App\Http\Controllers\AdminController@add_bulk_file_upload_before');
Route::post('/insert_bluk_upload_before', 'App\Http\Controllers\AdminController@insert_bluk_upload_before');
Route::get('/generate_csv_file_incomplete_before','App\Http\Controllers\AdminController@generate_csv_file_incomplete_before');
Route::get('/bulk_upload_before_clear_all', 'App\Http\Controllers\AdminController@bulk_upload_before_clear_all');


//document_type_auto_select
Route::POST('/get_document_data','App\Http\Controllers\AdminController@get_document_data');



//Advance search
Route::get('/view-advance-search', 'App\Http\Controllers\AdminController@view_creatives_new');

//advertisement id list
Route::get('/advertisement-id-list', 'App\Http\Controllers\AdminController@advertisement_id_list');
Route::get('/advertisementidlist/getadvertisement_id_list/','App\Http\Controllers\AdminController@getadvertisement_id_list')->name('advertisementidlist.getadvertisement_id_list');


//Open Advertisement List
Route::get('/advertisement-id-open-list', 'App\Http\Controllers\AdvertisementController@advertisement_id_open_list');
Route::get('/advertisementidopenlist/getadvertisement_id_open_list/','App\Http\Controllers\AdvertisementController@getadvertisement_id_open_list')->name('advertisementidopenlist.getadvertisement_id_open_list');
Route::post('/export_data_open','App\Http\Controllers\AdvertisementController@export_data_open')->name('website.export_data_open');

//Open Vendor Advertisement List 
Route::get('advertisement-id-vendor-open-list', 'App\Http\Controllers\AdvertisementController@advertisement_id_open_vendor_list');
Route::get('/advertisementidopenvendorlist/getadvertisement_id_open_vendor_list/','App\Http\Controllers\AdvertisementController@getadvertisement_id_open_vendor_list')->name('advertisementidopenvendorlist.getadvertisement_id_open_vendor_list');
Route::post('/export_data_open_vendor','App\Http\Controllers\AdvertisementController@export_data_open_vendor')->name('website.export_data_open_vendor');


//Vendor Functionlity  
Route::get('/view-creative-vendor', 'App\Http\Controllers\AdminController@view_creative_vendor');
Route::get('/creative_vendor/getcreatives_vendor/','App\Http\Controllers\AdminController@getcreatives_vendor')->name('creative_vendor.getcreatives_vendor');
Route::get('/add-single-file-upload-vendor', 'App\Http\Controllers\AdminController@add_single_file_upload_vendor');
Route::post('/insert_single_file_upload_vendor', 'App\Http\Controllers\AdminController@insert_single_file_upload_vendor');

Route::get('/edit-creative-vendor/{id}/{id1}/{id2}/{id3}/{id4}/{id5}/{id6}/{id7}/{id8}','App\Http\Controllers\AdminController@edit_creative_vendor');
Route::post('/update_creative_vendor','App\Http\Controllers\AdminController@update_creative_vendor');

//Route::get('/view-creative-approved', 'App\Http\Controllers\AdminController@view_creative_approved');
Route::get('/creative_approved/getcreatives_approved/','App\Http\Controllers\AdminController@getcreatives_approved')->name('creative_approved.getcreatives_approved');
Route::get('/generate-advertisement-id', 'App\Http\Controllers\AdminController@generate_advertisement_id');
Route::post('/insert_generate_advertisement_id', 'App\Http\Controllers\AdminController@insert_generate_advertisement_id');
Route::post('/get_advertisement_id_details','App\Http\Controllers\AdminController@get_advertisement_id_details')->name('website.get_advertisement_id_details');
 

Route::get('/view-creative-approved', 'App\Http\Controllers\AdminController@view_creatives_approved_new');


//Vendor change password
Route::get('/change-password', 'App\Http\Controllers\AdminController@change_password');
Route::post('/update_change_password','App\Http\Controllers\AdminController@update_change_password');



//Employee approve
Route::get('/view-creative-vendor-approve', 'App\Http\Controllers\AdminController@view_creative_vendor_approve');
Route::get('/creative_vendor_approve/getcreatives_vendor_approve/','App\Http\Controllers\AdminController@getcreatives_vendor_approve')->name('creative_vendor_approve.getcreatives_vendor_approve');
Route::post('/insert_creative_main','App\Http\Controllers\AdminController@insert_creative_main')->name('website.insert_creative_main');
Route::post('/insert_creative_main_bulk','App\Http\Controllers\AdminController@insert_creative_main_bulk')->name('website.insert_creative_main_bulk');



//send cron job 
Route::get('/sendcronjob', 'App\Http\Controllers\AdminController@cronjobsendagreement');


//Notification

Route::get('/view-notification', 'App\Http\Controllers\AdminController@view_notification_vendor');
Route::get('/view-notification-message/{id}','App\Http\Controllers\AdminController@view_notification_message');

Route::get('/view-notification-employee', 'App\Http\Controllers\AdminController@view_notification_employee');





//flipbook
Route::get('/view-newsletter', 'App\Http\Controllers\FlipbookController@view_newsletter');
Route::get('/upload-newsletter', 'App\Http\Controllers\FlipbookController@upload_newsletter');
Route::post('/insert_newsletter','App\Http\Controllers\FlipbookController@insert_newsletter')->name('website.insert_newsletter');

Route::get('/edit-newsletter/{id}','App\Http\Controllers\FlipbookController@edit_newsletter');
Route::post('/update_newsletter','App\Http\Controllers\FlipbookController@update_newsletter');


//manage-reports by nk
Route::get('/manage-reports', 'App\Http\Controllers\AdminController@manage_reports');

//manage-reports by nk adaptation
Route::get('/manage-reports-adaptation', 'App\Http\Controllers\AdminController@manage_reports_adaptation');

//manage-reports by nk miscellaneous
Route::get('/manage-reports-miscellaneous', 'App\Http\Controllers\AdminController@manage_reports_miscellaneous');

//Vendor approve
Route::get('/view-creative-vendor-approved', 'App\Http\Controllers\AdminController@view_creative_vendor_approved');

//bulk Download files
Route::POST('/zipDownload','App\Http\Controllers\AdminController@zipDownload')->name('website.zipDownload');

//manage Vendor Report
Route::get('/manage-vendor-reports', 'App\Http\Controllers\AdminController@manage_vendor_reports');

Route::POST('/get_share_links','App\Http\Controllers\AdminController@get_share_links');


Route::post('/insert_creative_main_share','App\Http\Controllers\AdminController@insert_creative_main_share')->name('website.insert_creative_main_share');


//Vendor Panel Miscellaneous Upload Creative & Miscellaneous Report
Route::get('/add-single-file-upload-vendor-miscellaneous', 'App\Http\Controllers\MiscellaneousController@add_single_file_upload_vendor_miscellaneous');
Route::post('/insert_single_file_upload_vendor_miscellaneous', 'App\Http\Controllers\MiscellaneousController@insert_single_file_upload_vendor_miscellaneous');
Route::get('/view-creative-vendor-approve-miscellaneous', 'App\Http\Controllers\MiscellaneousController@view_creative_vendor_approve_miscellaneous');
Route::get('/view-creative-vendor-miscellaneous', 'App\Http\Controllers\MiscellaneousController@view_creative_vendor_miscellaneous');
Route::get('/creative_vendor_miscellaneous/getcreatives_vendor_miscellaneous/','App\Http\Controllers\MiscellaneousController@getcreatives_vendor_miscellaneous')->name('creative_vendor_miscellaneous.getcreatives_vendor_miscellaneous');
Route::get('/edit-creative-vendor-miscellaneous/{id}','App\Http\Controllers\MiscellaneousController@edit_creative_vendor_miscellaneous');
Route::post('/update_creative_vendor_miscellaneous','App\Http\Controllers\MiscellaneousController@update_creative_vendor_miscellaneous');
Route::get('/view-creative-vendor-approved-miscellaneous', 'App\Http\Controllers\MiscellaneousController@view_creative_vendor_approved_miscellaneous');
Route::get('/edit-creative-vendor-miscellaneous/{id}','App\Http\Controllers\MiscellaneousController@edit_creative_vendor_miscellaneous');


//Admin Panel Miscellaneous Upload Creative & Miscellaneous Report
Route::get('/view-creative-vendor-approved-miscellaneous', 'App\Http\Controllers\MiscellaneousController@view_creative_vendor_approved_miscellaneous');
Route::post('/insert_creative_main_miscellaneous','App\Http\Controllers\MiscellaneousController@insert_creative_main_miscellaneous')->name('website.insert_creative_main_miscellaneous');
Route::get('/add-single-file-upload-miscellaneous', 'App\Http\Controllers\MiscellaneousController@add_single_file_upload_miscellaneous');
Route::post('/insert_single_file_upload_miscellaneous', 'App\Http\Controllers\MiscellaneousController@insert_single_file_upload_miscellaneous');
Route::get('/add-bulk-file-upload-miscellaneous', 'App\Http\Controllers\MiscellaneousController@add_bulk_file_upload_miscellaneous');
Route::post('/insert_bluk_upload_miscellaneous', 'App\Http\Controllers\MiscellaneousController@insert_bluk_upload_miscellaneous');
Route::post('/insert_bulk_creative_main_miscellaneous','App\Http\Controllers\MiscellaneousController@insert_bulk_creative_main_miscellaneous')->name('website.insert_bulk_creative_main_miscellaneous');
Route::get('/bulk_upload_clear_all_miscellaneous', 'App\Http\Controllers\MiscellaneousController@bulk_upload_clear_all_miscellaneous');
Route::get('/generate_csv_file_incomplete_miscellaneous','App\Http\Controllers\MiscellaneousController@generate_csv_file_incomplete_miscellaneous');

//Vendor Panel Adaptation Creatives Upload & Report

Route::get('/generate-advertisement-id-adaptation', 'App\Http\Controllers\AdaptationController@generate_advertisement_id_adaptation');
Route::post('/insert_generate_advertisement_id_adaptation', 'App\Http\Controllers\AdaptationController@insert_generate_advertisement_id_adaptation');
Route::get('/add-single-file-upload-vendor-adaptation', 'App\Http\Controllers\AdaptationController@add_single_file_upload_vendor_adaptation');
Route::post('/insert_single_file_upload_vendor_adaptation', 'App\Http\Controllers\AdaptationController@insert_single_file_upload_vendor_adaptation');
Route::post('/get_advertisement_id_details_adaptation','App\Http\Controllers\AdaptationController@get_advertisement_id_details_adaptation')->name('website.get_advertisement_id_details_adaptation');
Route::get('/view-creative-vendor-adaptation', 'App\Http\Controllers\AdaptationController@view_creative_vendor_adaptation');
Route::get('/creative_vendor_adaptation/getcreatives_vendor_adaptation/','App\Http\Controllers\AdaptationController@getcreatives_vendor_adaptation')->name('creative_vendor_adaptation.getcreatives_vendor_adaptation');
Route::get('/edit-creative-vendor-adaptation/{id}','App\Http\Controllers\AdaptationController@edit_creative_vendor_adaptation');
Route::post('/update_creative_vendor_adaptation','App\Http\Controllers\AdaptationController@update_creative_vendor_adaptation');

// Admin Panel Adaptation Creatives Upload & Report

Route::get('/add-single-file-upload-adaptation', 'App\Http\Controllers\AdaptationController@add_single_file_upload_adaptation');
Route::post('/insert_single_file_upload_adaptation', 'App\Http\Controllers\AdaptationController@insert_single_file_upload_adaptation');
Route::get('/view-creative-vendor-approved-adaptation', 'App\Http\Controllers\AdaptationController@view_creative_vendor_approved_adaptation');
 
Route::get('/admin-dashboard', 'App\Http\Controllers\DashboardController@dashboard');   
Route::post('/closedcreative', 'App\Http\Controllers\DashboardController@closedcreative')->name('closedcreative');

Route::get('/files-report', 'App\Http\Controllers\AdminController@files_report');

//Campaign Creatives uploaded
Route::get('/campaign-creatives-list', 'App\Http\Controllers\CampaignUploadController@campaign_creatives_list');
Route::get('/campaigncreatives/getcampaigncreatives/','App\Http\Controllers\CampaignUploadController@getcampaigncreatives')->name('campaigncreatives.getcampaigncreatives');
Route::get('/add-campaign-creatives', 'App\Http\Controllers\CampaignUploadController@add_campaign_creatives');
Route::post('/insert_campaign_creatives', 'App\Http\Controllers\CampaignUploadController@insert_campaign_creatives');
Route::get('/edit-campaign-creatives/{id}','App\Http\Controllers\CampaignUploadController@edit_campaign_creatives');
Route::post('/update_campaign_creatives','App\Http\Controllers\CampaignUploadController@update_campaign_creatives');


//Campaign Creatives uploaded
Route::get('/vendor-campaign-creatives-list', 'App\Http\Controllers\CampaignUploadVendorController@campaign_creatives_vendor_list');
Route::get('/vendorcampaigncreatives/vendorcampaigncreatives/','App\Http\Controllers\CampaignUploadVendorController@getcampaigncreativesvendor')->name('vendorcampaigncreatives.getcampaigncreativesvendor');
Route::get('/vendor-add-campaign-creatives', 'App\Http\Controllers\CampaignUploadVendorController@add_campaign_creatives_vendor');
Route::post('/vendor_insert_campaign_creatives', 'App\Http\Controllers\CampaignUploadVendorController@insert_campaign_creatives_vendor');
Route::get('/vendor-edit-campaign-creatives/{id}','App\Http\Controllers\CampaignUploadVendorController@edit_campaign_creatives_vendor');
Route::post('/vendor_update_campaign_creatives','App\Http\Controllers\CampaignUploadVendorController@update_campaign_creatives_vendor');





    
});





});












