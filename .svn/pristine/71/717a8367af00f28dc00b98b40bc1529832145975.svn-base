<?php
include_once ("../inc/global.inc.php");

$user_id = $_SESSION['_user']['user_id'];
$user_team_id = $_SESSION['_user']['teamId'];

$users_query = mysql_query('select user_id,username,mobile,address,picture_uri from user where teamId=' . $user_team_id);
while ($user_row = mysql_fetch_row($users_query)) {
    $user_rows[] = $user_row;
}

?>
<!DOCTYPE html>
<html>
    <head>
        <title>CTF首页</title>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <link rel="stylesheet" type="text/css" href="css/base.css">
        <link rel="stylesheet" type="text/css" href="css/media-style.css">
    </head>
    <body>
        <?php
        include 'left_header.php';
        ?>        
        <div class="g-mn1">
            <div class="g-mn1c">
                <!--题目-->
                <div class="j-list lists">
                    <div> 
                        <div>
                            <a href="team_match.php"><input type="submit" value="战队成绩" class="inputSubmit"style="border: 0 none;height: 25px;line-height: 22px;width: 70px;color: #FFFFFF;margin-left: 10px;vertical-align: middle;cursor: pointer;font-weight: bold;background-color: #0B7933;border-radius: 5px;"></a>
                            <a noclick="return false;">
                                <h3 class="b-title-match b-title-2" style="color:#75B7E6;">
<?php 
                             echo $isadmin===true ?
                                 '<input type="submit" value="战队管理" class="inputSubmit"style="border: 0 none;height: 25px;line-height: 22px;width: 70px;color: #FFFFFF;margin-left: 10px;vertical-align: middle;cursor: pointer;font-weight: bold;background-color: #0B7933;border-radius: 5px;">'
                                 :
                                 '<input type="submit" value="战队信息" class="inputSubmit"style="border: 0 none;height: 25px;line-height: 22px;width: 70px;color: #FFFFFF;margin-left: 10px;vertical-align: middle;cursor: pointer;font-weight: bold;background-color: #0B7933;border-radius: 5px;">'
                             ;
?>                                                                         
                                </h3>
                            </a>
                        </div>
                        <ul class="ul-tip">
                            
<?php
                          echo $isadmin===true ? ' <div id="del_fun">解散战队</div>' : '<div id="del_this">退出战队</div>';
?>                                
                            
                        </ul>
                        <div style="border-bottom: 2px solid #808080;width:100%;margin-top:3px;"></div>
                    <div class="b-15"></div>
                    <div class="b-course" id="b-scroll">
                        <table class="manage-table" cellspacing="0" cellpadding="0">
                            <tr>
                                <th width="20%">用户名</td>
                                <th width="10%">&nbsp;</td>
                                <th width="10%">职务</td>
                                <th width="20%">手机号</td>
                                <th width="20%">单位/学校</td>
<?php
                   if($team_admin[0] === $user_id){
                       echo '<th width="20%">操作</th>';
                   }
?>                               
                            </tr>  
<?php
                    foreach($user_rows as $user_k => $user_v){
?>
                            <tr id="tr<?=$user_v[0];?>">
                                <td><?=$user_v[1];?></td>
                                <td>
<?php
                      $userpath=api_get_path ( WEB_PATH ) . 'storage/users_picture/';
                      $user_image=$user_v[4] ? $userpath.$user_v : URL_APPEND."portal/sp/images/user-small.jpg";
?> 
                                  <img src="<?=$user_image;?>"  width="28px" height="28px" />  
                                </td>
                                <td>
<?php
                               if($team_admin[0] === $user_v[0]){
                                   echo '队长';
                               }else{
                                   echo '队员';
                               }
?>
                                </td>
                                <td><?=$user_v[2];?></td>
                                <td><?=$user_v[3];?></td>
<?php
                               if($team_admin[0] === $user_id && $team_admin[0] !== $user_v[0]){
                                   echo '<td><img style="cursor:pointer;" onclick="ti_func('.$user_v[0].')" src="'.URL_APPEND.'main/ctf/images/zuqiu.png"/></td>';
                               }
?>                                
                            </tr>
                       
<?php
                    }
?>                     
                        </table>        
                    </div>
                    </div>   
                </div>
            </div>
        </div>   

    </dv>
</section>
</body>
</html>
<script type="text/javascript" src="<?=URL_APPEND;?>themes/js/fly/fly.js"></script>
<script type="text/javascript" src="<?=URL_APPEND;?>themes/js/fly/requestAnimationFrame.js"></script>
<script type="text/javascript">
        function ti_func(id,one){
            one=one ? one : 'casual';
            var con_query;
            if(!isNaN(id) && one === 'casual'){
                con_query=confirm('确定从本组中删除该用户');
                var pagex=event.pageX;
                var pagey=event.pageY;
            }else{
                con_query=true;
            }
            if(con_query){
                $.ajax({
                   url:'del_team.php',
                 type:'get',
              dataType:'html',
              data:'teamid='+id+'&one='+one,
            success:function(er){
                  if(er === 'ok'){
                      addProduct(pagex,pagey,id);
                  }else if(er ===  'err'){
                      alert('操作失败，请稍候再试!');
                  }else if(er === 'location'){
                      location.href='<?=URL_APPEND?>main/ctf/index_2.php';
                  }
             }
                });
            }
        }       
        function addProduct(pagex,pagey,id){
             $("#tr"+id+">td:last>img").css('display','none');
            flyer = $('<img class="u-flyer" src="images/zuqiu.png"/>');
          flyer.fly({
            start: {
              left: pagex,
              top: pagey
            },
            end: {
              left: 2000,
              top: 100 
            },
                onEnd:function(){
                   flyer.remove();
                   $("#tr"+id).remove();
                }
          });
        }
        $(function(){
<?php
             if($isadmin === true){
?>            
                $("#del_fun").click(function(){
                    $("#del_fun").css({background:"#FF0033", color:"#FFFFFF"});
                    if(confirm('确定解散战队？')){
                       ti_func('all');
                    }else{
                      $("#del_fun").css({background:"#FFFFFF", color:"#FF0033"});   
                    }
                });
<?php
             }else{
?>
           $("#del_this").click(function(){
               $("#del_this").css({background:"#FF0033", color:"#FFFFFF"});
               if(confirm('确定退出战队？')){
                    ti_func(<?=$user_id?>,'one');
               }else{
                    $("#del_this").css({background:"#FFFFFF", color:"#FF0033"});   
               }
           });
<?php        
             }
?>            
        });
</script>
