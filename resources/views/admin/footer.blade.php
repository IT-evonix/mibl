
<!-- jQuery CDN -->
 <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>

<!-- Bootstrap JS CDN -->
<script type="text/javascript" src="{{ENV('APP_URL')}}assets/js/bootstrap.min.js"></script>

<!-- Datatables JS CDN -->
<script type="text/javascript" src="{{ENV('APP_URL')}}assets/js/jquery.dataTables.min.js"></script>

<script type="text/javascript" src="{{ENV('APP_URL')}}assets/js/dataTables.buttons.min.js"></script>
<script type="text/javascript" src="{{ENV('APP_URL')}}assets/js/jszip.min.js"></script>
<script type="text/javascript" src="{{ENV('APP_URL')}}assets/js/pdfmake.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.36/vfs_fonts.js"></script>
<script type="text/javascript" src="{{ENV('APP_URL')}}assets/js/buttons.flash.min.js"></script>
<script type="text/javascript" src="{{ENV('APP_URL')}}assets/js/buttons.html5.min.js"></script>
<script type="text/javascript" src="{{ENV('APP_URL')}}assets/js/buttons.print.min.js"></script>
<script src="https://cdn.syncfusion.com/ej2/dist/ej2.min.js"></script>

<link rel="stylesheet" href="https://ajax.googleapis.com/ajax/libs/jqueryui/1.13.2/themes/base/jquery-ui.css">
<script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.13.2/jquery-ui.min.js"></script>


<script>
      $(document).ready(function() {
        $('input').attr('autocomplete', 'off');
      });
    </script>
<script>
$('.sub-menu ul').hide();
$(".sub-menu a").click(function (e) {

    const href = $(this).attr("href");

    if (!href || href === "#" || href.startsWith("javascript")) {
        e.preventDefault();

        $(this).parent(".sub-menu").children("ul").slideToggle(100);
        $(this).find(".right").toggleClass("fa-caret-up fa-caret-down");
    }

});
</script>

<script>
$('.submenu1 ul').hide();
$(".submenu1 a").click(function () {
$(this).parent(".submenu1").children("ul").slideToggle("100");
$(this).find(".right").toggleClass("fa-caret-up fa-caret-down");
}); 

document.querySelectorAll('a[target="_blank"]').forEach(a=>{
    a.rel = "noopener noreferrer";
});
</script>

