<?php
/**
 ==============================================================================
赛题管理
 ==============================================================================
 */
$language_file = array ('admin', 'registration' );
$cidReset = true;
include_once ('../inc/global.inc.php');

if(isset($_POST['dowload_ctf']) && $_POST['dowload_ctf'] === '导出CTF'){
    $download_url=URL_ROOT.'/www'.URL_APPEND.'storage/download_url';
    if(!file_exists($download_url)){
        mkdir($download_url,0777);
    }
    $mysqldump_t='mysqldump -u'.DB_USER.' -p'.DB_PWD.' -t '.DB_NAME.' tbl_Reward tbl_archives tbl_cation tbl_class tbl_contest tbl_event tbl_exam tbl_faq tbl_history tbl_match tbl_team >'.$download_url.'/ctf.sql';
    exec($mysqldump_t);
    $storage_url=URL_ROOT.'/www'.URL_APPEND.'storage/';
    $copy_attachment='cp -r '.$storage_url.'attachment '.$download_url;
    exec($copy_attachment);
    $copy_report='cp -r '.$storage_url.'report '.$download_url;
    exec($copy_report);
    $tar_str='cd '.$download_url.';tar -jcf ctf.tar.bz2 ctf.sql attachment report;rm -R ctf.sql attachment report;chmod 777 ctf.tar.bz2';
    exec($tar_str);
    $bz_file='../../storage/download_url/ctf.tar.bz2';
    header("Content-type: application/zip");
    header('Content-Disposition: attachment; filename="' . basename($bz_file) . '"');
    header("Content-Length: ". filesize($bz_file));
    readfile($bz_file);
    $del_ctf='rm ../../storage/download_url/ctf.tar.bz2';
    exec($del_ctf);
}

require_once (api_get_path ( LIBRARY_PATH ) . 'usermanager.lib.php');
require_once (api_get_path ( LIBRARY_PATH ) . 'dept.lib.inc.php');

api_protect_admin_script ();
if (! isRoot ()) api_not_allowed ();

$htmlHeadXtra [] = Display::display_thickbox ();

$action = getgpc ( 'action', 'G' );
$dept_id = isset ( $_GET ['keyword_deptid'] ) ? intval(getgpc ( 'keyword_deptid', 'G' )) : '0';

$sql = "SELECT user_id FROM " . $table_user . " WHERE is_admin=1 AND " . Database::create_in ( $_configuration ['default_administrator_name'], 'username' );
$root_user_id = Database::get_into_array ( $sql, __FILE__, __LINE__ );
$redirect_url = 'main/admin/user/user_list.php';
include_once ('../inc/header.inc.php');
echo '<aside id="sidebar" class="column ctfinex open">
       <div id="flexButton" class="closeButton close"></div>
      </aside>';

//部门数据
$objDept = new DeptManager ();

function get_sqlwhere() {
     global $objDept;
    $sql_where ='';
        $keyword = escape ( getgpc ( 'keyword', 'P' ), TRUE );
         $dept_id=getgpc ( 'matchName', 'P' );
     
        if($dept_id){
          $sql_where.=',tbl_exam where tbl_event.examId=tbl_exam.id ';
        }        
    
     if ($dept_id) {
 
        $sql_where .='and matchId='.$dept_id.' ';     
    }   
    
    if($dept_id){
            if($keyword=='输入搜索关键词' && !empty($keyword)){
                $keyword='';
            }else{
                 $sql_where .= "and (tbl_exam.exam_Name LIKE '%" . $keyword . "%')";
            } 
    }
    
    if ($sql_where){
        return $sql_where;
    }else{ 
        return "";
    }
}

function get_number_of_users() {
	$sql = "SELECT COUNT(*) AS total_number_of_items FROM tbl_event" ;
        $sql_where = get_sqlwhere ();
	if ($sql_where) $sql .= $sql_where;
	return Database::getval ( $sql, __FILE__, __LINE__ );
}

function get_user_data($from, $number_of_items) {
	$sql = "SELECT tbl_event.id,examId,isShow,eventState,tbl_event.id,matchId,isUser,sTime,tbl_event.id FROM  tbl_event ";
	$sql_where = get_sqlwhere ();     
	if ($sql_where) $sql .= $sql_where;
                $order=  intval($_GET['order']);
                if(is_int($order) && $order === 1){
                    $order_by='asc';
                }else{
                    $order_by='desc';
                }
	$sql .= "order by sTime {$order_by} LIMIT $from,$number_of_items";

                  $res = api_sql_query ( $sql, __FILE__, __LINE__ );
	$users = array ();
	while ( $user = Database::fetch_row ( $res ) ) {
	        $users [] = $user;
                  }
	return $users;
           
}
function eventState($state){
    if($state){
        return '显示';
    }else{
        return '隐藏';
    }
}

