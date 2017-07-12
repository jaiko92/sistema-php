<?php

require 'claseConexion.php';


// directoriosLeft 1
  function directoriosLeft($autor){
    $conexion =  new ConexionBDD();

     function subDirectorios(  $padre , $autor )
    {
      $conexion =  new ConexionBDD();
      $datos = $conexion->obtenerDatos( "SELECT * FROM `carpeta` WHERE `idAutor` = '$autor'  AND `idCarpetaPadre`  =   '$padre'  ORDER BY `nombre` ");
        echo "<ul>";
      foreach ($datos as $r) {
          echo "<li> <label>";
          if($r['permiso']==0){
              echo "<span class='icon-folder' id='".$r['permiso']."'></span>";
          }else{
              echo "<span class='icon-folder-upload id='".$r['permiso']."''></span>";
          }
          echo "<input type='button' class='carpeta' value = '". $r['nombre'] ."' id = '". $r['URL'] ."' >" ;
          subDirectorios( $r['URL'] , $autor);
           echo "</label></li>";
      }
        echo "</ul>";
    }

    echo "<ul>";
    $datos = $conexion->obtenerDatos( "SELECT * FROM `carpeta` WHERE `idAutor` = $autor AND `idCarpetaPadre` IS  NULL ORDER BY `nombre` ");
      foreach ($datos as $r) {
          echo "<li><label>";
          if($r['permiso']==0){
              echo "<span class='icon-folder' id='".$r['permiso']."'></span>";
          }else{
              echo "<span class='icon-folder-upload' id='".$r['permiso']."'></span>";
          }
          echo "<input type='button' class='carpeta' value = '". $r['nombre'] ."' id = '". $r['URL'] ."' >" ;
          subDirectorios( $r['URL'] , $autor);
           echo "</label></li>";
      }
        echo "</ul>";
  }

