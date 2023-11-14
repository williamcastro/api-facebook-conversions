<?php

namespace WilliamCastro\ApiFacebookConversions\Exceptions;

use Exception;

class EmptyEventPayloadException extends Exception
{
    public function __construct($message = 'Empty event `payload`')
    {
        parent::__construct($message, 3);
    }
}