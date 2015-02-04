<?php
// 
// Template
//
?>
<html>
<head>
	<META HTTP-EQUIV="Content-Type" CONTENT="text/html; charset=iso-8859-1">
	<script src="<?php echo STATIC_WEBCONTENT_PATH;?>/js/jquery-1.4.2.min.js" type="text/javascript" charset="utf-8"></script>	
</head>
<body>

<div id="content">
	<div class="search">
		<form id="search" method='post' enctype="multipart/data-form" action="javascript:void(0);" onsubmit="return false;">
			 <label for="tname"><small>Nombre&nbsp;</small></label> 
                         <input type="text" name="name" id="tname"  size="16" /> 
			 <br/>
			 <label for="tlastName"><small>Apellido&nbsp;</small></label> 
                         <input type="text" name="lastName" id="tlastName"  size="16" /> 
			 <br/> 
			 <input type="hidden" name="appAction" value="<?php echo $parameters['appAction']['search']; ?>" />
                         <input type="submit" name="search_submit" value=" Buscar " /> 
	 		 <input type="button" name="create_button" id="tcreate_button"value=" Crear  " />
		</form>

		<script type="text/javascript" charset="utf-8"> 
			$(function(){
				$("#search")
				.attr('action','<?= WEBCONTENT_PATH ?>/')
				.removeAttr('onSubmit');

				$("#tcreate_button")
				.click(function(){
					document.location = "<?= WEBCONTENT_PATH ?>/?appAction=<?=$parameters['appAction']['createForm'] ?>";			
				});

			});
		</script>
	</div>

	<div class="searchResults">
		<table id="searchResults">
<?
			foreach($parameters['searchResults'] as &$user):
?>
			<tr>
				<td>
				   <a href="<?= WEBCONTENT_PATH ?>/?appAction=<?=$parameters['appAction']['view'] ?>&id=<?= $user->getId() ?>" alt="editar"><?=$user->getName().", ".$user->getLastName() ?></a>
				</td>
				<td>
				   <a href="<?= WEBCONTENT_PATH ?>/?appAction=<?=$parameters['appAction']['editForm'] ?>&id=<?= $user->getId() ?>" alt="editar">editar</a>
				</td>
				<td>
				   <a href="<?= WEBCONTENT_PATH ?>/?appAction=<?=$parameters['appAction']['remove'] ?>&id=<?= $user->getId() ?>" alt="borrar">borrar</a>
				</td>
			
			</tr>

<?	
			endforeach;
?>

		</table>
	</div>

</div>
</body>
</html>
