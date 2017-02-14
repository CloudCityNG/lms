<?php
$cidReset = true;
include_once ("inc/app.inc.php");
if(!api_get_user_id ()){
    $_SESSION['up_url']='http://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
    header ( "Location: ./login.php" );
}
include_once ("inc/page_header.php");
include_once (api_get_path ( LIBRARY_PATH ) . 'fileManage.lib.php');
include_once (api_get_path ( LIBRARY_PATH ) . 'dept.lib.inc.php');
include_once ('../../main/inc/conf/user.conf.php');
$tbl_user = Database::get_main_table ( TABLE_MAIN_USER );
$user_id = api_get_user_id ();
$objDept = new DeptManager ();
$r_action=  getgpc("action");
$tooken_key = $_SESSION['user_profile'];

if (is_equal ( $r_action, "save" ) && $_POST[$tooken_key] == 'tooken') {
    $user_data = array (
        "lastname" => getgpc ( "lastname","P" ),
        "en_name" => getgpc ( "en_name","P" ),
        "birthday" => getgpc ( "birthday" ,"P"),
        'sex' => intval(getgpc ( 'sex',"P" )),
        'email' => getgpc ( 'email',"P" ),
        'phone' => getgpc ( 'phone',"P" ),
        'mobile' => getgpc ( 'mobile',"P" ),
    );

    if (getgpc ( "firstname" ) && api_get_setting ( 'profile', 'name' ) == 'true') $user_data ['firstname'] = getgpc ( "firstname" ,"P");
 //   if (getgpc ( "email" ) && api_get_setting ( 'profile', 'email' ) == 'true') $user_data ['email'] = getgpc ( "email" );
    if (getgpc ( "dept_id" ) && api_get_setting ( 'profile', 'dept' ) == 'true') $user_data ['dept_id'] = intval(getgpc ( "dept_id","P" ));
    if (getgpc ( "official_code" ) && api_get_setting ( 'profile', 'officialcode' ) == 'true') $user_data ['official_code'] = getgpc ( "official_code","P" );
    if (getgpc ( "credential_no" ) && api_get_setting ( 'profile', 'credential_no' ) == 'true') $user_data ['credential_no'] = getgpc ( "credential_no" ,"P");
   /// if (getgpc ( "phone" ) && api_get_setting ( 'profile', 'phone' ) == 'true') $user_data ['phone'] = getgpc ( "phone" );
   // if (getgpc ( "mobile" ) && api_get_setting ( 'profile', 'mobile' ) == 'true') $user_data ['mobile'] = getgpc ( "mobile" );
    if (getgpc ( "qq" ) && api_get_setting ( 'profile', 'qq' ) == 'true') $user_data ['qq'] = getgpc ( "qq" ,"P");

    $dept_in_org = $objDept->get_dept_in_org ( intval(getgpc ( "dept_id" ,"P")), TRUE );
    $dept_org = array_pop ( $dept_in_org );
    $user_data ['org_id'] = $dept_org ['id'];
    $user_data ['last_updated_date'] = date ( 'Y-m-d H:i:s' );

    if ($_FILES ['picture'] ['size'] > 0 && is_uploaded_file ( $_FILES ['picture'] ['tmp_name'] )) {
        $new_picture = upload_user_image ( $user_id );
        if ($new_picture) $user_data ['picture_uri'] = $new_picture;
    } elseif (getgpc ( 'remove_picture' )) {
        remove_user_image ( $user_id );
        $user_data ['picture_uri'] = '';
    }

    $sql = Database::sql_update ( $tbl_user, $user_data, "user_id=" . Database::escape ( $user_id ) );
    $result = api_sql_query ( $sql, __FILE__, __LINE__ );
    if ($result) {
         header ( "Location:".URL_APPEDND."/portal/sp/user_profile.php?msg=success" );
    }
}

$user_data = UserManager::get_user_information ( $user_id );
if ($user_data) {
    $user_dept_path = get_dept_path ( $user_data ["dept_id"], false, TRUE );
} else {
    api_redirect ( 'user_center.php' );
}

