<?php
$user_type=session('login_type');

$user_search_ad=session('user_search_ad');
$user_advance_search=session('user_advance_search');
$user_bulk_upload_files=session('user_bulk_upload_files');
$user_single_file_upload=session('user_single_file_upload');
$user_agreement=session('user_agreement');
$user_approve_creatives=session('user_approve_creatives');
$user_advertisement_id_list=session('user_advertisement_id_list');

$user_manage_newsletter=session('user_manage_newsletter');
$user_upload_newsletter=session('user_upload_newsletter');
$user_notification=session('user_notification');
$user_manage_report=session('user_manage_report');

$user_manage_miscellaneous=session('user_manage_miscellaneous');
$user_manage_adaptation=session('user_manage_adaptation');




$user_type_search_ad=session('user_type_search_ad');
$user_type_advance_search=session('user_type_advance_search');
$user_type_bulk_upload_files=session('user_type_bulk_upload_files');
$user_type_single_file_upload=session('user_type_single_file_upload');
$user_type_agreement=session('user_type_agreement');
$user_type_approve_creatives=session('user_type_approve_creatives');
$user_type_advertisement_id_list=session('user_type_advertisement_id_list');

$user_type_manage_newsletter=session('user_type_manage_newsletter');
$user_type_upload_newsletter=session('user_type_upload_newsletter');
$user_type_notification=session('user_type_notification');
$user_type_manage_report=session('user_type_manage_report');

$user_type_manage_miscellaneous=session('user_type_manage_miscellaneous');
$user_type_manage_adaptation=session('user_type_manage_adaptation');

$user_login_type=session('user_login_type');
?>

<!--<script src="https://ajax.googleapis.com/ajax/libs/jquery/1/jquery.min.js"></script>-->
<script type="text/javascript">
	jQuery(function($) {
	var path = window.location.href; // because the 'href' property of the DOM element is the absolute path
	//alert($('ul a').length);
	$('.sub-menu span').each(function() {	
		if (this.href === path) {
			$(this).addClass('sunny');
		}
		//alert(this.href);
	});
	});	
</script>

