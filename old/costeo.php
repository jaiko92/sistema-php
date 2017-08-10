<?php

require ('../xajax/xajax.inc.php');
function get_days_for_month($m,$y){
if($m == 02){
if(($y % 4 == 0) && (($y % 100 != 0) || ($y % 400 == 0))){
return 29;
}else{
return 28;
}
} else if ($m == 4 || $m == 6 || $m == 9 || $m == 11){
        return 30;
}else{
    return 31;
}
}
function helloWorld($isCaps){
if ($isCaps)
$text = "HELLO WORLD!";
else
$text = "Hello World!";
$objResponse = new xajaxResponse();
$objResponse->addAssign("div1","innerHTML",$text);
return $objResponse;
}
function setColor($sColor)
{
$objResponse = new xajaxResponse();
$objResponse->addAssign("div1","style.color", $sColor);
return $objResponse;
}
function tabla_optionb($consulta){ 
	conectar_bdd("a");
	$respuesta = new xajaxResponse();
	$tabla="xmlrecibido"; $datosfas.="CORRECTO";
	$result=mysql_query($consulta);
	//$nuevo_tabla.=
	$nuevo_tabla.="<table border='1' align='center' style='BORDER-RIGHT: #11437E 1px solid; BORDER-TOP: #11437E 1px solid; BORDER-LEFT: #11437E 1px solid; BORDER-BOTTOM: #11437E 1px solid; COLOR: #222; FONT-SIZE: 12px; BACKGROUND-COLOR:#EBF4FB; width: 110px; height:20px; text-align: center;'>"; $aussa=0;
        while($row=mysql_fetch_assoc($result)){  
		$utilidad=$utilidad+$row['utilidad'];
		$sumaven=$sumaven+$row['suma'];
		if ($row['moneda']=="M.N.") {
			$costasp=$costasp + $row['cost']; $costasp=$costasp + $row['costeo'];
		} else {
			$costasd=$costasd + $row['cost']; $costasd=$costasp + $row['costeo'];
		}
		$aussb=0; $aussa++; $na_tabla.="<tr>"; $foliocost=$row['folio'];
		foreach($row as $key => $value) { $datosfas.=$key." - ".$value." - <br>";
			if ($aussa=="1") {  $aussb++;
				if ($aussb=="1") { 
			//		$n_tabla.="<tr><td>".$value."</td>"; 
				} else { 
					if ($aussa!="0") { } 
				}
				$n_tabla.="<td onClick='xajax_consulta(xajax.getFormValues(\"abcejemplo\"),\"".$key."\")'>".$key."</td>";
				$na_tabla.="<td>".$value."</td>";
			} else { if ($aussa=="2") {  }
				$na_tabla.="<td>".$value."</td>";
		} 
	} 
//$nuevo_tabla.="<td>".$costas." - ".$aussb."</td>";
//if ($aussb<="23") { //$nuevo_tabla.=$n_tabla; }
$na_tabla.="<td onClick='xajax_cost(\"".$foliocost."\")' colspan='2' name='cost".$foliocost."' id='cost".$foliocost."'>costeo</td>";
$na_tabla.="</tr>"; 
} 
$nuevo_tabla.=$n_tabla;	
$nuevo_tabla.=$na_tabla;	
$nuevo_tabla.="<tr>";
$nuevo_tabla.="<td colspan='4'>".$costasp." - ".$costasd;
if ($sumaven) { $nuevo_tabla.=" utilidad = ".$utilidad." venta = ".$sumaven; }
$nuevo_tabla.="</td>";
$nuevo_tabla.="</tr>";
$nuevo_tabla.="</table>";	

	return $nuevo_tabla;
 }