// directoriosRight 2
  function directoriosRight($autor , $padre){
    $conexion =  new ConexionBDD();

    $nomCarpeta = $conexion->resultadoUnico( 'carpeta' ,  'nombre' ,  " `URL`  =   '$padre' " );

    $permisoCarpeta = $conexion->resultadoUnico( 'carpeta' ,  'permiso' ,  " `URL`  =   '$padre' " );

    $idAutorCarpeta = $conexion->resultadoUnico( 'carpeta' ,  'idAutor' ,  " `URL`  =   '$padre' " );

    $idCreadorCarpeta = $conexion->resultadoUnico( 'carpeta' ,  'idCreador' ,  " `URL`  =   '$padre' " );

    echo "<div class='folder-name'>";

    $columnasD = " `idUsuario`, `carpeta`.`nombre` AS 'nombreCarpeta' , `usuario`.`nombre` AS 'nombreUsuario'  , `materno`, `paterno`, `correo`, `contrasena`, `estatus` , `URL`, `permiso`, `ultimaModificacion`, `idCarpetaPadre`, `idAutor`, `idCreador` " ;

    // Consulta Carpetas
    if ($padre == 'NULL' || $padre == NULL){ // Padre == null, significa carpetas raices
  //    $datos = $conexion->obtenerDatos( "SELECT * FROM `carpeta` WHERE `idAutor` = $autor AND `idCarpetaPadre` IS  NULL ORDER BY nombre ASC");
// SELECT  `idUsuario`, `carpeta`.`nombre` AS 'nombreCarpeta' , `usuario`.`nombre` AS 'nombreUsuario'  , `materno`, `paterno`, `correo`, `contrasena`, `estatus` , `URL`, `permiso`, `ultimaModificacion`, `idCarpetaPadre`, `idAutor`, `idCreador` FROM `carpeta`   INNER JOIN `usuario` ON `idUsuario` =  `idCreador`  WHERE 1 ORDER BY 'nombreCarpeta' ASC
      $condicionD = " `idCarpetaPadre`  IS NULL AND (`idAutor` = '$autor'   OR  `idCreador` = '$autor'  OR `permiso` > 0) "  ;
      $datos = $conexion->obtenerDatos( "SELECT $columnasD FROM `carpeta` INNER JOIN `usuario` ON `idUsuario` =  `idCreador`  WHERE $condicionD ORDER BY 'nombreCarpeta' ASC " );

    //  echo "SELECT $columnasD FROM `carpeta` INNER JOIN `usuario` ON `idUsuario` =  `idCreador`  WHERE $condicionD ORDER BY 'nombreCarpeta' ASC ";
      echo "<p>Directorio raíz</p>";
    }
    else
    {
      $padreMayor = $conexion->resultadoUnico( 'carpeta' ,  'idCarpetaPadre' ,  " `URL`  =   '$padre' " );
      echo "<p>".$nomCarpeta."</p>";
      echo "<input type='image' class='regresar' src='icons/arrow-left.png' id = '$padreMayor' >";
      $condicionD = " `idCarpetaPadre`  =   '$padre' AND (`idAutor` = '$autor'   OR  `idCreador` = '$autor'  OR `permiso` > 0) "  ;
      $datos = $conexion->obtenerDatos( "SELECT $columnasD FROM `carpeta` INNER JOIN `usuario` ON `idUsuario` =  `idCreador`  WHERE $condicionD ORDER BY 'nombreCarpeta' ASC " );

      //$datos = $conexion->obtenerDatos( "SELECT * FROM `carpeta` WHERE `idAutor` = '$autor'  AND `idCarpetaPadre`  =   '$padre' ORDER BY nombre ASC " );
    }

    echo "</div>";

    //Impresion de carpetas
    echo "<div class='folder-files'>
            <ul>";

    foreach ($datos as $r) {
        echo "<li><label title='Creado por: ". $r['nombreUsuario'] . " " . $r['materno'] . " " . $r['paterno'] . "' class='carpeta'
          value = '". $r['nombreCarpeta']."' id = '". $r['URL'] ."' data-idCreador='".$r['idCreador']."' data-idAutor='".$r['idAutor']."' data-permiso='".$r['permiso']."'>";
          if($r['permiso'] > 0){
            if($r['idCreador'] != $autor){
              echo "<span class='icon-folder-download'></span><br>";
            }else{
              echo "<span class='icon-folder-upload'></span><br>";
            }
          }else{
            echo "<span class='icon-folder'></span><br>";
          }
          echo "<input type='button' value = '". $r['nombreCarpeta']."'>" ;
         echo "</label></li>";
    }


    //$datos = $conexion->obtenerDatos( "SELECT * FROM `archivo` WHERE  `idCarpeta`  =   '$padre'  ORDER BY nombre ASC" );
  //SELECT `idArchivo`, `archivo`.`nombre` AS 'nombreArchivo', `tipo`, `fechaCreacion`, `archivo`.`url` AS 'archivoURL', `permisoN`, `idCarpeta`, `idUsuario`, `tamano` , `carpeta`.`URL` AS 'carpetaURL', `carpeta`.`nombre` AS 'nombreCarpeta' , `permiso`, `ultimaModificacion`, `idCarpetaPadre`, `idAutor`, `idCreador` FROM `archivo` INNER JOIN `carpeta` on `carpeta`.`URL` = `idCarpeta` WHERE 1
  $columnasD = " `idArchivo`, `archivo`.`nombre` AS 'nombreArchivo', `tipo`, `fechaCreacion`, `archivo`.`url` AS 'archivoURL', `permisoN`, `idCarpeta`, `idUsuario`, `tamano` , `carpeta`.`URL` AS 'carpetaURL', `carpeta`.`nombre` AS 'nombreCarpeta' , `permiso`, `ultimaModificacion`, `idCarpetaPadre`, `idAutor`, `idCreador` " ;
  $condicionD = " `idCarpeta`  =   '$padre'  and ( `idUsuario` = '$autor' OR `idAutor` = '$autor'  OR (`permiso` != 0 and `permiso` != 2) ) "  ;
  $datos = $conexion->obtenerDatos( "SELECT $columnasD FROM `archivo` INNER JOIN  `carpeta` on `carpeta`.`URL`  = `idCarpeta` WHERE $condicionD ORDER BY `nombreArchivo` ASC" );

    foreach ($datos as $r) {
        echo "<li><label class='archivo' value = '". $r['nombreArchivo'].".".$r['tipo']."' id = '". $r['idArchivo'] ."'  data-idUsuario='".$r['idUsuario']."' data-permiso='".$r['permiso']."'>";
        switch($r['tipo']){

            case 'img':
              echo "<span id='img' class='icon-image'></span><br>";
            break;

            case 'png':
              echo "<span id='png' class='icon-image'></span><br>";
            break;

            case 'gif':
              echo "<span id='gif' class='icon-image'></span><br>";
            break;

            case 'pdf':
              echo "<span id='pdf' class='icon-file-pdf'></span><br>";
            break;
            
            case 'doc':
              echo "<span id='doc' class='icon-file-word'></span><br>";
            break;
            
            case 'docx':
              echo "<span id='doc' class='icon-file-word'></span><br>";
            break;
            
            case 'xls':
              echo "<span id='xls' class='icon-file-excel'></span><br>";
            break;
            
            case 'xlsx':
              echo "<span id='xls' class='icon-file-excel'></span><br>";
            break;
            
            case 'mp3':
              echo "<span id='mp3' class='icon-music'></span><br>";
            break; 
            
            case 'mp4':
              echo "<span id='mp4' class='icon-video'></span><br>";
            break;
            
            case 'gp3':
              echo "<span id='3gp' class='icon-video'></span><br>";
            break;
            
            default:
              echo "<span id='file' class='icon-file'></span><br>";
            break;

        }
        echo "<input type='button' value = '". $r['nombreArchivo'].".".$r['tipo']."'>" ;
         echo "</label></li>";
    }
      echo "</ul> <input type='hidden'  name='carpetaActual' id='carpetaActual' value='$padre' data-idAutor='$idAutorCarpeta' data-permiso='$permisoCarpeta' data-nomCarpeta='$nomCarpeta'>";

    echo "</div>";
  }

    // agregarCarpeta 3
  function agregarCarpeta(){
      $conexion =  new ConexionBDD();
      $nId = getdate() ;

      $datos =array(
      array( 'nombre' , $_REQUEST['nombreCarpeta'] ) ,
      array( 'idAutor' , $_REQUEST['autor'] ),         array( 'idCreador' , $_REQUEST['creador'] ),
      array( 'permiso' ,  $_REQUEST['permiso'] ) ,     array( 'URL' ,  MD5($nId[0] .  $nId["yday"]) )
      );

      if ($_REQUEST['carpetaPadre'] != '' &&  $_REQUEST['carpetaPadre'] != 'NULL')
            array_push($datos , array( 'idCarpetaPadre' ,  $_REQUEST['carpetaPadre'] ) );

      $conexion->insertarDato('carpeta',$datos);
    }

    // agregarCarpeta 6
    function eliminarCarpeta($autor, $padre){
      $conexion =  new ConexionBDD();
      // Consulta Carpetas
      if ($padre == 'NULL' || $padre == NULL){ return ; }// Padre == null, significa carpetas raices
      else
      {
        $datos = $conexion->obtenerDatos( "SELECT * FROM `carpeta` WHERE `idAutor` = '$autor'  AND `idCarpetaPadre`  =   '$padre' " );
        foreach ($datos as $r) {
          eliminarCarpeta( $autor , $r['URL'] );
          $ficheros = $conexion->obtenerDatos( "SELECT * FROM `archivo` WHERE  `idCarpeta`  =   '$padre' " );
          foreach ($ficheros as $f) {
            if (file_exists( "../" . $f['url'] . "." . $f['tipo'] ) ) {   unlink ( "../" . $f['url'] . "." . $f['tipo'] );  }
              $conexion->eliminarDato('archivo' , 'idArchivo' , $f['idArchivo'] );
          }
        }
        $conexion->eliminarDato('carpeta' , 'URL' , $padre );
      }
    }

  // agregarCarpeta 7
    function eliminarArchivo ($id ){
        $conexion =  new ConexionBDD();
        //echo "Aqui";
        $f =   $conexion->resultadoUnico( 'archivo' , 'url' , " `idArchivo` = '$id' ") . "." .
              $conexion->resultadoUnico( 'archivo' , 'tipo' , " `idArchivo` = '$id' ");
        if (file_exists( "../" . $f ) ) {   unlink ( "../" . $f );  }
        $conexion->eliminarDato('archivo' , 'idArchivo' , $id );
      }

      // modificarNombreCarpeta 8
    function modificarCarpeta( $id ){
        $conexion =  new ConexionBDD();
        $datos =array(   array( 'nombre' , $_REQUEST['nombreCarpeta'] ) ,  array( 'permiso' , $_REQUEST['permiso'] )  );
        $conexion->actualizarDato('carpeta', $datos , " `URL`  LIKE  '$id' ");
      }


            // modificarNombreArchivo 9
    function modificarNombreArchivo( $id ){
        $conexion =  new ConexionBDD();
        $datos =array(   array( 'nombre' , $_REQUEST['nombreArchivo'] ) );
        $conexion->actualizarDato('archivo', $datos , " `idArchivo`  LIKE  '$id' ");
      }

