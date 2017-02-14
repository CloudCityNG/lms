<?php 
$cidReset = true;
include_once ("inc/app.inc.php");
$id=  intval(getgpc('id'));
$sql="select `title`,`content` from `sys_announcement` where  `visible`=1 and `id`=".$id;
$result= api_sql_query_array_assoc($sql, __FILE__, __LINE__);
$content=$result['0']['content'];
$title=$result['0']['title'];

?>
<html> 
<head> 
</head> 
<body>
    <blockquote>
        <p align="center"><?= $title?></p> 
        <hr /> 
        <p><?= $content?></p>
    </blockquote>
</body>
</html>