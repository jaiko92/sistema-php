

<!DOCTYPE html>
<html lang="es">
<head>
	<meta charset="UTF-8">
	<title>Unishare - Inicio</title>


	<script src="resources/jquery-1.12.4.js"></script>
	<script src="resources/jquery-ui.js"></script>
	<script src="resources/jquery-upload.js"></script>
	<script>
$(document).ready(function(){

		$(document).on("click", "#cerrarSesion", function(){
			location.href='resources/cerrarSesion.php';
		});

		$('a').click(function(e) {
			e.preventDefault();  //stop the browser from following
			window.location.href = 'img/banner.jpg';
		});



});

	</script>

</head>
<body>

<a href="no-script.html">Download now!</a>

</body>
</html>
