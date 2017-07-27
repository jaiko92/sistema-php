//Enviar petición tipo POST sin necesidad de recargar la página
$(document).ready(function(){

	//Boton para enviar y guardar las ventas/utilidades - Operacion 1
	$("#envio_venta").click(function(){
		$.ajax({
			data: $("#form_envio_venta").serialize(),
			url: 'enviar_info.php', type: 'post',
			success: function(data) {
				$(".contenido").html(data);
			}//data
		});//ajax
		return false;
	});//click-function

	//Boton para enviar y guardar los gastos - Operacion 2
	$("#envio_gastos").click(function(){
		$.ajax({
			data: $("#form_envio_gastos").serialize(),
			url: 'enviar_info.php', type: 'post',
			success: function(data) {
				$(".contenido").html(data);
			}//data
		});//ajax
		return false;
	});//click-function

	//Boton y funcion para mostrar todos los pendientes - Operacion 3
	$("#envio_pendientes").click(function(){
		$.ajax({
			data: $("#form_elegir_pendientes").serialize(),
			url: 'enviar_info.php', type: 'post',
			success: function(data) {
				$(".contenido").html(data);
			}//data
		});//ajax
		return false;
	});//click-function

	function mostrar_pendientes(){
		$.ajax({
			data: $("#form_elegir_pendientes").serialize(),
			url: 'enviar_info.php', type: 'post',
			success: function(data) {
				$(".contenido").append(data);
			}//data
		});//ajax
	}

	//Boton para actualizar las ventas, las utilidades y los gastos - Operacion 4
	$(document).on("click","#actualizar_pendiente",function(e){
		$.ajax({
			data: $(this).parent().parent().serialize(),
			url: 'enviar_info.php', type: 'post',
			success: function(data) {
				$(".contenido").html(data);
				mostrar_pendientes();
			}//data
		});//ajax
		return false;
	});//click-function

	//Funciones para recuperar los datos del usuario actual - Operación 5
	function mostrar_usuario(){
		$.ajax({
			data: {'op': 5 , 'usuario': $("#datos_usuario").attr("data-user") },
			url: 'enviar_info.php', type: 'post',
			success: function(data) {
				$(".usuario").html(data);
			}//data
		});//ajax
	}

	$("#datos_usuario").ready(function(){
		mostrar_usuario();
	});//click-function

	//Boton para actualizar datos de usuario actual - Operacion 6
	$("#btn_editar_usuario").click(function(){
		$.ajax({
			data: $("#editar_usuario").serialize(),
			url: 'enviar_info.php', type: 'post',
			success: function(data){
				$(".contenido").html(data);
			}//data
		});//ajax
		return false;
	});//click-function

	//Boton para eliminar el contenido generado
	$(".elemento_menu_nav").click(function(event) {
		$(".contenido").empty();
	});

});//ready-function