$dept_options = $objDept->get_sub_dept_ddl2 ( 0, 'array' );
unset ( $dept_options [1] );
$tooken_name = md5(rand(88,888888));
$_SESSION['user_profile'] = $tooken_name;
$img_attributes = get_user_picture ( $user_id );

$interbreadcrumb [] = array ("url" => 'index.php', "name" => "首页" );
$interbreadcrumb [] = array ("url" => 'index.php?learn_status=user', "name" => "用户中心" );
$interbreadcrumb [] = array ("url" => 'user_profile.php', "name" => "信息修改" );
echo import_assets ( 'js/formValidator/style/validator.css', WEB_QH_PATH );
echo import_assets ( 'js/formValidator/formValidator.js', WEB_QH_PATH );
echo import_assets ( 'js/formValidator/formValidatorRegex.js', WEB_QH_PATH );
echo import_assets ( 'js_calendar.js', api_get_path ( WEB_JS_PATH ) );
?>
<style>
    body{
        color:#444;
    }
    .la{color:#444;}
  input{color:#444;}
</style>
<script type="text/javascript">
    $(document).ready(function(){
        //$.formValidator.initConfig({formid:"theForm",onerror:function(){alert("校验没有通过，具体错误请看错误提示")}});
        $.formValidator.initConfig({formid:"theForm",onerror:function(msg){$.prompt(msg)}});

        $("#firstname").formValidator({onshow:"请输入真实姓名",onfocus:"真实姓名不能为空",oncorrect:"真实姓名输入合法"})
                .inputValidator({min:1,empty:{leftempty:false,rightempty:false,emptyerror:"真实姓名两边不能有空符号"},onerror:"真实姓名长度不合要求,请确认"});

        $("#credential_no").formValidator({onshow:"请输入身份证号",onfocus:"请输入18位身份证号",oncorrect:"身份证号输入合法"})
                .inputValidator({min:18,empty:{leftempty:false,rightempty:false,emptyerror:"身份证号两边不能有空符号"},onerror:"身份证号长度不合要求,请确认"})
                .regexValidator({regexp:"username",datatype:"enum",onerror:"身份证号格式不正确"});

        $("#mobile").formValidator({onshow:"请输入手机号",onfocus:"请输入11位手机号",oncorrect:"手机号输入合法"})
                .inputValidator({min:11,empty:{leftempty:false,rightempty:false,emptyerror:"手机号两边不能有空符号"},onerror:"手机号长度不合要求,请确认"})
                .regexValidator({regexp:"num",datatype:"enum",onerror:"手机号格式不正确"});

        $("#phone").formValidator({empty:true,onshow:"请输入固定电话号",onfocus:"请输入固定电话号",oncorrect:"固定电话号输入合法"})
                .inputValidator({min:11,onerror:"固定电话号长度不合要求,请确认"})
                .regexValidator({regexp:"num",datatype:"enum",onerror:"固定电话号格式不正确"});

        $("#qq").formValidator({onshow:"请输入QQ号",onfocus:"请输入QQ号",oncorrect:"QQ号输入合法"})
                .inputValidator({min:5,empty:{leftempty:false,rightempty:false,emptyerror:"QQ号两边不能有空符号"},onerror:"QQ号长度不合要求,请确认"})
                .regexValidator({regexp:"num",datatype:"enum",onerror:"QQ号格式不正确"});

        $("#age").formValidator({onshow:"请输入年龄",onfocus:"请输入年龄",oncorrect:"年龄输入合法"})
                .inputValidator({min:2,empty:{leftempty:false,rightempty:false,emptyerror:"年龄两边不能有空符号"},onerror:"年龄长度不合要求,请确认"})
                .regexValidator({regexp:"num",datatype:"enum",onerror:"年龄格式不正确"});

        $("#credential_type").change(function(){
            if($("#credential_type").val()=="0"){
                $("#credential_no").attr("disabled","true");
                $("#credential_no").removeAttr("class");
            }else{
                $("#credential_no").removeAttr("disabled");
            }
        });
    });
</script>
      <?php      if(api_get_setting ( 'lm_switch' ) == 'true'){
                ?>
  <style>
.m-moclist .nav .u-categ .navitm.it a:hover{
	color:#357CD2;
	background:#fff;
} 
.m-moclist .nav .u-categ .navitm.it.course-mess:hover{
    border-right-color: #357CD2;
}
.m-moclist .nav .u-categ .navitm.it.cur:hover .f-f1:hover{
background:#357CD2;
color:#fff;
}
.m-moclist .nav .u-categ .navitm.it.cur:hover .i-mc a:hover{
    color:#357CD2;
}
input[type=submit] {
background: #357CD2;
}
  </style>
      <?php   }   ?> 

<div class="clear"></div>
<div class="m-moclist">
    <div class="g-flow" id="j-find-main">
           <div class="b-30"></div>
          <!--左侧-->
   <div class="g-container f-cb">
        <div class="g-sd1 nav">
            <div class="m-sidebr" id="j-cates">
                <ul class="u-categ f-cb">
                    
                    <li class="navitm it f-f0 f-cb first cur" data-id="-1" data-name="用户中心" id="auto-id-D1Xl5FNIN6cSHqo0">
                         <a class="f-thide f-f1" style="background-color:<?=(api_get_setting ( 'lm_switch' ) == 'true' ?'#357CD2;':'#13a654;')?>;color:#FFF" title="用户中心">用户中心</a>
                    </li>
                    <li class="navitm it f-f0 f-cb haschildren" data-id="-1" data-name="我的足迹" id="auto-id-D1Xl5FNIN6cSHqo0">
                        <a class="f-thide f-f1" title="我的足迹" href="my_foot.php">我的足迹</a>
                    </li>
                     <li class="navitm it f-f0 f-cb haschildren" data-id="-1" data-name="选课记录" id="auto-id-D1Xl5FNIN6cSHqo0">
                        <a class="f-thide f-f1" title="选课记录" href="course_applied.php">选课记录</a>
                    </li>
                    <li class="navitm it f-f0 f-cb haschildren" data-id="-1" data-name="信息修改" id="auto-id-D1Xl5FNIN6cSHqo0">
                        <a class="f-thide f-f1" title="信息修改" href="user_profile.php" style="color:<?=(api_get_setting ( 'lm_switch' ) == 'true' ?'#357CD2;':'#13a654;')?>;font-weight:bold">信息修改</a>
                    </li>
                    <li class="navitm it f-f0 f-cb haschildren" data-id="-1" data-name="密码修改" id="auto-id-D1Xl5FNIN6cSHqo0">
                        <a class="f-thide f-f1" title="密码修改" href="user_center.php">密码修改</a>
                    </li>
                    <li class="navitm it f-f0 f-cb haschildren" data-id="-1" data-name="我的考勤" id="auto-id-D1Xl5FNIN6cSHqo0">
                        <a class="f-thide f-f1" title="我的考勤" href="work_attendance.php">我的考勤</a>
                    </li>
                    <li class="navitm it f-f0 f-cb haschildren" data-id="-1" data-name="站内信" id="auto-id-D1Xl5FNIN6cSHqo0">
                        <a class="f-thide f-f1" title="站内信" href="msg_view.php">站内信</a>
                    </li>      
                </ul>
                 <ul class="u-categ f-cb" style="margin-top:15px;">
                               <li class="navitm it f-f0 f-cb first cur"  data-id="-1" data-name="学习中心" id="auto-id-D1Xl5FNIN6cSHqo0">
                                   <a class="f-thide f-f1" style="background-color:<?=(api_get_setting ( 'lm_switch' ) == 'true' ?'#357CD2;':'#13a654;')?>;color:#FFF" title="学习中心">学习中心</a>
                               </li>
                               <?php  
                                $sql="select id,title from setup order by custom_number";
                                  $res=  api_sql_query_array($sql);
                                  foreach ($res as $value) {
                                      ?>
                            <li class="navitm it f-f0 f-cb haschildren"  data-id="-1" data-name="课程分类" id="auto-id-D1Xl5FNIN6cSHqo0">
                            <a class="f-thide f-f1" <?=$value['id']==$id?' style="color:green;font-weight:bold"':''?> title="<?=$value['title']?>" href="<?=URL_APPEND."portal/sp/learning_before.php?id=".$value['id']?>"><?=$value['title']?></a>
                                <div class="i-mc">
                                                    <div class="subitem" clstag="homepage|keycount|home2013|0601b">
                                                            <?php    
                                                            $sql1="select subclass from setup where id=".$value['id'];
                                                              $re1=  Database::getval($sql1);
                                                              $rews1=explode(',',$re1);
                                                                  $subclass1='';
                                                                  foreach ($rews1 as $v1) {
                                                                      if($v1!==''){
                                                                         $subclass1[]=$v1; 
                                                                      }
                                                                  }
                                                              $objCrsMng1=new CourseManager();//课程分类  对象。
                                                              $objCrsMng1->all_category_tree = array (); 
                                                              $category_tree1 = $objCrsMng1->get_all_categories_trees ( TRUE,$subclass1);
                                                              $i = 0;   $j = 0;   $o = array(); //标记循环变量， 数组 ;
                                                              foreach ( $category_tree1 as $category ) { ///父类循环
                                                                $url = "learning_before.php?id=".$value['id']."&category=" . $category ['id'];
                                                                  $cate_name = $category ['name'] . (($category_cnt [$category ['id']]) ? "&nbsp;(" . $category_cnt [$category ['id']] . ")" : "");
                                                                  if($category['parent_id']==0) {
                                                                  ?>
                                                                <a class="j-subit f-ib f-thide" href="<?=$url?>"><?=$cate_name?></a>
                                                                  <?php  if($i==3){$i=0;}
                                                                    }  
                                                                 }
                                                                  if(!$category_tree1){    
                                                                      echo "<p align='center'>没有相关课程分类，请联系课程管理员</p>";
                                                                  }
                                                                  ?>

                                                        </div>
                                                </div>

                                        </li>
                                        
                                       
                               <?php  }  ?>
                                <li class="navitm it f-f0 f-cb haschildren course-mess"  data-id="-1" data-name="课程表">
                                     <a class="f-thide f-f1" title="课程表" href="./syllabus.php">课程表</a>
                                </li>
                 </ul>
            </div>
            <div class="m-university u-categ f-cb" id="j-university">
                <div style="background-color:<?=(api_get_setting ( 'lm_switch' ) == 'true' ?'#357CD2;':'#13a654;')?>;color:#FFF">
                   <div class="bar f-cb">
                   <h3 class="f-thide f-f1">报告管理</h3>
                </div>
                <ul class="u-categ f-cb">
                    <li class="navitm it f-f0 f-cb haschildren" data-id="-1" data-name="我的实验报告" id="auto-id-D1Xl5FNIN6cSHqo0">
                        <a class="f-thide f-f1" title="我的实验报告" href="labs_report.php" >我的实验报告</a>
                    </li> 
                    <li class="navitm it f-f0 f-cb haschildren" data-id="-1" data-name="我的实验图片录像" id="auto-id-D1Xl5FNIN6cSHqo0">
                        <a class="f-thide f-f1" title="我的实验图片录像" href="course_snapshot_list.php" >我的实验图片录像</a>
                    </li>
                     <li class="navitm it f-f0 f-cb haschildren couse-mess" data-id="-1" data-name="系统公告" id="auto-id-D1Xl5FNIN6cSHqo0">
                         <a class="f-thide f-f1" title="系统公告" href="announcement.php" >系统公告</a>
                    </li>

                </ul>
                               
               </div>
                            
           </div>
       </div>
       
       
       
    <div class="g-mn1" > 
         <div class="g-mn1c m-cnt" style="display:block;">
    <div class="j-list lists" id="j-list"> 
        <div class="userContent">
            <form action="user_profile.php" method="post" name="theForm" enctype="multipart/form-data" id="theForm">
                <input type="hidden" name="action" value="save" />
                <input type="hidden" name="<?=$tooken_name;?>" value="tooken"/>
                <input name="MAX_FILE_SIZE" type="hidden" value="1048576" />
                <input name="language" type="hidden" value="simpl_chinese" />
                <div class="i-m">
                    <div class="la username">
                        <span class="as usera">用户名：</span><?=$user_data ['username']?>
                    </div>
                    <div class="la email">
                        <span class="as umail">邮&nbsp;&nbsp;&nbsp;件：</span><?php
                            echo form_input ( 'email', $user_data ['email'], 'id="email" class="inputText" style="width:180px"' );
                        ?><div id="emailTip" style="display: inline;"></div>
                    </div>
                    <div class="la sex">
                        <span class="as usex">性&nbsp;&nbsp;&nbsp;别：</span> <?php
                            echo form_radio ( 'sex', 1, $user_data ["sex"] == 1 ) . ' 男&nbsp;&nbsp;';
                            echo form_radio ( 'sex', 2, $user_data ["sex"] == 2 ) . ' 女&nbsp;&nbsp;';
                            echo form_radio ( 'sex', 0, $user_data ["sex"] == 0 ) . ' 保密';
                        ?>
                    </div>
                    <div class="la usernum">
                        <label for="usernum">编&nbsp;&nbsp;&nbsp;号：</label>
                        <?php
                        if (api_get_setting ( 'profile', 'officialcode' ) == 'true') {
                            echo form_input ( 'official_code', $user_data ['official_code'], 'id="email" class="inputText" style="width:180px"' );
                        } else {
                            echo $user_data ['official_code'];
                            echo form_hidden ( 'official_code', $user_data ['official_code'] );
                        }
                        ?>
                        <div id="emailTip" style="display: inline;"></div>
                    </div>
                    <div class="la chineseuser">
                        <label for="chineseuser"><span style="color: #F00; ">*&nbsp;</span>中文名：</label>
                        <?php
                        if (api_get_setting ( 'profile', 'name' ) == 'true') {
                            echo form_input ( 'firstname', $user_data ['firstname'], 'id="firstname" class="inputText" style="width:180px"' );
                        } else {
                            echo $user_data ['firstname'];
                            echo form_hidden ( 'firstname', $user_data ['firstname'] );
                        }
                        ?>
                        <div id="firstnameTip" style="display: inline;"></div>
                    </div>
                    <div class="la phone">
                        <label for="phone"><span style="color: #F00; ">*&nbsp;</span>手&nbsp;&nbsp;&nbsp;机：</label>
                        <?php
                            echo form_input ( 'mobile', $user_data ['mobile'], 'id="mobile" class="inputText" style="width:180px"' );
                        ?>
                        <div id="firstnameTip" style="display: inline;"></div>
                    </div>
                    <!--div class="la phone">
                        <label for="phone">固&nbsp;&nbsp;&nbsp;话：</label>
                        <?php 
                            //echo form_input ( 'phone', $user_data ['phone'], 'id="phone" class="inputText" style="width:180px"' ); 
                        ?>
                        <div id="firstnameTip" style="display: inline;"></div>
                    </div-->
                    <div class="save-button">
                        <input class="btn_querenbaocun" name="apply_change" id="save-buttons" type="submit"/>
                    </div>
                </div>
                <div class="i-mc">
                    <div class="imgcontent">
                        <div class="userimg">

                            <img <?=$img_attributes?> />
                            <div class="dopost">
                            <input class="inputText borderleft"  name="picture"   type="file" /><br />
                            <span class="borderleft"><input name="remove_picture"  type="checkbox" value="1" id="remove_picture" />
            				<label  for="remove_picture">移除图片</label></span><br />
                            <span class="hightred borderleft">（注意: 上传图片大小不要超过1M,格式仅限jpg,gif,png）</span>
							
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
    </div>
    </div>
    </div>
</div>
</div>
<?php 
include './inc/page_footer.php';
?>
</body>
<style type="text/css">
/*    body{
        min-height:80%;
    }*/
</style>
</html>
