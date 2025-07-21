<?php
ini_set("display_errors", 1);

ini_set("display_startup_errors", 1);

error_reporting(E_ALL);
    if (isset($_POST['registrarse'])){
        require_once("functionesLogin.php");
        $registro = new RegistroUser(
            $_POST['documento'],
            $_POST['usuario'],
            $_POST['nombre'],
            $_POST['correo'],
            $_POST['password'],
            $_POST['rol']
        );
        if ($registro->checkUser($_POST['documento'])) {
            echo "<script> alert('Usuario existente'); document.location='registrarse.php'; </script>";
        }
        elseif (( $_POST["nombre"])==='' || ($_POST["usuario"])==='' || ($_POST["password"])==='' ||  ( $_POST["correo"])==='' || ( $_POST["rol"])==='' ) {
            echo "<script> alert('Datos vacios');document.location='registrarse.php';</script>";
        }
        else{
            $registro->insertData();
            echo "<script> alert('Usuario registrado exitosamente'); document.location='../Menu/index.php'; </script>";
   
        }
}   
?>