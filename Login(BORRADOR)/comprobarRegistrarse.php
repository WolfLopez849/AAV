<?php
ini_set("display_errors", 1);

ini_set("display_startup_errors", 1);

error_reporting(E_ALL);
    if (isset($_POST['registrarse'])){
        require_once("functionesLogin.php");
        $register = new RegistroUser();
        
        $register->setDocumento($_POST["documento"]);
        $register->setUsuario($_POST["usuario"]);
        $register->setPassword($_POST["password"]);
        $register->setCorreo($_POST["correo"]);
        $register->setRol($_POST["rol"]);
        
        if ($register->checkUser($_POST['documento'])) {
            echo "<script> alert('Usuario existente'); document.location='registrarse.php'; </script>";
        }else if (( $_POST["nombre"])==='' || ($_POST["usuario"])==='' || ($_POST["password"])==='' ||  ( $_POST["correo"])==='' || ( $_POST["rol"])==='' ) {
            echo "<script> alert('Datos vacios');document.location='../Login/registrarse.php';</script>";
        }
        else{
            $register->insertData();
            echo "<script> alert('Usuario registrado exitosamente'); document.location='../html/index.php'; </script>";
   
        }
}   
?>