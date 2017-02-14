<?php
$cidReset = true;
include_once ("inc/app.inc.php");
include_once ("../../main/inc/global.inc.php");
include_once ('../../main/assignment/assignment.lib.php');
include_once (api_get_path ( LIBRARY_PATH ) . 'fileDisplay.lib.php');
include_once (api_get_path ( LIBRARY_PATH ) . 'fileManage.lib.php');
include_once (api_get_path ( LIBRARY_PATH ) . 'document.lib.php');
include_once (api_get_path ( LIBRARY_PATH ) . 'tablesort.lib.php');
include_once (api_get_path ( LIBRARY_PATH ) . "export.lib.inc.php");
include_once (api_get_path ( SYS_CODE_PATH ) . 'document/document.inc.php');

if(!api_get_user_id ()){
    header('Location:login.php');exit;
}
//需要传递的参数 课程编号   需要获得用户名。
$submit =  getgpc('submit','P');
$action = getgpc('action','G');
$b =  getgpc('b','G');
$code =  getgpc('cidReq','G');
$user = api_get_user_name ();//用户名称
$url = "report_test.php";
$old_url = basename($_SERVER['HTTP_REFERER']);
$url_old_u = $_SESSION['old_url'];
if($url_old_u == NULL){
    $_SESSION['old_url']=$old_url;
}else{
    $old_url=$_SESSION['old_url'];
}

$htmlHeadXtra [] = import_assets ( "yui/tabview/assets/skins/sam/tabview.css", api_get_path ( WEB_JS_PATH ) );
$htmlHeadXtra [] = '<script type="text/javascript">$(document).ready( function() {$("body").addClass("yui-skin-sam");});</script>';
$htmlHeadXtra [] = Display::display_thickbox ();

include_once ("inc/page_header_report.php");    //导航条

if($code){
    $sql_class="select `title` from `course` where `code` = '$code' ";
    $class=  Database::getval($sql_class,__FILE__, __LINE__ );  //课程名称   
    $sql="select `id` ,`purpose`,`equipment`,`content`,`result`,`analysis` from `report` where `user`='".$user."' and  `code`='".$class."'";
    $result=Database::fetch_one_row($sql,__FILE__,__LINE__);
    if($result){
        $id=$result['id'];
        $purpose=$result['purpose'];
        $equipment=$result['equipment'];
        $content=$result['content'];
        $res_result=$result['result'];
        $analysis=$result['analysis'];
    }
}


//返回实验报告管理
if($_POST['test_a']=='acb'){
    tb_close ( $old_url );
}

//报告的id
if(!empty($submit)&&isset($submit)){
    $user = getgpc('name','P');
    $code = getgpc('class','P');
    $report_name = $user.'_'.$code;
    $sql="select `id` from `report` where `user`='".$user."' and  `code`='".$code."'";
    $id=Database::getval($sql,__FILE__,__LINE__);
    if(empty($id)&&($_POST['aaaa']!='0000'))
    {
        $sql="insert into `report`(`report_name`,`user`,`code`,`type`)value('$report_name','$user','$code','1')";
        api_sql_query ( $sql, __FILE__, __LINE__ );
        $sql="select `id` from `report` where `user`='".$user."' and  `code`='".$code."'";
        $id=Database::getval($sql,__FILE__,__LINE__);
    }
}

