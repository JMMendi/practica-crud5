<?php

use App\Db\User;

session_start();

require __DIR__ . "/../vendor/autoload.php";

$usuarios = User::read();
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Usuarios</title>
    <!-- CDN sweetalert2 -->

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <!-- CDN Tailwind -->
    <script src="https://cdn.tailwindcss.com"></script>

    <!-- CDN FontAwesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css" integrity="sha512-Kc323vGBEqzTmouAECnVceyQqyqdsSiqLQISBL29aUW4U/M7pSPA/gEUZQqv1cwx4OnYxTxve5UMg5GT6L4JJg==" crossorigin="anonymous" referrerpolicy="no-referrer" />

</head>

<body class="bg-teal-200 p-4">
    <h3 class="py-2 text-center text-xl">Listado de Usuarios</h3>
    

<div class="w-1/2 mx-auto p-2">
    <a href="nuevo.php" class="text-xl rounded-xl bg-green-300">
        <i class="fas fa-add"></i>NUEVO
    </a>
    <table class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400">
        <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
            <tr>
                <th scope="col" class="px-6 py-3">
                    Username
                </th>
                <th scope="col" class="px-6 py-3">
                    Provincia
                </th>
                <th scope="col" class="px-6 py-3">
                    Acciones
                </th>
            </tr>
        </thead>
        <tbody>
            <?php 
                foreach ($usuarios as $item) :
            ?>
            <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600">
                <th scope="row" class="flex items-center px-6 py-4 text-gray-900 whitespace-nowrap dark:text-white">
                    <img class="w-10 h-10 rounded-full" src="<?= $item->imagen ?>" alt="Jese image">
                    <div class="ps-3">
                        <div class="text-base font-semibold"><?= $item->username ?></div>
                        <div class="font-normal text-gray-500"><?= $item->email ?></div>
                    </div>  
                </th>
                <td class="px-6 py-4">
                    <p class="p-2 rounded-xl border-2 border-black" 
                    style="background-color:<?= $item->color ?> ; color:black"><?= $item->nombre ?></p>
                </td>
                <td class="px-6 py-4">
                    <form action="delete.php" method="POST">
                        <a href="update.php?id=<?= $item->id ?>" class="font-medium text-blue-600 dark:text-blue-500 hover:underline">
                            <i class="fas fa-edit text-green-500 hover:text-xl"></i>
                        </a>
                        <button type="submit">
                            <i class="fas fa-trash text-red-500 hover:text-xl"></i>
                        </button>
                    </form>
                </td>
            </tr>
            <?php endforeach; ?>
           
        </tbody>
    </table>
</div>

<?php
 if(isset($_SESSION['mensaje'])) :
?>
    <script>
        Swal.fire({
          icon: "success",
          title: "<?= $_SESSION['mensaje'] ?>",
          showConfirmButton: false,
          timer: 1500
        });
    </script>
    <?php 
        unset($_SESSION['mensaje']);
        endif; 
    ?>
</body>
</html>