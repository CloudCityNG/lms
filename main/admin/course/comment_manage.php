<?php
$language_file = array ('admin' );
$cidReset = true;
include_once ('../../inc/global.inc.php');
if (! isRoot () && $_SESSION['_user']['status']!='1') api_not_allowed ();
                
$Comment = Database::get_main_table ( Comment );
if (isset ( $_REQUEST ['action'] )) {
	switch (getgpc("action")) {
		case 'batch_unsubscribe' :
                    $subid = $_POST['id'];
                    if ($subid && is_array ( $subid )) {
                        foreach ( $subid as $id ) {
                            $sql="delete  from  {$Comment}  where  id=".intval($id);
                            api_sql_query($sql);
                        } 
                       tb_close('comment_manage.php');
                    }
                    break;
                 case 'showMore':
                        $subid = $_POST['id'];
                        if ($subid && is_array ( $subid )) {
                            foreach ( $subid as $id ) {
                                 $sql="update  {$Comment}  set  `state`=1 where  `id`=". intval($id);
                                 api_sql_query($sql);
                            } 
                           tb_close('comment_manage.php');
                        }
                        break;
                 case 'hideMore':
                        $subid = $_POST['id'];
                        if ($subid && is_array ( $subid )) {
                            foreach ( $subid as $id ) {
                                 $sql="update  {$Comment}  set  `state`=0 where  `id`=". intval($id);
                                 api_sql_query($sql);
                            } 
                           tb_close('comment_manage.php');
                        }
                        break;
	}
}
if($_GET['delete_comment']){
     $sql="delete  from  {$Comment}  where  id=".  intval(getgpc('delete_comment'));
     api_sql_query($sql);
}
if($_GET['hide_comment']){
    $sql="update  {$Comment}  set  `state`=0 where  `id`=". intval(getgpc('hide_comment'));
    api_sql_query($sql);
}
if($_GET['show_comment']){
    $sql="update  {$Comment}  set  `state`=1 where  `id`=". intval(getgpc('show_comment'));
    api_sql_query($sql);
}

function get_sqlwhere() {  
         $sql_where='';
	if (isset ( $_GET ['keyword_username'] ) && $_GET ['keyword_username']) {
		$keyword = trim ( Database::escape_str (getgpc("keyword_username","G") ), TRUE );
                $uid=  intval(DATABASE::getval("select  user_id  from  user  where username='".$keyword."'"));
                if($uid!=0){
                  $str1.="AND  uid={$uid} ";  
                } 
                $sql_where .= $str1;
	} 
        if(isset($_GET['keyword_coursetitle']) && $_GET ['keyword_coursetitle']){
                $keyword = trim ( Database::escape_str (getgpc("keyword_coursetitle","G") ), TRUE );
                $sql="select  code  from  course  where title  like  '%".$keyword."%'";
                $res=  api_sql_query_array_assoc($sql);
                $str='';
                foreach ($res as  $k=>$val){
                    if($k==0){
                        $str.= " cid={$val['code']} ";
                    }else{
                        $str.= " or  cid={$val['code']} ";
                    } 
                }  
                $sql_where .="  AND(".$str.")  ";
        }
	$sql_where = trim ( $sql_where );
	if ($sql_where)
		return substr ( $sql_where, 3 );
	else return "";
}

function get_number_of_data() {
	$Comment = Database::get_main_table ( Comment );
	$sql = "SELECT count(`id`) AS total_number_of_items
	FROM  ".$Comment." where  `fid`=0";
	$sql_where = get_sqlwhere ();
	if ($sql_where) $sql .= " AND " . $sql_where;
	return Database::get_scalar_value ( $sql );
}

function get_data($from, $number_of_items, $column, $direction) {
	$Comment = Database::get_main_table ( Comment ); 
	
	$sql = "SELECT  `id` AS col0,`cid` AS col1,`text` AS col2,`uid`	 AS col3,`comtime` AS col4,`id` AS col5,`id` AS col6,`id` AS col7  FROM  {$Comment}  where  `fid`=0";

	$sql_where = get_sqlwhere ();
	if ($sql_where) $sql .= " AND ". $sql_where;
        $sql .= " LIMIT $from,$number_of_items";
	$res = Database::query ( $sql, __FILE__, __LINE__, 0, 'NUM' );
	$data = array ();
	while ( $adata = Database::fetch_row ( $res ) ) {
		$data [] = $adata;
		unset ( $adata );
	}
	return $data;
}

