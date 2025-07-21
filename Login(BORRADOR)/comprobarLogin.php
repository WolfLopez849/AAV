<?php
ini_set("display_errors", 1);

ini_set("display_startup_errors", 1);

error_reporting(E_ALL);

session_start();
if (isset($_POST['loguearse'])) {
    require_once("functionsRegister.php");
    $credenciales = new LoginUser();
    $credenciales->setUsuario($_POST['usuario']);
    $credenciales->setPassword($_POST['password']);
    $login = $credenciales->login();
    if ($login) {
        header('Location:../html/index.php');
    }else {
        echo "<script>alert('email o contraseña incorrecto'); document.location='ingresar.php';</script>";
    }
}

?>