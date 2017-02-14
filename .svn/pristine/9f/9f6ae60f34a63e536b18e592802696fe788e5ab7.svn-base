<?php
$language_file = array ('admin' );
$cidReset = true;
include_once ('../../inc/global.inc.php');
if (! isRoot () && $_SESSION['_user']['status']!='1') api_not_allowed (); 
$comment_fid=  intval(getgpc('comment_fid')); 
if($comment_fid){
   $_SESSION['comment_fid']=$comment_fid; 
} 
$Comment = Database::get_main_table ( Comment );
if (isset ( $_REQUEST ['action'] )) {
	switch (getgpc("action")) {
		case 'batch_delete' :
                    $subid = $_POST['id'];
                    if ($subid && is_array ( $subid )) {
                        foreach ( $subid as $id ) {
                            $sql="delete  from  {$Comment}  where  id=".intval($id);
                            api_sql_query($sql);
                        } 
                         api_redirect ( "comment_reply_info.php?comment_fid=".$_SESSION['comment_fid']);
                    }
                    break;
                 case 'show_More':
                        $subid = $_POST['id'];
                        if ($subid && is_array ( $subid )) {
                            foreach ( $subid as $id ) {
                                 $sql="update  {$Comment}  set  `state`=1 where  `id`=". intval($id);
                                 api_sql_query($sql);
                            } 
                             api_redirect ( "comment_reply_info.php?comment_fid=".$_SESSION['comment_fid']);
                        }
                        break;
                 case 'hide_More':
                        $subid = $_POST['id'];
                        if ($subid && is_array ( $subid )) {
                            foreach ( $subid as $id ) {
                                 $sql="update  {$Comment}  set  `state`=0 where  `id`=". intval($id);
                                 api_sql_query($sql);
                            } 
                             api_redirect ( "comment_reply_info.php?comment_fid=".$_SESSION['comment_fid']);
                        }
                        break;
	}
}

function get_sqlwhere() {  
         $sql_where='';
	if (isset ( $_GET ['keyword_username'] ) && $_GET ['keyword_username']) {
		$keyword = trim ( Database::escape_str (getgpc("keyword_username","G") ), TRUE );
                $uid=  intval(DATABASE::getval("select  user_id  from  user  where username='".$keyword."'"));
                $str.="AND  uid={$uid} ";  
                $sql_where .= $str;
	} 
	$sql_where = trim ( $sql_where );
	if ($sql_where)
		return substr ( $sql_where, 3 );
	else return "";
}

if($_GET['delete']){
     $sql="delete  from  {$Comment}  where  id=".  intval(getgpc('delete'));
     api_sql_query($sql);
      api_redirect ( "comment_reply_info.php?comment_fid=".intval(getgpc('comment_fid')));
}
if($_GET['hide']){  
    $sql="update  {$Comment}  set  `state`=0 where  `id`=". intval(getgpc('hide'));
    api_sql_query($sql); 
   api_redirect ( "comment_reply_info.php?comment_fid=".intval(getgpc('comment_fid')));
}
if($_GET['show']){  
    $sql="update  {$Comment}  set  `state`=1 where  `id`=". intval(getgpc('show'));
    api_sql_query($sql); 
     api_redirect ( "comment_reply_info.php?comment_fid=".intval(getgpc('comment_fid')));
}
                
function get_number_of_data() {
	$Comment = Database::get_main_table ( Comment );
        $comment_fid=  intval(getgpc('comment_fid'));
	$sql = "SELECT count(`id`) AS total_number_of_items
	FROM  ".$Comment." where  `fid`=".$comment_fid;
        $sql_where = get_sqlwhere ();
	if ($sql_where) $sql .= " AND " . $sql_where;
	return Database::get_scalar_value ( $sql );
}

function get_data($from, $number_of_items, $column, $direction) {
	$Comment = Database::get_main_table ( Comment ); 
	$comment_fid=  intval(getgpc('comment_fid'));
	$sql = "SELECT  `id` AS col0,`text` AS col1,`uid`  AS col2,`comtime` AS col3,`id` AS col4,`id` AS col5  FROM  {$Comment}  where  `fid`=".$comment_fid;
        $sql_where = get_sqlwhere ();
	if ($sql_where) $sql .= " AND " . $sql_where;    
        $sql .= " LIMIT $from,$number_of_items";
	$res = Database::query ( $sql, __FILE__, __LINE__, 0, 'NUM' );
	$data = array ();
	while ( $adata = Database::fetch_row ( $res ) ) {
		$data [] = $adata;
		unset ( $adata );
	}
	return $data;
}
                
