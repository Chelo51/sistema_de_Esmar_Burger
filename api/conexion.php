<?php
// Valores por defecto para desarrollo local en XAMPP
$servidor   = "localhost";
$usuario    = "root"; 
$password   = "";     
$base_datos = "esmar_burger_db";
$puerto     = 3306;

// Soporte para entornos de producción (Render, Railway, Heroku, etc.)
$db_url = getenv("JAWSDB_URL") ?: getenv("CLEARDB_DATABASE_URL") ?: getenv("DATABASE_URL");

if ($db_url) {
    $url = parse_url($db_url);
    if (isset($url["host"])) {
        $servidor   = $url["host"];
        $usuario    = $url["user"] ?? '';
        $password   = $url["pass"] ?? '';
        $base_datos = substr($url["path"] ?? '', 1); // Remover la diagonal '/' inicial
        $puerto     = $url["port"] ?? 3306;
    }
} else {
    // Verificar variables de entorno individuales
    if (getenv("DB_HOST"))     $servidor   = getenv("DB_HOST");
    if (getenv("DB_USER"))     $usuario    = getenv("DB_USER");
    if (getenv("DB_PASSWORD")) $password   = getenv("DB_PASSWORD");
    if (getenv("DB_NAME"))     $base_datos = getenv("DB_NAME");
    if (getenv("DB_PORT"))     $puerto     = getenv("DB_PORT");
}

// Establecer conexión
$conexion = new mysqli($servidor, $usuario, $password, $base_datos, $puerto);

if ($conexion->connect_error) {
    die("Error en la conexión a la base de datos: " . $conexion->connect_error);
}
?>
