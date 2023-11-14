<?php

namespace WilliamCastro\ApiFacebookConversions\Exceptions;

use Exception;

class EmptyAccessTokenException extends Exception
{
    public function __construct($message = 'Empty facebook `acess_token`')
    {
        parent::__construct($message, 1);
    }
}