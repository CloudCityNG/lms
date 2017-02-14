<?php
 
try {
    $req['provider'] = $_POST['provider'];
	$req['authKey'] = $_POST['authKey'];
	$req['clientIp'] = $_G['clientip'];

	$data = sso_query('http://was.nm.cmcc/ssoCenter/sso/ssoCheck.do',$req);
	$res = explode(" ",$data);
	
} catch (Exception $e) {
    echo "Error:".$e->getMessage();
	exit;
} 

/*post 单点数据*/
function sso_query($path, array $req = array()) {

	$post_data = http_build_query($req, '', '&');

	static $ch = null;
	if (is_null($ch)) {
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_USERAGENT, 
			'Mozilla/4.0 (compatible; Bter PHP bot; '.php_uname('a').'; PHP/'.phpversion().')'
			);
	}
	curl_setopt($ch, CURLOPT_URL, $path);
	curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data);
	curl_setopt($ch, CURLOPT_HTTPHEADER, 0);
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
                 // run the query
	$res = curl_exec($ch);
	return $res;
}

if($res[4]){
    $uid = trim($res[4]);
	$username = trim($res[4]);

}
