<?php
$language_file = array ('courses','admin');
$cidReset=true;
include_once ("../../inc/global.inc.php");
$this_section=SECTION_PLATFORM_ADMIN;
$countnum=mysql_fetch_row(mysql_query('select count(id) from vmdisk_log'));
if($countnum['0'] > 2000){
    $delnum=$countnum['0']-2000;
    if($delnim < 10000){
       mysql_query('delete from vmdisk_log order by id desc limit '.$delnum);
     }else{
       mysql_query('delete from vmdisk_log order by id desc limit 10000');  
     }
}
api_protect_admin_script ();
if (! isRoot ()) api_not_allowed ();
$display_admin_menushortcuts=(api_get_setting('display_admin_menushortcuts')=='true'?TRUE:FALSE);
include_once(api_get_path(SYS_CODE_PATH).'course/course.inc.php');
require_once (api_get_path(LIBRARY_PATH).'course.lib.php');
require_once (api_get_path ( LIBRARY_PATH ) . 'dept.lib.inc.php');
Display::display_header();
$vm_log = Database::get_main_table ( vmdisk_log);  

function get_sqlwhere() { 
    $sql_where = "";
    if (is_not_blank ( $_POST['the_week'] )) { 
        $oneweek=date("Y-m-d H:i:s",time()-3600*24*7);
        $sql_where .= " and lesson_id !=0 and start_time < '".date("Y-m-d H:i:s",time())."'  and  start_time >= '".$oneweek."'";
    }else if(is_not_blank ( $_POST['the_month'] )){
        $oneweek=date("Y-m-d H:i:s",time()-3600*24*30);
        $sql_where .= " and lesson_id !=0 and start_time < '".date("Y-m-d H:i:s",time())."'  and  start_time >= '".$oneweek."'";
    }
    
    if ($sql_where)  return  $sql_where ;
    else   return "";
}

$sql_where=get_sqlwhere();
//data-one 
$sql="select  count(lesson_id) as lesson_num,lesson_id  from {$vm_log} as t1 INNER JOIN course as t2 where t1.lesson_id=t2.code";
if($sql_where){
    $sql.=$sql_where;
}
$sql.=" group  by  lesson_id  order by  count(lesson_id) desc";
$ress= api_sql_query_array_assoc($sql);
foreach ($ress as $key=>$value){
    $ctitle=DATABASE::getval("select  title  from  course where  code=".$value['lesson_id']);
     $ress[$key]['lesson_id']=  str_replace('-', '<br/>', $ctitle); 
     if($key>9){
          $other_num+=$value['lesson_num'];
     }
}
//data-two
$sql="select start_time from {$vm_log} as t1 INNER JOIN course as t2 where t1.lesson_id=t2.code";
if($sql_where){
    $sql.=$sql_where;
}
$ress1= api_sql_query_array_assoc($sql);
foreach ($ress1 as $k=>$val){
    $v=end(explode(" ", $val['start_time']));
    $va=explode(":", $v);
    $ress1[$k]['start_time']=$va[0];
}  
$a=0;$b=0;$c=0;$d=0;$e=0;$f=0;$g=0;$h=0;$i=0;$j=0;$k=0;$l=0;
foreach ($ress1 as $value1){
    if($value1['start_time']>=0 && $value1['start_time']<2){
        $a+=1;
    }else if($value1['start_time']>=2 && $value1['start_time']<4){
        $b+=1;
    }else if($value1['start_time']>=4 && $value1['start_time']<6){
        $c+=1;
    }else if($value1['start_time']>=6 && $value1['start_time']<8){
        $d+=1;
    }else if($value1['start_time']>=8 && $value1['start_time']<10){
        $e+=1;
    }else if($value1['start_time']>=10 && $value1['start_time']<12){
        $f+=1;
    }else if($value1['start_time']>=12 && $value1['start_time']<14){
        $g+=1;
    }else if($value1['start_time']>=14 && $value1['start_time']<16){
        $h+=1;
    }else if($value1['start_time']>=16 && $value1['start_time']<18){
        $i+=1;
    }else if($value1['start_time']>=18 && $value1['start_time']<20){
        $j+=1;
    }else if($value1['start_time']>=20 && $value1['start_time']<22){
        $k+=1;
    }else if($value1['start_time']>=22 && $value1['start_time']<=23){
        $l+=1;
    }  
}
 

