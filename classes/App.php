<?php

require_once(CLASSES_PATH.'/DbContext.php');
require_once(APPOBJECTS_PATH.'/AppObject.php');

/**
 * Clase de tipo singleton, solo mantiene una instancia
 */
class App
{
	private static $instance;

        /**
         *Código de la accion por defecto que se ejecutará
         * @var type 
         */
	private $defaultAction;
        /**
         * Arreglo que contiene las acciones disponibles para la ejecución
         * @var type array
         */
	private $actions = array();
        
        /**
         * Mapa de objetos para acceder a las funcionalidades
         * @var type Array
         */
	private $actionObjs = array();


	private function __contruct($isWebService)
	{
		$this->defaultAction  = '';
		$this->actions = array();
		$this->actionObjs = array();
	}

        /**
         * Retorna la instancia actual de la aplicación o en su defecto
         * crea una nueva
         * @return type App
         */
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

                //obtiene la forma de envío y la almacena en la variable data
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

                //si no se especifica una acción, se ejecutará la accion por defecto
		if(empty($data['appAction']))
		{
			if(empty($this->defaultAction))			
				return AppObject::NOT_OK;			

			$appAction = $this->defaultAction;
		}	
		else 
			$appAction = $data['appAction'];

                //si la acción no se encuentra ene l mapa, entonces no está disponible
		if(!array_key_exists($appAction,$this->actions))
			return AppObject::NOT_OK;


                // habiendo seteado la acción, se buscan sus datos en el arreglo
                // de acciones registradas
		$class  = $this->actions[$appAction]['class'];
		$method = $this->actions[$appAction]['classMethod'];
		$requestMethodString = 	$this->actions[$appAction]['requestMethodString'];
	
                //si no esxiste un objeto de acción registrado
		if(! array_key_exists($class,$this->actionObjs))
		{
                    //es almacenado uno nuevo, cuya key es la clase
			$actionObj = new $class;
			$this->actionObjs[$class] = $actionObj;
		}
		else
		{
                        //de lo contrario se obtiene un objeto de acción
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
	
                //es ejecutado el metodo
		$actionObj->$method(DbContext::create(),$data);

		return AppObject::OK;
	}

	public function regAction($actionScope,$requestMethodString='')
	{
		if(empty($actionScope))
			return AppObject::NOT_OK;			

                //realiza un corte a la cadena separada por el .
		$actionScopeArray = split('\.',$actionScope,2);

		if(! is_array($actionScopeArray) )
			return AppObject::NOT_OK;

		if(count($actionScopeArray) != 2)			
			return AppObject::NOT_OK;
		
		$key = md5($actionScope);

		if(array_key_exists($key,$this->actions))
			return AppObject::NOT_OK;
	
                //ingresa un nuevo key a la cadena
		$this->actions[$key] = 
			array('class' => $actionScopeArray[0],
			      'classMethod'=>$actionScopeArray[1],
			      'requestMethodString' => $requestMethodString);
				
		return AppObject::NOT_OK;
	}


        //establece la acción por defecto que se realizará cuando no se ha especificado
	public function setDefaultAction($actionScope)
	{
		$this->defaultAction = md5($actionScope);
	}
		
}

?>
