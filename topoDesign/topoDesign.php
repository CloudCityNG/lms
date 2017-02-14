<?php
header("content-type:text/html;charset=utf-8");
include_once ("../main/inc/global.inc.php");
include_once ("../main/inc/app.inc.php");
require_once (api_get_path ( LIBRARY_PATH ) . 'database.lib.php');
require_once (api_get_path ( LIBRARY_PATH ) . 'dept.lib.inc.php');
$language_file = 'admin';
$cidReset = true;
$title=api_get_setting('siteName');

//$url="http://".$_SERVER['HTTP_HOST'].URL_APPEDND."/main/cloud/cloudconnectdevice.php";//打开控制台路径

$action=getgpc("action","G");
//echo $action."&nbsp;".$_GET['id']."&nbsp;";
$topoid=getgpc("id","G");
$topoid=(int)$topoid;
if( $topoid!==''){
    $netmap_data=DATABASE::fetch_one_row("select `id`,`name`,`netmap`,`diagram` from `labs_labs`  where `id`='".$topoid."'",__FILE__,__LINE__);
     
     $toponame=trim($netmap_data['name']);
     $netmap=$netmap_data['netmap']; 
     $diagram=$netmap_data['diagram'];
}else{
     $html = "访问被拒绝：非法操作。";
     Display::display_error_message ( $html, false );
     exit();
}
//echo $toponame."&nbsp;&nbsp;&nbsp;&nbsp;".$netmap."<hr>";exit();
if(isset($_GET['action']) && $action=='design' && $topoid!==''){
     
    $rep1=array('}','"','px',' ');
    $rep2=array('','','','');
    
    if($netmap){ 
//        echo $netmap."<hr>";
        $net_arr = explode('","', $netmap);
        foreach($net_arr as $net_value){
           $net_var1 = explode('","', $net_value);
           $net_var2 = explode(' ', $net_var1[0]);
           foreach($net_var2 as $ss){
               $net_var3 = explode(':', $ss);
               $nodeIdArr[]=str_replace($rep1, $rep2, $net_var3[0]);
           }
        }
        $diagram = explode(';', $diagram);
        $nodeIdArrs=array_merge($diagram,$nodeIdArr);
        $deviceArr1=array_unique($nodeIdArrs);
        $deviceArr=array_filter($deviceArr1);
        $DD=array();
        foreach($deviceArr as $sda){
            $DD[]=$sda;
        } 
        $deviceArr_count=count($DD);
        if($deviceArr_count){
            $sqll='(';
            $deviceArr_count1=$deviceArr_count - 1;
            for($f=0;$f<$deviceArr_count;$f++){
                
                if($deviceArr_count=='1'){
                    $sqll.=' `id`='.$DD[$f];
                }else{
                    if($DD[$f]){
                        if($f==$deviceArr_count1){
                            $sqll.=' `id`='.$DD[$f];
                        }else{
                            $sqll.=' `id`='.$DD[$f].' OR ';
                        }
                    }
                }
            }
            $sqll.=')'; 
        }
        
        $sql_device="select * from `labs_devices` where `lab_id`='".$toponame."' ";
        if($DD[0]){
            $sql_device.=" AND ".$sqll;
        }
//        echo $sql_device;
        $device_data=api_sql_query_array_assoc($sql_device,__FILE__,__LINE__);
        $device_str='{"nodes":[';
        $offset_top=='';
        $offset_left=='';
        $desc_left=='';
        $desc_top=='';
        $device_counts=count($device_data);
        $device_end_id=$device_counts-1;
         
        for($i=0;$i<$device_counts;$i++){
            $nodeId=$device_data[$i]['id'];
            $dname=$device_data[$i]['name'];
            $picture=$device_data[$i]['picture'];
            $device_model=$device_data[$i]['ios'];
            $offset_top=$device_data[$i]['top']."px";
            $offset_left=$device_data[$i]['left']."px";
            $sllot=$device_data[$i]['slot'];
            $desc_info=$device_data[$i]['desc'];
            $desc_left="-40";
            $desc_top="60";
            $slots=explode(';',$sllot);array_pop( $slots);
	    
            $interface="";
            if(in_array($nodeId, $deviceArr)){
                if($default[$i]['id']==$device){
                       $key1=0;
                       foreach($slots as $kk=> $mod){
			      if($mod=='NO'){
				      }else{ 
                           $sqls="select `size`,`interface_type` from `labs_mod` where `mod_name`='".$mod."'";
                           $mod_data = api_sql_query_array_assoc ( $sqls, __FILE__, __LINE__ );
                           $size=$mod_data[0]['size'];

                           $interface_type=$mod_data[0]['interface_type']; 
                           $interface_numbers=explode(',',$size);
                           $interface_number=$interface_numbers[1];
                           $interface_nums=$interface_nums+$interface_number+1;

                           if($interface_type=="串口"){
                              for($s=0;$s<=$interface_number;$s++){
				      
                                  $interface.='"'.$key1.'":"S'.$kk.'/'.$s.'",';
				      $key1=$key1+1;
				      
                              }
                           }else{
                              for($e=0;$e<=$interface_number;$e++){
				      
                                  $interface.='"'.$key1.'":"E'.$kk.'/'.$e.'",';
                                  $key1=$key1+1;
				      
                              } 
                           }

                           $interface_type="";
				      }
                       }
                   }
               }
               $interfaces= rtrim($interface,",");
               if($device_model!==''){
                   $device_models="(".$device_model.")";
               }
            if($i==$device_end_id){
            //最后一个没有逗号
                $device_str.='{"nodeId":"'.$nodeId.'","nodeName":"'.$dname.$device_models.'","nodeType":"'.$device_model.'","model":"'.$picture.'","offset":{"left":"'.$offset_left.'","top":"'.$offset_top.'"},"nodeDesc":{"desc":"'.$desc_info.'","left":'.$desc_left.',"top":'.$desc_top.'},"portsnumber":"'.$interface_nums.'","interface":{'.$interfaces.'}}';
            }else{
                $device_str.= '{"nodeId":"'.$nodeId.'","nodeName":"'.$dname.$device_models.'","nodeType":"'.$device_model.'","model":"'.$picture.'","offset":{"left":"'.$offset_left.'","top":"'.$offset_top.'"},"nodeDesc":{"desc":"'.$desc_info.'","left":'.$desc_left.',"top":'.$desc_top.'},"portsnumber":"'.$interface_nums.'","interface":{'.$interfaces.'}},';
            }
            $interface_nums=0;
     }
//     $device_str.='],"netMap":["'.$netmap.'"]}';
     $device_str.='],"netMap":['.$netmap.']}';
        
    }else{
        $sql_device="select * from `labs_devices` where `lab_id`='".$toponame."' ";
        $device_data=api_sql_query_array_assoc($sql_device,__FILE__,__LINE__);
        $device_str='{"nodes":[';
        $offset_top=='';
        $offset_left=='';
        $desc_left=='';
        $desc_top=='';
        $device_counts=count($device_data);
        $device_end_id=$device_counts-1;
        
            for($i=0;$i<$device_counts;$i++){
                $nodeId=$device_data[$i]['id'];
                $dname=$device_data[$i]['name'];
                $picture=$device_data[$i]['picture'];
                $device_model=$device_data[$i]['ios'];
                $offset_top=$device_data[$i]['top']."px";
                $offset_left=$device_data[$i]['left']."px";
                $sllot=$device_data[$i]['slot'];
                $desc_info=$device_data[$i]['vmdisks'];
                $desc_left="-40";
                $desc_top="60";
                $slots=explode(';',$sllot);array_pop( $slots);
                $interface="";
                if($default[$i]['id']==$device){
                        $key1=0;
                        foreach($slots as $kk=> $mod){
				if($mod=='NO'){
				      }else{ 
                            $sqls="select `size`,`interface_type` from `labs_mod` where `mod_name`='".$mod."'";
                            $mod_data = api_sql_query_array_assoc ( $sqls, __FILE__, __LINE__ );
                            $size=$mod_data[0]['size'];

                            $interface_type=$mod_data[0]['interface_type']; 
                            $interface_numbers=explode(',',$size);
                            $interface_number=$interface_numbers[1];
                            $interface_nums=$interface_nums+$interface_number+1;

                            if($interface_type=="串口"){
                               for($s=0;$s<=$interface_number;$s++){
                                   $interface.='"'.$key1.'":"S'.$kk.'/'.$s.'",';
                                   $key1=$key1+1;
                               }
                            }else{
                               for($e=0;$e<=$interface_number;$e++){
                                   $interface.='"'.$key1.'":"E'.$kk.'/'.$e.'",';
                                   $key1=$key1+1;
                               } 
                            }

                            $interface_type="";
				      }
                        }
                    }
                   $interfaces= rtrim($interface,",");
                   if($device_model!==''){
                       $device_models="(".$device_model.")";
                   }
                if($i==$device_end_id){
                //最后一个没有逗号
                    $device_str.='{"nodeId":"'.$nodeId.'","nodeName":"'.$dname.$device_models.'","nodeType":"'.$device_model.'","model":"'.$picture.'","offset":{"left":"'.$offset_left.'","top":"'.$offset_top.'"},"nodeDesc":{"desc":"'.$desc_info.'","left":'.$desc_left.',"top":'.$desc_top.'},"portsnumber":"'.$interface_nums.'","interface":{'.$interfaces.'}}';
                }else{
                    $device_str.= '{"nodeId":"'.$nodeId.'","nodeName":"'.$dname.$device_models.'","nodeType":"'.$device_model.'","model":"'.$picture.'","offset":{"left":"'.$offset_left.'","top":"'.$offset_top.'"},"nodeDesc":{"desc":"'.$desc_info.'","left":'.$desc_left.',"top":'.$desc_top.'},"portsnumber":"'.$interface_nums.'","interface":{'.$interfaces.'}},';
                }
                $interface_nums=0;
         }
         $device_str.='],"netMap":[]}';
    }
}else{
    tb_close();
}
//echo $device_str."<br>";exit;
?>
<!DOCTYPE HTML>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>拓扑设计</title>
    <meta name="keywords" content="IOU, IOL, Web Interface, CCIE"/>
    <meta name="description"
          content="IOU Web Interface gives you the flexibility you need to use Cisco IOU without understanding Linux OS."/>
    <link href="css/template_netmap.css" rel="stylesheet" type="text/css"/>
    <link href="css/jquery.contextMenu.css" rel="stylesheet" type="text/css"/>
    <link rel="stylesheet" media="all" type="text/css" href="css/jquery-impromptu.css"/>

    <style type="text/css">
        #main {width:100%; border: 0px solid graytext; }
        #sidebarContainer { height: 50px; margin-top:10px;border-bottom: 1px solid graytext;}
        #sidebarContainer span.title{ text-align: left;font-size: 18px;font-weight: bold;margin-left:10px; color:rgb(130, 51, 175)}
        #sidebarContainer a.action_botton{ height: 30px; float:right;margin-right:15px;font-size: 16px;  /*border: 1px solid green;*/  }
        #sidebarContainer div {margin-top: 5px;  }
        #graphContainer {   /*float: left;*/  width:100%;  height: 700px;  /*            margin-top: 100px;*/   }
        #sidebarContainer ul { list-style: none;padding: 0;}
        #sidebarContainer ul li { padding: 0 0 0 8px;  }
    </style>

    <script type='text/javascript' src='js/jquery-1.8.2.min.js'></script>
    <script type='text/javascript' src='js/jquery-ui-1.8.23.custom.min.js'></script>
    <script type='text/javascript' src='js/jquery.jsPlumb-1.3.14-all-min.js'></script>
    <script type="text/javascript" src="js/jquery.contextMenu.js"></script>
    <script type="text/javascript" src="js/HashMap.js"></script>
    <script type="text/javascript" src="js/jquery.jsIOUWebNetMap.js"></script>
    <script>
   
