<?php
session_start();
header('Content-Type: application/json; charset=utf-8');
//$user_id = 1; //overwrite later
$user_id = $_SESSION['user']["ID"];
//$cadena_datos ="date=2021-02-08&tsh=34&dosis=34&weight=&note=&link="; //delete

try {

  // Obtencion de lista actualizada de usuarios registrados
  if ( !empty($_POST) ) {
        $cadena_datos =filter_input(INPUT_POST, "datos");  
        //var_dump($cadena_datos);
        $datos = JQUnserialize($cadena_datos); 

        if(array_key_exists("entry_id",$datos) == true ){ 
            $entry_id = $datos["entry_id"];
            remove($entry_id);
           
        }else{
            insert($datos);
        }     
   }
  $entries_sql = get_entries($user_id);
  echo json_encode($entries_sql);


} catch( Exception $ex) {
  // Envio de código de error de alta
  echo json_encode((object)['error' => $ex->getMessage()]);   
}


function JQUnserialize( $cadena_datos ) {
  $datos = array();
  foreach (explode('&', $cadena_datos ) as $dato) {
      $param = explode("=", $dato);
      //var_dump($param);
      if ($param) {
        $clave = urldecode($param[0]); 
        $valor = urldecode($param[1]);
        $datos[$clave] = $valor;     
      }   
  }
  //var_dump($datos);
  return $datos;
}






function insert($datos){
    global $user_id;
    // Obtencion de valores del formulario
    $date = $datos["date"];
    $tsh =  $datos["tsh"];  
    $dosis = $datos["dosis"];
    $weight = $datos["weight"];
    $note = $datos["note"]; 
    $link = $datos["link"]; 

    if ($weight == ""){$weight = null;}
    if ($note == ""){$note = null;}
    if ($link == ""){$link = null;}

    //connecting to DB
    $host = '127.0.0.1';
    $db = 'hypo';
    $port = 3306;
    $charset = 'utf8';
    $dsn = "mysql:host=$host;dbname=$db;charset=$charset;port=$port"; 
    $user = 'root'; // Usuario
    $pass = ''; // Contraseña
    // Opciones de configuracion
    $opt = [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION, PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC, PDO::ATTR_EMULATE_PREPARES => false ];
 
    try {
        $pdo = new PDO($dsn,  $user, $pass, $opt);
        // ---------------------------- Inicio de transacción
        $pdo->beginTransaction();

            // Registro de la cuenta
        $sentencia = $pdo->prepare("INSERT INTO diary(
              DATE, TSH, DOSIS, WEIGHT,  LINK, NOTE, USER_ID) VALUES ( :date, :tsh, :dosis, :weight, :link, :note, :user_id)"); 
        $sentencia->execute([
            ':date' => $date,
            ':tsh' => $tsh,
            ':dosis' => $dosis,
            ':weight' => $weight,
            ':link' => $link,
            ':note' => $note, 
            ':user_id' => $user_id
          ]);     

        // --------------------------- Confirmación de transacción
        $pdo->commit();
    } catch( Exception $ex) {
        echo "Error al realizar la operación (insert entry): ".$ex->getMessage(); 
        
        // --------------------------- Cancelación de transacción 
        $pdo->rollBack(); 
        die();
    }   
    $_POST = array(); 

}

function get_entries($user_id){

        //connecting to DB
    $host = '127.0.0.1';
    $db = 'hypo';
    $port = 3306;
    $charset = 'utf8';
    $dsn = "mysql:host=$host;dbname=$db;charset=$charset;port=$port"; 
    $user = 'root'; // Usuario
    $pass = ''; // Contraseña
    // Opciones de configuracion
    $opt = [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION, PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC, PDO::ATTR_EMULATE_PREPARES => false ];
  
    try {
      $pdo = new PDO($dsn,  $user, $pass, $opt);
      // ---------------------------- Inicio de transacción
      $pdo->beginTransaction();

          // Registro de la cuenta
      $sentencia = $pdo->prepare("SELECT DATE, TSH, DOSIS, WEIGHT, NOTE, LINK, ID FROM diary WHERE
            USER_ID = $user_id ORDER BY DATE DESC"); 
      $sentencia->execute();     

      // --------------------------- Confirmación de transacción
      $reg = $sentencia->fetchAll();
      return($reg);
      
  } catch( Exception $ex) {
      echo "Error al realizar la operación (get entries): ".$ex->getMessage(); 
  
      die();
  }   
}

function remove($entry_id){
   
    //connecting to DB
    $host = '127.0.0.1';
    $db = 'hypo';
    $port = 3306;
    $charset = 'utf8';
    $dsn = "mysql:host=$host;dbname=$db;charset=$charset;port=$port"; 
    $user = 'root'; // Usuario
    $pass = ''; // Contraseña
    // Opciones de configuracion
    $opt = [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION, PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC, PDO::ATTR_EMULATE_PREPARES => false ];
    
    try {
        $pdo = new PDO($dsn,  $user, $pass, $opt);
        // ---------------------------- Inicio de transacción
        $pdo->beginTransaction();

            // Registro de la cuenta
        //echo "ehjfg";
        $sentencia = $pdo->prepare("DELETE FROM diary WHERE ID = :id"); 
        $sentencia->execute([':id' => $entry_id]);     
        // --------------------------- Confirmación de transacción
        $pdo->commit();
        } catch( Exception $ex) {
            echo "Error al realizar la operación (remove): ".$ex->getMessage(); 
            
            // --------------------------- Cancelación de transacción 
            $pdo->rollBack(); 
            die();
        }   
    $_POST = array();
}



?>