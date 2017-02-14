<?php
/*
 ==============================================================================

 ==============================================================================
 */
/**
 ==============================================================================
 * This is the security library for ZLMS.
 *
 * This library is based on recommendations found in the PHP5 Certification
 * Guide published at PHP|Architect, and other recommendations found on
 * http://www.phpsec.org/
 * The principles here are that all data is tainted (most scripts of ZLMS are
 * open to the public or at least to a certain public that could be malicious
 * under specific circumstances). We use the white list approach, where as we
 * consider that data can only be used in the database or in a file if it has
 * been filtered.
 *
 * For session fixation, use ...
 * For session hijacking, use get_ua() and check_ua()
 * For Cross-Site Request Forgeries, use get_token() and check_tocken()
 * For basic filtering, use filter()
 * For files inclusions (using dynamic paths) use check_rel_path() and check_abs_path()
 *
 * @package zllms.library
 ==============================================================================
 */
/**
 * Security class
 *
 * Include/require it in your code and call Security::function()
 * to use its functionalities.
 *
 * This class can also be used as a container for filtered data, by creating
 * a new Security object and using $secure->filter($new_var,[more options])
 * and then using $secure->clean['var'] as a filtered equivalent, although
 * this is *not* mandatory at all.
 *
 * @author	Yannick Warnier <yannick.warnier@ZLMS.com>
 */
class Security{

	var	$clean = array();

	/**
	 * Checks if the relative path given is really under the checker path
	 * @param	string	Relative path to be checked (relative to the current directory) (with trailing slash)
	 * @param	string	Checker path under which the path should be (with trailing slash)
	 * @return	bool	True if the path is under the checker, false otherwise
	 */
	function check_rel_path($rel_path,$checker_path)
	{
		if(empty($checker_path)){return false;} //checker path must be set
		$current_path = getcwd(); //no trailing slash
		if(substr($rel_path,-1,1)!='/'){
			$rel_path = '/'.$rel_path;
		}
		$abs_path = $current_path.$rel_path;
		$true_path=str_replace("\\", "/", realpath($abs_path));
		$found = strpos($true_path.'/',$checker_path);
		if($found===0)
		{
			return true;
		}
		return false;
	}


	/**
	 * Checks if the absolute path given is really under the checker path
	 * @param	string	Absolute path to be checked (with trailing slash)
	 * @param	string	Checker path under which the path should be (with trailing slash)
	 * @return	bool	True if the path is under the checker, false otherwise
	 */
	function check_abs_path($abs_path,$checker_path)
	{
		if(empty($checker_path)){return false;} //checker path must be set

		$true_path=str_replace("\\", "/", realpath($abs_path));

		$found = strpos($true_path.'/',$checker_path);
		if($found===0)
		{
			return true;
		}
		return false;
	}

	/**
	 * Filters dangerous filenames (*.php[.]?* and .htaccess) and returns it in
	 * a non-executable form (for PHP and htaccess, this is still vulnerable to
	 * other languages' files extensions)
	 * @param   string  Unfiltered filename
	 * @param   string  Filtered filename
	 */
	public static function filter_filename ($filename) {
		require_once (api_get_path ( LIBRARY_PATH ) . 'fileManage.lib.php');
		return disable_dangerous_file($filename);
	}


	/**
	 * This function checks that the token generated in get_token() has been kept (prevents
	 * Cross-Site Request Forgeries attacks)
	 * @param	string	The array in which to get the token ('get' or 'post')
	 * @return	bool	True if it's the right token, false otherwise
	 */
	function check_token($array='post')
	{
		switch($array){
			case 'get':
				if(isset($_SESSION['sec_token']) && isset($_GET['sec_token']) && $_SESSION['sec_token'] === $_GET['sec_token'])
				{
					return true;
				}
				return false;
			case 'post':
				if(isset($_SESSION['sec_token']) && isset($_POST['sec_token']) && $_SESSION['sec_token'] === $_POST['sec_token'])
				{
					return true;
				}
				return false;
			default:
				if(isset($_SESSION['sec_token']) && isset($array) && $_SESSION['sec_token'] === $array)
				{
					return true;
				}
				return false;
		}
		return false; //just in case, don't let anything slip
	}


