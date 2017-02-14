<?php
header("content-type:text/html;charset=utf-8");
include_once ("../../inc/global.inc.php");
Display::display_header ( NULL, FALSE );
$id= intval(getgpc('id','G'));
$table_labs = Database::get_main_table (labs_labs);
if(isset($id)){
    $sql="select *,info as info2 from $table_labs where id = '".$id."'";

    $res = api_sql_query( $sql, __FILE__, __LINE__ );
    while($ss = Database::fetch_array ( $res )){
        $default = $ss;
    }
}
$form = new FormValidator ( 'uploadEnvironment', 'POST', 'upload_environment.php?id='.$id, '' );
$form->addElement ( 'html', '<i>上传文件尺寸应该小于:200M;</i>' ); 
$form->addElement("hidden","labs_id",$id);
//上传实验指导书
$form->addElement('file','info','实验环境(lib)',array('style' => "width:50%", 'class' => 'inputText','id'=>'infoid' ));
          
$group = array ();
$group [] = $form->createElement ( 'submit', 'submit', get_lang ( 'Ok' ), 'class="inputSubmit"' );
$group [] = $form->createElement ( 'style_button', 'cancle', null, array ('type' => 'button', 'class' => "cancel", 'value' => get_lang ( 'Cancel' ), 'onclick' => 'javascript:self.parent.location.reload();self.parent.tb_remove();' ) );
$form->addGroup ( $group, 'submit', '&nbsp;', null, false );

$default['type']=1;
$form->setDefaults ($default);  
Display::setTemplateBorder ( $form, '100%' ); 
if ($form->validate ()) {
        $labs  = $form->getSubmitValues ();
        $info  = $labs['info2'];
        $labs_id=$labs['labs_id'];
    //判断是否上传文件
    $typefile  = $_FILES['info'];
    $filename=$typefile['name'];
  if(!empty($filename)){  
            $typesize=$typefile['size'];
            $typeerr=$typefile['error'];
            $typetm=$typefile['tmp_name'];
        if($filename){  
             $fitype=  substr(strrchr($filename,'.'),1);
          if($fitype == 'lib'){
              $fok=1;
            }else{
              $fok=0;
            }
          if($fok == '1'){ 
              if($typeerr == '0'){
                   $upsize=200*1024*1024;
                if($typesize < $upsize){
                    $typepath=api_get_path ( SYS_PATH ).'storage/routecourses/';
                if(!file_exists($typepath)){
                     make_dir("$typepath");
                }
               if(file_exists($typepath)){
                    //删除原有的文件
                    if(!empty($labs['info2'])){
                        unlink($typepath.'/'.$labs['info2']);
                    }   
                $typename=$labs_id.'-'.date('Ymd',time()).'.'.$fitype;
                $ispackage2=move_uploaded_file($typetm,$typepath.$typename);
          if($ispackage2){
                $info=$typename;          
          }else{
                 $error='资料上传失败'; 
          }  
          }  
          }else{
                 $error='上传文件不能超过200M';
          }  
          }else{
                 $error='资料上传失败';
          }  
          }else{
                 $error='只能上传lib文件 !';
          }
          }else{
                 $error='请上传实验环境 !';
        }
  }

    $sql_data = array (
        'info' => $info
    );

    $sql = Database::sql_update( $table_labs, $sql_data,"id='$labs_id'");
   api_sql_query ( $sql, __FILE__, __LINE__ );

    if(empty($error)){
      tb_close ( 'labs_topo.php' );
    }
}

$form->display ();

if(isset($error) && $error!== ''){
    if($error == 'ok'){
         echo "<script language=\"javascript\">alert('资料上传成功！')</script>";
         tb_close ('labs_topo.php');
   }else{
       echo "<script type='text/javascript'>alert('".$error."')</script>";
       
   }
}
Display::display_footer ();
?>
<script type="text/javascript">
  $(function(){
      $("#infoid").parent().append("<input style='border:0px solid #EEEEEE;background-color:#EEEEEE;color:#666;' readonly name='info2' type='text' value='<?=$default['info']?>' />");
  })
</script>