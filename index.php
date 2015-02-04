<?php
//inicia la sesion php
session_start();

//variables de entorno
define("INDEX_PATH"     ,dirname(__FILE__));
define("WEBCONTENT_PATH",dirname($_SERVER["SCRIPT_NAME"]));
require_once(INDEX_PATH.'/config/var.php');
require_once(CLASSES_PATH.'/App.php');
require_once(ACTIONS_PATH.'/WebUserActions.php');

//crea una instacia de la aplicación si no existe (singleton)
$app = App::create();

//registra las acciones disponibles
$app->regAction("WebUserActions.search");
$app->regAction("WebUserActions.remove");
$app->regAction("WebUserActions.view");
$app->regAction("WebUserActions.update"  ,'POST');
$app->regAction("WebUserActions.create"  ,'POST');

$app->regAction("WebUserActions.editForm");
$app->regAction("WebUserActions.createForm");
$app->regAction("WebUserActions.principalForm",'none');

//establece la acción por defecto
$app->setDefaultAction("WebUserActions.principalForm");

//ejecuta
$app->run();

?>
