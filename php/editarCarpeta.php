<div class="content">
	<div class="titleContent">
		<p>Editar carpeta</p><input type="button" id="close" value="X">
	</div>

	<form id = 'frmEditarCarpeta'>
	  <input type="text" name="nombreCarpeta" id="nombreCarpeta" placeholder="Nombre de la carpeta" maxlength="30"> <div class= "error" id="errorNC"> </div> <br>
		<label for="permiso">Permiso</label>
	  	<select  name="permiso" id="permiso">
			<option value="0">Privada</option>
			<option value="1">Solo lectura</option>
			<option value="2">Solo Escritura</option>
			<option value="3">Lectura y Escritura</option>
		</select>
		<br>
		<input type="hidden"  name="carpetaPadre" id="carpetaPadre" value="">
		<input type="hidden"  name="autor" id="autor" value="1">
		<input type="hidden"  name="creador" id="creador" value="1">
		<input type="hidden"  name="ope" id="ope" value="8">
		<input type="hidden"  name="id" id="id" value="">
		<div class="btnConj">
			<input type="button" class="btnContent" id="close" value="Cancelar">
			<input type="button" class="btnContent" value="Editar carpeta" id="btnEditarCarpeta">
		</div>
	</form>
</div>

