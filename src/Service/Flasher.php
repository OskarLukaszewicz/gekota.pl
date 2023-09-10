<?php

namespace App\Service;

class Flasher 
{
    static function getFlashMessage(string $action, string $object, string $objectName = null): string
    {
        $messages = [
            'Animal' => [
                'remove' => "Zwierzak " . $objectName . " został usunięty",
                'create' => "Sukces! Zwierzak " . $objectName . " został dodany",
                'update' => "Sukces! Zwierzak " . $objectName . " został zedytowany"
            ],
            'BlogPost' => [
                'remove' => "Post został usunięty",
                'create' => "Sukces! Post został dodany",
                'update' => "Sukces! Post został zedytowany"
            ],
            'Event' => [
                'remove' => "Wydarzenie zostało usunięte",
                'create' => "Sukces! Wydarzenie zostało dodane",
                'update' => "Sukces! Wydarzenie został zedytowane"
            ],
            'Image' => [
                'remove' => "Obraz " . $objectName . " został usunięty",
                'create' => "Sukces! Obraz " . $objectName . " został dodany",
                'update' => "Sukces! Obraz " . $objectName . " został zedytowany"
            ],
            'User' => [
                'remove' => "Użytkownik " . $objectName . " został usunięty",
                'create' => "Sukces! Użytkownik " . $objectName . " został dodany",
                'update' => "Sukces! Użytkownik " . $objectName . " został zedytowany"
            ],
            'Relation' => [
                'remove' => "Sukces, przypisanie obrazów " . $objectName . " zostało usunięte",
                'add' => "Sukces! Obrazy " . $objectName . " zostały przypisane",
                'relationError1' => "Należy wybrać przynajmniej jedno zdjęcie"
            ]
        ];

        return $messages[$object][$action];
    }

}