	/**
	 * Checks the user agent of the client as recorder by get_ua() to prevent
	 * most session hijacking attacks.
	 * @return	bool	True if the user agent is the same, false otherwise
	 */
	function check_ua()
	{
		if(isset($_SESSION['sec_ua']) and $_SESSION['sec_ua'] === $_SERVER['HTTP_USER_AGENT'].$_SESSION['sec_ua_seed'])
		{
			return true;
		}
		return false;
	}

	/**
	 * Clear the security token from the session
	 * @return void
	 */
	public static function clear_token () {
		$_SESSION['sec_token'] = null;
		unset($_SESSION['sec_token']);
	}


	/**
	 * This function sets a random token to be included in a form as a hidden field
	 * and saves it into the user's session. Returns an HTML form element
	 * This later prevents Cross-Site Request Forgeries by checking that the user is really
	 * the one that sent this form in knowingly (this form hasn't been generated from
	 * another website visited by the user at the same time).
	 * Check the token with check_token()
	 * @return	string	Hidden-type input ready to insert into a form
	 */
	function get_HTML_token()
	{
		$token = md5(uniqid(rand(),TRUE));
		$string = '<input type="hidden" name="sec_token" value="'.$token.'"/>';
		$_SESSION['sec_token'] = $token;
		return $string;
	}


	/**
	 * This function sets a random token to be included in a form as a hidden field
	 * and saves it into the user's session.
	 * This later prevents Cross-Site Request Forgeries by checking that the user is really
	 * the one that sent this form in knowingly (this form hasn't been generated from
	 * another website visited by the user at the same time).
	 * Check the token with check_token()
	 * @return	string	Token
	 */
	function get_token()
	{
		$token = md5(uniqid(rand(),TRUE));
		$_SESSION['sec_token'] = $token;
		return $token;
	}


	/**
	 * Gets the user agent in the session to later check it with check_ua() to prevent
	 * most cases of session hijacking.
	 * @return void
	 */
	function get_ua()
	{
		$_SESSION['sec_ua_seed'] = uniqid(rand(),TRUE);
		$_SESSION['sec_ua'] = $_SERVER['HTTTP_USER_AGENT'].$_SESSION['sec_ua_seed'];
	}


	/**
	 * This function filters a variable to the type given, with the options given
	 * @param	mixed	The variable to be filtered
	 * @param	string	The type of variable we expect (bool,int,float,string)
	 * @param	array	Additional options
	 * @return	bool	True if variable was filtered and added to the current object, false otherwise
	 */
	function filter($var,$type='string',$options=array())
	{
		$result = false;
		//get variable name and value
		$args = func_get_args();
		$names =array_keys($args);
		$name = $names[0];
		$value = $args[$name];
		switch($type){
			case 'bool':
				break;
			case 'int':
				break;
			case 'float':
				break;
			case 'string':
				break;
			case 'array':
				//an array variable shouldn't be given to the filtered
				return false;
			default:
				return false;
		}
		if(!empty($option['save'])){
			$this->clean[$name]=$result;
		}
		return $result;
	}


	/**
	 * This function returns a variable from the clean array. If the variable doesn't exist,
	 * it returns null
	 * @param	string	Variable name
	 * @return	mixed	Variable or NULL on error
	 */
	function get($varname)
	{
		if(isset($this->clean[$varname])){
			return $this->clean[$varname];
		}
		return NULL;
	}


	/**
	 * This function tackles the XSS injections.
	 * @since 1.8.6.1(1.4.0)
	 */
	function remove_XSS($var)
	{
		//liyu:20091215 旧方式
		//global $charset;
		//return htmlentities($var,ENT_QUOTES,$charset);
		
		$purifier = new HTMLPurifier(null,$user_status);
		if (is_array($var)) {
			return $purifier->purifyArray($var);
		} else {
			return $purifier->purify($var);
		}
	}
}
?>