function show_hide($id){
    $comment_fid=  intval(getgpc('comment_fid')); 
    $Comment = Database::get_main_table ( Comment );
    $sql="select  `state`  from  {$Comment}  where  `id`=".$id;
    $state=DATABASE::getval($sql); 
    if($state==1){
       $action_html='';
       $action_html .= confirm_href ( 'forumthread.gif', '你确定隐藏该条评论？', 'show_hide', 'comment_reply_info.php?hide=' . $id.'&comment_fid='.$comment_fid );
    }else{
       $action_html='';
       $action_html .= confirm_href ( 'forumthread_na.gif', '你确定显示该条评论？', 'show_hide', 'comment_reply_info.php?show=' . $id.'&comment_fid='.$comment_fid );
    }
    return $action_html;
}
function  Delete_filter($id) {
    $comment_fid=  intval(getgpc('comment_fid'));
     $html='';
    $html.= confirm_href ( 'delete.gif', '你确定执行此操作？', 'Delete', 'comment_reply_info.php?delete=' . $id.'&comment_fid='.$comment_fid );
    return $html;        
} 
function user_filter($uid){
    $sql="select  `username`  from `user` where `user_id`=".$uid;
    $uname=DATABASE::getval($sql);
    return  $uname;
}
function  date_filter($date){
    $time= date("Y-m-d h:i:s",strtotime($date));
    return  $time;
}
                

$form = new FormValidator ( '', 'get', '', '', null, false );
$renderer = $form->defaultRenderer ();
$renderer->setElementTemplate ( '<span>{label}{element}</span> ' );
$form->addElement ( 'hidden', 'comment_fid',$comment_fid );              
$form->addElement ( 'text', 'keyword_username', get_lang ( '用户名' ), array ('style' => "width:120px", 'class' => 'inputText' ) );              
$form->addElement ( 'submit', 'submit', get_lang ( 'Query' ), 'class="inputSubmit"' );
                
$table = new SortableTable ( 'comment_reply', 'get_number_of_data', 'get_data', 0, NUMBER_PAGE, 'DESC' );

$parameters ['comment_fid'] = $_SESSION['comment_fid'];
$parameters ['keyword_username'] =trim ( Database::escape_str (getgpc("keyword_username","G") ), TRUE );
$table->set_additional_parameters ( $parameters );  

$idx = 0;
$table->set_header ( $idx ++, '编号', false ); 
$table->set_header ( $idx ++, get_lang ( '回复内容' ), false, null, array ('style' => 'width:55%' ) );
$table->set_header ( $idx ++, get_lang ( '回复者' ), false, null, array ('style' => 'width:10%' ) );
$table->set_header ( $idx ++, get_lang ( '回复时间' ), false, null, array ('style' => 'width:16%' ) ); 
$table->set_header ( $idx ++, get_lang ( '显示/隐藏' ), false, null, array ('style' => 'width:8%' ) );
$table->set_header ( $idx ++, get_lang ( '删除' ), false ,null,array('style'=>'width:8%'));
             
$table->set_column_filter ( 2, 'user_filter' );
$table->set_column_filter ( 3, 'date_filter' ); 
$table->set_column_filter ( 4, 'show_hide' );
$table->set_column_filter ( 5, 'Delete_filter' );
$actions = array ('batch_delete' => get_lang ( 'BatchDelete' ),'show_More' => get_lang ( '批量显示' ),'hide_More' => get_lang ( '批量隐藏' ) );
$table->set_form_actions ( $actions );
?>

<link rel="stylesheet" href="<?=api_get_path ( WEB_PATH )?>themes/css/layout.css" type="text/css" media="screen" />
<section>
    <div class="managerSearch">
            <?php $form->display();Display::display_footer ();?>
    </div>
    <article class="module width_full hidden">
<?php $table->display ();?>
    </article>

</section>
</body>
</html>