function tabla_optiona($columna,$tabla,$referencia,$fileje){ 
		$respuesta = new xajaxResponse();
		$estado="incorrecto";
		$desf= mysql_query("describe ".$tabla);
		while($rowf=mysql_fetch_assoc($desf)){ if ($rowf['Type']=="date" && $rowf['Field']==$columna) { $estado="correcto"; } }
	$result=mysql_query("select ".$columna." from ".$tabla." ".$fileje." group by ".$columna." order by ".$columna);
	$nuevo_tabla.="<select name='".$columna."' id='".$columna."' onChange='xajax_consulta(xajax.getFormValues(\"abcejemplo\"))' style='BORDER-RIGHT: #11437E 1px solid; BORDER-TOP: #11437E 1px solid; BORDER-LEFT: #11437E 1px solid; BORDER-BOTTOM: #11437E 1px solid; COLOR: #222; FONT-SIZE: 12px; BACKGROUND-COLOR:#EBF4FB; width: 110px; height:20px; text-align: center;'>
<option value=''>".$columna."</option>";
        while($row=mysql_fetch_assoc($result)){ 
$healthy = array("ñ", "á", "é", "í", "ó", "ú", "Ñ", "Á", "É", "Í", "Ó", "Ú");
$yummy   = array("&ntilde;", "&aacute;", "&eacute;", "&iacute;", "&oacute;", "&uacute;", "&Ntilde;", "&Aacute;", "&Eacute;", "&Iacute;", "&Oacute;", "&Uacute;");
$newphrase = str_replace($healthy, $yummy, $row[$columna]);
	if ($estado=="incorrecto" && $row[$columna]!="") {
	if ($referencia==$row[$columna]) {
	$nuevo_tabla.="<option value='".utf8_decode($newphrase)."' selected> ".strtoupper(utf8_decode($newphrase))." </option>";
	} else {
	$nuevo_tabla.="
<option value='".$newphrase."'> ".strtoupper($newphrase)." </option>";
	} }
	if ($estado=="correcto") { 
$nuevo4[]=$row[$columna];
$fecha6=explode("-",$row[$columna]);  
$fecha7=$fecha6[0]."-".$fecha6[1];
$nuevo2 = $fecha7; if ($nuevo3==$fecha7) { $nuevo3 = $nuevo2; } else { $nuevo3 = $nuevo2;
$nuevo4[]=$nuevo3;
$nuevo4[]=$fecha6[0]; }
	} $newphrase=""; 
	}
	if ($estado=="correcto") { 
foreach($nuevo4 AS $VALUEn) {
	$nuevo_tabla.="
<option value='".$VALUEn."'> ".strtoupper($VALUEn)." </option>";
	}}
	$nuevo_tabla.="</select>";	
	return $nuevo_tabla;

 }
function consulta($datos,$orden)
{
	$respuesta = new xajaxResponse();
	conectar_bdd("a");
	$act='0';
	$mova="1";
	$utneta='';
	foreach ($datos as $name => $valor ){
		if ($valor!="") {
			if ($mova=="1") {
				$pruebaeje.="select fechaf, rfc, folio, agente, pagada, subtotal, cost, rsocial, moneda, tc from factura where ".$name." like '".$valor."%'"; $mova++;
				$costpr.="SELECT agente, SUM(SUBTOTAL*TC) as suma, SUM((SUBTOTAL*TC)-(COST*TC)) as utilidad from factura where ".$name." like '".$valor."%'";
				$consulta.=" where ".$name." like '".$valor."%'";
				$consultaand.=" and ".$name." like '".$valor."%'";
			} else {
				$pruebaeje.=" and ".$name." like '".$valor."%'"; $mova++;
				$costpr.=" and ".$name." like '".$valor."%'";
				$consulta.=" and ".$name." like '".$valor."%'";
				$consultaand.=" and ".$name." like '".$valor."%'";
			}
		}
	}
	if ($orden) { $ordensssa=" order by ".$orden." asc"; }
	$cadena=$pruebaeje.$ordensssa.";";
	$cadenacost=$costpr."group by agente;";
	$nuevo_tabla=tabla_optionb($cadena);
	$nuevo_tablacost=tabla_optionb($cadenacost);


	$UTILIDAD="SELECT SUM((SUBTOTAL*TC)-(COST*TC)) as utilidad from factura".$consulta.";";
	$resultuti=mysql_query($UTILIDAD);
        while($row=mysql_fetch_assoc($resultuti)){  $utilidades.="Utilidades = ".$row['utilidad']."<br>";
	$utneta=$row['utilidad'];
	}		
	$UTILIDAD="SELECT SUM(total) as utilidad, moneda from xmlrecibido where t_doc='0'".$consultaand." and moneda='m.n.';";
	$resultuti=mysql_query($UTILIDAD);
        while($row=mysql_fetch_assoc($resultuti)){  $utilidades.="compra = ".$row['utilidad']."<br>";
	$utneta=$utneta-$row['utilidad'];
	}		
	$UTILIDAD="SELECT SUM(total*t_cambio) as utilidad from facturasdll".$consulta.";";
	$resultuti=mysql_query($UTILIDAD);
        while($row=mysql_fetch_assoc($resultuti)){  $utilidades.="comprad = ".$row['utilidad']."<br>";
	$utneta=$utneta-$row['utilidad'];
	}		
	$UTILIDAD="SELECT SUM(total) as utilidad, moneda from nominas".$consulta.";";
	$resultuti=mysql_query($UTILIDAD);
        while($row=mysql_fetch_assoc($resultuti)){  $utilidades.="nomina = ".$row['utilidad']."<br>";
	$utneta=$utneta-$row['utilidad'];
	}		
	$utilidades.="resultado = ".$utneta."<br>";

	$respuesta->addAssign("div1","innerHTML",$utilidades);
	$respuesta->addAssign("div3","innerHTML",$nuevo_tablacost);
	$respuesta->addAssign("div2","innerHTML",$nuevo_tabla);
	$modid="";
	unset($modid);
	if ($act=='1') {
	$modid="setTimeout('xajax_consulta(xajax.getFormValues(\"abcejemplo\"),\"".$orden."\")',10000);";
	$respuesta->script($modid);
	$act='0';
	}
	$modid="";
	unset($modid);
	return $respuesta;
}
function conectar_bdd($ac)
{
	if($ac=="a")
	{
		$cnx=mysql_connect('localhost:3306','root','');
		//$conexion=mysql_connect('localhost','root','');
		mysql_select_db("reportes", $cnx);
		//echo "si entre";
	}
	else if ($ac=="c")
	{
	
		$conexion=mysql_connect('mpc2:3309','root5','matin');
		//$conexion=mysql_connect('localhost','root','');
		mysql_select_db("reporetes", $conexion);
		mysql_close($conexion);
	}
}
function cost($folio){ 
	$respuesta = new xajaxResponse();
	conectar_bdd("a");
	$nombre="cost".$folio;
	$nuevo_tabla="<input name='costa".$folio."' type='text' id='costa".$folio."' value='' size='42' class='lg_textfeldboton' onBlur='xajax_guacos(this.value,".$folio.",xajax.getFormValues(\"abcejemplo\"))'>";
	$respuesta->addAssign($nombre,"innerHTML",$nuevo_tabla);
	return $respuesta;

 }
