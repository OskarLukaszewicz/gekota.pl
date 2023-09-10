<?php

namespace App\Exception;

use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

use Throwable;

class ActionNotFoundException extends NotFoundHttpException
{
    public function __construct(
        string $message = "",
        Throwable $previous = null,
        int $code = 0
    ) {
        parent::__construct($message, $previous, 0 );
    }
}