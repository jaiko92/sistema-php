<?php 
	session_start();
	if(isset($_SESSION['idUsuario'], $_SESSION['nombreUsuario'])){
		echo "<script language='javascript'>
				location.href='menu_trabajo.php'
			</script>";
	}else{
		$URL = $_GET['lru'];
		$Permiso = $_GET['pms'];
		$Autor = $_GET['aut'];
		echo "<script>alert('".$URL."');</script>";
	}
 ?>

<!DOCTYPE html>
<html lang="es">
<head>
	<meta charset="UTF-8">
	<title>Unishare - Inicio</title>
	<meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
	<link rel="stylesheet" href="css/style.css">
	<script src="resources/jquery-1.12.4.js"></script>
		<script>
	$(document).ready(function(){

		$(document).bind("contextmenu", function(e){  return false; });
		$(".upMenu").fadeOut(0);

		$('.menu-folder').ready( function(){
			$.ajax({
				data:  {'ope': 4 , 'url': '<?php echo $URL ?>' },
				url: 'resources/operacionesBDD.php', type: "post",
				success:  function (dat) {	 $('.menu-folder').html(dat);
				$('.menu-folder').children('ul').children('li').find('ul').hide();
				}
			});
		});

		$(document).on("click",".menu-folder .carpeta",function(){
			$( this).parent().children('ul').slideToggle(400);
			if($( this).siblings('ul').height()=='1'){
				$( this).siblings('img').attr("src", "icons/folder-open-white.png");
			}else{
				if($( this).siblings('img').attr("id")=='0'){
					$( this).siblings('img').attr("src", "icons/folder-white.png");
				}else{
					$( this).siblings('img').attr("src", "icons/folder-white-link.png");
				}
			}
		});

		$(document).on("click", "#button", function(){
			if($(window).width()<=600){
				$('aside').slideToggle(400);
			}
		});

	    $(document).click(function(e){
			if(e.button == 0){
		    	$(".contextMenu").css("display", "none");
			}
	    });

	    $(document).keydown(function(e){
			if(e.keyCode == 27){
				$(".contextMenu").css("display", "none");
			}
		});

		$(document).on("dblclick",".menu-folder .carpeta",function(){
			obtenerHijosCarpeta( $(this).attr("id") ) ;
		});

		$(document).on("dblclick",".folder-files .carpeta",function(){
				obtenerHijosCarpeta( $(this).attr("id") ) ;
		});

		$(document).on("dblclick",".regresar",function(){
				obtenerHijosCarpeta( $(this).attr("id") ) ;
		});

		$('.folder-files').ready( obtenerHijosCarpeta('<?php echo $URL ?>') );

		function obtenerHijosCarpeta(padre) {
			$.ajax({
				data:  {'ope': 5 , 'autor': '<?php echo $Autor ?>' , 'padre' : padre },
				url: 'resources/operacionesBDD.php', type: "post",
				success:  function (dat) {
					$('.files').html(dat);
				}
			});
		}

	});

	</script>
</head>
<body>

	<header>

		<div class="container">

			<img src="img/logo_unishare_completo.jpg" alt="">

			<div class="btn-register">
				<div class="btn-header">
					<a href="register.php" id="registrarse">Registrarse</a>
				</div>

				<div class="btn-header">
					<a href="register.php" id="ingresar">Ingresar</a>
				</div>
			</div>

		</div>

	</header>
	
	<section class="files">
			
			<!--Contenido></!-->

	</section>

	<aside>
		
		<div class="menu-folder">
			
			<!--Contenido></!-->

		</div>
		
		<div class="information">
		</div>
	</aside>
		
</body>
</html>