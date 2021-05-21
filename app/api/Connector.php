<?php 
class Connector
{ 
	/* Подключим остальные классы */
	private $classes = array(
		'config'     => 'Config',
		'db'         => 'Database',
		'request'	 => 'Request',
		'importer'	 => 'Importer'
	);
	 
	private static $objects = array();
	 
	public function __construct()
	{
		//error_reporting(E_ALL & !E_STRICT);
	}
 
	public function __get($name)
	{
	 
		if(isset(self::$objects[$name]))
		{
			return(self::$objects[$name]);
		}
		 
		if(!array_key_exists($name, $this->classes))
		{
			return null;
		}
		 
		$class = $this->classes[$name];
		 
		include_once(dirname(__FILE__).'/'.$class.'.php');
		 
		self::$objects[$name] = new $class();
		 
		return self::$objects[$name];
	}
}