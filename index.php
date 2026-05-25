<?php
include 'conexion.php';
$mensaje = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $categoria   = $_POST['categoria'];
    $stock       = $_POST['stock']; 
    $descripcion = $_POST['descripcion'];
    $precio      = $_POST['precio'];

    if (!empty($categoria) && isset($stock) && !empty($precio)) {
        // Aquí ya usamos 'stock' en lugar de 'nombre' para la base de datos
        $stmt = $conexion->prepare("INSERT INTO platos (categoria, stock, descripcion, precio) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("sisd", $categoria, $stock, $descripcion, $precio);
        
        if ($stmt->execute()) {
            $mensaje = "<p class='success'>¡Plato registrado con éxito en XAMPP!</p>";
        } else {
            $mensaje = "<p class='error'>Error al guardar: " . $conexion->error . "</p>";
        }
        $stmt->close();
    } else {
        $mensaje = "<p class='error'>Por favor, llena los campos obligatorios.</p>";
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Formulario de Trabajo Grupal</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            text-align: center;
            margin-top: 40px;
            background-color: #ffffff;
        }
        h1 {
            color: #3498db;
            font-size: 24pt;
            font-weight: normal;
            margin-bottom: 25px;
        }
        h2 {
            font-size: 16pt;
            margin-bottom: 20px;
            font-weight: bold;
        }
        .formulario-contenedor {
            max-width: 450px;
            margin: 0 auto;
            text-align: left;
        }
        .grupo-campo {
            margin-bottom: 12px;
            display: block;
        }
        .grupo-campo label {
            display: inline-block;
            width: 40%;
            text-align: right;
            padding-right: 15px;
            box-sizing: border-box;
            font-size: 11pt;
        }
        .grupo-campo input, .grupo-campo select, .grupo-campo textarea {
            width: 55%;
            padding: 4px;
            border: 1px solid #999;
            box-sizing: border-box;
            font-size: 11pt;
            vertical-align: middle;
        }
        .btn-contenedor {
            text-align: center;
            margin-top: 30px;
        }
        .btn-enviar {
            padding: 8px 25px;
            background-color: #f2f2f2;
            border: 1px solid #aeaeae;
            font-size: 11pt;
            cursor: pointer;
            border-radius: 2px;
        }
        .btn-enviar:hover {
            background-color: #e2e2e2;
        }
        .success { color: green; font-weight: bold; text-align: center; margin-bottom: 15px; }
        .error { color: red; font-weight: bold; text-align: center; margin-bottom: 15px; }
    </style>
</head>
<body>

    <h1>Formulario de Trabajo Grupal</h1>
    
    <div class="formulario-contenedor">
        <h2>Registro de Platos (Esmar Burger)</h2>
        
        <?php echo $mensaje; ?>

        <form action="index.php" method="POST">
            
            <div class="grupo-campo">
                <label>Categoría del Menú:</label>
                <select name="categoria" required>
                    <option value="">-- Selecciona --</option>
                    <option value="Hamburguesas">Hamburguesas</option>
                    <option value="Broaster">Broaster</option>
                    <option value="Salchipapas">Salchipapas</option>
                    <option value="Bebidas">Bebidas / Otros</option>
                </select>
            </div>

            <div class="grupo-campo">
                <label>Stock:</label>
                <input type="number" name="stock" placeholder="Cantidad disponible" required>
            </div>

            <div class="grupo-campo">
                <label>Ingredientes / Descripción:</label>
                <textarea name="descripcion" rows="2" placeholder="Ej: Carne + queso + jamón"></textarea>
            </div>

            <div class="grupo-campo">
                <label>Precio (S/):</label>
                <input type="number" step="1" name="precio" placeholder="Ej: 12" required>
            </div>

            <div class="btn-contenedor">
                <button type="submit" class="btn-enviar">Enviar Datos</button>
            </div>

        </form>
    </div>

</body>
</html>