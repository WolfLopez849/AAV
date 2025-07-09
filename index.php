<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Disco MeliLau</title>
    <style>
        body {
            font-family: 'didot', sans-serif;
            background-color: #495057;
            color: #eee;
            display: flex;
            flex-direction: column;
            align-items: center;
            height: 10vh;
        }
        form {
            background-color: #14213D;
            padding: 15px;
            border-radius: 10px;
            box-shadow: 0 0 10px #000;
        }
        input [type="number"]{
            padding: 8px;
            
            border-radius: 8px;
            margin-right: 8px;
        }
        input[type="submit"] {
            padding: 8px 16px;
            border: none;
            background-color: rgb(94, 29, 224);
            color: white;
            border-radius: 5px;
            cursor: pointer;
        }
        .mensaje{
            margin-top: 20px;
            font-size: 1.2em;
            padding: 10px;
            background-color: #14213D;
            border-radius: 5px;
        }
        </style>
</head> 
    </body>

    <h1>Portero de Disco MeliLau automatico</h1>
    <p>laura</p>

    <form method="post">
        <label for="nacimiento">Año de nacimiento:</label>
        <input type="number" name="nacimiento" id="nacimiento" required min==1920 max ="<?php echo date ('Y'); ?>">
        <input type="submit" value="Entrar">
    </form>
   <?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nacimiento = intval($_POST["nacimiento"]);
    $año_actual = date("Y");
    $edad = $año_actual - $nacimiento;

    echo "<div class='mensaje'>";
    if ($edad < 18) {
        echo "Lo siento, no puedes entrar. ¡Vuelve cuando seas mayor de edad!";
    } elseif ($edad > 65) {
        echo "Hmm... ya estás un poco mayor para esto. ¡Mejor ve a descansar!";
    } else {
        echo "Adelante, puedes pasar. ¡Que la pases bien!";
    }
    echo "</div>";
}
?>

</body>
</html>