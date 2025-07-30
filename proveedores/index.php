<!-- eso me pasa por meterme en software -->
<!-- pobresita, siempre puedes pedir ayuda recuerda -->
<?php
require_once("../Config/db.php");
require_once("../Config/conectar.php");
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Registro de Proveedor</title>
  <style>
    body {
      font-family: 'Segoe UI', sans-serif;
      background-color: #32475a;
      margin: 0;
      padding: 40px 20px;
      color: #fff;
    }

    h2 {
      background-color: #86c1e9;
      color: #1c2c3a;
      padding: 12px;
      text-align: center;
      border-radius: 6px;
      margin-bottom: 20px;
    }

    .formulario {
      background-color: #fff;
      color: #000;
      border-radius: 10px;
      padding: 25px;
      max-width: 1000px;
      margin: auto;
      box-shadow: 0 0 10px rgba(0,0,0,0.3);
    }

    .form-grid {
      display: grid;
      grid-template-columns: 1fr 1fr;
      column-gap: 20px;
      row-gap: 15px;
    }

    .form-grid label {
      font-weight: bold;
      margin-bottom: 4px;
      display: block;
    }

    .form-grid input, .form-grid select {
      width: 100%;
      padding: 8px;
      border: 1px solid #ccc;
      border-radius: 5px;
      font-size: 14px;
    }

    .botones {
      display: flex;
      justify-content: center;
      margin-top: 20px;
    }

    .botones button {
      padding: 10px 20px;
      font-weight: bold;
      margin: 0 10px;
      border: none;
      border-radius: 5px;
      cursor: pointer;
      font-size: 14px;
      color: #fff;
    }

    .guardar {
      background-color: #28a745;
    }

    .cancelar {
      background-color: #dc3545;
    }

    .tabla-container {
      background: #fff;
      color: #000;
      border-radius: 10px;
      padding: 20px;
      max-width: 1000px;
      margin: 40px auto;
      box-shadow: 0 0 10px rgba(0,0,0,0.3);
    }

    .tabla-container h2 {
      background-color: #86c1e9;
      color: #1c2c3a;
      padding: 10px;
      border-radius: 6px;
      margin-bottom: 15px;
    }

    table {
      width: 100%;
      border-collapse: collapse;
    }

    table th, table td {
      padding: 10px;
      border: 1px solid #ccc;
      text-align: center;
    }

    table th {
      background-color: #86c1e9;
      color: #1c2c3a;
    }

    .editar {
      background-color: #ffc107;
      color: white;
      padding: 6px 12px;
      border-radius: 4px;
      text-decoration: none;
    }

    .eliminar {
      background-color: #dc3545;
      color: white;
      padding: 6px 12px;
      border-radius: 4px;
      text-decoration: none;
    }

    .editar:hover, .eliminar:hover {
      opacity: 0.9;
    }
  </style>

<body>

<h2>Insertar Proveedor</h2>

<div class="formulario">
  <form method="POST" action="">
    <input type="hidden" name="id" value="<?php echo $_GET['editar'] ?? ''; ?>">

    <div class="form-grid">
      <div>
        <label>Nombre:</label>
        <input type="text" name="nombre" required value="<?php echo $nombre; ?>">
      </div>

      <div>
        <label>Teléfono:</label>
        <input type="text" name="telefono" required value="<?php echo $telefono; ?>">
      </div>

      <div>
        <label>Tipo Documento:</label>
        <select name="tipo_doc">
          <option>DNI</option>
          <option>Cédula</option>
          <option>NIT</option>
        </select>
      </div>

      <div>
        <label>Dirección:</label>
        <input type="text" name="direccion" required value="<?php echo $direccion; ?>">
      </div>

      <div>
        <label>Número Documento:</label>
        <input type="text" name="documento" required value="<?php echo $documento; ?>">
      </div>

      <div>
        <label>Email:</label>
        <input type="email" name="email" required value="<?php echo $email; ?>">
      </div>
    </div>

    <div class="botones">
      <?php if ($editar): ?>
        <button type="submit" name="actualizar" class="guardar">🔄 Actualizar</button>
        <a href="index.php"><button type="button" class="cancelar">❌ Cancelar</button></a>
      <?php else: ?>
        <button type="submit" name="guardar" class="guardar">💾 Guardar</button>
        <button type="reset" class="cancelar">❌ Cancelar</button>
      <?php endif; ?>
    </div>
  </form>
</div>

<div class="tabla-container">
  <h2>Datos de Proveedores</h2>
  <table>
    <thead>
      <tr>
        <th>Nro</th>
        <th>Nombre</th>
        <th>Documento</th>
        <th>Teléfono</th>
        <th>Dirección</th>
        <th>E-mail</th>
        <th>Editar</th>
        <th>Eliminar</th>
      </tr>
    </thead>
    <tbody>
      <?php
      $resultado = mysqli_query($conexion, "SELECT * FROM proveedores");
      $i = 1;
      while ($fila = mysqli_fetch_assoc($resultado)):
      ?>
      <tr>
        <td><?php echo $i++; ?></td>
        <td><?php echo $fila['nombre']; ?></td>
        <td><?php echo $fila['documento']; ?></td>
        <td><?php echo $fila['telefono']; ?></td>
        <td><?php echo $fila['direccion']; ?></td>
        <td><?php echo $fila['email']; ?></td>
        <td><a class="editar" href="index.php?editar=<?php echo $fila['id']; ?>">Editar</a></td>
        <td><a class="eliminar" href="index.php?eliminar=<?php echo $fila['id']; ?>" onclick="return confirm('¿Estás seguro de eliminar este proveedor?');">Eliminar</a></td>
      </tr>
      <?php endwhile; ?>
    </tbody>
  </table>
</div>
