<?php
/* ini_set("display_errors", 1);
ini_set("display_startup_errors", 1);
error_reporting(E_ALL); */

session_start();
session_destroy();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inicio</title>
    <!-- boostrap -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-LN+7fdVzj6u52u30Kp6M/trliBMCMKTyK833zpbD+pXdCLuTusPj697FH4R/5mcr" crossorigin="anonymous">    <!-- css -->
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="decoracion.css">
    <link rel="stylesheet" href="ingresar.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.3/font/bootstrap-icons.css">
</head>
<body>
    <div class="container-m">
      <div class="section1 cuadro-contenedor">
          <div>
            <?php
              if ($_SESSION['usuario']) {
                echo '<a class="btn btn-danger" href="../html/index.php">Volver</a>';
              }
            ?>
          </div>
            <div class="imagen">
                <img style="border-radius: 1rem;" src="https://cdn.discordapp.com/attachments/1078289568949866509/1078292170441764914/2.gif" alt="">
            </div>
          <div class="d-flex justify-content-center align-items-center titulo"><h1 style="font-weight: 800;">BIENVENIDOS</h1></div>
            <div  class="d-flex justify-content-center align-items-center" >
              <form action="comprobarLogin.php"  method="POST">
                <div class="mb-3" id="input">
                  <label for="email" class="iniciarSesion">Email</label>
                  <span class="icono"><i class="bi bi-person-fill"></i></span>  
                  <input 
                      type="email"
                      id="email"
                      name="email"
                      class="form-control"  
                    />
                </div>
                <div class="mb-3" id="input">
                  <label for="password" class="iniciarSesion">Password</label>
                  <span class="icono"><i class="bi bi-unlock"></i></span>
                    <input 
                      type="password"
                      id="password"
                      name="password"
                      class="form-control"  
                    />
                </div>
                <input type="submit" value="loguearse" name="loguearse" class="btn a">
              </form>
        </div>
      </div>
        <div class="section2 body" id="section2">
            <div id="imagen">
                <img src="../img/pequeña.gif" style=" width: 600px; height: 350px ;border-radius: 2rem;">
            </div>
             <div  class="d-flex justify-content-center align-items-center" >
                
                <form action="comprobarRegistrarse.php"   method="POST">
                    <h1 class="m-5" id="h1" style="font-weight: 800;">REGISTRAR</h1>
                    <div class="mb-3"id="escritura">
                        <label id="label" for="usuario" class="form-label">Usuario</label>
                        <input 
                          type="text"
                          id="usuario input"
                          name="usuario"
                          class="form-control"  
                        />
                      </div>
                    <div class="mb-3"id="escritura">
                    <label id="label" for="email" class="form-label">email</label>
                        <input 
                          type="cor"
                          id="email input"
                          name="email"
                          class="form-control"  
                        />
                    </div>
                    <div class="mb-3"id="escritura">
                    <label id="label" for="password" class="form-label">Password</label>
                        <input 
                          type="password"
                          id="password input"
                          name="password"
                          class="form-control"  
                        />
                    </div>
                    <div class="mb-3 form-check">
                      <input type="checkbox" class="form-check-input" id="exampleCheck1">
                      <label id="recuerdame" class="form-check-label" for="exampleCheck1">No me olvides dorindel</label>
                    </div>
                    <input type="submit" value="registrarse" name="registrarse" class="btn" id="button" >
                  </form>

            </div> 
        </div>
    </div>
      <!-- Boostrap -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/js/bootstrap.bundle.min.js" integrity="sha384-ndDqU0Gzau9qJ1lfW4pNLlhNTkCfHzAVBReH9diLvGRem5+R9g2FzA8ZGN954O5Q" crossorigin="anonymous"></script></body>
</html>