// operacion 11 o cd
  function comprobarDato($tabla , $consulta){
      $conexion =  new ConexionBDD();
      $sql = "SELECT *  FROM `$tabla` WHERE $consulta";
  //    echo $sql;
      if  ($conexion->existeDato($sql) ) echo '0' ;  else echo '1';
    }



    // agregarArchivos 12
  function agregarArchivos(){
    $conexion =  new ConexionBDD();
    $nameIMG = '';   $nId = getdate() ;

    if ( !isset( $_FILES["arch"] )) return 0;

    $f = $_FILES["arch"] ;

    $c = count($f['name']);
    //var_dump($f);

    for ($i=0; $i < $c ; $i++) {
      if ($f["name"][$i] != '' ) {
        $nCarpeta = rand( 1, 50) ;
        $ruta = "multimedia/";
        $infoArchivo =  pathinfo( $f["name"][$i]);
        $nameIMG =  MD5(( rand() - rand()  + rand() * rand() ) . $nId["yday"].  $nId[0] )  ;
        @copy ( $f["tmp_name"][$i],'../' . $ruta.$nameIMG. '.' . $infoArchivo['extension']) ;

        $datos =array(
              array( 'url' , $ruta.$nameIMG ) ,
              array( 'tipo' , $infoArchivo['extension'] ),   array( 'nombre' , $infoArchivo['filename'] ),
               array( 'idUsuario' ,   $_REQUEST['creador']  ) // array( 'permiso' , $_REQUEST['permiso'] ),
              //    ,     array( 'tamano' ,  f["size"][$i]  )
        );

        if ($_REQUEST['carpetaPadre'] != '' &&  $_REQUEST['carpetaPadre'] != 'NULL')
          array_push($datos , array( 'idCarpeta' ,  $_REQUEST['carpetaPadre'] ) );

          $conexion->insertarDato('archivo',$datos);
      }
    }
  }

