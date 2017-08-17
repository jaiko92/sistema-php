//Enviar petición tipo POST sin necesidad de recargar la página
$(document).ready(function(){

	//Generar facturas pendientes - Operacion 1
	$("#btn_facturas_pendientes").click(function(){
		$.ajax({
			data: $("#form_facturas_pendientes").serialize(),
			url: 'enviar_info.php', type: 'post',
			success: function(data) {
				$(".contenido").html(data);
			}//data
		});//ajax
		return false;
	});//click-function

	function facturas_pendientes(){
		$.ajax({
			data: $("#form_facturas_pendientes").serialize(),
			url: 'enviar_info.php', type: 'post',
			success: function(data) {
				$(".contenido").append(data);
			}//data
		});//ajax
	}

	//Boton para actualizar el costeo - Operacion 2
	$(document).on("click","#btn_actualizar_costeo",function(e){
		$.ajax({
			data: $(this).parent().parent().serialize(),
			url: 'enviar_info.php', type: 'post',
			success: function(data) {
				$(".contenido").html(data);
				facturas_pendientes();
			}//data
		});//ajax
		return false;
	});//click-function

	//Generar facturas canceladas - Operacion 3
	$("#btn_facturas_canceladas").click(function(){
		$.ajax({
			data: $("#form_facturas_canceladas").serialize(),
			url: 'enviar_info.php', type: 'post',
			success: function(data) {
				$(".contenido").html(data);
			}//data
		});//ajax
		return false;
	});//click-function

	//Generar autocompletado - Operación 4
	//Generar posibles resultados para autocompletado
	$(function(){
		$.ajax({
			data: {'op': 4, 'dato': "rfc"},
			url:'enviar_info.php', type: 'post',
			success: function(data){
				var tags_rfc = data.split(",");

				$("#buscador_rfc").autocomplete({
					source: tags_rfc
				});
			}
		});

		$.ajax({
			data: {'op': 4, 'dato': "rsocial"},
			url:'enviar_info.php', type: 'post',
			success: function(data){
				var tags_rsocial = data.split(",");

				$("#buscador_rsocial").autocomplete({
					source: tags_rsocial
				});
			}
		});

		$.ajax({
			data: {'op': 4, 'dato': "agente"},
			url:'enviar_info.php', type: 'post',
			success: function(data){
				var tags_agente = data.split(",");

				$("#buscador_agente").autocomplete({
					source: tags_agente
				});
			}
		});

	});

	//Generar facturas en general - Operación 5
	$("#btn_facturas").click(function(){
		$.ajax({
			data: $("#form_facturas").serialize(),
			url: 'enviar_info.php', type: 'post',
			success: function(data) {
				$(".contenido").html(data);
			}//data
		});//ajax
		return false;
	});//click-function

	//Mostrar metas mensuales - Operación 6
	$("#btn_mostrar_metas").click(function(){
		$.ajax({
			data: $("#form_mostrar_metas").serialize(),
			url: 'enviar_info.php', type: 'post',
			success: function(data) {
				$(".contenido").html(data);
			}//data
		});//ajax
		return false;
	});//click-function

	//Ingresar meta mensual - Operación 7
	$("#btn_ingresar_metas").click(function(){
		$.ajax({
			data: $("#form_ingresar_metas").serialize(),
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

	//Boton para generar los reportes en la pagina web - Operacion 7
	$("#btn_generar_reporte").click(function(){
		$.ajax({
			data: $("#form_generar_reporte").serialize(),
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