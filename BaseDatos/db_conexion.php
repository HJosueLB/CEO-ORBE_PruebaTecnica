<?php

// Función para conectar a la base de datos.
Function db_conexion() {

    // Se declara las variables requeridas para la conexión.

    $server_name = 'localhost'; // Nombre del servidor de la BD.
    $user = 'root'; // Nombre de usuario de acceso BD.
    $password = ''; // Contraseña del usuario de acceso BD.
    $db_name = 'ColegioBD'; // Nombre de la base de datos.

    // Codigo de conexión a la base de datos.
    $conexion = mysqli_connect( $server_name, $user, $password, $db_name );

    // Verificar conexión con condicional.
    if ( $conexion->connect_error ) {
        die( 'Conexión fallida: ' . $conexion->connect_error);
    }

    return $conexion;
}

// Llamado a la función de conexión a la base de datos.
$conexion = db_conexion(); 

?>