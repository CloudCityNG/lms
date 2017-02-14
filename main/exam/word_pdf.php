<?php
include_once ('../inc/global.inc.php');
$url = htmlspecialchars($_GET['url']);
$pdf_url = substr($url,0,strripos($url,'.')).'.pdf';
if(!file_exists($pdf_url)) {
     $word_pdf = "sudo unoconv -f pdf ".APP_ROOT_PATH.$url;
     exec($word_pdf);

    if(file_exists(APP_ROOT_PATH.$pdf_url))
    {
      echo $pdf_url;exit;
    }
}