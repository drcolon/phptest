<?php

require_once("AppObject.php");

class WebUser implements AppObject
{
  private $removed = false;

  private $name;
  private $lastName;
  private $email;
  private $idType;
  private $dateAccess;
  private $ipAccess;
  private $id;

  private function __construct(PDOStatement $stmt)
  {
    if(isset($stmt))
      {
	$result = $stmt->fetch(PDO::FETCH_ASSOC);

	$this->name       = $result["name_web_user"];
	$this->lastName   = $result["last_name_web_user"];
	$this->email      = $result["email_web_user"];
	$this->idType     = $result["id_type_web_user"];
	$this->dateAccess = $result["date_access_web_user"];
	$this->ipAccess   = $result["ip_access_web_user"];
	$this->id         = $result["id_web_user"];

	$this->removed    = false;
      }		
    else
      $this->removed = true;

  }

  // TODO Implement
  public static function & findAll(PDO $dbContext)
  {
    return array();
  }

  public static function findById(PDO $dbContext,$id)
  {	
    $sqlQuery = 
      "select * from web_user where id_web_user=:id";

    $stmt = $dbContext->prepare($sqlQuery);
    $stmt->bindParam(':id', $id);

    if(! $stmt->execute() )
      return AppObject::DB_ERROR;

    if( $stmt->rowCount() != 1 )
      return AppObject::NOT_OK;
		
    return new WebUser($stmt);
  }

  public static function & findByColumns(PDO $dbContext,array &$columns)
  {
    $name = "%%";
    $lastName = "%%";
	
    if( array_key_exists("name",$columns) )
      if( isset($columns["name"] ) )
	$name = '%'.trim($columns["name"]).'%';

    if( array_key_exists("lastName",$columns) )
      if( isset($columns["lastName"] ) )
	$lastName = '%'.trim($columns["lastName"]).'%';

    //
    //
    $sqlQuery = 
      "select * from web_user where 
			   lower(name_web_user)      like lower(:name) and 
			   lower(last_name_web_user) like lower(:lastName)";

    $stmt = $dbContext->prepare($sqlQuery);
    $stmt->bindParam(':name'    , $name);
    $stmt->bindParam(':lastName', $lastName);

    if(! $stmt->execute() )
      return AppObject::DB_ERROR;

    $numRows = $stmt->rowCount();

    $resultArray = array();

    for($i=0;$i<$numRows;$i++)
      {
	$resultArray[] =  new WebUser($stmt);
      }

    return $resultArray;		
  }


  public static function createWithColumns(PDO $dbContext,array &$columns)
  {	
    //
    //
    $listOfColumns = 
      array(
	    'name'       => array('name_web_user',true),
	    'lastName'   => array('last_name_web_user',true),
	    'idUserType' => array('id_type_web_user',true),
	    'email'      => 'email_web_user',
	    'dateAccess' => 'date_access_web_user',
	    'ipAccess'   => 'ip_access_web_user'   
	    );
	
    $fixedColumns = array();

    foreach($listOfColumns as $columnName => &$value )
      {
	if(is_array($value))
	  {			
	    if( ! array_key_exists($columnName,$columns) )
	      return AppObject::NOT_OK;	
		
	    if( ! isset($columns[$columnName]) )
	      return AppObject::NOT_OK;
				
	    $fixedColumns[$value[0]] = trim($columns[$columnName]);				
	  }
	elseif( array_key_exists($columnName,$columns) )
	  if( isset($columns[$columnName]) )
	    $fixedColumns[$value] = trim($columns[$columnName]);		
      }

    //
    //
    $sqlQuery = 
      "select count(*) from web_user 
			 where lower(name_web_user)     = lower(:name) and 
			       lower(last_name_web_user)= lower(:lastName)";

    $stmt = $dbContext->prepare($sqlQuery);
    $stmt->bindParam(':name'    , $columns['name']);
    $stmt->bindParam(':lastName', $columns['lastName']);
	
    if(! $stmt->execute() )
      {
	return AppObject::DB_ERROR;
      }

    $result = $stmt->fetch(PDO::FETCH_NUM);
    $count  = $result[0]+0; 
		
    if($count > 0 )
      {
	return AppObject::ALREADY_EXISTS;
      }
	

    //
    //
    $fixedColumnNames = array_keys($fixedColumns);

    function columnNameReplace($v){return '?';};		

    $sqlQuery =
      "insert into web_user 
			(".implode(",",$fixedColumnNames).") 
			values 
			(".implode(",",array_map("columnNameReplace", range(1,count($fixedColumns))) ).")";

    $stmt = $dbContext->prepare($sqlQuery);
	
    if(! $stmt->execute(array_values($fixedColumns)) )
      {
	return AppObject::DB_ERROR;
      }

    if( $stmt->rowCount() != 1)
      {
	return AppObject::NOT_OK;
      }


    return AppObject::OK;								
  }


  // TODO Implement DB data update
  public static function updateWithColumns(PDO $dbContext,array &$columns)
  {	
    return AppObject::OK;								
  }




  public function getId()
  {
    return $this->id;
  }

  public function getName()
  {
    return $this->name;
  }

  public function getLastName()
  {
    return $this->lastName;
  }


  public function getEmail()
  {
    return $this->email;
  }
	
  public function getIdUserType()
  {
    return $this->idType;
  }

  public function getDateAccess()
  {
    return $this->dateAccess;
  }

  public function getIpAccess()
  {
    return $this->ipAccess;
  }

  // TODO Implement DB data delete
  public function remove(PDO $dbContext)
  {
  }	

}

?>
