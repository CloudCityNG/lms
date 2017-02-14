<?php
include_once ("../inc/global.inc.php");
if($_POST['name'] && $_POST['id']){
    $name=getgpc('name','P');
    $id=getgpc('id','P');
    $upqu=mysql_query('update tbl_class set className="'.$name.'" where id='.$id);
    if($upqu){
        echo "<script type='text/javascript'>self.parent.location.reload();self.parent.tb_remove();</script>";exit;
    }
}
$idid=$_GET['category'];
$id=intval($idid);
$query=mysql_query('select className from tbl_class where id='.$id);
$row=mysql_fetch_row($query);

?>
<form action="exam_add_edit.php" method="post">
    <div>分类名称：<input type="text" name="name" value="<?=$row[0]?>"/><input type="hidden" name="id" value="<?=$id?>"/></div>
    <div style="text-align: center;margin-top:10px;"><input type="submit" value="修改" class="su-put" /></div>
</form>
<style>
    .su-put{
        width:77px;
        height:30px;
        line-height:30px;
        color:#fff;
        text-align:center;
        border-radius:5px;
        background:#0B7933;
        outline:0;
        border:0;
    } 
</style>