?>
 
<section id="main" class="column" style="width:98%;">
    <h4 class="page-mark" style="width:98%;margin:20px 1% 0 2%;">当前位置：<a href="<?=URL_APPEDND;?>/main/admin/index.php">平台首页</a> &gt; <a href="<?=URL_APPEDND;?>/main/admin/log/logging_list.php">日志管理</a> &gt; 虚拟机日志统计
            <span style="float: right;">
            <form action="" method="post" >
                <input type="hidden" name="the_week" value="theweek">
                <input type="submit"  value="本周">
            </form>
            </span>
            <span style="float: right;">
            <form action="" method="post" >
                <input type="hidden" name="the_month" value="themonth">
                <input type="submit"  value="本月">
            </form>
            </span>
    </h4>
    <div style="width:98%;height:450px;margin:20px 1% 0 2%;">
        <div id="container_one" style="border: 1px #008000 dashed;width:50%;height: 100%;float: left;"><?php  if(!$ress) echo "无相关数据！！";?></div>
        <div id="container_two" style="border: 1px #008000 dashed;width:49%;height: 100%;float: left;"><?php  if(!$ress1) echo "无相关数据！！";?></div>
    </div>
    <article class="module width_full hidden" style="width:98%;margin:20px 1% 0 2%;">
        <div id="container" style="min-width:700px;min-height: 400px;"></div>
    </article>
