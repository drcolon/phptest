<?php

require_once(CLASSES_PATH.'/DbContext.php');
require_once(APPOBJECTS_PATH.'/AppObject.php');

class App
{
	private static $instance;

	private $defaultAction;
	private $actions = array();
	private $actionObjs = array();


	private function __contruct($isWebService)
	{
		$this->defaultAction  = '';
		$this->actions = array();
		$this->actionObjs = array();
	}

	public static function create()
	{
		if(! isset(self::$instance) )
		{	
			self::$instance = new App(false);
		}

		return self::$instance;
	}

	public function run()
	{
		$requestMethod = $_SERVER['REQUEST_METHOD'];

		switch($requestMethod)
		{
			case 'GET':
				$data = & $_GET;
				break;

			case 'POST':
				$data = & $_POST;
				break;
			
			default:
				$data = array();
		}

		if(empty($data['appAction']))
		{
			if(empty($this->defaultAction))			
				return AppObject::NOT_OK;			

			$appAction = $this->defaultAction;
		}	
		else
			$appAction = $data['appAction'];

		if(!array_key_exists($appAction,$this->actions))
			return AppObject::NOT_OK;


		$class  = $this->actions[$appAction]['class'];
		$method = $this->actions[$appAction]['classMethod'];
		$requestMethodString = 	$this->actions[$appAction]['requestMethodString'];
	
		if(! array_key_exists($key,$this->actionObjs))
		{
			$actionObj = new $class;
			$this->actionObjs[$class] = $actionObj;
		}
		else
		{
			$actionObj= $this->actionObjs[$class];
		}

		if( $requestMethodString === 'none')
		{
			unset($data);
			$data = array();			
		}
		elseif( (! empty($requestMethodString)) && $requestMethodString !== $requestMethod )
		{
			echo "estoy aqui 1 ".$requestMethodString."-".$requestMethod;

			return AppObject::NOT_OK;
		}
	
		$actionObj->$method(DbContext::create(),$data);

		return AppObject::OK;
	}

	public function regAction($actionScope,$requestMethodString='')
	{
		if(empty($actionScope))
			return AppObject::NOT_OK;			

		$actionScopeArray = split('\.',$actionScope,2);

		if(! is_array($actionScopeArray) )
			return AppObject::NOT_OK;

		if(count($actionScopeArray) != 2)			
			return AppObject::NOT_OK;
		
		$key = md5($actionScope);

		if(array_key_exists($key,$this->actions))
			return AppObject::NOT_OK;
	
		$this->actions[$key] = 
			array('class' => $actionScopeArray[0],
			      'classMethod'=>$actionScopeArray[1],
			      'requestMethodString' => $requestMethodString);
				
		return AppObject::NOT_OK;
	}


	public function setDefaultAction($actionScope)
	{
		$this->defaultAction = md5($actionScope);
	}
		
}

?>