function guacos($cost,$folio,$consulta){ 
	$respuesta = new xajaxResponse();
	conectar_bdd("a");
	$_GRABAR_SQL='update factura set cost="'.$cost.'" where folio="'.$folio.'";';
	mysql_query($_GRABAR_SQL);
	$consultagua="select cost from factura where folio like '".$folio."'";
	$resultgua=mysql_query($consultagua);
	while($row=mysql_fetch_assoc($resultgua)){  
	$foliocost=$row['cost'];
	}
//	$respuesta->addAlert($_GRABAR_SQL.$foliocost);
//	if ($foliocost){ $respuesta->addAlert($_GRABAR_SQL); }
	$mova="1";
	foreach ($consulta as $name => $valor ){
		if ($valor!="") {
			if ($mova=="1") {
				$pruebaeje.="select fechaf, rfc, folio, agente, pagada, subtotal, cost, rsocial from factura where ".$name." like '".$valor."%'"; $mova++;
				$costpr.="select fechaf, agente, sum(subtotal) as total, sum(cost) as costeo, moneda, sum(subtotal*TC)-sum(cost*TC) as rescost from factura where ".$name." like '".$valor."%'";
			} else {
				$pruebaeje.=" and ".$name." like '".$valor."%'"; $mova++;
				$costpr.=" and ".$name." like '".$valor."%'";
			}
		}
	}
	$cadena=$pruebaeje.";";
	$cadenacost=$costpr."group by fechaf, agente, moneda;";
	$nuevo_tabla=tabla_optionb($cadena);
	$nuevo_tablacost=tabla_optionb($cadenacost);
//	$result=mysql_query($consulta);
//        while($row=mysql_fetch_assoc($result)){  
//	}		

//	$respuesta->addAssign("div1","innerHTML",$nuevo_tablacost);
	$respuesta->addAssign("div3","innerHTML",$nuevo_tablacost);
	$respuesta->addAssign("div2","innerHTML",$nuevo_tabla);
	return $respuesta;

 }
function filtro($consulta){ 
	conectar_bdd("a");
	$respuesta = new xajaxResponse();
	$result=mysql_query($consulta);
	$mova="1";
        while($row=mysql_fetch_assoc($result)){  
		foreach($row as $key => $value) { 
			$nuevo_tabla[$mova][$key]=$value;
		} $mova++; 
	} 


	return $nuevo_tabla;
 }
$xajax = new xajax();


$xajax->registerFunction("helloWorld");
$xajax->registerFunction("guacos");
$xajax->registerFunction("cost");
$xajax->registerFunction("consulta");
$xajax->registerFunction("setColor");

