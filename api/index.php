<?php
include 'conexion.php';
$mensaje = "";

// Verificar si la conexión falló
if (!$conexion) {
    $mensaje = "<div class='mensaje-contenedor error' id='msg-db-error'>
                    <svg width='20' height='20' viewBox='0 0 24 24' fill='none' stroke='currentColor' stroke-width='2' stroke-linecap='round' stroke-linejoin='round' style='flex-shrink:0;'><path d='M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z'></path><line x1='12' y1='9' x2='12' y2='13'></line><line x1='12' y1='17' x2='12.01' y2='17'></line></svg>
                    <div style='text-align: left;'>
                        <strong>Sin conexión a base de datos:</strong><br>
                        <span style='font-size: 0.82rem; opacity: 0.85; line-height: 1.3;'>
                            " . (!empty(getenv("DATABASE_URL")) || !empty(getenv("DB_HOST")) 
                                ? "Verifica las credenciales de tu base de datos remota en Vercel." 
                                : "En producción (Vercel) necesitas una base de datos en la nube. En local (XAMPP), asegúrate de iniciar el servicio MySQL.") . "
                        </span>
                    </div>
                </div>";
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && $conexion) {
    $categoria   = $_POST['categoria'];
    $stock       = $_POST['stock']; 
    $descripcion = $_POST['descripcion'];
    $precio      = $_POST['precio'];

    if (!empty($categoria) && isset($stock) && !empty($precio)) {
        // Validación y prepared statements seguros
        $stmt = $conexion->prepare("INSERT INTO platos (categoria, stock, descripcion, precio) VALUES (?, ?, ?, ?)");
        if ($stmt) {
            $stmt->bind_param("sisd", $categoria, $stock, $descripcion, $precio);
            
            if ($stmt->execute()) {
                $mensaje = "<div class='mensaje-contenedor success' id='msg-success'>
                                <svg width='18' height='18' viewBox='0 0 24 24' fill='none' stroke='currentColor' stroke-width='2' stroke-linecap='round' stroke-linejoin='round'><polyline points='20 6 9 17 4 12'></polyline></svg>
                                <span>¡Plato registrado con éxito en el sistema!</span>
                            </div>";
            } else {
                $mensaje = "<div class='mensaje-contenedor error' id='msg-error'>
                                <svg width='18' height='18' viewBox='0 0 24 24' fill='none' stroke='currentColor' stroke-width='2' stroke-linecap='round' stroke-linejoin='round'><circle cx='12' cy='12' r='10'></circle><line x1='12' y1='8' x2='12' y2='12'></line><line x1='12' y1='16' x2='12.01' y2='16'></line></svg>
                                <span>Error al guardar: " . $conexion->error . "</span>
                            </div>";
            }
            $stmt->close();
        } else {
            $mensaje = "<div class='mensaje-contenedor error' id='msg-error'>
                            <svg width='18' height='18' viewBox='0 0 24 24' fill='none' stroke='currentColor' stroke-width='2' stroke-linecap='round' stroke-linejoin='round'><circle cx='12' cy='12' r='10'></circle><line x1='12' y1='8' x2='12' y2='12'></line><line x1='12' y1='16' x2='12.01' y2='16'></line></svg>
                            <span>Error de base de datos: no se pudo preparar la consulta.</span>
                        </div>";
        }
    } else {
        $mensaje = "<div class='mensaje-contenedor error' id='msg-error'>
                        <svg width='18' height='18' viewBox='0 0 24 24' fill='none' stroke='currentColor' stroke-width='2' stroke-linecap='round' stroke-linejoin='round'><circle cx='12' cy='12' r='10'></circle><line x1='12' y1='8' x2='12' y2='12'></line><line x1='12' y1='16' x2='12.01' y2='16'></line></svg>
                        <span>Por favor, llena todos los campos obligatorios.</span>
                    </div>";
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro de Platos - Esmar Burger</title>
    <meta name="description" content="Sistema de gestión y registro de platos para el restaurante Esmar Burger.">
    <link rel="stylesheet" href="css/style.css">
</head>
<body>

    <h1>Esmar <span>Burger</span></h1>
    <p class="subtitle">Panel de Administración de Cocina</p>
    
    <main class="formulario-contenedor">
        <h2>Registrar Nuevo Plato</h2>
        
        <?php echo $mensaje; ?>

        <form action="index.php" method="POST">
            
            <div class="grupo-campo">
                <label for="categoria">Categoría del Menú:</label>
                <select id="categoria" name="categoria" required <?php echo !$conexion ? 'disabled' : ''; ?>>
                    <option value="">-- Selecciona --</option>
                    <option value="Hamburguesas">Hamburguesas</option>
                    <option value="Broaster">Broaster</option>
                    <option value="Salchipapas">Salchipapas</option>
                    <option value="Bebidas">Bebidas / Otros</option>
                </select>
            </div>

            <div class="grupo-campo">
                <label for="stock">Stock Disponible:</label>
                <input type="number" id="stock" name="stock" placeholder="Cantidad disponible (ej: 50)" min="0" required <?php echo !$conexion ? 'disabled' : ''; ?>>
            </div>

            <div class="grupo-campo">
                <label for="descripcion">Ingredientes / Descripción:</label>
                <textarea id="descripcion" name="descripcion" rows="3" placeholder="Ej: Carne de 150g, doble queso cheddar, tocino ahumado y salsa de la casa" <?php echo !$conexion ? 'disabled' : ''; ?>></textarea>
            </div>

            <div class="grupo-campo">
                <label for="precio">Precio de Venta (S/):</label>
                <input type="number" id="precio" step="0.01" name="precio" placeholder="Ej: 12.50" min="0" required <?php echo !$conexion ? 'disabled' : ''; ?>>
            </div>

            <div class="btn-contenedor">
                <button type="submit" class="btn-enviar" id="btn-submit" <?php echo !$conexion ? 'disabled style="opacity: 0.55; cursor: not-allowed; background: linear-gradient(135deg, #7f8c8d, #95a5a6); box-shadow: none;"' : ''; ?>>
                    <?php echo !$conexion ? 'Sin Conexión' : 'Registrar Plato'; ?>
                </button>
            </div>

        </form>
    </main>

</body>
</html>
