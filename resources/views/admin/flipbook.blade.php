<html>
  <head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <link rel="stylesheet" type="text/css" href="{{ENV('APP_URL')}}flipbook/css/flipbook.style.css">
    <link rel="stylesheet" type="text/css" href="{{ENV('APP_URL')}}flipbook/css/font-awesome.css">
    <script src="{{ENV('APP_URL')}}flipbook/js/jquery-1.8.3.js"></script>
    <script src="{{ENV('APP_URL')}}flipbook/js/flipbook.min.js"></script>
    <script></script>
    <style>
      body {
        margin: 0px;
        font-family: calibri, sans-serif;
        font-size: 12pt;
        background-color: #F0F0F1;
        line-height: 1.5em;
      }

      .page-wrap {
        position: relative;
        width: 1000px;
        margin: 0px auto;
        padding: 0px;
      }

      .menu-holder {
        height: 40px;
        position: relative;
        margin: 0px auto;
        padding: 0px 0px 2px 0px;
        width: 850px;
      }

      .topbackground {
        background: url(images/topbar-bg.jpg) repeat-x;
        height: 92px;
        position: absolute;
        top: 0px;
        width: 100%;
      }

      .ui-tooltip {
        font-size: 10pt;
      }

      #menu {
        padding: 0;
        margin: 0;
        list-style: none;
      }

      #menu li {
        float: left;
        margin-left: 1px;
      }

      #menu li a {
        display: block;
        height: 40px;
        line-height: 40px;
        padding: 0 15px;
        float: left;
        background: #ddd;
        color: #000;
        text-decoration: none;
        font-family: arial, sans-serif;
        font-size: 10pt;
      }

      #menu li a b {
        text-transform: uppercase;
      }

      #menu li a:hover,
      #menu li a.active {
        background: #d04646 url(images/arrow.gif) no-repeat center bottom;
        color: #fff;
        font-weight: bold;
      }

      .box-shadow {
        background: #fff;
        border: solid 1px #dddddd;
        -moz-border-radius: 5px;
        -webkit-border-radius: 5px;
        border-radius: 5px;
        -moz-box-shadow: 0px 0px 5px #000000;
        -webkit-box-shadow: 0px 0px 5px #000000;
        box-shadow: 0px 0px 5px #000000;
      }

      .box-header {
        padding: 5px 3px;
        font-size: 16pt;
        color: #D04646;
        text-align: center;
      }

      .big-number-text {
        line-height: 1.5em;
        font-size: 30pt;
        color: #45494A;
      }

      .grid-small-text {
        padding: 5px;
        font-size: 10pt;
        text-align: center;
        vertical-align: middle;
      }

      .green-button {
        background-color: #D04646;
        border: solid 1px #D04646;
      }

      .yellow-button {
        min-width: 80px !important;
        height: 25px !important;
        margin: 0px auto;
        background-color: #31A3DD;
        border: solid 1px #31A3DD;
        color: white !important;
        font-weight: bold;
      }

      .grid-button-edit {
        min-width: 50px;
        height: 20px;
        color: white;
        -moz-border-radius: 5px;
        -webkit-border-radius: 5px;
        border-radius: 5px;
      }

      .grid-button-edit:hover {
        background-color: #D04646;
        color: #fff !important;
      }

      .row-items {
        border-bottom: solid 1px #ddd;
      }

      .ui-jqgrid tr.jqgrow td {
        font-size: 0.8em
      }

      .ui-jqgrid-labels {
        font-size: 0.8em
      }

      .ui-jqgrid-pager {
        font-size: 0.7em !important
      }

      .topics_subtopics {
        overflow-y: auto;
        height: 480px;
        width: 580px;
      }

      .topics_subtopics_notes {
        overflow-y: auto;
        height: 480px;
        width: 207px;
      }

      .form-row {
        border-bottom: solid 1px #ddd;
        line-height: 2.5em;
        vertical-align: middle;
      }

      .anchor-style {
        text-decoration: underline;
        cursor: pointer;
        color: blue;
      }

      .list-link {
        text-decoration: none;
        cursor: pointer;
        color: black;
      }

      .list-link:hover {
        color: #3399FF !important;
      }

      .ui-pg-table,
      .ui-paging-info {
        font-size: 11px !important;
      }

      .form-error {
        font-size: 10pt;
        color: red;
      }

      .ui-widget {
        font-size: 10pt !important;
      }

      .ui-timepicker-div .ui-widget-header {
        margin-bottom: 8px;
      }

      .ui-timepicker-div dl {
        text-align: left;
      }

      .ui-timepicker-div dl dt {
        height: 25px;
        margin-bottom: -25px;
      }

      .ui-timepicker-div dl dd {
        margin: 0 10px 10px 65px;
      }

      .ui-timepicker-div td {
        font-size: 90%;
      }

      .ui-tpicker-grid-label {
        background: none;
        border: none;
        margin: 0;
        padding: 0;
      }

      .ui-timepicker-rtl {
        direction: rtl;
      }

      .ui-timepicker-rtl dl {
        text-align: right;
      }

      .ui-timepicker-rtl dl dd {
        margin: 0 65px 10px 10px;
      }

      #footer-wrap {
        position: relative;
        width: 900px;
        text-align: right;
        margin: 5px auto;
        padding: 0px;
        color: #000;
        font-size: 12px;
        height: 15px;
      }

img.flowpaper_bttnDownload.flowpaper_tbbutton.download {
    display: none !important;
}
    </style>
  </head>
  <body>
    <div>&nbsp;</div>
    <div>
    <?php
     @$year=date("Y", strtotime($data->date_of_posting));
     @$month=date("m", strtotime($data->date_of_posting));
     @$photo_url=$data->photo_url;
      ?>
      <embed src="https://flowpaper.com/flipbook/https://democheck.in/MIBL-laravel/uploads/2018/01/A5%20Leaflet.pdf#toolbar=0" width="100%" height="800" style="border: none;" allowFullScreen>
      </embed>
  

      <div id="container"></div>
    </div>
  </body>
</html>