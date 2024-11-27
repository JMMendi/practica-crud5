<?php

use App\Db\User;

session_start();

$id = filter_input(INPUT_POST, 'id');

if (!$id || $id <= 0) {
    header("Location:users.php");
    exit;
}
require __DIR__."/../vendor/autoload.php";


if (!$imagen = User::getImagenById($id)) {
    die("El id no existe");
    header("Location:users.php");
    exit;
} else {
    if (basename($imagen) != ("Chocobo_Shibuya.webp")) {
        unlink($imagen);
    }
    User::delete($id);
}


$_SESSION['mensaje'] = "Usuario eliminado correctamente.";
header("Location: users.php");