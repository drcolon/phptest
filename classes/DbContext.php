<?php

abstract class DbContext
{	
	private static $instance;

	public function create()
	{
		$dsn = "mysql:host=localhost;dbname=".DB_NAME;

		if(! isset(self::$instance) )
		{	
			self::$instance = new PDO($dsn, DB_USER, DB_PASS);
		}

		return self::$instance;
	}

}

?>
