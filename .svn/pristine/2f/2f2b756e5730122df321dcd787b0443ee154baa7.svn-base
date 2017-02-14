<?php
$cidReset = true;
$language_file = array ('courses', 'admin' );
include_once ("../../inc/global.inc.php");
//api_protect_admin_script ();

//include_once (api_get_path ( SYS_CODE_PATH ) . 'course/course.inc.php');
//require_once (api_get_path ( LIBRARY_PATH ) . 'course.lib.php');

$htmlHeadXtra [] =import_assets('commons.js');

$htmlHeadXtra [] = '<script type="text/javascript">
if(document.attachEvent)  window.attachEvent("onload",  iframeAutoFit);  
else  window.addEventListener("load",  iframeAutoFit,  false);
</script>';

$htmlHeadXtra [] = Display::display_thickbox ();
$htmlHeadXtra [] = '<script type="text/javascript">
	function refresh_tree(){ parent.CategoryTree.location.reload();/*parent.CategoryTree.d.openAll();*/ }
	</script>';
Display::display_header ();

/*
 ==============================================================================
 课程分类管理
 ==============================================================================
 */

    $language_file = 'admin';
    $cidReset = true;
    include_once ("../../inc/global.inc.php");
    $this_section = SECTION_PLATFORM_ADMIN;

    require_once (api_get_path ( LIBRARY_PATH ) . 'course.lib.php');
    require_once (api_get_path ( SYS_CODE_PATH ) . 'admin/course/course_category.inc.php');

    $org_id = (isset ( $_REQUEST ["org_id"] ) ? intval(getgpc ( 'org_id' )) : "-1");
    $category = (isset ( $_REQUEST ["category"] ) ? intval(getgpc ( 'category' )) : "0");
    $action = getgpc ( 'action' );

    $tbl_course = Database::get_main_table ( TABLE_MAIN_COURSE );
    $tbl_category = Database::get_main_table ( TABLE_MAIN_CATEGORY );

    $sql = "SELECT parent_id FROM $tbl_category WHERE id=" . intval(Database::escape (getgpc("id","G") ));
    $parent_id = Database::get_scalar_value ( $sql );

    if (! empty ( $action )) {
        if ($action == 'delete') {
           function del_file($parent_id,$isrm=false){
             $set_query=mysql_query('select id,subclass from setup');
             while($set_row=mysql_fetch_assoc($set_query)){
                 $set_rows[]=$set_row;
             }
             foreach($set_rows as $set_k => $set_v){
                 $set_str=explode(',',$set_v['subclass']);
                 if(in_array($parent_id,$set_str)){
                     $p_id=$set_v['id'];
                     break;
                 }
             }
             if(file_exists('/tmp/'.$parent_id)){
                 unlink('/tmp/'.$parent_id);
             }
             if($isrm===false){
                $del_list='rm /tmp/*s';
                exec($del_list);
                $del_l='rm /tmp/*l';
                exec($del_l);
             }

             if(file_exists('/tmp/'.$p_id.'r')){
                  unlink('/tmp/'.$p_id.'r');
             }
            }
    function s_del($id){
        $parent_arr=mysql_fetch_row(mysql_query('select parent_id from course_category where id='.$id));
        del_file($parent_arr[0],true);
    }

            $id=intval(getgpc('id'));
            $Character=getgpc('Character');
            if($Character==='p'){
                del_file($id);
            }else{
                s_del($id);
            }
            $rtn=deleteNode ($id);
            if($rtn==101){

            }



             tb_close("course_category_iframe.php");
        }elseif ($action == 'moveUp') {
            moveNodeUp (intval(getgpc('id')), getgpc('tree_pos'), $category );
            api_redirect ( $_SERVER ['PHP_SELF'] . '?category=' . $parent_id . "&result=success" );
        }
    }

    if (empty ( $action )) {
        $sql = "SELECT t1.id,t1.name,t1.code,t1.parent_id,t1.tree_pos,t1.children_count,COUNT(DISTINCT t3.code) AS nbr_courses
		 FROM $tbl_category t1 LEFT JOIN $tbl_category t2 ON t1.id=t2.parent_id
		 LEFT JOIN $tbl_course t3 ON t3.category_code=t1.id
	     GROUP BY t1.name,t1.parent_id,t1.tree_pos,t1.children_count
		 ORDER BY t1.tree_pos";
        $result = api_sql_query ( $sql, __FILE__, __LINE__ );
        $Categories = api_store_result ( $result );
    }

    if (! empty ( $category ) && empty ( $action )) {
        $result = api_sql_query ( "SELECT parent_id,name FROM $tbl_category WHERE id='$category'", __FILE__, __LINE__ );
        list ( $parent_id, $categoryName ) = mysql_fetch_row ( $result );
    }

    $objCrsMng = new CourseManager ();
    $category_tree = $objCrsMng->get_all_categories_tree ( TRUE );

    function _get_course_count($parent_id) {
        $GLOBALS ['objCrsMng']->sub_category_ids = array ();
        $sub_category_ids = $GLOBALS ['objCrsMng']->get_sub_category_tree_ids ( $parent_id, TRUE );
        $tbl_course = Database::get_main_table ( TABLE_MAIN_COURSE );
        $sql = "SELECT COUNT(*) FROM " . $tbl_course . " WHERE category_code " . Database::create_in ( $sub_category_ids );
        //echo $sql;
        return Database::get_scalar_value ( $sql );
    }

    $table_header [] = array (get_lang ( 'CategoryName' ) );
    $table_header [] = array (get_lang ( 'CategoryCode' ) );
    $table_header [] = array (get_lang ( 'CourseCount' ), false, null, array ('style' => 'width:80px' ) );
    $table_header [] = array (get_lang ( 'Actions' ), false, null, array ('style' => 'width:80px' ) );
