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
    <!-- boostrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-KK94CHFLLe+nY2dmCWGMq91rCGa5gtU4mk92HdvYe+M/SXH301p5ILy+dN9+nJOZ" crossorigin="anonymous">
    <!-- css -->
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="decoracion.css">
    <link rel="stylesheet" href="ingresar.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.3/font/bootstrap-icons.css">
    <title>crear cuentra</title>
</head>
<body>
<div class="container-m">
    <div class="section2 body" id="section2">
            <div id="imagen">
                <img src="../img/pequeña.gif" style=" width: 50rem; height: 25rem ;border-radius: 2rem;">
            </div>
             <div  class="d-flex justify-content-center align-items-center" >
                <form action="comprobarRegistrarse.php"   method="POST">
                    <h1 class="m-5" id="h1" style="font-weight: 800;">REGISTRAR</h1>

                    <div class="mb-3"id="escritura">
                        <label id="label" for="documento" class="form-label">Documento</label>
                        <input 
                          type="text"
                          id="documento input"
                          name="documento"
                          class="form-control"  
                        />
                      </div>
                    <div class="mb-3"id="escritura">
                        <label id="label" for="nombre" class="form-label">Nombre</label>
                        <input 
                          type="text"
                          id="nombre input"
                          name="nombre"
                          class="form-control"  
                        />
                      </div>
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
                    <label id="label" for="correo" class="form-label">correo</label>
                        <input 
                          type="correo"
                          id="correo input"
                          name="correo"
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
                    <div class="mb-3"id="escritura">
                        <label id="label" for="rol" class="form-label">Rol</label>
                        <input 
                          type="text"
                          id="rol input"
                          name="rol"
                          class="form-control"  
                        />
                      </div>
                    <div class="mb-3 form-check">
                      <input type="checkbox" class="form-check-input" id="exampleCheck1">
                      <label id="recuerdame" class="form-check-label" for="exampleCheck1">No me olvides dorindel</label>
                    </div>
                    <a href="./ingresar.php" id="cambioA">iniciar sesion</a>
                    <input type="submit" value="registrarse" name="registrarse" class="btn" id="button" >
                  </form>

            </div>
        </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ENjdO4Dr2bkBIFxQpeoTz1HIcje39Wm4jDKdf19U8gI4ddQ3GYNS7NTKfAdVQSZe" crossorigin="anonymous"></script>

</body>
</html>