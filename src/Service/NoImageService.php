<?php

namespace App\Service;

use App\Entity\Image;

class NoImageService
{
    private static $instance;

    private function __construct()
    {

    }

    public static function getNoImageInstance(): Image
    {
        if (!self::$instance) {
            self::$instance = new Image();
            self::$instance->setUrl("noImage.jpg");
            self::$instance->setFigcaption("Brak zdjęcia");
        }

        return self::$instance;
    }
}

