<?php

namespace App\Utils;

use App\Db\Provincia;
use App\Db\User;

class Validaciones {
    public static function sanearCadenas(string $cadena) : string {
        return htmlspecialchars(trim($cadena));
    }

    public static function isLongitudValida(string $nomCampo, string $valor, int $min, int $max) : bool {
        if(strlen($valor) < $min || strlen($valor) > $max) {
            $_SESSION["err_$nomCampo"] = "***ERROR, $nomCampo debe tener entre $min y $max caracteres. ***";
            return false;
        }
        return true;
    }

    public static function isEmailValido(string $email) : bool {
        if(!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $_SESSION['err_email'] = "*** ERROR, el email introducido no es válido. ***";
            return false;
        }
        return true;
    }

    public static function isProvinciaValida($prov) : bool {
        if (!in_array($prov, Provincia::getIdsProvincias())) {
            $_SESSION['err_provincia'] = "*** ERROR, la provincia introducida no es válida o no está seleccionada. ***";
            return false;
        }
        return true;
    }

    public static function imagenValida(string $tipo, int $size) : bool {
        if(!in_array($tipo, Datos::getTypeImages())) {
            $_SESSION['err_imagen'] = "*** ERROR, el tipo de la imagen no es válido. ***";
            return false;
        }
        if ($size > 2000000) {
            $_SESSION['err_imagen'] = "*** ERROR, el tamaño de la imagen no puede superar los 2MBs. ***";
            return false;
        }
        return true;
    }

    public static function isCampoUnico(string $nomCampo, string $valor, int $id=null) : bool {
        if(User::esCampoUnico($nomCampo, $valor, $id)) {
            $_SESSION["err_$nomCampo"] = "*** ERROR, el valor de $nomCampo está duplicado. ***";
            return true;
        }
        return false;
    }

    public static function pintarErrores(string $error) : void {
        if (isset($_SESSION[$error])){
            echo "<p class='text-red-500 text-xl italic'>$_SESSION[$error]</p>";
            unset($_SESSION[$error]);
        }
    }
}