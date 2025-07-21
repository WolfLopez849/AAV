<?php
ini_set("display_errors", 1);

ini_set("display_startup_errors", 1);

error_reporting(E_ALL);
    require_once("../Config/db.php");
    require_once("../Config/conectar.php");
    require_once("../Login/functionsRegister.php");
   
    class RegistroUser extends Conectar{
        private $id;
        private $usuario;
        private $nombre /* falta*/ */
        private $correo;
        private $password;

        public function __construc($id=0,$usuario="",$nombre="",$correo="",$password="", $dbCnx=""){
            $this->id=$id;
            $this->usuario=$usuario;
            $this->nombre=$nombre;
            $this->correo=$correo;
            $this->password=$password;
            parent::__construct($dbCnx);
        }
        /* set's */
        public function setId($id){
            $this->id=$id;
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
     
        /* get's */
        public function getId(){
            return $this->id;
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
        public function checkUser($documento){
            try {
                $stm=$this->dbCnx->prepare("SELECT * FROM AAV WHERE documento = '$documento'");
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
                $stm= $this->dbCnx->prepare("INSERT INTO AAV (correo,usuario,password)
                VALUES(?,?,?)");
                $stm -> execute([$this->correo, $this->usuario, md5($this->password)]);
                $login = new LoginUser();
                $login ->setCorreo($_POST['correo']);
                $login ->setPassword($_POST['password']);
                $success= $login->login();
            } catch (Exception $e) {
                return $e->getMessage();
            }
        }
}

?>