//写入数据表
if(!empty($submit)&&isset($submit)){
    $mark=  getgpc('mark','P');
    $cidReq= getgpc('code','P');
    $cidReq_a=getgpc('cidReq_a','P');
    $b=getgpc('b','P');
    if($cidReq_a=='0000'){
        echo "<script>alert(\"没有选择课程名称！\");</script>";
        echo "<script>window.location.href='report_test.php'</script>";
        
    }
    if($cidReq_a!=NULL){ $cidReq=$cidReq_a; }
    
    $purpose=getgpc('purpose','P');
    $equipment=getgpc('equipment','P');
    $content=getgpc('content','P');
    $result=getgpc('result','P');
    $analysis=getgpc('analysis','P');
    
    //写入实验目的及要求
    if($mark='purpose_a'&&!empty($purpose)){
        $sql="UPDATE `report` SET  `purpose`= '".$purpose."' ,`type`='1' WHERE `id`= '$id'" ;
        api_sql_query ( $sql, __FILE__, __LINE__ );
    }
    //实验设备环境及要求
    if($mark='equipment_a'&&!empty($equipment)){
               $sql="UPDATE `report` SET  `equipment`= '".$equipment."' ,`type`='1'  WHERE `id`= '$id'" ;
        api_sql_query ( $sql, __FILE__, __LINE__ );
    }
    //实验内容与步骤
    if($mark='content_a'&&!empty($content)){
        $sql="UPDATE `report` SET  `content`= '".$content."' ,`type`='1'  WHERE `id`= '$id'" ;
        api_sql_query ( $sql, __FILE__, __LINE__ );
    }
    //实验结果
    if($mark='result_a'&&!empty($result)){
        $sql="UPDATE `report` SET  `result`= '".$result."' ,`type`='1' WHERE `id`= '$id'" ;
        api_sql_query ( $sql, __FILE__, __LINE__ );
    }
    //实验分析与讨论
    if($mark='analysis_a'&&!empty($analysis)){
        $sql="UPDATE `report` SET  `analysis`= '".$analysis."' ,`type`='1' WHERE `id`= '$id'" ;
        api_sql_query ( $sql, __FILE__, __LINE__ );
    }
    
    
    echo "<script>window.location.href='report_test.php?cidReq=".$cidReq."&b=".$b."'</script>";
    
}

//编辑，查看获取参数
if(!empty($action)&&isset($action)){
    $action=getgp('action','G');
    $name=getgp('name','G');
    $class=getgp('class','G');
    $mark=getgp('mark','G');
}

if($_GET['key']!=NULL){
    $num=  getgpc('key','G');
    $d=null;
    $sql_content="select `content` from `report` where `user`='$user' and `code` ='$class' ";
    $content=  Database::getval($sql_content,__FILE__, __LINE__ );
    $a=explode(";",$content);
    array_splice($a,$num,1); 
    foreach($a as $key => $value){  
        if($d==NULL){
            $d=$value;
        }else{
            $d =$d.";".$value;
        }
    }
   
    $sql="UPDATE `report` SET  `content`= '".$d."' ,`type`='1'  WHERE `user`='$user' and `code` ='$class' " ;
    api_sql_query ( $sql, __FILE__, __LINE__ );
    echo "<script>window.location.href='report_test.php?cidReq=".$cidReq."&b=".$b."'</script>";
}
//提交报告
$act=  getgpc('act');
$cid=getgpc('cidReq','G');
if($act=='submit'){
        $sql_course="select `title` from `course` where `code` = '$cid' ";
       $c_title=  Database::getval($sql_course,__FILE__, __LINE__ );
       $sql="UPDATE `report` SET  `status`=1   WHERE `user`='".api_get_user_name ()."' and `code` ='{$c_title} ' " ;   
       $res = api_sql_query ( $sql, __FILE__, __LINE__ ); 

       if($res && api_get_setting ( 'lm_switch' ) == 'true' && api_get_setting ( 'lm_nmg' ) == 'true')
       {
           $sql="select * from `report`  WHERE `user`='".api_get_user_name ()."' and `code` ='{$c_title} ' " ;  
           $res = api_sql_query_array_assoc($sql);  
           $u_id = api_get_user_name();
         //发送post请求给对方
                  $data = array(
                  'type' => 2,
                  'data' => array(
                      'user_id' => $u_id,
                      'course' => $code,
                      'course_name' => $res[0]['code'],
                      'type' => '1',
                      'data' => array(
                          'purpose' => $res[0]['purpose'],
                          'lab_equipment' => $res[0]['equipment'],
                          'ex_name' => $res[0]['report_name'],
                          'ex_url' => $res[0]['content'],
                          'results' =>$res[0]['result'],
                          'ex_analysis' => $res[0]['analysis'],
                      ),
                  ),
              );
            $uri = "http://10.217.209.81:8080/ISMC/Ismc_kl_mocha/rss/sendResults.do";
             //$uri = "http://localhost/lms/portal/sp/post.php";
            $data_str = json_encode($data);
            $ch = curl_init();

            curl_setopt($ch, CURLOPT_URL, $uri);
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_HEADER, 0);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            $post_data = array(
                "json" => $data_str,
            );

            curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data);
            $data_sours = curl_exec($ch);
            $data_result = json_decode($data_sours, true);
            $return_arr=json_decode($data_result,true);
            $return=$return_arr['Return'];
            if($return!="1(Success)"){
                $sql_rollback="UPDATE `report` SET  `status`=0   WHERE `user`='".api_get_user_name ()."' and `code` ='{$c_title} ' " ;   
                $res = api_sql_query ( $sql_rollback, __FILE__, __LINE__ ); 
                 echo "数据同步失败，请重新提交或联系系统管理员";
                 exit;
             }

       }
        tb_close ( 'course_home.php?cidReq='.$code.'&action=introduction' );
}
else
{
    
}
?>
 <?php if(api_get_setting ( 'lm_switch' ) == 'true'){?>
  <style>
input[type=submit] {
background: #357cd2;
border: 1px solid #357cd2;
}
.add-sub a{   
    background:#357cd2;    
}
.add-sub  .tip{  
    color:#357cd2;
}
.j-top .lab-return:hover{
    background:#357cd2;    
    border:1px solid #357cd2;
}

  </style>
      <?php   }   ?>
