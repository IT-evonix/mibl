

<div class="container-fluid">
<div class="row margin-row">
<!-- Sidebar -->
<!-- <div class="col-lg-2 menus"> -->
<div class="col-lg-2">
    <section id="tab" class="tab">
        <div id="myDIV">
            <ul>

            <li id="menu" class='sub-menu'><a class="btn p-3" href="#" role="button" aria-haspopup="true" aria-expanded="false" id="master">
            <div class="tab-icon ">
                <img src="{{ENV('APP_URL')}}assets/img/r-master.png" class="r-master img-fluid tab-img">
            </div>
                 
          Master <i class="fas fa-sort-down"></i></a>
            <ul id="masters">
                <li><a class="btn p-2" href="{{ENV('APP_URL')}}view-user-type" role="button"  id="user_type">Manage User Type</a></li>
                <li id="submenu" class="submenu1"><a class="btn p-2" href="#" role="button" aria-haspopup="true" aria-expanded="false" id="master">
                Manage Field Type<i class="fas fa-sort-down pl-1"></i>
                </a>

                <ul id="fields">
                <li><a class="btn" href="{{ENV('APP_URL')}}view-vendor-type" role="button"  id="vendor_type">Manage Vendor Type</a></li>
                <li><a class="btn" href="{{ENV('APP_URL')}}view-department-type" role="button"  id="department_type">Manage Department Type</a></li>
                <li><a class="btn" href="{{ENV('APP_URL')}}view-brand" role="button" id="brand">Manage Brand</a></li>
                <li><a class="btn" href="{{ENV('APP_URL')}}view-archive-category" role="button"  id="archive_category">Manage Archive Category</a></li>
                <!--<li><a class="btn" href="{{ENV('APP_URL')}}view-category" role="button" id="category">Manage Category</a></li>-->
                <li><a class="btn" href="{{ENV('APP_URL')}}view-department" role="button" id="department">Manage Department</a></li>
                <li><a class="btn" href="{{ENV('APP_URL')}}view-document-type" role="button" id="document_type">Manage Document Type</a></li>
                <li><a class="btn" href="{{ENV('APP_URL')}}view-language" role="button" id="language">Manage Language</a></li>
                <li><a class="btn" href="{{ENV('APP_URL')}}view-agreement" role="button" id="agreement">Manage Agreement</a></li>
                <li><a class="btn" href="{{ENV('APP_URL')}}view-vendor" role="button" id="vendor">Manage Vendor</a></li>
    
            </ul>
            </ul>
            </li>
             
            <li>
            <a class="btn p-3" href="{{ENV('APP_URL')}}view-user" role="button" id="user">
                <div class="tab-icon"><img src="{{ENV('APP_URL')}}assets/img/r-manageuser.png" class="img-fluid tab-img"></div>
                Manage User
               
            </a>
            </li>   

            <li><a class="btn p-3" href="{{ENV('APP_URL')}}view-search" role="button"  id="search" >
                <div class="tab-icon"><img src="{{ENV('APP_URL')}}assets/img/r-search.png" class="img-fluid tab-img"></div>
                Search Ad
            </a>
        </li>
        <li>
            <a class="btn p-3" href="{{ENV('APP_URL')}}view-creatives" role="button" id="manage_creatives">
                <div class="tab-icon"><img src="{{ENV('APP_URL')}}assets/img/r-manage-creative.png" class="img-fluid tab-img"></div>
               Manage Creatives
            </a>
        </li>

        <li>
            <a class="btn p-3" href="{{ENV('APP_URL')}}view-creatives-irdai" role="button" id="manage_creatives_irdai">
                <div class="tab-icon"><img src="{{ENV('APP_URL')}}assets/img/r-search.png" class="img-fluid tab-img"></div>
                IRDAI - Search Creatives 
            </a>
        </li>

       
        <li id="menu" class='sub-menu'><a class="btn p-3" href="#" role="button" aria-haspopup="true" aria-expanded="false" id="master">
        <div class="tab-icon ">
        <img src="{{ENV('APP_URL')}}assets/img/r-master.png" class="r-master img-fluid tab-img">
        </div>
        Agreement <i class="fas fa-sort-down"></i></a>
        <ul id="mastersmanage_agreement">
        <li><a class="btn p-2" href="{{ENV('APP_URL')}}view-agreements" role="button"  id="manage_agreement">Manage Agreement</a></li>
        <li><a class="btn p-2" href="{{ENV('APP_URL')}}upload-agreement" role="button"  id="upload_assgreement">Upload Agreement</a></li>
        </ul>
        </li>

        <li>
            <a class="btn p-3" href="{{ENV('APP_URL')}}add-single-file-upload" role="button" id="single_file_upload">
                <div class="tab-icon"><img src="{{ENV('APP_URL')}}assets/img/r-single.png" class="img-fluid tab-img"></div>
                Single File Upload
            </a>
        </li>
        <li>
            <a class="btn p-3" href="{{ENV('APP_URL')}}add-bulk-file-upload" role="button" id="bluk_upload">
                <div class="tab-icon"><img src="{{ENV('APP_URL')}}assets/img/r-bulk.png" class="img-fluid tab-img"></div>
                Bulk File Upload
            </a>
        </li>  

        </ul>    
            
        </div>
    </section>
</div>

    

