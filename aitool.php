<?php
header('Content-Type: application/json; charset=utf-8');


//$cadena_datos ="tsh=3&t3=4&t4=4&t4u=4&fti=4"; //delete

try {

  // Obtencion de lista actualizada de usuarios registrados
  if ( !empty($_POST) ) {
       $cadena_datos =filter_input(INPUT_POST, "datos");  
        //var_dump($cadena_datos);
        $datos = JQUnserialize($cadena_datos); 
        $result = diagnose($datos);
        echo json_encode($result);
   }



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






function diagnose($datos){
    // Obtencion de valores del formulario
    $tsh = $datos["tsh"];
    $t3 =  $datos["t3"];  
    $t4 = $datos["t4"];
    $t4u = $datos["t4u"];
    $fti = $datos["fti"]; 

    shell_exec("/usr/local/bin/python3 pip install numpy 2>&1");
    $message =shell_exec("/usr/local/bin/python3 /Applications/XAMPP/xamppfiles/htdocs/final_proj/AnnaShark.github.io/python_script/python_script.py $tsh $t3 $t4 $t4u $fti 2>&1");
    $result = trim($message);
    return($result);


    $_POST = array(); 

}




?>