(function() {
    "use strict";
    var nodes = [ ],
        endpoint = {
            Anchor : "Continuous",
            //Connector : [ "Bezier", { curviness: 50 } ],
            Connector : [ "Straight" ],
            Endpoint : "Blank",
            //PaintStyle : { lineWidth : 10, strokeStyle : "#000000" },
            cssClass: "link"
        },
        netMap = new ArrayList(),
        devMapIndex = new HashMap(),
        currentNodeIndex = 0,
        sourcePort = "",
        targetPort = "",
        sourceNode = "",
        targetNode = "",
        usedPorts = new HashMap(),
        endpointOptions = { isSource: true, isTarget: true },
        prepare = function(elId) {
            //jsPlumbDemo.initHover(elId);
            return jsPlumb.addEndpoint(elId, endpoint);
        };

    function getDevIndex(type) {
        var index = devMapIndex.get(type);
        index = index || 0;
        index = parseInt(index, 0);
       // alert(type + " index: " + index);

        index = index + 1;
        devMapIndex.put(type, index);
        return index;
    }

 /**
     * 打开控制台
     */
//     function openConsole(nodesId) {
//          open("<?=$url?>?nodesId="+nodesId+"&gId=<?=$group_id?>&topo=<?=$topoid?>");
//    }

    /**
     *  <li> Mouse up listener
     * @param d
     */
    function addLiMouseUpListener(d) {
        var deviceObj = $(d);
        deviceObj.find("div a").bind("dblclick", function(e) {
            var aObj = $(this);
            var consoleId = aObj.attr("id");
            openConsole(consoleId);
        });
        $.contextMenu({
            selector: '.window',
            callback: function(key, options) {
                if (key === "delete") {
                    var devObj = $(this);
                    var devId = devObj.attr("id"), i;
                    for (i = 0; i < nodes.length; i++) {
                        if (devId === nodes[i]) {
                            nodes.splice(i, 1);
                        }
                    }
                    devObj.remove();
                }
            },
            items: {
                "delete": {name: "Delete", icon: "delete"}
            }
        });

        deviceObj.find("ul li").bind("mouseup", function() {
            targetNode = $(this).parent().parent().attr("id");
            targetPort = $(this).find("a").html();
            if (targetNode === sourceNode && sourcePort === targetPort) {
                $(".window ul").hide();
                return;
            }

            // Verify this node
            if (jsIOUWebNetMap.isUsedPort(targetNode + ":" + targetPort)) {
                sourceNode = "";
                sourcePort = "";
                alert("对不起，这个目标端口已经使用！");
                return;
            }

            console.log("starting build connection: " + targetPort + sourcePort);

            jsPlumb.connect({
                source: sourceNode,
                target: targetNode,
                //     paintStyle:{lineWidth:3,strokeStyle:'rgb(0,0,0)'},
                overlays: [
                    [ "Label", {label: sourcePort, location: 0.15, cssClass: "label"}],
                    [ "Label", {label: targetPort, location: 0.85, cssClass: "label"}]
                ]
            });

            jsIOUWebNetMap.hidePopWindows();
            jsIOUWebNetMap.resetCurrentPort();
        });

        deviceObj.bind("dblclick", function(e) {
//            e.preventDefault();
            var aObj = $(this);
            var consoleId = aObj.attr("id");
            openConsole(consoleId);
        });
    }

    /**
     * <image> event listener
     * @param imgDom
     */
    function imageListener(imgDom) {
        var currentNode =  $(imgDom).parent().attr("id"),
            ulDom =   $(imgDom).parent().find("ul");
        ulDom.show();     // show this router's popWin

            // set popWin used ports' color
        $.each(ulDom.find("li"), function(index, obj) {
            var aDom =    $(obj).find("a"),
                currentPort = aDom.html(),
                nodePort = currentNode + ":" +  currentPort;

            console.log("Port: " + nodePort + "  isUsed: " + jsIOUWebNetMap.isUsedPort(nodePort));

            if (jsIOUWebNetMap.isUsedPort(nodePort)) {
                console.log("used port add color");
                aDom.css("color", "green");
            } else {
                aDom.css("color", "black");
            }
        });   // end each
    }

    /**
     * <li> mouse down listener
     */
    function liMouseDownListener(li) {
        sourceNode = $(li).parent().parent().attr("id");
        sourcePort = $(li).find("a").html();
        console.log(sourceNode + ":" + sourcePort);
        if (jsIOUWebNetMap.isUsedPort(sourceNode + ":" + sourcePort)) {
            sourceNode = "";
            sourcePort = "";
            alert("对不起，这个接口已经使用！");
        }
    }

    function buildNodeHtml(device) {
        var deviceId = device.nodeId,
            devHtml = '<img  src="<?=URL_APPEDND?>/storage/images/devices/' + device.model + '.png"  title="' +device.nodeName+ '">' +
                '<div class="name"><a id="console_' + deviceId + '" >' + device.nodeName + '</a></div> <br />' +
                '<div id="nodeDescDiv_' + deviceId + '" class="nodeDesc"><textarea rows="3" style="resize:none" cols="12" id="nodeDesc_' +
                deviceId + '" type="text" value="' + device.nodeDesc.desc + '">' + device.nodeDesc.desc + '</textarea></div>';
        devHtml += '<input id="nodeType_' + deviceId +  '" type="hidden" value="' + device.type + '">';
        devHtml += '<input id="model_' + deviceId +  '" type="hidden" value="' + device.model + '">';
        devHtml += '<input id="nodeName_' + deviceId + '" type="hidden" value="' + device.nodeName + '">';
        devHtml += '<input id="portsnumber' + deviceId + '" type="hidden" value="' + device.portsnumber + '">'; 

        $.each(device.portsnumber, function(portType, number) {
          devHtml += '<input port="'+ portType +'" class="myPorts_'+ deviceId +'" type="hidden" value="' + device.portsnumber + '">';
          devHtml += '<input port="'+ portType +'" class="myPort_'+ deviceId +'" type="hidden" value="' + number + '">';
        });


        devHtml += '<ul style="display: none">';
//            var i; 
//            for (i = 0; i < device.portsnumber; i++) {
//                devHtml += '<input id="interface_' + deviceId +i+ '" type="hidden" value="' + device.interface[i] + '">';
//                devHtml += '<li class="eq"><a href="#" title="' + device.interface[i]  + '">' + device.interface[i] + '</a></li>';
//            } 
            $.each(device.interface, function(i, s) {
                devHtml += '<input id="interface_' + deviceId +i+ '" type="hidden" value="' + device.interface[i] + '">';
                devHtml += '<li class="eq"><a href="#" title="' + device.interface[i]  + '">' + device.interface[i] + '</a></li>';
            });
        
        devHtml += '</ul>';
        return devHtml;
    }

    /**
     * device bind action
     * @param obj
     */
    function deviceBindAction(obj) {
        // li mark Source link
        obj.find("ul li.eq").each(function(i, e) {
            //  alert($(e).html());
            jsPlumb.makeSource($(e), {
                parent: $(e).parent().parent(),
                anchor: "Continuous",
                connector: [ "Straight" ],
                connectorStyle: { strokeStyle: 'rgb(243,230,18)', lineWidth: 3}/*,
                 maxConnections:5,
                 onMaxConnections:function(info, e) {
                 alert("Maximum connections (" + info.maxConnections + ") reached");
                 }*/
            });
        });

        obj.find("ul li").bind("mousedown", function() {
            liMouseDownListener(this);
        });

        obj.find("img").bind("click", function() {
            jsIOUWebNetMap.hidePopWindows();
            imageListener(this);
        });

        obj.find("img").bind("mouseover", function() {
            if (sourcePort !== "") {
                $(this).parent().find("ul").show();
                imageListener(this);
            }
        });
    }

    window.jsIOUWebNetMap = {
        version: 0.3,
        createRouter : function(device, event) {

            var devObjIndex = getDevIndex(device.type),
                devObjId = device.type +  devObjIndex,
                devTypeArray = device.models, // ['100A', '900B'],
                devType = "<option value='0'>请选择</option>",
                i;
            for (var m = 0; m < devTypeArray.length; m++) {
                devType += "<option>" + devTypeArray[m].mName + "</option>";
            }



            var statesdemo = {
                state0: {
                    title: '请选择设备类型',
                    html: '<label>名称: <input id="devName_' + devObjId + '" type="text" value="' + device.devName + devObjIndex + '"></label><br />' +
                        '<label>类型: <select name="devType_' + devObjId + '">' + devType + '</select></label><br />' +
                        '<label>注释: <input id="nodeDesc_' + devObjId + '" value=""></label>',
                    buttons: { Next: 1 },
                    focus: 1,
                    submit: function (e, v, m, f) {
                        e.preventDefault();

                        // 选中的设计类型
                        var selectdDeviceMode = f["devType_" + devObjId];

                      //  alert(f["devType_" + devObjId]);return;
                        if (selectdDeviceMode === "0") {
                           var devType  =$("select[name=devType_"+ devObjId +"]");
                            devType.css("border-color", "red");
                            devType.focus();
                            return;
                        } else {
                            device.selectdDeviceMode = selectdDeviceMode;

                            for (var k = 0; k <device.models.length; k++) {
                                if (selectdDeviceMode === device.models[k].mName) {
                                    var selectedPosts = device.models[k].ports;
                                   /* $.each( selectedPosts, function(key, val){
                                      //  alert( "Name: " + key + ", Value: " + val);

                                        selectedPostsHhml = "<label>"+ key +": <input type=\"text\" id=\"s_port_\"" + devObjId + "\" value=\"3\"> </label><br />";
                                    });*/
                                    var selectedPostsHtml = "";

                                    for (var port in selectedPosts) {
                                      //  alert(port);
                                      //  alert(selectedPosts[port]);
                                        if (selectedPosts[port] === 0) {
                                            continue;
                                        }

                                        selectedPostsHtml += "<label>"+ port +": <input class=\"myPorts\" port=\""+port +"\" type=\"text\" id=\""+ port +"_port_" + devObjId + "\" value=\""+selectedPosts[port]+"\"> </label><br />";
                                    }

                                    break;
                                } // end if
                            } // -end for
                            $("#ports_s").html(selectedPostsHtml);
                            statesdemo.selectedPostsHtml = selectedPostsHtml;
                           // alert("selectedPostsHhml: " + selectedPostsHtml);
                            device.nodeName = $("input#devName_" + devObjId).val();
                            device.nodeDesc = {};
                            device.nodeDesc.desc =  $("input#nodeDesc_" + devObjId).val();
                            $.prompt.goToState('state1');
                        }

                    }
                },
                state1 : {
                    title: '请选择商品数',
                    html:   "<div id=\"ports_s\"></div>", //'<label>s: <input type="text" id="s_port_' + devObjId + '" value="3"> </label><br />' +
                         //'<label>e: <input type="text" id="e_port_' + devObjId + '" value="2"> </label>',
                    buttons: { Back: -1, Done: 1 },
                    focus: 1,
                    submit: function(e, v, m, f) {
                        console.log(f);
                        if (v === 1) {

                            device.nodeId = devObjId;

                            var portsObj = {};
                            $(".myPorts").each(function(i){
                                var myPorts = $(this);
                                var portNum = myPorts.attr("port");
//                                alert(portNum);
                                portsObj[portNum] = myPorts.val();
                            });
                            device.ports = portsObj;
//                            device.ports.s = $("input#s_port_" + devObjId).val();
//                            device.ports.e = $("input#e_port_" + devObjId).val();


                            // build dev html start....
                            var id = devObjId;  // getDevIndex(device.type);

                            var d = document.createElement("div");
                            d.className = "window";

                            var devHtml = buildNodeHtml(device);
                            $(d).html(devHtml);
                            $(d).css({left: event.pageX, top: event.pageY});
                            $("#graphContainer").append(d);


                            deviceBindAction($(d));

                            addLiMouseUpListener(d);

                       // id = device.type + id;

                            var elementObj = jsPlumb.CurrentLibrary.getElementObject(d);
                            jsPlumb.CurrentLibrary.setAttribute(elementObj, "id", id);
                            // alert("id 2: " + id);

                            jsPlumb.draggable(id, {
                                containment: "parent"
                            });

                            jsPlumb.draggable("nodeDescDiv_" + id, {
                                //  containment:"parent"
                            });

                            nodes.push(id);
                            // build dev html end.

                            $.prompt.close();
                        }
                        if (v === -1) {
                            $.prompt.goToState('state0');
                        }

                        e.preventDefault();
                    }
                }
            };

            $.prompt(statesdemo);

           // return {d:d, id:id};
        },
        addRouter : function(id, event) {
            //alert(' add device... ');
            jsIOUWebNetMap.createRouter(id, event);
           // var e = prepare(info.id);
            /*jsPlumb.draggable(info.id, {
                containment:"parent"
            });
            nodes.push(info.id);*/
        },
        hidePopWindows : function() { $("div.window ul").hide(); },
        resetCurrentPort : function() {
            sourceNode = "";
            sourcePort = "";
            targetNode = "";
            targetPort = "";
        },
        getNodes : function () { return nodes; },
        getNetMap : function() { return netMap; },
        addNetMap : function(map) {
            console.log(" ### adding map: " + map);
            netMap.add(map);
        },
        removeNetMap : function(map) {
            console.log(" ###removing map: " + map);
            netMap.removeValue(map);
        }, 
        logUsePort : function (port) {console.log(port + " add log..."); usedPorts.put(port, ""); },
        removeUsePort : function (port) {
            console.log(port + " remove log...");
            usedPorts.remove(port, "");
        },

        isUsedPort : function(port) {
            return usedPorts.containsKey(port);
        },

        loadNetMap: function(jsonStr) {
           // var jsonStr = '{"nodes":[{"nodeId":"cloud1","nodeName":"云1","nodeType":"cloud","offset":{"left":"209px","top":"76px"},"nodeDesc":{"desc":"324234","left":161,"top":138},"ports":{"s":"3","e":"2"}}],"netMap":[]} ';

            var json = $.parseJSON(jsonStr);

            if (json !== null) {
                $.each(json.nodes, function(i, device) {
                 //   alert("render No. " + i + " " +  JSON.stringify(device) );

                    var devObjIndex = getDevIndex(device.nodeType);
                    var devObjId = device.nodeId;
                    //alert("device id: " +  devObjId);

                    var id = devObjId;  // getDevIndex(device.type);

                    var d = document.createElement("div");
                    d.className = "window";

                    device.type = device.nodeType;
                    // device.nodeDesc

                    var devHtml = buildNodeHtml(device);
                    $(d).html(devHtml);
                    $(d).css({left: device.offset.left, top: device.offset.top});
                    $("#graphContainer").append(d);

                    $("#nodeDescDiv_" + id).css({left: device.nodeDesc.left, top: device.nodeDesc.top  });

                    deviceBindAction($(d));

                    addLiMouseUpListener(d);

                    // id = device.type + id;

                    var elementObj = jsPlumb.CurrentLibrary.getElementObject(d);
                    jsPlumb.CurrentLibrary.setAttribute(elementObj, "id", id);
                    // alert("id 2: " + id);

                    jsPlumb.draggable(id, {
                        containment: "parent"
                    });

                    jsPlumb.draggable("nodeDescDiv_" + id, {
                        //  containment:"parent"
                    });

                    nodes.push(id);
                });

                $.each(json.netMap, function(i, conn) {
                    var nodesLabel = conn.split(" "),
                        nodeLabel_1 = nodesLabel[0].split(":"),
                        nodeLabel_2 = nodesLabel[1].split(":"),
                        node1 = nodeLabel_1[0],
                        label1 = nodeLabel_1[1],
                        node2 = nodeLabel_2[0],
                        label2 = nodeLabel_2[1];

                    jsPlumb.connect({
                        source: node1,
                        target: node2,
                        //     paintStyle:{lineWidth:3,strokeStyle:'rgb(0,0,0)'},
                        overlays: [
                            [ "Label", {label: label1, location: 0.15, cssClass: "label"}],
                            [ "Label", {label: label2, location: 0.85, cssClass: "label"}]
                        ]
                    });

                    jsIOUWebNetMap.logUsePort(node1 + ":" + label1);
                    jsIOUWebNetMap.logUsePort(node2 + ":" + label2);
                    jsIOUWebNetMap.addNetMap(conn);
                });
            }
        },
        exportNetMap: function() {
            var json = {},
                jsonNodes = [],
                i,interface_str,
                exportInterface;
            for (i = 0; i < nodes.length; i++) {
                var exportNodeId = nodes[i],j,
                    interface_value=$("#interface_" + exportNodeId+i).val(),
                    node = $("#" + exportNodeId),
                    exportNode = {left: node.css('left'), top: node.css('top')},
                    exportNodeDesc = {desc: $("#nodeDesc_" + exportNodeId).val(),
                        left: $("#nodeDescDiv_" + exportNodeId).position().left,
                        top: $("#nodeDescDiv_" + exportNodeId).position().top};
       
                // 导出设备端口
                var myPorts = {};
                $(".myPort_" + exportNodeId).each(function() {
                    var currPort = $(this)
                  //  alert(currPort.attr("port"));
                    myPorts[currPort.attr("port")] = currPort.val();
                });
                
                //device interface 
            var  interface_str='';
            for ( j= 0; j <myPorts[0] ; j++) {
                    if(myPorts[0]==1){
                              interface_str+='"'+j+'":"'+$("#interface_" + exportNodeId+j).val()+'"';
                    }else{
                              var leng=myPorts[0]-1;
                              if(j==leng){
                                   interface_str+='"'+j+'":"'+$("#interface_" + exportNodeId+j).val()+'"';
                              }else{
                                   interface_str+='"'+j+'":"'+$("#interface_" + exportNodeId+j).val()+'",';
                              }
                  }
            }
            exportInterface="{"+interface_str+"}";

                jsonNodes.push({
                    "nodeId": exportNodeId,
                    "nodeName": $("#nodeName_" + exportNodeId).val(),
                    "nodeType": $("#nodeType_" + exportNodeId).val(),
                    "model" :  $("#model_" + exportNodeId).val(),
                    "portsnumber": myPorts[0],
                    "offset": exportNode, 
                    "nodeDesc": {desc: $("#nodeDesc_" + exportNodeId).val(),
                        left: $("#nodeDescDiv_" + exportNodeId).position().left,
                        top: $("#nodeDescDiv_" + exportNodeId).position().top},//exportNodeDesc,
                    "interface" :  exportInterface
                    });
            }
            json.nodes = jsonNodes;
//alert( JSON.stringify(json.nodes));
            var netMapArray = [];
           //alert("size: " + netMap.size());
            var j;
            for (j = 0; j < netMap.size(); j++) {
                var item = netMap.get(j);
                netMapArray.push(item);
            }
            json.netMap = netMapArray;

            console.log(json);
            return json;
        }
    };
})();
    </script>
    <script type="text/javascript" src="js/jquery-impromptu.js"></script>

    <script type="text/javascript">
        /*global window, document, jQuery, $, alert, console, jsPlumb, HashMap, ArrayList:true, jsIOUWebNetMap:true*/
        document.onselectstart = function () {
            return false;
        };

        jsPlumb.bind("ready", function () {
            jsPlumb.importDefaults({
                Anchor: "Continuous",
                //Connector : [ "Bezier", { curviness: 50 } ],
                Connector: [ "Straight" ],
                Endpoint: "Blank",
                //  paintStyle:{lineWidth:3,strokeStyle:'rgb(0,0,0)'},
                PaintStyle: { lineWidth: 2, strokeStyle: "#000000" },
                cssClass: "link"//,
                /* ConnectionOverlays : [
                 [ "Label", {label:"e", location:0.15, cssClass:"label"}],
                 [ "Label", {label:"e", location:0.85, cssClass:"label"}]
                 ]*/
            });
        });

        function exportMap(json) {
            //alert("Export: " + JSON.stringify(json));
            var netmap= JSON.stringify(json);
            document.cookie="data="+netmap;
            document.cookie="topoid=<?=$topoid?>";
            window.location.href="topoSave.php";
        }
        function cleanMap() {
            document.cookie="actionss=cleanMap";
            document.cookie="tid=<?=$topoid?>";
            window.location.href="topoClean.php";
        }
        var createAble = false;
        $(document).ready(function () {
            var jsonStr ='<?=$device_str?>';
//            var jsonStr = 'nodeId":"router1","nodeName":"DCFS-8000","nodeType":"router","offset":{"left":"484px","top":"172px"},"nodeDesc":{"desc":"DCFS-8000","left":-35,"top":65},"portsnumber":"2","interface":{"0":"1/1","1":"0/2"}},{"nodeId":"desktop2","nodeName":"电脑2","nodeType":"desktop","offset":{"left":"448px","top":"410px"},"nodeDesc":{"desc":"computer2","left":-35,"top":65},"portsnumber":"2","interface":{"0":"1/1","1":"0/2"}},{"nodeId":"desktop1","nodeName":"电脑1","nodeType":"desktop","offset":{"left":"822px","top":"357px"},"nodeDesc":{"desc":"computer1","left":-35,"top":65},"portsnumber":"2","interface":{"0":"1/1","1":"0/2"}}],"netMap":[]}';
            //jsonStr = "{}";
//            alert(jsonStr);
             jsIOUWebNetMap.loadNetMap(jsonStr);
            var sidebarContainer = $("#sidebarContainer");
            var sidebarHtml = "",  i;
            sidebarHtml += '<span class="title"><?=$toponame?></span><br>';
            sidebarHtml += '<a  class="action_botton" href="#"  onclick="exportMap(jsIOUWebNetMap.exportNetMap());" >保存拓扑<\/a>&nbsp;';
            sidebarHtml += '<a  class="action_botton" href="#" onclick="cleanMap();" style="color:red">清除拓扑<\/a>&nbsp;&nbsp;&nbsp;';

            sidebarContainer.html(sidebarHtml);

            $("div", "#sidebarContainer").draggable({
                appendTo: "body",
                helper: "clone"
            }).bind("mousedown", function () {
                createAble = true;
            });

            $("div#graphContainer").droppable({
                drop: function (event, ui) {
                    // alert("ui: " + ui.draggable.attr('type'));
                    if (createAble) {
                        jsIOUWebNetMap.addRouter(devices[ui.draggable.attr('type')], event);
                    }
                }
            }).bind("mousedown", function () {
                createAble = false;
            }).bind("mouseup", function () {
                        // alert("container clear");
                $("div.window ul").hide();
                jsIOUWebNetMap.resetCurrentPort();
            });

            // var curNodeIndex = 1 ,

            jsPlumb.bind("endpointHover", function (connection) {
                alert('hover');
            });
            jsPlumb.bind("beforeDrop", function (connection) {
                alert('beforeDrop');
            });

            jsPlumb.bind("mouseenter", function (conn) {
                alert("mouseenter : " + conn);
            });

            jsPlumb.bind("jsPlumbConnection", function (conn) {
                // alert("jsPlumbConnection");
                $("div.window ul").hide();
                var connection = conn.connection;
                var sourceId = connection.sourceId;
                var targetId = connection.targetId;
                console.log("build connection: source: " + sourceId + " targetId: " + targetId);
                if (sourceId === targetId) {  // it connect itself, return
                    jsPlumb.detach(conn);
                    return;
                }

                var overlays = connection.overlays;

                var map = sourceId + ":" + overlays[0].getLabel() + " " + targetId + ":" + overlays[1].getLabel();
                //     var sourcePort = overlays[0].getLabel();
                //  console.log("sourcePort: " +  sourcePort);
                console.log(overlays[0].getLabel());
                jsIOUWebNetMap.logUsePort(sourceId + ":" + overlays[0].getLabel());
                jsIOUWebNetMap.logUsePort(targetId + ":" + overlays[1].getLabel());
                jsIOUWebNetMap.addNetMap(map);

                jsIOUWebNetMap.resetCurrentPort();
            });


            $("img#rountAddBtn").bind("click", function () {
                jsIOUWebNetMap.addRouter(devices[0]);
                //  jsIOUWebNetMap.initMakeTarget();
            });

            jsPlumb.bind("dblclick", function (c) {
                console.log(c);
                var overlays = c.overlays;
                var map = c.sourceId + ":" + overlays[0].getLabel() + " " + c.targetId + ":" + overlays[1].getLabel();
                console.log(map);

                jsIOUWebNetMap.removeUsePort(c.sourceId + ":" + overlays[0].getLabel());
                jsIOUWebNetMap.removeUsePort(c.targetId + ":" + overlays[1].getLabel());

                jsIOUWebNetMap.removeNetMap(map);
                jsPlumb.detach(c);
            });
        });
    </script>
</head>
<body>
    <div id="main">
        <div id="sidebarContainer"></div>
        <div id="graphContainer"></div>   
    </div>
</body>
</html>
