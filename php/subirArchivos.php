<?php
 $nameARCH = '';   $nId = getdate() ;
 
 if ( !isset($_FILES["arch"]) ) return ;

 $f =  $_FILES["arch"];


 $c = count($f['name']);
 //var_dump($f);

 for ($i=0; $i < $c ; $i++) {
   if ($f["name"][$i] != '' ) {
     $nCarpeta = rand( 1, 50) ;
     $ruta = "../multimedia/";
     $extension =  pathinfo( $f["name"][$i]);
     $nameARCH =  MD5(( rand() - rand()  + rand() * rand() ) . $nId["yday"].  $nId[0] )  . '.' ;
     @copy ( $f["tmp_name"][$i], $ruta.$nameARCH.$extension['extension']) ;
   }
 }

 ?>
