<?php

require_once(APPOBJECTS_PATH.'/WebUser.php');
require_once(APPOBJECTS_PATH.'/WebUserType.php');

class WebUserActions 
{ 

	public function create(PDO $dbContext,array &$data)
	{
		$data['dateAccess'] = date('Y-m-d h:m:s');
		$data['ipAccess']   = $_SERVER['REMOTE_ADDR'];

		$status = WebUser::createWithColumns($dbContext,$data);

		if($status === AppObject::ALREADY_EXISTS )
		{
			unset($data['dateAccess']);
			unset($data['ipAccess']);

			unset($_SESSION['appActionBackData']);

			$_SESSION['appActionBackData'] = & $data;

			$location = WEBCONTENT_PATH.'/?appAction='.md5(__CLASS__.'.createForm').'&appActionBack=true';
			
			$msg = 'ya existe un usuario con el mismo nombre completo!!';
		}
		elseif($status === AppObject::OK)
		{
			$location = WEBCONTENT_PATH.'/?appAction='.md5(__CLASS__.'.principalForm');
			
			$msg = 'Se comploto la creacion del usuario!!';
		}
		else
		{
			$location = WEBCONTENT_PATH.'/';
			$msg = "error grave";
		}

		echo '<script type="text/javascript" charset="utf-8">';
		echo "alert('$msg')\n";
		echo "document.location = '$location'\n;";
		echo "</script>";
	}


	public function update(PDO $dbContext,array &$data)
	{
		$data['dateAccess'] = date('Y-m-d h:m:s');
		$data['ipAccess']   = $_SERVER['REMOTE_ADDR'];

		$status = WebUser::updateWithColumns($dbContext,$data);

		if($status === AppObject::ALREADY_EXISTS )
		{
			unset($data['dateAccess']);
			unset($data['ipAccess']);

			$location = WEBCONTENT_PATH.'/?appAction='.md5(__CLASS__.'.editForm').'&id='.$data['id'];
			
			$msg = 'ya existe un usuario con el mismo nombre completo !!';
		}
		elseif($status === AppObject::OK)
		{
			$location = WEBCONTENT_PATH.'/?appAction='.md5(__CLASS__.'.principalForm');
			
			$msg = 'Se comploto la edicion del usuario!!';
		}
		else
		{
			$location = WEBCONTENT_PATH.'/';
			$msg = "error grave";
		}

		echo '<script type="text/javascript" charset="utf-8">';
		echo "alert('$msg')\n";
		echo "document.location = '$location'\n;";
		echo "</script>";
	}


	public function remove(PDO $dbContext,array &$data)
	{
		print_r($data);
		if(isset($data['id']))
		{
			$user = WebUser::findById($dbContext,$data['id']);

			var_dump($user);
			if($user instanceof WebUser)
			{
				$user->remove($dbContext);
				
				$msg = "Se borro el usuario";
			}
			else
			{	
				unset($data['id']);

				$msg = "No se pudo borrar el usuario";
			}
		}	

		$location = WEBCONTENT_PATH.'/';
			
		echo '<script type="text/javascript" charset="utf-8">';
		echo "alert('$msg')\n";
		echo "document.location = '$location'\n;";
		echo "</script>";
		
	}


	public function search(PDO $dbContext,array &$data)
	{
		$parameters['appAction'] = array();

		$parameters['appAction']['search']     = md5(__CLASS__.'.search');
		$parameters['appAction']['remove']     = md5(__CLASS__.'.remove');
		$parameters['appAction']['createForm'] = md5(__CLASS__.'.createForm');

		$parameters['appAction']['editForm']   = md5(__CLASS__.'.editForm');
		$parameters['appAction']['view']       = md5(__CLASS__.'.view');

		$users = & WebUser::findByColumns($dbContext,$data);


		if(is_array($users))
			$parameters['searchResults'] = & $users;
		else
			$parameters['searchResults'] = array();

		require_once(TEMPLATES_PATH.'/tInitial.php');
	
	}


	public function principalForm(PDO $dbContext,array &$data)
	{
		$parameters = array();

		$parameters['appAction'] = array();

		$parameters['appAction']['search']     = md5(__CLASS__.'.search');
		$parameters['appAction']['remove']     = md5(__CLASS__.'.remove');
		$parameters['appAction']['createForm'] = md5(__CLASS__.'.createForm');

		$parameters['appAction']['editForm']   = md5(__CLASS__.'.editForm');
		$parameters['appAction']['view']       = md5(__CLASS__.'.view');

		$parameters['searchResults'] = WebUser::findAll($dbContext);
                var_dump($parameters);

		require_once(TEMPLATES_PATH.'/tInitial.php');
	}


	public function createForm(PDO $dbContext,array &$data)
	{
		$parameters = array();

		$parameters['appAction'] = array();

		$parameters['appAction']['currentAction'] = md5(__CLASS__.'.create');

		if(isset($data['appActionBack']))
		{
			$parameters['name']     = $_SESSION['appActionBackData']['name'];
			$parameters['lastName'] = $_SESSION['appActionBackData']['lastName'];
			$parameters['email']    = $_SESSION['appActionBackData']['email'];
			$parameters['actualIdUserType'] = $_SESSION['appActionBackData']['idUserType'];

			unset($_SESSION['appActionBackData']);
		}
		
		$this->generalForm($dbContext,$data,$parameters);
	}


	public function editForm(PDO $dbContext,array &$data)
	{
		$parameters = array();

		$parameters['appAction'] = array();

		$parameters['appAction']['currentAction'] = md5(__CLASS__.'.update');

		if(isset($data['id']))
		{
			$user = WebUser::findById($dbContext,$data['id']);

			if($user instanceof WebUser)
			{
				$parameters['name']     = $user->getName();
				$parameters['lastName'] = $user->getLastName();
				$parameters['email']    = $user->getEmail();
				$parameters['actualIdUserType'] = $user->getIdUserType();
				$parameters['id'] = $data['id'];

			}
			else
			{	
				unset($data['id']);
			}
		}	
		
		if(isset($data['id']))
		{
			$this->generalForm($dbContext,$data,$parameters);
		}
	}


	public function view(PDO $dbContext,array &$data)
	{
		$parameters = array();

		$parameters['appAction'] = array();

		$parameters['appAction']['currentAction'] = md5(__CLASS__.'.principalForm');

		if(isset($data['id']))
		{
			$user = WebUser::findById($dbContext,$data['id']);

			if($user instanceof WebUser)
			{
				$parameters['name']     = $user->getName();
				$parameters['lastName'] = $user->getLastName();
				$parameters['email']    = $user->getEmail();
				$parameters['actualIdUserType'] = $user->getIdUserType();
				$parameters['ipAccess']    = $user->getIpAccess();
				$parameters['dateAccess']    = $user->getDateAccess();
				$parameters['id'] = $data['id'];

				$parameters['view'] = true;

			}
			else
			{	
				unset($data['id']);
			}
		}	
		
		if(isset($data['id']))
		{
			$this->generalForm($dbContext,$data,$parameters);
		}
	}


	public function generalForm(PDO $dbContext,array &$data,array &$parameters)
	{
		
		$userTypes = & WebUserType::findAll($dbContext);

		if(is_array($userTypes))
			$parameters['idUserType'] = & $userTypes;
		else
			$parameters['idUserType'] = array();		

		require_once(TEMPLATES_PATH.'/tForm.php');
	}


}

?>
