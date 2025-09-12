<?php

namespace App\Module\Field\Exception;


use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Throwable;

class UniqueFieldException extends HttpException
{
    public function __construct(string $message = "Conflict", Throwable $previous = null)
    {
        parent::__construct(Response::HTTP_CONFLICT, $message, $previous);
    }
}