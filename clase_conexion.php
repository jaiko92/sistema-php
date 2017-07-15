<?php

  class ConexionBDD{
    var  $usuario , $password , $pdo , $sql, $bdd,
    $reader , $consulta , $preparador , $ejecutor;
  
    function __construct(){  
      $this->usuario  = 'root';  $this->password = '';
      $this->bdd = "projectbt";
    }
  
    function __construct2($usuario , $password , $bdd){
      $this->usuario = $usuario; $this->password = $password;
      $this->bdd = $bdd;
      conexion();
    }
    
    public function conexion(){
      try{
        $this->pdo = new PDO ("mysql:host=localhost;dbname=$this->bdd", $this->usuario, $this->password);
        $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        return true;
      }catch(PDOException $e){
        return "ERROR: " . $e->getMessage();
      }
    }
  
    private function realizaConsulta($consulta){
      try{
        $this->conexion();
        $this->ejecutador = $this->pdo->prepare($consulta); $this->ejecutador->execute();
        $this->cerrarConexion();
        return true;
      }catch (DOException $e){
        return "ERROR: " . $e->getMessage();
      }
    }
    
    public function ejecutarConsulta($consulta){
      try{
        $this->conexion();
        $c = $this->pdo->exec($consulta);
        $this->cerrarConexion();
        return $c;
      }catch (DOException $e){
        return "ERROR: " . $e->getMessage();
      }
    }
  
  public function existeDato($consulta){
    try{
    $this->conexion();
    $nc = $this->pdo->query($consulta)->rowCount();
    $this->cerrarConexion();
      if ($nc != 0){
        return true;
      }else{
        return false;
      }
    }catch (DOException $e){
      return "ERROR: " . $e->getMessage();
    }
  }
  
  public function obtenerColumna($sql){
    $this->realizaConsulta($sql);
    return ($this->ejecutador->fetch(PDO::FETCH_BOTH));
  }
  
  public function obtenerDatos($sql){
    $this->realizaConsulta($sql);
    return ($this->ejecutador->fetchAll());
  }
  
  public function insertarDato($tabla , $datos){
    if(is_array($datos)){
      $insert = "(" ; $values = ' VALUES(';
      foreach ($datos as list($nombreC , $datoC)){
        $insert = $insert . ' `'. $nombreC . '` ,' ;
        $values = $values . ' \''. $datoC . '\' ,' ;
      }
      $insert = substr($insert ,  0, -1) . ')';
      $values = substr($values ,  0, -1) . ')';
      $sql = "INSERT INTO $tabla $insert $values ;";
      //echo $sql;
      $this->ejecutarConsulta($sql);
      return true;
    }else{
      return false;
    }
  }
  
  public function eliminarDato($tabla , $campo , $valor ){
    $sql = "DELETE FROM `$tabla` WHERE `$campo` LIKE '$valor' ";  //    echo $sql;
    return  $this->ejecutarConsulta($sql);
  }
  
  public function actualizarDato($tabla , $datos , $condicion){
    if(is_array($datos)){
      $set= '';
      foreach ($datos as list($nombreC , $datoC))
      $set =
      $set . ' `'.
      $nombreC . '` = \''.
      $datoC . '\' ,' ;
      
      $set = substr($set ,  0, -1) ;
      $sql = "UPDATE $tabla SET $set WHERE $condicion ;";
      //   echo $sql;
      
      $this->ejecutarConsulta($sql);
      return true;
    }else{
      return false;
    }
  }
    
  public function resultadoUnico($tabla , $campoR , $condicion){
    $condicion = "SELECT  `$campoR` FROM `$tabla` WHERE $condicion ";
    $dato = $this->obtenerDatos( $condicion );
    if(isset($dato[0][0])){
      return $dato[0][0];
    }else{
      return null;
    }
  }
  
  public function cerrarConexion() { $pdo = null ; }
  
  }

?>
