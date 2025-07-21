<?php
ini_set("display_errors", 1);

ini_set("display_startup_errors", 1);

error_reporting(E_ALL);
    require_once("../Config/db.php");
    require_once("../Config/conectar.php");
    require_once("functionsRegister.php");
   
    class RegistroUser extends Conectar{
        private $documento;
        private $usuario;
        private $nombre; 
        private $correo;
        private $password;
        private $rol;

        public function __construct($documento=0,$usuario="",$nombre="",$correo="",$password="",$rol="", $dbCnx=""){
            $this->documento=$documento;
            $this->usuario=$usuario;
            $this->nombre=$nombre;
            $this->correo=$correo;
            $this->password=$password;
            $this->rol=$rol;
            parent::__construct($dbCnx);
        }
        /* set's */
        public function setDocumento($documento){
            $this->documento=$documento;
        }
        public function setUsuario($usuario){
            $this->usuario=$usuario;
        }
        public function setNombre($nombre){
            $this->nombre=$nombre;
        }
        public function setCorreo($correo){
            $this->correo=$correo;
        }
        public function setPassword($password){
            $this->password=$password;
        }
        public function setRol($rol){
            $this->rol=$rol;
        }
     
        /* get's */
        public function getDocumento(){
            return $this->documento;
        }
        public function getUsuario(){
            return $this->usuario;
        }
        public function getNombre(){
            return $this->nombre;
        }
        public function getCorreo(){
            return $this->correo;
        }
        public function getPassword(){
            return $this->password;
        }
        public function getRol(){
            return $this->rol;
        }

        public function checkUser($documento){
            try {
                $stm=$this->dbCnx->prepare("SELECT * FROM usuarios WHERE documento = '$documento'");
                $stm->execute();
                if ($stm->fetchColumn()) {
                    return true;
                }
                else{
                    return false;
                }
            } catch (Exception $e) {
                return $e->getMessage();
            }}
        /* finish */
        public function insertData(){
            try {
                $stm= $this->dbCnx->prepare("INSERT INTO usuarios (documento,nombre,usuario,correo,password,rol)
                VALUES(?,?,?,?,?,?)");
                $stm -> execute([ $this->documento, $this->nombre, $this->usuario, $this->correo, md5($this->password), $this->rol
        ]);
                /*$login = new LoginUser();
                $login ->setCorreo($_POST['correo']);
                $login ->setPassword($_POST['password']);
                $success= $login->login();*/
            } catch (Exception $e) {
                return $e->getMessage();
            }
        }
}

?>