// obtenerArchivo 13
  function obtenerArchivo ($id ){
      $conexion =  new ConexionBDD();
      //echo "Aqui";
      echo  $conexion->resultadoUnico( 'archivo' , 'url' , " `idArchivo` = '$id' ") . "." .
            $conexion->resultadoUnico( 'archivo' , 'tipo' , " `idArchivo` = '$id' ");
    }

//agregarArchivosZIP 14
  function agregarArchivosZIP()
  {
    $conexion =  new ConexionBDD();   $archivoZip = new ZipArchive;
    $nId = getdate() ;
    $archivoZip->open($_FILES["fileZIP"]['tmp_name']);
  //  $archivoZip->open('1.zip');
    $carpetaTmp = "tmp";

    $r = $archivoZip->extractTo($carpetaTmp);
    if($r == TRUE){
        for ($x =  $archivoZip->numFiles ; $x >= 0; $x--) {
          $archivo = $archivoZip->statIndex($x);
          if (!is_dir($carpetaTmp .'/' . $archivo['name']))
          {
            $ruta = "multimedia/";
            $infoArchivo =  pathinfo( $archivo['name'] ) ;
            $nameF =  MD5(( rand() - rand()  + rand() * rand() ) . $nId["yday"].  $nId[0] )  ;
            @copy ( $carpetaTmp . '/' . $archivo['name']  , '../' . $ruta.$nameF. '.' . $infoArchivo['extension']) ;
        //    echo $archivo['name'] . '<br>' ;
            $datos =array(
                  array( 'url' , $ruta.$nameF ) ,                 array( 'tipo' , $infoArchivo['extension'] ),
                   array( 'nombre' , $infoArchivo['filename'] ),  array( 'idUsuario' ,   $_REQUEST['creador']  )
                  //    ,     array( 'tamano' ,  f["size"][$i]  )
            );

            if ($_REQUEST['carpetaPadre'] != '' &&  $_REQUEST['carpetaPadre'] != 'NULL')
              array_push($datos , array( 'idCarpeta' ,  $_REQUEST['carpetaPadre'] ) );

            $conexion->insertarDato('archivo',$datos);
            @unlink($carpetaTmp .'/'. $archivo['name'] );
          }
          else { @rmdir($carpetaTmp .'/'. $archivo['name'] ); }
      }
    }
    $archivoZip->close();
  //  rmdir ($carpetaTmp);  mkdir($carpetaTmp );

  }

