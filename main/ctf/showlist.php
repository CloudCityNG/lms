<?php
include_once ("../inc/global.inc.php");
$id=$_POST['id'];
$mark=getgpc('mark','P');
$class_id=intval($id);
$content='';
if($mark==='radio'){
    $mark='radio';
}else if($mark==='checkbox'){
    $mark='checkbox';
}else{
    echo 'err';exit;
}
$class_query=mysql_query('select id,exam_Name from tbl_exam where classId='.$class_id);
while($class_row=mysql_fetch_assoc($class_query)){
    $content.='<tr>
                        <td><input  type="'.$mark.'" name="radiovalue"  value="'.$class_row["id"].'" /></td>
                        <td>'.htmlspecialchars_decode($class_row['exam_Name']).'</td>
                    </tr>';
}
echo $content;