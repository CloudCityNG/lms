<?php
/**
 * This is an add routing and switching page
 * @changzf
 * on 2013/01/10
 */

$cidReset = true;
include_once ("../../inc/global.inc.php");
if (! isRoot () && $_SESSION['_user']['status']!='1') api_not_allowed ();
header("content-type:text/html;charset=utf-8");

require_once (api_get_path ( LIBRARY_PATH ) . 'database.lib.php');
require_once (api_get_path ( LIBRARY_PATH ) . 'dept.lib.inc.php');
$table_labs = Database::get_main_table (labs_labs);


$_SESSION['id'] =  intval(getgpc('id','G'));
$id = $_SESSION['id'];

if(isset($id)){
    $sql="select *,info as info2 from $table_labs where id = '".$id."'";

    $res = api_sql_query( $sql, __FILE__, __LINE__ );
    while($ss = Database::fetch_array ( $res )){
        $default = $ss;
    }
}

$form = new FormValidator ( 'labs_topo','POST','labs_topo_edit.php?id='.$id,'');
$form->addElement ( 'html', '<div style="margin-top:2px;"></div>');
$form->addElement ( 'text', 'name', "名字", array ('maxlength' => 50, 'style' => "width:50%;height:25px;", 'class' => 'inputText' ) );
$form->addRule ( 'name', get_lang ( 'ThisFieldIsRequired' ), 'required' );
$form->addRule ( 'name', get_lang ( '最大字符长度为30' ), 'maxlength', 30 );

$form->registerRule('name_only','function','check_name');
$form->addRule('name','您输入的内容已存在，请重新输入', 'name_only');
function check_name($element_name, $element_value) {
    $table_labs_ios = Database::get_main_table ( labs_ios );
    $sql="select name from $table_labs_ios";
    $vmdisk_name=Database::get_into_array ( $sql );

    if (in_array($element_value,$vmdisk_name)) {
        return false;
    } else {
        return true;
    }
}
$labs_category_sql = "SELECT `name` FROM  `labs_category`";
$res = api_sql_query ( $labs_category_sql, __FILE__, __LINE__ );
$category= array ();
while ( $category = Database::fetch_row ( $res) ) {
    $categorys [] = $category;
}
foreach ( $categorys as $k1 => $v1){
    foreach($v1 as $k2 => $v2){
        $labs_categorys[$v2]  = $v2;
    }
}
//array_push($labs_categorys,'请选择分类');
arsort($labs_categorys);

$form->addElement ( 'select', 'labs_category', "拓扑分类", $labs_categorys,array ('maxlength' => 50, 'style' => "width:30%;height:30px;" ) );
$form->addElement('file','info','实验环境(lib)',array('style' => "width:50%", 'class' => 'inputText','id'=>'infoid' ));
$form->addElement ( 'textarea', 'description', "描述", array ('id' => 'description','class' => 'inputText','type'=>'textarea','style' => 'width:80%;height:150px' ) );
$form->addElement ( 'textarea', 'netmap', "网络拓扑", array (  'style' => "width:80%;height:100px", 'class' => 'inputText' ) );

$group = array ();
$group [] = $form->createElement ( 'style_submit_button', 'submit', get_lang ( 'Ok' ), 'class="add"' );
$group [] = $form->createElement ( 'style_button', 'cancle', null, array ('type' => 'button', 'class' => "cancel", 'value' => get_lang ( 'Cancel' ), 'onclick' => 'javascript:self.parent.tb_remove();' ) );
$form->addGroup ( $group, 'submit', '&nbsp;', null, false );

$cate_name_sql="select `name`from `labs_category` where `id`=".$default['labs_category'];
$default['labs_category']=DATABASE::getval($cate_name_sql,__FILE__,__LINE__);

$form->setDefaults ($default);
$form->add_progress_bar ();
Display::setTemplateBorder ( $form, '90%' );
if ($form->validate ()) {
        $labs  = $form->getSubmitValues ();
        $info  = $labs['info2'];
        $cate_id_sql="select `id`from `labs_category` where `name`='".$labs['labs_category']."'";
        $labs_category=DATABASE::getval($cate_id_sql,__FILE__,__LINE__);
        $netmap    = $labs['netmap'];
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
                $typename=$id.'-'.date('Ymd',time()).'.'.$fitype;
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

    $name    = $labs['name'];
    $description    = $labs['description'];
    $sql_data = array (
        'name' => $name,
        'labs_category' => $labs_category,
        'description' => $description,
        'info' => $info,
        'netmap' => $netmap
    );
    $name_sql="select name from labs_labs where id=".$id;
    $labsName=Database::getval($name_sql,__FILE__,__LINE__);

    $sql = Database::sql_update( $table_labs, $sql_data,"id='$id'");
    $result = api_sql_query ( $sql, __FILE__, __LINE__ );


    $devices_sql="UPDATE  `labs_devices` SET  `lab_id` =  '".$name."' WHERE  `labs_devices`.`lab_id` ='".$labsName."'";
    api_sql_query ( $devices_sql, __FILE__, __LINE__ );
    if(empty($error)){
      tb_close ( 'labs_topo.php' );
    }
}
Display::display_header ( $tool_name, FALSE );
$form->display ();
Display::display_footer ();
?>
<script type="text/javascript">
  $(function(){
      $("#infoid").parent().append("<input style='border:0px solid #EEEEEE;background-color:#EEEEEE;color:#666;' readonly name='info2' type='text' value='<?=$default['info']?>' />");
  })
</script>
<?php
if(isset($error) && $error!== ''){
    if($error == 'ok'){
         echo "<script language=\"javascript\">alert('资料上传成功！')</script>";
         tb_close ('labs_experimental_anual.php');
   }else{
       echo "<script type='text/javascript'>alert('".$error."')</script>";
       
   }
}
?>