function examfun($id){
    
    $mat_query=mysql_query('select classId,matchId,tbl_exam.id from tbl_event,tbl_exam where tbl_event.id='.$id.' and tbl_event.examId=tbl_exam.id');
    $mat_id=mysql_fetch_row($mat_query);
    //return '<a href="'.URL_APPEND.'main/ctf/answer_question.php?classId='.$mat_id[0].'&qid='.$mat_id[2].'&gid='.$mat_id[1].'" target="_blank"><img src="../../themes/img/course_home.gif" style="width:30px;height:30px;" /></a>';
    return link_button ( 'course_home.gif', '预览', URL_APPEND.'main/ctf/preview_question.php?classId='.$mat_id[0].'&qid='.$mat_id[2].'&gid='.$mat_id[1].'&act=preview', '80%', '80%', FALSE );
}
function matchfun($matchid){
    $matchque=mysql_query('select matchName from tbl_contest where id='.$matchid);
    $matrow=mysql_fetch_row($matchque);
    return $matrow[0];
}
function userfun($user_id){
    $uquery=mysql_query('select username from user where user_id='.$user_id);
    $urow=mysql_fetch_row($uquery);
    return $urow[0];
}
function sTimefun($stime){
    return date('Y-m-d H:i:s',$stime);
}
function modify_filter($id, $url_params) {
	global $_configuration;
    $result ='';
	$result .= '&nbsp;' . link_button ( 'edit.gif', 'ModifyUserInfo', 'event_eidit.php?id=' . $id, '98%', '97%', FALSE );
	if (api_is_platform_admin () && ! in_array ( $id )) {
		$result .= '&nbsp;' . confirm_href ( 'delete.gif', 'ConfirmYourChoice', 'Delete', 'exam_question.php?action=delete_faq&id=' . $id );
	}

	return $result;
}
function eventName($examid){
    $exam_query=mysql_query('select exam_Name from tbl_exam where id='.$examid);
    $exam_arr=mysql_fetch_row($exam_query);
    return htmlspecialchars_decode($exam_arr[0]);
}

if (isset ( $_GET ['action'] )) {
    $id=  intval(getgpc('id'));
	switch (getgpc('action','G')) {
                case 'delete_faq':
                    $sql="delete from tbl_event where id=".$id;
                    api_sql_query ( $sql, __FILE__, __LINE__ );
                    tb_close( 'exam_question.php' );
	}
}
if($_GET['admin_users_direction'] === 'ASC'){
   $_GET['admin_users_direction']='DESC';
}
if(!empty($_GET)){
    $get_arr=$_GET;
    $get_str='';
    foreach($get_arr as $get_k => $get_v){
        $get_str.=$get_k.'='.$get_v.'&';
    }
}

if (isset ( $_POST ['action'] )) {
	switch ($_POST ['action']) {
		case 'delete' :
                                                     $del_id = $_POST['id'];
			foreach ($del_id as $index => $id ) {
                                                          $sql="delete from tbl_event where id=".$id;
                                                         mysql_query($sql);
			}
                                          break;
        }
}

$form = new FormValidator ( 'tbl_event', 'post', '', '', '_self', false );
$renderer = $form->defaultRenderer ();
$renderer->setElementTemplate ( '<span>{element}</span> ' );
$keyword_tip = get_lang ( 'LoginName' ) . "/" . get_lang ( "FirstName" ) . "/" . get_lang ( "OfficialCode" );
$form->addElement ( 'text', 'keyword', get_lang ( 'keyword' ), array ('style' => "", 'class' => 'inputText', 'title' => $keyword_tip,'value'=>'输入搜索关键词','id'=>'searchkey') );
$sql1 = "SELECT `id`,`matchName` FROM `tbl_contest`";
$result1 = api_sql_query ( $sql1, __FILE__, __LINE__ );
$arr= array ();
$arrs[0]='--所有赛事--';
while ( $arr = Database::fetch_row ( $result1) ) {
    $arrs [$arr[0]] = $arr[1];
}
$form->addElement ( 'select', 'matchName', get_lang ( 'UserInDept' ), $arrs, array ('style' => 'min-width:150px;height:30px;border: 1px solid #999;' ) );
$form->addElement ( 'submit', 'submit', get_lang ( 'Query' ), 'class="inputSubmit"' );
echo '<section id="main" class="column">';
echo '<h4 class="page-mark" >当前位置：平台首页  &gt; 赛题管理</h4>';
echo '<div class="managerSearch">';
$form->display (); //searc form

