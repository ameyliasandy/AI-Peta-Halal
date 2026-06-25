<?php

namespace App\Helpers;

class ImageHelper
{
    public static function restoran($nama)
    {
        $nama = strtolower($nama);

        if (
            str_contains($nama, 'ayam') ||
            str_contains($nama, 'kfc') ||
            str_contains($nama, 'gepuk')
        ) {
            return asset('images/restoran/ayam.jpg');
        }

        if (
            str_contains($nama, 'mie') ||
            str_contains($nama, 'noodle') ||
            str_contains($nama, 'marugame')
        ) {
            return asset('images/restoran/mie.jpg');
        }

        if (
            str_contains($nama, 'seafood') ||
            str_contains($nama, 'ikan')
        ) {
            return asset('images/restoran/seafood.jpg');
        }

        if (
            str_contains($nama, 'warung') ||
            str_contains($nama, 'sunda') ||
            str_contains($nama, 'padang') ||
            str_contains($nama, 'rm')
        ) {
            return asset('images/restoran/nusantara.jpg');
        }

        return asset('images/restoran/default.jpg');
    }
}