//agregarArchivosZIP 15
  function descargarArchivosZIP($carpetaPadre , $nombre)
  {
    $tmp = "RAR";
    $conexion =  new ConexionBDD(); $archivoZip = new ZipArchive;
    $datos = $conexion->obtenerDatos( "SELECT * FROM `archivo` WHERE  `idCarpeta`  =   '$carpetaPadre'  ORDER BY `nombre` ASC" );
    if ($archivoZip->open($tmp . '/' .$nombre.".zip", ZIPARCHIVE::CREATE )!==TRUE) return;
    foreach ($datos as $r) {
      $archivoZip->addFile( "../".$r['url'] . "." .  $r['tipo']  , $r['nombre'] . "." .  $r['tipo'] );
    }
  // $archivoZip->renameName('multimedia','files');
    $archivoZip->close();

    $enlace = $tmp . '/' .$nombre.".zip";
    if ( file_exists ( $enlace ))
    {
      echo 'resources/' . $enlace;
      //header ("Content-Type: ". mime_content_type (  $enlace ));
      //header ("Content-Disposition: attachment; filename=".$nombre.".zip");
      //header('Content-Transfer-Encoding: binary');
      //readfile($enlace);
      //@unlink($enlace );
    //  echo "<p>un archivo descargado</p>";
    }
  }

// operacion 16 o cu
  function columnaUnica($consulta){
    //echo $consulta;
    $conexion =  new ConexionBDD();
    $datos = $conexion->obtenerDatos($consulta);
    if (isset($datos[0])) print json_encode($datos[0]);
  }

//modificarPerfil 17
  function modificarPerfil( $id ){
    $conexion =  new ConexionBDD();
    if($_REQUEST['contraseña'] != ''){
        $datos =array(  array( 'nombre' , $_REQUEST['nombre'] ) , array('materno' , $_REQUEST['materno'] ),
        array('paterno' , $_REQUEST['paterno'] ) , array('correo' , $_REQUEST['correo']  ) ,
        array('contrasena' , md5("unishare".$_REQUEST['contraseña'] ."erahsinu")   )  );
    }else{
        $datos =array(  array( 'nombre' , $_REQUEST['nombre'] ) , array('materno' , $_REQUEST['materno'] ),
        array('paterno' , $_REQUEST['paterno'] ) , array('correo' , $_REQUEST['correo']  )  );
    }
    
    $conexion->actualizarDato('usuario', $datos , " `idUsuario`  LIKE  '$id' ");
  }