$down_form = new FormValidator ( 'dowload_contest', 'post', '', '', '_self', false );
$down_form->addElement ( 'submit', 'dowload_ctf','导出CTF', 'class="inputSubmit"' );
$down_form->display();

$upload_ctf='upload_ctf.php';
echo link_button ( 'add_user_big.gif', '导入CTF', $upload_ctf, '35%', '97%' );

echo '<span class="searchtxt right">';
$url_add_user='exam_add.php?keyword_deptid='.(is_not_blank($_GET['keyword_deptid'])?intval(getgpc('keyword_deptid','G')):intval(getgpc('keyword_orgid','G')));
echo link_button ( 'add_user_big.gif', '新增赛题', $url_add_user, '98%', '97%' );
echo '</span>';
echo "</div>";

if (isset ( $_GET ['keyword'] ) && is_not_blank ( $_GET ["keyword"] )) {
	$parameters ['keyword'] = getgpc ( 'keyword', 'G' );
	$parameters = array ('keyword' => $_GET ['keyword'], 'keyword_status' => $_GET ['keyword_status'], 'keyword_org_id' => intval($_GET ["keyword_org_id"]) );
}

if (is_not_blank ( $_GET ["keyword_org_id"] )) $parameters ['keyword_org_id'] = intval( getgpc("keyword_org_id",'G') );
if ($_GET ['matchName']) $parameters ['matchName'] = $_GET ['matchName'];

$table = new SortableTable ( 'admin_users', 'get_number_of_users', 'get_user_data', 0, NUMBER_PAGE, 'DESC' );
$table->set_additional_parameters ( $parameters );
$idx = 0;
$table->set_header ( $idx ++, '',  false, null, null);
$table->set_header ( $idx ++, get_lang ( '题目名称' ), false, null, array ('style' => 'width:20%' ));
$table->set_header ( $idx ++, get_lang ( '显示顺序' ), false, null, array ('style' => 'width:10%' ));
$table->set_header ( $idx ++, get_lang ( '显示状态' ), false, null, array ('style' => 'width:5%' ));
$table->set_header ( $idx ++, get_lang ( '预览' ), false, null, array ('style' => 'width:8%' ));
$table->set_header ( $idx ++, get_lang ( '所属赛事' ), false, null, array ('style' => 'width:20%' ));
$table->set_header ( $idx ++, get_lang ( '创建用户' ), false, null, array ('style' => 'width:10%' ));
$table->set_header ( $idx ++, get_lang ( '创建时间' ), false, null, array ('style' => 'width:15%' ));
$table->set_header ( $idx ++, get_lang ( '操作' ), false, null, array ('style' => 'width:15%' ));
$table->set_column_filter (1, 'eventName' );
$table->set_column_filter (3, 'eventState' );
$table->set_column_filter (4, 'examfun' );
$table->set_column_filter (5, 'matchfun' );
$table->set_column_filter (6, 'userfun' );
$table->set_column_filter (7, 'sTimefun' );
$table->set_column_filter (8, 'modify_filter' );
$actions = array ('delete' => get_lang ( 'BatchDelete' ),'dowload_zip'=>'批量下载' );
$table->set_form_actions ( $actions );
$order=  intval($_GET['order']);
$str_order=$order === 1 ? null : '?order=1';
?>
    <article class="module width_full">
        <table cellspacing="0" cellpadding="0" class="p-table">
	   <?php $table->display ();?>
        </table>
    </article>
</section>
<style>
    #tbl_event,#dowload_contest{
        float:left;
    }
    .row{
        display: inline-block;
    }
</style>
<script type="text/javascript">
document.ready=function(){
    var Penultimate_th=document.getElementsByTagName('tr')[0].childNodes[15];
     Penultimate_th.onmouseover=function(){
             this.style.cursor="pointer";
     };
    Penultimate_th.onclick=function(){
              location.href=location.origin+location.pathname+'<?=$str_order;?>';
    };
};
</script>