$xajax->processRequests(); ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
<head>
<?php
 conectar_bdd("a"); ?>
<title>xajax example</title>
<?php $xajax->printJavascript('../xajax/'); ?>
</head>
<body style="text-align:center;">
<table border='1' align='center'>
<tr>
<form name="abcejemplo" id="abcejemplo" action="" method="post">
<td><?php echo tabla_optiona(folio,factura,$_GET["mov"],$EJEMPLO0); ?></td>
<td><?php echo tabla_optiona(rfc,factura,$_GET["rfc"],$EJEMPLO0); ?></td>
<td><?php echo tabla_optiona(fechaf,factura,$_GET["fechaf"],$EJEMPLO0); ?></td>
<td><?php echo tabla_optiona(rsocial,factura,$_GET["fechaf"],$EJEMPLO0); ?></td>
</tr>
<tr>
<td><?php echo tabla_optiona(agente,factura,$_GET["mov"],$EJEMPLO0); ?></td>
<td><?php echo tabla_optiona(foliof,factura,$_GET["mov"],$EJEMPLO0); ?></td>
</form>
<td>
<input name="poliza" type="checkbox" id="poliza" onClick="xajax_activap(this.checked)">poliza
<a href=polizas.php><br>ver poliza</a>
</td>
</tr>
</table>
<div id="div1" name="div1">&#160;</div>
<div id="div3" name="div3">&#160;</div>
<div id="div2" name="div2">&#160;</div>
<?php 
	$consulta="select * from factura;";
	$result=mysql_query($consulta);
        while($row=mysql_fetch_assoc($result)){ 
	list($anio, $mes, $dia)=explode("-",$row['fechaf']); 
	if ($agente!=$row['agente']) { $agente=$row['agente']; }
	if ($anioa!=$anio) { $anioa=$anio; }
	if ($row['moneda']=="DLL") { 
		$reporte['y'][$anioa]['totald']+=$row['total']; 
		$reporte['a'][$agente][$anioa]['totald']+=$row['total']; 
		$reporte['y'][$anioa]['totaldc']+=$row['cost']; 
		$reporte['a'][$agente][$anioa]['totaldc']+=$row['cost']; 
	} else { 
		$reporte['y'][$anioa]['totalp']+=$row['total']; 
		$reporte['a'][$agente][$anioa]['totalp']+=$row['total']; 
		$reporte['y'][$anioa]['totalpc']+=$row['cost']; 
		$reporte['a'][$agente][$anioa]['totalpc']+=$row['cost']; 
	} 
	if ($mesa!=$mes) { $mesa=$mes; } 
	if ($row['moneda']=="DLL") { 
		$reporte['m'][$anioa.$mes]['totald']+=$row['total']; 
		$reporte['a'][$agente][$anioa.$mes]['totald']+=$row['total']; 
	} else { 
		$reporte['m'][$anioa.$mes]['totalp']+=$row['total']; 
		$reporte['a'][$agente][$anioa.$mes]['totalp']+=$row['total']; 
	} 
	if ($diaa!=$dia) { $diaa=$dia; } 
	if ($row['moneda']=="DLL") { 
		$reporte['d'][$anioa.$mes.$dia]['totald']+=$row['total']; 
		$reporte['a'][$agente][$anioa.$mes.$dia]['totald']+=$row['total']; 
	} else { 
		$reporte['d'][$anioa.$mes.$dia]['totalp']+=$row['total']; 
		$reporte['a'][$agente][$anioa.$mes.$dia]['totalp']+=$row['total']; 
	} 
	} 
//$informacion=tabla_optionc;
//print_r($reporte);
$m=5; $y=2016;
if($m == 02){
if(($y % 4 == 0) && (($y % 100 != 0) || ($y % 400 == 0))){
echo 29;
}else{
echo 28;
} 
} else if ($m == 4 || $m == 6 || $m == 9 || $m == 11){
    
        echo 30;
}else{
    echo 31;
}

 ?>
<table border="1">
<?php 

foreach ($reporte['y'] as $name => $valor ){
 ?>
<tr>
<td colspan="2">
<?php 
echo $name;
 ?>
</td>
</tr>
<?php 
foreach ($valor as $nameaf => $valoraf ){
 ?>
<tr>
<td>
<?php 
echo $nameaf;
 ?>
</td>
<td>
<?php 
echo $valoraf;
 ?>
</td>
</tr>
<?php 
} }



 ?>
</table>
</body>
</html>