// enviarCorreo 25
    function enviarCorreo ($nombreElemento, $elemento, $nomViejo, $idUsuario, $idCarpetaPadre, $opc){
        $conexion =  new ConexionBDD();
        if($elemento == 'arc'){
          switch ($opc) {
            case 0:
              $complementoMensaje = " ha agregado un nuevo archivo llamado ";
              $titulo = "Se ha agregado un archivo";
              break;
            
            case 1:
              $complementoMensaje = " ha editado el archivo que se llamaba ".$nomViejo." y hoy se llama ";
              $titulo = "Se ha editado un archivo";
              break;
                        
            case 2:
              $complementoMensaje = " ha eliminado el archivo ".$nomViejo;
              $titulo = "Se ha borrado un archivo";
              break;
            
            default:
              # code...
              break;
          }
        }else{
          switch ($opc) {
            case 0:
              $complementoMensaje = " ha agregado una nueva carpeta llamada ";
              $titulo = "Se ha agregado una carpeta";
              break;
            
            case 1:
              $complementoMensaje = " ha editado la carpeta que se llamaba ".$nomViejo." y hoy se llama ";
              $titulo = "Se ha editado una carpeta";
              break;
                        
            case 2:
              $complementoMensaje = " ha eliminado la carpeta ".$nomViejo;
              $titulo = "Se ha borrado una carpeta";
              break;
            
            default:
              # code...
              break;
          }
        }
        $datosUsuario = $conexion->obtenerDatos( "SELECT * FROM `usuario` WHERE  `idUsuario`  =   '$idUsuario'" );

        $datosCarpetaPadre = $conexion->obtenerDatos( "SELECT * FROM `carpeta` WHERE  `URL`  =   '$idCarpetaPadre'" );

        $complementoDia = " el día ";
        $complementoHora = " a la hora de ";

        if($idCarpetaPadre != ''){
          $complementoCarpetaPadre = " en la carpeta ".$datosCarpetaPadre['nombre'].".";
        }else{
          $complementoCarpetaPadre = " en la carpeta raíz.";
        }

        $nombre = $datosUsuario['nombre']." ".$datosUsuario['paterno']." ".$datosUsuario['materno'];

        $fecha = getdate();
        $dia = $fecha["mday"]."/".$fecha["month"]."/".$fecha["year"];
        $hora = $fecha["hours"].":".$fecha["minutes"].":".$fecha["seconds"];

        $Correo = $conexion->obtenerDatos( "SELECT * FROM `usuario` WHERE `estatus` == 1" );

        $Mensaje = "Hola buen día\r\n".$nombre.$complementoMensaje.$nombreElemento.$complementoDia.$dia.$complementoHora.$hora.$complementoCarpetaPadre;
        $Cabecera = "From: no-reply@unishare.com";

        foreach ($Correo as $cUser) {
            mail($cUser["correo"], $titulo, $Mensaje, $Cabecera);
        }
        

    }