<div class="container-fluid">
<div class="row margin-row">
<!-- Sidebar -->
<!-- <div class="col-lg-2 menus"> -->
<div class="col-lg-2 left_menu_box">
    <section id="tab_no" class="tab">
        <div id="myDIV" class="scrollbar">
            <ul>

            @if($user_login_type == 'Employee')
            
            @if($user_type == 'Super Admin')   
            <li>
            <a href="{{ENV('APP_URL')}}admin-dashboard" role="button" id="dashboard">
                <div class="tab-icon report_id_icon"></div>
                <span class="left_menu_text">Dashboard</span>
            </a>
            </li>  
            @endif 
            
            
             @if($user_type == 'Super Admin')  
             
                <li id="menu" class='sub-menu'>
                <a href="#" role="button" aria-haspopup="true" aria-expanded="false">
                    <div class="tab-icon master_icon"><!--<img src="{{ENV('APP_URL')}}assets/img/r-master.png">--></div>
                    <span class="left_menu_text">Master</span> <i class="fas fa-sort-down"></i>
                </a>
                <ul id="masters">
                    <li class=""><a href="{{ENV('APP_URL')}}view-user-type" role="button" id="user_type">Manage User Type</a></li>
                    <li id="submenu" class="submenu1">
                        <a class="submenu_inner_heading_no" href="#" role="button" aria-haspopup="true" aria-expanded="false" id="master">Manage Field Type<i class="fas fa-sort-down pl-1"></i></a>

                        <ul id="fields">
                        <li><a class="btn" href="{{ENV('APP_URL')}}view-vendor-type" role="button"  id="vendor_type">Manage Vendor Type</a></li>
                        <!-- <li><a class="btn" href="{{ENV('APP_URL')}}view-department-type" role="button"  id="department_type">Manage Department Type</a></li> -->
                        <li><a class="btn" href="{{ENV('APP_URL')}}view-brand" role="button" id="brand">Manage Brand</a></li>
                        <li><a class="btn" href="{{ENV('APP_URL')}}view-archive-category" role="button"  id="archive_category">Manage Archive Category</a></li>
                        <li><a class="btn" href="{{ENV('APP_URL')}}view-archive-sub-category" role="button"  id="archive_sub_category">Manage Archive Sub Category</a></li>
                        <!--<li><a class="btn" href="{{ENV('APP_URL')}}view-category" role="button" id="category">Manage Category</a></li>-->
                        <li><a class="btn" href="{{ENV('APP_URL')}}view-department" role="button" id="department">Manage Department</a></li>
                        <li><a class="btn" href="{{ENV('APP_URL')}}view-document-type" role="button" id="document_type">Manage Document Type</a></li>
                        <li><a class="btn" href="{{ENV('APP_URL')}}view-language" role="button" id="language">Manage Language</a></li>
                        <li><a class="btn" href="{{ENV('APP_URL')}}view-agreement" role="button" id="agreement">Manage Agreement Type</a></li>
                        <li><a class="btn" href="{{ENV('APP_URL')}}view-vendor" role="button" id="vendor">Manage Vendor</a></li>
                        </ul>
                    </li>
                </ul>
                </li>
            @endif
            @if($user_type == 'Super Admin')   
            <li>
            <a href="{{ENV('APP_URL')}}view-user" role="button" id="user">
                <div class="tab-icon manageuser_icon"><!--<img src="{{ENV('APP_URL')}}assets/img/r-manageuser.png" >--></div>
                <span class="left_menu_text">Manage User</span>
               
            </a>
            </li> 
            
            <li>
            <a href="{{ENV('APP_URL')}}view-auditor" role="button" id="auditor">
                <div class="tab-icon manageuser_icon"><!--<img src="{{ENV('APP_URL')}}assets/img/r-manageuser.png" >--></div>
                <span class="left_menu_text">Manage Auditor</span>
            </a>
            </li> 
            @endif 

     @if($user_type == 'Super Admin' || ($user_search_ad == 'yes' && $user_type_search_ad == 'yes'))    
        <li><a href="{{ENV('APP_URL')}}view-search" role="button"  id="search" >
                <div class="tab-icon searchad_icon"><!--<img src="{{ENV('APP_URL')}}assets/img/r-search.png" >--></div>
                <span class="left_menu_text">Search Ad</span>
            </a>
        </li>
        @endif 
       
        @if($user_type == 'Super Admin' || ($user_advance_search == 'yes' && $user_type_advance_search == 'yes'))   
        <li>
            <a href="{{ENV('APP_URL')}}view-advance-search" role="button" id="manage_creatives">
                <div class="tab-icon advancesearch_icon"><!--<img src="{{ENV('APP_URL')}}assets/img/r-manage-creative.png" >--></div>
                <span class="left_menu_text">Advance Search</span>
            </a>
        </li>
        @endif

        @if($user_type == 'Auditor User')  
        <li>
            <a href="{{ENV('APP_URL')}}view-creatives-irdai" role="button" id="manage_creatives_irdai">
                <div class="tab-icon"><img src="{{ENV('APP_URL')}}assets/img/r-search.png" ></div>
                <span class="left_menu_text">Search Creatives</span> 
            </a>
        </li>
        @endif

        @if($user_type == 'Super Admin' || ($user_agreement == 'yes' && $user_type_agreement == 'yes')) 
        <li id="menu_agreement" class='sub-menu'>
            <a href="#" role="button" aria-haspopup="true" aria-expanded="false" id="master">
                <div class="tab-icon agreement_icon"><!--<img src="{{ENV('APP_URL')}}assets/img/r-master.png">--></div>
                <span class="left_menu_text">Agreement</span> <i class="fas fa-sort-down"></i>
            </a>
            <ul id="mastersmanage_agreement">
            <li><a href="{{ENV('APP_URL')}}view-agreements" role="button"  id="manage_agreement">Manage Agreement</a></li>
            <li><a href="{{ENV('APP_URL')}}upload-agreement" role="button"  id="upload_assgreement">Upload Agreement</a></li>
            </ul>
        </li>
        @endif
        
        @if($user_type == 'Super Admin' || ( ($user_manage_newsletter == 'yes' && $user_type_manage_newsletter == 'yes') ||  ($user_upload_newsletter == 'yes' && $user_type_upload_newsletter == 'yes') ) )    
        <li id="menu_newsletter" class='sub-menu'>
            <a href="#" role="button" aria-haspopup="true" aria-expanded="false" id="master">
                <div class="tab-icon newsletter_icon"></div>
                <span class="left_menu_text">Newsletter</span> <i class="fas fa-sort-down"></i>
            </a>
            <ul id="mastersmanage_newsletter">
            @if($user_type == 'Super Admin' || ($user_manage_newsletter == 'yes' && $user_type_manage_newsletter == 'yes') )
            <li><a href="{{ENV('APP_URL')}}view-newsletter" role="button"  id="manage_newsletter">Manage Newsletter</a></li>
            @endif
            @if($user_type == 'Super Admin' || ($user_upload_newsletter == 'yes' && $user_type_upload_newsletter == 'yes') )
            <li><a href="{{ENV('APP_URL')}}upload-newsletter" role="button"  id="upload_newsletter">Upload Newsletter</a></li>
            @endif 
           </ul>
        </li>  
        @endif
        
        @if($user_type == 'Super Admin' || ($user_manage_miscellaneous == 'yes' && $user_type_manage_miscellaneous == 'yes'))
         <li id="menu_miscellaneous_employee" class='sub-menu'>
            <a href="#" role="button" aria-haspopup="true" aria-expanded="false" id="master">
                <div class="tab-icon draft_icon"><!--<img src="{{ENV('APP_URL')}}assets/img/r-master.png">--></div>
                <span class="left_menu_text">Miscellaneous</span> <i class="fas fa-sort-down"></i>
            </a>
            <ul id="mastersmanage_miscellaneous_employee">
            <li><a href="{{ENV('APP_URL')}}add-single-file-upload-miscellaneous" role="button"  id="add_single_file_upload_miscellaneous">Single Miscellaneous File Upload</a></li>
            <li><a href="{{ENV('APP_URL')}}add-bulk-file-upload-miscellaneous" role="button"  id="add_bulk_file_upload_miscellaneous">Bulk Miscellaneous File Upload</a></li>
            <li><a href="{{ENV('APP_URL')}}view-creative-vendor-approved-miscellaneous" role="button"  id="approve_miscellaneous_creatives">Approve Miscellaneous Creatives</a></li>
            </ul>
        </li>
        @endif
        
        
        @if($user_type == 'Super Admin' || ($user_manage_adaptation == 'yes' && $user_type_manage_adaptation == 'yes'))
         <li id="menu_adaptation_employee" class='sub-menu'>
            <a href="#" role="button" aria-haspopup="true" aria-expanded="false" id="master">
                <div class="tab-icon draft_icon"><!--<img src="{{ENV('APP_URL')}}assets/img/r-master.png">--></div>
                <span class="left_menu_text">Adaptation</span> <i class="fas fa-sort-down"></i>
            </a>
            <ul id="mastersmanage_adaptation_employee">
            <li><a href="{{ENV('APP_URL')}}add-single-file-upload-adaptation" role="button"  id="add_single_file_upload_adaptation">Single Adaptation File Upload</a></li>
            <li><a href="{{ENV('APP_URL')}}view-creative-vendor-approved-adaptation" role="button"  id="approve_adaptation_creatives">Approve Adaptation Creatives</a></li>
            </ul>
        </li>
        @endif

        @if($user_type == 'Super Admin' || ($user_single_file_upload == 'yes' && $user_type_single_file_upload == 'yes')) 
        <li>
            <a href="{{ENV('APP_URL')}}add-single-file-upload" role="button" id="single_file_upload">
                <div class="tab-icon singlefileupload_icon"><!--<img src="{{ENV('APP_URL')}}assets/img/r-single.png" >--></div>
                <span class="left_menu_text">Single File Upload</span>
            </a>
        </li>
        
       <!-- <li>
            <a href="{{ENV('APP_URL')}}add-single-file-upload-miscellaneous" role="button" id="single_file_upload_miscellaneous">
                <div class="tab-icon singlefileupload_icon"></div>
                <span class="left_menu_text">Single Miscellaneous File Upload</span>
            </a>
        </li>-->
        @endif

        @if($user_type == 'Super Admin' || ($user_bulk_upload_files == 'yes' && $user_type_bulk_upload_files == 'yes'))
        <li>
            <a href="{{ENV('APP_URL')}}add-bulk-file-upload" role="button" id="bluk_upload">
                <div class="tab-icon bulkfileupload_icon"><!--<img src="{{ENV('APP_URL')}}assets/img/r-bulk.png" >--></div>
                <span class="left_menu_text">Bulk File Upload</span>
            </a>
        </li> 
        @endif 
      
        @if($user_type == 'Super Admin' || ($user_bulk_upload_files == 'yes' && $user_type_bulk_upload_files == 'yes'))

        <li>
            <a href="{{ENV('APP_URL')}}add-bulk-file-upload-before" role="button" id="bluk_upload_before">
                <div class="tab-icon bulkfileupload2019_icon"><!--<img src="{{ENV('APP_URL')}}assets/img/r-bulk.png" >--></div>
                <span class="left_menu_text" style="margin: 0;">Bulk File Upload <br>Before 2019</span>
            </a>
        </li> 
        @endif
        
        
        @if($user_type == 'Super Admin' || ($user_bulk_upload_files == 'yes' && $user_type_bulk_upload_files == 'yes'))
        <!--<li>
            <a href="{{ENV('APP_URL')}}add-bulk-file-upload-miscellaneous" role="button" id="bluk_upload_miscellaneous">
                <div class="tab-icon bulkfileupload_icon"></div>
                <span class="left_menu_text">Bulk Miscellaneous File Upload</span>
            </a>
        </li> -->
        @endif
        

        @if($user_type == 'Super Admin' || ($user_approve_creatives == 'yes' && $user_type_approve_creatives == 'yes'))

        <li>
        <a href="{{ENV('APP_URL')}}view-creative-vendor-approved" role="button" id="manage_creative_approve">
        <div class="tab-icon approve_creatives_icon"><!--<img src="{{ENV('APP_URL')}}assets/img/r-bulk.png" >--></div>
        <span class="left_menu_text">Approve Creatives</span>
        </a>
        </li> 
        <!--
        <li>
        <a href="{{ENV('APP_URL')}}view-creative-vendor-approved-miscellaneous" role="button" id="manage_creative_approve_miscellaneous">
        <div class="tab-icon approve_creatives_icon"></div>
        <span class="left_menu_text">Approve Miscellaneous Creatives</span>
        </a>
        </li> -->
        @endif
        
       
        

       

        @if($user_type == 'Super Admin' || ($user_advertisement_id_list == 'yes' && $user_type_advertisement_id_list == 'yes'))

        <li>
        <a href="{{ENV('APP_URL')}}advertisement-id-list" role="button" id="advertisement_id_list">
        <div class="tab-icon advertisement_id_icon"><!--<img src="{{ENV('APP_URL')}}assets/img/r-bulk.png" >--></div>
        <span class="left_menu_text">Advertisement Id List</span>
        </a>
        </li> 
        
        <li>
        <a href="{{ENV('APP_URL')}}advertisement-id-open-list" role="button" id="open_advertisement_id_list">
        <div class="tab-icon advertisement_id_icon"></div>
        <span class="left_menu_text">Open Advertisement Id List</span>
        </a>
        </li> 
        @endif
        
        <li>
            <a href="{{ENV('APP_URL')}}view-notification-employee" role="button" id="view_notification_employee">
                <div class="tab-icon notification_icon"></div>
                <span class="left_menu_text">Notification</span>
            </a>
        </li>
         <!-- 
          <li>
            <a href="{{ENV('APP_URL')}}view-newsletter" role="button" id="view_newsletter">
                <div class="tab-icon newsletter_icon"></div>
                <span class="left_menu_text">Newsletter</span>
            </a>
          </li>-->



        @endif

        @if($user_login_type == 'Vendor') 

        <li>
            <a href="{{ENV('APP_URL')}}view-creative-approved" role="button" id="manage_creative_approved">
                <div class="tab-icon approved_creatives_icon"><!--<img src="{{ENV('APP_URL')}}assets/img/r-manage-creative.png" >--></div>
                <span class="left_menu_text">Approved Creatives</span>
            </a>
        </li>
        <li>
            <a href="{{ENV('APP_URL')}}view-creative-vendor" role="button" id="manage_creative_draft">
                <div class="tab-icon draft_icon"><!--<img src="{{ENV('APP_URL')}}assets/img/r-manage-creative.png" >--></div>
                <span class="left_menu_text">Draft Creatives</span>
            </a>
        </li>
        <li>
            <a href="{{ENV('APP_URL')}}add-single-file-upload-vendor" role="button" id="single_file_upload_vendor">
                <div class="tab-icon upload_creatives_icon"><!--<img src="{{ENV('APP_URL')}}assets/img/r-single.png" >--></div>
                <span class="left_menu_text">Upload Creative</span>
            </a>
        </li>
        
        
        <li id="menu_miscellaneous" class='sub-menu'>
            <a href="#" role="button" aria-haspopup="true" aria-expanded="false" id="master">
                <div class="tab-icon draft_icon"><!--<img src="{{ENV('APP_URL')}}assets/img/r-master.png">--></div>
                <span class="left_menu_text">Miscellaneous</span> <i class="fas fa-sort-down"></i>
            </a>
            <ul id="mastersmanage_miscellaneous">
            <li><a href="{{ENV('APP_URL')}}view-creative-vendor-miscellaneous" role="button"  id="manage_miscellaneous">Draft Miscellaneous Creatives</a></li>
            <li><a href="{{ENV('APP_URL')}}add-single-file-upload-vendor-miscellaneous" role="button"  id="upload_miscellaneous">Upload Miscellaneous Creative</a></li>
            </ul>
        </li>
        
        <!--
        <li>
            <a href="{{ENV('APP_URL')}}view-creative-vendor-miscellaneous" role="button" id="manage_creative_miscellaneous_draft">
                <div class="tab-icon draft_icon"></div>
                <span class="left_menu_text">Draft Miscellaneous Creatives</span>
            </a>
        </li>
        
        <li>
            <a href="{{ENV('APP_URL')}}add-single-file-upload-vendor-miscellaneous" role="button" id="single_file_upload_vendor_miscellaneous">
                <div class="tab-icon upload_creatives_icon"></div>
                <span class="left_menu_text">Upload Miscellaneous Creative</span>
            </a>
        </li>-->
        
        
        <li id="menu_adaptation" class='sub-menu'>
            <a href="#" role="button" aria-haspopup="true" aria-expanded="false" id="master">
                <div class="tab-icon draft_icon"><!--<img src="{{ENV('APP_URL')}}assets/img/r-master.png">--></div>
                <span class="left_menu_text">Adaptation</span> <i class="fas fa-sort-down"></i>
            </a>
            <ul id="mastersmanage_adaptation">
            <li><a href="{{ENV('APP_URL')}}view-creative-vendor-adaptation" role="button"  id="manage_adaptation">Draft Adaptation Creatives</a></li>
            <li><a href="{{ENV('APP_URL')}}add-single-file-upload-vendor-adaptation" role="button"  id="upload_adaptation">Upload Adaptation Creative</a></li>
            <li><a href="{{ENV('APP_URL')}}generate-advertisement-id-adaptation" role="button"  id="generate_advertisement_id_adaptation">Generate Adaptation Advertisement ID</a></li>
            </ul>
        </li>
        
        <li>
        <a href="{{ENV('APP_URL')}}advertisement-id-vendor-open-list" role="button" id="open_advertisement_vendor_id_list">
        <div class="tab-icon advertisement_id_icon"></div>
        <span class="left_menu_text">Open Advertisement Id List</span>
        </a>
        </li> 
        
        <li>
            <a href="{{ENV('APP_URL')}}generate-advertisement-id" role="button" id="generate_advertisement_id">
                <div class="tab-icon advertisement_id_icon"><!--<img src="{{ENV('APP_URL')}}assets/img/r-single.png" >--></div>
                <span class="left_menu_text">Generate Advertisement<br>ID</span>
            </a>
        </li>
        
        
        <!--
        <li>
            <a href="{{ENV('APP_URL')}}generate-advertisement-id-adaptation" role="button" id="generate_advertisement_id_adaptation">
                <div class="tab-icon advertisement_id_icon"></div>
                <span class="left_menu_text">Generate Adaptation Advertisement<br>ID</span>
            </a>
        </li>-->
        
        <li>
            <a href="{{ENV('APP_URL')}}change-password" role="button" id="change_password">
                <div class="tab-icon changepass_icon"><!--<img src="{{ENV('APP_URL')}}assets/img/r-master.png" >--></div>
                <span class="left_menu_text">Change Password</span>
            </a>
        </li>
        <li>
            <a href="{{ENV('APP_URL')}}view-notification" role="button" id="view_notification">
                <div class="tab-icon notification_icon"></div>
                <span class="left_menu_text">Notification</span>
            </a>
        </li>

        <li>
        <a href="{{ENV('APP_URL')}}manage-vendor-reports" role="button" id="vendor_reports">
        <div class="tab-icon advertisement_id_icon"></div>
        <span class="left_menu_text">Manage Reports</span>
        </a>
        </li> 
        
         <li>
            <a href="{{ENV('APP_URL')}}vendor-campaign-creatives-list" role="button" id="campaign_upload">
                <div class="tab-icon singlefileupload_icon"></div>
                <span class="left_menu_text">Campaign Upload</span>
            </a>
        </li>
        
        @endif

        @if($user_type == 'Super Admin' || ($user_manage_report == 'yes' && $user_type_manage_report == 'yes'))   
        <li>
        <a href="{{ENV('APP_URL')}}manage-reports" role="button" id="manage_reports">
        <div class="tab-icon report_id_icon"></div>
        <span class="left_menu_text">Manage Reports</span>
        </a>
        </li>  
        
        <li>
        <a href="{{ENV('APP_URL')}}manage-reports-adaptation" role="button" id="manage_reports_adaptation">
        <div class="tab-icon report_id_icon"></div>
        <span class="left_menu_text">Manage Adaptation Reports</span>
        </a>
        </li>  
        
        <li>
        <a href="{{ENV('APP_URL')}}manage-reports-miscellaneous" role="button" id="manage_reports_miscellaneous">
        <div class="tab-icon report_id_icon"></div>
        <span class="left_menu_text">Manage Miscellaneous Reports</span>
        </a>
        </li>
        
        @endif 
        
        @if($user_type == 'Super Admin' || $user_type == 'Admin User' )
        <li>
        <a href="{{ENV('APP_URL')}}files-report" role="button" id="manage_reports_files">
        <div class="tab-icon report_id_icon"></div>
        <span class="left_menu_text">Share Link Report</span>
        </a>
        </li> 
        @endif 
       
       @if($user_login_type != 'Vendor' && $user_login_type != 'Auditor') 
        <li>
        <a href="https://www.miblmbank.com/landing/admin/" role="button" id="manage_reports_files" target="_blank">
        <div class="tab-icon report_id_icon"></div>
        <span class="left_menu_text">Landing Pages</span>
        </a>
        </li>
        @endif 
        
        
        @if($user_login_type == 'Auditor')   
        <li>
            <a href="{{ENV('APP_URL')}}view-auditor-creative" role="button" id="manage_creatives">
                <div class="tab-icon advancesearch_icon"><!--<img src="{{ENV('APP_URL')}}assets/img/r-manage-creative.png" >--></div>
                <span class="left_menu_text">View Creative</span>
            </a>
        </li>
        @endif
        
        @if($user_type == 'Super Admin')   
        <li>
            <a href="{{ENV('APP_URL')}}campaign-creatives-list" role="button" id="campaign_upload">
                <div class="tab-icon singlefileupload_icon"></div>
                <span class="left_menu_text">Campaign Upload</span>
            </a>
        </li>
        @endif
        
        
        </ul>    
            
        </div>
    </section>
    <!-- <div class="ps-scrollbar-y-rail" style="top: 0px; height: 885px; right: 3px;"><div class="ps-scrollbar-y" tabindex="0" style="top: 0px; height: 789px;"></div></div> -->


</div>

    

