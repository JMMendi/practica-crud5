<?php

namespace App\Utils;

class Datos {
    public static function getTypeImages() : array {
        return [
            'image/png',
            'image/jpeg',
            'image/jpg',
            'image/bmg',
            'image/webp'
        ];
    }
}