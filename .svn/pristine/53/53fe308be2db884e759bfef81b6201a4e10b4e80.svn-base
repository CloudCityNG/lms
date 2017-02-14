<?php
/**
==============================================================================
 * 令牌桶状态
==============================================================================
 */
$language_file = 'admin';
$cidReset = true;
include_once ("../../inc/global.inc.php");
include_once("../../inc/lib/pagination.class.php");
 

$status_id=intval(getgpc('edit_id'));
function token_bucketName(){
    $status_id=intval(getgpc('edit_id'));
    if($status_id==''){
        $status_id=Database::getval("select id from token_bucket order by id limit 0,1",__FILE__,__LINE__);
    }
    if(isset($status_id) && $status_id!==''){

        $sql = "select token_bucket_name from token_bucket where id=".$status_id;
        $token_bucket_name = Database::getval( $sql, __FILE__, __LINE__ );
    }
    return $token_bucket_name;

}

$token_bucket_name = token_bucketName();
  
function get_sqlwhere() {
    global $restrict_org_id, $objCrsMng;
    $sql_where = ""; 
    if (is_not_blank ( $_GET ['keyword'] )) {
        $keyword = Database::escape_string ( $_GET ['keyword'], TRUE );
        $sql_where .= " AND (`Pid` LIKE '%" . trim ( $keyword ) . "%' OR `status` LIKE '%" . trim ( $keyword ) . "%' OR `values` LIKE '%" . trim ( $keyword ) . "%'  )";
    } 

    $sql_where = trim ( $sql_where );
    if ($sql_where)
        return substr ( ltrim ( $sql_where ), 3 );
    else return "";
}

Display::display_header ( $tool_name, FALSE );
$form = new FormValidator ( 'search_simple', 'get', '', '', '_self', false );
$renderer = $form->defaultRenderer ();
$renderer->setElementTemplate ( '<span>{element}</span> ' );
$keyword_tip ="端口";
$form->addElement ( 'text', 'keyword', get_lang ( 'keyword' ), array ('style' => "width:200px", 'class' => 'inputText','id'=>'searchkey', 'title' => $keyword_tip ) );
$form->addElement ( 'submit', 'submit', get_lang ( 'Query' ), 'class="inputSubmit"' );
?>
<article class="module width_full hidden">
    <div class="managerSearch" style ="border:1px dotted #666;">
        <?php  $form->display ();?> 
    </div>
    <form name="form1" method="post" action="">
            <?php 
             //节点令牌桶页面设置
$sql = "select `Pid`,`status` ,`values` FROM ".$token_bucket_name." where status=1";
$sql_where = get_sqlwhere ();
if ($sql_where){
    $sql .= " AND " . $sql_where;
}
$sql .= " order by `Pid`";
$res = api_sql_query( $sql, __FILE__, __LINE__ );
$vmsummarys=array();
while($vmsummary = Database::fetch_row( $res )){
    $vmsummarys[] = $vmsummary;
}
$result = count($vmsummarys);//addres count

$table=$token_add_var.'<table cellspacing="0" cellpadding="0" class="p-table">
           <tr style="background-color: rgb(240, 240, 240); "><th>端口</th><th>状态</th><th>值</th></tr>';
if($result>0){
    for($i=0;$i<$result;$i++){
        if($vmsummarys[$i][1]){
            $statuss=$vmsummarys[$i][1];
            if($statuss=='1'){
                $statuss="已占用";
            }else{
                $statuss="空闲";
            }
            
        }
        $table.='<tr class="row_even">
	                    <td style="width:100px;text-align:center">'.$vmsummarys[$i][0].'</td>
		            <td style="width:100px;text-align:center">'.$statuss.'</td>
                            <td style="width:100px;text-align:center">'.$vmsummarys[$i][2].'</td>
                         </tr>';
    }
}else{
    $table.='<tr><td colspan="10">没有令牌桶信息</td></tr>';
}
$table.="</table>";
echo $table;
Display::display_footer ();
            ?>
    </form>
    
</article>

</body>
</html>