function action_filter($id) {
    $action_html='';
    $action_html .=link_button ( 'synthese_view.gif', '', 'comment_reply_info.php?comment_fid=' . $id, '70%', '80%', FALSE );
    return $action_html;
}
function show_hide($id){
    $Comment = Database::get_main_table ( Comment );
    $sql="select  `state`  from  {$Comment}  where  `id`=".$id;
    $state=DATABASE::getval($sql); 
    if($state==1){
       $action_html='';
       $action_html .= confirm_href ( 'forumthread.gif', '你确定隐藏该条评论？', 'show_hide', 'comment_manage.php?hide_comment=' . $id );
    }else{
       $action_html='';
       $action_html .= confirm_href ( 'forumthread_na.gif', '你确定显示该条评论？', 'show_hide', 'comment_manage.php?show_comment=' . $id );
    }
    return $action_html;
}
function  Delete_filter($id) {
     $html='';
    $html.= confirm_href ( 'delete.gif', '你确定执行此操作？', 'Delete', 'comment_manage.php?delete_comment=' . $id );
    return $html;        
} 
function course_filter($cid){
    $sql="select  `title`  from `course`  where  `code`=".$cid;
    $title=DATABASE::getval($sql);
    return  $title;
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
$tool_name = get_lang ( 'CourseList' );
$htmlHeadXtra [] = import_assets ( "yui/tabview/assets/skins/sam/tabview.css", api_get_path ( WEB_JS_PATH ) );
$htmlHeadXtra [] = '<script type="text/javascript">$(document).ready( function() {$("body").addClass("yui-skin-sam");});</script>';
$htmlHeadXtra [] = Display::display_thickbox (); 
Display::display_header ();
                
$form = new FormValidator ( 'search_simple', 'get', '', '', null, false );
$renderer = $form->defaultRenderer ();
$renderer->setElementTemplate ( '<span>{label}{element}</span> ' );
$form->addElement ( 'text', 'keyword_username', get_lang ( '用户名' ), array ('style' => "width:120px", 'class' => 'inputText' ) );              
$form->addElement ( 'text', 'keyword_coursetitle', get_lang ( '课程名' ), array ('style' => "width:120px", 'class' => 'inputText' ) );              
$form->addElement ( 'submit', 'submit', get_lang ( 'Query' ), 'class="inputSubmit"' );
                
$table = new SortableTable ( 'comment', 'get_number_of_data', 'get_data', 0, NUMBER_PAGE, 'DESC' );

$parameters ['keyword_username'] = getgpc('keyword_username');
$parameters ['keyword_coursename'] = getgpc('keyword_coursetitle');
$table->set_additional_parameters ( $parameters );

$idx = 0;
$table->set_header ( $idx ++, '编号', false );
$table->set_header ( $idx ++, get_lang ( '课程名称' ), false, null, array ('style' => 'width:20%' ) );
$table->set_header ( $idx ++, get_lang ( '评论内容' ), false, null, array ('style' => 'width:45%' ) );
$table->set_header ( $idx ++, get_lang ( '评论者' ), false, null, array ('style' => 'width:6%' ) );
$table->set_header ( $idx ++, get_lang ( '评论时间' ), false, null, array ('style' => 'width:12%' ) );
$table->set_header ( $idx ++, get_lang ( '查看回复' ), false, null, array ('style' => 'width:5%' ) );
$table->set_header ( $idx ++, get_lang ( '显示/隐藏' ), false, null, array ('style' => 'width:5%' ) );
$table->set_header ( $idx ++, get_lang ( '删除' ), false ,null,array('style'=>'width:5%'));

$table->set_column_filter ( 1, 'course_filter' );
$table->set_column_filter ( 3, 'user_filter' );
$table->set_column_filter ( 4, 'date_filter' );
$table->set_column_filter ( 5, 'action_filter' );
$table->set_column_filter ( 6, 'show_hide' );
$table->set_column_filter ( 7, 'Delete_filter' );
$actions = array ('batch_unsubscribe' => get_lang ( 'BatchDelete' ),'showMore' => get_lang ( '批量显示' ),'hideMore' => get_lang ( '批量隐藏' ) );
$table->set_form_actions ( $actions );
?>

<aside id="sidebar" class="column course open">

    <div id="flexButton" class="closeButton close">

    </div>
</aside>

<section id="main" class="column">
    <h4 class="page-mark">当前位置：<a href="<?=URL_APPEDND;?>/main/admin/index.php">平台首页</a> &gt; <a href="<?=URL_APPEDND;?>/main/admin/course/course_list.php">课程管理</a> &gt; 课程评论管理</h4>
    <div class="managerSearch">
            <?php $form->display();?>
    </div>
    <article class="module width_full hidden">
<?php $table->display ();?>
    </article>

</section>
</body>
</html>
