<?php

require_once("AppObject.php");

class WebUserType implements AppObject
{
  private $removed = false;

  private $name;
  private $id;

  private function __construct(PDOStatement $stmt)
  {
    if(isset($stmt))
      {
	$result = $stmt->fetch(PDO::FETCH_ASSOC);

	$this->name = $result["name_web_type_user"];
	$this->id   = $result["id_web_type_user"];

	$this->removed = false;
      }
    else
      $this->removed = true;		
  }

  private static function & findFromStatement(PDOStatement $stmt)
  {
    if(! $stmt->execute() )
      return AppObject::DB_ERROR;

    $numRows = $stmt->rowCount();

    $resultArray = array();

    for($i=0;$i<$numRows;$i++)
      {
	$resultArray[] =  new WebUserType($stmt);
      }

    return $resultArray;		

  }

  public static function & findAll(PDO $dbContext)
  {
    $sqlQuery = 
      "select * from web_type_user order by name_web_type_user";
	
    $stmt = $dbContext->prepare($sqlQuery);

    return self::findFromStatement($stmt);
  }

  public static function findById(PDO $dbContext,$id)
  {	
    $sqlQuery = 
      "select * from web_type_user where id_web_type_user=:id";

    $stmt = $dbContext->prepare($sqlQuery);
    $stmt->bindParam(':id', $id);

    if(! $stmt->execute() )
      return AppObject::DB_ERROR;

    if($stmt->rowCount() != 1)
      return AppObject::NOT_OK;
		
    return new WebUserType($stmt);
  }

  // TODO Implement 
  public static function & findByColumns(PDO $dbContext,array &$columns)
  {
  }


  // TODO: Implement DB data insertion
  public static function create(PDO $dbContext,$name)
  {
    return AppObject::OK;								
  }
	
  public static function createWithColumns(PDO $dbContext,array &$columns)
  {
    if( ! array_key_exists("name",$columns) )
      {
	return AppObject::NOT_OK;	
      }

    if( ! isset($columns["name"] ) )
      {
	return AppObject::NOT_OK;
      }

    return self::create($dbContext,$columns["name"]);
  }

  // TODO Implement DB data upate
  public static function update(PDO $dbContext,$name,$id)
  {
    return AppObject::OK;								
  }

  // TODO Implement DB data upate
  public static function updateWithColumns(PDO $dbContext,array &$columns)
  {
  }



  public function getId()
  {
    return $this->id;
  }

  public function getName()
  {
    return $this->name;
  }

  public function remove(PDO $dbContext)
  {
    if($this->removed)
      return AppObject::NOT_OK;

    //
    //
    $sqlQuery =
      "delete from web_type_user where id_web_type_user=:id";

    $stmt = $dbContext->prepare($sqlQuery);
    $stmt->bindParam(':id', $this->id);

    if(! $stmt->execute() )
      {
	print_r($this);
	print_r($stmt->errorInfo());
	return AppObject::DB_ERROR;
      }

    if( $stmt->rowCount() != 1)
      return AppObject::NOT_OK;
	
    $this->removed = true;
	
    return AppObject::OK;				
  }	

}


?>
