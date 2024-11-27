<?php

use App\Db\Provincia;
use App\Db\User;
use App\Utils\Validaciones;

session_start();

require __DIR__ . "/../vendor/autoload.php";

$provincias = Provincia::read();

if (isset($_POST['username'])) {
    $username = Validaciones::sanearCadenas($_POST['username']);
    $email = Validaciones::sanearCadenas($_POST['email']);
    $provincia = (isset($_POST['provincia_id'])) ?? -1;

    $errores = false;

    if (!Validaciones::isLongitudValida('username', $username, 3, 24)) {
        $errores = true;
    } else if (Validaciones::isCampoUnico('username', $username)) {
        $errores = true;
    }
    if (!Validaciones::isEmailValido($email)) {
        $errores = true;
    } else if (!Validaciones::isCampoUnico('email', $email)) {
        $errores = true;
    }
    if (!Validaciones::isProvinciaValida($provincia)) {
        $errores = true;
    }

    $imagen = "img/Chocobo_Shibuya.webp";

    if (is_uploaded_file($_FILES['imagen']['tmp_name'])) {
        if (!Validaciones::imagenValida($_FILES['imagen']['type'], $_FILES['imagen']['size'])) {
            $errores = true;
        } else {
            $imagen = "imag/" . uniqid() . "_" . $_FILES['imagen']['tmp_name'];
            if (!move_uploaded_file($_FILES['imagen']['tmp_name'], $imagen)) {
                $_SESSION['err_imagen'] = "*** ERROR, no se pudo guardar la imagen en la ruta prevista. ***";
                $errores = true;
            }
        }
    }

    // $imagen = "img/Capibara.jpeg";
    // if (is_uploaded_file($_FILES['imagen']['tmp_name'])) {
    //     // Si estoy aquí, el usuario subió un fichero. Ahora comprobamos si es válido.
    //     if (!Validaciones::isImagenValida($_FILES['imagen']['type'], $_FILES['imagen']['size'])) {
    //         $errores = true;
    //     } else {
    //         // Si he llegado aquí, el fichero es válido de tipo y tamaño.
    //         $imagen = "img/" . uniqid() . "_" . $_FILES['imagen']['name'];
    //         if (!move_uploaded_file($_FILES['imagen']['tmp_name'], $imagen)) {
    //             $_SESSION['err_imagen'] = "*** Error no se pudo guardar la imagen en la ruta prevista. ***";
    //             $errores = true;
    //         }
    //     }
    // }

    if ($errores) {
        header("Location: nuevo.php");
        exit;
    }

    (new User)
    ->setUsername($username)
    ->setEmail($email)
    ->setProvinciaID($provincia)
    ->setImagen($imagen)
    ->create();

    $_SESSION['mensaje'] = "El usuario $username ha sido creado correctamente.";
    header("Location:users.php");
}

?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nuevo Usuario</title>

    <!-- CDN sweetalert2 -->

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <!-- CDN Tailwind -->
    <script src="https://cdn.tailwindcss.com"></script>

    <!-- CDN FontAwesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css" integrity="sha512-Kc323vGBEqzTmouAECnVceyQqyqdsSiqLQISBL29aUW4U/M7pSPA/gEUZQqv1cwx4OnYxTxve5UMg5GT6L4JJg==" crossorigin="anonymous" referrerpolicy="no-referrer" />

</head>

<body class="bg-teal-500 p-2">
    <h3 class="py-2 text-center text-xl">Crear Usuarios</h3>

    <div class="mx-auto w-2/4 rounded-x1 shadow-x1 border-2 border-black p-6">
        <form method="POST" action="<?= $_SERVER['PHP_SELF'] ?>" enctype="multipart/form-data">
            <div class="mb-5">
                <label for="username" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Username</label>
                <input type="text" id="username" name="username" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" placeholder="Username..." />
                <?php
                Validaciones::pintarErrores('err_username');
                ?>
            </div>

            <div class="mb-5">
                <label for="email" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Email</label>
                <input type="email" id="email" name="email" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" placeholder="usuario@email.com" />
                <?php Validaciones::pintarErrores('err_email'); 
                ?>

            </div>
            <div class="mb-5">
                <label for="provincia_id" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Provincia</label>
                <div class="flex">
                    <select name="provincia_id" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                        <option> ___ Selecciona una provincia ___</option>
                    <?php foreach ($provincias as $item) : ?>
                        <option value="<?= $item->id ?>"><?= $item->nombre ?></option>
                    <?php endforeach; ?>
                    </select>
                    
                </div>
                <?php Validaciones::pintarErrores('err_provincia_id'); 
                ?>

            </div>
            <div class="mb-5">
                <label for="imagen" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Imagen</label>
                <div class="flex justify-between">
                    <div>
                        <input type="file" name="imagen" accept="image/*" oninput="imgpreview.src=window.URL.createObjectURL(this.files[0])" /> <!-- Esto último es para que muestre la primera imagen que subes -->
                    </div>
                    <div class="w-full ml-8">
                        <img src="img/Chocobo_Shibuya.webp" id="imgpreview" alt="imagen por defecto" class="w-56 h-56 w-full rounded object-fill">
                    </div>
                </div>
                <?php Validaciones::pintarErrores('err_imagen'); 
                ?>

            </div>
            <div class="flex flex-row-reverse mb-2">
                <button type="submit" class="font-bold text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm w-full sm:w-auto px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">
                    <i class="fas fa-save mr-2"></i>GUARDAR
                </button>
                <a href="users.php" class="mr-2 font-bold text-white bg-green-700 hover:bg-green-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm w-full sm:w-auto px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">
                    <i class="fas fa-home mr-2"></i>VOLVER
                </a>
            </div>

        </form>
    </div>

</body>

</html>