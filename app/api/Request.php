<?php

/**
 * Класс-обертка для обращения к переменным _GET, _POST 
 */

require_once('Connector.php');

class Request extends Connector
{
 
	public function __construct()
	{		
		parent::__construct();
		
		$_POST = $this->stripslashes_recursive($_POST);
		$_GET = $this->stripslashes_recursive($_GET);	
	}
 
    public function method($method = null)
    {
    	if(!empty($method))
    		return strtolower($_SERVER['REQUEST_METHOD']) == strtolower($method);
	    return $_SERVER['REQUEST_METHOD'];
    }
 
    public function get($name, $type = null)
    {
    	$val = null;
    	if(isset($_GET[$name]))
    		$val = $_GET[$name];
    		
    	if(!empty($type) && is_array($val))
    		$val = reset($val);
    	
    	if($type == 'string')
    		return strval(preg_replace('/[^\p{L}\p{Nd}\d\s_\-\.\%\s]/ui', '', $val));
    		
    	if($type == 'integer')
    		return intval($val);

    	if($type == 'boolean')
    		return !empty($val);
    		
    	return $val;
    }
 
    public function post($name = null, $type = null)
    {
    	$val = null;
    	if(!empty($name) && isset($_POST[$name]))
    		$val = $_POST[$name];
    	elseif(empty($name))
    		$val = file_get_contents('php://input');
    		
    	if($type == 'string')
    		return strval(preg_replace('/[^\p{L}\p{Nd}\d\s_\-\.\%\s]/ui', '', $val));
    		
    	if($type == 'integer')
    		return intval($val);

    	if($type == 'boolean')
    		return !empty($val);

    	return $val;
    }
	private function stripslashes_recursive($var)
	{
		if(get_magic_quotes_gpc())
		{
			$res = null;
			if(is_array($var))
				foreach($var as $k=>$v)
					$res[stripcslashes($k)] = $this->stripslashes_recursive($v);
				else
					$res = stripcslashes($var);
		}
		else
		{
			$res = $var;
		}
		return $res;
	}
 
} 