//checar solicitudes 26
    function obtenerSolicitudes(){
      $conexion = new ConexionBDD();
      $datos = $conexion->obtenerDatos("SELECT * FROM `usuario` WHERE  `estatus` = 0 ORDER BY `paterno` ASC");
      echo "<div class='folder-name'><p>Solicitudes de registro</p><input type='image' class='regresar' src='icons/arrow-left.png' id = '' >
      <input type='hidden'  name='carpetaActual' id='carpetaActual' value='oS'></div>";
      echo "<div class='tablaSolicitud'><table>
            <tr><th>Nombre</th><th>Correo</th><th>Estatus</th><th></th></tr>";

            foreach($datos as $dUsuario){
              echo "<form id='frmGuardarSolicitud'>
                      <tr>
                      <td>".$dUsuario['paterno']." ".$dUsuario['materno']." ".$dUsuario['nombre']."</td>
                      <td>".$dUsuario['correo']."</td>
                      <td><select name='status' id='status'>
                            <option value='2' selected='selected'>Solicitud</option>
                            <option value='0'>Usuario</option>
                            <option value='1'>Administrador</option>
                            <option value='3'>Rechazado</option>
                          </select></td>
                      <td><input type='hidden' name='cUsuario' id='cUsuario' value='".$dUsuario['idUsuario']."'>
                      <input type='button' class='btnSolicitud' name='btnActualizarSolicitud' id='btnActualizarSolicitud' value='Guardar'></td>
                      </tr>

                    </form>";
            }
      echo "</table><br><br></div>";
    }

//actualizar solicitudes 27
    function actualizarSolicitud($id){
        $conexion =  new ConexionBDD();
        $permisoFinal =  $_REQUEST['status'] ;
        if ($permisoFinal == 2) return;
        else if ($permisoFinal == 3) {
          $conexion->eliminarDato('usuario' , 'idUsuario' , $id );
        }
        else if ($permisoFinal == 0 || $permisoFinal == 1){
          $datos = array(   array( 'estatus' , '1' ) ,  array( 'permisoU' , $permisoFinal )  );
          $conexion->actualizarDato('usuario', $datos , " `idUsuario`  LIKE  '$id' ");
        }
    }

//menu que accede a opciones
if (isset($_REQUEST['ope']))
{
    $ope = $_REQUEST['ope'] ;

    if ($ope == 1 || $ope == 'dl') directoriosLeft($_REQUEST['autor']);

    if ($ope == 2 || $ope == 'di') directoriosRight($_REQUEST['autor'] , $_REQUEST['padre'] );

    if ($ope == 3 || $ope == 'ac') agregarCarpeta();

    if ($ope == 11 || $ope == 'cd') comprobarDato($_REQUEST['tabla'] , $_REQUEST['consulta'] );

    if ($ope == 12 || $ope == 'aa') agregarArchivos();

    if ($ope == 13 || $ope == 'oa') obtenerArchivo($_REQUEST['id'] );

    if ($ope == 4 || $ope == 'dli') directoriosLeftInvitado($_REQUEST['url']);

    if ($ope == 5 || $ope == 'dri') directoriosRightInvitado($_REQUEST['autor'] , $_REQUEST['padre'] );

    if ($ope == 6 || $ope == 'ec') eliminarCarpeta($_REQUEST['autor'] , $_REQUEST['padre'] );

    if ($ope == 7 || $ope == 'ea') eliminarArchivo($_REQUEST['id']);

    if ($ope == 8 || $ope == 'mc') modificarCarpeta($_REQUEST['id']);

    if ($ope == 9 || $ope == 'mc') modificarNombreArchivo($_REQUEST['id']);
    
    if ($ope == 14 || $ope == 'az') agregarArchivosZIP();

    if ($ope == 15 || $ope == 'dz') descargarArchivosZIP($_REQUEST['padre'] , $_REQUEST['nombre'] );

    if ($ope == 16 || $ope == 'cu') columnaUnica($_REQUEST['consulta'] );

    if ($ope == 17 || $ope == 'mp') modificarPerfil($_REQUEST['id'] );

    if ($ope == 25 || $ope == 'sm') enviarCorreo($_REQUEST['nombreElemento'], $_REQUEST['elemento'], $_REQUEST['nomViejo'], $_REQUEST['idUsuario'], $_REQUEST['idCarpetaPadre'], $_REQUEST['fecha'], $_REQUEST['hora'], $_REQUEST['opc'], $_REQUEST['idAutor']);

    if ($ope == 26 || $ope == 'os') obtenerSolicitudes();

    if ($ope == 27 || $ope == 'as') actualizarSolicitud($_REQUEST['cUsuario']);
}

?>
