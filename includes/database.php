<?php

try {
    $db = mysqli_connect('localhost', 'root', 'root', 'trabajoterminal');
} catch (Exception $e) {
    echo "Error de conexion en la base de datos: " . $e->getMessage();
    exit;
}



if (!$db) {
    echo "Error: No se pudo conectar a MySQL.";
    echo "errno de depuración: " . mysqli_connect_errno();
    echo "error de depuración: " . mysqli_connect_error();
    exit;
}
