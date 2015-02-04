<?php

interface AppObject
{
	const OK             = true;
	const NOT_OK         = false;
	const DB_ERROR       = 1;
	const ALREADY_EXISTS = 2;

	public static function & findAll(PDO $dbContext);
	public static function findById(PDO $dbContext,$id);
	public static function & findByColumns(PDO $dbContext,array &$columns);

	public static function updateWithColumns(PDO $dbContext,array &$columns);

	public static function createWithColumns(PDO $dbContext,array &$columns);

	public function remove(PDO $dbContext);
}

?>
