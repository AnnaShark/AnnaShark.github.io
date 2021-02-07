<?php



//_______

function login($mail, $password){

  // Acceso a la base de datos
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

  $pdo = new PDO($dsn,  $user, $pass, $opt);    

  $sentencia =  $pdo->prepare("SELECT * FROM users WHERE MAIL = :mail AND PASSWORD = :pass ");
  $sentencia->execute([':mail' => $mail,':pass' => $password ]);
  
  $registro = $sentencia->fetch(PDO::FETCH_ASSOC);
  var_dump($sentencia);
  // Obtencion de datos del usuario con las credenciales indicadas
  if ( $registro == true) {
      // Existe el usuario. Sus datos se almacenan en el estado de sesion 
      $_SESSION['user'] = $registro;
      //var_dump($registro);
      header('Location: '.'diary.php');
      } else {
      // No existe el usuario. Usuario desconocido 
      header('Location: '.'login.html');//uncomment
      }
}  



function register($mail, $password){
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
        $sentencia = $pdo->prepare("INSERT INTO users(
            MAIL, PASSWORD) VALUES ( :mail, :pass)"); 
        $sentencia->execute([
            ':mail' => $mail,
            ':pass' => $password
        ]);     

        // --------------------------- Confirmación de transacción
        $pdo->commit();
    } catch( Exception $ex) {
        echo "Error al realizar la operación (register user): ".$ex->getMessage(); 
        
        // --------------------------- Cancelación de transacción 
        $pdo->rollBack(); 
        die();
    }   
    $_POST = array(); 

}



session_start();
// Comprobacion de valores enviados 
if ( count($_POST) > 0 ) {  
        // Obtencion de valores
$mail = filter_input(INPUT_POST, "mail");
$password = filter_input(INPUT_POST, "pass");
    if ($_POST["action"]== "Login"){
        login($mail,$password);
  
    } elseif ($_POST["action"]== "Register"){
        register($mail,$password);
        login($mail,$password); 
    }
} else {
            
    header('Location: '.'login.html'); //uncomment
}


?>