<?php
ini_set("display_errors", 1);

ini_set("display_startup_errors", 1);

error_reporting(E_ALL);

    require_once('../Config/conectar.php');
    require_once('../Config/db.php');
    Class LoginUser extends Conectar{
        private $documento;
        private $usuario;
        private $password;
        private $correo;
        private $rol;
        
    public function __construct($documento=0,$usuario="",$password='',$correo='',$rol="",$dbCnx=''){
        $this->documento=$documento;
        $this->usuario=$usuario;
        $this->password=$password;
        $this->correo=$correo;
        $this->rol=$rol;
        $this->dbCnx = new PDO(DB_TYPE . ":host=" . DB_HOST . ";dbname=" . DB_NAME, DB_USER, DB_PWD, [PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC]);
    }
    /* SET's */
    public function setDocumento($documento){
        $this->documento=$documento;
    }
    public function setUsuario($usuario){
        $this->usuario=$usuario;
    }
    public function setPassword($password){
        $this->password=$password;
    }
    public function setCorreo($correo){
        $this->correo=$correo;
    }
    public function setRol($rol){
        $this->rol=$rol;
    }
    
    /* GET's */
    public function getDocumento(){
        return $this->documento;
    }
    public function getUsuario(){
        return $this->usuario;
    }
    public function getPassword(){
        return $this->password;
    }
    public function getCorreo(){
        return $this->correo;
    }
    public function getRol(){
        return $this->rol;
    }
    
    public function fetchAll(){
        try {
            $stm = $this->dbCnx->prepare("SELECT * FROM AAV");
            $stm -> execute();
            return $stm->fetchAll();
        } catch (Exception $e) {
            return $e->getMessage();
        }
    }

    public function login(){
        try {
            $stm = $this->dbCnx->prepare("SELECT * FROM AAV WHERE usuario = ? AND password =?");
            $stm->execute([$this->usuario, md5($this->password)]);
            $user=$stm->fetchAll();
            if(count($user)>0){
                session_start();
                $_SESSION['documento']=$user[0]['documento'];
                $_SESSION['usuario']=$user[0]['usuario'];
                $_SESSION['password']=$user[0]['password'];
                $_SESSION['correo']=$user[0]['correo'];
                $_SESSION['rol']=$user[0]['rol'];
                return true;
            }
            else{
                return false;
            }
        } catch (Exception $e) {
            return $e -> getMessage();
        }
    }
}
?>