$Category_id = $Categories;
?>
<aside id="sidebar" class="column course open">
    <div id="flexButton" class="closeButton close">

    </div>
</aside>
<section id="main" class="column">
    <h4 class="page-mark">当前位置：<a href="<?=URL_APPEDND;?>/main/admin/index.php">平台首页</a> &gt; 
        <a href="<?=URL_APPEDND;?>/main/admin/course/course_list.php">课程管理</a> &gt; 课程分类</h4>
    <article class="module width_full hidden ip">
        <table cellpadding="0" cellspacing="0" id="course-table">
            <tbody>
            <tr>
                <td class="course-dtd">
                    <div class="course-title"><strong>课程分类管理</strong></div>
                    <div class="course-add"><?php    echo link_button ( 'folder_new.gif', 'AddACategory', 'course_category_add_edit.php?action=add&category=' . $category,'90%','95%');?></div>
                </td>
            </tr>
            
            <tr><?php 
                if($Categories==null){
                    echo "<td class='course-info' style='text-align:center'> 没有相关的课程分类 </td>";
                }else{
                    echo '<td class="course-info"  style="color: #ff0000">'.'提示：鼠标点击父菜单，即可查看子菜单栏目。'.'</td>';
                }
            ?></tr>
            <?php
            //echo '<pre>';var_dump($Categories);echo '</pre>';
             foreach($Categories as $name){?>
                <?php if($name['parent_id']==0) {
                    $pid = intval($name['id']);
                    ?>
            <tr>
                <td>
                    <table cellpadding="0" cellspacing="0" class="course-list">

                        <tr class="bline">

                            <td class="course-win2">  <?php echo ($name['name']);?></td>
                            <td class="course-win3">课程总数:<?php $count = _get_course_count(intval($name['id'])); echo $count;?></td>
                            <?php 
                                        if($name ['children_count']){
                                          echo '<td class="course-win2 opens"><img src="../../../themes/img/folder_document.gif" title="进入分类"/></td>';
                                        }else{
                                          echo '<td class="course-win2 opens">&nbsp;</td>';
                                        }
                            ?>
                            <td class="course-win4">
                                <?php
                                 echo "&nbsp;/&nbsp;";
                                 echo link_button ( 'edit.gif', 'EditNode', 'course_category_add_edit.php?action=edit&category=' . urlencode ( intval($name['id']) ), '90%', '95%', FALSE );?>   
                                <?php
                                echo '&nbsp;/&nbsp;'.confirm_href ( 'delete.gif', 'ConfirmYourChoice', 'DeleteNode', $_SERVER ['PHP_SELF'] . "?category=" . urlencode ( intval($name['id']) ) . "&action=delete&id=" . urlencode ( intval($name['id']) ).'&Character=p' );
                                
                                ?></td>
                        </tr>
                        <?php foreach($Categories as $id){?>
                            <?php if($id['parent_id'] ==$pid ){?>
                        <tr class="bline-m hide">
                            <td colspan="4">
                                <table cellpadding="0" cellspacing="0" class="course-list-list">
                                    <tr>
                                        <td class="course-win2"><?php echo $id['name'];?></td>
                                        <td class="course-win3">课程总数:<?php $count = _get_course_count(intval($id['id'])); echo $count;?></td>
                                        <td class="course-win4">
                                <?php  
                                        echo link_button ( 'edit.gif', 'EditNode', 'course_category_add_edit.php?action=edit&category=' . urlencode ( intval($id['id']) ), '90%', '95%', FALSE );?>
                                <?php 
                                 
                                    echo '&nbsp;/&nbsp;' . confirm_href ( 'delete.gif', 'ConfirmYourChoice', 'DeleteNode', $_SERVER ['PHP_SELF'] . "?category=" . urlencode ( intval($id['id']) ) . "&action=delete&id=" . urlencode ( intval($id['id']) ).'&Character=s' );
                                ?>
                                            </td>
                                    </tr>
                                </table>
                            </td>
                        </tr>
            
                        <?php }?>
                        <?php }?>

                    </table>
                </td>
            </tr>

                <?php }?>
            <?php }?>
           
            </tbody>
        </table>
    </article> 
</section>
</body>
</html>
