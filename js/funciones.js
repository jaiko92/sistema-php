//Enviar petición tipo POST sin necesidad de recargar la página
$(document).ready(function(){
	//Boton para enviar y guardar las ventas/utilidades
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

	//Boton para enviar y guardar los gastos
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

	//Boton para eliminar el contenido generado
	$(".elemento_menu_nav").click(function(event) {
		$(".contenido").empty();
	});
});//ready-function