<!--<link rel="stylesheet" type="text/css" href="css/lab-need.css">-->
<div class="clear"></div>
<div class="m-moclist">
    <div class="g-flow" id="j-find-main">
        
        <div class="g-mn1"> 
            <div class="g-mn1c m-cnt" style="display:block;margin-left:100px;background:#fff;padding-bottom:50px;">
                
                <div class="top f-cb j-top">
                     <?php if($b!=NULL){?>   
                    <span class="f-fc6 f-fs1" id="j-catTitle"><b>实验报告> <?= $class?></b></span>
                    <?php }elseif($class==NULL){  ?>
                       <span class="f-fc6 f-fs1" id="j-catTitle">实验报告  </span> 
                 <?php   }else{?>  
                     <span class="f-fc6 f-fs1" id="j-catTitle"><b>实验报告 > <?= $class?></b></span> 
                 <?php } ?>   
                    </h3>
<!--                    <span   style="width:70px;margin-left: 400px;border: 1px solid #ccc;font-weight: bold;" ><a href="report_test.php?cidReq=<?=$code?>&act=submit">提交报告</a></span>-->
                    <form action="<?= $url?>" method="post" style="display:inline-block;float:right;" >
                        <input type="hidden" name="test_a" value="acb">
                        <input type="hidden" name="old_url" value="<?= $old_url?>">
                        <input type="submit"  name="submit" value="返回" style="width:70px;" class="lab-return">
                    </form>
                    <div class="j-nav nav f-cb"> 
                        <div id="j-tab">  
                        </div>
                    </div>
                </div>
                
                 <div class="j-list lists" id="j-list"> 
                    <div class="u-content">
                        <h3 class="sub-simple u-course-title"></h3>
                        
                        <div class="lab-goal">   
                            <?php 
                                if($code==NULL){ ?>
                                    <div class="lab-goal-title">
                                        <div class="lab-left">选择课程名称：</div>
                                      <div class="lab-right">
                                
                                        <?php 
                                        $sql="SELECT `code` FROM `report` where `user`='".$user."'";
                                        $res= api_sql_query_array_assoc($sql);
                                        $sql_in=null;
                                        for($i=0;$i<count($res);$i++){
                                            $data=$res[$i]['code'];
                                            $sql_in .="'".$data."' , ";
                                        }
                                        $sql_in ='( '.rtrim(trim($sql_in),",").' )';
                                        $sql = "select `code`,`title` FROM  `course` ";
                                        if(!empty($sql_in)){
                                            $sql .= 'where  `title` not in '.$sql_in;
                                        }
                                            $res = api_sql_query ( $sql, __FILE__, __LINE__ );
                                            $vm= array ();
                                            while ( $vm = Database::fetch_row ( $res) ) {
                                                $c=$vm[0];
                                                $lab [$c] = $vm[1];
                                            }
                                        ?>
                                            <form action="<?= $url?>" method="post">
                                            <select name ="cidReq_a" style="height:30px;width:400px;border:1px solid #13a654;margin:10px;margin-left:5px;">
                                                 <option value="0000" selected>----请先选择课程名称----</option>
                                                <?php foreach($lab as $key => $value){ ?>
                                                <option value="<?= $key?>"><?= $value?></option>
                                                <?php }?>
                                            </select>
                                            <input type="hidden" name="aaaa" value="0000">
                                            <input type="hidden" name="a" value="a">
                                            <input type="hidden" name="old_url" value="<?= $old_url?>">
                                           <input type="submit"  name="submit" value="保存" class="lab-save">
                                             </form>
                                  </div> 
                                    </div>
                                            <?PHP 
                                            }
                            ?>
                            
                        </div>
                        
                        <div class="lab-goal">   
                            <?php 
                                if($code!=NULL){ ?>
<!--                                    <div class="lab-goal-title">
                                       <div class="lab-left">课程名称：</div>
                                       <div class="lab-right">
                                         <h3 class="lab-f16"><?= $class?></h3>
                                       </div>
                                    </div>-->
                            <?PHP            
                                }
                            ?>
                            
                            
                            <div class="lab-goal-title">
                               <div class="lab-left">一、</div>
                               <div class="lab-right">
                                 <h3 class="lab-f16">实验目的及要求</h3>
                               </div>
                            </div>
                            <div class="lab-goal-con">
                                <div class="lab-left"></div>
                                <div class="lab-right lab-con-right">
                                   <form action="<?= $url?>" method="post">
                                       <textarea name="purpose" ><?= $purpose?></textarea>
                                       <input type="hidden" name="mark" value="purpose_a">
                                       <input type="hidden" name="name" value="<?= $user?>">
                                       <input type="hidden" name="code" value="<?= $code?>">
                                       <input type="hidden" name="class" value="<?= $class?>">
                                       <input type="hidden" name="b" value="<?= $b?>">
                                       <input type="submit"  name="submit" value="保存">
                                   </form>
                                </div>
                            </div>
                        </div>
                        
                        <div class="lab-goal">   
                            <div class="lab-goal-title">
                                <div class="lab-left">二、</div>
                                <div class="lab-right">
                                  <h3 class="lab-f16">实验设备环境及要求</h3>
                                </div>
                            </div>
                            <div class="lab-goal-con">
                                <div class="lab-left"></div>
                                <div class="lab-right lab-con-right">
                                    <form action="<?= $url?>" method="post">
                                        <textarea name="equipment" ><?= $equipment?></textarea>
                                        <input type="hidden" name="mark" value="equipment_a">
                                        <input type="hidden" name="name" value="<?= $user?>">
                                        <input type="hidden" name="class" value="<?= $class?>">
                                        <input type="hidden" name="code" value="<?= $code?>">
                                        <input type="hidden" name="b" value="<?= $b?>">
                                        <input type="submit"  name="submit" value="保存">
                                    </form>
                                </div>
                            </div>
                        </div>

                        <div class="lab-goal">   
                            <div class="lab-goal-title">
                                <div class="lab-left">三、</div>
                                <div class="lab-right">
                                  <h3 class="lab-f16">试验内容与步骤 &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                      <!--<a href="report_content_add.php" title='添加试验内容与步骤'>添加</a>-->
                                  <?php    echo link_button ( 'exercise22.png', '添加试验内容与步骤', 'report_content_add.php?cidReq='.$code.'&b='.$b, '50%', '50%', FALSE );  ?>
                                  </h3>
                                </div>
                            </div>
                            <div class="lab-goal-con">
                                <div class="lab-left"></div>
           <!-- 有实验步骤时 -->
                                <div class="lab-right lab-con-right">
                                    <?php 
                                        $sql_content="select `content` from `report` where `user`='$user' and `code` ='$class' ";
                                        $content=  Database::getval($sql_content,__FILE__, __LINE__ );
                                        if($content!=NULL){
                                            $a=explode("^^",$content);
                                            $i=1;
                                            foreach($a as $key => $value){  
                                                $b=explode("^",$value);
                                                $c=$b['0'];
                                                //$c=$i++.".".$c;
                                                ?>
                                                <span class="no-step"><?= $c?>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href="report_test.php?cidReq=<?= $code?>&b=<?= $b?>&key=<?= $key?>">删除</a></span>
                                                <span class="no-step"> <img src="../../storage/snapshot/<?= $b['1']?>.jpg"></span>
                                        <?php } 
                                        }else{
                                            echo '<span class="no-step"><b>没有试验内容与步骤，请点击上方按钮添加。</b></span>';
                                        } ?>
                                </div> 
                            </div>
                        </div>
                        <div class="lab-goal">   
                            <div class="lab-goal-title">
                               <div class="lab-left">四、</div>
                               <div class="lab-right">
                                 <h3 class="lab-f16">实验结果</h3>
                               </div>
                            </div>
                            <div class="lab-goal-con">
                                <div class="lab-left"></div>
                                <div class="lab-right lab-con-right">
                                    <form action="<?= $url?>" method="post">
                                        <textarea name="result" ><?= $res_result?></textarea>
                                        <input type="hidden" name="mark" value="result_a">
                                        <input type="hidden" name="name" value="<?= $user?>">
                                        <input type="hidden" name="class" value="<?= $class?>">
                                        <input type="hidden" name="code" value="<?= $code?>">
                                        <input type="hidden" name="b" value="<?= $b?>">
                                        <input type="submit"  name="submit" value="保存">
                                    </form>
                                </div>
                            </div>
                        </div>
                        <div class="lab-goal"  style="border-bottom:1px dotted #ccc;">   
                            <div class="lab-goal-title">
                                <div class="lab-left">五、</div>
                                <div class="lab-right">
                                  <h3 class="lab-f16">实验分析与讨论</h3>
                                </div>
                            </div>
                            <div class="lab-goal-con">
                                <div class="lab-left"></div>
                                <div class="lab-right lab-con-right">
                                    <form action="<?= $url?>" method="post">
                                        <textarea name="analysis" ><?= $analysis?></textarea>
                                        <input type="hidden" name="mark" value="analysis_a">
                                        <input type="hidden" name="name" value="<?= $user?>">
                                        <input type="hidden" name="class" value="<?= $class?>">
                                        <input type="hidden" name="code" value="<?= $code?>">
                                        <input type="hidden" name="b" value="<?= $b?>">
                                        <input type="submit"  name="submit" value="保存">
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>  
                 <span class="add-sub">
                     <a href="report_test.php?cidReq=<?=$code?>&act=submit" style="color:#fff;">提交报告</a>
                     <span class="tip">（只能提交一次报告，请谨慎提交！）</span>
                 </span>
            </div>
            
        </div>
        
    </div>
<!--    <form action="<?= $url?>" method="post" class="lab-return">
        <input type="hidden" name="test_a" value="acb">
        <input type="hidden" name="old_url" value="<?= $old_url?>">
        <input type="submit"  name="submit" value="返回" >
    </form>-->
</div>
<style>
    
</style>
</body>
</html>
