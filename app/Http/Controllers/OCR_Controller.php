<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;

use thiagoalessio\TesseractOCR\TesseractOCR;


use DB;
use Auth;
use Imagick;
use Google\Cloud\Vision\VisionClient;



class OCR_Controller extends Controller
{



   public function getocrimagestext()
    {

        $imagePath='20210922 World heart Day Emailer.jpg';
        //echo (new TesseractOCR())->version();die;


        
        echo (new TesseractOCR($imagePath))
        ->lang('eng')
        ->run();

    }

    public function upload_image_get_advertisement_id(Request $request)
    { 
	  // echo json_encode("success");die;
        if($request->ajax()){
     $current_dateTime = time();
            $data = $request->file('file');
           $extension = $data->getClientOriginalExtension();
            $filename = $current_dateTime.'.'.$extension; // renameing image
           $path ='uploads/temp/';
           $upload_success = $data->move($path, $filename);
        if($extension == 'pdf')
        {
            
           $path="uploads/temp/".$filename;
           $pdf = file_get_contents($path);
           $number = preg_match_all("/\/Page\W/", $pdf, $dummy);
           if($number == 1)
           {
             $number=0;
           }else
           {
             $number=$number-1;
           }
           $photo_url=$filename;
           $arr_2=explode(".",$photo_url);
            $photo_url=$arr_2[0];
         // echo $path."[$number]";die;
           $imgExt = new Imagick();
           $imgExt->setResolution(400,400);
           $imgExt->readImage($path."[$number]");
           $imgExt->writeImages('uploads/temp/'.$photo_url.'.jpg', true);
          
           $filename=$photo_url.".jpg";

           $image_path1 =$path;
           if (file_exists($image_path1)) {
               @unlink($image_path1);
           }

          }else
          {
            $filename=$filename;   
          }
           $vision = new VisionClient(['keyFile'=> json_decode(file_get_contents("https://www.miblmbank.com/key4.json"),true)]);
           //$vision = new VisionClient(['keyFile'=> json_decode(file_get_contents("key4.json"),true)]);
           $imagepath = fopen("uploads/temp/$filename",'r');
           $image = $vision->image($imagepath,['TEXT_DETECTION']);
           $result=$vision->annotate($image);
          
          
           @$document = @$result->fullText();
          if($document != '')
          {
           @$data = @$document->text();
           // $pattern = "/[a-zA-Z0-9]+([^a-z-0-9]+([\/]\/{0,2})+(\d)+)/";
           $image_path1 = "uploads/temp/$filename";
           if (file_exists($image_path1)) {
               @unlink($image_path1);
           }
          }else
          {
            @$data ='';
          }
           echo json_encode($data);
        
        }
    }


}
