<?php
header("Content-type: text/html; charset=utf-8");
require_once ('../../../main/inc/global.inc.php');

class json{
    function returnJson($code,$messge,$data=''){
           $array =   array(
              "Result"=>$code,
              "reason"=>$messge,
              "data"=>$data
            );
          $json = json_encode($array);
          return ($json);
    }
}

class OperationUser
{
    public function cation($json_user)
    {
        $table_user = Database::get_main_table (user);
        $json = new json();
        $json_str = stripslashes( $json_user );
        $data = json_decode($json_str, true);//解析json字符串

        $count_json = count($data);
		if($count_json>0){
			
			 api_sql_query("BEGIN");
        for ($i = 0; $i < $count_json; $i++){
			if($data[$i]['role']=="admin"){
				$status=10;
				$is_admin=1;
			}elseif($data[$i]['role']=="student"){
				$status=5;
				$is_admin=0;
			}elseif($data[$i]['role']=="teacher"){
				$status=1;
				$is_admin=0;
			}
         if ($data[$i]['type'] == 'insert')
         {
            $sql_data = array(
               'username' => $data[$i]['userid'],
               'firstname' => $data[$i]['cn'],
			   'is_admin'  => $is_admin,
               'mobile' => $data[$i]['mobile'],
               'status' => $status,
               'email' => $data[$i]['email'],
              'dept_id' => $data[$i]['department'],
            );
            $sql = Database::sql_insert($table_user, $sql_data);
			return $sql;
            $res = api_sql_query($sql, __FILE__, __LINE__);
         } elseif ($data[$i]['type'] == 'delete')
         {

           $username = $data[$i]['userid'];
           $sql = "DELETE  from `user` WHERE `username` ='".$username."'";
           $res = api_sql_query($sql, __FILE__, __LINE__);

        } elseif ($data[$i]['type'] == 'update')
        {

           $username = $data[$i]['userid'];
           $sql_data = array(
              'firstname' => $data[$i]['cn'],
			  'is_admin'  => $is_admin,
              'mobile' => $data[$i]['mobile'],
              'status' => $data[$i]['role'],
              'email' => $data[$i]['email'],
              'dept_id' => $data[$i]['department'],
           );
          $sql = Database::sql_update($table_user, $sql_data, "username='".$username."'");
          $res = api_sql_query($sql, __FILE__, __LINE__);
        }
		}
		

        if ($res)
        {
            $uri = "http://10.221.135.20/lms/main/admin/user/receive_data.php";
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
            if ($data_result['Result'] == '-1')
            {
                api_sql_query("ROLLBACK");
                return  $json->returnJson("0", "攻防平台同步失败");
            } else
            {
                api_sql_query("COMMIT");
                return  $json->returnJson("1", "数据同步成功");
            }

        } else
        {
                api_sql_query("ROLLBACK");
				return  $json->returnJson("0", "实训同步信息失败");
        }
	  }else{
		  return "No data recive";
	  }
	}
}

if(api_get_setting ( 'lm_switch' ) == 'true' && api_get_setting( 'lm_nmg' ) == 'true')
{
    $server = new SoapServer('OperationUser.wsdl', array('soap_version' => SOAP_1_1));
    $server->setClass('OperationUser');
    $server->handle();

 //   include('./SoapDiscovery.class.php');
 //   $disco = new SoapDiscovery('OperationUser', 'soap');
 //   $disco->getWSDL();
}

