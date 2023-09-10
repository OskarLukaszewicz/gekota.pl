<?php

namespace App\Exception;

use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

use Throwable;

class ObjectByIdNotFoundException extends NotFoundHttpException
{
    public function __construct(
        string $object ="",
        string $id = "",
        int $code = 0,
        Throwable $previous = null
    ) {
        parent::__construct('Obiekt typu ' . $object . ' o id ' . $id . ' nie został znaleziony.', $previous, 0 );
    }
}