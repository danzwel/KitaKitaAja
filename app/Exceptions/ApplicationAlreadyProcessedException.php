<?php

namespace App\Exceptions;

use Exception;

class ApplicationAlreadyProcessedException extends Exception
{
    public function __construct(string $message = 'Pengajuan ini sudah diproses sebelumnya.')
    {
        parent::__construct($message);
    }
}
