<?php

use App\Db\Provincia;
use App\Db\User;

require __DIR__."/../vendor/autoload.php";

$cantidad = 0;

do {
    $cantidad = readline("Introduzca el número de usuarios a crear: ");
    if ($cantidad < 5 || $cantidad > 40) {
        echo "Error, debe introducir una cantidad entre 5 y 40";
    }
} while ($cantidad < 5 || $cantidad > 40);

//Provincia::crearProvinciasRandom();
User::crearRegistrosRandom($cantidad);

echo "Se han creado $cantidad usuarios.".PHP_EOL;