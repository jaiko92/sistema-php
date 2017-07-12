<div class="content">
	<div class="titleContent">
		<p>Editar archivo</p><input type="button" id="close" value="X">
	</div>

	<form id = 'frmEditarArchivo'>
	  <input type="text" name="nombreArchivo" id="nombreArchivo" placeholder="Nombre del archivo" maxlength="30"> <div class= "error" id="errorNC"> </div> <br>
		<input type="hidden"  name="idCarpeta" id="idCarpeta" value="">
		<input type="hidden"  name="idUsuario" id="idUsuario" value="1">
		<input type="hidden"  name="ope" id="ope" value="9">
		<input type="hidden"  name="id" id="id" value="">
		<div class="btnConj">
			<input type="button" class="btnContent" id="close" value="Cancelar">
			<input type="button" class="btnContent" value="Editar archivo" id="btnEditarArchivo">
		</div>
	</form>
</div>