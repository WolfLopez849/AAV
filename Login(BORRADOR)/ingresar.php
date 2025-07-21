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
     <!-- Typografia -->
     <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@200;400;600&display=swap" rel="stylesheet">
    <!-- boostrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-KK94CHFLLe+nY2dmCWGMq91rCGa5gtU4mk92HdvYe+M/SXH301p5ILy+dN9+nJOZ" crossorigin="anonymous">
    <!-- css -->
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="decoracion.css">
    <link rel="stylesheet" href="ingresar.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.3/font/bootstrap-icons.css">
    <title>iniciar sesion</title>
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
                <a href="./registrarse.php" class="titulo">crear una cuenta</a><br>
                <input type="submit" value="loguearse" name="loguearse" class="btn a">
              </form>
                  

        </div>

      
      </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ENjdO4Dr2bkBIFxQpeoTz1HIcje39Wm4jDKdf19U8gI4ddQ3GYNS7NTKfAdVQSZe" crossorigin="anonymous"></script>

</body>
</html>