<?php
//
//  Template
//
$disable = "";
if(isset($parameters['view']))
{
	$disabled = 'disabled';
}

?>
<html>
<head>
	<META HTTP-EQUIV="Content-Type" CONTENT="text/html; charset=iso-8859-1">
	<script src="<?php echo STATIC_WEBCONTENT_PATH; ?>/js/jquery-1.4.2.min.js" type="text/javascript" charset="utf-8"></script>	
</head>
<body>

<div id="content">

<!--

  TODO: Form Validation using Javascript, using RegExp.

-->

<form id="createForm" method='post' enctype="multipart/data-form" action="javascript:void(0);" onsubmit="return false;">
	<label for="tname"><small>Nombre&nbsp;</small></label> 
        <input type="text" name="name" id="tname"  size="16" value="<?php echo $parameters['name'];?>" <?php echo $disabled; ?> /> 
	<br/>
	<label for="tlastName"><small>Apellido&nbsp;</small></label> 
        <input type="text" name="lastName" id="tlastName"  size="16" value="<?php echo $parameters['lastName'];?>" <?php echo $disabled;?>/> 
	<br/> 
	<label for="temain"><small>Correo electronico&nbsp;</small></label> 
        <input type="text" name="email" id="temail"  size="16" value="<?php echo $parameters['email'];?>" <?php echo $disabled; ?>/> 
	<br/> 
	<label for="tidUserType"><small>Tipo de usuario&nbsp;</small></label> 
	<select name="idUserType" id="tidUserType"  <?php echo $disabled;?> >
		<option value='-1'>&gt;&gt;</option>
<?php	
	foreach($parameters['idUserType'] as &$userType)
	{
		$checked = ($userType->getId() == $parameters['actualIdUserType']) && isset($parameters['actualIdUserType']) ? 'selected="selected" checked' : '';
		echo "<option value=\"".$userType->getId()."\"  $checked >".$userType->getName()."</option>\n";
	}	
?>
	</select>
	<br/>
<?php  if(isset($parameters['view'])): ?>
	<label for="tdateAccess"><small>fecha de Acceso&nbsp;</small></label> 
        <input type="text" name="dateAccess" id="tdateAccess"  size="16" value="<?php echo $parameters['dateAccess'];?>" <?php echo $disabled;?>/> 
	<br/> 
	<label for="tipAccess"><small>Ip&nbsp;</small></label> 
        <input type="text" name="ipAccess" id="tipAccess"  size="16" value="<?php echo $parameters['ipAccess'];?>" <?php echo $disabled;?>/> 
	<br/> 
<?php  endif; ?>

<?php  if(isset($parameters['id'])): ?>
	<input type="hidden" name="id" value="<?php echo $parameters['id'];?>" />
<?php  endif; ?>
	<input type="hidden" name="appAction" value="<?php echo $parameters['appAction']['currentAction'];?>" />
        <input type="submit" name="create_submit" value=" Aceptar " /> 
<?php  if(!isset($parameters['view'])): ?>
	<input type="button" name="cancel_button" id="tcancel_button"value=" Cancelar  " />
<?php  endif; ?>


</form>
<script type="text/javascript" charset="utf-8"> 
	$(function(){
		$("#createForm")
		.attr('action','<?= WEBCONTENT_PATH ?>/')
		.removeAttr('onSubmit');
<?php	if(!isset($parameters['view'])): ?>

		$("#tcancel_button")
		.click(function(){
			document.location = "<?= WEBCONTENT_PATH ?>/";			
		});
<?php      endif; ?>
	});
</script>
</div>
</body>
</html>
