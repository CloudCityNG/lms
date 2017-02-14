<?php
header("content-type:text/html;charset=utf-8");
include_once ("../../inc/global.inc.php");
include_once ("../../inc/lib/fileUpload.lib.php");
include_once ("../../inc/lib/main_functions.lib.php");
include_once ("../../inc/lib/fileManage.lib.php");
Display::display_header ( NULL, FALSE );

if(isset($_POST['submit']) && $_POST['submit'] == '确定'){
    /*
     * $default_page : 默认主页
     * $title        : 文档名称
     * $comment      : 文档所属拓扑
     * $type         : 所属类型    
     */
    function uploaded_package($uploaded_file , $base_work_dir){ 
                                                             
                $uploaded_file ['name'] = stripslashes ( $uploaded_file ['name'] );
                $uploaded_file ['name'] = add_ext_on_mime ( $uploaded_file ['name'], $uploaded_file ['type'] );
                $destination = $base_work_dir . time () . '.zip';
                if (move_uploaded_file ( $uploaded_file['tmp_name'], $destination )){
                if (! file_exists ( $base_work_dir )) mkdir ( $base_work_dir, CHMOD_NORMAL );

                $upload_path = "/htmlpkg_" . time ();
                $dest_path = $base_work_dir . $upload_path;
                if (! file_exists ( $dest_path )) @mkdir ( $dest_path );

                if (! file_exists ( $dest_path )) exit ( "目标解压路径不存在: " . $dest_path );
                if (unzip_file ( $destination, $dest_path )) {
                      my_delete ( $destination );
                      my_delete ( $uploaded_file ['tmp_name'] );
                      return $upload_path;
                  }
                }else{
                      return false;
                }
    }
    //设置内存及执行时间
    ini_set ( 'memory_limit', '200M' );
    ini_set ( 'max_execution_time', 1800 ); //设置执行时间
    
    $upfile=$_FILES["uploadfile"];
    $name=$upfile["name"];
    $size=$upfile["size"];
    $error=$upfile["error"];//错误信息
 
 if($name){
    $type=substr(strrchr($name, '.'), 1);//echo $type;
        switch($type){
          case "zip" : $ok=1;
            break;
          default:$ok=0;
            break;
        }
 
if($ok){
    if($ok && $error=='0'){
        $upsize=200*1024*1024;
       if($size < $upsize){
        $labs_id= getgpc("title","P");
        $demopath=api_get_path ( SYS_PATH ).'storage/routerdoc/'.$labs_id;
        if(!file_exists("$demopath")){
               make_dir("$demopath");
        }
        $document_type=  getgpc("type","P");
        $file_path=$demopath.'/'.getgpc('attribute','P');
if(!file_exists($file_path)){        
        $ispackage=uploaded_package($_FILES["uploadfile"],$demopath);
 if($ispackage){
          $document_path=$labs_id.$ispackage.'/'.  getgpc('attribute','P');
          $urlsql=mysql_query('select document_path from labs_document where labs_id='.$labs_id.' order by id desc limit 1');
          $urlarr=mysql_fetch_row($urlsql);
          if($urlarr[0]){
            $urlpath=strrpos($urlarr[0],'/');
            $url_dcpath=substr($urlarr[0],0,$urlpath);
            $homepath=api_get_path ( SYS_PATH ).'storage/routerdoc/'.$url_dcpath;
            $exechopath="rm -rf ".$homepath;
//            exec($exechopath);
            sript_exec_log($exechopath);
            $docu_br=mysql_query("update labs_document set document_path='$document_path' where labs_id=".$labs_id);
            if($docu_br){
                        $error='ok';
            }
          }else{
                $labsqu=mysql_query('select name from labs_labs where id='.$labs_id);
                $labsarr=mysql_fetch_row($labsqu);
                //操作成功后，提示成功
                $file_attr=$demopath.'/'.$ispackage.'/'.getgpc('attribute','P');
                if(file_exists($file_attr)){ //当文件上传成功并保存到指定目录来，才执行插入语句
                    $sql = "INSERT INTO `labs_document` (`id`, `document_name`,`type`, `document_size`, `labs_id`,`document_path`) VALUES(null, '".$labsarr[0]."', '".$document_type."', '".$size."', '".$labs_id."','".$document_path."')";
                    $res = api_sql_query ( $sql, __FILE__, __LINE__ );
                    if($res){
                       $error='ok';
                    }
                 }
          }      
}else{
          $error='资料上传失败';
}
}else{
          $error='该手册已经存在！';
}     
}else{
          $error='上传文件不能超过200M';
     }
     }else{
         $error='资料上传失败';
     }
     }else{
         $error='只能上传zip的压缩包 !';
     }
}else{
         $error='请上传实验指导书！'; 
}

}

$form = new FormValidator ( 'uploadform', 'POST', 'document_upload.php', '', 'enctype="multipart/form-data"' );
$form->addElement ( 'html', '<i>上传文件尺寸应该小于:200M;</i>' );
$form->addElement ( 'hidden', 'title', intval($_GET['sid']) );

//上传实验指导书
$form->addElement ( 'file', 'uploadfile', '实验指导书(zip)', array ('style' => "width:50%", 'class' => 'inputText' ) );
$form->addRule ( 'uploadfile', get_lang ( 'ThisFieldIsRequired' ), 'required' );

$form->addElement ( 'text', 'attribute', get_lang ( '默认上传首页' ), array ('size' => '45', 'style' => "width:40%", 'class' => 'inputText' ) );
$form->addRule ( 'attribute', get_lang ( 'ThisFieldIsRequired' ), 'required' );
$form->addRule ( 'attribute', get_lang ( 'OnlyLettersAndNumbersAllowed' ), 'regex', '/^[a-zA-Z0-9\-_\.\/]+$/i' );
$default ['attribute'] = 'index.html';

$group = array ();
$group [] = $form->createElement ( 'submit', 'submit', get_lang ( 'Ok' ), 'class="inputSubmit"' );
$group [] = $form->createElement ( 'style_button', 'cancle', null, array ('type' => 'button', 'class' => "cancel", 'value' => get_lang ( 'Cancel' ), 'onclick' => 'javascript:self.parent.location.reload();self.parent.tb_remove();' ) );
$form->addGroup ( $group, 'submit', '&nbsp;', null, false );

$default['type']=1;
$form->setDefaults ($default);  
Display::setTemplateBorder ( $form, '100%' );
$form->display ();
Display::display_footer ();
if(isset($error) && $error!== ''){
    if($error == 'ok'){
         echo "<script language=\"javascript\">alert('资料上传成功！')</script>";
         tb_close ('labs_topo.php');
   }else{
       echo "<script type='text/javascript'>alert('".$error."')</script>";
       
   }
}