</section>
<script type="text/javascript" src="../../../themes/js/jquery-1.5.2.min.js"></script>
<script src="../../../themes/js/highcharts.js"></script>
<script src="../../../themes/js/highcharts-3d.js"></script> 
<script src="../../../themes/js/exporting.js"></script>
<script type="text/javascript">
$(function () {
    $('#container_one').highcharts({
        chart: {
            type: 'pie',
            options3d: {
				enabled: true,
                alpha: 45,
                beta: 0
            }
        },
        title: {
            text: '前10名学习用户最多的课程统计图'
        },
        tooltip: {
            pointFormat: '{series.name}: <b>{point.percentage:.1f}%</b>'
        },
        plotOptions: {
            pie: {
                allowPointSelect: true,
                cursor: 'pointer',
                depth: 35,
                dataLabels: {
                    enabled: true,
                    format: '{point.name}'
                }
            },
            series: {
                events: {
                    click: function(event) { 
                     $.ajax({
                         type:'POST',
                         url:'vm_log_ajax.php',
                         data:'action=show&e='+event.point.name+'&theweek=<?=$_POST['the_week']?>&themonth=<?=$_POST['the_month']?>',
                         dateType:'json',
                         success:function(data){   
                              var myobj=eval(data); 
                              var users=new Array();
                              var nums=new Array();
                              for(var i=0;i<myobj.length;i++){  
                                users[i]=myobj[i].user_id;
                                nums[i]=parseInt(myobj[i].user_num);
                                } 
                                $('#container').highcharts({
                                  chart: {
                                      type: 'column',
                                      margin: 75,
                                      options3d: {
                                          enabled: true,
                                          alpha: 15,
                                          beta: 0,
                                          depth: 70
                                      }
                                  },
                                  title: {
                                      text: '部分虚拟机日志统计详细信息折线图'
                                  },
                                  subtitle: {
                                      text: ' '
                                  },
                                  plotOptions: {
                                      column: {
                                          depth: 25
                                      }
                                  },
                                  xAxis: {
                                      categories: users
                                  },
                                  yAxis: {
                                      opposite: true
                                  },
                                  series: [{
                                      name: '本课程用户学习次数',
                                      data: nums
                                  }]
                              
                              }); 
                         }
                      });
                    }
                } 
            }
        },
        series: [{
            type: 'pie',
            name: '比例',
            data: [
                {
                    name: '<?=$ress[0]["lesson_id"]?>',
                    y: <?=$ress[0]["lesson_num"]?>,
                    sliced: true,
                    selected: true
                },
                ['<?=$ress[1]["lesson_id"]?>', <?=$ress[1]["lesson_num"]?> ],
                ['<?=$ress[2]["lesson_id"]?>', <?=$ress[2]["lesson_num"]?> ],
                ['<?=$ress[3]["lesson_id"]?>', <?=$ress[3]["lesson_num"]?> ],
                ['<?=$ress[4]["lesson_id"]?>', <?=$ress[4]["lesson_num"]?> ],
                ['<?=$ress[5]["lesson_id"]?>', <?=$ress[5]["lesson_num"]?> ],
                ['<?=$ress[6]["lesson_id"]?>', <?=$ress[6]["lesson_num"]?> ],
                ['<?=$ress[7]["lesson_id"]?>', <?=$ress[7]["lesson_num"]?> ],
                ['<?=$ress[8]["lesson_id"]?>', <?=$ress[8]["lesson_num"]?> ],
                ['<?=$ress[9]["lesson_id"]?>', <?=$ress[9]["lesson_num"]?> ],
                ['其它', <?=$other_num?> ]
            ]
        }]
    });

        var time_num_arr=new Array();
        time_num_arr=[  ['00时--02时',<?=$a?>],
                        ['02时--04时',<?=$b?>],
                        ['04时--06时',<?=$c?>],
                        ['06时--08时',<?=$d?>],
                        ['08时--10时',<?=$e?>],
                        ['10时--12时',<?=$f?>],
                        ['12时--14时',<?=$g?>],
                        ['14时--16时',<?=$h?>],
                        ['16时--18时',<?=$i?>],
                        ['18时--20时',<?=$j?>],
                        ['20时--22时',<?=$k?>],
                        ['22时--24时',<?=$l?>]
                     ];
    var  time_num_arrs=new Array();
    var i=0;
   $.each(time_num_arr,function(n,value) {
         if(value=="00时--02时,0"  || value=="02时--04时,0" || value=="04时--06时,0" || value=="06时--08时,0" || value=="08时--10时,0" || value=="10时--12时,0" || value=="12时--14时,0" || value=="14时--16时,0" || value=="16时--18时,0" || value=="18时--20时,0" || value=="20时--22时,0" || value=="22时--24时,0"){
            
         }else{  
             time_num_arrs[i]= value;    
             i++; 
         } 
    });
 
      $('#container_two').highcharts({
        chart: {
            type: 'pie',
            options3d: {
				enabled: true,
                alpha: 45,
                beta: 0
            }
        },
        title: {
            text: '一天中各时间段内学习分布统计图'
        },
        tooltip: {
            pointFormat: '{series.name}: <b>{point.percentage:.1f}%</b>'
        },
        plotOptions: {
            pie: {
                allowPointSelect: true,
                cursor: 'pointer',
                depth: 35,
                dataLabels: {
                    enabled: true,
                    format: '{point.name}'
                }
            },
            series: {   
                events: {
                    click: function(event) {  
                    $.ajax({
                         type:'POST',
                         url:'vm_log_ajax.php',
                         data:'action=shows&es='+event.point.name+'&theweek=<?=$_POST['the_week']?>&themonth=<?=$_POST['the_month']?>',
                         dateType:'json',
                         success:function(datas){    
                            var myobjs=eval(datas); 
                            var lessons=new Array();
                            var unum=new Array();
                            for(var i=0;i<myobjs.length;i++){  
                              lessons[i]=myobjs[i].lesson_id;
                              unum[i]=parseInt(myobjs[i].user_num);
                            }  
                            $('#container').highcharts({
                                chart: {
                                    type: 'column',
                                    margin: 75,
                                    options3d: {
                                        enabled: true,
                                        alpha: 15,
                                        beta: 0,
                                        depth: 70
                                    }
                                },
                                title: {
                                    text: '部分虚拟机日志统计详细信息折线图'
                                },
                                subtitle: {
                                    text: ' '
                                },
                                plotOptions: {
                                    column: {
                                        depth: 25
                                    }
                                },
                                xAxis: {
                                    categories:lessons
                                },
                                yAxis: {
                                    opposite: true
                                },
                                series: [{
                                    name: '人次',
                                    data: unum
                                }]
                            }); 
                         }
                      });
                    }
                } 
            } 
        },
        series: [{
            type: 'pie',
            name: 'Browser share',
            data:
              time_num_arrs      
        }]
    });
});
</script>
</body>
</html>
