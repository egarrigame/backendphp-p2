<?php
// src/config/database.php

/**
 * Configuración de la conexión a la base de datos
 */

// Credenciales bbdd
// Docker local
define('DB_HOST', 'db'); // El nombre del servicio del docker-compose
define('DB_NAME', 'backend_ddbb');
define('DB_USER', 'backend_user');
define('DB_PASS', 'backend_userpassword');
define('DB_CHARSET', 'utf8mb4');

/**
 * Función tener la instacia de la conexión (Singleton para evitar conexiones simultáneas)
 * * @return PDO La conexión a la base de datos.
 */
function conectarDB(): PDO {
    static $pdo = null; // Variable para almacenar la conexión (singleton), la primera vez es null, después tendrá la conexión creada

    if ($pdo === null) { // si no existe la conexión, la creamos
        try {
            $dsn = "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=" . DB_CHARSET; // data source name - las credenciales
            $options = [
                PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION, // lanzamos errores en caso de fallos
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC, // que PDO devuelva arrays asociativos (campo - valor)     
                PDO::ATTR_EMULATE_PREPARES   => false, // desactivar sentencvias preparadas, usar nativas del motor de bbdd para mayor seguridad                 
            ];

            $pdo = new PDO($dsn, DB_USER, DB_PASS, $options); // instancias de PDO para conectar la bbdd

        } catch (PDOException $e) {
            die("Error de conexión a la base de datos: " . $e->getMessage()); // si algo falla, detenemos ejecución y mostramos el error
        }
    }

    return $pdo;
}
