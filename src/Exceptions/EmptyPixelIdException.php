<?php
namespace WilliamCastro\ApiFacebookConversions\Exceptions;

use Exception;

class EmptyPixelIdException extends Exception
{
    public function __construct($message = 'Empty facebook `pixelId`')
    {
        parent::__construct($message, 2);
    }
}