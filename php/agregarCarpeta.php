<div class="content">
	<div class="titleContent">
		<p>Crear una nueva carpeta</p><input type="button" id="close" value="X">
	</div>

	<form id = 'frmAgregarCarpeta'>
	  <input type="text" name="nombreCarpeta" id="nombreCarpeta" placeholder="Nombre de la carpeta" maxlength="30"> <div class= "error" id="errorNC"> </div> <br>
		<label for="permiso">Permiso</label>
	  	<select  name="permiso" id="permiso">
			<option value="0">Privada</option> 
			<option value="1">Solo lectura</option>
			<option value="2">Solo Escritura</option>
			<option value="3">Lectura y Escritura</option>
			<option value="4">Lectura, Escritura y Borrado</option>
		</select>
		<br>
		<input type="hidden"  name="carpetaPadre" id="carpetaPadre" value="">
		<input type="hidden"  name="autor" id="autor" value="1">
		<input type="hidden"  name="creador" id="creador" value="1">
		<input type="hidden"  name="ope" id="ope" value="3">
		<div class="btnConj">
			<input type="button" class="btnContent" id="close" value="Cancelar">
			<input type="button" class="btnContent" value="Crear carpeta" id="btnAgregarCarpeta">
		</div>
	</form>

</div>